<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftar extends CI_Controller
{
    private $d = array(
        "tbName" => "berkas_administrasi",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Pendaftar",
            "parent" => array("pengaturan"),
            "modul" => "app/pendaftar",
            "view"  => "app",
            "page"  => "Pendaftar",
        ),
    );
    private $retVal = array("status" => false, "pesan" => []);

    function __construct()
    {
        parent::__construct();
        $this->otentikasi = otentikasi($this->d);
    }

    public function index()
    {
        $this->load->library("select2");
        $this->d['web']['loadview'] = $this->d['web']['view'] . "/pendaftar";
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");

        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        //$d['pembimbing'] = $this->datapembimbing();
        $this->d['web']['importJs'] = array(
            base_url('assets/web/pendaftar.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }

    public function administrasi_kkn($idkkn = null)
    {

        $this->db->from("administrasi as a");
        $this->db->select("a.*");
        $this->db->where("a.idkkn", $idkkn);

        $runsql = $this->db->get();
        return $runsql->num_rows();
    }

    public function cekPeserta($idkkn = null, $idmhs = [])
    {
        $this->db->from("pendaftar as p");
        $this->db->select(" 
            k.tema,k.tahun,
            m.nim, m.id as idmhs,
            if(t.id IS NOT NULL,'peserta','pendaftar') as status,
            t.id as idpeserta,
		");
        $this->db->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");

        $this->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->db->join("peserta as t", "t.idpendaftar=p.id", "left");
        $this->db->where("YEAR(p.created)", date('Y'));
        $this->db->where("p.idkkn!=", $idkkn);
        $this->db->where("t.id IS NOT NULL", null);
        $this->db->where_in("m.id", $idmhs);

        $runsql = $this->db->get();
        $tmp = [];

        if ($runsql->num_rows() > 0) {
            $no = 1;
            foreach ($runsql->result_array() as $index => $dp) {
                $no++;
                $tmp[$dp['idmhs']] = [
                    "tahun" => $dp['tahun'],
                    "tema" => $dp['tema'],
                    "nim" => $dp['nim'],
                    "idmhs" => $dp['idmhs'],
                    "idpeserta" => $dp['idpeserta'],
                    "status" => $dp['status'],
                ];
            }
        }
        return $tmp;
    }

    public function read()
    {
        $this->load->library('Datatables');
        $this->load->library("dataweb");

        $idkkn = $this->input->post('idkkn');
        $statumhs = $this->input->post('statumhs');
        $iduser = $this->session->userdata('iduser');

        $administrasi_kkn = $this->administrasi_kkn($idkkn);
        $data_verifikasi = $this->data_verifikasi($idkkn);

        //$prodiakses = $this->dataweb->prodiakses($idkkn, $iduser)['db'];
        $this->datatables->from("pendaftar as p");
        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            p.id as idpendaftar,p.idkkn,
            u.id as iduser, CONCAT(TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)),'/ ',m.nim) as nama, 
            CONCAT(u.hp,'/ ',u.email) as kontak, u.nik, u.kel, u.path as profilpic, u.hp, u.email,
            m.nim,prodi.prodi, fak.fakultas,
            m.id as idmhs,
            if(t.id IS NOT NULL,'peserta','pendaftar') as status,
            t.id as idpeserta,
            prodi.urut,fak.id as idfakultas,
		");
        $this->datatables->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        // $this->datatables->join("peserta as ps", "ps.idpendaftar=p.id", "left");
        $this->datatables->join("user as u", "u.id=m.iduser", "left");
        $this->datatables->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->datatables->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");

        $this->datatables->join("kkn as k", "k.id=p.idkkn", "left");
        $this->datatables->join("peserta as t", "t.idpendaftar=p.id", "left");
        $this->datatables->where("p.idkkn", $idkkn);
        $this->datatables->where("m.nim IS NOT NULL", null);
        if ($this->session->userdata('role') != 1) {
            if (isset($prodiakses[$iduser])) {
                $listprodi = [];
                // debug($prodiakses);
                foreach ($prodiakses[$iduser] as $i => $dp) {
                    $listprodi[] = $dp['idprodi'];
                }
                $this->datatables->where_in("prodi.id", $listprodi);
            } else {
                $this->datatables->where("p.idkkn", 0);
            }
        }

        switch ($statumhs) {
            case 'peserta':
                $this->datatables->where("t.id IS NOT NULL", null);
                break;
            case 'pendaftar':
                $this->datatables->where("t.id IS NULL", null);
                break;
        }

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;

        $idmhs = [];
        $datapeserta = [];
        if (count($retVal['data']) > 0) {
            foreach ($retVal['data'] as $index => $dp) {
                $idmhs[] = $dp['idmhs'];
            }
            $datapeserta = $this->cekPeserta($idkkn, $idmhs);
        }

        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['no'] = $no;
            $tmp['foto'] = "<div class='avatar avatar-xl'><img src='" . base_url($dp['profilpic']) . "' ></div>";
            $tmp['nama'] = $dp['nama'];
            $tmp['kel'] = $dp['kel'];
            $tmp['urut'] = $dp['urut'];
            $tmp['nim'] = $dp['nim'];
            $tmp['idfakultas'] = $dp['idfakultas'];
            $tmp['fakultas'] = $dp['fakultas'] . "/ " . $dp['prodi'];

            $pesertaKknLain = isset($datapeserta[$dp['idmhs']]) ? $datapeserta[$dp['idmhs']] : [];
            $verifikasi = searchMultiArray($data_verifikasi, "idpendaftar", $dp['idpendaftar']);

            if (count($pesertaKknLain) > 0) {
                $labelVer = '<div>
                                <span class="badge bg-black">Peserta</span>
                                <span class="badge bg-black">' . $pesertaKknLain['tema'] . '</span>
                            </div>';
            } else {
                $labelVer = '<div><span class="badge bg-secondary">Belum Lengkap</span>';
                if (count($verifikasi) > 0) {
                    $verifikasi = $verifikasi[0];
                    if ($verifikasi['jumver'] >= $administrasi_kkn) {
                        $labelVer = '<span class="badge bg-primary">Sudah Verifikasi</span>';
                    } else
                        $labelVer = '<span class="badge bg-info">Belum Verifikasi</span>';
                }
                if ($dp['idpeserta'] > 0)
                    $labelVer .= '<span class="badge bg-success">Peserta</span>';
                else
                    $labelVer .= '<span class="badge bg-danger">Bukan Peserta</span>';
                //$labelVer .= ' Memenuhi Syarat';
                $labelVer .= '</div>';
            }
            //$labelVer = '<span class="badge bg-primary">' . $labelVer . '</span>';

            //$tmp['status'] = $labelVer; // . " " . print_r($verifikasi);
            $tmp['kontak'] = $labelVer . "<br>" . kirimwa($dp['hp'], true) . "/ " . $dp['email'];

            $tmp['aksi'] = "<div class='btn-group me-1 mb-1'>
                                <div class='dropdown'>
                                    <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                    <div class='dropdown-menu dropdown-menu-end' style=''>
                                        <a class='dropdown-item verRow' data-pilih='" . $no . "'    data-iduser='" . $dp['iduser'] . "' data-idpeserta='" . $dp['idpeserta'] . "' data-idpendaftar='" . $dp['idpendaftar'] . "'  data-idkkn='" . $dp['idkkn'] . "' href='#'><i class='bi bi-check-lg'></i> Verifikasi</a>
                                        <a class='dropdown-item deleteRow' data-pilih='" . $no . "' data-iduser='" . $dp['iduser'] . "' data-idpeserta='" . $dp['idpeserta'] . "' data-idpendaftar='" . $dp['idpendaftar'] . "' data-idkkn='" . $dp['idkkn'] . "' href='#'><i class='bi bi-trash'></i> Hapus</a>
                                    </div>
                                </div>
                            </div>";
            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }

    public function cari()
    {
        $retVal = $this->retVal;
        $vCari = $this->input->post();

        if (count($vCari) > 0) {
            $this->db->from("administrasi");
            $this->db->select("*");

            foreach ($vCari as $val => $cariSql) {
                if ($cariSql['cond'] == "like")
                    $this->db->like($cariSql['fld'], $cariSql['val'], "both");
                else
                    $this->db->where($cariSql['fld'], $cariSql['val']);
            }
            $this->db->order_by("namaadministrasi ASC");
            $sql = $this->db->get();
            if ($sql->num_rows() > 0) {
                $retVal['status'] = true;
                $retVal['db'] = $sql->result_array();
            }
        }
        die(json_encode($retVal));
    }

    public function statistik($idkkn = null, $statusmhs = null)
    {
        $d['idkkn'] = $idkkn;
        $d['statusmhs'] = $statusmhs;
        $this->load->view('statistik/pendaftar', $d);
    }

    public function getStatistik()
    {
        $retVal = $this->retVal;

        $this->load->library("dataweb");
        $idkkn = $this->input->post('idkkn');
        $statusmhs = $this->input->post('statusmhs');
        $iduser = $this->session->userdata('iduser');

        //$prodiakses = $this->dataweb->prodiakses($idkkn, $iduser)['db'];
        $this->db->from("pendaftar as p");
        $this->db->select("
            prodi.prodi, fak.fakultas,
            SUM(if(u.kel='L',1,0)) as pria,
            SUM(if(u.kel='P',1,0)) as wanita,
            COUNT(p.id) as jumlah,
		");
        $this->db->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        $this->db->join("peserta as ps", "ps.idpendaftar=p.id", "left");
        $this->db->join("user as u", "u.id=m.iduser", "left");
        $this->db->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->db->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");

        $this->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->db->join("peserta as t", "t.idpendaftar=p.id", "left");
        $this->db->where("p.idkkn", $idkkn);
        $this->db->group_by("p.idkkn");
        $this->db->group_by("prodi.id");
        $this->db->order_by("fak.id", "ASC");
        $this->db->order_by("prodi.urut", "ASC");

        if ($this->session->userdata('role') != 1) {
            if (isset($prodiakses[$iduser])) {
                $listprodi = [];
                foreach ($prodiakses[$iduser] as $i => $dp) {
                    $listprodi[] = $dp['idprodi'];
                }
                $this->db->where_in("prodi.id", $listprodi);
            } else {
                $this->db->where("p.idkkn", 0);
            }
        }

        switch ($statusmhs) {
            case 'peserta':
                $this->db->where("ps.id IS NOT NULL", null);
                break;
            case 'pendaftar':
                $this->db->where("ps.id IS NULL", null);
                break;
        }

        $sql = $this->db->get();
        if ($sql->num_rows() > 0) {
            $retVal['status'] = true;
            $retVal['db'] = $sql->result_array();
        }
        die(json_encode($retVal));
    }

    public function delete()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $idpeserta = $this->input->post('idpeserta');
        $idpendaftar = $this->input->post('idpendaftar');
        $run = akses_akun("delete", $this->otentikasi, "peserta", $idpeserta);
        if ($run->status) {

            $kond = array(
                array("where", "id", $idpeserta),
            );
            $retVal = $this->Model_data->delete($kond, "peserta", null, true);

            if ($retVal['status']) {
                $updateSQL = "UPDATE berkas_administrasi SET status=null WHERE idpendaftar='" . $idpendaftar . "'";
                $this->Model_data->runQuery($updateSQL);
            }
        }
        die(json_encode($retVal));
    }

    /*
    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        allowheader();

        $this->form_validation->set_rules('idkkn', 'pilihan KKN', 'trim|required');
        $this->form_validation->set_rules('namaadministrasi', 'jenis administrasi', 'trim|required');
        if ($this->input->post('upload_file') == 'y') {
            $this->form_validation->set_rules('upload_type', 'type file upload', 'trim|required');
            $this->form_validation->set_rules('upload_size', 'ukurang file', 'trim|required');
        }
        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idadministrasi'];
            unset($dataSave['idadministrasi']);

            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
            } elseif ($id <> "" && akses_akun("update", $this->otentikasi, $this->d['tbName'], $id)->status) {
                $kond = array(
                    array("where", $this->d['primaryKey'], $id),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, $this->d['tbName'], null, true);
            } else {
                $retVal['pesan'] = "Maaf, akses ditolak";
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }
    */

    public function hapus_verifikasiberkas()
    {
        $retVal = array("status" => false, "pesan" => ["gagal dilakukan, hubungi admin!"], "login" => true);
        $idpendaftar = $this->input->post('idpendaftar');

        if ($idpendaftar > 0) {
            $dataSave = [
                'status' => null,
            ];
            $kond = array(
                array("where", "idpendaftar", $idpendaftar),
            );
            $retVal = $this->Model_data->update($kond, $dataSave, "berkas_administrasi", "verifikasi administrasi", true);
        }

        die(json_encode($retVal));
    }


    public function update_verifikasiberkas()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        allowheader();
        $this->load->library("dataweb");

        $this->form_validation->set_rules('idkkn', 'Jenis KKN', 'trim|required');
        $this->form_validation->set_rules('idpendaftar', 'Pendaftar KKN', 'trim|required');

        $idkkn = $this->input->post("idkkn");
        $iduser = $this->input->post("iduser");
        $dataform = dataSerialize($this->input->post("dataform"));
        $idpendaftar = $this->input->post("idpendaftar");
        $vstatus = null;
        $statuspeserta = $dataform['statuspeserta'];

        if ($this->form_validation->run()) {
            $idupload = isset($dataform['idupload']) ? $dataform['idupload'] : null;
            $idadministrasi = isset($dataform['idadministrasi']) ? $dataform['idadministrasi'] : null;
            $vstatus = "MS";
            if ($idupload && $idadministrasi)
                foreach ($dataform['verBerkas'] as $i => $dp) {
                    if ($dp <> "MS")
                        $vstatus = "TMS";

                    $id = $idupload[$i];
                    $dataSave = array(
                        "idadministrasi" => $idadministrasi[$i],
                        "idpendaftar" => $idpendaftar,
                        "status" => $dp,
                    );

                    if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                        /*
                    if ($ispeserta) {
                        $retVal['pesan'] = ["Maaf, mahasiswa an. " . $pesertadt['nama'] . " pada tahun " . $pesertadt['tahun'] . " sudah menjadi peserta pada KPM " . $pesertadt['tema']];
                        $retVal['status'] = false;
                        die(json_encode($retVal));
                    }
                    */

                        $retVal = $this->Model_data->save($dataSave, "berkas_administrasi", "verifikasi administrasi", true);
                    } elseif ($id <> "" && akses_akun("update", $this->otentikasi, "berkas_administrasi", $id)->status) {
                        $kond = array(
                            array("where", "id", $id),
                        );
                        $retVal = $this->Model_data->update($kond, $dataSave, "berkas_administrasi", "verifikasi administrasi", true);
                    } else {
                        $retVal['pesan'] = "Maaf, akses ditolak";
                    }
                }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }

        if ($statuspeserta) {


            $dbpeserta = $this->dataweb->status_peserta(null, null, $iduser);
            $simpan = true;
            if ($dbpeserta['status']) {
                $detpeserta = $dbpeserta['db'][0];
                // echo $idpendaftar . " " . $detpeserta['idpendaftar'];
                // die;
                if ($detpeserta['idpendaftar'] != $idpendaftar) {
                    $retVal['pesan'] = ["Gagal menjadi peserta karena telah terdaftar sebagai peserta pada " . $detpeserta['tema'] . "! hapus terlebih dahulu pada kkn sebelumnya"];
                    $retVal['status'] = false;
                    die(json_encode($retVal));
                } elseif (!$detpeserta['idpeserta']) {
                    $simpan = true;
                } else {
                    $simpan = false;
                }
            }

            if ($simpan) {
                $dataSave = array(
                    "idpendaftar" => $idpendaftar,
                );
                $retVal = $this->Model_data->save($dataSave, "peserta", "penetapan peserta", true);
            } else {
                // echo "tidak ada";
            }
        } else {
            $kond = array(
                array("where", "idpendaftar", $idpendaftar),
            );
            $retVal = $this->Model_data->delete($kond, "peserta", null, true);
        }

        die(json_encode($retVal));
    }

    public function update_status($idpendaftar = null, $vstatus = null, $iduser = null)
    {
        $detpeserta = array();

        $this->load->library("dataweb");
        $this->db->from("peserta as p");
        $this->db->select("p.id");
        $this->db->where("p.idpendaftar", $idpendaftar);
        $sql = $this->db->get();
        $insert = true;
        $id = null;
        if ($sql->num_rows() > 0) {
            $id = $sql->row()->id;
        }

        if ($vstatus == "MS") {

            $dbpeserta = $this->dataweb->cek_peserta(null, $idpendaftar, $iduser);

            if ($dbpeserta['status']) {
                $detpeserta = $dbpeserta['db'][0];
                return $detpeserta;
            }

            $kond = array(
                array("where", "id", $id),
            );
            $dataSave = array(
                "idpendaftar" => $idpendaftar,
            );
            if ($insert && akses_akun("insert", $this->otentikasi)->status) {
                //berkas_administrasi;    
                $this->Model_data->save($dataSave, "peserta", "verifikasi administrasi", true);
            } elseif (!$insert && akses_akun("update", $this->otentikasi, "berkas_administrasi", $id)->status) {
                $this->Model_data->update($kond, $dataSave, "peserta", "verifikasi administrasi", true);
            }
        } else {
            $kond = array(
                array("where", "idpendaftar", $idpendaftar),
            );
            $this->Model_data->delete($kond, "peserta", null, true);
        }
        return $detpeserta;
        //echo $this->db->last_query();
    }

    public function data_verifikasi($idkkn = null)
    {
        $retVal = [];
        $this->db->from("berkas_administrasi as ba");
        $this->db->select(" ps.id as idpeserta, SUM(IF(ba.status IS NOT NULL,1,0))as jumver,
                            ba.idpendaftar,
                            if(ps.id IS NOT NULL,1,0) as ispeserta,
                        ");
        $this->db->join("pendaftar as p", "p.id=ba.idpendaftar", "left");
        $this->db->join("peserta as ps", "ps.idpendaftar=p.id", "left");
        $this->db->where("p.idkkn", $idkkn);
        $this->db->group_by("ba.idpendaftar");
        $this->db->order_by("ba.idpendaftar ASC");
        $sql = $this->db->get();
        if ($sql->num_rows() > 0) {
            $retVal = $sql->result_array();
        }
        //debug($retVal);
        return $retVal;
    }
}
