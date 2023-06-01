<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Berita extends CI_Controller
{
    private $d = array(
        "web" => array(
            "title" => "Berita Website",
            "modul" => "web",
            "view"  => "",
            "page"  => "Website",
        ),
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->library("Dataweb");

        $this->d['web']['importPlugins'] = array(
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/beritaweb.js?' . date("ymdhis")),
        );


        $this->d['web']['loadview'] = "beritaweb";
        $this->d['lastlogin'] = $this->dataweb->lastlogin([], null, 7);
        //debug($this->d);
        $this->load->view("index", $this->d);
    }

    public function loadweb()
    {
        $retVal = array("status" => false, "pesan" => "", $db = []);
        allowheader();

        $lastid = $this->input->post("lastid");
        $limit = $this->input->post("limit");
        $limitDef = ($limit > 0) ? $limit : 10;

        $vCari = $this->input->post("vCari");
        $this->db->from("berita as b");
        $this->db->select("
            b.id as idberita, b.judul, b.slug, b.detail,b.waktu, b.thumbnail,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path as profilpic,
        ");
        $this->db->join("user as u", "u.id=b.iduser", "left");
        $vCari = (isset($vCari)) ? $vCari : [];
        if (count($vCari) > 0) {
            foreach ($vCari as $val => $cariSql) {
                if ($cariSql['val'] != "") {
                    if ($cariSql['cond'] == "like")
                        $this->db->like($cariSql['fld'], $cariSql['val'], "both");
                    else
                        $this->db->where($cariSql['fld'], $cariSql['val']);
                }
            }
        }
        if ($lastid > 0)
            $this->db->where("b.id<" . $lastid, null);
        $this->db->group_by("b.id");
        $this->db->order_by("b.id DESC");
        $this->db->limit($limitDef);
        $data = $this->db->get();
        $html = "";
        $color = array("purple", "blue", "green", "red", "yellow");
        if ($data->num_rows() > 0) {
            $db = array();
            $retVal = array(
                "status" => true,
                "pesan" => "data ditemukan",
            );
            $dataload = $data->result_array();

            foreach ($dataload as $i => $dp) {
                $randcolor = rand(0, 4);
                $detail = strip_tags($dp['detail']);
                $berita = explode(' ', $detail);

                $content = "";
                $urlberita = base_url('web/detailberita/' . $dp['slug']);
                if (count($berita) > 40) {
                    for ($i = 0; $i <= 40; $i++)
                        $content .= $berita[$i] . ' ';
                    $content .= '...';
                    $content .= "<br><a href='" . $urlberita . "'>Selengkapnya!</a>";
                } else {
                    $content = $dp['detail'];
                }

                $thumbnail = "";
                if ($dp['thumbnail']) {
                    $thumbnail = "<img src='" . base_url($dp['thumbnail']) . "' class='card-img-top img-fluid' >";
                }

                $html = " <div class='col-xl-6 col-md-6 col-sm-12  rowdaftar' data-id='" . $dp['idberita'] . "''>
                            <div class='card'>
                                <div class='card-body'>
                                    <ul class='list-group list-group-flush'>
                                        <li class='list-group-item'><i class='bi bi-person-circle'></i> " . $dp['nama'] . " <span style='font-size:11px'>publish : " . $dp['waktu'] . "</span></li>
                                    </ul>
                                    <a href='" . $urlberita . "'>" . $thumbnail . "</a>
                                    <span class='badge bg-secondary' style='font-size:11px;'><i class='bi bi-clock'></i>" . waktu_lalu($dp['waktu']) . "</span>
                                    <div class='card-body'>                        
                                        <h5 class='card-title'><a href='" . $urlberita . "'>" . $dp['judul'] . "</a></h5>
                                        <p class='card-text'>
                                            " . $content . "
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>";
                $db[] = $html;
            }
            $retVal['db'] = $db;
        }
        die(json_encode($retVal));
    }
}
