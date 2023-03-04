<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_data extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->db->initialize();
	}

	public function runQuery($myQuery)
	{
		$ret = array('pesan' => ['hubungi admin'], 'status' => false, 'queryTime' => 0, 'db' => []);
		try {
			$this->db->db_debug = FALSE;
			$started = microtime(true);
			$runquery = $this->db->query($myQuery);
			$end = microtime(true);
			$difference = $end - $started;
			$queryTime = number_format($difference, 10);
			//$ret['queryTime']=$queryTime;

			//if ($this->db->trans_status() === FALSE || $queryTime>2.5)
			if ($runquery) {
				$ret['pesan'] = array('Berhasil dilakukan');
				$ret['status'] = true;
				$ret['db'] = $runquery;
			} else {
				$db_error = $this->db->error();
				$ret['pesan'] = array('Gagal, Error Code ' . $db_error['code'] . ' ' . $db_error['message']);
				$ret['status'] = false;
				$ret['db'] = [];
			}

			$this->db->db_debug = TRUE;
			return $ret;
		} catch (Exception $e) {
			log_message('error: ', $e->getMessage());
			return;
		}
	}

	public function generateCond($vCari = [])
	{
		if (count($vCari) > 0)
			foreach ($vCari as $fld => $val) {
				if (strcmp($val[0], "wherex") == 0)
					$this->db->where($val[1], null, false);
				elseif (strcmp($val[0], "where") == 0)
					$this->db->where($val[1], $val[2], null, false);
				elseif (strcmp($val[0], "like") == 0)
					$this->db->like($val[1], $val[2], $val[3], null, false);
				elseif (strcmp($val[0], "where_in") == 0)
					$this->db->where_in($val[1], $val[2], null, false);
				elseif (strcmp($val[0], "or_where") == 0)
					$this->db->or_where($val[1], $val[2], null, false);
				elseif (strcmp($val[0], "or_like") == 0)
					$this->db->or_like($val[1], $val[2], $val[3], null, false);
				elseif (strcmp($val[0], "or_where_in") == 0)
					$this->db->or_where_in($val[1], $val[2], null, false);
			}
	}

	public function searchData($vCari, $tblNm, $vColSel)
	{
		$ret = array('pesan' => ['hubungi admin'], 'status' => false, 'queryTime' => 0, 'db' => []);
		try {
			$this->db->db_debug = FALSE;

			$started = microtime(true);
			$this->db->select($vColSel, false);
			if (count($vCari) > 0)
				$this->generateCond($vCari);
			$runquery = $this->db->get($tblNm);

			if ($runquery) {
				$ret['pesan'] = array('Berhasil dilakukan');
				$ret['status'] = true;
				$ret['db'] = $runquery;
			} else {
				$db_error = $this->db->error();
				$ret['pesan'] = array('Gagal, Error Code ' . $db_error['code'] . ' ' . $db_error['message']);
				$ret['status'] = false;
				$ret['db'] = [];
			}

			$this->db->db_debug = TRUE;
			return $ret;
		} catch (Exception $e) {
			log_message('error: ', $e->getMessage());
			return;
		}
	}

	public function save($data, $tbl, $grup = "data", $log = false)
	{
		$ret = array('pesan' => ['hubungi admin'], 'status' => false, 'queryTime' => 0, 'db' => []);
		try {
			$this->db->db_debug = FALSE;

			$started = microtime(true);
			if (!isset($data['owned']) || !isset($data['created'])) {
				$data['owned'] = $this->session->userdata("iduser");
				$data['created'] = date("Y-m-d H:i:s");
			}
			//unset($data['id']);
			$runquery = $this->db->insert($tbl, $data);
			$lastId = $this->db->insert_id();
			$end = microtime(true);
			$difference = $end - $started;
			$queryTime = number_format($difference, 10);
			//$ret['queryTime'] = $queryTime;
			$ret['id'] = $lastId;
			if ($runquery) {
				$ret['pesan'] = array('Tambah ' . $grup . ' berhasil dilakukan');
				$ret['status'] = true;
				/*
				if ($log) {
					$datalog = array(
						'tabel' => $tbl,
						'iduser' => $this->session->userdata("iduser"),
						'idfk' => $lastId,
						'waktu' => date("Y-m-d h:i:s"),
						'aksi' => "tambah",
						'datalog' => "{data : " . json_encode($data) . "}",
					);
					$this->db->insert("log", $datalog);
				}
				*/
			} else {
				$db_error = $this->db->error();
				$ret['pesan'] = array('Gagal, Error Code ' . $db_error['code'] . ' ' . $db_error['message']);
				$ret['status'] = false;
			}

			$this->db->db_debug = TRUE;
			return $ret;
		} catch (Exception $e) {
			log_message('error: ', $e->getMessage());
			return;
		}
	}

	public function update($vCond, $data, $tbl, $grup = "data", $log = false)
	{
		$ret = array('pesan' => ['hubungi admin'], 'status' => false, 'db' => []);
		//untuk log
		//$lastData = $this->searchData($vCond, $tbl, "*")['db']->result_array();
		try {
			$this->db->db_debug = FALSE;
			$jumCond = count($vCond);
			$lastId = "";
			if ($jumCond > 0) {
				$i = 1;
				foreach ($vCond as $fld => $val) {
					$lastId = $lastId . $val[2];
					if (strcmp($val[0], "like") == 0)
						$this->db->like($val[1], $val[2], $val[3]);
					else
						$this->db->where($val[1], $val[2]);
					if ($i < $jumCond)
						$lastId = $lastId . ",";
					$i++;
				}

				$started = microtime(true);
				if (!isset($data['iduser_update'])) {
					$data['iduser_update'] = $this->session->userdata("iduser");
					$data['update'] = date("Y-m-d H:i:s");
				}


				$runquery = $this->db->update($tbl, $data);
				$end = microtime(true);
				$difference = $end - $started;
				$queryTime = number_format($difference, 10);
				//$ret['queryTime'] = $queryTime;
				$ret['id'] = $lastId;

				if ($runquery) {
					$ret['pesan'] = array('Update ' . $grup . ' berhasil dilakukan');
					$ret['status'] = true;
					/*
					if ($log) {
						$datalog = array(
							'tabel' => $tbl,
							'iduser' => $this->session->userdata("iduser"),
							'idfk' => $lastId,
							'waktu' => date("Y-m-d h:i:s"),
							'aksi' => "ganti",
							'datalog' => "{cond:" . json_encode($vCond) . ",datanew:" . json_encode($data) . ", lastdata:" . json_encode($lastData) . "}",
						);
						$this->db->insert("log", $datalog);
					}
					*/
				} else {
					$db_error = $this->db->error();
					$ret['pesan'] = array('Gagal, Error Code ' . $db_error['code'] . ' ' . $db_error['message']);
					$ret['status'] = false;
				}
			}
			$this->db->db_debug = TRUE;
			return $ret;
		} catch (Exception $e) {
			log_message('error: ', $e->getMessage());
			return;
		}
	}

	public function delete($vCond, $tbl, $grup = "data", $log = false)
	{
		//untuk log
		//$lastData = $this->searchData($vCond, $tbl, "*")['db']->result_array();
		try {
			$this->db->db_debug = FALSE;
			if (count($vCond) > 0) {
				$this->generateCond($vCond);

				$started = microtime(true);
				$runquery = $this->db->delete($tbl);
				$end = microtime(true);
				$difference = $end - $started;
				$queryTime = number_format($difference, 10);

				$ret['queryTime'] = $queryTime;

				if ($runquery) {
					$ret['pesan'] = array('Hapus ' . $grup . ' berhasil dilakukan');
					$ret['status'] = true;
					/*
					if ($log) {
						$datalog = array(
							'tabel' => $tbl,
							'iduser' => $this->session->userdata("iduser"),
							'waktu' => date("Y-m-d h:i:s"),
							'aksi' => "hapus",
							'datalog' => "{cond:" . json_encode($vCond) . ", lastdelete:" . json_encode($lastData) . "}",
						);
						$this->db->insert("log", $datalog);
					}
					*/
				} else {
					$db_error = $this->db->error();
					$ret['pesan'] = array('Gagal, Error Code ' . $db_error['code'] . ' ' . $db_error['message']);
					$ret['status'] = false;
				}
			}
			$this->db->db_debug = TRUE;
			return $ret;
		} catch (Exception $e) {
			log_message('error: ', $e->getMessage());
			return;
		}
	}
}

/* End of file model_data.php */
/* Location: ./application/models/model_data.php */