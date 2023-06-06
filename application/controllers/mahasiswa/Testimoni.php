<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Testimoni extends CI_Controller
{
    private $d = array(
        "tbName" => "testimoni",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "KKN Mahasiswa",
            "parent" => array(""),
            "modul" => "mahasiswa/testimoni",
            "view"  => "mahasiswa",
            "page"    => "Testimoni KKN",
        ),
    );
    private $retVal = array("status" => false, "pesan" => "");

    function __construct()
    {
        parent::__construct();
        $this->otentikasi = otentikasi($this->d);
    }

    public function delete()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $id = $this->input->post('idTerpilih');

        $retVal['pesan'] = ["akses ditolak"];
        $run = akses_akun("delete", $this->otentikasi, $this->d['tbName'], $id);
        if ($run->status) {
            $kond = array(
                array("where", "id", $id),
            );
            $runquery = $this->Model_data->delete($kond, $this->d['tbName'], null, true);
            $retVal['pesan'] = $runquery['pesan'];
            $retVal['status'] = true;
        }

        die(json_encode($retVal));
    }
    public function validYoutubeUrl($url)
    {
        // Menggunakan regex untuk memvalidasi URL YouTube
        if (preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/i', $url)) {
            return true;
        }
        return false;
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => "", "login" => true);
        allowheader();

        $this->form_validation->set_rules('idkelompok', 'Kelompok', 'trim|required');
        $this->form_validation->set_rules('link', 'Link Youtube', 'trim|required|valid_url');

        if ($this->form_validation->run()) {
            if (!$this->validYoutubeUrl($this->input->post('link'))) {
                $retVal['pesan'] = ['Wajib menggunakan youtube'];
                die(json_encode($retVal));
            }
            $dataSave = $this->input->post();
            $infoYoutube = infoYoutube($this->input->post('link'));

            if (!$dataSave['judul']) {
                $dataSave['judul'] = $infoYoutube['title'];
            }
            $dataSave['thumbnail'] = $infoYoutube['thumbnail'];

            if (akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
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
