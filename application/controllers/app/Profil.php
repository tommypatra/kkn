<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends CI_Controller
{
    private $d = array(
        "tbName" => "profil",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Profil",
            "parent" => array(),
            "modul" => "app/profil",
            "view"  => "app",
            "page"    => "Profil",
        ),
    );
    private $retVal = array("status" => false, "pesan" => "");

    function __construct()
    {
        parent::__construct();
        $this->otentikasi = otentikasi($this->d);
    }

    public function index($idjenis_profil = null)
    {
        $this->load->library("dataweb");


        $vCari = array(
            array("val" => $idjenis_profil, "fld" => "id", "cond" => "where"),
        );
        $profil = $this->dataweb->dataglobal("jenis_profil", $vCari);
        if (!$profil['status']) {
            redirect("app/dashboard");
        }
        $this->d['profil'] = $profil['db'][0];

        $this->d['web']['title'] = $this->d['profil']['jenis'] . " " . $this->config->item("app_singkatan");
        $this->d['web']['loadview'] = $this->d['web']['view'] . "/profil";
        $this->d['web']['importPlugins'] = array(
            //loadPlugins("highlight"),
            loadPlugins("datatables"),
            loadPlugins("datetime"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("editorweb"),
            loadPlugins("dropzone"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/app/profil.js?' . date("ymdhis")),
        );


        $this->load->view('app/index', $this->d);
    }

    public function cari()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        allowheader();
        $this->load->library("dataweb");

        $vCari = array(
            array("val" => $this->input->post("idjenis_profil"), "fld" => "j.id", "cond" => "where"),
        );
        $retVal = $this->dataweb->dataprofil($vCari);

        die(json_encode($retVal));
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        allowheader();

        $this->form_validation->set_rules('detail', 'detail', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idprofil'];
            $idjenis_profil = $dataSave['idjenis_profil'];
            unset($dataSave['idprofil']);
            unset($dataSave['file']);

            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
                $id = $retVal['id'];
            } elseif ($id <> "" && akses_akun("update", $this->otentikasi, $this->d['tbName'], $id)->status) {
                $kond = array(
                    array("where", $this->d['primaryKey'], $id),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, $this->d['tbName'], null, true);
            } else {
                $retVal['pesan'] = ["Maaf, akses ditolak"];
            }

            //upload dan update 
            if ($retVal['status']) {
                if (!empty($_FILES['file']['name'])) {
                    $tmppath = "uploads/profil/";
                    $fullpath = "./" . $tmppath;

                    if (!file_exists($fullpath))
                        mkdir($fullpath, 0755, true);

                    $config['file_name'] = $idjenis_profil;
                    //$config['encrypt_name'] = TRUE;
                    $config['upload_path'] = $fullpath;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = $this->config->item("max_size_img");
                    $config['overwrite'] = true;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('file')) {
                        $file_info = $this->upload->data();
                        $kond = array(
                            array("where", "id", $id),
                        );

                        $dataSave = array(
                            'thumbnail' => $tmppath . $file_info['file_name'],
                            'fileinfo' => json_encode($file_info),
                        );
                        $retVal = $this->Model_data->update($kond, $dataSave, $this->d['tbName'], null, true);
                    } else {
                        $retVal['pesan'] = ["Tidak ada lampiran file yang akan diupload"];
                        $retVal['status'] = false;
                    }
                }
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }
}
