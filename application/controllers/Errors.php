<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Errors extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        redirect("web");
    }

    public function notfound404()
    {
        $this->load->view("errors/notfound404");
    }
}
