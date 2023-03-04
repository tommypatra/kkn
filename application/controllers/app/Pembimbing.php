<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembimbing extends CI_Controller
{
    private $d = array(
        "tbName" => "pembimbing",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Pembimbing",
            "parent" => array("pengaturan"),
            "modul" => "app/pembimbing",
            "view"  => "app",
            "page"    => "Pembimbing",
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
        $this->d['web']['loadview'] = $this->d['web']['view'] . "/pembimbing";
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");

        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("datetime"),
            loadPlugins("mask"),
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
            base_url('assets/web/pembimbing.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        $this->load->library('Datatables');

        $idsk = $this->input->post('idsk');

        $this->datatables->from("pembimbing_kkn as pk");
        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            sk.id as idsk, sk.sk_no, sk.sk_tgl, sk.path,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.email, u.hp, u.nik, u.kel, u.path as profilpic,
            pk.id as idkknpembimbing,pk.keterangan,
		");
        $this->datatables->join("sk_pembimbing as sk", "pk.idsk_pembimbing=sk.id", "left");
        $this->datatables->join("pembimbing as p", "p.id=pk.idpembimbing", "left");
        $this->datatables->join("hakakses as h", "p.idhakakses=h.id", "left");
        $this->datatables->join("user as u", "u.id=h.iduser", "left");
        $this->datatables->where("sk.id", $idsk);

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['idkknpembimbing'] . "'>";
            $tmp['no'] = $no;
            $tmp['foto'] = "<div class='avatar avatar-xl'><img src='" . base_url($dp['profilpic']) . "' ></div>";
            $tmp['nama'] = $dp['nama'];
            $tmp['kel'] = $dp['kel'];
            $tmp['kontak'] = $dp['email'] . "/ " . $dp['hp'];
            $tmp['keterangan'] = $dp['keterangan'];

            $tmp['aksi'] = "<div class='buttons'>
                                <a href='#' class='btn btn-danger rounded-pill deleteRow' data-pilih='" . $no . "' data-id='" . $dp['idkknpembimbing'] . "' href='#'><i class='bi bi-trash'></i></a>
                            </div>";
            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }

    public function cari_sk($vCari = null)
    {
        $retVal = $this->retVal;
        if (!$vCari)
            $vCari = $this->input->post();

        if (count($vCari) > 0) {
            $this->db->from("sk_pembimbing as s");
            $this->db->select("s.*");

            foreach ($vCari as $val => $cariSql) {
                if ($cariSql['cond'] == "like")
                    $this->db->like($cariSql['fld'], $cariSql['val'], "both");
                else
                    $this->db->where($cariSql['fld'], $cariSql['val']);
            }
            $sql = $this->db->get();
            if ($sql->num_rows() > 0) {
                $retVal['status'] = true;
                $retVal['db'] = $sql->result_array();
            }
        }
        die(json_encode($retVal));
    }

    public function simpansk()
    {
        $retVal = array("status" => false, "pesan" => "", "login" => true);
        allowheader();
        $tb_name = "sk_pembimbing";
        $pk = "id";

        $this->form_validation->set_rules('idkkn', 'Pilihan KKN', 'trim|required');
        $this->form_validation->set_rules('sk_no', 'No. SK', 'trim|required');
        $this->form_validation->set_rules('sk_tgl', 'Tanggal SK', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idsk_pembimbing'];
            unset($dataSave['idsk_pembimbing']);

            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, $tb_name, null, true);
            } elseif ($id <> "" && akses_akun("update", $this->otentikasi, $tb_name, $id)->status) {
                $kond = array(
                    array("where", $pk, $id),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, $tb_name, null, true);
            } else {
                $retVal['pesan'] = "Maaf, akses ditolak";
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }

    public function uploadsk()
    {
        $retVal = array("status" => false, "pesan" => ["upload gagal dilakukan"], "login" => true);

        $idkkn = $this->input->post('idkkn', true);
        $idsk = $this->input->post('idsk', true);
        $multi = $this->input->post('multi', true);
        $berkas = $this->input->post('berkas', true);

        if (!empty($_FILES['file']['name'])) {
            $tmppath = "uploads/" . $berkas . "/";
            $fullpath = "./" . $tmppath;

            if (!file_exists($fullpath))
                mkdir($fullpath, 0755, true);

            $config['file_name'] = $idsk;
            //$config['encrypt_name'] = TRUE;
            $config['upload_path'] = $fullpath;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = $this->config->item("max_size");
            $config['overwrite'] = true;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('file')) {
                $file_info = $this->upload->data();
                $kond = array(
                    array("where", "id", $idsk),
                );

                $dataSave = array(
                    'path' => $tmppath . $file_info['file_name'],
                    'fileinfo' => json_encode($file_info),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, "sk_pembimbing", "sk dosen pembimbing", true);
            } else {
                $retVal['pesan'] = ["Tidak ada lampiran file yang akan diupload"];
                $retVal['status'] = false;
            }
        }

        die(json_encode($retVal));
    }

    public function daftar()
    {
        $retVal = $this->retVal;
        $this->db->from("hakakses as h");
        $this->db->select("
                a.id, a.iduser, a.idgrup, a.statuspeg, a.nip,
                h.id as idhakakses,h.aktivasi,h.token,
                TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama,
                u.nama as namaasli,
                u.email, u.nik,u.kel,u.tmplahir,u.tgllahir,u.alamat,u.hp,u.institusi,u.path,u.aktivasi as aktivasi_user,
        ");
        $this->db->join("pembimbing as a", "h.id=a.idhakakses", "left");
        $this->db->join("user as u", "u.id=h.iduser", "left");
        $this->db->where("h.idgrup", "5");
        $this->db->where("h.aktivasi", 1);
        $this->db->order_by("u.nama ASC");
        $vCari = $this->input->post();
        foreach ($vCari as $val => $cariSql) {
            if ($cariSql['cond'] == "like")
                $this->db->like($cariSql['fld'], $cariSql['val'], "both");
            else
                $this->db->where($cariSql['fld'], $cariSql['val']);
        }

        $sql = $this->db->get();
        //echo $this->db->last_query();
        if ($sql->num_rows() > 0) {
            $retVal['status'] = true;
            $retVal['db'] = $sql->result_array();
        }
        die(json_encode($retVal));
    }

    public function cari()
    {
        $retVal = $this->retVal;
        $this->db->from("pembimbing as p");
        $this->db->select("p.id as idpembimbing,u.id as iduser,TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
                            u.hp, u.nik, u.kel, u.path as profilpic,
                            pk.id as idpembimbing_kkn,
                            sp.id as idsk_pembimbing,
                            ");
        $this->db->join("hakakses as h", "p.idhakakses=h.id", "left");
        $this->db->join("user as u", "u.id=h.iduser", "left");
        $this->db->join("pembimbing_kkn as pk", "pk.idpembimbing=p.id", "left");
        $this->db->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "left");

        $this->db->where("p.id IS NOT NULL", null);
        $this->db->where("u.aktivasi", "y");
        $this->db->order_by("u.nama ASC");

        $vCari = $this->input->post();
        foreach ($vCari as $val => $cariSql) {
            if ($cariSql['cond'] == "like")
                $this->db->like($cariSql['fld'], $cariSql['val'], "both");
            else
                $this->db->where($cariSql['fld'], $cariSql['val']);
        }

        $sql = $this->db->get();
        //debug($vCari, 0);
        //echo $this->db->last_query();
        //die;
        if ($sql->num_rows() > 0) {
            $retVal['status'] = true;
            $retVal['db'] = $sql->result_array();
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
                $run = akses_akun("delete", $this->otentikasi, "pembimbing_kkn", $dp);
                if ($run->status) {
                    $kond = array(
                        array("where", "id", $dp),
                    );
                    $runquery = $this->Model_data->delete($kond, "pembimbing_kkn", null, true);
                    $pesan[] = $runquery['pesan'];
                }
            }
            $retVal['status'] = true;
            $retVal['pesan'] = $pesan;
        }
        die(json_encode($retVal));
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        allowheader();

        $this->form_validation->set_rules('idsk', 'SK Pembimbing KKN', 'trim|required');

        if ($this->form_validation->run()) {
            $idsk = $this->input->post('idsk');
            $pembimbing = $this->input->post('iduser_pembimbing');
            $keterangan = $this->input->post('keterangan');

            if (count($pembimbing) > 0) {
                $pesan = [];
                foreach ($pembimbing as $ind => $vidpembimbing) {
                    if (akses_akun("insert", $this->otentikasi)->status) {
                        $dataSave = array(
                            "idsk_pembimbing" => $idsk,
                            "idpembimbing" => $vidpembimbing,
                            "keterangan" => $keterangan,
                        );
                        //print_r($dataSave);
                        $sqlrun = $this->Model_data->save($dataSave, "pembimbing_kkn", null, true);
                        $pesan[] = $sqlrun['pesan'];
                    }
                }
                $retVal['pesan'] = $pesan;
                $retVal['status'] = true;
            } else {
                $retVal['pesan'] = "Maaf, pilih data dosen terlebih dahulu.";
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }
}
