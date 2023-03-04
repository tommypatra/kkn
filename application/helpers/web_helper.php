<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//fungsi debug
if (!function_exists('debug')) {
	function debug($vArr, $vStop = true)
	{
		echo "<pre>";
		print_r($vArr);
		if ($vStop)
			die;
	}
}

if (!function_exists('gantikarakter')) {
	function gantikarakter($sumber, $karakter = "X")
	{
		$times = strlen(trim(substr($sumber, 4, 5)));
		$star = '';
		for ($i = 0; $i < $times; $i++) {
			$star .= $karakter;
		}
		return $star;
	}
}


if (!function_exists('allowheader')) {
	function allowheader($content_type = "application/json")
	{
		$allow = [
			'kkn.iainpare.ac.id',
		];

		//debug($_SERVER);
		$http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "https://kkn.iainpare.ac.id";
		$web_origin = parse_url($http_origin);

		Header("Access-Control-Allow-Origin: " . $http_origin);
		Header("Access-Control-Allow-Headers: *");
		header("Access-Control-Allow-Credentials: true");
		Header("Access-Control-Allow-Methods: GET, POST");
		header("Content-Type: " . $content_type . "; charset=utf-8");

		$CI = get_instance();
		//if (!in_array($web_origin['host'], $allow) || !$CI->input->is_ajax_request()) {
		if (!in_array($web_origin['host'], $allow)) {
			$retval = array("status" => false, "pesan" => ["tidak diperbolehkan"]);
			die(json_encode(($retval)));
		}
	}
}


if (!function_exists('encdec')) {
	function encdec($action, $string)
	{
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'secretkey1';
		$secret_iv = 'secretkey4';
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if ($action == 'enc') {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
			$output = str_replace(['+', '/', '='], ['-', '_', ''], $output);
		} else if ($action == 'dec') {
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
		return $output;
	}
}

if (!function_exists('waktu_lalu')) {
	function waktu_lalu($timestamp = null)
	{
		$waktu = "";
		if ($timestamp) {
			$phpdate = strtotime($timestamp);
			$mysqldate = date('Y-m-d H:i:s', $phpdate);

			$selisih = time() - strtotime($mysqldate);
			$detik = $selisih;
			$menit = round($selisih / 60);
			$jam = round($selisih / 3600);
			$hari = round($selisih / 86400);
			$minggu = round($selisih / 604800);
			$bulan = round($selisih / 2419200);
			$tahun = round($selisih / 29030400);
			if ($detik <= 60) {
				$waktu = $detik . ' detik lalu';
			} else if ($menit <= 60) {
				$waktu = $menit . ' menit lalu';
			} else if ($jam <= 24) {
				$waktu = $jam . ' jam lalu';
			} else if ($hari <= 7) {
				$waktu = $hari . ' hari lalu';
			} else if ($minggu <= 4) {
				$waktu = $minggu . ' minggu lalu';
			} else if ($bulan <= 12) {
				$waktu = $bulan . ' bulan lalu';
			} else {
				$waktu = $tahun . ' tahun lalu';
			}
		}
		return $waktu;
	}
}

if (!function_exists('searchMultiArray')) {
	function searchMultiArray($array, $key, $value)
	{
		$results = array();

		if (is_array($array)) {
			if (isset($array[$key]) && $array[$key] == $value) {
				$results[] = $array;
			}

			foreach ($array as $subarray) {
				$results = array_merge($results, searchMultiArray($subarray, $key, $value));
			}
		}

		return $results;
	}
}

if (!function_exists('arrtoselect2')) {
	function arrtoselect2($vdata = [], $vid = "id", $vtext = "text")
	{
		$retval = array();
		if (count($vdata) > 0)
			foreach ($vdata as $i => $dp) {
				$retval[] = array('id' => $dp[$vid], 'text' => $dp[$vtext]);
			}
		return $retval;
	}
}

if (!function_exists('generatetoken_old')) {
	function generatetoken_old($vgrup = null, $vdata = null)
	{
		$key1 = substr($vdata, 0, 2);
		$key2 = substr($vdata, 2, 2);
		$key3 = substr($vdata, 4, 2);
		$key4 = substr($vdata, 6, 2);
		$key5 = substr($vdata, 8, 2);
		$key6 = substr($vdata, 10, 2);
		$key7 = substr($vdata, 12, 2);
		$key8 = substr($vdata, 14, 2);
		$token = $vgrup . date("y") . $key5 . date("m") . $key4 . date("d") . $key2 . date("H") . $key6 .
			date("i") . $key8 . date("s") . $key1 . rand(10, 99) . $key3 . rand(10, 99) . $key7 . rand(10, 99);
		return $token;
	}
}

if (!function_exists('generatetoken')) {
	function generatetoken($vgrup = "1", $viduser = null)
	{
		$key1 = rand(10, 99);
		$key2 = rand(10, 99);
		$key3 = rand(10, 99);
		$key4 = rand(10, 99);
		$key5 = rand(10, 99);
		$key6 = rand(10, 99);
		$key7 = rand(10, 99);
		$token =  $key1 . $vgrup . $viduser . date("y") . $key2 . date("m") . $key3 . date("d") . $key4 . date("H") . $key5 . date("i") . $key6 . date("s") .  $key7;
		return $token;
	}
}

if (!function_exists('decodeNIK')) {
	function decodeNIK($vdata = null)
	{
		$retval = [];
		//inisiasi tahun sekarang
		$thnskrng = date("Y");
		//menyiapkan temporari tahun
		$tmpthn = (int)substr($thnskrng, 0, 2);

		$retval["prov"] = substr($vdata, 0, 2);
		$retval["kab"] = substr($vdata, 2, 2);
		$retval["kec"] = substr($vdata, 4, 2);

		$tgl = (int)substr($vdata, 6, 2);
		$bln = (int)substr($vdata, 8, 2);
		$thn = (int)substr($vdata, 10, 2);

		$thnlahir = ($tmpthn . $thn);
		if ((int)$thnlahir > (int)$thnskrng) {
			$thnlahir = ($tmpthn - 1) . $thn;
		}

		$retval["kel"] = "L";
		if ($tgl > 40) {
			$tgl = $tgl - 40;
			$retval["kel"] = "P";
		}
		$retval["tgllahir"] = date("Y-m-d");
		if (checkdate($bln, $tgl, $thnlahir))
			$retval["tgllahir"] = $thnlahir . "-" . $bln . "-" . $tgl;
		//echo $retval["tgllahir"];
		//die;
		return $retval;
	}
}


if (!function_exists('setCalculate')) {
	function setCalculate()
	{
		$CI = &get_instance();
		$data = array(
			'v1' => rand(1, 998),
			'v2' => 1,
		);
		$CI->session->set_userdata($data);
		return $data;
	}
}

if (!function_exists('akses_akun')) {
	function akses_akun($action = null, $otentikasi = [], $tbname = null, $id = null, $fldid = "id", $fldcheck = "owned")
	{
		$CI = &get_instance();
		$data = array();
		$status = false;

		//untuk cek apakan user yang melakukan insert pertama kali
		$input1st = false;
		if ($tbname && $id) {
			$query = "SELECT * FROM " . $tbname . " WHERE " . $fldid . "=" . $id . " AND " . $fldcheck . "='" . $CI->session->userdata("iduser") . "'";
			$runquery = $CI->db->query($query);
			if ($runquery->num_rows() > 0) {
				$input1st = true;
				$data = $runquery->row();
			}
		} else {
			$input1st = true;
		}

		if ($action == "insert" && $otentikasi['hakAkses']['c'] == "y") {
			$status = true;
		} elseif ($action == "update") {
			if ($otentikasi['hakAkses']['f'] == "y" && $otentikasi['hakAkses']['u'] == "y")
				$status = true;
			elseif ($otentikasi['hakAkses']['f'] <> "y" && $input1st && $otentikasi['hakAkses']['u'] == "y")
				$status = true;
		} elseif ($action == "delete") {
			if ($otentikasi['hakAkses']['f'] == "y" && $otentikasi['hakAkses']['d'] == "y")
				$status = true;
			elseif ($otentikasi['hakAkses']['f'] <> "y" && $input1st && $otentikasi['hakAkses']['d'] == "y")
				$status = true;
		}
		$retval = array("status" => $status, "data" => $data);
		return (object) $retval;
	}
}

if (!function_exists('parseTree')) {
	function parseTree($tree, $root = "", $treeCol = [])
	{
		$return = array();
		foreach ($tree as $child => $dataArr) {
			$tmpRet = array();
			if ($dataArr[$treeCol[1]] == $root || $child == 0) {
				unset($tree[$child]);
				$hasChild = true;
				$tmpRet[$treeCol[0]] = $dataArr[$treeCol[0]];
				$tmpRet[$treeCol[1]] = parseTree($tree, $dataArr[$treeCol[0]], $treeCol);
				for ($i = 2; $i < count($treeCol); $i++) {
					$tmpRet[$treeCol[$i]] = $dataArr[$treeCol[$i]];
				}
				array_push($return, $tmpRet);
			}
		}
		return empty($return) ? null : $return;
	}
}

//fungsi dapat gps dari foto
if (!function_exists('gps2Num')) {
	function gps2Num($coordPart)
	{
		$parts = explode('/', $coordPart);
		if (count($parts) <= 0)
			return 0;
		if (count($parts) == 1)
			return $parts[0];
		return floatval($parts[0]) / floatval($parts[1]);
	}
}

//fungsi dapat gps dari foto
if (!function_exists('photo_getGPS')) {

	function photo_getGPS($exif = [])
	{
		$result = array('latitude' => null, 'longitude' => null);

		if (!isset($exif['GPS']) || !isset($exif['GPS']['GPSLatitude'])) {
			return $result;
			die;
		}
		//$exif = $exif['GPS'];

		$GPSLatitudeRef = $exif['GPS']['GPSLatitudeRef'];
		$GPSLatitude    = $exif['GPS']['GPSLatitude'];
		$GPSLongitudeRef = $exif['GPS']['GPSLongitudeRef'];
		$GPSLongitude   = $exif['GPS']['GPSLongitude'];

		$lat_degrees = count($GPSLatitude) > 0 ? gps2Num($GPSLatitude[0]) : 0;
		$lat_minutes = count($GPSLatitude) > 1 ? gps2Num($GPSLatitude[1]) : 0;
		$lat_seconds = count($GPSLatitude) > 2 ? gps2Num($GPSLatitude[2]) : 0;

		$lon_degrees = count($GPSLongitude) > 0 ? gps2Num($GPSLongitude[0]) : 0;
		$lon_minutes = count($GPSLongitude) > 1 ? gps2Num($GPSLongitude[1]) : 0;
		$lon_seconds = count($GPSLongitude) > 2 ? gps2Num($GPSLongitude[2]) : 0;

		$lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
		$lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;

		$latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60 * 60)));
		$longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60 * 60)));

		$result = array('latitude' => $latitude, 'longitude' => $longitude);

		return $result;
	}
}

//fungsi user aktif berdasarkan session
if (!function_exists('useraktif')) {
	function useraktif($menit = 60)
	{
		$CI = get_instance();
		$retVal = array("status" => false, "pesan" => ["akses terbatas"], "jumlah" => 0);
		$runquery = $CI->db->query("SELECT count(id) as jumlah from sessions
									WHERE timestamp > UNIX_TIMESTAMP(DATE_ADD(NOW(),INTERVAL -" . $menit . " MINUTE))");

		if ($runquery->num_rows() > 0) {
			$dt = $runquery->row();
			$retVal = array("status" => true, "pesan" => ["data ditemukan"], "jumlah" => $dt->jumlah);
		}

		return $retVal;
	}
}


if (!function_exists('caripemilikdata')) {
	function caripemilikdata($tbname = null, $id = null, $fld = "iduser")
	{
		$retVal = "";
		$CI = &get_instance();
		$kond = array(
			array("where", "id", $id),
		);
		$run = $CI->Model_data->searchData($kond, $tbname, $fld . " as pemilik");
		//echo $CI->db->last_query();
		if ($run['status'])
			$retVal = $run['db']->row()->pemilik;
		return $retVal;
	}
}

if (!function_exists('printTree')) {
	function printTree($tree, $show = true, $retval = [])
	{
		if (count($tree) > 0) {
			if ($show)
				echo '<ul>';
			foreach ($tree as $b) {
				$retval[] = $b['id'];
				if ($show)
					echo '<li>' . $b['text'];
				$retval = printTree($b['children'], $show, $retval);
				if ($show)
					echo '</li>';
			}
			if ($show)
				echo '</ul>';
		}
		return $retval;
	}
}

//membuat tree dari array
if (!function_exists('buildTree')) {
	function buildTree(array $elements, $parentId = 0)
	{
		$branch = array();

		foreach ($elements as $element) {
			if ($element['idparent'] == $parentId) {
				$children = buildTree($elements, $element['id']);
				if ($children) {
					$element['children'] = $children;
				}
				$branch[] = $element;
			}
		}

		return $branch;
	}
}


//fungsi loadTahun
if (!function_exists('loadTahun')) {
	function loadTahun($jumlah = 6)
	{
		$thn = (int)date("Y");
		$listThn = array();
		for ($i = $thn; $i >= ($thn - $jumlah); $i--) {
			$listThn[] = array("id" => $i, "text" => $i);
		}
		print_r(json_encode($listThn));
	}
}

function get_client_ip()
{
	return getenv('HTTP_CLIENT_IP') ?:
		getenv('HTTP_X_FORWARDED_FOR') ?:
		getenv('HTTP_X_FORWARDED') ?:
		getenv('HTTP_FORWARDED_FOR') ?:
		getenv('HTTP_FORWARDED') ?:
		getenv('REMOTE_ADDR');
}


//fungsi get_client_info
if (!function_exists('get_client_info')) {
	function get_client_info()
	{
		$CI = get_instance();
		$CI->load->library('user_agent');

		$agent = array(
			"platform" => $CI->agent->platform,
			"browser" => $CI->agent->browser,
			"version" => $CI->agent->version,
			"mobile" => $CI->agent->mobile,
			"robot" => $CI->agent->robot,
			"referer" => $CI->agent->referer,
			"ipaddress" => null,
		);

		return json_encode($agent);
	}
}

//fungsi convert serializearray untuk jadi lebih sederhana di akses
if (!function_exists('simpleSerializeArray')) {
	function simpleSerializeArray($var = [])
	{
		$retVal = array();
		if (is_array($var)) {
			foreach ($var as $ind => $dp) {
				$retVal[$dp['name']][] = $dp['value'];
			}
		}
		return $retVal;
	}
}

//fungsi label tanggal
if (!function_exists('labeltanggal')) {
	function labeltanggal($vtgl1 = null, $vtgl2 = null, $checktgl2 = true)
	{
		$status = false;
		$label = "";
		$sekarang = date("Y-m-d");

		if (!$vtgl1)
			$vtgl1 = date("Y-m-d");
		else
			$vtgl1 = date("Y-m-d", strtotime($vtgl1));

		if (!$vtgl2)
			$vtgl2 = date("Y-m-d");
		else
			$vtgl2 = date("Y-m-d", strtotime($vtgl2));

		if ($vtgl1 == $vtgl2) {
			$checktgl2 = false;
		}

		$bgclr = "default";
		$label = "";
		if ($checktgl2) {
			if ($vtgl1 == $vtgl2)
				$label = "";
			elseif ($vtgl1 > $sekarang && $vtgl2 > $sekarang) {
				$bgclr = "warning";
				$label = "Belum";
			} elseif ($vtgl1 < $sekarang && $vtgl2 < $sekarang) {
				$bgclr = "danger";
				$label = "Tertutup";
			} else {
				$bgclr = "success";
				$label = "Terbuka";
				$status = true;
			}
			//$labeltgl=date("d-m-Y",$vtgl1)." sd ".date("d-m-Y",$vtgl2);
		} else {
			if ($vtgl1 > $sekarang) {
				$bgclr = "primary";
				$label = "Belum";
			} elseif ($vtgl1 < $sekarang) {
				$bgclr = "warning";
				$label = "Selesai";
			} else {
				$bgclr = "success";
				$label = "Terbuka";
				$status = true;
			}
			//$labeltgl=date("d-m-Y",$vtgl1);
		}
		$retval = array(
			"status" => $status,
			"bgclr" => $bgclr,
			"label" => $label,
			//"labeltgl"=>$labeltgl,
			"labelbadge" => "<span class='badge bg-" . $bgclr . "'>" . $label . "</span>",
		);
		return $retval;
	}
}


//fungsi convert serialize jadi post array
if (!function_exists('dataSerialize')) {
	function dataSerialize($datapost)
	{
		$retVal = array();
		parse_str($datapost, $retVal);
		return $retVal;
	}
}

//fungsi format rupiah
function format_rupiah($vuang = 0, $vkoma = 0)
{
	return number_format($vuang, $vkoma, ",", ".");
}

//fungsi cek otentikasi login, read dan mengembalikan hakAkses user atau grup pada suatu modul
if (!function_exists('otentikasi')) {
	function otentikasi($vParam = [])
	{
		$CI = get_instance();
		$defVal = array("status" => false, "login" => false, "pesan" => ["akses terbatas"], "hakAkses" => array("c" => "n", "r" => "n", "u" => "n", "d" => "n", "f" => "n"));
		$retVal = $defVal;
		if (!$CI->session->userdata('iduser')) {
			batal:
			if (!$CI->input->is_ajax_request()) {
				$CI->session->set_flashdata('retVal', $defVal);
				redirect(base_url());
			} else {
				print_r(json_encode($defVal));
			}
			die;
		}


		// cek user sudah update atau belum berdasarkan NULL pada IDDESA
		/*
		$cekUpdateAkun = $CI->db->query("SELECT id FROM user WHERE iddesa IS NOT NULL AND id='" . $CI->session->userdata('iduser') . "'");
		if ($cekUpdateAkun->num_rows() < 1) { // || $CI->session->userdata("aktivasi") != "y") {
			$tmppesan = "";
			if ($cekUpdateAkun == "")
				$tmppesan .= "Lengkapi data anda terlebih dahulu";

			$defVal = array("status" => false, "login" => true, "pesan" => "Lengkapi data anda terlebih dahulu");
			$CI->session->set_flashdata('retVal', $defVal);
			redirect(base_url('app/profil'));
		}
		*/
		// akhir cek user sudah update atau belum berdasarkan NULL pada IDDESA

		$groups = json_decode($CI->session->userdata('idgrup'));
		$in_groups = "(";
		foreach ($groups as $i => $val) {
			$in_groups = $in_groups . "'" . $val . "'";
			if ($i < count($groups) - 1)
				$in_groups = $in_groups . ",";
		}
		$in_groups = $in_groups . ")";
		//echo $in_groups;die;

		$aksesGrp = $CI->db->query("SELECT a.* FROM aksesgrup as a LEFT JOIN module as m ON (m.id=a.idmodule) WHERE m.module='" . $vParam['web']['modul'] . "' AND a.idgrup IN " . $in_groups);
		$aksesUsr = $CI->db->query("SELECT a.* FROM aksesuser as a LEFT JOIN module as m ON (m.id=a.idmodule) WHERE m.module='" . $vParam['web']['modul'] . "' AND a.iduser='" . $CI->session->userdata('iduser') . "'");


		//$aksesGrp = $CI->db->query("SELECT * FROM aksesgrup WHERE module='" . $vParam['web']['modul'] . "' AND idgrup IN " . $in_groups);
		//$aksesUsr = $CI->db->query("SELECT * FROM aksesuser WHERE module='" . $vParam['web']['modul'] . "' AND iduser='" . $CI->session->userdata('iduser') . "'");

		$adaAkses = true;
		if ($aksesUsr->num_rows() > 0)
			$hakAkses = $aksesUsr->row();
		elseif ($aksesGrp->num_rows() > 0) {
			$hakAkses = $aksesGrp->row();
		} else {
			$adaAkses = false;
		}

		$retVal["status"] = true;
		$retVal["login"] = true;
		$retVal["pesan"] = ["login dilakukan"];
		if ($adaAkses)
			$retVal["hakAkses"] = array("c" => $hakAkses->c, "r" => $hakAkses->r, "u" => $hakAkses->u, "d" => $hakAkses->d, "f" => $hakAkses->f);

		if ($retVal["hakAkses"]["r"] == "n") {
			goto batal;
		}
		return $retVal;
	}
}


//fungsi cek login
if (!function_exists('cekLogin')) {
	function cekLogin($vUrl = null, $retVal = [])
	{
		$CI = get_instance();

		if (!$vUrl)
			$vUrl = base_url('app/dashboard');

		if ($CI->session->userdata('iduser')) {
			$retVal['login'] = true;
			$retVal['status'] = true;
			$retVal['vUrl'] = $vUrl;
			if (!$CI->input->is_ajax_request()) {
				$CI->session->set_flashdata('retVal', $retVal);
				redirect($vUrl);
			} else {
				print_r(json_encode($retVal));
			}
		}
		//die;
	}
}

//fungsi extract email
if (!function_exists('extractemail')) {
	function extractemail($email)
	{
		$retval = array();
		preg_match("/^(.+)@([^\(\);:,<>]+\.[a-zA-Z]+)/", $email, $retval);
		return $retval;
	}
}

//fungsi inisiasi layanan google login
if (!function_exists('setupgoogle')) {
	function setupgoogle()
	{
		$CI = get_instance();
		$CI->load->library('google');
		$CI->google->setClientId('');
		$CI->google->setClientSecret('');
		$CI->google->setRedirectUri('https://kkn.iainpare.ac.id/login');
		$CI->google->addScope('email');
		$CI->google->addScope('profile');
	}
}

//fungsi format tanggal 
if (!function_exists('formatTgl')) {
	function formatTgl($vTgl = null, $vFormat = "YMD")
	{
		$vRetVal = date("Y-m-d");
		if ($vTgl) {
			switch ($vFormat) {
				case "DMY":
					$vRetVal = date('d-m-Y', strtotime($vTgl));
					break;
				case "YMD":
					$vRetVal = date('Y-m-d', strtotime($vTgl));
					break;
				default:
					$vRetVal = date('Y-m-d', strtotime($vTgl));
			}
		}
		return $vRetVal;
	}
}
