<?php
	try{
		require_once("../includes/config.php");
		global $db;

		$font_data = $_POST;
		/*$font_data['id'] = $_POST['id'];
		$font_data['name'] = $_POST['name'];
		$font_data['filename'] = $_POST['filename'];
		$font_data['path'] = $fonts_dir.$_POST['filename'];*/


		require_once($classes_dir."cssify.class.php");

		$cssify = new Cssify();
		$cssify->set_font_data($font_data);

		$response = array();
		$response['css'] = $cssify->build_font_family_string();

		die(json_encode($response));

	}
	catch(Exception $e){
		die(json_encode($e));
	}

?>