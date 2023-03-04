<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends CI_Controller
{
    private $d = array(
        "tbName" => "pembimbing",
        "idgrup" => "4",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Profil Pembimbing",
            "parent" => array(""),
            "modul" => "pembimbing/profil",
            "view"  => "pembimbing",
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
            base_url('assets/web/pembimbing/profil.js?' . date("ymdhis")),
        );

        //die(json_encode($retVal));
        $this->load->view('app/index', $this->d);
    }

    public function loadidentitas()
    {
        $retVal = $this->retVal;
        $vCari = array(
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
            //array("cond" => "where", "fld" => "h.idgrup", "val" => $this->d['idgrup']),
        );
        $retVal = $this->dataweb->datapembimbing($vCari);
        die(json_encode($retVal));
    }

    public function simpan()
    {
        allowheader();
        $retVal = array("status" => false, "pesan" => [""]);

        $this->form_validation->set_rules('idhakakses', 'hakakses', 'trim|required');
        $this->form_validation->set_rules('statuspeg', 'Status Pegawai', 'trim|required');
        $this->form_validation->set_rules('nip', 'NIP/ NIDN', 'trim|required');
        $this->form_validation->set_rules('persetujuan', 'cek pernyataan setuju', 'trim|required');

        //idgrup 4 untuk pembimbing
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
            $dataSave = array(
                "statuspeg" => $this->input->post("statuspeg"),
                "nip" => $this->input->post("nip"),
                "idhakakses" => $this->input->post("idhakakses"),
            );
            $id = $this->input->post("idpembimbing");

            //if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
            if ($id == "") {
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
                //} elseif ($id <> "" && akses_akun("update", $this->otentikasi, $this->d['tbName'], $id)->status) {
            } elseif ($id <> "") {
                unset($dataSave['idpembimbing']);
                $kond = array(
                    array("where", "id", $id),
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
