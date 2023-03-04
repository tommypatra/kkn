<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Upload extends CI_Controller
{
    private $d = array(
        "tbName" => "upload",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Upload",
            "parent" => array(),
            "modul" => "app/upload",
            "view"  => "app",
            "page"    => "Upload",
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
        $this->d['web']['loadview'] = $this->d['web']['view'] . "/upload";
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

        //$d['pembimbing'] = $this->datapembimbing();
        $this->d['web']['importJs'] = array(
            base_url('assets/web/app/upload.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        $this->load->library('Datatables');

        $this->datatables->from("upload as f");
        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            f.id as idupload, f.judul, f.keterangan, f.waktu, f.fileinfo, f.path, f.is_image,f.publish,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path as profilpic,
		");
        $this->datatables->join("user as u", "u.id=f.owned", "left");

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['idupload'] . "'>";
            $tmp['no'] = $no;
            $path = "";
            $go = "#";
            if ($dp['path']) {
                $go = base_url($dp['path']);
                $fileinfo = json_decode($dp['fileinfo'], true);
                $path = "<a href='" . base_url($dp['path']) . "' target='_blank'><img src='" . base_url("assets/img/files.png") . "?" . rand(1000, 9999) . "' width='100%'></a>";
                if ($dp['is_image'])
                    $path = ($dp['path']) ? "<a href='" . base_url($dp['path']) . "' target='_blank'><img src='" . base_url($dp['path']) . "?" . rand(1000, 9999) . "' width='100%'></a>" : "";
                $path .= "<div style='font-size:12px'>";
                $path .= "<b>" . $fileinfo['client_name'] . "</b><br>";
                $path .= "type : " . $fileinfo['file_ext'] . "<br>";
                $path .= "size : " . $fileinfo['file_size'];
                $path .= "</div>";
            }
            $publish = "Tidak Terpublish";
            if ($dp['publish'] == 1)
                $publish = "Terpublish";

            $tmp['path'] = $path;
            $tmp['judul'] = $dp['judul'];
            $tmp['waktu'] = $dp['waktu'];
            $tmp['publish'] = "<span class='badge bg-secondary'>" . $publish . "</span>";
            $tmp['nama'] = $dp['nama'];
            $tmp['keterangan'] = $dp['keterangan'];

            $tmp['aksi'] = "<div class='btn-group me-1 mb-1'>
                                <div class='dropdown'>
                                    <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                    <div class='dropdown-menu dropdown-menu-end' style=''>
                                        <a class='dropdown-item' href='" . $go . "' target='_blank'><i class='bi bi-newspaper'></i> Detail</a>
                                        <a class='dropdown-item editRow' data-pilih='" . $no . "' data-id='" . $dp['idupload'] . "' href='#'><i class='bi bi-pencil-square'></i> Ganti</a>
                                        <a class='dropdown-item deleteRow' data-pilih='" . $no . "' data-id='" . $dp['idupload'] . "' href='#'><i class='bi bi-trash'></i> Hapus</a>
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
        $retVal = $this->dataweb->daftarupload($vCari);
        die(json_encode($retVal));
    }


    public function delete()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $id = $this->input->post('idTerpilih');
        if (count($id) > 0) {
            $pesan = [];
            foreach ($id as $i => $dp) {
                $run = akses_akun("delete", $this->otentikasi, "upload", $dp);
                //debug($run->data);
                if ($run->status) {
                    if (file_exists($run->data->path))
                        unlink($run->data->path);
                    $kond = array(
                        array("where", "id", $dp),
                    );
                    $runquery = $this->Model_data->delete($kond, "upload", null, true);
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
        $this->form_validation->set_rules('waktu', 'waktu upload', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idupload'];

            unset($dataSave['idupload']);
            unset($dataSave['file']);

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
                //debug($_FILES);
                if (!empty($_FILES['file']['name'])) {
                    $tmppath = "uploads/berkasweb/" . date("Y") . "/" . date("m") . "/";
                    $fullpath = "./" . $tmppath;
                    if (!file_exists($fullpath))
                        mkdir($fullpath, 0755, true);

                    $config['file_name'] = $id;
                    //$config['encrypt_name'] = TRUE;
                    $config['upload_path'] = $fullpath;
                    $config['allowed_types'] = 'doc|docx|xls|xlsx|ppt|pptx|pdf|gif|jpg|png|jpeg';
                    $config['max_size'] = $this->config->item("max_size");
                    $config['overwrite'] = true;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    //debug($config);
                    if ($this->upload->do_upload('file')) {
                        $file_info = $this->upload->data();

                        $kond = array(
                            array("where", "id", $id),
                        );
                        $dataSave = array(
                            'path' => $tmppath . $file_info['file_name'],
                            'fileinfo' => json_encode($file_info),
                            'is_image' => $file_info['is_image'],
                        );
                        $retVal = $this->Model_data->update($kond, $dataSave, $this->d['tbName'], null, true);
                    } else {
                        $retVal['pesan'] = ["Data berhasil tersimpan tapi upload file gagal dilakukan"];
                        $retVal['status'] = true;
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
