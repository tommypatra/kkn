<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resetpass extends CI_Controller
{
    private $d = array(
        "web" => array(
            "title" => "Reset Password",
            "modul" => "resetpass",
            "page"  => "Resetpass",
            "view"  => "",
        ),
    );

    function __construct()
    {
        parent::__construct();
    }


    public function index($token = null)
    {
        $this->d['web']['importPlugins'] = array(
            loadPlugins("loading"),
            loadPlugins("validation"),
            loadPlugins("sweetalert"),
            loadPlugins("myapp"),
        );
        $this->d['web']['importJs'] = array(
            base_url('assets/web/resetpass.js?' . date("ymdhis")),
        );

        $this->d['idtoken'] = $token;
        $this->d['token'] = $this->statustoken($token);
        $this->load->view("resetpass", $this->d);
    }

    public function jumtoken($email = null)
    {
        $retVal = array("status" => false, "pesan" => "token tidak ditemukan", "jumlah" => 0);
        $this->db->from("reset_password as rp");
        $this->db->select("COUNT(rp.id) as tot");
        $this->db->join("user as u", "u.id=rp.iduser", "left");
        $this->db->where("'" . date("Y-m-d") . "'=DATE(rp.created)", null);
        $this->db->where("u.email", $email);
        $cekDt = $this->db->get();
        //echo $this->db->last_query();
        //debug($cekUsr);
        if ($cekDt->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "ditemukan", "jumlah" => $cekDt->row()->tot);
        }
        return $retVal;
    }

    public function statustoken($token = null)
    {
        $retVal = array("status" => false, "pesan" => "token tidak ditemukan", "db" => []);

        $this->db->from("reset_password as rp");
        $this->db->select("rp.*,u.*");
        $this->db->join("user as u", "u.id=rp.iduser", "left");
        //$this->db->where("rp.aktif", "n");
        $this->db->where("DATE_ADD(NOW(), INTERVAL 8 HOUR)<rp.expired", null);
        $this->db->where("rp.token", $token);
        $cekDt = $this->db->get();
        //echo $this->db->last_query();
        //debug($cekUsr);
        if ($cekDt->num_rows() > 0) {
            $reset = $cekDt->row();
            $retVal = array("status" => true, "pesan" => "ditemukan", "db" => $reset);
        } else {
            $retVal = array("status" => false, "pesan" => "Maaf, token expired");
        }
        //debug($retVal);
        return $retVal;
    }

    public function kirimlink()
    {
        allowheader();
        $retVal = array("status" => false, "pesan" => "");
        $pesan = [];
        $this->form_validation->set_rules('email_lupa', 'Email', 'trim|required');
        if ($this->form_validation->run()) {

            $fldUsr = $this->input->post('email_lupa', true);

            $this->db->from("user as u");
            $this->db->select("u.*");
            $this->db->where("u.aktivasi", "y");
            $this->db->where("u.email", $fldUsr);
            $cekUsr = $this->db->get();

            //debug($cekUsr);
            if ($cekUsr->num_rows() > 0) {
                $akun = $cekUsr->row();
                $jumtoken = $this->jumtoken($fldUsr);
                if ($jumtoken['jumlah'] >= 3) {
                    $pesan = ["Reset password dalam 1 hari dibatasi hanya 3 kali, coba besok lagi atau akses link yang telah dikirim ke email anda pada hari ini jika masih berlaku"];
                    goto next;
                }
                $tb = "reset_password";

                //update all user where user id
                /*
                $kond = array(
                    array("where", "iduser", $akun->id),
                );
                $dataSave = array(
                    'aktif' => "y",
                );
                $exupd = $this->Model_data->update($kond, $dataSave, $tb, null, true);
                */
                //debug($exupd);

                //generate token
                $token = rand(100, 999) . date("m") . date("H") . $akun->id . date("i") . date("Y") . date("d") . date("s");
                $linkreset = base_url('resetpass/' . $token);
                $created = date("Y-m-d H:i:s");
                $expired = date("Y-m-d H:i:s", strtotime("+30 minutes"));

                $dataSave = array(
                    "iduser" => $akun->id,
                    "owned" => $akun->id,
                    "token" => $token,
                    "created" => $created,
                    "expired" => $expired,
                );
                $retVal = $this->Model_data->save($dataSave, $tb, null, true);
                //debug($retVal);

                // kirim email
                $pesanemail = "<h3><b>Link Reset Berhasil</b></h3>
                <p>Klik link berikut untuk melakukan reset password " . $linkreset . ", link expired hinggal " . $expired . "</p>
                <i>Terima Kasih</i>
                ";
                $kirimemail = kirimEmail($fldUsr, "RESET PASSWORD AKUN", $pesanemail);
                //end kirim email

                if ($kirimemail)
                    $pesan = ["Link reset password berhasil terkirim dan expired hinggal " . $expired . ", lakukan perubahan password pada link yang dikirimken ke email " . $fldUsr];

                $retVal['status'] = true;
            } else {
                $pesan = ["Gagal, email " . $fldUsr . " tidak terdaftar pada website ini!"];
            }
        } else {
            $pesan = $this->form_validation->error_array();
        }
        next:
        $retVal['pesan'] = $pesan;
        die(json_encode($retVal));
    }

    public function simpan()
    {
        $retVal = array("status" => false, "pesan" => "", "login" => true);
        allowheader();

        $this->form_validation->set_rules('token', 'token', 'trim|required');
        $this->form_validation->set_rules('pass1', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('pass2', 'Ulangi password', 'required|matches[pass1]');

        if ($this->form_validation->run()) {
            $tokendt = $this->statustoken($this->input->post('token'));
            $dataSave = array(
                "fldpass" => password_hash($this->input->post('pass1', true), PASSWORD_DEFAULT),
            );
            $kond = array(
                array("where", "id", $tokendt['db']->id),
            );
            $retVal = $this->Model_data->update($kond, $dataSave, "user", null, true);
        } else {
            $retVal['pesan'] = $this->form_validation->error_array();
            $retVal['status'] = false;
        }
        die(json_encode($retVal));
    }
}
