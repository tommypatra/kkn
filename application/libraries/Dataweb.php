<?php

/**
 * Appweb
 * @package    CodeIgniter
 * @subpackage libraries
 * @category   library
 * @version    1.0 <beta>
 * @author     TommyPatra <tommyirawan.patra@gmail.com>
 * @link       
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dataweb
{
    private $CI;

    public function __construct()
    {
        $this->CI   = &get_instance();
        $this->vUrl = base_url();
    }

    public function cariTabel($vCari = [])
    {
        if (count($vCari) > 0)
            foreach ($vCari as $val => $cariSql) {
                $cond = isset($cariSql['cond']) ? $cariSql['cond'] : "where";
                $fld = isset($cariSql['fld']) ? $cariSql['fld'] : "id";
                $val = isset($cariSql['val']) ? $cariSql['val'] : null;

                if ($cond == "like")
                    $this->CI->db->like($fld, $val, "both");
                elseif ($cond == "where_in")
                    $this->CI->db->where_in($fld, $val);
                else
                    $this->CI->db->where($fld, $val);
            }
    }

    public function cariGrup($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("grup as g");
        $this->CI->db->select("g.*");
        $this->CI->db->order_by("g.nama_grup ASC");

        $this->cariTabel($vCari);

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }


    public function cariUser_new($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("user as u");
        $this->CI->db->select("u.*");
        $this->CI->db->order_by("u.id ASC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $this->cariTabel($vCari);

        $runsql = $this->CI->db->get();
        //echo $this->CI->db->last_query();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        //debug($retVal);
        return $retVal;
    }

    //cari akun atau cariakun bisa juga

    public function cariGrupUser_new($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("user as u");
        $this->CI->db->select("u.*,
                            h.id as idhakakses, h.iduser,h.idgrup,h.token,h.aktivasi,
                            p.id as idpembimbing, p.nip,p.statuspeg,
                            m.id as idmahasiswa, m.nim, m.idprodi,
                            ");
        $this->CI->db->join("hakakses as h", "h.iduser=u.id", "left");
        $this->CI->db->join("admin as a", "a.idhakakses=h.id", "left");
        $this->CI->db->join("mahasiswa as m", "m.idhakakses=h.id", "left");
        $this->CI->db->join("pembimbing as p", "p.idhakakses=h.id", "left");
        $this->CI->db->order_by("u.id ASC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $this->cariTabel($vCari);

        $runsql = $this->CI->db->get();
        //echo $this->CI->db->last_query();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        //debug($retVal);
        return $retVal;
    }

    public function cariGrupUser($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("user as u");
        $this->CI->db->select("u.*,
            IF(a.id IS NOT NULL,
                CONCAT('{\"status\":\"1\",\"idgrup\":\"',a.idgrup,'\",\"aktif\":\"',a.aktivasi,'\"}'),
                CONCAT('{\"status\":\"0\",\"idgrup\":\"0\",\"aktif\":\"n\"}')
                ) as is_admin,
            IF(m.id IS NOT NULL,
                CONCAT('{\"status\":\"1\",\"idgrup\":\"',m.idgrup,'\",\"aktif\":\"',m.aktivasi,'\"}'),
                CONCAT('{\"status\":\"0\",\"idgrup\":\"0\",\"aktif\":\"n\"}')
                ) as is_mahasiswa,
            IF(p.id IS NOT NULL,
                CONCAT('{\"status\":\"1\",\"idgrup\":\"',p.idgrup,'\",\"aktif\":\"',p.aktivasi,'\"}'),
                CONCAT('{\"status\":\"0\",\"idgrup\":\"0\",\"aktif\":\"n\"}')
                ) as is_pembimbing,
        ");
        $this->CI->db->join("admin as a", "a.iduser=u.id", "left");
        $this->CI->db->join("mahasiswa as m", "m.iduser=u.id", "left");
        $this->CI->db->join("pembimbing as p", "p.iduser=u.id", "left");
        $this->CI->db->order_by("u.id ASC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $this->cariTabel($vCari);

        $runsql = $this->CI->db->get();
        //echo $this->CI->db->last_query();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        //debug($retVal);
        return $retVal;
    }

    public function datajabatan($vCari = [], $offset = 0, $page_limit = 0, $order = "ASC")
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("mst_jabatan as j");
        $this->CI->db->select("j.*");

        $this->cariTabel($vCari);

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $this->CI->db->order_by("j.urut " . $order);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }


    public function datamahasiswa($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("user as u");
        $this->CI->db->select("
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
            u.nik, u.kel, u.path as pasfoto, u.hp, u.email, u.aktivasi,
            h.id as idhakakses,h.idgrup,
            m.id as idmahasiswa, m.nim, m.path as kartumahasiswa, prodi.id as idprodi, fak.id as idfakultas, prodi.prodi, fak.fakultas,
		");
        $this->CI->db->join("hakakses as h", "h.iduser=u.id", "left");
        $this->CI->db->join("mahasiswa as m", "m.idhakakses=h.id", "left");
        $this->CI->db->join("pendaftar as p", "p.idmahasiswa=m.id", "left");
        $this->CI->db->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->CI->db->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");

        $this->CI->db->where("h.aktivasi", "1");
        $this->CI->db->where("u.aktivasi", "y");
        $this->cariTabel($vCari);

        $this->CI->db->order_by("fak.id ASC, prodi.id ASC, m.nim ASC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function datamahasiswa_old($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("user as u");
        $this->CI->db->select("
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
            u.nik, u.kel, u.path as pasfoto, u.hp, u.email, u.aktivasi,
            m.id as idmahasiswa, m.nim, m.path as kartumahasiswa, prodi.id as idprodi, fak.id as idfakultas, prodi.prodi, fak.fakultas,
		");
        $this->CI->db->join("mahasiswa as m", "m.iduser=u.id", "left");
        $this->CI->db->join("pendaftar as p", "p.idmahasiswa=m.id", "left");
        $this->CI->db->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->CI->db->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");

        $this->CI->db->where("m.aktivasi", "y");
        $this->CI->db->where("u.aktivasi", "y");
        $this->cariTabel($vCari);

        $this->CI->db->order_by("fak.id ASC, prodi.id ASC, m.nim ASC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }


    public function datamodule($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("menu as m");
        $this->CI->db->select("m.*, CONCAT(m.urut,'. ',m.menu,' (mod=',m.module,', show=',m.show,', link=',m.link,')') as text");

        $this->cariTabel($vCari);

        $this->CI->db->order_by("m.idparent DESC, (m.urut + 0) ASC, m.menu ASC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function dataposko($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("kelompok as kl");
        $this->CI->db->select(" p.*,kl.namakelompok,k.tema,k.tahun,
                                wd.desa, kec.kecamatan, kab.kabupaten, prov.provinsi,
                                TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
        ");
        $this->CI->db->join("posko as p", "kl.id=p.idkelompok", "left");
        $this->CI->db->join("lokasi as l", "l.id=kl.idlokasi", "left");
        $this->CI->db->join("kkn as k", "k.id=l.idkkn", "left");


        $this->CI->db->join("pembimbing_kkn as pk", "pk.id=kl.idpembimbing_kkn", "left");
        $this->CI->db->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "left");
        $this->CI->db->join("pembimbing as pb", "pb.id=pk.idpembimbing", "left");
        $this->CI->db->join("hakakses as h", "h.id=pb.idhakakses", "left");
        $this->CI->db->join("user as u", "u.id=h.iduser", "left");

        $this->CI->db->join("wilayah_desa as wd", "wd.id=l.iddesa", "left");
        $this->CI->db->join("wilayah_kec as kec", "kec.id=wd.idkecamatan", "left");
        $this->CI->db->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->CI->db->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");

        $this->cariTabel($vCari);

        $this->CI->db->order_by("p.idkelompok ASC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function datapembimbing($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("user as u");
        $this->CI->db->select("
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
            u.nik, u.kel, u.path as pasfoto, u.hp, u.email, u.aktivasi,
            p.id as idpembimbing, p.nip, p.statuspeg,
            h.id as idhakakses, h.idgrup, h.aktivasi,
		");
        $this->CI->db->join("hakakses as h", "h.iduser=u.id", "left");
        $this->CI->db->join("pembimbing as p", "p.idhakakses=h.id", "left");

        //$this->CI->db->where("h.aktivasi", "1");
        $this->CI->db->where("u.aktivasi", "y");
        $this->cariTabel($vCari);

        $this->CI->db->order_by("p.nip ASC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function lastlogin($vCari = [], $offset = null, $page_limit = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("user as u");
        $this->CI->db->select("
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
            u.nik, u.kel, u.path as foto, u.hp, u.email,
            l.lastlogin
		");
        $this->CI->db->where("u.aktivasi", "y");
        $this->CI->db->where("l.lastlogin IS NOT NULL", null);
        $this->CI->db->join("(SELECT MAX(lastlogin) as lastlogin,iduser FROM login_history GROUP BY iduser) as l", "l.iduser=u.id", "left");

        $this->cariTabel($vCari);

        $this->CI->db->group_by("l.iduser");
        $this->CI->db->order_by("l.lastlogin DESC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function dataoutput($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("output as o");
        $this->CI->db->select(" o.*,op.id as idoutput_penempatan, op.path,op.fileinfo,op.jenis,mo.output,mo.urut,
                                pm.id as idpenempatan,k.id as idkkn, op.created as waktu_upload,
                                IF(CURDATE() BETWEEN k.tamulai AND k.taselesai,'terbuka','tertutup') as kettugas,
                            ");
        $this->CI->db->join("mst_output as mo", "mo.id=o.idoutput", "left");
        $this->CI->db->join("kkn as k", "k.id=o.idkkn", "left");
        $this->CI->db->join("pendaftar as p", "p.idkkn=k.id", "left");
        $this->CI->db->join("peserta as ps", "ps.idpendaftar=p.id", "left");
        $this->CI->db->join("penempatan as pm", "pm.idpeserta=ps.id", "left");
        $this->CI->db->join("output_penempatan as op", "op.idpenempatan=pm.id AND op.idoutput=o.id", "left");

        $this->cariTabel($vCari);
        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("mo.urut ASC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function dataglobal($vTabel = null, $vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from($vTabel);
        $this->CI->db->select("*");

        $this->cariTabel($vCari);
        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("id ASC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function dataprofil($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("profil as p");
        $this->CI->db->select(" p.*, j.jenis,
                        IF(p.iduser_update IS NULL, 
                            TRIM(CONCAT(ui.glrdepan,' ',ui.nama,' ',ui.glrbelakang)),
                            TRIM(CONCAT(uu.glrdepan,' ',uu.nama,' ',uu.glrbelakang))
                        ) as nama,
                        IF(p.iduser_update IS NULL, 
                            p.created,
                            p.update
                        ) as waktu,
        ");
        $this->CI->db->join("jenis_profil as j", "j.id=p.idjenis_profil", "left");
        $this->CI->db->join("user as ui", "ui.id=p.owned", "left");
        $this->CI->db->join("user as uu", "uu.id=p.iduser_update", "left");

        $this->cariTabel($vCari);
        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("p.id ASC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }


    public function pesertakkn($vCari = [], $offset = 0, $page_limit = 0, $ordery_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("peserta as ps");
        $this->CI->db->select("
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
            u.nik, u.kel, u.path as profilpic, u.hp, u.email, u.aktivasi, u.hp, u.tmplahir, u.tgllahir, u.kel, u.alamat,
            m.id as idmahasiswa, m.nim, m.path as kartumhspic, prodi.id as idprodi, fak.id as idfakultas, prodi.prodi, fak.fakultas,
            k.tahun,k.tema,k.angkatan,k.jenis,k.tempat, ps.id as idpeserta,
            k.kknmulai,k.kknselesai,
            p.id as idpendaftar, k.id as idkkn, jb.jabatan,jb.id as idjabatan,
            pm.idkelompok,pm.id as idpenempatan, IF(pm.id IS NULL,0,1) as statuspenempatan,
            kl.namakelompok,
            dsa.id as iddesa,dsa.desa,l.lastlogin,
            IF(DATE_ADD(NOW(), INTERVAL 8 HOUR)>=k.bagikelompok,'terbuka','tertutup') as ketpublishkelompok,
            IF(CURDATE() BETWEEN k.kknmulai AND k.kknselesai,'terbuka','tertutup') as ketkkn,
		");
        $this->CI->db->join("penempatan as pm", "pm.idpeserta=ps.id", "left");
        $this->CI->db->join("kelompok as kl", "kl.id=pm.idkelompok", "left");
        $this->CI->db->join("lokasi as l", "l.id=kl.idlokasi", "left");
        $this->CI->db->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->CI->db->join("mst_jabatan as jb", "pm.idjabatan=jb.id", "left");
        $this->CI->db->join("pendaftar as p", "p.id=ps.idpendaftar", "left");
        $this->CI->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->CI->db->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        $this->CI->db->join("user as u", "u.id=m.iduser", "left");
        $this->CI->db->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->CI->db->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");
        $this->CI->db->join("(SELECT MAX(lastlogin) as lastlogin,iduser FROM login_history GROUP BY iduser) as l", "l.iduser=u.id", "left");

        $this->cariTabel($vCari);

        if ($ordery_by)
            $this->CI->db->order_by($ordery_by);
        else
            $this->CI->db->order_by("k.jenis ASC,pm.idjabatan ASC, u.kel ASC, fak.id ASC, prodi.id ASC, m.nim ASC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function daftarpembimbing($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("pembimbing_kkn as pk");
        $this->CI->db->select("
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
            u.nik, u.kel, u.path as profilpic, u.hp, u.email, u.aktivasi, u.hp, u.tmplahir, u.tgllahir, u.kel, u.alamat,
            l.lastlogin,
            
		");
        $this->CI->db->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "left");
        $this->CI->db->join("pembimbing as p", "p.id=pk.idpembimbing", "left");
        $this->CI->db->join("hakakses as h", "h.id=p.idhakakses", "left");
        $this->CI->db->join("user as u", "u.id=h.iduser", "left");
        $this->CI->db->join("(SELECT MAX(lastlogin) as lastlogin,iduser FROM login_history GROUP BY iduser) as l", "l.iduser=u.id", "left");

        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("u.nama ASC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function status_peserta($tahun = null, $idpendaftar = null, $iduser = null)
    {
        $retVal = array("status" => false, "pesan" => [""]);
        if (!$tahun)
            $tahun = date("Y");
        if (!$iduser)
            $iduser = $this->CI->session->userdata('iduser');

        $this->CI->db->from("peserta as ps");
        $this->CI->db->select("ps.id as idpeserta, p.id as idpendaftar, k.tema, k.tahun, u.nama, m.nim, m.id as idmhs, u.id as iduser");
        $this->CI->db->join("pendaftar as p", "p.id=ps.idpendaftar", "left");
        $this->CI->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->CI->db->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        $this->CI->db->join("user as u", "u.id=m.iduser", "left");
        $this->CI->db->where("u.id", $iduser);
        $this->CI->db->where("p.id IS NOT NULL", null);
        $this->CI->db->where("k.tahun", $tahun);
        if ($idpendaftar) {
            $this->CI->db->where("p.id", $idpendaftar);
        }

        $sql = $this->CI->db->get();


        if ($sql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => ["data ditemukan"], "db" => $sql->result_array());
        }
        return $retVal;
    }

    public function cek_peserta($tahun = null, $idpendaftar = null, $iduser = null)
    {
        $retVal = array("status" => false, "pesan" => [""]);
        if (!$tahun)
            $tahun = date("Y");
        if (!$iduser)
            $iduser = $this->CI->session->userdata('iduser');

        $this->CI->db->from("pendaftar as p");
        $this->CI->db->select("ps.id as idpeserta, p.id as idpendaftar, k.tema, k.tahun, u.nama, m.nim, m.id as idmhs, u.id as iduser");
        $this->CI->db->join("peserta as ps", "p.id=ps.idpendaftar", "left");
        $this->CI->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->CI->db->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        $this->CI->db->join("user as u", "u.id=m.iduser", "left");
        $this->CI->db->where("u.id", $iduser);
        $this->CI->db->where("p.id IS NOT NULL", null);
        $this->CI->db->where("k.tahun", $tahun);
        if ($idpendaftar) {
            $this->CI->db->where("p.id!=" . $idpendaftar, null);
        }

        $sql = $this->CI->db->get();
        //echo $this->CI->db->last_query();


        if ($sql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => ["data ditemukan"], "db" => $sql->result_array());
        }
        //debug($retVal);
        return $retVal;
    }

    public function daftaraktifitas($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("aktifitas as a");
        $this->CI->db->select("
            a.id as idaktifitas,a.uraian,a.waktu,a.idpenempatan,a.latitude,a.longitude,a.jummhs,a.jummasyarakat,a.grup,a.estbiaya,
            k.id as idkkn, k.tema, k.tahun,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
            u.nik, u.kel, u.path as profilpic, u.hp, u.email, u.aktivasi, u.hp, u.tmplahir, u.tgllahir, u.kel, u.alamat,
        ");
        $this->CI->db->join("penempatan as pm", "pm.id=a.idpenempatan", "left");
        /*
        $this->CI->db->join("kelompok as kl", "kl.id=pm.idkelompok", "left");
        $this->CI->db->join("lokasi as l", "l.id=kl.idlokasi", "left");
        $this->CI->db->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->CI->db->join("mst_jabatan as jb", "pm.idjabatan=jb.id", "left");
        */
        $this->CI->db->join("peserta as ps", "ps.id=pm.idpeserta", "left");
        $this->CI->db->join("pendaftar as p", "p.id=ps.idpendaftar", "left");
        $this->CI->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->CI->db->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        $this->CI->db->join("user as u", "u.id=m.iduser", "left");
        /*
        $this->CI->db->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->CI->db->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");
        $this->CI->db->join("(SELECT MAX(lastlogin) as lastlogin,iduser FROM login_history GROUP BY iduser) as l", "l.iduser=u.id", "left");
        */
        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("a.waktu DESC, u.nama ASC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function rekapaktifitas($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("aktifitas as a");
        $this->CI->db->select("
            SUM(IF(a.grup='FISIK',1,0)) as kegfisik,
            SUM(IF(a.grup='NON FISIK',1,0)) as kegnonfisik,
            COUNT(a.id) as kegtotal,
            SUM(IF(a.grup='FISIK',a.estbiaya,0)) as estbiayafisik,
            SUM(IF(a.grup='NON FISIK',a.estbiaya,0)) as estbiayanonfisik,
            SUM(a.estbiaya) as esttotal,
        ");
        $this->CI->db->join("penempatan as pm", "pm.id=a.idpenempatan", "left");
        $this->CI->db->join("peserta as ps", "ps.id=pm.idpeserta", "left");
        $this->CI->db->join("pendaftar as p", "p.id=ps.idpendaftar", "left");
        $this->CI->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->CI->db->group_by("k.id", "left");
        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("k.tahun DESC, k.jenis DESC, k.tema ASC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function aktifitas_dpl($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("aktifitas_dpl as dpl");
        $this->CI->db->select("dpl.*");
        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("dpl.created DESC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }
    public function notifikasi($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("notifikasi as n");
        $this->CI->db->select("n.*,
                                TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path as profilpic,
        ");
        $this->CI->db->join("user as u", "u.id=n.iduser_asal", "left");
        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("n.created DESC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function kelompok_terkaktif($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("aktifitas as a");
        $this->CI->db->select("
            COUNT(a.id) as jumpopuler,  
            kl.namakelompok, k.tema, k.tahun, kl.id as idkelompok,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
            wd.desa, kec.kecamatan, kab.kabupaten, prov.provinsi,
            IF(DATE_ADD(NOW(), INTERVAL 8 HOUR)>=k.bagikelompok,'terbuka','tertutup') as ketpublishkelompok,
        ");
        $this->CI->db->join("penempatan as pm", "pm.id=a.idpenempatan", "left");
        $this->CI->db->join("kelompok as kl", "kl.id=pm.idkelompok", "left");
        $this->CI->db->join("peserta as ps", "pm.idpeserta=ps.id", "left");
        $this->CI->db->join("pendaftar as p", "p.id=ps.idpendaftar", "left");
        $this->CI->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->CI->db->join("pembimbing_kkn as pk", "pk.id=kl.idpembimbing_kkn", "left");
        $this->CI->db->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "left");
        $this->CI->db->join("pembimbing as pb", "pb.id=pk.idpembimbing", "left");
        $this->CI->db->join("hakakses as h", "h.id=pb.idhakakses", "left");
        $this->CI->db->join("user as u", "u.id=h.iduser", "left");

        $this->CI->db->join("lokasi as l", "l.id=kl.idlokasi", "left");
        $this->CI->db->join("wilayah_desa as wd", "wd.id=l.iddesa", "left");
        $this->CI->db->join("wilayah_kec as kec", "kec.id=wd.idkecamatan", "left");
        $this->CI->db->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->CI->db->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");


        $this->CI->db->group_by("kl.id", "left");
        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("jumpopuler DESC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function daftarberita($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("berita as b");
        $this->CI->db->select("
            b.id as idberita, b.judul, b.slug, b.detail,b.waktu, b.thumbnail,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path as profilpic,
		");
        $this->CI->db->join("user as u", "u.id=b.iduser", "left");
        $this->CI->db->order_by("b.waktu DESC");

        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("b.waktu DESC, b.judul, b.slug");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function daftarupload($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("upload as f");
        $this->CI->db->select("
            f.id as idupload, f.judul, f.keterangan, f.waktu, f.fileinfo, f.path, f.is_image,f.publish,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.hp, u.nik, u.kel, u.path as profilpic,
        ");
        $this->CI->db->join("user as u", "u.id=f.owned", "left");

        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("f.waktu DESC, f.id DESC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function  lampiranberita($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("berita_upload as f");
        $this->CI->db->select("
            f.id as idupload,f.path,f.fileinfo,f.is_image,f.created,
        ");
        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("f.idaktifitas DESC, f.is_image, f.id DESC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function daftarlampiran($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("aktifitas_upload as f");
        $this->CI->db->select("
            f.id as idupload,f.path,f.fileinfo,f.is_image,
            a.id as idaktifitas,a.uraian,a.waktu,a.idpenempatan,a.latitude,a.longitude,a.jummhs,a.jummasyarakat,a.grup,a.estbiaya,
        ");
        $this->CI->db->join("aktifitas as a", "f.idaktifitas=a.id", "left");
        $this->CI->db->join("penempatan as pm", "pm.id=a.idpenempatan", "left");
        /*
        $this->CI->db->join("kelompok as kl", "kl.id=pm.idkelompok", "left");
        $this->CI->db->join("lokasi as l", "l.id=kl.idlokasi", "left");
        $this->CI->db->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->CI->db->join("mst_jabatan as jb", "pm.idjabatan=jb.id", "left");
        $this->CI->db->join("peserta as ps", "ps.id=pm.idpeserta", "left");
        $this->CI->db->join("pendaftar as p", "p.id=ps.idpendaftar", "left");
        $this->CI->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->CI->db->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        $this->CI->db->join("user as u", "u.id=m.iduser", "left");
        $this->CI->db->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->CI->db->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");
        $this->CI->db->join("(SELECT MAX(lastlogin) as lastlogin,iduser FROM login_history GROUP BY iduser) as l", "l.iduser=u.id", "left");
        */
        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("f.idaktifitas DESC, f.is_image, f.id DESC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function daftarkomentar($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("aktifitas_komentar as k");
        $this->CI->db->select("
            k.id as idkomentaraktifitas, k.idaktifitas, k.komentar,k.waktu,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
            u.nik, u.kel, u.path as profilpic, u.hp, u.email, u.aktivasi, u.hp, u.tmplahir, u.tgllahir, u.kel, u.alamat,
        ");
        $this->CI->db->join("user as u", "u.id=k.iduser", "left");

        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("k.waktu DESC,k.id DESC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function daftarlike($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("aktifitas_like as l");
        $this->CI->db->select("
            l.id as idlike, l.idaktifitas,l.iduser,l.created,
        ");
        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("l.created DESC,l.id DESC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function daftarkelompok($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("kelompok as k");
        $this->CI->db->select("
            k.id as idkelompok, sp.idkkn, pm.id as idpenempatan, COUNT(pm.id) as jumlah,
            k.namakelompok, wd.id as iddesa, 
            wd.desa, kec.kecamatan, kab.kabupaten, prov.provinsi,
            u.path as profilpic,
            u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, u.email,
            l.lastlogin,
            po.id as idposko, po.latitude, po.longitude, po.alamat, po.path as fotoposko, po.fileinfo, po.proker,
        ");
        $this->CI->db->join("posko as po", "k.id=po.idkelompok", "left");
        $this->CI->db->join("pembimbing_kkn as pk", "pk.id=k.idpembimbing_kkn", "left");
        $this->CI->db->join("sk_pembimbing as sp", "sp.id=pk.idsk_pembimbing", "left");

        $this->CI->db->join("penempatan as pm", "pm.idkelompok=k.id", "left");

        $this->CI->db->join("lokasi as l", "l.id=k.idlokasi", "left");
        $this->CI->db->join("wilayah_desa as wd", "wd.id=l.iddesa", "left");
        $this->CI->db->join("wilayah_kec as kec", "kec.id=wd.idkecamatan", "left");
        $this->CI->db->join("wilayah_kab as kab", "kab.id=kec.idkabupaten", "left");
        $this->CI->db->join("wilayah_prov as prov", "prov.id=kab.idprovinsi", "left");


        $this->CI->db->join("pembimbing as p", "p.id=pk.idpembimbing", "left");
        $this->CI->db->join("hakakses as h", "h.id=p.idhakakses", "left");
        $this->CI->db->join("user as u", "u.id=h.iduser", "left");
        $this->CI->db->join("(SELECT MAX(lastlogin) as lastlogin,iduser FROM login_history GROUP BY iduser) as l", "l.iduser=u.id", "left");
        $this->CI->db->group_by("k.id");

        $this->cariTabel($vCari);

        $this->CI->db->order_by("CAST(k.namakelompok AS UNSIGNED), k.namakelompok ASC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function dataprodi($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("mst_prodi as p");
        $this->CI->db->select("
            p.id as idprodi, p.prodi, 
            fak.id as idfakultas, fak.fakultas,
		");
        $this->CI->db->join("mst_fakultas as fak", "fak.id=p.idfakultas", "left");

        $this->cariTabel($vCari);

        $this->CI->db->order_by("fak.id ASC, p.id ASC");
        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function cariKkn($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("kkn as k");
        $this->CI->db->select("k.*,
                k.id as idkkn,
                CONCAT(DATE_FORMAT(k.daftarmulai, \"%d-%m-%Y\"),' s/d ',DATE_FORMAT(k.daftarselesai, \"%d-%m-%Y\")) as waktudaftar,
                CONCAT(DATE_FORMAT(k.kknmulai, \"%d-%m-%Y\"),' s/d ',DATE_FORMAT(k.kknselesai, \"%d-%m-%Y\")) as waktukkn,
                CONCAT(DATE_FORMAT(k.tamulai, \"%d-%m-%Y\"),' s/d ',DATE_FORMAT(k.taselesai, \"%d-%m-%Y\")) as waktutugas,
                CONCAT(DATE_FORMAT(k.nilaimulai, \"%d-%m-%Y\"),' s/d ',DATE_FORMAT(k.nilaiselesai, \"%d-%m-%Y\")) as waktunilai,
                DATE_FORMAT(k.bagikelompok, \"%d-%m-%Y\") as waktubagikelompok,

                IF(CURDATE() BETWEEN k.daftarmulai AND k.daftarselesai,'terbuka','tertutup') as ketdaftar,
                IF(CURDATE() BETWEEN k.kknmulai AND k.kknselesai,'terbuka','tertutup') as ketkkn,
                IF(CURDATE() BETWEEN k.tamulai AND k.taselesai,'terbuka','tertutup') as kettugas,
                IF(CURDATE() BETWEEN k.nilaimulai AND k.nilaiselesai,'terbuka','tertutup') as ketnilai,
                IF(DATE_ADD(NOW(), INTERVAL 8 HOUR)>=k.bagikelompok,'terbuka','tertutup') as ketpublishkelompok,
                COUNT(v.id) as jumlahvalidasi,
        ");
        $this->CI->db->join("pendaftar as p", "p.idkkn=k.id", "left");
        $this->CI->db->join("peserta as v", "v.idpendaftar=p.id", "left");
        $this->CI->db->order_by("k.tahun DESC", "k.kknmulai DESC", "k.tema");
        $this->CI->db->group_by("k.id");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $this->cariTabel($vCari);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function loadwilayahktp($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("wilayah_prov as prov");
        $this->CI->db->select("
                        prov.id as idprovinsi, prov.provinsi, prov.kode as kodewilayah_prov,
                        kab.id as idkabupaten, kab.kabupaten, kab.kode as kodewilayah_kab,
                        kec.id as idkecamatan, kec.kecamatan, kec.kode as kodewilayah_kec,
                        d.id as iddesa, d.desa, d.kode as kodewilayah_desa,
                        ");
        $this->CI->db->join("wilayah_kab as kab", "kab.idprovinsi=prov.id", "left");
        $this->CI->db->join("wilayah_kec as kec", "kec.idkabupaten=kab.id", "left");
        $this->CI->db->join("wilayah_desa as d", "d.idkecamatan=kec.id", "left");
        $this->CI->db->order_by("prov.kode ASC, kab.kode ASC, kec.kode ASC, d.kode ASC");

        $this->cariTabel($vCari);

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function loadprovinsi($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("wilayah_prov as p");
        $this->CI->db->select("p.*");
        $this->CI->db->order_by("p.kode ASC, p.provinsi ASC");

        $this->cariTabel($vCari);

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function loadkabupaten($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("wilayah_kab as k");
        $this->CI->db->select("k.*");
        $this->CI->db->order_by("k.kodewilayah_prov ASC, k.kode ASC");

        $this->cariTabel($vCari);

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function loadkecamatan($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("wilayah_kec as c");
        $this->CI->db->select("c.*");
        $this->CI->db->order_by("c.kodewilayah_prov ASC, c.kodewilayah_kab ASC, k.kode ASC");

        $this->cariTabel($vCari);

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function loaddesa($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);
        $this->CI->db->from("wilayah_desa as d");
        $this->CI->db->select("d.*");
        $this->CI->db->order_by("d.kodewilayah_prov ASC, d.kodewilayah_kab ASC, d.kodewilayah_kec ASC, d.kode ASC");

        $this->cariTabel($vCari);

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }

    public function loadprofil($vCari = [], $offset = 0, $page_limit = 0)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("user as u");
        $this->CI->db->select("
                u.*,
                u.id as iduser,
                dsa.id as iddesa,dsa.desa,
                kec.id as idkecamatan,kec.kecamatan,
                kab.id as idkabupaten,kab.kabupaten,
                prov.id as idprovinsi,prov.provinsi,
                h.id as idhakakses,h.idgrup,h.token,h.aktivasi,
		");
        $this->CI->db->join("hakakses as h", "h.iduser=u.id", "left");
        $this->CI->db->join("wilayah_desa as dsa", "dsa.id=u.iddesa", "left");
        $this->CI->db->join("wilayah_kec as kec", "kec.id=u.idkecamatan", "left");
        $this->CI->db->join("wilayah_kab as kab", "kab.id=u.idkabupaten", "left");
        $this->CI->db->join("wilayah_prov as prov", "prov.id=u.idprovinsi", "left");
        $this->CI->db->order_by("prov.provinsi ASC, kab.kabupaten ASC, kec.kecamatan ASC, dsa.desa ASC");
        $this->CI->db->group_by("u.id");

        $this->cariTabel($vCari);

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $runsql = $this->CI->db->get();
        if ($runsql->num_rows() > 0) {
            $retVal = array("status" => true, "pesan" => "data ditemukan", "db" => $runsql->result_array());
        }
        return $retVal;
    }


    public function aktifitas_trending($vCari = [], $kategori = "trending", $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("aktifitas as a");
        $this->CI->db->select("
                a.id as idaktifitas, a.idpenempatan,a.latitude, a.longitude,a.uraian,a.waktu,a.jummhs,a.jummasyarakat,a.grup,a.estbiaya,
                kl.id as idkelompok, kl.namakelompok, jb.jabatan,
                u.id as iduser, TRIM(CONCAT(u.glrdepan,' ',u.nama,' ',u.glrbelakang)) as nama, 
                u.nik, u.kel, u.path as profilpic, u.hp, u.email, u.aktivasi, u.hp, u.tmplahir, u.tgllahir, u.kel, u.alamat,
                prodi.prodi,fak.fakultas,
                dsa.desa, 
                IF(DATE_ADD(NOW(), INTERVAL 8 HOUR)>=k.bagikelompok,'terbuka','tertutup') as ketpublishkelompok,
                k.id as idkkn,k.tema,k.tahun,k.jenis,
                COUNT(pop.id) as jumpopuler,
            ");

        $this->cariTabel($vCari);

        $this->CI->db->where("pop.id IS NOT NULL", null);

        $this->CI->db->join("penempatan as pm", "pm.id=a.idpenempatan", "left");
        $this->CI->db->join("peserta as ps", "pm.idpeserta=ps.id", "left");
        $this->CI->db->join("kelompok as kl", "kl.id=pm.idkelompok", "left");
        $this->CI->db->join("lokasi as l", "l.id=kl.idlokasi", "left");
        $this->CI->db->join("wilayah_desa as dsa", "dsa.id=l.iddesa", "left");
        $this->CI->db->join("mst_jabatan as jb", "pm.idjabatan=jb.id", "left");
        $this->CI->db->join("pendaftar as p", "p.id=ps.idpendaftar", "left");
        $this->CI->db->join("kkn as k", "k.id=p.idkkn", "left");
        $this->CI->db->join("mahasiswa as m", "m.id=p.idmahasiswa", "left");
        $this->CI->db->join("user as u", "u.id=m.iduser", "left");
        $this->CI->db->join("mst_prodi as prodi", "m.idprodi=prodi.id", "left");
        $this->CI->db->join("mst_fakultas as fak", "fak.id=prodi.idfakultas", "left");
        if ($kategori == "trending")
            $this->CI->db->join("aktifitas_komentar as pop", "pop.idaktifitas=a.id", "left");
        else
            $this->CI->db->join("aktifitas_like as pop", "pop.idaktifitas=a.id", "left");
        $this->CI->db->group_by("a.id");

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("jumpopuler DESC");

        $this->CI->db->order_by("a.created DESC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);

        $data = $this->CI->db->get();

        if ($data->num_rows() > 0) {
            $retVal['db'] = $data->result_array();
            $retVal['status'] = true;
            $retVal['pesan'] = "data ditemukan";
        }
        return $retVal;
    }


    public function daftaraktifitas_dpl($vCari = [], $offset = 0, $page_limit = 0, $order_by = null)
    {
        $retVal = array("status" => false, "pesan" => "tidak ditemukan", "db" => []);

        $this->CI->db->from("aktifitas_dpl as adp");
        $this->CI->db->select("adp.*");
        $this->cariTabel($vCari);

        if ($order_by)
            $this->CI->db->order_by($order_by);
        else
            $this->CI->db->order_by("adp.waktu DESC");

        if ($page_limit > 0)
            $this->CI->db->limit($page_limit, $offset);
        $data = $this->CI->db->get();

        if ($data->num_rows() > 0) {
            $retVal['db'] = $data->result_array();
            $retVal['status'] = true;
            $retVal['pesan'] = "data ditemukan";
        }
        return $retVal;
    }
}

/* End of file Dataweb.php */
/* Location: ./application/libraries/Dataweb.php */