<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aktifitas extends CI_Controller
{
    private $d = array(
        "tbName" => "aktifitas_dpl",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Aktifitas DPL",
            "parent" => array(""),
            "modul" => "pembimbing/aktifitas",
            "view"  => "pembimbing",
            "page"  => "Pembimbing",
        ),
    );
    private $retVal = array("status" => false, "pesan" => []);

    function __construct()
    {
        parent::__construct();
        $this->otentikasi = otentikasi($this->d);
    }

    public function index($idkelompok)
    {
        $this->load->library("select2");
        $this->load->library('Dataweb');
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/aktifitas";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("datetime"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("editorweb"),
            loadPlugins("dropzone"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );
        $this->d['idkelompok'] = $idkelompok;
        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkelompok),
        );
        $kelompok = $this->dataweb->daftarkelompok($vCari, 0, 1);
        if (!$kelompok['status']) {
            redirect("app/dashboard");
        }
        $this->d['kelompok'] = $kelompok['db'][0];
        $this->d['web']['importJs'] = array(
            base_url('assets/web/pembimbing/aktifitas.js?' . date("ymdhis")),
        );

        $this->load->view('app/index', $this->d);
    }

    public function read()
    {
        $this->load->library('Datatables');
        $this->load->library('Dataweb');

        $this->datatables->from("aktifitas_dpl as adp");
        $this->datatables->select("
			'' as no, '' as aksi, 
            adp.id, adp.uraian, adp.latitude, adp.longitude, adp.path, adp.fileinfo, adp.waktu,
		");
        $this->datatables->where("adp.idkelompok", $this->input->post("idkelompok"));


        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $dokumentasi = "";
            if ($dp['path']) {
                $dokumentasi = "<img src='" . base_url($dp['path']) . "' width='100%'>";
            }
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['id'] . "'>";
            $tmp['no'] = $no;
            $tmp['uraian'] = $dp['uraian'];
            $tmp['latitude'] = $dp['latitude'];
            $tmp['longitude'] = $dp['longitude'];
            $tmp['waktu'] = $dp['waktu'];
            $tmp['dokumentasi'] = $dokumentasi;
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

    public function cari()
    {
        $this->load->library("dataweb");
        $vCari = $this->input->post();
        $retVal = $this->dataweb->daftaraktifitas_dpl($vCari);
        die(json_encode($retVal));
    }


    public function loadkelompok()
    {
        allowheader();
        $this->load->library('Dataweb');
        $cari = array(
            array("cond" => "where", "fld" => "sp.idkkn", "val" => $this->input->post('idkkn')),
        );
        $retVal = $this->dataweb->daftarkelompok($cari);
        die(json_encode($retVal));
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        allowheader();

        $this->form_validation->set_rules('waktu', 'waktu', 'trim|required');
        $this->form_validation->set_rules('uraian', 'uraian aktifitas', 'trim|required');

        $uraian = strip_tags($this->input->post('uraian'));
        if ($uraian == "") {
            $retVal = array("status" => false, "pesan" => ["uraian aktifitas tidak boleh kosong"]);
            die(json_encode($retVal));
        }

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['id'];
            unset($dataSave['id']);
            unset($dataSave['file']);
            //debug($dataSave);

            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
                $id = $retVal['id'];
            } elseif ($id <> "" && akses_akun("update", $this->otentikasi, $this->d['tbName'], $id)->status) {
                $kond = array(
                    array("where", $this->d['primaryKey'], $id),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, $this->d['tbName'], null, true);
            } else {
                $retVal['pesan'] = ["Maaf, akses ditolak"];
            }

            //upload dan update 
            if ($retVal['status']) {
                if (!empty($_FILES['file']['name'])) {
                    $tmppath = "uploads/aktifitas_dpl/" . date("Y") . "/" . date("m") . "/";
                    $fullpath = "./" . $tmppath;

                    if (!file_exists($fullpath))
                        mkdir($fullpath, 0755, true);

                    $config['file_name'] = $id;
                    //$config['encrypt_name'] = TRUE;
                    $config['upload_path'] = $fullpath;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = $this->config->item("max_size_img");
                    $config['overwrite'] = true;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('file')) {
                        $file_info = $this->upload->data();
                        $kond = array(
                            array("where", "id", $id),
                        );

                        $dataSave = array(
                            'path' => $tmppath . $file_info['file_name'],
                            'fileinfo' => json_encode($file_info),
                        );
                        $retVal = $this->Model_data->update($kond, $dataSave, $this->d['tbName'], null, true);
                    } else {
                        $retVal['pesan'] = ["Tidak ada lampiran file yang akan diupload"];
                        $retVal['status'] = false;
                    }
                }
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }

    public function delete()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $id = $this->input->post('idTerpilih');
        if (count($id) > 0) {
            $pesan = [];
            foreach ($id as $i => $dp) {
                $run = akses_akun("delete", $this->otentikasi, $this->d['tbName'], $dp);
                //debug($run->data);
                if ($run->status) {
                    if (file_exists($run->data->path))
                        unlink($run->data->path);
                    $kond = array(
                        array("where", "id", $dp),
                    );
                    $runquery = $this->Model_data->delete($kond, $this->d['tbName'], null, true);
                    $pesan[] = $runquery['pesan'];
                }
            }
            $retVal['status'] = true;
            $retVal['pesan'] = $pesan;
        }
        die(json_encode($retVal));
    }
}
