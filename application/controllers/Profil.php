<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends CI_Controller
{
    private $d = array(
        "tbName" => "user",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Profil Akun",
            "parent" => array(""),
            "modul" => "akun",
            "view"  => "",
            "page"  => "Profil",
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

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/profil";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/profil.js?' . date("ymdhis")),
        );

        $this->load->view('app/index', $this->d);
    }
}
