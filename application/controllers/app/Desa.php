<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Desa extends CI_Controller
{
    private $d = array(
        "tbName" => "wilayah_desa",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Kelurahan/ Desa",
            "parent" => array("pengaturan"),
            "modul" => "app/desa",
            "view"  => "app",
            "page"  => "desa",
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

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/desa";
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
            base_url('assets/web/app/desa.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        $this->load->library('Datatables');

        $idprovinsi = $this->input->post("idprovinsi");
        $idkabupaten = $this->input->post("idkabupaten");
        $idkecamatan = $this->input->post("idkecamatan");

        $this->datatables->select("
			'' as cek, '' as no, '' as aksi,
            d.id, d.desa, d.idkecamatan,
            c.kecamatan, c.idkabupaten,
            k.kabupaten, k.idprovinsi, 
            p.provinsi,
            CONCAT(d.kodewilayah_prov,'.',d.kodewilayah_kab,'.',d.kodewilayah_kec,'.',d.kode) as kode,
		");

        $this->datatables->from("wilayah_desa as d");
        $this->datatables->join("wilayah_kec as c", "c.id=d.idkecamatan", "left");
        $this->datatables->join("wilayah_kab as k", "k.id=c.idkabupaten", "left");
        $this->datatables->join("wilayah_prov as p", "p.id=k.idprovinsi", "left");

        $idprovinsi = ($idprovinsi != "") ? $idprovinsi : -1;
        $idkabupaten = ($idkabupaten != "") ? $idkabupaten : -1;
        $idkecamatan = ($idkecamatan != "") ? $idkecamatan : -1;

        $this->datatables->where("p.id", $idprovinsi);
        $this->datatables->where("k.id", $idkabupaten);
        $this->datatables->where("c.id", $idkecamatan);

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['id'] . "'>";
            $tmp['no'] = $no;
            $tmp['desa'] = $dp['desa'];
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

        $this->form_validation->set_rules('idkecamatan', 'kecamatan', 'trim|required');
        $this->form_validation->set_rules('kodewilayah_prov', 'kodewilayah prov', 'trim|required');
        $this->form_validation->set_rules('kodewilayah_kab', 'kodewilayah kabupaten', 'trim|required');
        $this->form_validation->set_rules('kodewilayah_kec', 'kodewilayah kecamatan', 'trim|required');
        $this->form_validation->set_rules('kode', 'kode desa', 'trim|required');
        $this->form_validation->set_rules('desa', 'desa', 'trim|required');

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
