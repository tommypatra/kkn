<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    private $d = array(
        "web" => array(
            "title" => "Website KKN Reborn",
            "modul" => "Dashboard",
            "view"  => "web",
            "page"  => "Website",
        ),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->library('parser');
    }

    public function index()
    {
        redirect("web");
    }

    public function yt()
    {
        $img = getYouTubeThumbnail('https://www.youtube.com/watch?v=P6xLzxQLeq8');
        if ($img)
            echo '<img src="' . $img . '">';
    }

    public function kkn($idkkn = null)
    {
        $this->load->library("Dataweb");

        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
        );
        $datakkn = $this->dataweb->cariKkn($vCari);
        if (!$datakkn['status'] || !$idkkn) {
            redirect("web");
        }
        $this->d['datakkn'] = $datakkn['db'][0];

        //debug($this->d['datakkn']);

        $this->d['web']['importPlugins'] = array(
            loadPlugins("datetime"),
            loadPlugins("mask"),
            loadPlugins("editorweb"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/dashboard_kkn.js?' . date("ymdhis")),
        );

        // start anggota kelompok berdasarkan idkelompok
        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
        );
        $anggota = $this->dataweb->pesertakkn($vCari, null, null, "l.lastlogin DESC");
        $this->d['anggota'] = $anggota['db'];

        $pesertakkn = searchMultiArray($this->d['anggota'], "iduser", $this->session->userdata('iduser'));
        $this->d['pesertakkn'] = array();
        if (count($pesertakkn) > 0)
            $this->d['pesertakkn'] = $pesertakkn[0];
        //debug($this->d['anggota']);
        // end anggota kelompok berdasarkan idkelompok

        //cari pembimbing dan lokasi
        $vCari = array(
            array("cond" => "where", "fld" => "sp.idkkn", "val" => $idkkn),
        );
        $pembimbing = $this->dataweb->daftarpembimbing($vCari, null, null, "l.lastlogin DESC");
        $this->d['pembimbing'] = $pembimbing['db'];
        //end cari pembimbing

        //cari kelompok
        $vCari = array(
            array("cond" => "where", "fld" => "sp.idkkn", "val" => $idkkn),
        );
        $kelompok = $this->dataweb->daftarkelompok($vCari);
        $this->d['kelompok'] = $kelompok['db'];
        //end cari kelompok

        //cari rekap
        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
        );
        $rekapaktifitas = $this->dataweb->rekapaktifitas($vCari);
        if ($rekapaktifitas['status']) {
            $rekapaktifitas = $rekapaktifitas['db'][0];
        } else {
            $rekapaktifitas = [];
        }
        $this->d['rekapaktifitas'] = $rekapaktifitas;
        //end cari cari

        $this->d['idkkn'] = $idkkn;
        $this->d['web']['loadview'] = "dashboard_kkn";
        $this->load->view("index", $this->d);
    }

    function kelompok($idkelompok = null)
    {
        if (!$idkelompok) {
            redirect("web");
        }

        $this->load->library("Dataweb");
        $this->d['web']['loadview'] = "dashboard_kelompok";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datetime"),
            loadPlugins("mask"),
            loadPlugins("dropzone"),
            loadPlugins("editorweb"),
            loadPlugins("tour"),
            loadPlugins("sweetalert"),
            loadPlugins("leaflet"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );
        $this->d['web']['importJs'] = array(
            base_url('assets/web/dashboard_kelompok.js?' . date("ymdhis")),
        );

        // start detail peserta berdasarkan idkelompok OK
        $vCari = array(
            array("cond" => "where", "fld" => "kl.id", "val" => $idkelompok),
        );
        $pesertakkn = $this->dataweb->pesertakkn($vCari);
        if (!$pesertakkn['status']) {
            redirect("web");
            die;
        }
        $this->d['pesertakkn'] = $pesertakkn['db'][0];
        $this->d['anggota'] = $pesertakkn['db'];
        //debug($pesertakkn['db']);
        // end detail peserta berdasarkan idkelompok

        // start login apakh peserta kkn
        $is_pesertakkn = searchMultiArray($this->d['anggota'], "iduser", $this->session->userdata('iduser'));
        $this->d['is_pesertakkn'] = $is_pesertakkn;

        // start kelompok berdasarkan idkelompok OK
        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkelompok),
        );
        $daftarkelompok = $this->dataweb->daftarkelompok($vCari);
        $this->d['kelompok'] = $daftarkelompok['db'][0];
        //debug($this->d['kelompok']);
        // end kelompok berdasarkan idkelompok

        //cari rekap
        $vCari = array(
            array("cond" => "where", "fld" => "pm.idkelompok", "val" => $idkelompok),
        );
        $rekapaktifitas = $this->dataweb->rekapaktifitas($vCari);
        if ($rekapaktifitas['status']) {
            $rekapaktifitas = $rekapaktifitas['db'][0];
        } else {
            $rekapaktifitas = [];
        }
        $this->d['rekapaktifitas'] = $rekapaktifitas;
        //debug($this->d['rekapaktifitas']);
        //end cari cari


        $this->load->view("index", $this->d);
    }


    public function personal($idpenempatan = null)
    {
        if (!$idpenempatan) {
            redirect("web");
        }

        $this->load->library("Dataweb");

        $this->d['web']['loadview'] = "dashboard_personal";

        $this->d['web']['importPlugins'] = array(
            loadPlugins("datetime"),
            loadPlugins("mask"),
            loadPlugins("tour"),
            loadPlugins("dropzone"),
            loadPlugins("editorweb"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("leaflet"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/dashboard_personal.js?' . date("ymdhis")),
        );

        // start detail peserta berdasarkan idpenempatan
        $vCari = array(
            array("cond" => "where", "fld" => "pm.id", "val" => $idpenempatan),
        );
        $pesertakkn = $this->dataweb->pesertakkn($vCari);
        if (!$pesertakkn['status']) {
            redirect("web");
            die;
        }
        $this->d['pesertakkn'] = $pesertakkn['db'][0];

        // start login apakh peserta kkn
        $is_pesertakkn = array();
        if ($pesertakkn['db'][0]['iduser'] == $this->session->userdata('iduser')) {
            $is_pesertakkn = $pesertakkn['db'][0];
        }
        $this->d['is_pesertakkn'] = $is_pesertakkn;
        // end login apakh peserta kkn

        // start kelompok berdasarkan idpenempatan
        $vCari = array(
            array("cond" => "where", "fld" => "pm.id", "val" => $idpenempatan),
        );
        $daftarkelompok = $this->dataweb->daftarkelompok($vCari);
        $this->d['kelompok'] = $daftarkelompok['db'][0];
        //print_r($this->d['kelompok']);
        // end kelompok berdasarkan idpenempatan

        // start anggota kelompok berdasarkan idkelompok
        $vCari = array(
            array("cond" => "where", "fld" => "kl.id", "val" => $this->d['pesertakkn']['idkelompok']),
        );
        $anggota = $this->dataweb->pesertakkn($vCari);
        $this->d['anggota'] = $anggota['db'];
        // end anggota kelompok berdasarkan idkelompok

        //cari rekap
        $vCari = array(
            array("cond" => "where", "fld" => "pm.id", "val" => $idpenempatan),
        );
        $rekapaktifitas = $this->dataweb->rekapaktifitas($vCari);
        if ($rekapaktifitas['status']) {
            $rekapaktifitas = $rekapaktifitas['db'][0];
        } else {
            $rekapaktifitas = [];
        }
        $this->d['rekapaktifitas'] = $rekapaktifitas;
        //end cari rekap

        //cari notifikasi

        $jumnotif = 0;
        /*
        $dtnotif = array();
        if ($is_pesertakkn['status']) {
            $vCari = array(
                array("cond" => "where", "fld" => "n.iduser", "val" => $this->session->userdata("iduser")),
                array("cond" => "where", "fld" => "n.status", "val" => "0"),
            );
            $sqlnotif = $this->dataweb->notifikasi($vCari);
            $jumnotif = count($sqlnotif['db']);
            foreach ($sqlnotif['db'] as $i => $dp) {
                $dtnotif[] = $dp;
                if ($i >= 9)
                    break;
            }
            //$dtnotif = $sqlnotif['db'];
        }
        $this->d['dtnotif'] = $dtnotif;
        */
        $this->d['jumnotif'] = $jumnotif;
        //end cari notifikasi

        $this->load->view("index", $this->d);
    }


    public function detail_aktifitas($idaktifitas = null)
    {
        $retVal = array("status" => false, "pesan" => "", $db = []);

        $this->load->library("Dataweb");

        $this->d['web']['loadview'] = "detail_aktifitas";
        $this->d['idaktifitas'] = $idaktifitas;

        $this->d['web']['importPlugins'] = array(
            loadPlugins("datetime"),
            loadPlugins("mask"),
            loadPlugins("dropzone"),
            loadPlugins("editorweb"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/detail_aktifitas.js?' . date("ymdhis")),
        );


        $this->load->view("index", $this->d);
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => "");
        allowheader();
        $this->load->library("Dataweb");

        $this->form_validation->set_rules('idkkn', 'kkn', 'trim|required');
        $this->form_validation->set_rules('waktu', 'waktu', 'trim|required');
        $this->form_validation->set_rules('uraian', 'uraian kegiatan', 'trim|required');
        $this->form_validation->set_rules('estbiaya', 'estimasi biaya', 'trim|required');
        $this->form_validation->set_rules('grup', 'grup', 'trim|required');
        $idpenempatan = null;

        if ($this->form_validation->run()) {
            $id = $this->input->post('idaktifitas');
            $cekuraian = trim(strip_tags($this->input->post('uraian')));
            if ($cekuraian == "") {
                $retVal['pesan'] = ["uraian kegiatan tidak boleh kosong"];
                die(json_encode($retVal));
            }

            // start login apakh peserta kkn
            $vCari = array(
                array("cond" => "where", "fld" => "k.id", "val" => $this->input->post('idkkn')),
                array("cond" => "where", "fld" => "u.id", "val" => $this->session->userdata('iduser')),
            );
            $pesertakkn = $this->dataweb->pesertakkn($vCari);
            if (!$pesertakkn['status']) {
                die(json_encode($retVal));
            }
            $pesertakkn = $pesertakkn['db'][0];
            // start login apakh peserta kkn

            $idpenempatan = $pesertakkn['idpenempatan'];
            $dataSave = array(
                'idpenempatan' => $pesertakkn['idpenempatan'],
                'latitude' => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude'),
                'waktu' => $this->input->post('waktu'),
                'uraian' => $this->input->post('uraian'),
                'jummhs' => $this->input->post('jummhs'),
                'jummasyarakat' => $this->input->post('jummasyarakat'),
                'grup' => $this->input->post('grup'),
                'estbiaya' => $this->input->post('estbiaya'),
            );

            if (!$id) {
                $retVal = $this->Model_data->save($dataSave, "aktifitas", null, true);
            } elseif ($id) {
                $kond = array(
                    array("where", $this->d['primaryKey'], $id),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, "aktifitas", null, true);
            } else {
                $retVal['pesan'] = ["Maaf, akses ditolak"];
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        $retVal['idpenempatan'] = $idpenempatan;

        die(json_encode($retVal));
    }

    public function listlampiran($lampiran = [], $itsme = false, $ketkkn = 'tertutup')
    {
        $html = "";
        if (count($lampiran) > 0) {
            $html = "<div class='row' style='margin:10px;'>";
            $firstimg = true;
            $divimg = "12";
            foreach ($lampiran as $i => $dp) {
                $fileinfo = json_decode($dp['fileinfo'], true);
                $url = base_url($dp['path']);
                if (!$dp['is_image']) {
                    $html .= "<div class='col-12'><i class='bi bi-file-pdf'></i> <a style='font-size:12px;' target='_blank' href='" . $url . "'>" . $fileinfo['client_name'] . "</a>";
                    if ($itsme && $ketkkn == 'terbuka') {
                        $html .= "<a href='#' class='btn icon icon-left btn-sm hapus-lampiran' data-idupload='" . $dp['idupload'] . "' title='hapus lampiran'><i class='bi bi-trash'></i></a>";
                    }
                    $html .= "</div>";
                } else {
                    $html .= "<div class='col-" . $divimg . "'>
                               <img src='" . $url . "' class='w-100 gambardet'>
                            ";
                    if ($itsme && $ketkkn == 'terbuka') {
                        $html .= "<a href='#' class='btn icon icon-left btn-sm hapus-lampiran' data-idupload='" . $dp['idupload'] . "' title='hapus lampiran'><i class='bi bi-trash'></i></a>";
                    }
                    $html .= "</div>";
                    if ($firstimg) {
                        $firstimg = false;
                        $divimg = "4";
                    }
                }
            }
            $html .= "</div>";
        }
        return $html;
    }

    public function loademptylkh()
    {
        $ret = "<div class='card'>
                    <div class='card-body'>  
                        <div class='row'>
                            <div class='col-3 col-lg-2 col-md-2'>
                                <a href='#'>
                                    <div class='avatar avatar-xl'>
                                        <img src='" . base_url("assets/img/pria.png") . "' height='65px'>
                                    </div>
                                </a>
                            </div>
                            <div class='col-9 col-lg-10 col-md-10'>
                                <a href='#'>
                                    <h5 class='mb-0'>Admin</h5>
                                </a>
                                <div style='font-size:13px;'><i class='bi bi-envelope'></i> " . $this->config->item('email_admin') . "</div>
                                <div class='row' style='font-size:12px'>
                                    <div class='col-12'>
                                    <a href='#'>Admin </a> - System Web 
                                    </div>
                                    <div class='col-6'>
                                        <i class='bi bi-clock'></i> beberapa waktu lalu
                                        
                                    </div>
                                </div>
                            </div>
                            <hr style='margin-top:10px;'>
                            <div class='col-md-12'>
                                <p>Belum ada LKH Mahasiswa yang dibuat pada system ini</p>
                            </div>  
                            <div style='font-size:13px;margin-bottom:5px;'>
                                <i class='bi bi-hand-thumbs-up'></i> 0 Like &nbsp; 
                                <i class='bi bi-chat-left-text'></i> 0 Komentar
                            </div>
                        </div>
                    </div>
                </div>";
        return $ret;
    }

    public function loadlkh()
    {
        $retVal = array("status" => false, "pesan" => "", $db = []);
        allowheader();

        $modeinput = $this->input->post("modeinput");
        $lastid = $this->input->post("lastid");
        $limit = $this->input->post("limit");
        $limitDef = ($limit > 0) ? $limit : 5;

        $vCari = $this->input->post("vCari");
        $this->db->from("aktifitas as a");
        $this->db->select("
                a.id as idaktifitas, a.idpenempatan,a.latitude, a.longitude,a.uraian,a.waktu,a.jummhs,a.jummasyarakat,a.grup,a.estbiaya,
                kl.id as idkelompok, kl.namakelompok, jb.jabatan,
                u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
                u.nik, u.kel, u.path as profilpic, u.hp, u.email, u.aktivasi, u.hp, u.tmplahir, u.tgllahir, u.kel, u.alamat,
                prodi.prodi,fak.fakultas,
                dsa.desa, 
                IF(DATE_ADD(NOW(), INTERVAL 8 HOUR)>=k.bagikelompok,'terbuka','tertutup') as ketpublishkelompok,
                k.id as idkkn,k.tema,k.tahun,k.jenis,
                IF('" . date('Y-m-d') . "' BETWEEN k.kknmulai AND k.kknselesai,'terbuka','tertutup') as ketkkn,

            ");
        //$this->db->where("a.idpenempatan", $idpenempatan);
        $vCari = (isset($vCari)) ? $vCari : [];
        $globalinput = (isset($modeinput)) ? true : false;
        if (count($vCari) > 0) {
            foreach ($vCari as $val => $cariSql) {
                if ($cariSql['cond'] == "like")
                    $this->db->like($cariSql['fld'], $cariSql['val'], "both");
                else
                    $this->db->where($cariSql['fld'], $cariSql['val']);
            }
        }

        $this->db->join("penempatan as pm", "pm.id=a.idpenempatan", "left");
        $this->db->join("peserta as ps", "pm.idpeserta=ps.id", "left");
        $this->db->join("kelompok as kl", "kl.id=pm.idkelompok", "left");
        //$this->db->join("mst_jabatan as j", "j.id=pm.idjabatan", "left");
        $this->db->join("lokasi as l", "l.id=kl.idlokasi", "left");
        $this->db->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->db->join("mst_jabatan as jb", "pm.idjabatan=jb.id", "left");
        $this->db->join("pendaftar as p", "p.id=ps.idpendaftar", "left");
        $this->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->db->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        $this->db->join("user as u", "u.id=m.iduser", "left");
        $this->db->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->db->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");
        if ($lastid > 0)
            $this->db->where("a.id<" . $lastid, null);
        $this->db->order_by("a.id DESC");
        $this->db->limit($limitDef);
        $data = $this->db->get();
        //echo $this->db->last_query();
        //debug($cekUsr);
        $html = "";
        if ($data->num_rows() > 0) {
            $db = array();
            $retVal = array(
                "status" => true,
                "pesan" => "data ditemukan",
            );
            $dataaktifitas = $data->result_array();

            $idaktifitas = array();
            foreach ($dataaktifitas as $i => $dp) {
                $idaktifitas[] = $dp['idaktifitas'];
            }

            $loadkomentar = $this->loadkomentar($idaktifitas);
            $loadlike = $this->loadlike($idaktifitas);
            $loadlampiran = $this->loadlampiran($idaktifitas);
            //print_r($loadkomentar);

            foreach ($dataaktifitas as $i => $dp) {
                $itsme = false;
                $btn_hapusaktifitas = "";
                $btn_uploadaktifitas = "";
                //mencari dalam array daftar komentar
                $ketkelompok = ($dp['ketpublishkelompok'] == "terbuka") ? " - " . $dp['jabatan'] . " <br> <a href='" . base_url("dashboard/kelompok/" . $dp['idkelompok']) . "'>Kelompok " . $dp['namakelompok'] . " (" . $dp['desa'] . ")</a>" : "";
                $listkomentar = searchMultiArray($loadkomentar, "idaktifitas", $dp['idaktifitas']);
                $listlike = searchMultiArray($loadlike, "idaktifitas", $dp['idaktifitas']);

                $thumbs = "bi-hand-thumbs-up tambah-like";
                if ($this->session->userdata('iduser'))
                    $thumbs .= " tambah-like";

                $adalike = searchMultiArray($listlike, "iduser", $this->session->userdata('iduser'));
                if (count($adalike) > 0)
                    $thumbs = "bi-hand-thumbs-up-fill";

                $listlampiran = searchMultiArray($loadlampiran, "idaktifitas", $dp['idaktifitas']);

                $url = base_url("dashboard/personal/" . $dp['idpenempatan']);
                if ($dp['iduser'] == $this->session->userdata('iduser') && $globalinput) {
                    $itsme = true;
                    if ($dp['ketkkn'] == 'terbuka') {
                        $btn_hapusaktifitas = "<a href='#' class='btn icon icon-left btn-sm hapus-aktifitas' data-idaktifitas='" . $dp['idaktifitas'] . "' title='hapus aktifitas'><i class='bi bi-trash'></i></a>";
                        //$btn_uploadaktifitas = "<a href='#' class='btn icon icon-left btn-sm btn-upload-aktifitas' data-idaktifitas='" . $dp['idaktifitas'] . "' title='upload' ><i class='bi bi-upload'></i></a>";
                        $btn_uploadaktifitas = "<input type='file' data-idpenempatan='" . $dp['idpenempatan'] . "' data-idaktifitas='" . $dp['idaktifitas'] . "' style='font-size:10px' class='upload-aktifitas' />";
                    }
                }

                $html = "<div class='card rowlkh' data-id='" . $dp['idaktifitas'] . "'>";
                $html .= "<div class='card-body'>";


                $email = $dp['email'];
                if (!$this->session->userdata('iduser')) {
                    $email = preg_replace('/(?<=.)[^@](?=[^@]*?@)|(?:(?<=@.)|(?!^)\G(?=[^@]*$)).(?=.*\.)/', 'x', $email);
                }

                $html .= "  <div class='row'>
                                <div class='col-3 col-lg-2 col-md-2'>
                                    <a href='" . $url . "'>
                                        <div class='avatar avatar-xl'>
                                            <img src='" . base_url($dp['profilpic']) . "' height='65px'>
                                        </div>
                                    </a>
                                </div>
                                <div class='col-9 col-lg-10 col-md-10'>
                                    <a href='" . $url . "'>
                                        <h5 class='mb-0' >" . $dp['nama'] . "</h5>
                                    </a>
                                    <div style='font-size:13px;'><i class='bi bi-envelope'></i> " . $email . "</div>
                                    <div class='row' style='font-size:12px'>
                                        <div class='col-12'>
                                        <a href='" . base_url("dashboard/kkn/" . $dp['idkkn']) . "'>" . $dp['tema'] . " " . $dp['tahun'] . "</a> " . $ketkelompok . "
                                        </div>
                                        <div class='col-6'>
                                            <i class='bi bi-clock'></i> " . waktu_lalu($dp['waktu']) . "
                                            " . $btn_hapusaktifitas . "
                                        </div>
                                    </div>
                                </div>
                        ";
                $html .= "<hr style='margin-top:10px;' ><div class='col-md-12'>" . $dp['uraian'] . "</div>";
                $html .= "  <div class='buttons'>
                                " . $btn_uploadaktifitas . "
                            </div>";
                $html .= $this->listlampiran($listlampiran, $itsme, $dp['ketkkn']);

                $html .= "<div style='font-size:14px;margin-bottom:5px;'>
                            <i class='bi " . $thumbs . "' data-iduser='" . $dp['iduser'] . "' data-idaktifitas='" . $dp['idaktifitas'] . "'></i> " . count($listlike) . " Like &nbsp; 
                            <i class='bi bi-chat-left-text'></i> " . count($listkomentar) . " Komentar</div>";
                if ($this->session->userdata('iduser')) {
                    $html .= "  <div class='form-group'>
                                    <div class='row'>
                                        <div class='col-md-2'>
                                            <div class='avatar avatar-lg'>
                                                <img src='" . $this->session->userdata('profilpic') . "' alt='Avatar'>
                                            </div>
                                        </div>
                                        <div class='col-md-10'>
                                            <form class='fkomentar'>
                                                <input type='hidden' name='iduser' value='" . $dp['iduser'] . "'>
                                                <input type='hidden' name='idaktifitas' value='" . $dp['idaktifitas'] . "'>
                                                <textarea class='form-control komentar' name='komentar' rows='3' placeholder='tabe` " . strtolower($this->session->userdata('nama')) . ", kasi komentar tawwa...'></textarea>
                                                <div style='margin-top:5px;'>
                                                    <button type='submit' class='btn btn-primary btn-sm'>Kirim Komentar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>    
                                </div>    
                    ";
                }
                foreach ($listkomentar as $i => $dx) {
                    //print_r($dx);
                    $html .=    "<div class='row'>
                                    <div class='col-md-1'>
                                        <div class='avatar avatar-sm'>
                                            <img src='" . base_url($dx['profilpic']) . "' alt='Avatar'>
                                        </div>
                                    </div>
                                    <div class='col-md-11'>
                                        <h6>" . $dx['nama'] . "</h6> 
                                        <div style='font-size:11px'><i class='bi bi-clock'></i> " . waktu_lalu($dx['waktu']) . "</div>
                                        <div>&ldquo;" . $dx['komentar'] . "&rdquo;</div>
                                        <hr>
                                    </div>
                                </div>";
                    if ($i >= 4) {
                        $html .=    "<div class='row'>
                                        <div class='col-md-12'><a href='" . base_url("dashboard/detail_aktifitas/" . $dp['idaktifitas']) . "'>Selengkapnya...</a>
                                    </div>";
                        break;
                    }
                }
                $html .= "</div>";
                $html .= "</div>";
                $html .= "</div>";
                $db[] = $html;
            }
            $retVal['db'] = $db;
        } else {
            if ($lastid < 1) {
                $retVal['db'][] = $this->loademptylkh();
            }
        }
        die(json_encode($retVal));
    }

    public function simpan_notif($idaktifitas = null, $iduser = null, $grup = null)
    {
        if ($idaktifitas && $iduser && $grup && ($iduser != $this->session->userdata("iduser"))) {

            $dataSave = array(
                "idaktifitas" => $idaktifitas,
                "iduser" => $iduser,
                'iduser_asal' => $this->session->userdata('iduser'),
                "notif" => "telah memberi <b>" . $grup . "</b> baru pada aktifitas anda, cek yuk!",
            );
            $ret = $this->Model_data->save($dataSave, "notifikasi", "notifikasi pengguna", true);
        }
    }

    public function simpan_like()
    {
        $retVal = array("status" => false, "pesan" => "", $db = []);
        allowheader();

        if ($this->session->userdata('iduser')) {
            $this->form_validation->set_rules('idaktifitas', 'aktifitas', 'trim|required');
            $this->form_validation->set_rules('iduser', 'tujuan pengguna', 'trim|required');

            if ($this->form_validation->run()) {
                $idaktifitas = $this->input->post('idaktifitas');
                $iduser = $this->input->post('iduser');
                $dataSave = array(
                    'iduser' => $this->session->userdata('iduser'),
                    'idaktifitas' => $idaktifitas,
                );
                $retVal = $this->Model_data->save($dataSave, "aktifitas_like", "like aktifitas", true);
                $this->simpan_notif($idaktifitas, $iduser, "like");
            } else {
                $retVal['pesan'] = $this->form_validation->error_array();
                $retVal['status'] = false;
            }
        } else {
            $retVal['pesan'] = ["login terlebih dahulu"];
        }
        die(json_encode($retVal));
    }


    public function simpan_komentar()
    {
        $retVal = array("status" => false, "pesan" => "", $db = []);
        allowheader();

        if ($this->session->userdata('iduser')) {
            $this->form_validation->set_rules('idaktifitas', 'aktifitas', 'trim|required');
            $this->form_validation->set_rules('iduser', 'tujuan pengguna', 'trim|required');
            $this->form_validation->set_rules('komentar', 'komentar', 'trim|required');

            if ($this->form_validation->run()) {
                $idaktifitas = $this->input->post('idaktifitas');
                $iduser = $this->input->post('iduser');
                $dataSave = array(
                    'iduser' => $this->session->userdata('iduser'),
                    'idaktifitas' => $idaktifitas,
                    'waktu' => date("Y-m-d H:i:s"),
                    'komentar' => $this->input->post('komentar'),
                );
                $retVal = $this->Model_data->save($dataSave, "aktifitas_komentar", "komentar aktifitas", true);
                $this->simpan_notif($idaktifitas, $iduser, "komentar");
            } else {
                $retVal['pesan'] = $this->form_validation->error_array();
                $retVal['status'] = false;
            }
        }
        die(json_encode($retVal));
    }

    function loadlampiran($id = [])
    {
        $retVal = array();
        $this->load->library("Dataweb");

        $vCari = array(
            array("cond" => "where_in", "fld" => "f.idaktifitas", "val" => $id),
        );
        $daftarlampiran = $this->dataweb->daftarlampiran($vCari);
        if ($daftarlampiran['status'])
            $retVal = $daftarlampiran['db'];

        return $retVal;
    }

    function detailaktifitas($id = null)
    {
        $retVal = array();

        $this->db->from("aktifitas as a");
        $this->db->select("
                a.id as idaktifitas, a.idpenempatan,a.latitude, a.longitude,a.uraian,a.waktu,a.jummhs,a.jummasyarakat,a.grup,a.estbiaya,
                kl.id as idkelompok, kl.namakelompok, jb.jabatan,
                u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
                u.nik, u.kel, u.path as profilpic, u.hp, u.email, u.aktivasi, u.hp, u.tmplahir, u.tgllahir, u.kel, u.alamat,
                prodi.prodi,fak.fakultas,
                dsa.desa, 
                IF(DATE_ADD(NOW(), INTERVAL 8 HOUR)>=k.bagikelompok,'terbuka','tertutup') as ketpublishkelompok,
                k.id as idkkn,k.tema,k.tahun,k.jenis,
            ");
        $this->db->where("a.id", $id);

        $this->db->join("penempatan as pm", "pm.id=a.idpenempatan", "left");
        $this->db->join("peserta as ps", "pm.idpeserta=ps.id", "left");
        $this->db->join("kelompok as kl", "kl.id=pm.idkelompok", "left");
        $this->db->join("lokasi as l", "l.id=kl.idlokasi", "left");
        $this->db->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->db->join("mst_jabatan as jb", "pm.idjabatan=jb.id", "left");
        $this->db->join("pendaftar as p", "p.id=ps.idpendaftar", "left");
        $this->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->db->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        $this->db->join("user as u", "u.id=m.iduser", "left");
        $this->db->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->db->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");
        $this->db->order_by("a.id DESC");

        $data = $this->db->get();

        if ($data->num_rows() > 0) {
            $retVal['aktifitas'] = $data->row();
            $retVal['komentar'] = $this->loadkomentar($id);
            $retVal['like'] = $this->loadlike($id);
            $retVal['lampiran'] = $this->loadlampiran($id);
        }
        return $retVal;
    }

    function loadkomentar($id = [])
    {
        $retVal = array();
        $this->load->library("Dataweb");

        $vCari = array(
            array("cond" => "where_in", "fld" => "k.idaktifitas", "val" => $id),
        );
        $daftarkomentar = $this->dataweb->daftarkomentar($vCari);
        if ($daftarkomentar['status'])
            $retVal = $daftarkomentar['db'];

        return $retVal;
    }

    function loadlike($id = [])
    {
        $retVal = array();
        $this->load->library("Dataweb");

        $vCari = array(
            array("cond" => "where_in", "fld" => "l.idaktifitas", "val" => $id),
        );
        $daftar = $this->dataweb->daftarlike($vCari);
        if ($daftar['status'])
            $retVal = $daftar['db'];

        return $retVal;
    }

    public function hapus_aktifitas()
    {
        $retVal = array("status" => false, "pesan" => "", $db = []);
        allowheader();
        $otentikasi = otentikasi($this->d);

        if ($this->session->userdata('iduser')) {
            $this->form_validation->set_rules('id', 'aktifitas', 'trim|required');

            if ($this->form_validation->run()) {
                $id = $this->input->post("id");
                if (akses_akun("delete", $otentikasi, "aktifitas", $id)->status) {
                    $kond = array(
                        array("where", "id", $id),
                    );
                    $retVal = $this->Model_data->delete($kond, "aktifitas", null, true);
                    $retVal['pesan'] = $retVal['pesan'];
                } else {
                    $retVal['pesan'] = ["akses ditolak"];
                }
            } else {
                $retVal['pesan'] = $this->form_validation->error_array();
                $retVal['status'] = false;
            }
        }
        die(json_encode($retVal));
    }

    public function upload_aktifitas()
    {
        $retVal = array("status" => false, "pesan" => "", $db = []);
        allowheader();

        die(json_encode($retVal));
    }

    public function update_baca_semua()
    {
        $retVal = array("status" => false, "pesan" => "");
        allowheader();
        if ($this->session->userdata('iduser')) {
            $sql = "UPDATE notifikasi SET status='1' WHERE iduser='" . $this->session->userdata('iduser') . "'";
            $ex = $this->db->query($sql);
            if ($ex)
                $retVal = array("status" => true, "pesan" => "berhasil update");
        }

        die(json_encode($retVal));
    }

    public function read_aktifitas()
    {
        allowheader();
        $itsme = false;
        $btn_hapusaktifitas = "";
        $btn_uploadaktifitas = "";

        $retVal = $this->detailaktifitas($this->input->post('idaktifitas'));
        $retVal['html'] = "data tidak ditemukan";
        if (count($retVal) > 1) {;

            $dp = (array)$retVal['aktifitas'];
            $listkomentar = $retVal['komentar'];
            $listlampiran = $retVal['lampiran'];
            $listlike = $retVal['like'];

            //update status read untuk notifikasi
            if ($dp['iduser'] == $this->session->userdata('iduser')) {
                $sql = "UPDATE notifikasi SET status='1' WHERE idaktifitas='" . $dp['idaktifitas'] . "'";
                $this->db->query($sql);
            }

            $ketkelompok = ($dp['ketpublishkelompok'] == "terbuka") ? " - " . $dp['jabatan'] . " <br> <a href='" . base_url("dashboard/kelompok/" . $dp['idkelompok']) . "'>Kelompok " . $dp['namakelompok'] . " (" . $dp['desa'] . ")</a>" : "";
            $thumbs = "bi-hand-thumbs-up tambah-like";
            if ($this->session->userdata('iduser'))
                $thumbs .= " tambah-like";

            $adalike = searchMultiArray($listlike, "iduser", $this->session->userdata('iduser'));
            if (count($adalike) > 0)
                $thumbs = "bi-hand-thumbs-up-fill";

            $url = base_url("dashboard/personal/" . $dp['idpenempatan']);
            /*
            if ($dp['iduser'] == $this->session->userdata('iduser') && $globalinput) {
                $itsme = true;
                $btn_hapusaktifitas = "<a href='#' class='btn icon icon-left btn-sm hapus-aktifitas' data-idaktifitas='" . $dp['idaktifitas'] . "' title='hapus aktifitas'><i class='bi bi-trash'></i></a>";
                //$btn_uploadaktifitas = "<a href='#' class='btn icon icon-left btn-sm btn-upload-aktifitas' data-idaktifitas='" . $dp['idaktifitas'] . "' title='upload' ><i class='bi bi-upload'></i></a>";
                $btn_uploadaktifitas = "<input type='file' data-idpenempatan='" . $dp['idpenempatan'] . "' data-idaktifitas='" . $dp['idaktifitas'] . "' style='font-size:10px' class='upload-aktifitas' />";
            }
            */
            $email = $dp['email'];
            if (!$this->session->userdata('iduser')) {
                $email = preg_replace('/(?<=.)[^@](?=[^@]*?@)|(?:(?<=@.)|(?!^)\G(?=[^@]*$)).(?=.*\.)/', 'x', $email);
            }

            $html = "<div class='card rowlkh' data-id='" . $dp['idaktifitas'] . "'>";
            $html .= "<div class='card-body'>";
            $html .= "  <div class='row'>
                                <div class='col-3 col-lg-2 col-md-2'>
                                    <a href='" . $url . "'>
                                        <div class='avatar avatar-xl'>
                                            <img src='" . base_url($dp['profilpic']) . "' height='65px'>
                                        </div>
                                    </a>
                                </div>
                                <div class='col-9 col-lg-10 col-md-10'>
                                    <a href='" . $url . "'>
                                        <h5 class='mb-0' >" . $dp['nama'] . "</h5>
                                    </a>
                                    <div style='font-size:13px;'><i class='bi bi-envelope'></i> " . $email . "</div>
                                    <div class='row' style='font-size:12px'>
                                        <div class='col-12'>
                                        <a href='" . base_url("dashboard/kkn/" . $dp['idkkn']) . "'>" . $dp['tema'] . " " . $dp['tahun'] . "</a> " . $ketkelompok . "
                                        </div>
                                        <div class='col-6'>
                                            <i class='bi bi-clock'></i> " . waktu_lalu($dp['waktu']) . "
                                            " . $btn_hapusaktifitas . "
                                        </div>
                                    </div>
                                </div>
                        ";
            $html .= "<hr style='margin-top:10px;' ><div class='col-md-12'>" . $dp['uraian'] . "</div>";
            $html .= "  <div class='buttons'>
                                " . $btn_uploadaktifitas . "
                        </div>";
            $html .= $this->listlampiran($listlampiran, $itsme);

            $html .= "<div style='font-size:14px;margin-bottom:5px;'>
                            <i class='bi " . $thumbs . "' data-idaktifitas='" . $dp['idaktifitas'] . "' data-iduser='" . $dp['iduser'] . "'></i> " . count($listlike) . " Like &nbsp; 
                            <i class='bi bi-chat-left-text'></i> " . count($listkomentar) . " Komentar</div>";
            if ($this->session->userdata('iduser')) {
                $html .= "  <div class='form-group'>
                                    <div class='row'>
                                        <div class='col-md-2'>
                                            <div class='avatar avatar-lg'>
                                                <img src='" . $this->session->userdata('profilpic') . "' alt='Avatar'>
                                            </div>
                                        </div>
                                        <div class='col-md-10'>
                                            <form class='fkomentar'>
                                                <input type='hidden' name='idaktifitas' value='" . $dp['idaktifitas'] . "'>
                                                <input type='hidden' name='iduser' value='" . $dp['iduser'] . "'>
                                                <textarea class='form-control komentar' name='komentar' rows='3' placeholder='tabe` " . strtolower($this->session->userdata('nama')) . ", kasi komentar tawwa...'></textarea>
                                                <div style='margin-top:5px;'>
                                                    <button type='submit' class='btn btn-primary btn-sm'>Kirim Komentar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>    
                                </div>    
                    ";
            }
            foreach ($listkomentar as $i => $dx) {
                //print_r($dx);
                $html .=    "<div class='row'>
                                    <div class='col-md-1'>
                                        <div class='avatar avatar-sm'>
                                            <img src='" . base_url($dx['profilpic']) . "' alt='Avatar'>
                                        </div>
                                    </div>
                                    <div class='col-md-11'>
                                        <h6>" . $dx['nama'] . "</h6> 
                                        <div style='font-size:11px'><i class='bi bi-clock'></i> " . waktu_lalu($dx['waktu']) . "</div>
                                        <div>&ldquo;" . $dx['komentar'] . "&rdquo;</div>
                                        <hr>
                                    </div>
                                </div>";
            }
            $html .= "</div>";
            $html .= "</div>";
            $html .= "</div>";

            $retVal['html'] = $html;
        }
        die(json_encode($retVal));
    }
}
