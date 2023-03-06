<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penempatan extends CI_Controller
{
    private $d = array(
        "tbName" => "penempatan",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "KKN Mahasiswa",
            "parent" => array(""),
            "modul" => "mahasiswa/penempatan",
            "view"  => "mahasiswa",
            "page"    => "KKN Mahasiswa",
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
        $this->load->library("dataweb");
        $this->d['web']['loadview'] = $this->d['web']['view'] . "/penempatan";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datetime"),
            loadPlugins("datatables"),
            loadPlugins("dropzone"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/mahasiswa/penempatan.js?' . date("ymdhis")),
        );


        $vCari = array(
            array("cond" => "where", "fld" => "pm.id IS NOT NULL", "val" => null),
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
        );
        $pesertaKKN = $this->dataweb->pesertakkn($vCari);
        $listkkn = array();
        if ($pesertaKKN['status']) {
            $listkkn = $pesertaKKN['db'];
        }
        $this->d['dataSql'] = $listkkn;

        $this->load->view('app/index', $this->d);
    }

    public function update_profil_posko()
    {
        $retVal = array("status" => false, "pesan" => "");
        allowheader();

        $this->form_validation->set_rules('idkelompok', 'kelompok', 'trim|required');
        $this->form_validation->set_rules('proker', 'program kerja', 'trim|required');
        $this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
        $this->form_validation->set_rules('latitude', 'latitude', 'trim|required');
        $this->form_validation->set_rules('longitude', 'longitude', 'trim|required');

        $proker = strip_tags($this->input->post('proker'));
        if ($proker == "") {
            $retVal = array("status" => false, "pesan" => ["program kerja tidak boleh kosong"]);
            die(json_encode($retVal));
        }



        if ($this->form_validation->run()) {

            $dataSave = $this->input->post();
            $id = $dataSave['idposko'];
            unset($dataSave['idposko']);
            unset($dataSave['file']);

            if (!empty($_FILES['file']['name'])) {
                $exif = @exif_read_data($_FILES['file']['tmp_name'],  0, true);
                $lokasi = photo_getGPS($exif);
                if ($lokasi['latitude'] && $lokasi['longitude']) {
                    $dataSave['latitude'] = $lokasi['latitude'];
                    $dataSave['longitude'] = $lokasi['longitude'];
                }
            }

            if (!$id && $this->session->userdata("iduser")) {
                $retVal = $this->Model_data->save($dataSave, "posko", null, true);
                $id = $retVal['id'];
            } elseif ($id && $this->session->userdata("iduser")) {
                $kond = array(
                    array("where", "id", $id),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, "posko", null, true);
            } else {
                $retVal['pesan'] = "Maaf, akses ditolak";
            }

            //upload dan update 
            if ($retVal['status']) {

                if (!empty($_FILES['file']['name'])) {

                    $tmppath = "uploads/posko/" . date("Y") . "/";
                    $fullpath = "./" . $tmppath;

                    if (!file_exists($fullpath))
                        mkdir($fullpath, 0755, true);

                    $config['file_name'] = $id;
                    //$config['encrypt_name'] = TRUE;
                    $config['upload_path'] = $fullpath;
                    $config['allowed_types'] = 'JPG|JPEG|GIF|PNG|gif|jpg|png|jpeg|tft|TFT';
                    $config['max_size'] = 3048;
                    $config['overwrite'] = true;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('file')) {
                        $file_info = $this->upload->data();
                        $pathfile = $tmppath . $file_info['file_name'];
                        //debug($lokasi);
                        $kond = array(
                            array("where", "id", $id),
                        );
                        $dataSave = array(
                            'path' => $pathfile,
                            'fileinfo' => json_encode($file_info),
                        );

                        $retVal = $this->Model_data->update($kond, $dataSave, "posko", null, true);
                    } else {
                        $retVal['pesan'] = ["Tidak ada lampiran file yang akan diupload atau ukuran lebih dari 3MB"];
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

    public function lkhmahasiswa()
    {
        $link = base_url();
        $grupakun = json_decode($this->session->userdata('idgrup'), true);
        if (in_array(4, $grupakun)) {
            $this->load->library("dataweb");
            $vCari = array(
                array("cond" => "where", "fld" => "k.tahun", "val" => date("Y")),
                array("cond" => "where", "fld" => "pm.id IS NOT NULL", "val" => null),
                array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata('iduser')),
            );
            $penempatan = $this->dataweb->pesertakkn($vCari);
            if ($penempatan['status']) {
                $link = base_url("dashboard/personal/" . $penempatan['db'][0]['idpenempatan']);
            }
        }
        redirect($link);
    }
}
