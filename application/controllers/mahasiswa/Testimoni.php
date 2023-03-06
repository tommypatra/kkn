<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Testimoni extends CI_Controller
{
    private $d = array(
        "tbName" => "testimoni",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "KKN Mahasiswa",
            "parent" => array(""),
            "modul" => "mahasiswa/testimoni",
            "view"  => "mahasiswa",
            "page"    => "Testimoni KKN",
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
        $this->d['web']['loadview'] = $this->d['web']['view'] . "/testimoni";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datetime"),
            loadPlugins("datatables"),
            loadPlugins("dropzone"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/mahasiswa/testimoni.js?' . date("ymdhis")),
        );


        $vCari = array(
            array("cond" => "where", "fld" => "pm.id IS NOT NULL", "val" => null),
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
        );
        $pesertaKKN = $this->dataweb->pesertakkn($vCari);
        $listkkn = array();
        if ($pesertaKKN['status']) {
            $listkkn = $pesertaKKN['db'];
        }
        $this->d['dataSql'] = $listkkn;

        $this->load->view('app/index', $this->d);
    }
}
