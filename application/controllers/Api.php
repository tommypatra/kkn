<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    private $retVal = array("status" => false, "pesan" => "", "db" => []);

    function __construct()
    {
        parent::__construct();
        allowheader();
    }

    public function carigeneral()
    {
        $retVal = $this->retVal;

        $tbName = $this->input->post('vTable');
        $vOrder = $this->input->post('vOrder');
        $vCari = $this->input->post('vCari');
        $vField = $this->input->post('vField');
        $vField = ($vField <> "") ? $vField : "*";

        $this->db->from($tbName);
        $this->db->select($vField);
        if ($vCari) {
            foreach ($vCari as $val => $cariSql) {
                if ($cariSql['cond'] == "like")
                    $this->db->like($cariSql['fld'], $cariSql['val'], "both");
                else
                    $this->db->where($cariSql['fld'], $cariSql['val']);
            }
        }
        if ($vOrder) {
            $this->db->order_by($vOrder);
        }
        $sql = $this->db->get();
        //echo $this->db->last_query();
        if ($sql->num_rows() > 0) {
            $retVal['status'] = true;
            $retVal['db'] = $sql->result_array();
        }
        die(json_encode($retVal));
    }
    public function cariakun()
    {
        $this->load->library("dataweb");
        $vCari = $this->input->post('vCari');
        //debug($vCari);
        $retVal = $this->dataweb->cariGrupUser($vCari);
        die(json_encode($retVal));
    }

    public function outputkkn()
    {
        $this->load->library("dataweb");
        $vCari = $this->input->post();
        $retVal = $this->dataweb->dataoutput($vCari);
        die(json_encode($retVal));
    }

    public function useraktif()
    {
        $retVal = useraktif(5);
        die(json_encode($retVal));
    }

    public function berkaspendaftar_html($dtsql = [])
    {
        $retVal = "<h6>Verifikasi Dokumen</h6>";
        //$statusVer = "MS";
        $idgrup = json_decode($this->session->userdata('idgrup'));
        foreach ($dtsql as $i => $dp) {
            $uploadtype = "<span class='badge bg-primary'>Tidak Wajib Upload</span>";
            $detupload = "";
            $btnUpload = "";
            $ukuranFile = "";

            $modeinput = false;
            if ($dp['status'] != "MS")
                $modeinput = true;

            $pilVer = "";
            if (in_array(1, $idgrup)) {
                $pil = array("", "MS", "TMS");
                $pilVer = "<input type='hidden' name='idupload[" . $i . "]' value='" . $dp['idupload'] . "' >";
                $pilVer .= "<input type='hidden' name='idadministrasi[" . $i . "]' value='" . $dp['idadministrasi'] . "'>";
                $pilVer .= "<select class='form-select validate[required] verBerkas' name='verBerkas[" . $i . "]' data-idpendaftar='" . $dp['idpendaftar'] . "' data-idupload='" . $dp['idupload'] . "' data-idadministrasi='" . $dp['idadministrasi'] . "'>";
                foreach ($pil as $dtpil) {
                    $pilih = "";
                    if ($dp['status'] == $dtpil)
                        $pilih = "selected";
                    $pilVer .= "<option value='" . $dtpil . "' " . $pilih . ">" . $dtpil . "</option>";
                }
                $pilVer .= "</select>";
            } else {
                $pilVer = "<span class='badge bg-primary'>Belum diverifikasi</span>";
                if ($dp['status'] == "MS")
                    $pilVer = "<span class='badge bg-success'>Memenuhi Syarat</span>";
                elseif ($dp['status'] == "TMS")
                    $pilVer = "<span class='badge bg-danger'>Tidak Memenuhi</span>";
            }
            $detupload = $pilVer;

            if ($dp['upload_file'] == 'y') {
                $uploadtype = "<span class='badge bg-success'>Wajib Upload " . $dp['upload_type'] . "</span>";
                $ukuranFile = "(Max " . ($dp['upload_size'] / 1000) . "Mb)";

                $detupload .= "<hr>";

                if ($dp['path'] != "") {
                    $det = json_decode($dp['fileinfo'], true);

                    $detupload .= "<a href='" . base_url("file/read/" . $dp['idupload']) . "/berkas_administrasi' target='_blank'>" . $det['file_name'] . "</a><br>";
                    $detupload .= "(" . number_format(($det['file_size'] / 1000), 2) . " Mb) ";
                    if ($dp['ketpendaftaran'] && in_array(4, $idgrup) && $modeinput)
                        $detupload .= "<button type='button' class='btn btn-outline-danger btn-sm btn-hapus-upload' data-idpendaftar='" . $dp['idpendaftar'] . "' data-idupload='" . $dp['idupload'] . "' data-idadministrasi='" . $dp['idadministrasi'] . "' ><i class='bi bi-x-lg'></i></button>";
                }
                if ($dp['ketpendaftaran'] && in_array(4, $idgrup) && $modeinput)
                    $btnUpload = "<button type='button' class='btn btn-outline-success btn-sm btn-upload' data-idpendaftar='" . $dp['idpendaftar'] . "' data-idupload='" . $dp['idupload'] . "' data-idadministrasi='" . $dp['idadministrasi'] . "' ><i class='bi bi-upload'></i></button>";
            }

            $retVal .= "<div class='row'>";
            $retVal .= "<div class='col-md-1'>" . ($i + 1) . "</div>";
            $retVal .= "<div class='col-md-4'>" . $dp['namaadministrasi'] . "<br>" . $uploadtype . " " . $btnUpload . "</div>";
            $retVal .= "<div class='col-md-4' style='font-style: italic;'>" . $dp['keterangan'] . " " . $ukuranFile . "</div>";
            $retVal .= "<div class='col-md-3'>" . $detupload . "</div>";
            $retVal .= "</div>";
            $retVal .= "<hr>";
        }
        return $retVal;
    }

    public function identitasmhs_html($dtsql = [])
    {
        $retVal = "<div class='row'>";
        foreach ($dtsql as $i => $dp) {
            $retVal .= "<div class='row'>";
            $retVal .= "<div class='col-md-2'><img src='" . base_url($dp['pasfoto']) . "' width='100%'></div>";
            $retVal .= "<div class='col-md-4'>
                            <div><h5>" . $dp['nama'] . "</h5></div>
                            <div>NIM. " . $dp['nim'] . "</div>
                            <div>Prodi. " . $dp['prodi'] . "</div>
                            <div>HP. " . $dp['hp'] . "</div>
                            <div>" . $dp['email'] . "</div>
                        </div>";
            $retVal .= "<div class='col-md-6'><img src='" . base_url($dp['kartumahasiswa']) . "' width='100%'></div>";
        }
        $retVal .= "</div>";
        return $retVal;
    }

    public function berkaspendaftar_administrasi()
    {
        $retVal = array("status" => false, "pesan" => "", "html" => "Syarat administrasi belum ditemukan oleh admin!", "db" => []);
        $this->load->library("dataweb");

        $idkkn = $this->input->post("idkkn");
        $idpendaftar = $this->input->post("idpendaftar");

        $vCari = array(
            array("cond" => "where", "fld" => "p.id", "val" => $idpendaftar),
        );
        $datamahasiswa = $this->dataweb->datamahasiswa($vCari, null, 1);
        if (!$datamahasiswa['status']) {
            $retVal = array("status" => false, "pesan" => "", "html" => "Mahasiswa tidak ditemukan", "db" => []);
            die(json_encode($retVal));
        }

        $this->db->from("administrasi as a");
        $this->db->select("
            a.id as idadministrasi,a.namaadministrasi,a.upload_file,a.upload_type,a.upload_size,a.keterangan,
            ba.id as idupload,ba.idpendaftar,ba.status,ba.path,ba.keterangan as ketupload,ba.fileinfo,
            IF('" . date('Y-m-d') . "' BETWEEN k.daftarmulai AND k.daftarselesai,1,0) as ketpendaftaran,
		");
        $this->db->join("kkn as k", "k.id=a.idkkn", "left");
        $this->db->join("berkas_administrasi as ba", "ba.idadministrasi=a.id AND ba.idpendaftar='" . $idpendaftar . "'", "left");
        $this->db->where("a.idkkn", $idkkn);
        $this->db->order_by("a.idkkn ASC, a.upload_file DESC, a.namaadministrasi ASC");
        $sql = $this->db->get();
        //$retVal['pesan'] = $this->db->last_query();
        $dataSql = array();
        if ($sql->num_rows() > 0) {
            $retVal['berkas_html'] = $this->berkaspendaftar_html($sql->result_array());
            $retVal['berkas_db'] = $sql->result_array();
            $retVal['mhs_db'] = $datamahasiswa['db'];
            $retVal['mhs_html'] = $this->identitasmhs_html($datamahasiswa['db']);
        }
        die(json_encode($retVal));
    }

    public function loadprodi()
    {
        $this->load->library("dataweb");
        $retVal = $this->dataweb->dataprodi([]);
        die(json_encode($retVal));
    }


    public function kelompok_terkaktif()
    {
        $this->load->library("dataweb");
        $vCari = $this->input->post();
        unset($vCari['title']);

        $retVal = $this->dataweb->kelompok_terkaktif($vCari, null, 10);
        $retVal['html'] = array();
        if ($retVal['status']) {
            foreach ($retVal['db'] as $i => $dp) {
                $url1 = base_url("dashboard/kelompok/" . $dp['idkelompok']);

                $html = "  <div class='row'>
                                <hr>
                                <div class='col-9 col-lg-10 col-md-10'>
                                    <a href='" . $url1 . "'>
                                        <div style='font-size:12px;'>" . $dp['tema'] . " (" . $dp['tahun'] . ")</div>
                                        <h6 class='mb-0' >Kelompok " . $dp['namakelompok'] . "</h6>
                                        <div style='font-size:12px;'>" . $dp['provinsi'] . " " . $dp['kabupaten'] . " " . $dp['kecamatan'] . " " . $dp['desa'] . "</div>
                                        <div style='font-size:14px;font-weight:bold;'>DPL : " . $dp['nama'] . "</div>
                                    </a>
                                </div>
                                <div class='col-3 col-lg-2 col-md-2' style='text-align:center;'>
                                    
                                        <i class='bi bi-arrow-up-right'></i>
                                        " . $dp['jumpopuler'] . " 
                                        Aktifitas
                                    
                                </div>
                            </div>";
                $retVal['html'][] = $html;
            }
        }
        die(json_encode($retVal));
    }

    public function dataposko()
    {
        $this->load->library("dataweb");
        $vCari = $this->input->post();
        $retVal = $this->dataweb->dataposko($vCari);
        die(json_encode($retVal));
    }

    public function aktifitas_terbaik()
    {
        $this->load->library("dataweb");
        $kategori = $this->input->post('kategori');
        $title = $this->input->post('title');

        $vCari = $this->input->post();
        unset($vCari['title']);
        unset($vCari['kategori']);

        $label_kategori = "Like";
        $icon_kategori = "<i class='bi bi-hand-thumbs-up'></i>";
        if ($kategori == "trending") {
            $label_kategori = "Komentar";
            $icon_kategori = "<i class='bi bi-chat-left-text'></i>";
        }

        $retVal = $this->dataweb->aktifitas_trending($vCari, $kategori, null, 5);
        $retVal['html'] = array();
        if ($retVal['status']) {
            foreach ($retVal['db'] as $i => $dp) {
                $url1 = base_url("dashboard/personal/" . $dp['idpenempatan']);
                $url2 = base_url("dashboard/detail_aktifitas/" . $dp['idaktifitas']);
                $ketkelompok = ($dp['ketpublishkelompok'] == "terbuka") ? " - " . $dp['jabatan'] . " <br> <a href='" . base_url("dashboard/kelompok/" . $dp['idkelompok']) . "'>Kelompok " . $dp['namakelompok'] . " (" . $dp['desa'] . ")</a>" : "";

                $html = "  <div class='row'>
                                <div class='col-3 col-lg-2 col-md-2'>
                                    <a href='" . $url1 . "'>
                                        <div class='avatar avatar-xl'>
                                            <img src='" . base_url($dp['profilpic']) . "' height='65px'>
                                        </div>
                                    </a>
                                </div>
                                <div class='col-6 col-lg-8 col-md-8'>
                                    <a href='" . $url1 . "'>
                                        <h5 class='mb-0' >" . $dp['nama'] . "</h5>
                                    </a>
                                    <div style='font-size:13px;'><i class='bi bi-envelope'></i> " . $dp['email'] . "</div>
                                    <div class='row' style='font-size:12px'>
                                        <div class='col-12'>
                                            <a href='" . base_url("dashboard/kkn/" . $dp['idkkn']) . "'>" . $dp['tema'] . " " . $dp['tahun'] . "</a> " . $ketkelompok . "
                                        </div>
                                        <div class='col-6'>
                                            <i class='bi bi-clock'></i> " . waktu_lalu($dp['waktu']) . "
                                        </div>
                                    </div>
                                </div>
                                <div class='col-3 col-lg-2 col-md-2' style='text-align:center;'>
                                    " . $icon_kategori . " " . $dp['jumpopuler'] . " " . $label_kategori . " 
                                </div>
        ";
                $html .= "  <hr style='margin-top:10px;' >
                                <a href='" . $url2 . "'>
                                <div class='row'>
                                    <div class='col-md-12'>
                                        " . $dp['uraian'] . "
                                    </div>
                                </div>
                                </a>
                                ";
                $html .= "</div>";
                $retVal['html'][] = $html;
            }
        }
        die(json_encode($retVal));
    }

    public function jum_notif()
    {
        $this->load->library("dataweb");
        $vCari = $this->input->post();
        $retVal = $this->dataweb->notifikasi($vCari);
        die(json_encode($retVal));
    }

    public function aktifitas_dpl()
    {
        $this->load->library("dataweb");
        $vCari = $this->input->post();
        $retVal = $this->dataweb->aktifitas_dpl($vCari);
        $retVal['html'] = array();
        if ($retVal['status']) {
            foreach ($retVal['db'] as $i => $dp) {
                $html = "  <div class='row'>
                                <div class='col-md-12' style='font-size:14px;'>
                                    <i class='bi bi-clock'></i> " . waktu_lalu($dp['waktu']) . " 
                                    <div>" . $dp['uraian'] . "</div>
                                </div>
                            </div>";
                if ($i < count($retVal['db']) - 1)
                    $html .= "<hr>";
                $retVal['html'][] = $html;
            }
            //$retVal['html'][] = $html;
        }

        die(json_encode($retVal));
    }


    public function data_notif()
    {
        $this->load->library("dataweb");
        $vCari = $this->input->post();

        $retVal = $this->dataweb->notifikasi($vCari, null, 20);


        $retVal['html'] = array();
        if ($retVal['status']) {
            foreach ($retVal['db'] as $i => $dp) {
                $gourl = base_url("dashboard/detail_aktifitas/" . $dp['idaktifitas']);

                $status = "<span class='badge bg-secondary'>sudah dicekmi</span>";
                if (!$dp['status'])
                    $status = "<span class='badge bg-success'>baru!</span>";
                $html = "  <div class='row'>
                                <div class='col-md-2 avatar avatar-xl'>
                                    <img src='" . base_url($dp['profilpic']) . "' width='100%'> 
                                </div>
                                <div class='col-md-10' style='font-size:14px;'>
                                    <a href='" . $gourl . "'>
                                        <i class='bi bi-clock'></i> " . waktu_lalu($dp['created']) . " 
                                        <div> <b>" . $dp['nama'] . "</b> " . $dp['notif'] . ", Selengkapnya...</div>" . $status . "
                                    </a>
                                </div>
                            </div>";
                if ($i < count($retVal['db']) - 1)
                    $html .= "<hr>";
                $retVal['html'][] = $html;
            }
            $html = "  <div class='row mt-3'>
                            <div class='col-md-12'>
                                <a href='#' class='btn btn-danger rounded-pill' id='baca-semua-notif'><i class='bi bi-bookmark-check'></i> Sudah dicek semua!</a> 
                            </div>
                        </div>";
            $retVal['html'][] = $html;
        }
        die(json_encode($retVal));
    }

    //mencari item
    public function itemTree()
    {
        $this->load->library("Dataweb");
        $data = [];
        $cariSql = $this->input->post('vCari', true);
        $dt = $this->dataweb->datamodule($cariSql);
        $retVal = $dt;
        $retVal['listdata'] = buildTree($dt['db']);
        die(json_encode($retVal));
    }

    //mencari item
    public function daftargrup()
    {
        $this->load->library("Dataweb");
        $vCari = array();
        $retVal = $this->dataweb->cariGrup($vCari);
        die(json_encode($retVal));
    }
}
