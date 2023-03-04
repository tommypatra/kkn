<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kecamatan extends CI_Controller
{
    private $d = array(
        "tbName" => "wilayah_kec",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Kecamatan",
            "parent" => array("pengaturan"),
            "modul" => "app/kecamatan",
            "view"  => "app",
            "page"  => "kecamatan",
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
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/kecamatan";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("datetime"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );
        $this->d['web']['importJs'] = array(
            base_url('assets/web/app/kecamatan.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        $this->load->library('Datatables');

        $idprovinsi = $this->input->post("idprovinsi");
        $idkabupaten = $this->input->post("idkabupaten");

        $this->datatables->select("
			'' as cek, '' as no, '' as aksi,
            c.id, c.kecamatan, c.idkabupaten,
            k.kabupaten, k.idprovinsi, 
            p.provinsi,
            CONCAT(c.kodewilayah_prov,'.',c.kodewilayah_kab,'.',c.kode) as kode,
		");

        $this->datatables->from("wilayah_kec as c");
        $this->datatables->join("wilayah_kab as k", "k.id=c.idkabupaten", "left");
        $this->datatables->join("wilayah_prov as p", "p.id=k.idprovinsi", "left");
        $idprovinsi = ($idprovinsi != "") ? $idprovinsi : -1;
        $idkabupaten = ($idkabupaten != "") ? $idkabupaten : -1;
        $this->datatables->where("p.id", $idprovinsi);
        $this->datatables->where("k.id", $idkabupaten);

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['id'] . "'>";
            $tmp['no'] = $no;
            $tmp['kecamatan'] = $dp['kecamatan'];
            $tmp['kabupaten'] = $dp['kabupaten'];
            $tmp['provinsi'] = $dp['provinsi'];
            $tmp['kode'] = $dp['kode'];
            $tmp['aksi'] = "<div class='btn-group me-1 mb-1'>
                                <div class='dropdown'>
                                    <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                    <div class='dropdown-menu dropdown-menu-end' style=''>
                                        <a class='dropdown-item editRow' data-pilih='" . $no . "' data-id='" . $dp['id'] . "' href='#'><i class='bi bi-pencil-square'></i> Ganti</a>
                                        <a class='dropdown-item deleteRow' data-pilih='" . $no . "' data-id='" . $dp['id'] . "' href='#'><i class='bi bi-trash'></i> Hapus</a>
                                    </div>
                                </div>
                            </div>";

            $data[] = $tmp;
        }

        $retVal['data'] = $data;
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

        $this->form_validation->set_rules('idprovinsi', 'provinsi', 'trim|required');
        $this->form_validation->set_rules('kecamatan', 'kecamatan', 'trim|required');
        $this->form_validation->set_rules('kode', 'kode kecamatan', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['id'];
            unset($dataSave['id']);

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
