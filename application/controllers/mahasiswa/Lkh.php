<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lkh extends CI_Controller
{
    private $d = array(
        "tbName" => "aktifitas",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Daftar LKH",
            "parent" => array("pengaturan"),
            "modul" => "mahasiswa/lkh",
            "view"  => "mahasiswa",
            "page"    => "LKH",
        ),
    );
    private $retVal = array("status" => false, "pesan" => "");

    function __construct()
    {
        parent::__construct();
        $this->otentikasi = otentikasi($this->d);
    }

    public function index($idpenempatan = null)
    {
        $this->load->library("select2");
        $this->load->library('dataweb');
        $vcari = array(
            array("cond" => "where", "fld" => "pm.id", "val" => $idpenempatan),
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
        );
        $ispeserta = $this->dataweb->pesertakkn($vcari);
        if (!$ispeserta['status']) {
            redirect(base_url());
        }
        $ispeserta = $ispeserta['db'][0];
        $this->d['ispeserta'] = $ispeserta;
        //$this->d['web']['title'] = "LKH " . $this->config->item("app_singkatan") . " " . $ispeserta['nama'] . " (NIM " . $ispeserta['nim'] . ")";
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");


        $this->d['web']['loadview'] = $this->d['web']['view'] . "/lkh";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("datetime"),
            loadPlugins("editorweb"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );
        $this->d['web']['importJs'] = array(
            base_url('assets/web/mahasiswa/lkh.js?' . date("ymdhis")),
        );
        $this->d['idpenempatan'] = $idpenempatan;

        //debug($this->d);
        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        $this->load->library('Datatables');

        $idpenempatan = $this->input->post('idpenempatan');
        $ketkkn = $this->input->post('ketkkn');


        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            a.id,a.uraian,a.waktu,a.grup,
            if(a.estbiaya IS NULL,0,a.estbiaya) estbiaya,
            if(a.jummhs IS NULL,0,a.jummhs) jummhs,
            if(a.jummasyarakat IS NULL,0,a.jummasyarakat) jummasyarakat,
            au.path,
		");
        $this->datatables->where("a.idpenempatan", $idpenempatan);
        $this->datatables->from("aktifitas as a");
        $this->datatables->join("(SELECT * FROM aktifitas_upload WHERE is_image=1 GROUP BY idaktifitas) as au", "au.idaktifitas=a.id", "left");
        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['no'] = $no;
            $tmp['grup'] = $dp['grup'];
            $pathimg = "";
            if ($dp['path']) {
                $pathimg = "<img src='" . base_url($dp['path']) . "' width='150px'>";
            }
            $keterlibatan = "<div style='font-style:italic'>Mhs  (" . $dp['jummhs'] . ") Msyrkt (" . $dp['jummasyarakat'] . ")</div>";
            $tmp['uraian'] = "<h5>" . $dp['grup'] . "</h5>" . strip_tags($dp['uraian']) . " " . $keterlibatan;
            $tmp['waktu'] = $dp['waktu'];
            $tmp['foto'] = $pathimg;
            $tmp['jummhs'] = $dp['jummhs'];
            $tmp['jummasyarakat'] = $dp['jummasyarakat'];
            $tmp['estbiaya'] = format_rupiah($dp['estbiaya']);

            if ($ketkkn == "terbuka") {
                $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['id'] . "'>";
                $tmp['aksi'] = "<div class='btn-group me-1 mb-1'>
                                <div class='dropdown'>
                                
                                    <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                    <div class='dropdown-menu dropdown-menu-end' style=''>
                                        <a href='" . base_url('dashboard/detail_aktifitas/' . $dp['id']) . "' class='dropdown-item' target='_blank'><i class='bi bi-arrow-up-right-square-fill'></i> Detail LKH</a>
                                        <a class='dropdown-item editRow' data-pilih='" . $no . "' data-id='" . $dp['id'] . "' href='#'><i class='bi bi-pencil-square'></i> Ganti</a>
                                        <a class='dropdown-item deleteRow' data-pilih='" . $no . "' data-id='" . $dp['id'] . "' href='#'><i class='bi bi-trash'></i> Hapus</a>
                                    </div>
                                </div>
                            </div>";
            } else {
                $tmp['cek'] = "";
                $tmp['aksi'] = "<div class='btn-group me-1 mb-1'>
                                    <div class='dropdown'>
                                    
                                        <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                        <div class='dropdown-menu dropdown-menu-end' style=''>
                                            <a href='" . base_url('dashboard/detail_aktifitas/' . $dp['id']) . "' class='dropdown-item' target='_blank'><i class='bi bi-arrow-up-right-square-fill'></i> Detail LKH</a>
                                        </div>
                                    </div>
                                </div>";
            }
            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }

    public function cari()
    {
        $retVal = $this->retVal;
        $vCari = $this->input->post();

        if (count($vCari) > 0) {
            $this->db->from("output as o");
            $this->db->select("o.*");
            $this->db->order_by("o.idoutput ASC");

            foreach ($vCari as $val => $cariSql) {
                if ($cariSql['cond'] == "like")
                    $this->db->like($cariSql['fld'], $cariSql['val'], "both");
                else
                    $this->db->where($cariSql['fld'], $cariSql['val']);
            }
            $sql = $this->db->get();
            if ($sql->num_rows() > 0) {
                $retVal['status'] = true;
                $retVal['db'] = $sql->result_array();
            }
        }
        die(json_encode($retVal));
    }

    public function delete()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $id = $this->input->post('idTerpilih');

        foreach ($id as $i => $dp) {
            $retVal['pesan'][$i] = "akses ditolak";
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

        die(json_encode($retVal));
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => "", "login" => true);
        allowheader();

        $this->form_validation->set_rules('idpenempatan', 'penempatan', 'trim|required');
        $this->form_validation->set_rules('waktu', 'waktu', 'trim|required');
        $this->form_validation->set_rules('uraian', 'uraian', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['id'];
            unset($dataSave['id']);
            //debug($dataSave);
            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
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
