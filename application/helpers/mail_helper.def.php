<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('kirimEmail')) {
    function kirimEmail($kepada, $judul, $pesan)
    {
        $retval = false;
        $srcmail = '';  //email
        $keyemail = ''; //password

        $kepada = strtolower($kepada);
        if (strcmp($kepada, "") != 0) {
            $CI = &get_instance();
            $CI->load->library('email');

            $list = array($kepada);

            $config = array(
                'protocol'  => 'smtp',
                'smtp_host' => 'ssl://smtp.gmail.com',
                'smtp_port' => 465,
                'smtp_user' => $srcmail,
                'smtp_pass' => $keyemail,
                'mailtype'  => 'html',
                'charset'   => 'utf-8',
                'wordwrap'  => TRUE,
            );
            $CI->email->initialize($config);
            $CI->email->set_mailtype('html');
            $CI->email->set_newline("\r\n");

            $CI->email->from($srcmail, 'PEMBERITAHUAN ' . strtoupper($CI->config->item('app_name')) . ' (' . $CI->config->item('kampus') . ') [NOREPLY]');
            $CI->email->to($list);
            $CI->email->subject($judul);
            $CI->email->message($pesan);
            if ($CI->email->send())
                $retval = true;
        }
        return $retval;
    }
}
