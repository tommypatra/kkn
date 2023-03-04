<?php
defined('BASEPATH') or exit('No direct script access allowed');

class File extends CI_Controller
{
    private $retVal = array("status" => false, "pesan" => ["tidak ada aksi"]);

    function __construct()
    {
        parent::__construct();
    }

    public function read($id = null, $tabel = "")
    {
        $this->db->from($tabel);
        $this->db->select("path,fileinfo");
        $this->db->where("id", $id);
        $runquery = $this->db->get();
        if ($runquery->num_rows() > 0) {
            $db = $runquery->row();
            $fileinfo = json_decode($db->fileinfo, true);
            $fileload = $db->path;

            header("Content-Type: " . $fileinfo['file_type']);
            header("Content-Disposition: inline; filename=" . $fileload);
            readfile($fileload);
        } else {
            header(base_url());
        }
    }

    public function delete($id = null)
    {
        allowheader();
        $retVal = $this->retVal;
        $table = $this->input->post('table');
        $id = $this->input->post('idupload');

        $this->db->from($table);
        $this->db->select("owned,path,fileinfo");
        $this->db->where("id", $id);

        $runquery = $this->db->get();
        if ($runquery->num_rows() > 0) {
            $db = $runquery->row();
            $file = $db->path;
            if ($db->owned == $this->session->userdata("iduser")) {
                if (file_exists($file))
                    unlink($file);
                $this->db->where("id", $id);
                $this->db->delete($table);
                $retVal = array(
                    "status" => true, "pesan" => ["hapus berhasil dilakukan"]
                );
            }
        }

        die(json_encode($retVal));
    }

    public function upload_gambar_mhs()
    {
        allowheader();
        $this->load->library("Dataweb");
        $retVal = $this->retVal;

        $multi = $this->input->post('multi', true);
        $berkas = $this->input->post('berkas', true);
        $table = $this->input->post('table', true);
        $fldid = $this->input->post('fldid', true);
        $idhakakses = $this->input->post('idhakakses', true);

        //cari data mahasiswa 
        $vCari = array(
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
        );
        $datalama = $this->dataweb->datamahasiswa($vCari);

        //debug($datalama);
        if ($datalama['status']) {
            $dtmhs = $datalama['db'][0];
            $exc = array(
                "assets/img/favicon.ico",
                "assets/img/kartumhs.png",
                "assets/img/logo.png",
                "assets/img/pria.png",
                "assets/img/user.ico",
                "assets/img/user-avatar.png",
                "assets/img/wanita.png"
            );
            if (file_exists($dtmhs[$berkas]) && !in_array($dtmhs[$berkas], $exc)) {
                unlink($dtmhs[$berkas]);
            }
        }
        //end cari data mahasiswa 

        if (!empty($_FILES['file']['name'])) {
            $tmppath = "uploads/" . $berkas . "/" . date("Y") . "/";
            //debug($this->input->post());
            //die;
            $fullpath = "./" . $tmppath;

            if (!file_exists($fullpath))
                mkdir($fullpath, 0755, true);
            //echo $this->session->userdata('iduser');
            //die;
            //$config['file_name'] = $this->session->userdata('iduser');
            $config['encrypt_name'] = TRUE;
            $config['upload_path'] = $fullpath;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = $this->config->item("max_size_img");
            $config['overwrite'] = true;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('file')) {
                $file_info = $this->upload->data();
                $kond = array(
                    array("where", $fldid, $this->session->userdata('iduser')),
                );
                if ($berkas == "kartumahasiswa")
                    $kond = array(
                        array("where", "idhakakses", $idhakakses),
                    );

                $dataSave = array(
                    'path' => $tmppath . $file_info['file_name'],
                    'fileinfo' => json_encode($file_info),
                );
                //debug($kond, 0);
                //debug($dataSave);
                if ($berkas == "pasfoto") {
                    $datasession = array(
                        "profilpic" => base_url($tmppath . $file_info['file_name']),
                    );
                    $this->session->set_userdata($datasession);
                }
                $retVal = $this->Model_data->update($kond, $dataSave, $table, "upload dokumen", true);
            } else {
                $retVal['pesan'] = ["Tidak ada lampiran file yang akan diupload"];
                $retVal['status'] = false;
            }
        }

        die(json_encode($retVal));
    }

    public function detail_output($idpenempatan = null)
    {
        allowheader("text/html");
        $this->load->library("Dataweb");

        $this->d['web']['importPlugins'] = array(
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        //cari data  
        $vCari = array(
            array("cond" => "where", "fld" => "pm.id", "val" => $idpenempatan),
        );
        $this->d['pesertakkn'] = $this->dataweb->pesertakkn($vCari);
        if (!$this->d['pesertakkn']['status']) {
            redirect("web");
            die;
        }

        $this->d['dataoutput'] = $this->dataweb->dataoutput($vCari);

        $this->d['web']['loadview'] = "detail_upload";
        //debug($this->d);
        $this->load->view("index", $this->d);
    }

    public function upload_output()
    {
        allowheader();
        $this->load->library("Dataweb");
        $retVal = $this->retVal;

        $berkas = "output";
        $table = "output_penempatan";
        $idkkn = $this->input->post('idkkn', true);
        $idoutput = $this->input->post('idoutput', true);
        $idpenempatan = $this->input->post('idpenempatan', true);

        //cari data lama 
        $vCari = array(
            array("cond" => "where", "fld" => "op.idoutput", "val" => $idoutput),
            array("cond" => "where", "fld" => "op.idpenempatan", "val" => $idpenempatan),
        );
        $datalama = $this->dataweb->dataoutput($vCari);
        if ($datalama['status']) {
            $dtmhs = $datalama['db'][0];
            if (file_exists($dtmhs['path'])) {
                unlink($dtmhs['path']);
            }
        }
        //end cari data lama 

        if (!empty($_FILES['file']['name'])) {
            $tmppath = "uploads/" . $berkas . "/" . date("Y") . "/" . $idkkn . "/" . $idpenempatan . "/";
            $fullpath = "./" . $tmppath;

            if (!file_exists($fullpath))
                mkdir($fullpath, 0755, true);

            //$config['file_name'] = $idoutput;
            $config['encrypt_name'] = TRUE;
            $config['upload_path'] = $fullpath;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = $this->config->item("max_size_output");
            $config['overwrite'] = true;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('file')) {
                $file_info = $this->upload->data();
                $kond = array(
                    array("where", "idoutput", $idoutput),
                    array("where", "idpenempatan", $idpenempatan),
                );

                $dataSave = array(
                    'idoutput' => $idoutput,
                    'idpenempatan' => $idpenempatan,
                    'path' => $tmppath . $file_info['file_name'],
                    'fileinfo' => json_encode($file_info),
                );

                if (!$datalama['status'])
                    $retVal = $this->Model_data->save($dataSave, $table, "upload output", true);
                else
                    $retVal = $this->Model_data->update($kond, $dataSave, $table, "upload output", true);
            } else {
                $retVal['pesan'] = ["Tidak ada lampiran file yang akan diupload"];
                $retVal['status'] = false;
            }
        }

        die(json_encode($retVal));
    }

    public function upload_output_url()
    {
        allowheader();
        $this->load->library("Dataweb");
        $retVal = $this->retVal;

        $berkas = "output";
        $table = "output_penempatan";

        $this->form_validation->set_rules('idkkn', 'id kkn', 'trim|required');
        $this->form_validation->set_rules('idoutput', 'id output', 'trim|required');
        $this->form_validation->set_rules('idpenempatan', 'id penempatan', 'trim|required');
        $this->form_validation->set_rules('url', 'url file', 'trim|required|prep_url');

        if ($this->form_validation->run()) {

            $idkkn = $this->input->post('idkkn', true);
            $idoutput = $this->input->post('idoutput', true);
            $idpenempatan = $this->input->post('idpenempatan', true);

            //cari data lama 
            $vCari = array(
                array("cond" => "where", "fld" => "op.idoutput", "val" => $idoutput),
                array("cond" => "where", "fld" => "op.idpenempatan", "val" => $idpenempatan),
            );
            $datalama = $this->dataweb->dataoutput($vCari);
            if ($datalama['status']) {
                $dtmhs = $datalama['db'][0];
                if (file_exists($dtmhs['path'])) {
                    unlink($dtmhs['path']);
                }
            }
            //end cari data lama 


            $kond = array(
                array("where", "idoutput", $idoutput),
                array("where", "idpenempatan", $idpenempatan),
            );

            $dataSave = array(
                'idoutput' => $idoutput,
                'idpenempatan' => $idpenempatan,
                'path' => $this->input->post('url'),
                'jenis' => 'url',
            );

            if (!$datalama['status'])
                $retVal = $this->Model_data->save($dataSave, $table, "upload output", true);
            else
                $retVal = $this->Model_data->update($kond, $dataSave, $table, "upload output", true);
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }

        die(json_encode($retVal));
    }

    public function upload_aktifitas()
    {
        allowheader();
        $this->load->library("Dataweb");
        $retVal = $this->retVal;
        $table = "aktifitas_upload";


        $this->form_validation->set_rules('idaktifitas', 'aktifitas', 'trim|required');
        $this->form_validation->set_rules('idpenempatan', 'penempatan', 'trim|required');

        if ($this->form_validation->run()) {

            $idaktifitas = $this->input->post('idaktifitas', true);
            $idpenempatan = $this->input->post('idpenempatan', true);

            // start cari aktifitas kkn
            $vCari = array(
                array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
                array("cond" => "where", "fld" => "pm.id", "val" => $idpenempatan),
            );
            $cek = $this->dataweb->daftaraktifitas($vCari, null, 1);
            if (!$cek['status']) {
                die(json_encode($retVal));
            }
            $penempatan = $cek['db'][0];
            // end cari aktifitas kkn

            if (!empty($_FILES['file']['name'])) {
                $tmppath = "uploads/aktifitas/" . date("Y") . "/" . date("m") . "/" . date("d") . "/";
                $fullpath = "./" . $tmppath;

                if (!file_exists($fullpath))
                    mkdir($fullpath, 0755, true);

                //$config['file_name'] = $this->session->userdata('iduser');
                $config['encrypt_name'] = TRUE;
                $config['upload_path'] = $fullpath;
                $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
                $config['max_size'] = $this->config->item("max_size_img");
                $config['overwrite'] = true;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('file')) {
                    $file_info = $this->upload->data();
                    $is_image = ($file_info['is_image']) ? 1 : 0;
                    $dataSave = array(
                        'idaktifitas' => $idaktifitas,
                        'path' => $tmppath . $file_info['file_name'],
                        'fileinfo' => json_encode($file_info),
                        'is_image' => $is_image,
                    );
                    $retVal = $this->Model_data->save($dataSave, $table, "lampiran aktifitas", true);
                } else {
                    $retVal['pesan'] = array(
                        "Upload hanya gambar atau PDF saja maksimal 750kb",
                    );
                    $retVal['status'] = false;
                }
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }




        die(json_encode($retVal));
    }
}
