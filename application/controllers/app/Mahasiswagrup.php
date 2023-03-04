<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswagrup extends CI_Controller
{
    private $d = array(
        "tbName" => "hakakses",
        "idgrup" => "4",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Akun Mahasiswa",
            "parent" => array("pengaturan"),
            "modul" => "app/mahasiswagrup",
            "view"  => "app",
            "page"    => "Admin",
        ),
    );
    private $retVal = array("status" => false, "pesan" => "");

    function __construct()
    {
        parent::__construct();
        $this->otentikasi = otentikasi($this->d);
    }

    public function index()
    {
        $this->load->library("select2");
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/mahasiswagrup";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("datetime"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );
        $this->d['web']['importJs'] = array(
            base_url('assets/web/app/mahasiswagrup.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }

    public function cariakun()
    {
        $this->load->library('dataweb');
        $cari = $this->input->post('cari');
        $cond = array(
            array("cond" => "where", "fld" => "(u.nama LIKE '%" . $cari . "%' OR u.email LIKE '%" . $cari . "%')", "val" => null),
        );

        $dataakun = $this->dataweb->cariUser_new($cond);
        $retVal = [];
        if ($dataakun['status'])
            foreach ($dataakun['db'] as $data => $dp) {
                $retVal[] = array("id" => $dp['id'], "text" => $dp['nama'] . " (" . $dp['email'] . ")");
            }
        die(json_encode($retVal));
    }

    public function read()
    {
        $this->load->library('Datatables');

        $filterTables = simpleSerializeArray($this->input->post('filterTables'));

        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            a.id, a.iduser, a.idgrup, a.nim, a.idprodi,
            h.id as idhakakses,h.aktivasi,h.token,
            m.prodi,
            TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama,
            u.nama as namaasli,
            u.email, u.nik,u.kel,u.tmplahir,u.tgllahir,u.alamat,u.hp,u.institusi,u.path,u.aktivasi as aktivasi_user,
		");

        $this->datatables->from("hakakses as h");
        $this->datatables->where("h.idgrup", $this->d['idgrup']);
        $this->datatables->join("mahasiswa as a", "h.id=a.idhakakses", "left");
        $this->datatables->join("mst_prodi as m", "m.id=a.idprodi", "left");
        $this->datatables->join("user as u", "u.id=h.iduser", "left");
        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['idhakakses'] . "'>";
            $tmp['no'] = $no;
            $tmp['nama'] = $dp['nama'];
            $tmp['email'] = $dp['email'];
            $tmp['nim'] = $dp['nim'];
            $tmp['prodi'] = $dp['prodi'];
            $tmp['idprodi'] = $dp['idprodi'];
            $status = "Belum Aktif";
            if ($dp['aktivasi'])
                $status = "Aktif";

            if (!$dp['id'])
                $status = "Belum Selesai";
            $tmp['status'] = $status;

            $tmp['aksi'] = "<div class='btn-group me-1 mb-1'>
                                <div class='dropdown'>
                                    <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                    <div class='dropdown-menu dropdown-menu-end' style=''>
                                        <a class='dropdown-item editRow' data-pilih='" . $no . "' data-id='" . $dp['idhakakses'] . "' href='#'><i class='bi bi-pencil-square'></i> Ganti</a>
                                        <a class='dropdown-item deleteRow' data-pilih='" . $no . "' data-id='" . $dp['idhakakses'] . "' href='#'><i class='bi bi-trash'></i> Hapus</a>
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

        $this->load->library('dataweb');
        $cari = $this->input->post();
        $retVal = $this->dataweb->cariGrupUser_new($cari);

        die(json_encode($retVal));
    }

    public function delete()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $id = $this->input->post('idTerpilih');

        foreach ($id as $i => $dp) {
            $retVal['pesan'][$i] = "akses ditolak";
            if ($dp != "") {
                $run = akses_akun("delete", $this->otentikasi, $this->d['tbName'], $dp);
                if ($run->status) {
                    $kond = array(
                        array("where", "id", $dp),
                    );
                    $runquery = $this->Model_data->delete($kond, $this->d['tbName'], null, true);
                    $retVal['pesan'][$i] = $runquery['pesan'];
                    $retVal['status'] = true;
                }
            }
        }

        die(json_encode($retVal));
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => "", "login" => true);
        allowheader();

        $this->form_validation->set_rules('idgrup', 'grup id', 'trim|required');
        $this->form_validation->set_rules('iduser', 'user', 'trim|required');
        $this->form_validation->set_rules('nim', 'NIM', 'trim|required');
        $this->form_validation->set_rules('idprodi', 'program studi', 'trim|required');
        $this->form_validation->set_rules('aktivasi', 'status aktivasi', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idhakakses'];

            $dataHakakses = array(
                "iduser" => $dataSave['iduser'],
                "idgrup" => $dataSave['idgrup'],
                "aktivasi" => $dataSave['aktivasi'],
            );

            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $token = generatetoken($dataSave['idgrup'], $dataSave['iduser']);
                $dataHakakses['token'] = $token;
                $dataHakakses['owned'] = $dataSave['iduser'];
                $retVal = $this->Model_data->save($dataHakakses, "hakakses", null, true);
                if ($retVal['status']) {
                    $dataGrup = array(
                        "idhakakses" => $retVal['id'],
                        "idgrup" => $dataSave['idgrup'],
                        "idprodi" => $dataSave['idprodi'],
                        "nim" => $dataSave['nim'],
                    );
                    $retVal = $this->Model_data->save($dataGrup, "mahasiswa", null, true);
                }
            } elseif ($id <> "" && akses_akun("update", $this->otentikasi, "hakakses", $id)->status) {
                $kondHakakses = array(
                    array("where", "id", $dataSave['idhakakses']),
                );
                $retVal = $this->Model_data->update($kondHakakses, $dataHakakses, "hakakses", null, true);
                if ($retVal['status']) {
                    $kondgrup = array(
                        array("where", "idhakakses", $dataSave['idhakakses']),
                    );
                    $dataGrup = array(
                        "idprodi" => $dataSave['idprodi'],
                        "nim" => $dataSave['nim'],
                    );
                    //debug($dataGrup);
                    $retVal = $this->Model_data->update($kondgrup, $dataGrup, "mahasiswa", null, true);
                }
            } else {
                $retVal['pesan'] = "Maaf, akses ditolak";
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }
}
