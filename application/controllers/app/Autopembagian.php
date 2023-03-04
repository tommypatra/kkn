<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Autopembagian extends CI_Controller
{
    private $d = array(
        "tbName" => "penempatan",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Pembagian System",
            "parent" => array(""),
            "modul" => "app/autopembagian",
            "view"  => "app",
            "page"  => "Pembagian System",
        ),
    );
    private $retVal = array("status" => false, "pesan" => "");

    function __construct()
    {
        parent::__construct();
        $this->otentikasi = otentikasi($this->d);
    }

    public function index($idkkn = null)
    {
        $this->load->library('Dataweb');
        $this->d['web']['title'] = "Pembagian Kelompok System " . $this->config->item("app_singkatan");

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/autopembagian";
        $this->d['web']['importPlugins'] = array(
            loadPlugins("sweetalert"),
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("myapp"),
        );

        $this->d['web']['importJs'] = array(
            base_url('assets/web/autopembagian.js?' . date("ymdhis")),
        );
        $this->d['idkkn'] = $idkkn;
        //debug($this->d);

        $this->load->view('app/index', $this->d);
    }

    public function datasimulasi()
    {
        allowheader();
        $this->load->library('Dataweb');
        $retVal = $this->retVal;

        $idkkn = $this->input->post("idkkn");
        $jumkel = 50;

        //mencari kelompok
        $vCari[] = array("cond" => "where", "fld" => "sp.idkkn", "val" => $idkkn);
        $daftarkelompok = $this->dataweb->daftarkelompok($vCari);
        $kelompok = [];
        if ($daftarkelompok['status']) {
            $kelompok = $daftarkelompok['db'];
            $jumkel = count($kelompok);
        }

        //mencari peserta kkn
        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
            array("cond" => "where", "fld" => "pm.id IS NULL", "val" => null),
        );
        $pesertakkn = $this->dataweb->pesertakkn($vCari, 0, 0, "u.kel ASC, fak.id ASC, prodi.id ASC");

        $simulai_kelompok = [];
        if ($pesertakkn['status']) {
            $retVal['status'] = true;
            $retVal['pesan'] = ['Data ditemukan'];
            $indexanggota = 1;
            $indexkelompok = 0;
            foreach ($pesertakkn['db'] as $i => $dp) {
                $simulai_kelompok[$indexkelompok][] = $dp;
                $indexanggota++;
                $indexkelompok++;
                if ($indexanggota > $jumkel) {
                    $indexkelompok = 0;
                    $indexanggota = 1;
                }
            }
            $retVal['db'] = $simulai_kelompok;
        }
        $html = "";
        $ind = 0;

        foreach ($simulai_kelompok as $i => $kel) {
            $detkelompok = $kelompok[$i];
            $html .= "<div class='row'>";
            $html .= "  <div>
                            <h5>Kelompok-" . $detkelompok['namakelompok'] . " </h5>
                            <span style='font-size:13px;font-weight:bold;'>DPL. " . $detkelompok['nama'] . " (Kelurahan/Desa " . $detkelompok['desa'] . ")</span>
                        </div>";
            $no = 1;
            foreach ($kel as $j => $dp) {
                $id = "data-" . $ind;
                $class = "simulasi-" . $ind;
                //$vdata = json_encode(
                //  array("idelement" => $id, "idkelompok" => $i, "idpeserta" => $dp['idpeserta']),
                //);
                $html .= "<div class='row " . $class . "'>";
                $html .= "  <div class='col-1' style='text-align:right;' id='" . $id . "'>
                                <input class='cekbaris' type='checkbox' name='data[]' data-idelement='" . $id . "' data-idkelompok='" . $detkelompok['idkelompok'] . "' value='" . $dp['idpeserta'] . "'>
                            </div>";
                $html .= "  <div class='col-11'>" . ($no++) . ". " . $dp['nama'] . " (" . $dp['kel'] . ") - " . $dp['prodi'] . "</div>";
                $html .= "</div>";
                $ind++;
            }
            $html .= "</div>";
        }
        $retVal['dpl'] = $kelompok;
        $retVal['totalpeserta'] = count($pesertakkn['db']);

        $retVal['html'] = $html;
        die(json_encode($retVal));
    }

    public function simpan()
    {
        allowheader();
        $this->load->library('Dataweb');
        $retVal = $this->retVal;

        $this->form_validation->set_rules('idelement', 'id element', 'trim|required');
        $this->form_validation->set_rules('idkelompok', 'kelompok', 'trim|required');
        $this->form_validation->set_rules('idjabatan', 'jabatan', 'trim|required');
        $this->form_validation->set_rules('idpeserta', 'peserta', 'trim|required');

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            unset($dataSave['idelement']);

            if (akses_akun("insert", $this->otentikasi)->status) {
                $retVal = $this->Model_data->save($dataSave, $this->d['tbName'], null, true);
            } else {
                $retVal['pesan'] = ["Maaf, akses ditolak"];
            }
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        //$retVal['post'] = $dataSave;
        $retVal['idelement'] = $this->input->post('idelement');
        die(json_encode($retVal));
    }
}
