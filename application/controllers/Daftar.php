<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Daftar extends CI_Controller
{
    private $d = array(
        "web" => array(
            "title" => "Pendaftaran Akun",
            "modul" => "daftar",
            "view"  => "daftar",
            "page"  => "Pendaftaran Akun",
        ),
    );

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->library("Dataweb");

        $this->d['web']['importPlugins'] = array(
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("sweetalert"),
            loadPlugins("myapp"),
        );
        $this->d['web']['importJs'] = array(
            base_url('assets/web/register.js?' . date("ymdhis")),
        );

        $cari = array(
            array("cond" => "where", "fld" => "g.reg", "val" => "y"),
        );
        $listgrup = $this->dataweb->cariGrup($cari);
        //$this->d["selectgrup"] = arrtoselect2($listgrup['db'], "id", "nama_grup");
        $this->d["selectgrup"] = $listgrup['db'];

        $this->d["loadview"] = "register";
        $this->d['calculate'] = setCalculate();
        $this->load->view("auth", $this->d);
    }


    public function simpan()
    {
        $this->load->library("Dataweb");
        $retVal = array("status" => false, "pesan" => "");

        $this->form_validation->set_rules('nama', 'User', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('hp', 'No. Handphone', 'trim|required');
        $this->form_validation->set_rules('nik', 'NIK (nomor induk penduduk)', 'trim|required');
        $this->form_validation->set_rules('idgrup', 'Grup', 'trim|required');
        $this->form_validation->set_rules('pass1', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('pass2', 'Ulangi password', 'required|matches[pass1]');
        $this->form_validation->set_rules('fldHitung', 'Perhitungan', 'trim|required');
        if ($this->form_validation->run()) {
            $retVal['email'] = $this->input->post('email');
            $retVal['nama'] = $this->input->post('nama');

            $idgrup = $this->input->post("idgrup");

            $fldHit = $this->input->post('fldHitung', true);
            if (($this->session->userdata('v1') + $this->session->userdata('v2')) <> $fldHit) {
                $pesan[] = "Perhitungan anda salah, coba lagi";
                goto next;
            }

            $cekemail = extractemail($retVal['email']);
            $allowmail = allowdomain($cekemail[2]);
            //debug($cekemail);

            if (!$allowmail['status']) {
                $pesan[] = "Pendaftaran hanya menggunakan email terbatas, hubungi admin";
                goto next;
            }


            //start save user
            //$token = generatetoken($this->input->post("idgrup"), $this->input->post('nik'));
            $datanik = decodeNIK($this->input->post('nik'));
            $kond = array(
                array("cond" => "where", "fld" => "prov.kode", "val" => $datanik['prov']),
                array("cond" => "where", "fld" => "kab.kode", "val" => $datanik['kab']),
                array("cond" => "where", "fld" => "kec.kode", "val" => $datanik['kec']),
            );
            $loadwilayahktp = $this->dataweb->loadwilayahktp($kond, 1, 1);
            $wilayah = array(
                "prov" => null,
                "kab" => null,
                "kec" => null,
            );
            if ($loadwilayahktp['status']) {
                $tmpwilayah = $loadwilayahktp['db'][0];
                $wilayah = array(
                    "prov" => $tmpwilayah['idprovinsi'],
                    "kab" => $tmpwilayah['idkabupaten'],
                    "kec" => $tmpwilayah['idkecamatan'],
                );
            }
            //debug($datanik);
            $profilpic = ($datanik['kel'] == "L") ? "assets/img/pria.png" : "assets/img/wanita.png";
            $dataSave = array(
                "email" => $this->input->post('email'),
                "fldpass" => password_hash($this->input->post('pass1', true), PASSWORD_DEFAULT),
                "nama" => $this->input->post('nama'),
                "hp" => $this->input->post('hp'),
                "nik" => $this->input->post('nik'),
                "idprovinsi" => $wilayah['prov'],
                "idkabupaten" => $wilayah['kab'],
                "idkecamatan" => $wilayah['kec'],
                "tgllahir" => $datanik['tgllahir'],
                "kel" => $datanik['kel'],
                "path" => $profilpic,
            );
            $sqlrun = $this->Model_data->save($dataSave, "user", null, true);
            //end save user
            $iduser = $sqlrun['id'];
            if ($sqlrun['status']) {
                $retVal['status'] = true;

                $token = generatetoken($this->input->post("idgrup"), $iduser);
                $dataSave = array(
                    "iduser" => $iduser,
                    "owned" => $iduser,
                    "created" => date("Y-m-d H:i:s"),
                    "aktivasi" => '0',
                    "token" => $token,
                    "idgrup" => $this->input->post("idgrup"),
                );
                $sqlrun = $this->Model_data->save($dataSave, "hakakses", null, true);
                if ($sqlrun['status']) {
                    //start kirim email
                    $pesanemail = " <h3><b>Pendaftaran Berhasil</b></h3>
                                    <p>Klik link berikut untuk melakukan aktivasi " . base_url('daftar/aktivasi/' . $token) . " atau; lakukan aktivasi secara manual dengan menghubungi admin website ini.
                                    </p>
                                    <i>Terima Kasih</i>
                        ";
                    $kirimemail = kirimEmail($this->input->post('email'), "AKTIVASI AKUN", $pesanemail);
                    //end kirim email
                    if ($kirimemail)
                        $pesan[] = "Pendaftaran akun " . $this->input->post('nama') . " berhasil, lakukan aktivasi melalui email " . $this->input->post('email') . " atau dengan menghubungi admin website";
                }


                /* old save 
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
                */
            } else {
                $pesan[] = $sqlrun['pesan'];
            }
        } else {
            $pesan = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        next:
        $pesan = isset($pesan) ? $pesan : "terjadi kesalahan";
        if (!is_array($pesan))
            $pesan = array($pesan);

        $retVal['pesan'] = $pesan;
        $retVal['calculate'] = setCalculate();
        die(json_encode($retVal));
    }

    public function aktivasi($token = null)
    {
        $this->load->library("Dataweb");


        $pesan[] = "Tidak ada aktivasi!";


        $this->db->from("user as u");
        $this->db->select("u.*,h.iduser, h.id as idhakakses, h.aktivasi as hakakses_aktivasi");
        $this->db->where("h.token", $token);
        $this->db->join("hakakses h", "h.iduser=u.id", "left");

        $cekUsr = $this->db->get();

        if ($cekUsr->num_rows() > 0) {
            $akun = $cekUsr->row();
            $idhakakses = $akun->idhakakses;
            $iduser = $akun->iduser;
            //debug($akun);

            $kond = array(
                array("where", "id", $idhakakses),
            );

            if ($akun->hakakses_aktivasi) {
                $pesan = ["Aktivasi akun sudah dilakukan, silahkan login dan lengkapi data!"];
            } else {
                $dataSave = array(
                    "aktivasi" => "1",
                    "iduser_update" => $iduser,
                    "update" => date('Y-m-d H:i:s'),
                );
                $retVal = $this->Model_data->update($kond, $dataSave, "hakakses", "aktivasi akun ", true);
                $pesan = [$retVal['pesan'][0]];
            }
        }
        echo "<html>
				<head>
					<title>Aktivasi Akun</title>
				</head>
                <body>";
        echo "<h4>INFO AKTIVASI AKUN</h4>";
        if (count($pesan) > 0) {
            echo "<ul>";
            foreach ($pesan as $info) {
                echo "<li>" . $info . "</li>";
            }
            echo "</ul>";
        }
        echo "<a href='" . base_url() . "'>Klik disini untuk kembali ke laman utama web</a>";
        echo "  </body>
			</html>";
    }

    public function aktivasi_old($token = null)
    {
        $this->load->library("Dataweb");


        $pesan[] = "Tidak ada aktivasi!";
        //$runquery = $this->Model_data->searchData($kond, "user", "*");


        $this->db->from("user as u");
        $this->db->select("u.*,
                        m.id as idmahasiswa,
                        p.id as idpembimbing,
        ");
        $this->db->where("u.token", $token);
        $this->db->join("mahasiswa m", "m.iduser=u.id and (m.aktivasi!='y' OR m.aktivasi IS NULL) ", "left");
        $this->db->join("pembimbing p", "p.iduser=u.id and (p.aktivasi!='y' OR p.aktivasi IS NULL)", "left");
        $this->db->where("u.aktivasi", "y");
        $this->db->group_by("u.id");
        $cekUsr = $this->db->get();

        //debug($cekUsr);
        if ($cekUsr->num_rows() > 0) {
            $akun = $cekUsr->row();
            $iduser = $akun->id;
            $idmahasiswa = $akun->idmahasiswa;
            $idpembimbing = $akun->idpembimbing;
            //debug($akun);

            $kond = array(
                array("where", "iduser", $iduser),
            );
            $isupdate = false;
            if ($idmahasiswa) {
                $isupdate = true;
                $tableref = "mahasiswa";
                $dataSave = array(
                    "aktivasi" => "y",
                    "iduser_update" => $iduser,
                    "update" => date('Y-m-d H:i:s'),
                );
            } elseif ($idpembimbing) {
                $isupdate = true;
                $tableref = "pembimbing";
                $dataSave = array(
                    "aktivasi" => "y",
                    "iduser_update" => $iduser,
                    "update" => date('Y-m-d H:i:s'),
                );
            } else {
                $pesan = ["Aktivasi akun sudah dilakukan, silahkan login dan lengkapi data!"];
            }

            if ($isupdate) {
                $retVal = $this->Model_data->update($kond, $dataSave, $tableref, "aktivasi " . $tableref, true);
                $pesan = [$retVal['pesan'][0]];
            }
        }
        echo "<html>
				<head>
					<title>Aktivasi Akun</title>
				</head>
                <body>";
        echo "<h4>INFO AKTIVASI AKUN</h4>";
        if (count($pesan) > 0) {
            echo "<ul>";
            foreach ($pesan as $info) {
                echo "<li>" . $info . "</li>";
            }
            echo "</ul>";
        }
        echo "<a href='" . base_url() . "'>Klik disini untuk kembali ke laman utama web</a>";
        echo "  </body>
			</html>";
    }
}
