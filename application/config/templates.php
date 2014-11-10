<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['templates']['front']['default'] = array(
	'regions'		=>	array('header', 'main_menu', 'sidebar', 'footer'),
	'scripts'		=>	array(
		//['type' => 'base', 'value' => 'libraries/jquery/jquery-1.11.0.min'],
		//array('type' => 'base', 'value' => 'bootstrap/bootstrap.min'),
		//array('type' => 'base', 'value' => 'bootstrap/moment'),
		//['type' => 'base', 'value' => 'bootstrap/bootstrap-datetimepicker.es'],
		//array('type' => 'base', 'value' => 'bootstrap/bootstrap-datetimepicker.min'),
		//array('type' => 'base', 'value' => 'libraries/tinymce/tinymce.min'),
		//array('type' => 'base', 'value' => 'libraries/jqueryui/jquery-ui.min'),
		//array('type' => 'template', 'value' => 'script'),
	),
	'styles'		=>	array(
		//array('type' => 'base', 'value' => 'bootstrap/css/bootstrap.min'),
		//array('type' => 'base', 'value' => 'bootstrap/css/bootstrap-datetimepicker.min'),
		//array('type' => 'base', 'value' => 'libraries/jqueryui/jquery-ui.min'),
		//array('type' => 'template', 'value' => 'style'),
	)
);

$config['templates']['admin']['default'] = array(
	'regions'		=>	array('header', 'main_menu', 'sidebar', 'footer'),
	'scripts'		=>	array(
		array('type' => 'base', 'value' => 'bootstrap/bootstrap.min'),
		array('type' => 'base', 'value' => 'bootstrap/moment.min'),
		array('type' => 'base', 'value' => 'bootstrap/bootstrap-datetimepicker.min'),
		array('type' => 'base', 'value' => 'bootstrap/bootstrap-switch.min'),
		array('type' => 'base', 'value' => 'bootstrap/bootstrap-filestyle.min'),
		array('type' => 'base', 'value' => 'libraries/jquery-alerts/jquery.alerts.min'),
		array('type' => 'base', 'value' => 'libraries/jqueryui/jquery-ui.min'),
		array('type' => 'base', 'value' => 'libraries/tinymce/tinymce.min'),
		array('type' => 'base', 'value' => 'libraries/fancybox/jquery.fancybox.pack'),
		array('type' => 'base', 'value' => 'libraries/customscrollbar/jquery.mCustomScrollbar.concat.min'),
		array('type' => 'template', 'value' => 'script'),
	),
	'styles'		=>	array(
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap.min'),
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap-datetimepicker.min'),
		array('type' => 'base', 'value' => 'bootstrap/css/bootstrap-switch.min'),
		array('type' => 'base', 'value' => 'libraries/jquery-alerts/jquery.alerts.min'),
		array('type' => 'base', 'value' => 'libraries/jqueryui/jquery-ui.min'),
		array('type' => 'base', 'value' => 'libraries/fancybox/jquery.fancybox'),
		array('type' => 'base', 'value' => 'libraries/customscrollbar/jquery.mCustomScrollbar.min'),
		array('type' => 'template', 'value' => 'style'),
	)
);

/* End of file templates.php */
/* Location: ./application/config/templates.php */