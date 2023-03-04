<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hakakses extends CI_Controller
{
    private $d = array(
        "tbName" => "aksesgrup",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Grup Pengguna",
            "parent" => array("pengaturan"),
            "modul" => "app/hakakses",
            "view"  => "app",
            "page"  => "Hakakses",
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

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/hakakses";
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
            base_url('assets/web/app/hakakses.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        $this->load->library('Datatables');

        $idgrup = $this->input->post("idgrup");


        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            h.id, h.c, h.r, h.u, h.d, h.f, h.ket, m.module, g.nama_grup,
            h.idgrup, h.idmodule,
		");

        $this->datatables->from("aksesgrup as h");
        $this->datatables->join("grup as g", "g.id=h.idgrup", "left");
        $this->datatables->join("module as m", "m.id=h.idmodule", "left");
        $idgrup = ($idgrup != "") ? $idgrup : -1;
        $this->datatables->where("g.id", $idgrup);

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['id'] . "'>";
            $tmp['no'] = $no;
            $tmp['module'] = $dp['module'];
            $tmp['nama_grup'] = $dp['nama_grup'];
            $tmp['c'] = $dp['c'];
            $tmp['r'] = $dp['r'];
            $tmp['u'] = $dp['u'];
            $tmp['d'] = $dp['d'];
            $tmp['f'] = $dp['f'];
            $tmp['ket'] = $dp['ket'];


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

        $this->form_validation->set_rules('idgrup', 'grup', 'trim|required');
        $this->form_validation->set_rules('idmodule', 'module', 'trim|required');
        $this->form_validation->set_rules('c', 'create (c)', 'trim|required');
        $this->form_validation->set_rules('r', 'read (r)', 'trim|required');
        $this->form_validation->set_rules('u', 'update (u)', 'trim|required');
        $this->form_validation->set_rules('d', 'delete (d)', 'trim|required');
        $this->form_validation->set_rules('f', 'full akses (f)', 'trim|required');

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
