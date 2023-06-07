<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penempatan extends CI_Controller
{
    private $d = array(
        "tbName" => "penempatan",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Penempatan",
            "parent" => array(""),
            "modul" => "app/penempatan",
            "view"  => "app",
            "page"  => "Penempatan",
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
        $this->load->library('Dataweb');
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/penempatan";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/penempatan.js?' . date("ymdhis")),
        );

        $this->d['mstjabatan'] = $this->dataweb->datajabatan([], null, null, "DESC");

        $this->load->view('app/index', $this->d);
    }

    public function daftar($idkkn = null)
    {
        $this->load->library('Dataweb');
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");

        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("myapp"),
        );

        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
        );
        $datakkn = $this->dataweb->cariKkn($vCari);
        if (!$datakkn['status'] || !$idkkn) {
            redirect("web");
        }
        $this->d['datakkn'] = $datakkn['db'][0];
        $this->load->view('app/daftar', $this->d);
    }


    public function bagikelompok($idkkn = null)
    {
    }

    public function read()
    {
        $this->load->library('Datatables');
        $this->load->library('Dataweb');

        $idkkn = $this->input->post('idkkn');

        //cari peserta KKN
        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
        );
        $pesertakkn = $this->dataweb->pesertakkn($vCari);
        //akhir cari KKN aktif

        $this->datatables->from("kelompok as k");
        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, '' as peserta,
            k.id as idkelompok,k.keterangan,k.namakelompok,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as dsnpembimbing, u.hp, u.nik, u.kel, u.path as profilpic, u.email,
            dsa.id as iddesa,
            kec.id as idkecamatan,
            kab.id as idkabupaten,
            sp.idkkn,
            prov.id as idprovinsi,
            dsa.desa, kec.kecamatan, kab.kabupaten, prov.provinsi,
            pm.id as idpenempatan,pm.idpeserta,pm.idjabatan,
		");

        $this->datatables->join("penempatan as pm", "pm.idkelompok=k.id", "left");
        $this->datatables->join("lokasi as l", "l.id=k.idlokasi", "left");

        $this->datatables->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->datatables->join("wilayah_kec as kec", "kec.id=dsa.idkecamatan", "left");
        $this->datatables->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->datatables->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");

        $this->datatables->join("pembimbing_kkn as pk", "pk.id=k.idpembimbing_kkn", "left");
        $this->datatables->join("pembimbing as p", "p.id=pk.idpembimbing", "left");
        $this->datatables->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "left");

        $this->datatables->join("hakakses as h", "p.idhakakses=h.id", "left");
        $this->datatables->join("user as u", "u.id=h.iduser", "left");
        //$this->datatables->join("user as u", "u.id=p.iduser", "left");

        $this->datatables->where("sp.idkkn", $idkkn);
        $this->datatables->group_by("k.id");

        /*
            mhs.id as mhs_iduser, TRIM(CONCAT(mhs.glrdepan,' ',mhs.nama,' ',mhs.glrbelakang)) as mhs_nama, mhs.nik as mhs_nik, mhs.kel as mhs_kel, mhs.profilpic as mhs_foto, mhs.hp as mhs_hp, mhs.email as mhs_email,
            m.nim,prodi.prodi, fak.fakultas,

        $this->datatables->join("peserta as s", "s.id=p.idpeserta", "left");
        $this->datatables->join("pendaftar as r", "r.id=s.idpendaftar", "left");
        $this->datatables->join("mahasiswa as m", "m.id=r.idmahasiswa", "left");
        $this->datatables->join("user as mhs", "mhs.id=m.iduser", "left");
        $this->datatables->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->datatables->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");
        */

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' data-pilih='" . $no . "' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['idkelompok'] . "'>";
            $tmp['no'] = $no;
            $tmp['fldview'] = "<div class='row'>
                                    <div class='col-md-3'>
                                        <div class='avatar avatar-xl' style='vertical-align: top;'><img src='" . base_url($dp['profilpic']) . "' ></div>                                        
                                    </div>
                                    <div class='col-md-9'>
                                        <span class='badge bg-primary'>Kelompok " . $dp['namakelompok'] . "</span>
                                        <h5 style='margin-top:10px;margin-bottom:0px;'>" . $dp['dsnpembimbing'] . "</h5>
                                        <span style='font-size:12px'>" . $dp['email'] . "</span> 
                                        <div style='font-size:12px'>
                                            <span class='badge bg-success'>" . strtoupper($dp['desa']) . "</span>
                                            <span class='badge bg-success'>" . strtoupper($dp['kecamatan']) . "</span>
                                            <span class='badge bg-success'>" . $dp['kabupaten'] . "</span>
                                            <span class='badge bg-success'>" . $dp['provinsi'] . "</span>
                                        </div>    
                                    </div>";
            $tmp['dsnpembimbing'] = $dp['dsnpembimbing'];
            $tmp['namakelompok'] = $dp['namakelompok'];
            $tmp['desa'] = $dp['desa'];
            $tmp['kecamatan'] = $dp['kecamatan'];
            $tmp['kabupaten'] = $dp['kabupaten'];
            $tmp['provinsi'] = $dp['provinsi'];

            $dtmhs = searchMultiArray($pesertakkn, "idkelompok", $dp['idkelompok']);
            $dtpeserta = "Belum Ada";
            if (count($dtmhs) > 0) {
                $dtpeserta = "<ul>";
                foreach ($dtmhs as $i => $dt) {
                    $btnprop = "<a href='#' class='btn btn-success btn-sm rounded-pill gantiJabatan' data-id='" . $dt['idpenempatan'] . "'  href='#' title='ganti jabatan'><i class='bi bi-box-arrow-in-down-right'></i></a>
                                <a href='#' class='btn btn-success btn-sm rounded-pill gantiKelompok' data-namapeserta='" . $dt['nama'] . "' data-idpeserta='" . $dt['idpeserta'] . "' href='#' title='pindah kelompok'><i class='bi bi-box-arrow-right'></i></a>
                                <a href='#' class='btn btn-danger btn-sm rounded-pill deletePenempatan' data-id='" . $dt['idpenempatan'] . "' title='hapus penempatan'><i class='bi bi-trash'></i></a>";
                    $dtpeserta .= "<li><b>" . $dt['nama'] . " (" . $dt['kel'] . ")</b><div style='font-size:11px'>Nim. " . $dt['nim'] . ", Prodi. " . $dt['prodi'] . " (" . $dt['jabatan'] . ") " . $btnprop . "</div></li>";
                }
                $dtpeserta .= "</ul>";
            }
            $tmp['peserta'] = $dtpeserta;

            $tmp['aksi'] = "<a class='btn btn-primary penempatanRow' data-pilih='" . $no . "' data-idpenempatan='" . $dp['idpenempatan'] . "'  data-idkelompok='" . $dp['idkelompok'] . "' data-idkkn='" . $dp['idkkn'] . "' href='#'><i class='bi bi-diagram-3'></i></a>";
            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }

    public function readpeserta()
    {
        $this->load->library('Datatables');
        $this->load->library('Dataweb');

        $idkkn = $this->input->post('idkkn');

        $mstjabatan = $this->dataweb->datajabatan([], null, null, "DESC");

        $this->datatables->from("peserta as p");
        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, '' as peserta,
            p.id as idpeserta,pn.id as idpendaftar,mhs.id as idmahasiswa,u.id as userid,
            mhs.nim,
            TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path as profilpic, u.email,
            prd.id as idprodi, prd.prodi, fak.id as idfakultas, fak.fakultas,
            d.id as iddesa, d.desa,pn.idkkn,
		");

        $this->datatables->join("penempatan as pt", "pt.idpeserta=p.id", "left");
        $this->datatables->join("pendaftar as pn", "pn.id=p.idpendaftar", "left");
        $this->datatables->join("mahasiswa as mhs", "mhs.id=pn.idmahasiswa", "left");
        $this->datatables->join("mst_prodi as prd", "mhs.idprodi=prd.id", "left");
        $this->datatables->join("mst_fakultas as fak", "fak.id=prd.idfakultas", "left");
        $this->datatables->join("user as u", "u.id=mhs.iduser", "left");
        $this->datatables->join("wilayah_desa as d", "d.id=u.iddesa", "left");

        $this->datatables->where("pn.idkkn", $idkkn);
        $this->datatables->where("pt.idpeserta IS NULL", null);

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris2'  data-pilih='" . $no . "' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['idpeserta'] . "'>";
            $tmp['no'] = $no;
            $tmp['fldview'] = "<div class='row'>
                                    <div class='col-md-2'>
                                        <div class='avatar avatar-xl' style='vertical-align: top;'><img src='" . base_url($dp['profilpic']) . "' ></div>                                        
                                    </div>
                                    <div class='col-md-10'>
                                        <b>" . $dp['nama'] . "</b></br>
                                        NIM. " . $dp['nim'] . "</br>
                                        " . $dp['prodi'] . "</br>
                                        " . strtoupper($dp['desa']) . "
                                    </div>    
                                </div>";
            $tmp['selectjabatan'] = $this->htmlselectjabatan($mstjabatan, $no);
            $tmp['nama'] = $dp['nama'];
            $tmp['nim'] = $dp['nim'];
            $tmp['prodi'] = $dp['prodi'];
            $tmp['desa'] = $dp['desa'];
            $tmp['aksi'] = "<div class='buttons'>
                                <a href='#' class='btn btn-success rounded-pill insertPeserta' data-pilih='" . $no . "' data-idpeserta='" . $dp['idpeserta'] . "'data-idkkn='" . $dp['idkkn'] . "'  href='#'><i class='bi bi-box-arrow-in-right'></i></a>
                            </div>";

            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }

    public function cari()
    {
        $retVal = $this->retVal;
        $vCari = $this->input->post('vCari');
        $vField = $this->input->post('vField');

        //debug($vCari);
        if (count($vCari) > 0) {
            $this->db->from("peserta as p");
            $this->db->select(
                "
                p.id as idpeserta,pn.id as idpendaftar,mhs.id as idmahasiswa,u.id as userid,
                mhs.nim,
                TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path as profilpic, u.email,
                prd.id as idprodi, prd.prodi, fak.id as idfakultas, fak.fakultas,
                d.id as iddesa, d.desa,pn.idkkn, " . $vField
            );
            $this->db->join("pendaftar as pn", "pn.id=p.idpendaftar", "left");
            $this->db->join("mahasiswa as mhs", "mhs.id=pn.idmahasiswa", "left");
            $this->db->join("mst_prodi as prd", "mhs.idprodi=prd.id", "left");
            $this->db->join("mst_fakultas as fak", "fak.id=prd.idfakultas", "left");
            $this->db->join("user as u", "u.id=mhs.iduser", "left");
            $this->db->join("wilayah_desa as d", "d.id=u.iddesa", "left");
            foreach ($vCari as $val => $cariSql) {
                //debug($cariSql);
                if ($cariSql['cond'] == "like")
                    $this->db->like($cariSql['fld'], $cariSql['val'], "both");
                else
                    $this->db->where($cariSql['fld'], $cariSql['val']);
            }
            $this->db->order_by("u.nama ASC");
            $sql = $this->db->get();
            if ($sql->num_rows() > 0) {
                $retVal['status'] = true;
                $retVal['db'] = $sql->result_array();
            }
        }
        die(json_encode($retVal));
    }



    public function deletepenempatan()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $id = $this->input->post('idTerpilih');
        if (count($id) > 0) {
            $pesan = [];
            foreach ($id as $i => $dp) {
                $run = akses_akun("delete", $this->otentikasi, $this->d['tbName'], $dp);
                if ($run->status) {
                    $kond = array(
                        array("where", "idkelompok", $dp),
                    );
                    $runquery = $this->Model_data->delete($kond, $this->d['tbName'], null, true);
                    $pesan[] = $runquery['pesan'];
                }
            }
            $retVal['status'] = true;
            $retVal['pesan'] = $pesan;
        }
        die(json_encode($retVal));
    }

    public function delete()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $id = $this->input->post('idTerpilih');
        if (count($id) > 0) {
            $pesan = [];
            foreach ($id as $i => $dp) {
                $run = akses_akun("delete", $this->otentikasi, $this->d['tbName'], $dp);
                if ($run->status) {
                    $kond = array(
                        array("where", "id", $dp),
                    );
                    $runquery = $this->Model_data->delete($kond, $this->d['tbName'], null, true);
                    $pesan[] = $runquery['pesan'];
                }
            }
            $retVal['status'] = true;
            $retVal['pesan'] = $pesan;
        }
        die(json_encode($retVal));
    }
    public function insertpeserta()
    {
        $retVal = array("status" => false, "pesan" => []);
        allowheader();

        $formVal = $this->input->post('formVal');
        if (count($formVal) > 0) {
            foreach ($formVal as $i => $dp) {
                $dataSave = array(
                    "idkelompok" => $dp["idkelompok"],
                    "idjabatan" => $dp["idjabatan"],
                    "idpeserta" => $dp["idpeserta"],
                );
                if (akses_akun("insert", $this->otentikasi)->status) {
                    $ret = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
                    $pesan['pesan'][] = $ret['pesan'];
                } else {
                    $pesan['pesan'][] = ["Maaf, akses ditolak"];
                }
            }
            $retVal['status'] = true;
            $retVal['pesan'] = $pesan;
        } else {
            $retVal['pesan'] = ["gagal dilakukan, hubungi admin!"];
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }

    public function updatejabatan()
    {
        $retVal = array("status" => false, "pesan" => []);
        allowheader();

        $this->form_validation->set_rules('idpenempatan', 'penempatan', 'trim|required');
        $this->form_validation->set_rules('idjabatanganti', 'jabatan', 'trim|required');

        if ($this->form_validation->run()) {
            $id = $this->input->post("idpenempatan");
            $dataSave = array(
                "idjabatan" => $this->input->post("idjabatanganti"),
            );
            if (akses_akun("update", $this->otentikasi, $this->d['tbName'], $id)->status) {
                $kond = array(
                    array("where", $this->d['primaryKey'], $id),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, $this->d['tbName'], null, true);
            } else
                $retVal['pesan'] = ["Maaf, akses ditolak"];
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }

    public function htmlselectjabatan($data = null, $i = null)
    {
        $this->load->library('Dataweb');

        $retVal = "<select class='form-select' id='idjabatan" . $i . "' name='idjabatan' >";
        if ($data['status'])
            foreach ($data['db'] as $ind => $dp) {
                $retVal .= "<option value='" . $dp['id'] . "'>" . $dp['jabatan'] . "</option>";
            }
        $retVal .= "</select>";

        return $retVal;
    }

    public function updatekelompok()
    {
        $retVal = array("status" => false, "pesan" => []);
        allowheader();

        $this->form_validation->set_rules('idpesertaganti', 'mahasiswa', 'trim|required');
        $this->form_validation->set_rules('idkelompokganti', 'kelompok tujuan', 'trim|required');

        if ($this->form_validation->run()) {
            $idpesertaganti = $this->input->post("idpesertaganti");
            $idkelompokganti = $this->input->post("idkelompokganti");

            $vCari = array(
                array("where", "idpeserta", $idpesertaganti),
            );
            $cariPeserta = $this->Model_data->searchData($vCari, "penempatan", "id");
            $id = null;
            if ($cariPeserta['db']->num_rows() > 0)
                $id = $cariPeserta['db']->row()->id;

            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $dataSave = array(
                    "idkelompok" => $idkelompokganti,
                    "idpeserta" => $idpesertaganti,
                    "idjabatan" => 6,
                );
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
            } elseif ($id != "" && akses_akun("update", $this->otentikasi, $this->d['tbName'], $id)->status) {
                $dataSave = array(
                    "idkelompok" => $idkelompokganti,
                );
                $kond = array(
                    array("where", "idpeserta", $idpesertaganti),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, $this->d['tbName'], null, true);
            } else
                $retVal['pesan'] = ["Maaf, akses ditolak"];
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }

    public function loadkelompok()
    {
        allowheader();
        $this->load->library('Dataweb');
        $cari = array(
            array("cond" => "where", "fld" => "sp.idkkn", "val" => $this->input->post('idkkn')),
        );
        $retVal = $this->dataweb->daftarkelompok($cari);
        die(json_encode($retVal));
    }
}
