<?php
  require_once("includes/config.php");
  global $db;
  $template = "homepage";
  $template_file = $templates_dir."${template}_template.tpl";
  $data = array();
  $data['base_url'] = $base_url;
  $data['current_url'] = $current_url;
  $data['title'] = "Home | Font Viewer";
  extract($data);
  $template_data = array();
  $template_data['base-url'] = $base_url;  

  // get font data
  $sql = "SELECT * FROM fonts";
  $result = mysqli_query($db,$sql);
  $fonts = array();
  if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
      $font_name = pathinfo($row['name'],PATHINFO_FILENAME);
      $fonts[] = array(
                  'filename'=>$row['name'],
                  'name'=>$font_name,
                  'path'=>$fonts_url.$font_name."/".$row['name'],
                  'id' =>$row['id']
                 );
    }
  }

  $tpl = "<li data-obj='{data-obj}'><span class='filename'>{filename}</span><span class='name'>{name}</span></li>";
  $font_list = "";
  foreach($fonts as $font){  
    $_tpl =   str_replace("{filename}",$font['filename'],$tpl);
    $_tpl =   str_replace("{name}",$font['name'],$_tpl);
    $_tpl =   str_replace("{data-obj}",json_encode($font),$_tpl);
    
    $font_list.= $_tpl;
  }

  $template_data['font-list'] = $font_list;  

  $content = template_loader($template_file,$template_data);
  require_once("includes/views/content.php");
?>
