<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rilis extends CI_Controller
{
    private $d = array(
        "web" => array(
            "title" => "Rilis",
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
            base_url('assets/web/rilis.js?' . date("ymdhis")),
        );


        $this->d['web']['loadview'] = "rilis";
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
        $this->db->from("kkn as k");
        $this->db->select("k.*,
                k.id as idkkn,
                CONCAT(DATE_FORMAT(k.daftarmulai, \"%d-%m-%Y\"),' s/d ',DATE_FORMAT(k.daftarselesai, \"%d-%m-%Y\")) as waktudaftar,
                CONCAT(DATE_FORMAT(k.kknmulai, \"%d-%m-%Y\"),' s/d ',DATE_FORMAT(k.kknselesai, \"%d-%m-%Y\")) as waktukkn,
                CONCAT(DATE_FORMAT(k.tamulai, \"%d-%m-%Y\"),' s/d ',DATE_FORMAT(k.taselesai, \"%d-%m-%Y\")) as waktutugas,
                CONCAT(DATE_FORMAT(k.nilaimulai, \"%d-%m-%Y\"),' s/d ',DATE_FORMAT(k.nilaiselesai, \"%d-%m-%Y\")) as waktunilai,
                DATE_FORMAT(k.bagikelompok, \"%d-%m-%Y\") as waktubagikelompok,

                IF(CURDATE() BETWEEN k.daftarmulai AND k.daftarselesai,'terbuka','tertutup') as ketdaftar,
                IF(CURDATE() BETWEEN k.kknmulai AND k.kknselesai,'terbuka','tertutup') as ketkkn,
                IF(CURDATE() BETWEEN k.tamulai AND k.taselesai,'terbuka','tertutup') as kettugas,
                IF(CURDATE() BETWEEN k.nilaimulai AND k.nilaiselesai,'terbuka','tertutup') as ketnilai,
                COUNT(v.id) as jumlahvalidasi,
        ");
        $this->db->join("pendaftar as p", "p.idkkn=k.id", "left");
        $this->db->join("peserta as v", "v.idpendaftar=p.id", "left");
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
            $this->db->where("k.id<" . $lastid, null);
        $this->db->group_by("k.id");
        $this->db->order_by("k.id DESC");
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
                $url   = base_url("dashboard/kkn/" . $dp['idkkn']);
                $html  = "<div class='col-12 col-lg-6 col-md-12 rowdaftar' data-id='" . $dp['idkkn'] . "'>";
                $html .= "<div class='card'><a href='" . $url . "'>";
                $html .= "  <div class='card-body px-3 py-4-5'>
                                <div class='row'>
                                    <div class='col-2'>
                                        <div class='stats-icon " . $color[$randcolor] . "'>
                                            <i class='iconly-boldBookmark'></i>
                                        </div>
                                    </div>
                                    <div class='col-10'>
                                        <h5 class='text-muted font-semibold mb-0'>" . strtoupper($dp['tema']) . "</h5>
                                        <div class='font-extrabold mb-0' style='font-size:12px'>Tahun " . $dp['tahun'] . "</div>
                                        <ul style='font-size:13px;' class='mb-0'>
                                            <li>Pendaftaran : " . $dp['daftarmulai'] . " sd " . $dp['daftarselesai'] . " " . labeltanggal($dp['daftarmulai'], $dp['daftarselesai'])['labelbadge'] . "</li>
                                            <li>Pelaksanaan : " . $dp['kknmulai'] . " sd " . $dp['kknselesai'] . " " . labeltanggal($dp['kknmulai'], $dp['kknselesai'])['labelbadge'] . "</li>
                                        </ul>
                                        <div>
                                            <i class='bi bi-person-lines-fill'></i> Jumlah Peserta : " . $dp['jumlahvalidasi'] . "
                                        </div>
                                        <hr>
                                        <div>" . $dp['keterangan'] . "</div>
                                        <button class='btn icon btn-sm btn-secondary'> Detail Kegiatan Mahasiswa</button>
                                    </div>
                                </div>
                            </div></a>
                        </div>
                        </div>";
                $db[] = $html;
            }
            $retVal['db'] = $db;
        }
        die(json_encode($retVal));
    }
}
