<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kkn extends CI_Controller
{
    private $d = array(
        "tbName" => "pendaftar",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Jadwal",
            "parent" => array(""),
            "modul" => "mahasiswa/kkn",
            "view"  => "mahasiswa",
            "page"    => "Jadwal",
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
        $this->load->library("dataweb");
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/kkn";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datetime"),
            loadPlugins("dropzone"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/mahasiswa/kkn.js?' . date("ymdhis")),
        );


        $vCari = array(
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
        );
        $dataMhs = $this->dataweb->datamahasiswa($vCari);
        if (!$dataMhs['status']) {
            redirect("dashboard");
        }

        $idmahasiswa = $dataMhs['db'][0]['idmahasiswa'];
        //echo $idmahasiswa;
        //die;

        $this->db->select("
			'' as cek, '' as no, '' as aksi, 
            j.id, j.tema, j.jenis, , j.tempat, j.tahun, j.semester,
            j.daftarmulai, j.daftarselesai, 
            j.kknmulai, j.kknselesai, j.keterangan,
            j.tamulai, j.taselesai, j.nilaimulai, j.nilaiselesai, j.bagikelompok,
            p.id as idpendaftar,
			IF('" . date('Y-m-d') . "' BETWEEN j.daftarmulai AND j.daftarselesai,1,0) as ketpendaftaran,
            ps.id as idpeserta, if(ps.id IS NOT NULL,1,0) as ispeserta,
		");
        $this->db->from("kkn as j");
        $this->db->where("j.tahun", date("Y"));
        $this->db->join("pendaftar p", "p.idkkn=j.id AND p.idmahasiswa='" . $idmahasiswa . "'", "left");
        $this->db->join("peserta ps", "ps.idpendaftar=p.id", "left");
        $this->db->join("mahasiswa m", "m.id=p.idmahasiswa AND m.iduser='" . $this->session->userdata("iduser") . "'", "left");
        $sql = $this->db->get();
        $dataSql = array();
        $dataSqlAdm = array();

        if ($sql->num_rows() > 0) {
            $dataSql = $sql->result_array();
            $idkkn = array();
            foreach ($dataSql as $i => $dp) {
                $idkkn[] = $dp['id'];
            }

            $this->db->select("a.*");
            $this->db->where_in('a.idkkn', $idkkn);
            $this->db->from("administrasi as a");
            //$this->db->join("berkas_administrasi ba", "ba.idadministrasi=a.id");
            //$this->db->join("pendaftar p", "p.id=ba.idpendaftar AND p.idmahasiswa='" . $idmahasiswa . "'");
            $this->db->order_by("a.idkkn ASC, a.upload_file ASC, a.namaadministrasi ASC");
            $sql_admn = $this->db->get();
            if ($sql_admn->num_rows() > 0) {
                $dataSqlAdm = $sql_admn->result_array();
            }
        }
        $this->d['dataSql'] = $dataSql;
        $this->d['dataSqlAdm'] = $dataSqlAdm;
        //die(json_encode($retVal));
        $this->load->view('app/index', $this->d);
    }



    public function berkas_upload()
    {
        $retVal = array("status" => false, "pesan" => ["upload gagal dilakukan"], "login" => true);

        $idkkn = $this->input->post('idkkn', true);
        $idpendaftar = $this->input->post('idpendaftar', true);
        $idadministrasi = $this->input->post('idadministrasi', true);
        $multi = $this->input->post('multi', true);
        $berkas = $this->input->post('berkas', true);

        if (!empty($_FILES['file']['name'])) {
            $tmppath = "uploads/" . $berkas . "/" . $idkkn . "/";
            $fullpath = "./" . $tmppath;

            if (!file_exists($fullpath))
                mkdir($fullpath, 0755, true);

            $config['file_name'] = $idpendaftar . "-" . $idadministrasi;
            //$config['encrypt_name'] = TRUE;
            $config['upload_path'] = $fullpath;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = $this->config->item("max_size");
            $config['overwrite'] = true;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('file')) {
                $file_info = $this->upload->data();

                $dataSave = array(
                    'idpendaftar' => $idpendaftar,
                    'idadministrasi' => $idadministrasi,
                    'path' => $tmppath . $file_info['file_name'],
                    'fileinfo' => json_encode($file_info),
                );
                $retVal = $this->Model_data->save($dataSave, "berkas_administrasi", "berkas upload", true);
            } else {
                $retVal['pesan'] = ["Tidak ada lampiran file yang akan diupload"];
                $retVal['status'] = false;
            }
        }

        die(json_encode($retVal));
    }

    public function batalpendaftaran()
    {
        $retVal = array("status" => false, "pesan" => "");
        allowheader();

        $this->form_validation->set_rules('idkkn', 'kkn', 'trim|required');
        $this->form_validation->set_rules('idpendaftar', 'pendaftaran kkn', 'trim|required');

        if ($this->form_validation->run()) {
            $id = $this->input->post('idpendaftar');
            $i = 0;
            $akses = akses_akun("delete", $this->otentikasi, "pendaftar", $id);
            if ($akses->status) {
                $kond = array(
                    array("where", "id", $id),
                );
                $retVal = $this->Model_data->delete($kond, "pendaftar", null, true);
                $retVal['pesan'] = $retVal['pesan'];
            } else {
                $retVal['pesan'] = "akses ditolak";
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }

    public function berkas_delete($id = null)
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        if (!$id)
            $id = $this->input->post('idupload');
        $i = 0;
        $akses = akses_akun("delete", $this->otentikasi, "berkas_administrasi", $id);
        if ($akses->status && $id > 0) {
            if (isset($akses->data->path))
                unlink($akses->data->path);
            $kond = array(
                array("where", "id", $id),
            );
            $retVal = $this->Model_data->delete($kond, "berkas_administrasi", null, true);
            $retVal['pesan'][$i] = $retVal['pesan'];
        } else {
            $retVal['pesan'][$i] = "akses ditolak";
        }
        die(json_encode($retVal));
    }


    public function daftar()
    {
        $retVal = array("status" => false, "pesan" => "");
        allowheader();

        $this->form_validation->set_rules('idkkn', 'KKN', 'trim|required');

        if ($this->form_validation->run()) {

            $this->load->library("dataweb");
            //cari data mahasiswa 
            $vCari = array(
                array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
            );
            $datalama = $this->dataweb->datamahasiswa($vCari);
            $idmahasiswa = null;
            $kartumahasiswa = null;
            if ($datalama['status']) {
                $dtmhs = $datalama['db'][0];
                $idmahasiswa = $dtmhs['idmahasiswa'];
                $kartumahasiswa = $dtmhs['kartumahasiswa'];
            }
            //end cari data mahasiswa 

            if (!$idmahasiswa || !$kartumahasiswa) {
                $retVal = array("status" => false, "pesan" => ["Maaf, Anda belum melengkapi data mahasiswa di menu Profil Mahasiswa"]);
                die(json_encode($retVal));
            }

            $dataSave = array(
                "idkkn" => $this->input->post("idkkn"),
                "idmahasiswa" => $idmahasiswa,
            );
            $id = $this->input->post("id");

            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], "Pendaftaran KKN", true);
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
}
