<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Web extends CI_Controller
{
    private $d = array(
        "web" => array(
            "title" => "Website KKN Reborn",
            "modul" => "web",
            "view"  => "",
            "page"  => "Website",
        ),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->library('parser');
    }

    public function session()
    {
        debug($this->session->userdata());
    }


    public function index()
    {
        $this->load->library("Dataweb");

        $this->d['web']['importPlugins'] = array(
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
            loadPlugins("viewerjs"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/web.js?' . date("ymdhis")),
        );
        $data = array();

        //cari berita terakhir
        $daftarberita = $this->dataweb->daftarberita([], null, 4);
        $this->d['daftarberita'] = $daftarberita['db'];
        //end cari berita terakhir

        //cari komentar terakhir
        $daftarkomentar = $this->dataweb->daftarkomentar([], null, 10);
        $this->d['daftarkomentar'] = $daftarkomentar['db'];
        //end cari komentar terakhir

        //cari kkn
        $thn = array(date("Y"), (date("Y") - 1));
        //print_r($thn);
        $cari = array(
            array("cond" => "where_in", "fld" => "YEAR(k.kknmulai)", "val" => $thn),
        );
        $this->d['kkn'] = $this->dataweb->cariKkn($cari);
        //end cari kkn

        $this->d['web']['loadview'] = "web";
        $this->d['lastlogin'] = $this->dataweb->lastlogin([], null, 10);
        //debug($this->d);
        $this->load->view("index", $this->d);
    }

    public function cek_peserta()
    {
        $this->load->library("Dataweb");
        $this->dataweb->cek_peserta();
    }


    public function detailberita($idberita = null)
    {
        $this->load->library("Dataweb");

        $this->d['web']['importPlugins'] = array(
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $data = array();

        //cari berita terakhir
        $vCari = array(
            array("cond" => "where", "fld" => "(b.id='" . $idberita . "' OR b.slug='" . $idberita . "')", "val" => null),
        );
        $detailberita = $this->dataweb->daftarberita($vCari);
        if (!$detailberita['status']) {
            redirect("web");
        }
        $this->d['detailberita'] = $detailberita['db'][0];
        //end cari berita terakhir

        $this->d['web']['loadview'] = "detailberita";
        $this->load->view("index", $this->d);
    }

    public function peserta($idkkn = null)
    {
        $this->d['web']['title'] = "Peserta " . $this->d['web']['title'] . " " . $this->config->item("app_singkatan");
        $this->d['web']['loadview'] = "daftar/peserta";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        //cari kkn
        $this->load->library("dataweb");
        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
        );
        $datakkn = $this->dataweb->cariKkn($vCari);
        if (!$datakkn['status'] || !$idkkn) {
            redirect("web");
        }
        $this->d['datakkn'] = $datakkn['db'][0];

        //$d['pembimbing'] = $this->datapembimbing();
        $this->d['web']['importJs'] = array(
            base_url('assets/web/daftar/peserta.js?' . date("ymdhis")),
        );
        $this->d['idkkn'] = $idkkn;

        $this->load->view('index', $this->d);
    }

    public function dpl($idkkn = null)
    {
        $this->d['web']['title'] = "DPL " . $this->d['web']['title'] . " " . $this->config->item("app_singkatan");
        $this->d['web']['loadview'] = "daftar/dpl";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        //cari kkn
        $this->load->library("dataweb");
        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
        );
        $datakkn = $this->dataweb->cariKkn($vCari);
        if (!$datakkn['status'] || !$idkkn) {
            redirect("web");
        }
        $this->d['datakkn'] = $datakkn['db'][0];


        //$d['pembimbing'] = $this->datapembimbing();
        $this->d['web']['importJs'] = array(
            base_url('assets/web/daftar/dpl.js?' . date("ymdhis")),
        );
        $this->d['idkkn'] = $idkkn;

        $this->load->view('index', $this->d);
    }

    public function lokasi($idkkn = null)
    {
        $this->d['web']['title'] = "Lokasi " . $this->d['web']['title'] . " " . $this->config->item("app_singkatan");
        $this->d['web']['loadview'] = "daftar/lokasi";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        //cari kkn
        $this->load->library("dataweb");
        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
        );
        $datakkn = $this->dataweb->cariKkn($vCari);
        if (!$datakkn['status'] || !$idkkn) {
            redirect("web");
        }
        $this->d['datakkn'] = $datakkn['db'][0];


        //$d['pembimbing'] = $this->datapembimbing();
        $this->d['web']['importJs'] = array(
            base_url('assets/web/daftar/lokasi.js?' . date("ymdhis")),
        );
        $this->d['idkkn'] = $idkkn;

        $this->load->view('index', $this->d);
    }

    public function kuesioner($idkkn = null)
    {
        $this->d['web']['title'] = "Kuesioner " . $this->d['web']['title'] . " " . $this->config->item("app_singkatan");
        $this->d['web']['loadview'] = "daftar/kuesioner";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        //cari kkn
        $this->load->library("dataweb");
        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
        );
        $datakkn = $this->dataweb->cariKkn($vCari);
        if (!$datakkn['status'] || !$idkkn) {
            redirect("web");
        }
        $this->d['datakkn'] = $datakkn['db'][0];


        //$d['pembimbing'] = $this->datapembimbing();
        $this->d['web']['importJs'] = array(
            base_url('assets/web/daftar/kuesioner.js?' . date("ymdhis")),
        );
        $this->d['idkkn'] = $idkkn;

        $this->load->view('index', $this->d);
    }

    public function profil($idjenis_profil = null)
    {
        $this->load->library("dataweb");
        $vCari = array(
            array("val" => $idjenis_profil, "fld" => "id", "cond" => "where"),
        );
        $profil = $this->dataweb->dataglobal("jenis_profil", $vCari);
        if (!$profil['status']) {
            redirect("web");
        }
        $this->d['jenisprofil'] = $profil['db'][0];

        //cari konten profil
        $vCari = array(
            array("val" => $idjenis_profil, "fld" => "j.id", "cond" => "where"),
        );
        $dataprofil = $this->dataweb->dataprofil($vCari);
        if (!$dataprofil['status']) {
            redirect("web");
        }
        $this->d['dataprofil'] = $dataprofil['db'];

        $this->d['web']['title'] = "Profil " . $this->d['web']['title'] . " " . $this->config->item("app_singkatan") . " - " . $this->d['jenisprofil']['jenis'];
        $this->d['web']['loadview'] = "profil";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->load->view('index', $this->d);
    }

    public function dokumen($dokumen = "berkas")
    {
        $this->d['web']['title'] = "Daftar Dokumen " . $this->config->item("app_singkatan");
        $this->d['web']['loadview'] = "daftar/list_upload";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/daftar/list_upload.js?' . date("ymdhis")),
        );
        $this->d['dokumen'] = $dokumen;

        $this->load->view('index', $this->d);
    }

    public function lokasiposko($idkkn = null)
    {
        $this->load->library("Dataweb");
        $this->d['web']['loadview'] = "lokasiposko";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("sweetalert"),
            loadPlugins("leaflet"),
            loadPlugins("loading"),
            loadPlugins("myapp"),
        );
        $this->d['web']['importJs'] = array(
            base_url('assets/web/lokasiposko.js?' . date("ymdhis")),
        );
        $this->d['idkkn'] = $idkkn;
        $this->load->view('index', $this->d);
    }

    public function map()
    {
        $this->load->view('map');
    }

    public function read_peserta()
    {
        allowheader();
        $this->load->library('Datatables');

        $idkkn = $this->input->post('idkkn');
        $isadmin = $this->input->post('isadmin');

        $this->datatables->from("peserta as ps");
        $this->datatables->select("
			'' as no, '' as aksi, 
            u.id as iduser, 
            u.nama, 
            TRIM(CONCAT(peg.glrdepan,' ',peg.nama,' ',peg.glrbelakang)) as dpl,
            u.nik, u.kel, u.path as profilpic, u.hp, u.email, u.aktivasi, u.tmplahir, u.tgllahir, u.alamat,
            m.id as idmahasiswa, m.nim, m.path as kartumhspic, prodi.id as idprodi, fak.id as idfakultas, prodi.prodi, fak.fakultas,
            k.tahun,k.tema,k.angkatan,k.jenis,k.tempat, ps.id as idpeserta,
            k.kknmulai,k.kknselesai,
            p.id as idpendaftar, k.id as idkkn, jb.jabatan,jb.id as idjabatan,
            pm.idkelompok,pm.id as idpenempatan, IF(pm.id IS NULL,0,1) as statuspenempatan,
            kl.namakelompok, kec.kecamatan, kab.kabupaten, prov.provinsi,
            dsa.id as iddesa,dsa.desa,l.lastlogin,
            IF(DATE_ADD(NOW(), INTERVAL 8 HOUR)>=k.bagikelompok,'terbuka','tertutup') as ketpublishkelompok,
		");


        $this->datatables->join("penempatan as pm", "pm.idpeserta=ps.id", "left");
        $this->datatables->join("kelompok as kl", "kl.id=pm.idkelompok", "left");
        $this->datatables->join("lokasi as l", "l.id=kl.idlokasi", "left");

        $this->datatables->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->datatables->join("wilayah_kec as kec", "kec.id=dsa.idkecamatan", "left");
        $this->datatables->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->datatables->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");


        $this->datatables->join("mst_jabatan as jb", "pm.idjabatan=jb.id", "left");
        $this->datatables->join("pendaftar as p", "p.id=ps.idpendaftar", "left");
        $this->datatables->join("kkn as k", "k.id=p.idkkn", "left");

        //pembimbing
        $this->datatables->join("pembimbing_kkn as pk", "pk.id=kl.idpembimbing_kkn", "left");
        $this->datatables->join("pembimbing as pb", "pk.idpembimbing=pb.id", "left");
        $this->datatables->join("hakakses as hk", "hk.id=pb.idhakakses", "left");
        $this->datatables->join("user as peg", "peg.id=hk.iduser", "left");

        $this->datatables->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        $this->datatables->join("user as u", "u.id=m.iduser", "left");
        $this->datatables->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->datatables->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");
        $this->datatables->join("(SELECT MAX(lastlogin) as lastlogin,iduser FROM login_history GROUP BY iduser) as l", "l.iduser=u.id", "left");

        $this->datatables->where("k.id", $idkkn);
        // $this->datatables->group_by("ps.id");

        $retVal = json_decode($this->datatables->generate(), true);
        // echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['no'] = $no;
            $profilpic = ($dp['profilpic']) ? "<a href='" . base_url($dp['profilpic']) . "' target='_blank'><img src='" . base_url($dp['profilpic']) . "?" . rand(1000, 9999) . "' width='100%'></a>" : "";

            $tmp['nama'] = $dp['nama'];
            $tmp['nim'] = $dp['nim'];
            $tmp['kel'] = "<span class='badge bg-primary'>" . $dp['kel'] . "</span>";

            $email = $dp['email'];
            $phone = $dp['hp'];

            if (!$this->session->userdata('iduser')) {
                $email = preg_replace('/(?<=.)[^@](?=[^@]*?@)|(?:(?<=@.)|(?!^)\G(?=[^@]*$)).(?=.*\.)/', 'x', $email);
                $phone = preg_replace('/(\d{3})\d{4}(\d{3})/', '$1xxxx$2', $phone);
            }

            $tmp['email'] = $email;
            $tmp['prodi'] = $dp['prodi'];
            $tmp['desa'] = $dp['desa'];
            $tmp['dpl'] = $dp['dpl'];
            $tmp['namakelompok'] = str_pad($dp['namakelompok'], 3, '0', STR_PAD_LEFT);
            $tmp['lastlogin'] = $dp['lastlogin'];

            $tmp['detmahasiswa'] = "<div class='row'>
                                        <div class='col-3'>" . $profilpic . "</div>
                                        <div class='col-9'>
                                            <h6><a href='" . base_url('dashboard/personal/' . $dp['idpenempatan']) . "'>" . $dp['nama'] . "</a></h6> 
                                            <div style='font-size:13px'>" . $dp['prodi'] . "</div>
                                            <div style='font-size:12px'>NIM." . $dp['nim'] . "</div>
                                            <div style='font-size:12px'>Email : " . $email . "</div>
                                            <div class='text-muted mb-0' style='font-size:12px'>
                                                <span class='badge bg-success'><i class='bi bi-clock'></i> " . waktu_lalu($dp['lastlogin']) . "</span>
                                            </div>
                                        </div>
                                    </div>";

            if ($isadmin) {
                $tmp['provinsi'] = $dp['provinsi'];
                $tmp['kabupaten'] = $dp['kabupaten'];
                $tmp['kecamatan'] = $dp['kecamatan'];
                $tmp['desa'] = $dp['desa'];
            }
            $tmp['jabatan'] = $dp['jabatan'];
            $tmp['hp'] = $phone;
            $tmp['email'] = $email;
            $tmp['detkelompok'] = "Belum";
            if ($dp['ketpublishkelompok'] == 'terbuka' && $dp['namakelompok'] != "") {
                $tmp['detkelompok'] = " <h5>" . $dp['jabatan'] . "</h5>
                                        <h6><a href='" . base_url('dashboard/kelompok/' . $dp['idkelompok']) . "'>Kelompok " . $dp['namakelompok'] . "</a></h6>
                                        <div style='font-size:12px'>Provinsi " . $dp['provinsi'] . "</div>                                        
                                        <div style='font-size:12px'>Kabupaten " . $dp['kabupaten'] . "</div>                                        
                                        <div style='font-size:12px'>Kecamatan " . $dp['kecamatan'] . "</div>
                                        <div style='font-size:12px'>Kelurahan/Desa " . $dp['desa'] . "</div>";
            }
            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }

    public function read_dokumen()
    {
        allowheader();
        $this->load->library('Datatables');

        $dokumen = $this->input->post('dokumen');

        $this->datatables->from("upload as up");
        $this->datatables->select("
			'' as no, '' as dokumen,
            up.waktu,up.id as idupload, up.judul, up.path, up.fileinfo, up.is_image,up.keterangan, up.publish, 
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
            u.nik, u.kel, u.path as profilpic, u.hp, u.email, u.aktivasi, u.tmplahir, u.tgllahir, u.alamat,
		");
        $this->datatables->join("user as u", "u.id=up.owned", "left");
        //echo $dokumen;
        $this->datatables->where("up.publish", "1");
        if ($dokumen != "images") {
            $this->datatables->where("up.is_image", "0");
        } else {
            $this->datatables->where("up.is_image", "1");
        }

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['no'] = $no;
            $profilpic = ($dp['profilpic']) ? "<a href='" . base_url($dp['profilpic']) . "' target='_blank'><img src='" . base_url($dp['profilpic']) . "?" . rand(1000, 9999) . "' width='100%'></a>" : "";

            $tmp['no'] = $no;
            $path = "";
            $go = "#";
            if ($dp['path']) {
                $go = base_url($dp['path']);
                $fileinfo = json_decode($dp['fileinfo'], true);
                $path = "<a href='" . base_url($dp['path']) . "' target='_blank'><img src='" . base_url("assets/img/files.png") . "?" . rand(1000, 9999) . "' width='100%'></a>";
                if ($dp['is_image'])
                    $path = ($dp['path']) ? "<a href='" . base_url($dp['path']) . "' target='_blank'><img src='" . base_url($dp['path']) . "?" . rand(1000, 9999) . "' width='100%'></a>" : "";
            }

            $btn_download = "<a href='" . $go . "' target='_blank   ' class='btn btn-success rounded-pill'><i class='bi bi-download'></i> Download</a>";
            $btn_download .= "<div style='font-size:12px'>";
            $btn_download .= "<b>original file name : " . $fileinfo['client_name'] . "</b><br>";
            $btn_download .= "type : " . $fileinfo['file_ext'] . "<br>";
            $btn_download .= "size : " . $fileinfo['file_size'];
            $btn_download .= "</div>";

            $dokumen = "<div class='row'>";
            $dokumen .= "<div class='col-sm-2'>" . $path . "</div>";
            $dokumen .= "<div class='col-sm-10'>";
            $dokumen .= "<div style='font-size:12px'>" . $dp['waktu'] . "</div>";
            $dokumen .= "<h5>" . $dp['judul'] . " <span style='font-size:11px'><i class='bi bi-clock'></i> " . waktu_lalu($dp['waktu']) . "</span></h5>";
            $dokumen .= "<i>" . $dp['keterangan'] . "</i>";
            $dokumen .= $btn_download;
            $dokumen .= "</div>";
            $dokumen .= "</div>";

            $tmp['judul'] = $dp['judul'];
            $tmp['waktu'] = $dp['waktu'];
            $tmp['nama'] = $dp['nama'];
            $tmp['keterangan'] = $dp['keterangan'];

            $tmp['dokumen'] = $dokumen;

            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }

    public function read_dpl()
    {
        allowheader();
        $this->load->library('Datatables');

        $idkkn = $this->input->post('idkkn');

        $this->datatables->from("pembimbing_kkn as pk");
        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            k.id as idkelompok,k.keterangan,k.namakelompok,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path as profilpic, u.email,
            dsa.id as iddesa,
            kec.id as idkecamatan,
            kab.id as idkabupaten,
            sp.idkkn,p.nip,
            prov.id as idprovinsi,
            dsa.desa, kec.kecamatan, kab.kabupaten, prov.provinsi,
            pm.id as idpenempatan,pm.idpeserta,pm.idjabatan,
            l.lastlogin,
            IF(DATE_ADD(NOW(), INTERVAL 8 HOUR)>=kn.bagikelompok,'terbuka','tertutup') as ketpublishkelompok,

		");
        $this->datatables->join("kelompok as k", "pk.id=k.idpembimbing_kkn", "left");

        $this->datatables->join("penempatan as pm", "pm.idkelompok=k.id", "left");
        $this->datatables->join("lokasi as l", "l.id=k.idlokasi", "left");

        $this->datatables->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->datatables->join("wilayah_kec as kec", "kec.id=dsa.idkecamatan", "left");
        $this->datatables->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->datatables->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");

        $this->datatables->join("pembimbing as p", "p.id=pk.idpembimbing", "left");
        $this->datatables->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "left");
        $this->datatables->join("hakakses as h", "h.id=p.idhakakses", "left");
        $this->datatables->join("user as u", "u.id=h.iduser", "left");
        $this->datatables->join("kkn as kn", "kn.id=sp.idkkn", "left");
        $this->datatables->join("(SELECT MAX(lastlogin) as lastlogin,iduser FROM login_history GROUP BY iduser) as l", "l.iduser=u.id", "left");
        $this->datatables->where("sp.idkkn", $idkkn);
        $this->datatables->group_by("k.id");

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['no'] = $no;
            $profilpic = ($dp['profilpic']) ? "<a href='" . base_url($dp['profilpic']) . "' target='_blank'><img src='" . base_url($dp['profilpic']) . "?" . rand(1000, 9999) . "' width='100%'></a>" : "";

            $tmp['nama'] = $dp['nama'];
            $tmp['nip'] = $dp['nip'];
            $tmp['kel'] = "<span class='badge bg-primary'>" . $dp['kel'] . "</span>";

            $email = $dp['email'];
            if (!$this->session->userdata('iduser')) {
                $email = preg_replace('/(?<=.)[^@](?=[^@]*?@)|(?:(?<=@.)|(?!^)\G(?=[^@]*$)).(?=.*\.)/', 'x', $email);
            }


            $tmp['email'] = $email;
            $tmp['desa'] = $dp['desa'];
            $tmp['namakelompok'] = $dp['namakelompok'];
            $tmp['lastlogin'] = $dp['lastlogin'];

            $tmp['detdpl'] = "<div class='row'>
                                        <div class='col-3'>" . $profilpic . "</div>
                                        <div class='col-9'>
                                            <h6>" . $dp['nama'] . "</h6> 
                                            <div style='font-size:12px'>NIP/NIDN : " . $dp['nip'] . "</div>
                                            <div style='font-size:12px'>Email : " . $email . "</div>
                                            <div class='text-muted mb-0' style='font-size:12px'>
                                                <span class='badge bg-success'><i class='bi bi-clock'></i> " . waktu_lalu($dp['lastlogin']) . "</span>
                                            </div>
                                        </div>
                                    </div>";

            $tmp['detkelompok'] = "Belum";
            if ($dp['ketpublishkelompok'] == 'terbuka' && $dp['namakelompok'] != "") {
                $tmp['detkelompok'] = " <h6><a href='" . base_url('dashboard/kelompok/' . $dp['idkelompok']) . "'>Kelompok " . $dp['namakelompok'] . "</a></h6>
                                        <div style='font-size:12px'>Provinsi " . $dp['provinsi'] . "</div>                                        
                                        <div style='font-size:12px'>Kabupaten " . $dp['kabupaten'] . "</div>                                        
                                        <div style='font-size:12px'>Kecamatan " . $dp['kecamatan'] . "</div>
                                        <div style='font-size:12px'>Kelurahan/Desa " . $dp['desa'] . "</div>";
            }
            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }


    public function read_lokasi()
    {
        allowheader();
        $this->load->library('Datatables');

        $idkkn = $this->input->post('idkkn');

        $this->datatables->from("lokasi as l");
        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            dsa.id as iddesa,
            kec.id as idkecamatan,
            kab.id as idkabupaten,
            prov.id as idprovinsi,
            dsa.desa, kec.kecamatan, kab.kabupaten, prov.provinsi,
		");
        $this->datatables->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->datatables->join("wilayah_kec as kec", "kec.id=dsa.idkecamatan", "left");
        $this->datatables->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->datatables->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");

        $this->datatables->join("kkn as kn", "kn.id=l.idkkn", "left");
        $this->datatables->where("l.idkkn", $idkkn);

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['no'] = $no;

            $tmp['provinsi'] = $dp['provinsi'];
            $tmp['kecamatan'] = $dp['kecamatan'];
            $tmp['kabupaten'] = $dp['kabupaten'];
            $tmp['desa'] = $dp['desa'];
            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }

    public function read_kuesioner()
    {
        allowheader();
        $this->load->library('Datatables');

        $idkkn = $this->input->post('idkkn');

        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            e.id,e.idkkn,e.judul,e.link,e.tujuan,e.keterangan,
            CONCAT(e.tujuan,' ',e.judul) as urut,
		");
        $this->datatables->where("e.idkkn", $idkkn);
        $this->datatables->from("evaluasi as e");
        $this->datatables->join("kkn as k", "e.idkkn=k.id", "left");

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['no'] = $no;

            $tmp['id'] = $dp['id'];
            $tmp['idkkn'] = $dp['idkkn'];
            $tmp['urut'] = $dp['urut'];
            $tmp['judul'] = $dp['judul'];
            $tmp['link'] = '<a href="' . $dp['link'] . '" target="_blank" class="btn btn-danger rounded-pill"><i class="bi bi-ui-checks"></i> Instrumen</a>';
            $tmp['tujuan'] = $dp['tujuan'];
            $tmp['keterangan'] = $dp['keterangan'];
            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }
}
