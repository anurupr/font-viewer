<?php
  require_once("includes/config.php");
  $template = "homepage";
  $template_file = $templates_dir."${template}_template.tpl";
  $data = array();
  $data['base_url'] = $base_url;
  $data['current_url'] = $current_url;
  $data['title'] = "Home | Font Viewer";
  extract($data);
  $template_data = array();
  $template_data['base-url'] = $base_url;  
  $content = template_loader($template_file,$template_data);
  require_once("includes/views/content.php");
?>
