<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Akun extends CI_Controller
{
    private $d = array(
        "tbName" => "user",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Profil Akun",
            "parent" => array(""),
            "modul" => "akun",
            "view"  => "",
            "page"  => "Akun",
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
        $this->load->library('Dataweb');

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/akun";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datetime"),
            loadPlugins("dropzone"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/akun.js?' . date("ymdhis")),
        );

        $this->load->view('app/index', $this->d);
    }

    public function load()
    {
        $this->load->library("Dataweb");
        $iduser = $this->session->userdata("iduser");

        $vCari = array(
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
        );
        $retVal = $this->dataweb->loadprofil($vCari);
        die(json_encode($retVal));
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => []);
        allowheader();

        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('nama', 'nama lengkap', 'trim|required');
        $this->form_validation->set_rules('kel', 'jenis kelamin', 'trim|required');
        $this->form_validation->set_rules('nik', 'no.ktp', 'trim|required');
        $this->form_validation->set_rules('tmplahir', 'tmplahir', 'trim|required');
        $this->form_validation->set_rules('tgllahir', 'tgllahir', 'trim|required');
        $this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
        $this->form_validation->set_rules('idprovinsi', 'provinsi', 'trim|required');
        $this->form_validation->set_rules('idkabupaten', 'kabupaten', 'trim|required');
        $this->form_validation->set_rules('idkecamatan', 'kecamatan', 'trim|required');
        $this->form_validation->set_rules('iddesa', 'desa', 'trim|required');
        $this->form_validation->set_rules('hp', 'no.hp', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['iduser'];
            unset($dataSave['iduser']);
            unset($dataSave['email']);
            //debug($dataSave);
            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
                //} elseif ($id <> "" && akses_akun("update", $this->otentikasi, $this->d['tbName'], $id)->status) {
            } elseif ($id <> "") {
                $kond = array(
                    array("where", $this->d['primaryKey'], $id),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, $this->d['tbName'], null, true);
            } else {
                $retVal['pesan'] = ["Maaf, akses ditolak"];
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }

    public function resendemail()
    {
        $retVal = array("status" => false, "pesan" => ['maaf, gagal dilakukan karena semua sudah grup akun sudah aktif']);
        allowheader();
        $this->load->library("Dataweb");
        $vCari = array(
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
        );
        $run = $this->dataweb->loadprofil($vCari);
        if ($run['status']) {
            foreach ($run['db'] as $data => $dp) {
                if (!$dp['aktivasi']) {
                    $email = $dp['email'];
                    $token = $dp['token'];
                    $pesanemail = "<h3><b>Pendaftaran Berhasil</b></h3>
                                    <p>Klik link berikut untuk melakukan aktivasi " . base_url('daftar/aktivasi/' . $token) . " atau; lakukan aktivasi secara manual dengan menghubungi admin website ini.
                                    </p>
                                    <i>Terima Kasih</i>";
                    $kirimemail = kirimEmail($email, "AKTIVASI AKUN", $pesanemail);
                    if ($kirimemail) {
                        $retVal['status'] = true;
                        $retVal['pesan'] = ["Kirim email berhasil, lakukan aktivasi melalui email " . $email . " atau dengan menghubungi admin website"];
                    }
                }
            }
        }
        die(json_encode($retVal));
    }

    public function resendemail_old()
    {
        $retVal = array("status" => false, "pesan" => ['maaf, gagal dilakukan']);
        allowheader();
        $this->load->library("Dataweb");
        $vCari = array(
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
        );
        $run = $this->dataweb->loadprofil($vCari);
        if ($run['status']) {
            $email = $run['db'][0]['email'];
            $token = $run['db'][0]['token'];
            $pesanemail = "<h3><b>Pendaftaran Berhasil</b></h3>
            <p>Klik link berikut untuk melakukan aktivasi " . base_url('daftar/aktivasi/' . $token) . " atau; lakukan aktivasi secara manual dengan menghubungi admin website ini.
            </p>
            <i>Terima Kasih</i>";
            //echo $email . " " . $pesanemail;die;
            $kirimemail = kirimEmail($email, "AKTIVASI AKUN", $pesanemail);
            //end kirim email
            if ($kirimemail) {
                $retVal['status'] = true;
                $retVal['pesan'] = ["Kirim email berhasil, lakukan aktivasi melalui email " . $email . " atau dengan menghubungi admin website"];
            }
        }
        die(json_encode($retVal));
    }
}
