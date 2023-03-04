<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi extends CI_Controller
{
    private $d = array(
        "tbName" => "lokasi",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Lokasi KKN",
            "parent" => array("pengaturan"),
            "modul" => "pengaturan/lokasi",
            "view"  => "app/pengaturan",
            "page"    => "Lokasi",
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
        $this->d['web']['loadview'] = $this->d['web']['view'] . "/lokasi";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("datetime"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/pengaturan_lokasi.js?' . date("ymdhis")),
        );


        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        $this->load->library('Datatables');

        $filterTables = simpleSerializeArray($this->input->post('filterTables'));

        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            j.id, j.tema, j.jenis, , j.tempat, j.tahun, j.semester,
            j.daftarmulai, j.daftarselesai, 
            j.kknmulai, j.kknselesai, 
            j.tamulai, j.taselesai, j.nilaimulai, j.nilaiselesai, j.bagikelompok,
		");

        //debug($filterTables);
        $tahun = ($filterTables['flt_tahun'][0] !== "") ? $filterTables['flt_tahun'][0] : null;
        if ($tahun)
            $this->datatables->where("j.tahun", $filterTables["flt_tahun"][0]);

        $semester = ($filterTables['flt_semester'][0] !== "") ? $filterTables['flt_semester'][0] : null;
        if ($semester)
            $this->datatables->where("j.semester", $filterTables["flt_semester"][0]);

        $jenis = ($filterTables['flt_jenis'][0] !== "") ? $filterTables['flt_jenis'][0] : null;
        if ($jenis)
            $this->datatables->where("j.jenis", $filterTables["flt_jenis"][0]);


        $this->datatables->from("kkn as j");
        //echo $this->datatables->last_query();
        /*
			IF('".date('Y-m-d')."' BETWEEN j.daftar_mulai AND j.daftar_selesai,'Terbuka','Tertutup') as ketpendaftaran,
			IF('".date('Y-m-d')."' BETWEEN j.adm_mulai AND j.adm_selesai,'Terbuka','Tertutup') as ketadministrasi,
			IF('".date('Y-m-d')."' BETWEEN j.reviewer_mulai AND j.reviewer_selesai,'Terbuka','Tertutup') as ketreviewer,
			IF('".date('Y-m-d')."' BETWEEN j.seminar_mulai AND j.seminar_selesai,'Terbuka','Tertutup') as ketseminar,
			IF('".date('Y-m-d')."' BETWEEN j.laporan_sementara_mulai AND j.laporan_sementara_selesai,'Terbuka','Tertutup') as ketlaporansementara,
			IF('".date('Y-m-d')."' BETWEEN j.laporan_antara_mulai AND j.laporan_antara_selesai,'Terbuka','Tertutup') as ketlaporanantara,
			IF('".date('Y-m-d')."' BETWEEN j.laporan_akhir_mulai AND j.laporan_akhir_selesai,'Terbuka','Tertutup') as ketlaporanakhir,

		*/
        $retVal = json_decode($this->datatables->generate(), true);

        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['id'] . "'>";
            $tmp['no'] = $no;
            $tmp['tema'] = $dp['tema'];
            $tmp['jenis'] = $dp['jenis'];
            $tmp['tempat'] = $dp['tempat'];
            $tmp['tahun'] = $dp['tahun'] . $dp['semester'];

            $tmp['pendaftaran'] = $dp['daftarmulai'] . " sd " . $dp['daftarselesai'] . " " . labeltanggal($dp['daftarmulai'], $dp['daftarselesai'])['labelbadge'];
            $tmp['pelaksanaan'] = $dp['kknmulai'] . " sd " . $dp['kknselesai'] . " " . labeltanggal($dp['kknmulai'], $dp['kknselesai'])['labelbadge'];
            $tmp['laporan'] = $dp['tamulai'] . " sd " . $dp['taselesai'] . " " . labeltanggal($dp['tamulai'], $dp['taselesai'])['labelbadge'];
            $tmp['penilaian'] = $dp['nilaimulai'] . " sd " . $dp['nilaiselesai'] . " " . labeltanggal($dp['nilaimulai'], $dp['nilaiselesai'])['labelbadge'];
            $tmp['publishkelompok'] = $dp['bagikelompok'] . " " . labeltanggal($dp['bagikelompok'], null, false)['labelbadge'];


            $tmp['aksi'] = "<div class='btn-group me-1 mb-1'>
                                <div class='dropdown'>
                                    <button type='button' class='btn btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
                                    <div class='dropdown-menu dropdown-menu-end' style=''>
                                        <a class='dropdown-item editRow' data-pilih='" . $no . "' data-id='" . $dp['id'] . "' href='#'><i class='bi bi-pencil-square'></i> Ganti</a>
                                        <a class='dropdown-item deleteRow' data-pilih='" . $no . "' data-id='" . $dp['id'] . "' href='#'><i class='bi bi-trash'></i> Hapus</a>
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
        $retVal = $this->retVal;
        $vCari = $this->input->post();

        if (count($vCari) > 0) {
            $this->db->from("kkn");
            $this->db->select("*");

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

    public function delete()
    {
        $retVal = array("status" => false, "pesan" => "", "login" => true);
        $id = $this->input->post('idTerpilih');

        foreach ($id as $i => $dp) {
            if (akses_akun("delete", $this->otentikasi, $this->d['tbName'], $dp)->status) {
                $kond = array(
                    array("where", "id", $dp),
                );
                $retVal = $this->Model_data->delete($kond, $this->d['tbName'], null, true);
                $retVal['pesan'][$i] = $retVal['pesan'];
            } else {
                $retVal['pesan'][$i] = "akses ditolak";
            }
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
        $retVal = array("status" => false, "pesan" => "", "login" => true);
        allowheader();

        $this->form_validation->set_rules('tahun', 'tahun', 'trim|required');
        $this->form_validation->set_rules('slug', 'slug', 'trim|required');
        $this->form_validation->set_rules('slug', 'slug', 'callback_alphacostum');
        $this->form_validation->set_rules('semester', 'semester', 'trim|required');
        $this->form_validation->set_rules('jenis', 'jenis', 'trim|required');
        $this->form_validation->set_rules('tema', 'tema', 'trim|required');
        $this->form_validation->set_rules('tempat', 'tempat', 'trim|required');

        $this->form_validation->set_rules('no_sk', 'no SK', 'trim|required');
        $this->form_validation->set_rules('tgl_sk', 'tanggal SK', 'trim|required');

        $this->form_validation->set_rules('daftarmulai', 'daftarmulai', 'trim|required');
        $this->form_validation->set_rules('daftarselesai', 'daftarselesai', 'trim|required');
        $this->form_validation->set_rules('kknmulai', 'kknmulai', 'trim|required');
        $this->form_validation->set_rules('kknselesai', 'kknselesai', 'trim|required');
        $this->form_validation->set_rules('bagikelompok', 'bagikelompok', 'trim|required');
        $this->form_validation->set_rules('tamulai', 'tamulai', 'trim|required');
        $this->form_validation->set_rules('taselesai', 'taselesai', 'trim|required');
        $this->form_validation->set_rules('kknmulai', 'kknmulai', 'trim|required');
        $this->form_validation->set_rules('nilaimulai', 'nilaimulai', 'trim|required');
        $this->form_validation->set_rules('nilaiselesai', 'nilaiselesai', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idjadwal'];
            unset($dataSave['idjadwal']);

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
