<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Berita extends CI_Controller
{
    private $d = array(
        "tbName" => "berita",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Berita",
            "parent" => array(),
            "modul" => "app/berita",
            "view"  => "app",
            "page"    => "Berita",
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
        $this->d['web']['loadview'] = $this->d['web']['view'] . "/berita";
        $this->d['web']['importPlugins'] = array(
            //loadPlugins("highlight"),
            loadPlugins("datatables"),
            loadPlugins("datetime"),
            loadPlugins("editorweb"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("dropzone"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        //$d['pembimbing'] = $this->datapembimbing();
        $this->d['web']['importJs'] = array(
            base_url('assets/web/berita.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        $this->load->library('Datatables');

        $this->datatables->from("berita as b");
        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            b.id as idberita, b.judul, b.slug, b.detail,b.waktu, b.thumbnail,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path as profilpic,
		");
        $this->datatables->join("user as u", "u.id=b.iduser", "left");

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['idberita'] . "'>";
            $tmp['no'] = $no;
            $thumbnail = ($dp['thumbnail']) ? "<a href='" . base_url($dp['thumbnail']) . "' target='_blank'><img src='" . base_url($dp['thumbnail']) . "?" . rand(1000, 9999) . "' width='100%'></a>" : "";
            $tmp['thumbnail'] = $thumbnail;
            $tmp['judul'] = $dp['judul'];
            $tmp['slug'] = $dp['slug'];
            $tmp['nama'] = $dp['nama'];
            $tmp['detail'] = $dp['detail'];
            $tmp['waktu'] = $dp['waktu'];

            $tmp['aksi'] = "<div class='btn-group me-1 mb-1'>
                                <div class='dropdown'>
                                    <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                    <div class='dropdown-menu dropdown-menu-end' style=''>
                                        <a class='dropdown-item' href='" . base_url('web/detailberita/' . $dp['slug']) . "' target='_blank'><i class='bi bi-newspaper'></i> Detail</a>
                                        <a class='dropdown-item editRow' data-pilih='" . $no . "' data-id='" . $dp['idberita'] . "' href='#'><i class='bi bi-pencil-square'></i> Ganti</a>
                                        <a class='dropdown-item deleteRow' data-pilih='" . $no . "' data-id='" . $dp['idberita'] . "' href='#'><i class='bi bi-trash'></i> Hapus</a>
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
        $retVal = $this->dataweb->daftarberita($vCari);
        die(json_encode($retVal));
    }


    public function delete()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $id = $this->input->post('idTerpilih');
        if (count($id) > 0) {
            $pesan = [];
            foreach ($id as $i => $dp) {
                $run = akses_akun("delete", $this->otentikasi, "berita", $dp);
                //debug($run->data);
                if ($run->status) {
                    if (file_exists($run->data->thumbnail))
                        unlink($run->data->thumbnail);
                    $kond = array(
                        array("where", "id", $dp),
                    );
                    $runquery = $this->Model_data->delete($kond, "berita", null, true);
                    $pesan[] = $runquery['pesan'];
                }
            }
            $retVal['status'] = true;
            $retVal['pesan'] = $pesan;
        }
        die(json_encode($retVal));
    }

    function alphacostum($string)
    {
        if (preg_match('/[^a-z_\-0-9]/i', $string)) {
            $this->form_validation->set_message('alphacostum', 'The %s field may only contain alpha characters & White spaces');
            return false;
        } else {
            return true;
        }
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        allowheader();

        $this->form_validation->set_rules('judul', 'judul', 'trim|required');
        $this->form_validation->set_rules('slug', 'slug', 'trim|required');
        $this->form_validation->set_rules('slug', 'slug', 'callback_alphacostum');
        $this->form_validation->set_rules('waktu', 'waktu berita', 'trim|required');
        $this->form_validation->set_rules('detail', 'detail berita', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idberita'];

            unset($dataSave['idberita']);
            unset($dataSave['file']);

            $dataSave['iduser'] = $this->session->userdata("iduser");
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
                    $tmppath = "uploads/berita/" . date("Y") . "/" . date("m") . "/";
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
                            'thumbnail' => $tmppath . $file_info['file_name'],
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
}
