<?php
defined('BASEPATH') or exit('No direct script access allowed');

class dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('parser');
    }

    public function index()
    {
        $data = array();
        $data['user_name'] = 'admin';
        $data['separator'] = '|';
        $data['base_url'] = base_url();
        $data['url_templates'] = base_url('templates/mazer/');
        $data['profile_title'] = 'Profile view';
        $data['home_url'] = 'www.yourdomain.com/';
        $data['home_header_text'] = 'Home Page';


        // After all data has been found, parse
        $this->parser->parse('dashboard', $data);
    }
}
