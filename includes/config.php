<?php
global $db;

$db_host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "font-viewer";

$db = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
$site_name = "font-viewer";
$base_url = "http://localhost:82/".$site_name."/";
$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$fonts_dir = $_SERVER['DOCUMENT_ROOT']."/".$site_name."/assets/fonts/";
$fonts_archive_dir = $_SERVER['DOCUMENT_ROOT']."/".$site_name."/assets/fonts/archives/";
$templates_dir = $_SERVER['DOCUMENT_ROOT']."/".$site_name."/includes/templates/";
$upload_dir = $_SERVER['DOCUMENT_ROOT']."/".$site_name."/uploads/";


function template_loader($filename,$data){  
  $filecontent = file_get_contents($filename);

  $template_applied = null;
  $i = 0;
  foreach($data as $index=>$variable){
    if($i != 0){
      $template_applied = str_replace("{".$index."}",$variable,$template_applied);
    }
    else{
      $template_applied = str_replace("{".$index."}",$variable,$filecontent);
    }
    $i++;
  }

  return $template_applied;
}
?>
