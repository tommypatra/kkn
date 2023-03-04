<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok extends CI_Controller
{
    private $d = array(
        "tbName" => "kelompok",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Kelompok",
            "parent" => array("pengaturan"),
            "modul" => "app/kelompok",
            "view"  => "app",
            "page"  => "Kelompok",
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

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/kelompok";
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
            base_url('assets/web/kelompok.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }

    public function cari()
    {
        $retVal = $this->retVal;
        $vCari = $this->input->post();

        if (count($vCari) > 0) {
            $this->db->from("kelompok as k");
            $this->db->select("
                k.id as idkelompok,k.keterangan,k.namakelompok,
                u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path  as profilpic, u.email,
                d.id as iddesa,d.desa,l.id as idlokasi,p.id as idpembimbing,pk.id as idpembimbing_kkn,
                kec.id as idkec,kec.kecamatan,
                kab.id as idkab,kab.kabupaten,
                prov.id as idprov,prov.provinsi,
                CONCAT(d.desa,'/',kec.kecamatan) as vlokasi,
            ");
            $this->db->join("lokasi as l", "l.id=k.idlokasi", "left");
            $this->db->join("wilayah_desa as d", "d.id=l.iddesa", "left");
            $this->db->join("wilayah_kec as kec", "kec.id=d.idkecamatan", "left");
            $this->db->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
            $this->db->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");
            $this->db->join("pembimbing_kkn as pk", "pk.id=k.idpembimbing_kkn", "left");
            $this->db->join("pembimbing as p", "p.id=pk.idpembimbing", "left");
            $this->db->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "left");
            $this->db->join("kkn as kn", "kn.id=sp.idkkn", "left");
            $this->db->join("hakakses as h", "p.idhakakses=h.id", "left");
            $this->db->join("user as u", "u.id=h.iduser", "left");

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

    public function read()
    {
        $this->load->library('Datatables');

        $idkkn = $this->input->post('idkkn');

        $this->datatables->from("kelompok as k");
        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            k.id as idkelompok,k.keterangan,k.namakelompok,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path  as profilpic, u.email,
            d.id as iddesa,d.desa, 
            kec.id as idkec,kec.kecamatan,
            kab.id as idkab,kab.kabupaten,
            prov.id as idprov,prov.provinsi,
            CONCAT(d.desa,'/',kec.kecamatan) as vlokasi,

		");
        $this->datatables->join("lokasi as l", "l.id=k.idlokasi", "left");

        $this->datatables->join("wilayah_desa as d", "d.id=l.iddesa", "left");
        $this->datatables->join("wilayah_kec as kec", "kec.id=d.idkecamatan", "left");
        $this->datatables->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->datatables->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");


        $this->datatables->join("pembimbing_kkn as pk", "pk.id=k.idpembimbing_kkn", "left");
        $this->datatables->join("pembimbing as p", "p.id=pk.idpembimbing", "left");
        $this->datatables->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "left");
        $this->datatables->join("kkn as kn", "kn.id=sp.idkkn", "left");
        $this->datatables->join("hakakses as h", "p.idhakakses=h.id", "left");
        $this->datatables->join("user as u", "u.id=h.iduser", "left");
        //$this->datatables->join("user as u", "u.id=p.iduser", "left");
        $this->datatables->where("kn.id", $idkkn);

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['idkelompok'] . "'>";
            $tmp['no'] = $no;
            $tmp['foto'] = "<div class='avatar avatar-xl'><img src='" . base_url($dp['profilpic']) . "' ></div>";
            $tmp['nama'] = $dp['nama'];
            $tmp['kel'] = $dp['kel'];
            $tmp['kontak'] = $dp['hp'] . "/ " . $dp['email'];
            $tmp['namakelompok'] = $dp['namakelompok'];
            $tmp['vlokasi'] = $dp['vlokasi'];
            $tmp['keterangan'] = $dp['keterangan'];

            $tmp['aksi'] = "<div class='buttons'>
                                <a href='#' class='btn btn-secondary rounded-pill editRow' data-pilih='" . $no . "' data-id='" . $dp['idkelompok'] . "' href='#'><i class='bi bi-pencil-square'></i></a>
                                <a href='#' class='btn btn-danger rounded-pill deleteRow' data-pilih='" . $no . "' data-id='" . $dp['idkelompok'] . "' href='#'><i class='bi bi-trash'></i></a>
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
        if (count($id) > 0) {
            $pesan = [];
            foreach ($id as $i => $dp) {
                $run = akses_akun("delete", $this->otentikasi, $this->d['tbName'], $dp);
                if ($run->status) {
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

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        allowheader();

        $this->form_validation->set_rules('namakelompok', 'nama kelompok', 'trim|required');
        $this->form_validation->set_rules('idlokasi', 'Lokasi', 'trim|required');
        $this->form_validation->set_rules('idpembimbing_kkn', 'Dosen Pembimbing', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idkelompok'];
            unset($dataSave['idkelompok']);

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
