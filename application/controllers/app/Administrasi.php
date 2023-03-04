<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Administrasi extends CI_Controller
{
    private $d = array(
        "tbName" => "administrasi",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Syarat Administrasi",
            "parent" => array("pengaturan"),
            "modul" => "app/administrasi",
            "view"  => "app",
            "page"  => "Administrasi",
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
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");
        $this->d['web']['loadview'] = $this->d['web']['view'] . "/administrasi";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        //$d['pembimbing'] = $this->datapembimbing();
        $this->d['web']['importJs'] = array(
            base_url('assets/web/administrasi.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        $this->load->library('Datatables');

        $idkkn = $this->input->post('idkkn');

        $this->datatables->from("administrasi as a");
        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            a.id as idadministrasi, a. idkkn, a.namaadministrasi, a.upload_file, a.upload_type, a.upload_size,a.keterangan

		");
        $this->datatables->join("kkn as kn", "kn.id=a.idkkn", "left");
        $this->datatables->where("kn.id", $idkkn);

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['idadministrasi'] . "'>";
            $tmp['no'] = $no;
            $tmp['namaadministrasi'] = $dp['namaadministrasi'];

            $upload = '<span class="badge bg-primary">Tidak</span>';
            if ($dp['upload_file'] == "y")
                $upload = '<span class="badge bg-success">Upload</span>';
            $tmp['upload_file'] = $upload;
            $tmp['upload_type'] = $dp['upload_type'];
            $tmp['upload_size'] = $dp['upload_size'];
            $tmp['keterangan'] = $dp['keterangan'];

            $tmp['aksi'] = "<div class='btn-group me-1 mb-1'>
                                <div class='dropdown'>
                                    <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                    <div class='dropdown-menu dropdown-menu-end' style=''>
                                        <a class='dropdown-item editRow' data-pilih='" . $no . "' data-id='" . $dp['idadministrasi'] . "' href='#'><i class='bi bi-pencil-square'></i> Ganti</a>
                                        <a class='dropdown-item deleteRow' data-pilih='" . $no . "' data-id='" . $dp['idadministrasi'] . "' href='#'><i class='bi bi-trash'></i> Hapus</a>
                                    </div>
                                </div>
                            </div>";
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
            $this->db->from("administrasi");
            $this->db->select("*");

            foreach ($vCari as $val => $cariSql) {
                if ($cariSql['cond'] == "like")
                    $this->db->like($cariSql['fld'], $cariSql['val'], "both");
                else
                    $this->db->where($cariSql['fld'], $cariSql['val']);
            }
            $this->db->order_by("namaadministrasi ASC");
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
        allowheader();
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $id = $this->input->post('idTerpilih');
        if (count($id) > 0) {
            $pesan = [];
            foreach ($id as $i => $dp) {
                $run = akses_akun("delete", $this->otentikasi, $this->d['tbName'], $dp);
                if ($run->status) {
                    $kond = array(
                        array("where", "id", $dp),
                    );
                    $runquery = $this->Model_data->delete($kond, $this->d['tbName'], null, true);
                    $retVal['status'] = $runquery['status'];
                    $pesan[] = $runquery['pesan'];
                }
            }
            $retVal['pesan'] = $pesan;
        }
        die(json_encode($retVal));
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        allowheader();

        $this->form_validation->set_rules('idkkn', 'pilihan KKN', 'trim|required');
        $this->form_validation->set_rules('namaadministrasi', 'jenis administrasi', 'trim|required');
        if ($this->input->post('upload_file') == 'y') {
            $this->form_validation->set_rules('upload_type', 'type file upload', 'trim|required');
            $this->form_validation->set_rules('upload_size', 'ukurang file', 'trim|required');
        }
        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idadministrasi'];
            unset($dataSave['idadministrasi']);

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
