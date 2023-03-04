<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembagian extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }


    public function kelompok($idkkn = null, $jumkel = null)
    {
        $this->load->library("dataweb");

        $vCari = array(
            array("cond" => "where", "fld" => "k.id", "val" => $idkkn),
            array("cond" => "where", "fld" => "pm.id IS NULL", "val" => null),
        );

        $pesertakkn = $this->dataweb->pesertakkn($vCari, 0, 0, "u.kel ASC, fak.id ASC, prodi.id ASC");

        $simulai_kelompok = [];
        if ($pesertakkn['status']) {
            $kelompok = 1;
            $indexanggota = 1;
            foreach ($pesertakkn['db'] as $i => $dp) {
                $simulai_kelompok[$kelompok][] = $dp;
                $indexanggota++;
                $kelompok++;
                if ($indexanggota > $jumkel) {
                    $kelompok = 1;
                    $indexanggota = 1;
                }
            }
        }

        //debug($simulai_kelompok);
        foreach ($simulai_kelompok as $i => $kel) {
            echo "Kelompok-" . $i . "<br>";
            foreach ($kel as $j => $dp) {
                echo ($j + 1) . ". " . $dp['nama'] . " (" . $dp['kel'] . ") " . $dp['prodi'] . "<br>";
            }
        }
    }
}
