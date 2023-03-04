<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends CI_Controller
{
    private $d = array(
        "tbName" => "nilai",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Pembimbing Mahasiswa",
            "parent" => array(""),
            "modul" => "pembimbing/mahasiswa",
            "view"  => "pembimbing",
            "page"  => "Pembimbing mahasiswa",
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
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/mahasiswa";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/pembimbing/mahasiswa.js?' . date("ymdhis")),
        );

        $this->load->view('app/index', $this->d);
    }

    public function read()
    {
        $this->load->library('Datatables');
        $this->load->library('Dataweb');

        $this->datatables->from("kelompok as kel");
        $this->datatables->select("
			'' as no, '' as aksi, 
            kel.id as idkelompok,kel.keterangan,kel.namakelompok,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path as profilpic, u.email,
            mhs.nim,prd.prodi,
            dsa.id as iddesa,
            kec.id as idkecamatan,
            kab.id as idkabupaten,
            prov.id as idprovinsi,
            k.tahun, k.tema,
            dsa.desa, kec.kecamatan, kab.kabupaten, prov.provinsi,
            pm.id as idpenempatan,pm.idpeserta,pm.idjabatan,
            k.kknmulai,k.kknselesai,k.tempat,k.jenis,
            k.id as idkkn,j.jabatan,j.urut, 
            n.id as idnilai,n.nilai_angka,
            count(ak.id) as jumaktifitas,
            lap.jumupload,
		");

        $this->datatables->join("penempatan as pm", "pm.idkelompok=kel.id", "left");
        $this->datatables->join("mst_jabatan as j", "j.id=pm.idjabatan", "left");
        $this->datatables->join("lokasi as l", "l.id=kel.idlokasi", "left");

        $this->datatables->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->datatables->join("wilayah_kec as kec", "kec.id=dsa.idkecamatan", "left");
        $this->datatables->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->datatables->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");

        $this->datatables->join("pembimbing_kkn as pk", "pk.id=kel.idpembimbing_kkn", "left");
        $this->datatables->join("pembimbing as pb", "pb.id=pk.idpembimbing", "left");
        $this->datatables->join("hakakses as h", "h.id=pb.idhakakses", "left");

        $this->datatables->join("nilai as n", "n.idpenempatan=pm.id AND n.idmst_penilaian=1", "left");

        $this->datatables->join("peserta as p", "p.id=pm.idpeserta", "left");
        $this->datatables->join("pendaftar as pn", "pn.id=p.idpendaftar", "left");
        $this->datatables->join("kkn as k", "k.id=pn.idkkn", "left");
        $this->datatables->join("mahasiswa as mhs", "mhs.id=pn.idmahasiswa", "left");
        $this->datatables->join("mst_prodi as prd", "mhs.idprodi=prd.id", "left");
        $this->datatables->join("mst_fakultas as fak", "fak.id=prd.idfakultas", "left");
        $this->datatables->join("user as u", "u.id=mhs.iduser", "left");
        $this->datatables->join("aktifitas as ak", "ak.idpenempatan=pm.id", "left");

        $this->datatables->join("(SELECT idpenempatan,count(id) as jumupload FROM output_penempatan GROUP BY idpenempatan) as lap", "lap.idpenempatan=pm.id", "left");

        $this->datatables->where("h.iduser", $this->session->userdata("iduser"));
        $this->datatables->where("k.tahun", $this->input->post("tahun"));
        $this->datatables->group_by("pm.id");


        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['no'] = $no;
            $tmp['tema'] = $dp['tema'] . "/ " . $dp['tahun'] . "<div style='font-size:12px'><span class='badge bg-secondary'>" . $this->config->item("app_singkatan") . " " . $dp['jenis'] . "</span></div>";
            $tmp['tempat'] = $dp['tempat'];
            $tmp['tahun'] = $dp['tahun'];
            $tmp['namakelompok'] = $dp['namakelompok'];
            $tmp['urut'] = $dp['urut'];
            $tmp['nilai_angka'] = "<input title='0 sd 100' type='number' class='form-control validate[required] nilaimhs' style='width:85px;' data-idnilai='" . $dp['idnilai'] . "' data-idpenempatan='" . $dp['idpenempatan'] . "' data-idmst_penilaian='1'  value='" . $dp['nilai_angka'] . "'>
                                    <div class='btn-group mt-3' role='group' >
                                        <a title='LKH Mahasiswa' target='_blank' href='" . base_url("dashboard/personal/" . $dp['idpenempatan']) . "' class='btn btn-primary'><i class='bi bi-journal-album'></i> " . $dp['jumaktifitas'] . "</a>
                                        <a title='Output' target='_blank' href='" . base_url("file/detail_output/" . $dp['idpenempatan']) . "' class='btn btn-secondary'><i class='bi bi-paperclip'></i> " . $dp['jumupload'] . " </a>
                                    </div>";

            $tmp['desa'] = $dp['desa'];
            $tmp['kecamatan'] = $dp['kecamatan'];

            $tmp['jabatan'] = $dp['jabatan'];
            $tmp['datamhs'] = "<div class='row'>
                                    <div class='col-4'>    
                                        <img src='" . base_url($dp['profilpic']) . "' width='100%'>
                                    </div>
                                    <div class='col-8'>    
                                        " . $dp['nama'] . "/ <span style='font-size:12px;'> NIM. " . $dp['nim'] . "/ <span class='badge bg-success'>" . $dp['kel'] . "</span></span>
                                    </div>
                                </div>";
            $tmp['prodi'] = $dp['prodi'];

            $tmp['nama'] = $dp['nama'];
            $tmp['nim'] = $dp['nim'];

            $tmp['nilai_akhir'] = $dp['nilai_angka'];
            $tmp['kel'] = $dp['kel'];

            $tmp['pelaksanaan'] = "<div style='font-size:12px'>" . $dp['kknmulai'] . " sd " . $dp['kknselesai'] . " " . labeltanggal($dp['kknmulai'], $dp['kknselesai'])['labelbadge'] . "</div>";
            $tmp['kelompok'] = "<div class='row'>
                                    <div class='col-md-12'>
                                        Kelompok " . $dp['namakelompok'] . "
                                        <div style='font-size:11px'><span class='badge bg-secondary'>" . $dp['jabatan'] . "</span></div>
                                        <div style='font-size:12px'>
                                            <span class='badge bg-success'>" . strtoupper($dp['desa']) . "</span>
                                            <span class='badge bg-success'>" . strtoupper($dp['kecamatan']) . "</span>
                                            <span class='badge bg-success'>" . $dp['kabupaten'] . "</span>
                                            <span class='badge bg-success'>" . $dp['provinsi'] . "</span>
                                        </div>    
                                    </div>";
            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }


    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => "");
        allowheader();

        $this->form_validation->set_rules('idpenempatan', 'penempatan', 'trim|required');
        $this->form_validation->set_rules('idmst_penilaian', 'jenis penilaian', 'trim|required');
        $this->form_validation->set_rules('nilai_angka', 'nilai mahasiswa', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idnilai'];
            unset($dataSave['idnilai']);

            if ($id == "" && akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
            } elseif ($id <> "" && akses_akun("update", $this->otentikasi, $this->d['tbName'], $id)->status) {
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
}
