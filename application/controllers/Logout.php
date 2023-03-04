<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logout extends CI_Controller
{
    public function index()
    {
        $retVal = array("status" => true, "pesan" => "Logout berhasil dilakukan, Terima Kasih");
        $this->session->sess_destroy();
        $vUrl = base_url("login");
        redirect($vUrl);
    }
}
