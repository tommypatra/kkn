<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Akun extends CI_Controller
{
    private $d = array(
        "tbName" => "user",
        "otentikasi" => array(),
        "primaryKey" => "id",
        "web" => array(
            "title" => "Akun Aplikasi",
            "parent" => array("pengaturan"),
            "modul" => "app/Akun",
            "view"  => "app",
            "page"  => "Akun",
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

        $this->d['web']['loadview'] = $this->d['web']['view'] . "/akun";
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
            base_url('assets/web/app/akun.js?' . date("ymdhis")),
        );
        $this->load->view('app/index', $this->d);
    }


    public function read()
    {
        allowheader();
        $this->load->library('Datatables');
        $this->load->library('Dataweb');

        $this->datatables->select("
			'' as cek, '' as no, '' as aksi, 
            TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama,
            u.nama as namaasli,
            u.id, u.email, u.nik,u.kel,u.tmplahir,u.tgllahir,u.alamat,u.hp,u.institusi,u.path,u.aktivasi,
		");
        $this->datatables->from("user as u");

        $retVal = json_decode($this->datatables->generate(), true);
        //echo $this->db->last_query();
        $data = array();
        $ids = array();
        $listgrup = array();
        $no = 0;

        if (count($retVal['data']) > 0) {
            foreach ($retVal['data'] as $index => $dp) {
                $ids[] = $dp['id'];
            }
            if (count($ids) > 0) {
                $cond = array(
                    array("cond" => "where_in", "fld" => "u.id", "val" => $ids),
                );
                $carigrup = $this->dataweb->cariGrupUser($cond);
                if ($carigrup['status'])
                    $listgrup = $carigrup['db'];
            }
            //debug($listgrup);

            foreach ($retVal['data'] as $index => $dp) {
                $no++;
                $grupakun = searchMultiArray($listgrup, "id", $dp['id']);
                $is_admin = array();
                $is_mahasiswa = array();
                $is_pembimbing = array();
                $grup = "";
                if (count($grupakun) > 0) {
                    $grupakun = $grupakun[0];
                    $is_admin = json_decode($grupakun['is_admin'], true);
                    $is_mahasiswa = json_decode($grupakun['is_mahasiswa'], true);
                    $is_pembimbing = json_decode($grupakun['is_pembimbing'], true);

                    if ($is_admin['status'])
                        $grup .= "<span class='badge bg-success'>Admin</span>";

                    if ($is_mahasiswa['status'])
                        $grup .= "<span class='badge bg-secondary'>Mahasiswa</span>";

                    if ($is_pembimbing['status'])
                        $grup .= "<span class='badge bg-primary'>Pembimbing</span>";
                }

                $tmp['cek'] = "<input type='checkbox' class='cekbaris' id='cek" . $no . "' name='pilihcek[" . $no . "]' value='" . $dp['id'] . "'>";
                $tmp['no'] = $no;
                $tmp['nama'] = $dp['nama'] . "<div style='font-size:12px;'>" . $dp['nik'] . "</div>";
                $tmp['grup'] = $grup;
                $tmp['foto'] = "<div class='avatar avatar-xl'><img src='" . base_url($dp['path']) . "' ></div>";
                $tmp['email'] = $dp['email'];
                $tmp['namaasli'] = $dp['namaasli'];
                $tmp['tmplahir'] = $dp['tmplahir'] . "<div style='font-size:12px;'>" . $dp['tgllahir'] . "</div>";
                $tmp['alamat'] = $dp['alamat'] . "<div style='font-size:12px;'>" . $dp['hp'] . "</div> <div style='font-size:12px;'>" . $dp['email'] . "</div>";
                $tmp['aktivasi'] = $dp['aktivasi'];
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
        }


        $retVal['data'] = $data;
        die(json_encode($retVal));
    }

    public function delete()
    {
        allowheader();
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

    public function updategrup($iduser = null, $dtgrup = [])
    {

        if (count($dtgrup) > 0 && $iduser) {

            $kond = array(
                array("where", "iduser", $iduser),
            );
            $runquery = $this->Model_data->delete($kond, "grup", null, true);

            foreach ($dtgrup as $idgrup) {
                $kond = array(
                    array("where", "id", $idgrup),
                );
                $runquery = $this->Model_data->searchData($kond, "grup", "*");

                if ($runquery['db']->num_rows() > 0) {
                    $dt = $runquery['db']->row();

                    $dataSave = array(
                        "iduser" => $iduser,
                        "owned" => $iduser,
                        "idgrup" => $this->input->post("idgrup"),
                    );
                    $sqlrun = $this->Model_data->save($dataSave, $dt->tableref, null, true);
                    //$pesan[] = $sqlrun['pesan'];
                    //end insert grup
                    if ($sqlrun['status']) {
                        //start kirim email
                        $pesanemail = "    <h3><b>Pendaftaran Berhasil</b></h3>
                                <p>Klik link berikut untuk melakukan aktivasi " . base_url('daftar/aktivasi/' . $token) . " atau; lakukan aktivasi secara manual dengan menghubungi admin website ini.
                                </p>
                                <i>Terima Kasih</i>
                    ";
                        $kirimemail = kirimEmail($this->input->post('email'), "AKTIVASI AKUN", $pesanemail);
                        //end kirim email
                        if ($kirimemail)
                            $pesan[] = "Pendaftaran akun " . $this->input->post('nama') . " berhasil, lakukan aktivasi melalui email " . $this->input->post('email') . " atau dengan menghubungi admin website";
                    }
                } else {
                    $pesan[] = "Grup tidak ditemukan";
                }
            }
        }
    }

    public function simpan()
    {
        allowheader();
        $retVal = array("status" => false, "pesan" => "", "login" => true);

        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('nik', 'NIK', 'trim|required');
        $this->form_validation->set_rules('nama', 'nama Akun', 'trim|required');
        $this->form_validation->set_rules('kel', 'jenis kelamin', 'trim|required');
        $this->form_validation->set_rules('tmplahir', 'tempat lahir', 'trim|required');
        $this->form_validation->set_rules('tgllahir', 'tanggal lahir', 'trim|required');
        $this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
        if ($this->input->post('gantipass')) {
            $this->form_validation->set_rules('pass1', 'Password', 'required|min_length[8]');
            $this->form_validation->set_rules('pass2', 'Ulangi password', 'required|matches[pass1]');
        }

        // $idgrup = $this->input->post('idgrup');
        // if (count($idgrup) < 1) {
        //     $retVal['pesan'] = ["grup tidak boleh kosong"];
        //     die(json_encode($retVal));
        // }

        if ($this->form_validation->run()) {
            $dataSave = $this->input->post();
            $id = $dataSave['id'];
            unset($dataSave['id']);
            // unset($dataSave['idgrup']);
            if ($this->input->post('pass1')) {
                // $dataSave['fldpass'] = $dataSave['pass1'];
                $dataSave['fldpass'] = password_hash($dataSave['pass1'], PASSWORD_DEFAULT);
            }
            unset($dataSave['pass1']);
            unset($dataSave['pass2']);
            //debug($dataSave);
            unset($dataSave['gantipass']);

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
