<?php

/**
 * Select2
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

class Select2
{
	private $ci;
	public $vData = [];
	public $vType = "post";
	public $vDataType = "json";
	public $vUrl = "";
	public $timeout = 8;
	public $vDelay = 3;
	public $vlimit_rows = 10;
	public $min = 3;
	public $vFldSearch = array();
	public $vRef = array();
	public $multi = false;
	public $defVar = null;
	public $placeholder = "cari...";

	public function __construct()
	{
		$this->ci	= &get_instance();
		$this->vUrl	= base_url();

		$this->timeout = $this->ci->config->item('ajax_timeout') / 1000;
		$this->vDelay = $this->ci->config->item('delay');
		$this->vlimit_rows = $this->ci->config->item('limit_rows');
	}

	public function loadEmpty($el = "")
	{
		echo "\n";
		echo "//fungsi load basic select2 " . $el;
		echo "\n";
		if ($this->defVar)
			echo "var " . $this->defVar . "=";
		echo "$('" . $el . "').select2({
					placeholder: '" . $this->placeholder . "',";
		if ($this->multi)
			echo "multiple: true,";
		echo "	allowClear: true,
						dropdownAutoWidth: true,
				  });";
	}

	public function loadLokal($el = "", $data = [])
	{

		if (count($data) > 0)
			$this->vData = $data;

		if (count($this->vData) > 0) {
			$seldata = json_encode($this->vData);
			echo "\n";
			echo "//fungsi loadlokal dari select2";
			echo "\n";
			if ($this->defVar)
				echo "var " . $this->defVar . "=";
			echo "$('" . $el . "').select2({
					data: " . $seldata . ",
					dropdownAutoWidth: true,
					placeholder: '" . $this->placeholder . "',";
			if ($this->multi)
				echo "multiple: true,";
			echo "
					allowClear: true,
					query: function(q) {
						var pageSize,
							results,
							that = this;
						pageSize = " . $this->vlimit_rows . ";
						results = [];
						if (q.term && q.term !== '') {
						// HEADS UP; for the _.filter function i use underscore (actually lo-dash) here
						results = _.filter(that.data, function(e) {
							return e.text.toUpperCase().indexOf(q.term.toUpperCase()) >= 0;
						});
						} else if (q.term === '') {
						results = that.data;
						}
						q.callback({
						results: results.slice((q.page - 1) * pageSize, q.page * pageSize),
						more: results.length >= q.page * pageSize,
						});
					},
				});";
		}
	}

	public function addData($data = [])
	{
		$retVal = "";
		$vJum = count($data);
		$i = 1;
		if ($vJum > 0)
			foreach ($data as $val) {
				$retVal = $retVal . $val[0] . ": " . $val[1] . ",";
				if ($i < $vJum)
					$retVal = $retVal . "\n";
			}
		return $retVal;
	}

	public function loadServer($el = "")
	{
		echo "\n";
		echo "//fungsi loadserver dari select2";
		echo "\n";

		$tmpsearch = "";
		$x = 0;
		if (count($this->vFldSearch) > 0) {
			foreach ($this->vFldSearch as $tmpid => $tmpval) {
				$src = isset($tmpval['src']) ? $tmpval['src'] : "params.term";
				$fld = isset($tmpval['fld']) ? $tmpval['fld'] : "id";
				$cond = isset($tmpval['cond']) ? $tmpval['cond'] : "like";

				$tmpsearch = $tmpsearch . $x . ":{cond:'" . $cond . "',val:" . $src . ",fld:'" . $fld . "'}";
				//$tmpsearch=$tmpsearch.$tmpid.":{cond:'like',val:params.term,fld:'".$tmpval."'}";
				$x++;
				if ($x < count($this->vFldSearch))
					$tmpsearch = $tmpsearch . ",";
			}
		}

		if ($this->defVar)
			echo "var " . $this->defVar . "=";
		echo "$('" . $el . "').select2({
						minimumInputLength: " . $this->min . ",
						dropdownAutoWidth: true,
						delay: " . $this->vDelay . ",
						cache: true,
						allowClear: true,";
		if ($this->multi)
			echo "multiple: true,";
		echo "		
						placeholder: '" . $this->placeholder . "',
						ajax: {
							dataType: '" . $this->vDataType . "',
							url: '" . $this->vUrl . "',
							timeout: " . ($this->timeout * 1000) . ",
							type:'" . $this->vType . "',
							data: function(params) {
								return {
									" . $this->addData($this->vData) . "
									token_spip: $('#csrf').val(),
									vCari: {" . $tmpsearch . "},
									limit_rows: " . $this->vlimit_rows . ",
									page: params.page || 1,				
								}
							},			
							success:function(vRet){
								$('#csrf').val(vRet.csrf_value);
							},
							processResults: function (data,params) {
								params.page = params.page || 1;
								return {
									results: data.listdata,
									";
		if ($this->vlimit_rows > 0)
			echo "			pagination: {
										more: (params.page * " . $this->vlimit_rows . ") < data.total_count
									}";
		echo "
								};
							},				
						},
					});\n";
	}

	/*
		var array = ['3', '1'];
		$('#your select id').val(array).trigger("change");		
		*/

	public function tahun($el = "", $jum = 7)
	{
		$thn = (int)date("Y");
		$data = array();
		for ($i = $thn; $i >= ($thn - $jum); $i--) {
			$data[] = array("id" => $i, "text" => $i);
		}
		$this->loadLokal($el, $data);
		return $data;
	}

	public function kategorijadwal($el = "")
	{
		$data = array(
			array("id" => "PENELITIAN", "text" => "PENELITIAN"),
			array("id" => "PENGABDIAN", "text" => "PENGABDIAN"),
		);
		$this->vData = $data;
		$this->loadLokal($el);
		return $data;
	}


	public function statusaktif($el = "")
	{
		$data = array(
			array("id" => "YA", "text" => "YA"),
			array("id" => "TIDAK", "text" => "TIDAK"),
		);
		$this->vData = $data;
		$this->loadLokal($el);
		return $data;
	}

	public function loadakses($el = "")
	{
		$data = array(
			array("id" => "y", "text" => "YA"),
			array("id" => "n", "text" => "TIDAK"),
		);
		$this->vData = $data;
		$this->loadLokal($el);
		return $data;
	}

	public function listinstitusi($el = "")
	{
		$data = array(
			array("id" => "", "text" => ""),
			array("id" => "INTERNAL", "text" => "INTERNAL"),
			array("id" => "EKSTERNAL", "text" => "EKSTERNAL"),
		);
		$this->vData = $data;
		$this->loadLokal($el);
		return $data;
	}

	public function listpengaturanjadwal($el = "")
	{
		$data = array(
			array("id" => "TIM PENELITI", "text" => "TIM PENELITI"),
			array("id" => "MAKS DANA", "text" => "MAKS DANA"),
		);
		$this->vData = $data;
		$this->loadLokal($el);
		return $data;
	}

	public function jeniskelamin($el = "")
	{
		$data = array(
			array("id" => "L", "text" => "LAKI-LAKI"),
			array("id" => "P", "text" => "PEREMPUAN"),
		);
		$this->vData = $data;
		$this->loadLokal($el);
		return $data;
	}

	public function listsebagai($el = "", $admin = false)
	{
		$data = array(
			array("id" => "", "text" => ""),
			array("id" => "DOSEN", "text" => "DOSEN"),
			array("id" => "MAHASISWA", "text" => "MAHASISWA"),
		);
		if ($admin)
			$data[] = array("id" => "ADM", "text" => "ADM");
		$this->vData = $data;
		$this->loadLokal($el);
		return $data;
	}

	public function listgruppenilaian($el = "")
	{
		$data = array(
			array("id" => "SUBSTANTIF", "text" => "SUBSTANTIF"),
			array("id" => "SEMINAR", "text" => "SEMINAR"),
		);
		$this->vData = $data;
		$this->loadLokal($el);
		return $data;
	}

	public function grupAkun($el = "", $reg = null)
	{
		$query = "SELECT * FROM grup";
		if ($reg)
			$query .= "WHERE reg='" . $reg . "'";
		$query .= "ORDER BY nama_grup ASC";

		$execSql = $this->ci->Model_data->runQuery($query);
		$data = array();
		if ($execSql['db']->num_rows() > 0)
			foreach ($execSql['db']->result_array() as $dp) {
				$data[] = array("id" => $dp['id'], "text" => strip_tags($dp['nama_grup']));
			}
		$this->loadLokal($el, $data);
		return $data;
	}

	public function listUser($el = "")
	{
		$execSql = $this->ci->Model_data->runQuery("SELECT * FROM user ORDER BY nama ASC");
		$data = array();
		if ($execSql['db']->num_rows() > 0)
			foreach ($execSql['db']->result_array() as $dp) {
				$data[] = array("id" => $dp['id'], "text" => strip_tags($dp['nama']));
			}
		$this->loadLokal($el, $data);
		return $data;
	}

	public function bidangilmu($el = "")
	{
		$execSql = $this->ci->Model_data->runQuery("SELECT * FROM mst_bidang_ilmu ORDER BY bidang ASC");
		//$data[]=array("id"=>"","text"=>"pilih");
		if ($execSql['db']->num_rows() > 0)
			foreach ($execSql['db']->result_array() as $dp) {
				$data[] = array("id" => $dp['id'], "text" => strip_tags($dp['bidang']));
			}
		$this->loadLokal($el, $data);
		return $data;
	}

	public function klaster($el = "")
	{
		$execSql = $this->ci->Model_data->runQuery("SELECT * FROM mst_klaster ORDER BY klaster ASC");
		$data[] = array("id" => "", "text" => "pilih");
		if ($execSql['db']->num_rows() > 0)
			foreach ($execSql['db']->result_array() as $dp) {
				$data[] = array("id" => $dp['id'], "text" => strip_tags($dp['klaster']));
			}
		$this->loadLokal($el, $data);
		return $data;
	}


	public function jenispenelitian($el = "")
	{
		$execSql = $this->ci->Model_data->runQuery("SELECT * FROM mst_jenis ORDER BY jenis ASC");
		//$data[]=array("id"=>"","text"=>"pilih");
		if ($execSql['db']->num_rows() > 0)
			foreach ($execSql['db']->result_array() as $dp) {
				$data[] = array("id" => $dp['id'], "text" => strip_tags($dp['jenis']));
			}
		$this->loadLokal($el, $data);
		return $data;
	}

	public function arkan($el = "")
	{
		$execSql = $this->ci->Model_data->runQuery("SELECT * FROM mst_arkan ORDER BY arkan ASC");
		//$data[]=array("id"=>"","text"=>"pilih");
		if ($execSql['db']->num_rows() > 0)
			foreach ($execSql['db']->result_array() as $dp) {
				$data[] = array("id" => $dp['id'], "text" => strip_tags($dp['arkan']));
			}
		$this->loadLokal($el, $data);
		return $data;
	}

	public function aspek($el = "")
	{
		$execSql = $this->ci->Model_data->runQuery("SELECT * FROM mst_aspek ORDER BY aspek ASC");
		//$data[]=array("id"=>"","text"=>"pilih");
		if ($execSql['db']->num_rows() > 0)
			foreach ($execSql['db']->result_array() as $dp) {
				$data[] = array("id" => $dp['id'], "text" => strip_tags($dp['aspek']));
			}
		$this->loadLokal($el, $data);
		return $data;
	}

	public function luaran($el = "")
	{
		$execSql = $this->ci->Model_data->runQuery("SELECT * FROM mst_luaran ORDER BY luaran ASC");
		$data[] = array("id" => "", "text" => "pilih");
		if ($execSql['db']->num_rows() > 0)
			foreach ($execSql['db']->result_array() as $dp) {
				$data[] = array("id" => $dp['id'], "text" => strip_tags($dp['luaran']));
			}
		$this->loadLokal($el, $data);
		return $data;
	}

	public function listmenu($el = "")
	{
		$execSql = $this->ci->Model_data->runQuery("SELECT m.* FROM menu as m WHERE (m.show='y' OR m.link='#') ORDER BY m.idparent ASC,m.urut ASC,m.menu ASC");
		$data[] = array("id" => "", "text" => "pilih");
		if ($execSql['db']->num_rows() > 0)
			foreach ($execSql['db']->result_array() as $dp) {
				$data[] = array("id" => $dp['id'], "text" => strip_tags($dp['menu']));
			}
		$this->loadLokal($el, $data);
		return $data;
	}

	public function mstselect($el = null, $tbName = null, $vtext = null, $orderby = null)
	{
		if (!$orderby)
			$orderby = $vtext;
		$tmpQuery = "SELECT * FROM " . $tbName . " ORDER BY " . $orderby . " ASC";
		//echo $tmpQuery;
		$execSql = $this->ci->Model_data->runQuery($tmpQuery);
		$data = array();
		if ($execSql['db']->num_rows() > 0)
			foreach ($execSql['db']->result_array() as $dp) {
				$data[] = array("id" => $dp['id'], "text" => strip_tags($dp[$vtext]));
			}
		$this->loadLokal($el, $data);
		return $data;
	}
}

/* End of file Select2.php */
/* Location: ./application/libraries/Select2.php */