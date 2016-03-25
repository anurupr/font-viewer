<!DOCTYPE html>
<html>
  <head>    
    <link href="<?=$base_url; ?>assets/css/lib/ptsans-font.css" rel="stylesheet" type="text/css">
    <link href="<?=$base_url; ?>assets/css/lib/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="<?=$base_url; ?>assets/css/core.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$base_url; ?>assets/js/lib/jquery.min.js"></script>
    <script type="text/javascript" src="<?=$base_url; ?>assets/js/core.js"></script>
  </head>
  <body>
    <header class="logo-right menu-down">
      <div class="logo" >
        <a href="<?=$base_url; ?>index.php" style="font-family:'PT Sans Narrow';">Font Viewer</a>
      </div>
      <nav class="menu">
        <ul>
          <li>
            <a href="<?=$base_url; ?>index.php" class="<?=($current_url == $base_url.'index.php') ? 'active' : '' ; ?>">Home</a>
          </li>
          <li>
            <a href="<?=$base_url; ?>add-font.php" class="<?=($current_url == $base_url.'add-font.php') ? 'active' : '' ; ?>">Add Font</a>
          </li>
        </ul>
      </nav>
    </header>
    <section class="content">
