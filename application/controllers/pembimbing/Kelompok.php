<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok extends CI_Controller
{
    private $d = array(
        "tbName" => "penempatan",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Pembimbing",
            "parent" => array(""),
            "modul" => "pembimbing/kelompok",
            "view"  => "pembimbing",
            "page"  => "Pembimbing",
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

        $this->d['web']['importJs'] = array(
            base_url('assets/web/pembimbing/kelompok.js?' . date("ymdhis")),
        );

        $this->load->view('app/index', $this->d);
    }

    public function read()
    {
        $this->load->library('Datatables');
        $this->load->library('Dataweb');

        $thn = date("Y");
        $vCari = array(
            array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata("iduser")),
            array("cond" => "where_in", "fld" => "k.tahun", "val" => array($thn + 1, $thn, $thn - 1)),
        );
        $aktifitas = $this->dataweb->kelompok_terkaktif($vCari);

        $this->datatables->from("kelompok as kel");
        $this->datatables->select("
			'' as no, '' as aksi, 
            kel.id as idkelompok,kel.keterangan,kel.namakelompok,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as dsnpembimbing, u.hp, u.nik, u.kel, u.path as profilpic, u.email,
            pk.id as idpembimbing_kkn,
            dsa.id as iddesa,
            kec.id as idkecamatan,
            kab.id as idkabupaten,
            sp.idkkn,
            prov.id as idprovinsi,
            k.tahun, k.tema,
            dsa.desa, kec.kecamatan, kab.kabupaten, prov.provinsi,
            pm.id as idpenempatan,pm.idpeserta,pm.idjabatan,
            COUNT(pm.id) as jumlahpeserta,
            sp.path,
            k.kknmulai,k.kknselesai,k.tempat,k.jenis,
            ad.jumlah as jumlahaktifitas,
            po.latitude, po.longitude, po.alamat, po.path as fotoposko, po.proker,
		");

        $this->datatables->join("posko as po", "po.idkelompok=kel.id", "left");
        $this->datatables->join("penempatan as pm", "pm.idkelompok=kel.id", "left");
        $this->datatables->join("(SELECT count(id) as jumlah, idkelompok FROM aktifitas_dpl GROUP BY idkelompok) as ad", "ad.idkelompok=kel.id", "left");
        $this->datatables->join("lokasi as l", "l.id=kel.idlokasi", "left");

        $this->datatables->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->datatables->join("wilayah_kec as kec", "kec.id=dsa.idkecamatan", "left");
        $this->datatables->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->datatables->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");

        $this->datatables->join("pembimbing_kkn as pk", "pk.id=kel.idpembimbing_kkn", "left");
        $this->datatables->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "left");
        $this->datatables->join("pembimbing as p", "p.id=pk.idpembimbing", "left");
        $this->datatables->join("kkn as k", "k.id=sp.idkkn", "left");
        $this->datatables->join("hakakses as h", "h.id=p.idhakakses", "left");
        $this->datatables->join("user as u", "u.id=h.iduser", "left");
        $this->datatables->where("DATE_ADD(NOW(), INTERVAL 8 HOUR)>=k.bagikelompok", null);
        $this->datatables->where("u.id", $this->session->userdata("iduser"));
        $this->datatables->group_by("kel.id");


        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $dtkelompok = searchMultiArray($aktifitas['db'], "idkelompok", $dp['idkelompok']);

            $namakelompok = null;
            $jumkegiatan = 0;
            $idkelompok = null;
            if (isset($dtkelompok[0])) {
                $kel = $dtkelompok[0];
                $namakelompok = $kel['namakelompok'];
                $jumkegiatan = $kel['jumpopuler'];
                $idkelompok = $kel['idkelompok'];
            }

            $jumaktifitas = (int)$dp['jumlahaktifitas'];


            $urlposko = null;
            if ($dp['latitude'] && $dp['longitude'])
                $urlposko = "<a href='https://www.google.com/maps/search/?api=1&query=" . $dp['latitude'] . "," . $dp['longitude'] . "' target='_blank'><i class='bi bi-map'></i> Lokasi Posko</a>";

            $fotoposko = null;
            if ($dp['fotoposko'])
                $fotoposko = "<img src='" . base_url($dp['fotoposko']) . "' width='200px'>";

            $proker = "Proker belum ada";
            if ($dp['fotoposko'])
                $proker = $dp['proker'];

            $tmp['no'] = $no;
            $tmp['tema'] = $dp['tema'];
            $tmp['tempat'] = $dp['tempat'];
            $tmp['jenis'] = $dp['jenis'];
            $tmp['proker'] = $fotoposko . "<div style='font-size:12px;'>" . $proker . "</div>";
            $tmp['alamat'] = $dp['alamat'];
            $tmp['tahun'] = $dp['tahun'];
            $tmp['namakelompok'] = $dp['namakelompok'];
            $tmp['desa'] = $dp['desa'];
            $tmp['pelaksanaan'] = "<div style='font-size:12px'>" . $dp['kknmulai'] . " sd " . $dp['kknselesai'] . " " . labeltanggal($dp['kknmulai'], $dp['kknselesai'])['labelbadge'] . "</div>";
            $tmp['temakkn'] = "<div style='font-size:12px'><span class='badge bg-secondary'>" . $this->config->item("app_singkatan") . " " . $dp['jenis'] . "</span>" . $dp['tema'] . "/ " . $dp['tempat'] . "</div>";
            $tmp['kelompok'] = "<div class='row'>
                                    <div class='col-md-12'>
                                        <h6><a href='" . base_url("dashboard/kelompok/" . $dp['idkelompok']) . "' target='_blank'>Kelompok " . $dp['namakelompok'] . "</a></h6>
                                        <div style='font-size:12px'>
                                            <span class='badge bg-success'>" . strtoupper($dp['desa']) . "</span>
                                            <span class='badge bg-success'>" . strtoupper($dp['kecamatan']) . "</span>
                                            <span class='badge bg-success'>" . $dp['kabupaten'] . "</span>
                                            <span class='badge bg-success'>" . $dp['provinsi'] . "</span>
                                        </div>
                                        " . $urlposko . " 
                                    </div>";
            $tmp['jumlahpeserta'] = "<div style='font-size:13px;'>
                                        <span class='badge bg-info' title='jumlah peserta'><i class='bi bi-person-check'></i> " . $dp['jumlahpeserta'] . "</span>  
                                        <span class='badge bg-secondary' title='jumlah aktifitas'><i class='bi bi-arrow-up-right'></i> " . $jumkegiatan . "</span>
                                    </div>";
            $tmp['aksi'] = "<div class='btn-group' role='group'>
                                <a title='Aktifitas DPL' href='" . base_url("pembimbing/aktifitas/" . $dp['idkelompok']) . "' class='btn btn-primary' style='font-size:11px'><i class='bi bi-journal-text'></i> Aktifitas DPL (" . $jumaktifitas . ")</a>
                            </div>";
            $data[] = $tmp;
        }

        $retVal['data'] = $data;
        die(json_encode($retVal));
    }

    public function loadkelompok()
    {
        allowheader();
        $this->load->library('Dataweb');
        $cari = array(
            array("cond" => "where", "fld" => "sp.idkkn", "val" => $this->input->post('idkkn')),
        );
        $retVal = $this->dataweb->daftarkelompok($cari);
        die(json_encode($retVal));
    }
}
