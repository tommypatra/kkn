<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//mendefinisikan plugins
if (!function_exists('loadPlugins')) {
	function loadPlugins($plugin = "")
	{
		$retVal = array("css" => "", "js" => "",);
		$time = date("ymdhis") . rand(100, 999);
		switch ($plugin) {
			case "datatables":
				$retVal = array(
					"css" => base_url('assets/plugins/datatables/datatables.min.css'),
					"js" => base_url('assets/plugins/datatables/datatables.min.js')
				);
				break;
			case "datatables_template":
				$retVal = array(
					"css" => base_url('templates/mazer/assets/css/pages/datatables.css'),
					"js" => base_url('templates/mazer/assets/js/extensions/datatables.js')
				);
				break;
			case "select2":
				$retVal = array(
					"css" => array(
						base_url('assets/plugins/select2/dist/css/select2.min.css?' . $time),
						base_url('assets/plugins/select2/dist/css/select2.custom.css'),
					),
					"js" => base_url('assets/plugins/select2/dist/js/select2.min.js')
				);
				break;
			case "select2tree":
				$retVal = array(
					"css" => base_url('assets/plugins/select2tree/src/select2totree.css'),
					"js" => base_url('assets/plugins/select2tree/src/select2totree.js')
				);
				break;
			case "treeview":
				$retVal = array(
					"css" => base_url('assets/plugins/tree/dist/themes/default/style.min.css'),
					"js" => base_url('assets/plugins/tree/dist/jstree.min.js')
				);
				break;
			case "mask":
				$retVal = array(
					"css" => null,
					"js" => base_url('assets/plugins/mask/jquery.mask.min.js')
				);
				break;
			case "highlight":
				$retVal = array(
					"css" => null,
					"js" => base_url('assets/plugins/highlight/highlight.min.js')
				);
				break;
			case "datetime":
				$retVal = array(
					"css" => base_url('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'),
					"js" => array(
						base_url('assets/plugins/bootstrap-material-moment/moment.js'),
						base_url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'),
					),
				);
				break;
			case "validation":
				$retVal = array(
					"css" => base_url('assets/plugins/validationengine/css/validationEngine.jquery.css'),
					"js" => array(
						base_url('assets/plugins/validationengine/js/languages/jquery.validationEngine-id.js?' . $time),
						base_url('assets/plugins/validationengine/js/jquery.validationEngine.js'),
					),
				);
				break;
			case "viewerjs":
				$retVal = array(
					"css" => base_url('assets/plugins/viewerjs/viewer.min.css'),
					"js" => base_url('assets/plugins/viewerjs/viewer.min.js')
				);
				break;
			case "leaflet":
				$retVal = array(
					"css" => base_url('assets/plugins/leaflet/leaflet.css'),
					"js" => base_url('assets/plugins/leaflet/leaflet.js')
				);
				break;
			case "tour":
				$retVal = array(
					"css" => base_url('assets/plugins/tour/dist/webtour.min.css'),
					"js" => base_url('assets/plugins/tour/dist/webtour.min.js'),
				);
				break;
			case "ezview":
				$retVal = array(
					"css" => null,
					"js" => base_url('assets/plugins/EZView/EZView.js')
				);
				break;
			case "photoviewer":
				$retVal = array(
					"css" => base_url('assets/plugins/photoviewer/dist/photoviewer.min.css'),
					"js" => base_url('assets/plugins/photoviewer/dist/photoviewer.js')
				);
				break;
			case "contextmenu":
				$retVal = array(
					"css" => base_url('assets/plugins/contextMenu/jqcontext-menu.min.css?' . $time),
					"js" => base_url('assets/plugins/contextMenu/jqcontext-menu.min.js?' . $time)
				);
				break;
			case "camera":
				$retVal = array(
					"css" => null,
					"js" => base_url('assets/js/camera.js?' . $time)
				);
				break;
			case "chart":
				$retVal = array(
					"css" => null,
					"js" => base_url('assets/plugins/chart/chart.min.js')
				);
				break;
			case "dropzone":
				$retVal = array(
					"css" => base_url('assets/plugins/dropzone/min/dropzone.min.css'),
					"js" => base_url('assets/plugins/dropzone/min/dropzone.min.js')
				);
				break;
			case "editorweb":
				$retVal = array(
					"css" => base_url('assets/plugins/quill-1.3/quill.snow.css'),
					"js" =>  base_url('assets/plugins/quill-1.3/quill.js')
				);
				break;
			case "summernote":
				$retVal = array(
					"css" => base_url('templates/mazer/assets/css/pages/summernote.css'),
					"js" =>  base_url('templates/mazer/assets/js/extensions/summernote.js')
				);
				break;

			case "sweetalert":
				$retVal = array(
					"css" => base_url('assets/plugins/sweetalert2/dist/sweetalert2.min.css'),
					"js" =>  base_url('assets/plugins/sweetalert2/dist/sweetalert2.min.js')
				);
				break;
			case "jquery-validation":
				$retVal = array(
					"css" => null,
					"js" => base_url('assets/plugins/jquery-validation/dist/jquery.validate.min.js')
				);
				break;
			case "notify":
				$retVal = array(
					"css" => null,
					"js" => base_url('assets/plugins/bootstrap-notify/bootstrap-notify.js')
				);
				break;
			case "loading":
				$retVal = array(
					"css" => base_url('assets/plugins/loading/loading.css?' . $time),
					"js" => base_url('assets/plugins/loading/loading.js?' . $time)
				);
				break;
			case "select2lib":
				$retVal = array(
					"css" => null,
					"js" => base_url('assets/select2lib.js?' . $time),
				);
				break;
			case "myapp":
				$retVal = array(
					"css" => base_url('assets/myapp.css?' . $time),
					"js" => base_url('assets/myapp.js?' . $time),
				);
				break;
		}
		return $retVal;
	}
}
