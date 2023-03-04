<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    private $d = array(
        "otentikasi" => array(),
        "web" => array(
            "title" => "Dashboard",
            "modul" => "app/dashboard",
            "view"  => "app/dashboard",
            "page"  => "Dashboard",
        ),
    );

    function __construct()
    {
        parent::__construct();
        $this->otentikasi = otentikasi($this->d);
    }

    public function session()
    {
        debug($this->session->userdata());
    }

    public function index()
    {
        $this->d['web']['loadview'] = "app/dashboard";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datetime"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("myapp"),
        );

        $this->load->view('app/index', $this->d);
    }

    public function switchrole($idgrup = null)
    {
        $this->load->library("dataweb");
        $cari = array(
            array("cond" => "where_in", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
            array("cond" => "where_in", "fld" => "h.idgrup", "val" => $idgrup),
        );
        $grup = $this->dataweb->cariGrupUser_new($cari);
        //debug($grup);
        if ($grup['status']) {
            $data = array(
                "role" => $idgrup,
            );
            $this->session->set_userdata($data);
        }
        redirect("app/dashboard");
    }

    public function loadgrup()
    {
        $this->load->library("dataweb");
        $cari = array(
            array("cond" => "where_in", "fld" => "g.id", "val" => json_decode($this->session->userdata("idgrup"))),
        );
        $grup = $this->dataweb->cariGrup($cari);
        //echo $this->db->last_query();
        //debug($grup);
        $html = "";
        if ($grup['status']) {
            $html = "<ul>";
            foreach ($grup['db'] as $data => $dp) {
                $html .= "<li><a href='" . base_url('app/dashboard/switchrole/' . $dp['id']) . "'>" . $dp['nama_grup'] . "</a></li>";
            }
            $html .= "</ul>";
        }
        $retVal['html'] = $html;

        die(json_encode($retVal));
    }

    public function identitas()
    {
        $this->d['web']['vContent'] = "app/vIdentitas";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("notify"),
            loadPlugins("loading"),
        );
        $identitas = $this->db->query("SELECT * FROM user WHERE id='" . $this->session->userdata('iduser') . "'");
        $this->d['identitas'] = $identitas->row();
        $this->load->view('app/index', $this->d);
    }

    public function menuweb()
    {
        debug(menuweb());
    }
}
