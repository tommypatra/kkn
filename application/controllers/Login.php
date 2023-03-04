<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    private $d = array(
        "web" => array(
            "title" => "Login Aplikasi Web",
            "modul" => "login",
            "page"  => "Login",
            "view"  => "app/login",
        ),
    );

    function __construct()
    {
        parent::__construct();
        cekLogin();
    }

    public function encode($vpass = null)
    {
        if ($vpass) {
            $pass = password_hash($vpass, PASSWORD_DEFAULT);
            echo $pass;
        }
    }


    public function index()
    {
        $this->load->library("select2");
        $this->d['web']['importPlugins'] = array(
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("sweetalert"),
            loadPlugins("myapp"),
        );
        $this->d['web']['importJs'] = array(
            base_url('assets/web/login.js?' . date("ymdhis")),
        );
        $this->d["loadview"] = "login";

        $this->d['calculate'] = setCalculate();
        $this->load->view("auth", $this->d);
    }

    public function email()
    {
        //$this->setupgoogle();
        setupgoogle();
        $user_data = array();
        if (isset($_GET["code"])) {
            $token = $this->google->fetchAccessTokenWithAuthCode($_GET["code"]);
            //debug($token);
            if (!isset($token["error"])) {
                $this->google->setAccessToken($token['access_token']);
                //debug($token['access_token']);
                $gs = new Google_Service_Oauth2($this->google);
                $data = $gs->userinfo->get();
                //debug($data);
                $this->cekemail($data, $token);
                die;
            }
        }

        redirect("login");
    }

    public function limitlogin($iduser = "", $limit = true)
    {
        $idsession = $this->session->session_id;
        $tableName = "sessions_web";

        if ($idsession <> "" && $iduser <> "" && $limit) {

            $conduser = array(
                array('where', 'iduser', $iduser),
            );
            $this->Model_data->delete($conduser, $tableName, null, true);

            $cond = array(
                array('where', 'id', $idsession),
            );
            $datasave = array(
                'iduser' => $iduser,
            );
            $this->Model_data->update($cond, $datasave, $tableName, null, true);
        }
        return true;
    }

    public function cekemail($data = [], $token = [])
    {
        //debug($data);

        $email = isset($data['email']) ? $data['email'] : null;
        //debug($data,false);
        $cekUsr = $this->Model_data->runQuery("
        SELECT u.*,
            m.id as idmahasiswa,
            p.id as idpembimbing, 
            a.id as idadmin 
        FROM user as u
            LEFT JOIN mahasiswa as m ON (m.iduser=user.id and m.aktivasi='y') 
            LEFT JOIN pembimbing as p ON (p.iduser=user.id and p.aktivasi='y') 
            LEFT JOIN admin as a ON (a.iduser=user.id and a.aktivasi='y') 
        WHERE user.email='" . $email . "'
        GROUP BY user.id 
        ");
        //debug($cekUsr);
        if ($cekUsr['db']->num_rows() > 0) {
            $akun = $cekUsr['db']->row();
            $iduser = $akun->id;
            $nama = $akun->nama;
            $pesan = "User ditemukan, Selamat Datang " . $nama . ". Silahkan Menunggu, sedang diarahkan...";
            if (strlen($nama) > 15)
                $nama = substr($nama, 0, 12) . "...";

            $groups = array();
            foreach ($cekUsr['db']->result_array() as $dp) {
                $groups[] = $dp['idgrup'];
            }

            $this->set_session($cekUsr['db'], $token, $data);

            $this->limitlogin($iduser);
            $this->loginhistory($iduser);

            $retVal['status'] = true;
            $retVal['login'] = true;
        } else {
            $email = extractemail($data['email']);
            //debug($email);
            $status = "EKSTERNAL";
            //$sebagai = "DOSEN";
            $institusi = "Parepare";
            $aktivasi = "y";

            if ($email[2] == "iainpare.ac.id") {
                $status = "INTERNAL";
                $institusi = "IAIN Parepare";
                $aktivasi = "n";
            }

            $dataSave = array(
                "nama" => $data['givenName'] . " " . $data['familyName'],
                "email" => $email[0],
                "institusi" => $institusi,
                "status" => $status,
                "tmplahir" => "",
                "aktivasi_self" => "y",
                "aktivasi" => "y",
                "tgllahir" => date("Y-m-d"),
                "waktudaftar" => date("Y-m-d H:i:s"),
            );
            //debug($dataSave);
            $retVal = $this->Model_data->save($dataSave, "user", null, true);
            $iduser = $retVal['id'];
            if ($retVal['status']) {
                $dataSave = array(
                    "iduser" => $iduser,
                    "idgrup" => 11,
                );
                $retVal = $this->Model_data->save($dataSave, "grup_user", null, true);
                if ($retVal['status']) {
                    $this->cekemail($data, $token);
                    /*
					$this->limitlogin($iduser);
					$this->loginhistory($iduser);
					$url="pendaftaran/app/akun/identitas/".encdec("enc",$iduser);
					echo $url;die;	
					redirect($url);
					die;
					*/
                }
            }
            //$this->google->revokeToken($token);
        }
        redirect("login");
    }

    public function cekemail_new($data = [], $token = [])
    {
        //debug($data);

        $email = isset($data['email']) ? $data['email'] : null;
        //debug($data,false);
        $cekUsr = $this->Model_data->runQuery("
        SELECT u.*,h.idgrup,
        FROM user as u
            LEFT JOIN hakakses as h ON h.iduser=user.id
        WHERE user.email='" . $email . "' AND h.aktivasi='1'
        ");
        //debug($cekUsr);
        if ($cekUsr['db']->num_rows() > 0) {
            $akun = $cekUsr['db']->row();
            $iduser = $akun->id;
            $nama = $akun->nama;
            $pesan = "User ditemukan, Selamat Datang " . $nama . ". Silahkan Menunggu, sedang diarahkan...";
            if (strlen($nama) > 15)
                $nama = substr($nama, 0, 12) . "...";

            $groups = array();
            foreach ($cekUsr['db']->result_array() as $dp) {
                $groups[] = $dp['idgrup'];
            }

            $this->set_session_new($cekUsr['db'], $token, $data);
            //$this->limitlogin($iduser);
            $this->loginhistory($iduser);

            $retVal['status'] = true;
            $retVal['login'] = true;
        } else {
            $email = extractemail($data['email']);
            //debug($email);
            $status = "EKSTERNAL";
            //$sebagai = "DOSEN";
            $institusi = "Parepare";
            $aktivasi = "y";

            if ($email[2] == "iainpare.ac.id") {
                $status = "INTERNAL";
                $institusi = "IAIN Parepare";
                $aktivasi = "n";
            }

            $dataSave = array(
                "nama" => $data['givenName'] . " " . $data['familyName'],
                "email" => $email[0],
                "institusi" => $institusi,
                "status" => $status,
                "tmplahir" => "",
                "aktivasi_self" => "y",
                "aktivasi" => "y",
                "tgllahir" => date("Y-m-d"),
                "waktudaftar" => date("Y-m-d H:i:s"),
            );
            //debug($dataSave);
            $retVal = $this->Model_data->save($dataSave, "user", null, true);
            $iduser = $retVal['id'];
            if ($retVal['status']) {
                $dataSave = array(
                    "iduser" => $iduser,
                    "idgrup" => 11,
                );
                $retVal = $this->Model_data->save($dataSave, "grup_user", null, true);
                if ($retVal['status']) {
                    $this->cekemail($data, $token);
                    /*
					$this->limitlogin($iduser);
					$this->loginhistory($iduser);
					$url="pendaftaran/app/akun/identitas/".encdec("enc",$iduser);
					echo $url;die;	
					redirect($url);
					die;
					*/
                }
            }
            //$this->google->revokeToken($token);
        }
        redirect("login");
    }

    public function loginhistory($iduser)
    {
        $dataSave = array(
            'iduser' => $iduser,
            'lastlogin' => date('Y-m-d H:i:s'),
            'detail' => get_client_info(),
        );
        $runquery = $this->db->insert("login_history", $dataSave);
        return $runquery;
    }

    public function cek()
    {
        allowheader();
        $retVal = array("status" => false, "pesan" => "", "noid" => null, "login" => false);
        $vUrl = $this->input->post('vurl');

        $this->form_validation->set_rules('fldUser', 'User', 'trim|required');
        $this->form_validation->set_rules('fldPass', 'Password', 'trim|required');
        $this->form_validation->set_rules('fldHitung', 'Perhitungan', 'trim|required');
        if ($this->form_validation->run()) {

            $fldUsr = $this->input->post('fldUser', true);
            $fldPass = $this->input->post('fldPass', true);
            $fldHit = $this->input->post('fldHitung', true);


            if (($this->session->userdata('v1') + $this->session->userdata('v2')) <> $fldHit) {
                $pesan = "Perhitungan anda salah, coba lagi";
                goto next;
            }

            $this->db->from("user as u");
            $this->db->select("u.*,
                            if(m.id IS NOT NULL,m.idgrup,null) as grpmahasiswa,
                            if(p.id IS NOT NULL,p.idgrup,null) as grppembimbing,
                            if(a.id IS NOT NULL,a.idgrup,null) as grpadmin,
            ");
            $this->db->join("mahasiswa m", "m.iduser=u.id AND m.aktivasi='y'", "left");
            $this->db->join("pembimbing p", "p.iduser=u.id AND p.aktivasi='y'", "left");
            $this->db->join("admin a", "a.iduser=u.id AND a.aktivasi='y'", "left");
            $this->db->where("u.aktivasi", "y");
            $this->db->where("u.email", $fldUsr);
            $cekUsr = $this->db->get();

            //debug($cekUsr);
            if ($cekUsr->num_rows() > 0) {
                $akun = $cekUsr->row();
                $iduser = $akun->id;
                if (password_verify($fldPass, $akun->fldpass)) {
                    $nama = $akun->nama;
                    $pesan = "User ditemukan, Selamat Datang " . $nama . ". Silahkan Menunggu, sedang diarahkan...";

                    $this->loginhistory($iduser);
                    $this->set_session($cekUsr);
                    //$this->limitlogin($iduser);

                    $retVal['status'] = true;
                    $retVal['login'] = true;
                    $retVal['iddesa'] = $akun->iddesa;
                } else {
                    $pesan = "Password anda salah";
                }
            } else {
                $pesan = "User " . $fldUsr . " tidak ditemukan";
            }
        } else {
            $pesan = $this->form_validation->error_array();
        }

        next:
        if (!is_array($pesan))
            $pesan = array($pesan);

        $retVal['pesan'] = $pesan;
        $retVal['calculate'] = setCalculate();
        //debug($vUrl);
        //debug($retVal,1);
        die(json_encode($retVal));
    }

    public function cek_new()
    {
        allowheader();
        $retVal = array("status" => false, "pesan" => "", "noid" => null, "login" => false);
        $vUrl = $this->input->post('vurl');

        $this->form_validation->set_rules('fldUser', 'User', 'trim|required');
        $this->form_validation->set_rules('fldPass', 'Password', 'trim|required');
        $this->form_validation->set_rules('fldHitung', 'Perhitungan', 'trim|required');
        if ($this->form_validation->run()) {

            $fldUsr = $this->input->post('fldUser', true);
            $fldPass = $this->input->post('fldPass', true);
            $fldHit = $this->input->post('fldHitung', true);


            if (($this->session->userdata('v1') + $this->session->userdata('v2')) <> $fldHit) {
                $pesan = "Perhitungan anda salah, coba lagi";
                goto next;
            }

            $this->db->from("user as u");
            $this->db->select("u.*,h.id as idhakakses,h.iduser,h.idgrup,h.aktivasi as hakakses_aktivasi");
            $this->db->join("hakakses h", "h.iduser=u.id and h.aktivasi='1'", "left");
            //$this->db->where("h.aktivasi", "1");
            $this->db->where("u.email", $fldUsr);
            $this->db->order_by("h.idgrup ASC");
            $cekUsr = $this->db->get();

            //debug($cekUsr);
            if ($cekUsr->num_rows() > 0) {
                $akun = $cekUsr->row();
                $iduser = $akun->id;
                if (password_verify($fldPass, $akun->fldpass)) {

                    $nama = $akun->nama;
                    $pesan = "User ditemukan, Selamat Datang " . $nama . ". Silahkan Menunggu, sedang diarahkan...";

                    $this->loginhistory($iduser);
                    $this->set_session_new($cekUsr);
                    //$this->limitlogin($iduser);

                    $retVal['status'] = true;
                    $retVal['login'] = true;
                    $retVal['iddesa'] = $akun->iddesa;
                } else {
                    $pesan = "Password anda salah";
                }
            } else {
                $pesan = "User " . $fldUsr . " tidak ditemukan";
            }
        } else {
            $pesan = $this->form_validation->error_array();
        }

        next:
        if (!is_array($pesan))
            $pesan = array($pesan);

        $retVal['pesan'] = $pesan;
        $retVal['calculate'] = setCalculate();
        //debug($vUrl);
        //debug($retVal,1);
        die(json_encode($retVal));
    }

    public function set_session_new($cekUsr, $token = [], $data = [])
    {
        if ($cekUsr->num_rows() > 0) {
            $akun = $cekUsr->row();

            $groups = [0];
            $role = 0;
            foreach ($cekUsr->result_array() as $dp) {
                if ($role == 0 && $dp['idgrup'] != 0) {
                    $role = (int)$dp['idgrup'];
                }
                $groups[] = (int)$dp['idgrup'];
            }

            //debug($groups);
            //if (count($groups) > 0)
            //unset($groups[0]);

            //debug($groups);
            $nama = $akun->nama;
            if (strlen($nama) > 15)
                $nama = substr($nama, 0, 12) . "...";

            $myprofilpic = base_url($akun->path);
            if (count($token) > 0 && count($data) > 0)
                $myprofilpic = $data['picture'];

            $data = array(
                "iduser" => $akun->id,
                "idgrup" => json_encode($groups),
                "role" => $role,
                "nama" => $akun->nama,
                "email" => $akun->email,
                "namalengkap" => trim($akun->glrdepan . ' ' . $akun->nama . ' ' . $akun->glrbelakang),
                "logintime" => date("Y-m-d H:i:s"),
                "iddesa" => $akun->iddesa,
                "institusi" => $akun->institusi,
                "profilpic" => $myprofilpic,
            );
            $this->session->set_userdata($data);
        }
    }


    public function set_session($cekUsr, $token = [], $data = [])
    {
        if ($cekUsr->num_rows() > 0) {
            $akun = $cekUsr->row();

            $groups = [0];
            if ($akun->grpmahasiswa)
                $groups[] = $akun->grpmahasiswa;
            if ($akun->grppembimbing)
                $groups[] = $akun->grppembimbing;
            if ($akun->grpadmin)
                $groups[] = $akun->grpadmin;


            $nama = $akun->nama;
            if (strlen($nama) > 15)
                $nama = substr($nama, 0, 12) . "...";

            $myprofilpic = base_url($akun->path);
            if (count($token) > 0 && count($data) > 0)
                $myprofilpic = $data['picture'];

            $data = array(
                "iduser" => $akun->id,
                "idgrup" => json_encode($groups),
                "nama" => $akun->nama,
                "email" => $akun->email,
                "namalengkap" => trim($akun->glrdepan . ' ' . $akun->nama . ' ' . $akun->glrbelakang),
                "logintime" => date("Y-m-d H:i:s"),
                "iddesa" => $akun->iddesa,
                "institusi" => $akun->institusi,
                "profilpic" => $myprofilpic,
            );
            $this->session->set_userdata($data);
        }
    }

    public function auto($email = null)
    {
        //$this->session->sess_destroy();
        $fldUsr = $email . "@iainpare.ac.id";
        $this->db->from("user as u");
        $this->db->select("u.*,
                            if(m.id IS NOT NULL,m.idgrup,null) as grpmahasiswa,
                            if(p.id IS NOT NULL,p.idgrup,null) as grppembimbing,
                            if(a.id IS NOT NULL,a.idgrup,null) as grpadmin,
            ");
        $this->db->join("mahasiswa m", "m.iduser=u.id AND m.aktivasi='y'", "left");
        $this->db->join("pembimbing p", "p.iduser=u.id AND p.aktivasi='y'", "left");
        $this->db->join("admin a", "a.iduser=u.id AND a.aktivasi='y'", "left");
        $this->db->where("u.aktivasi", "y");
        $this->db->where("u.email", $fldUsr);
        $cekUsr = $this->db->get();

        if ($cekUsr->num_rows() > 0) {
            $akun = $cekUsr->row();
            $iduser = $akun->id;

            $nama = $akun->nama;
            $pesan = "User ditemukan, Selamat Datang " . $nama . ". Silahkan Menunggu, sedang diarahkan...";

            //$this->loginhistory($iduser);
            $this->set_session($cekUsr);
            //$this->limitlogin($iduser);
            $retVal['status'] = true;
            $retVal['login'] = true;
            $retVal['iddesa'] = $akun->iddesa;
        } else {
            $pesan = "User " . $fldUsr . " tidak ditemukan";
        }
        redirect("web");
    }
}
