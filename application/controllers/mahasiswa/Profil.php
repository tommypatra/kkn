<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends CI_Controller
{
    private $d = array(
        "tbName" => "mahasiswa",
        "idgrup" => "4",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Profil Mahasiswa",
            "parent" => array(""),
            "modul" => "mahasiswa/profil",
            "view"  => "mahasiswa",
            "page"    => "Profil",
        ),
    );
    private $retVal = array("status" => false, "pesan" => "");

    function __construct()
    {
        parent::__construct();
        $this->otentikasi = otentikasi($this->d);
        $this->load->library("dataweb");
    }

    public function index()
    {

        $this->load->library("select2");
        $this->d['web']['loadview'] = $this->d['web']['view'] . "/profil";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datetime"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("dropzone"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/mahasiswa/profil.js?' . date("ymdhis")),
        );

        //die(json_encode($retVal));
        $this->load->view('app/index', $this->d);
    }

    public function loadidentitas()
    {
        $retVal = $this->retVal;

        $vCari = array(
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
            array("cond" => "where", "fld" => "h.idgrup", "val" => $this->d['idgrup']),
        );
        $retVal = $this->dataweb->datamahasiswa($vCari);
        die(json_encode($retVal));
    }

    public function loadprodi()
    {
        $retVal = $this->retVal;
        $retVal = $this->dataweb->dataprodi([]);
        die(json_encode($retVal));
    }

    public function simpan()
    {
        allowheader();
        $retVal = array("status" => false, "pesan" => [""]);

        $this->form_validation->set_rules('idhakakses', 'hakakses', 'trim|required');
        $this->form_validation->set_rules('nim', 'Nim', 'trim|required');
        $this->form_validation->set_rules('idprodi', 'Program Studi', 'trim|required');
        $this->form_validation->set_rules('persetujuan', 'cek pernyataan setuju', 'trim|required');

        //idgrup 4 untuk mahasiswa
        /*
        $vCari = array(
            array("cond" => "where", "val" => $this->session->userdata("iduser"), "fld" => "u.id"),
            array("cond" => "where", "val" => "4", "fld" => "h.idgrup"),
        );
        $dataakun = $this->dataweb->loadprofil($vCari);
        if (!$dataakun['status']) {
            $retVal['pesan'] = ["tidak ditemukan"];
            $retVal['status'] = false;
            die(json_encode($retVal));
        }
        $dataakun = $dataakun["db"][0];
        */

        if ($this->form_validation->run()) {
            $this->load->library("dataweb");

            $dataSave = array(
                "nim" => $this->input->post("nim"),
                "iduser" => $this->session->userdata('iduser'),
                "idprodi" => $this->input->post("idprodi"),
                "idhakakses" => $this->input->post("idhakakses"),
            );

            $id = $this->input->post("idmahasiswa");

            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, "mahasiswa", "Mahasiswa", true);
            } elseif ($id <> "" && akses_akun("update", $this->otentikasi, "mahasiswa", $id)->status) {
                unset($dataSave['iduser']);
                unset($dataSave['idhakakses']);
                $kond = array(
                    array("where", "id", $id),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, "mahasiswa", "Mahasiswa", true);
            } else {
                $retVal['pesan'] = "Maaf, akses ditolak";
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }


    public function berkas_upload()
    {
        allowheader();
        $retVal = array("status" => false, "pesan" => ["upload gagal dilakukan"], "login" => true);

        $multi = $this->input->post('multi', true);
        $berkas = $this->input->post('berkas', true);
        $table = $this->input->post('table', true);
        $fldid = $this->input->post('fldid', true);


        //cari data mahasiswa 
        $vCari = array(
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
        );
        $datalama = $this->dataweb->datamahasiswa($vCari);
        if ($datalama['status']) {
            $dtmhs = $datalama['db'][0];
            if (file_exists($dtmhs['kartumhspic'])) {
                unlink($dtmhs['kartumhspic']);
            }
        }
        //end cari data mahasiswa 

        if (!empty($_FILES['file']['name'])) {
            $tmppath = "uploads/" . $berkas . "/" . date("Y") . "/";
            $fullpath = "./" . $tmppath;

            if (!file_exists($fullpath))
                mkdir($fullpath, 0755, true);

            //$config['file_name'] = $this->session->userdata('iduser');
            $config['encrypt_name'] = TRUE;
            $config['upload_path'] = $fullpath;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = $this->config->item("max_size_img");
            $config['overwrite'] = true;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('file')) {
                $file_info = $this->upload->data();
                $kond = array(
                    array("where", $fldid, $this->session->userdata('iduser')),
                );

                $dataSave = array(
                    'path' => $tmppath . $file_info['file_name'],
                    'fileinfo' => json_encode($file_info),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, $table, "upload dokumen", true);
            } else {
                $retVal['pesan'] = ["Tidak ada lampiran file yang akan diupload"];
                $retVal['status'] = false;
            }
        }

        die(json_encode($retVal));
    }
}
