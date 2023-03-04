<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi extends CI_Controller
{
    private $d = array(
        "tbName" => "lokasi",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Lokasi",
            "parent" => array("pengaturan"),
            "modul" => "app/lokasi",
            "view"  => "app",
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
        $this->d['web']['title'] = $this->d['web']['title'] . " " . $this->config->item("app_singkatan");

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/lokasi";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("datatables"),
            loadPlugins("datetime"),
            loadPlugins("mask"),
            loadPlugins("select2"),
            loadPlugins("select2lib"),
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );
        $this->d['web']['importJs'] = array(
            base_url('assets/web/lokasi.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        $this->load->library('Datatables');

        $filterTables = simpleSerializeArray($this->input->post('filterTables'));
        $setupjadwal = simpleSerializeArray($this->input->post('setupjadwal'));

        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            l.id,l.pergi,l.pulang,l.keterangan,
            d.id as iddesa,d.desa,
            kec.id as idkec,kec.kecamatan,
            kab.id as idkab,kab.kabupaten,
            prov.id as idprov,prov.provinsi,
            CONCAT(FORMAT(l.pergi, 0, 'de_DE')) as fpergi,
            CONCAT(FORMAT(l.pulang, 0, 'de_DE')) as fpulang,
		");

        $this->datatables->join("wilayah_desa as d", "d.id=l.iddesa", "left");
        $this->datatables->join("wilayah_kec as kec", "kec.id=d.idkecamatan", "left");
        $this->datatables->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->datatables->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");

        $idkkn = isset($setupjadwal['idjadwalkkn'][0]) ? $setupjadwal['idjadwalkkn'][0] : "none";
        //if ($idkkn )
        $this->datatables->where("l.idkkn", $idkkn);


        /*
        $tahun = ($filterTables['flt_tahun'][0] !== "") ? $filterTables['flt_tahun'][0] : null;
        if ($tahun)
            $this->datatables->where("j.tahun", $filterTables["flt_tahun"][0]);

        $semester = ($filterTables['flt_semester'][0] !== "") ? $filterTables['flt_semester'][0] : null;
        if ($semester)
            $this->datatables->where("j.semester", $filterTables["flt_semester"][0]);

        $jenis = ($filterTables['flt_jenis'][0] !== "") ? $filterTables['flt_jenis'][0] : null;
        if ($jenis)
            $this->datatables->where("j.jenis", $filterTables["flt_jenis"][0]);
        */

        $this->datatables->from("lokasi as l");
        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $no = 0;
        foreach ($retVal['data'] as $index => $dp) {
            $no++;
            $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['id'] . "'>";
            $tmp['no'] = $no;
            $tmp['desa'] = $dp['desa'];
            $tmp['kecamatan'] = $dp['kecamatan'];
            $tmp['kabupaten'] = $dp['kabupaten'] . "/ " . $dp['provinsi'];
            $tmp['pergi'] = $dp['pergi'];
            $tmp['fpergi'] = $dp['fpergi'];
            $tmp['pulang'] = $dp['pulang'];
            $tmp['fpulang'] = $dp['fpulang'];
            $tmp['keterangan'] = $dp['keterangan'];

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
            $this->db->from("lokasi as l");
            $this->db->select("l.*,
                            CONCAT(FORMAT(l.pergi, 0, 'de_DE')) as fpergi,
                            CONCAT(FORMAT(l.pulang, 0, 'de_DE')) as fpulang,
                            d.desa, d.kode as kodewilayah_desa, d.kodewilayah_kec, d.kodewilayah_kab,d.kodewilayah_prov,
                            kec.kecamatan,kab.kabupaten,prov.provinsi");

            $this->db->join("wilayah_desa as d", "d.id=l.iddesa", "left");
            $this->db->join("wilayah_kec as kec", "kec.id=d.idkecamatan", "left");
            $this->db->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
            $this->db->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");
            $this->db->order_by("prov.provinsi ASC, kab.kabupaten ASC, kec.kecamatan ASC, d.desa ASC");

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
        $retVal = array("status" => false, "pesan" => [], "login" => true);
        $id = $this->input->post('idTerpilih');

        foreach ($id as $i => $dp) {
            $retVal['pesan'][$i] = "akses ditolak";
            $run = akses_akun("delete", $this->otentikasi, $this->d['tbName'], $dp);
            if ($run->status) {
                $kond = array(
                    array("where", "id", $dp),
                );
                $runquery = $this->Model_data->delete($kond, $this->d['tbName'], null, true);
                $retVal['pesan'][$i] = $runquery['pesan'];
                $retVal['status'] = true;
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

        $this->form_validation->set_rules('idkkn', 'Pilihan KKN', 'trim|required');
        $this->form_validation->set_rules('idprovinsi', 'Provinsi', 'trim|required');
        $this->form_validation->set_rules('idkabupaten', 'Kabupaten', 'trim|required');
        $this->form_validation->set_rules('idkec', 'Kecamatan', 'callback_alphacostum');
        $this->form_validation->set_rules('idkecamatan', 'Desa', 'trim|required');
        $this->form_validation->set_rules('pergi', 'Pergi', 'trim|required');
        $this->form_validation->set_rules('pulang', 'Pulang', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['idlokasi'];
            unset($dataSave['idlokasi']);
            unset($dataSave['idprovinsi']);
            unset($dataSave['idkabupaten']);
            unset($dataSave['idkecamatan']);

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
