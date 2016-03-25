<?php
require_once("../includes/config.php");
global $db;
function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
       if ('.' === $file || '..' === $file) continue;
       if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
       else unlink("$dir/$file");
   }
 
   rmdir($dir);
}

// A list of permitted file extensions
$allowed = array('ttf', 'otf', 'eot','woff','zip','tar.gz','tar','tgz','tar.bz2');
$archive_formats = array('zip','tar.gz','tar','tgz','tar.bz2');
$font_formats = array('ttf', 'otf', 'eot','woff');

if(isset($_FILES['font-file']) && $_FILES['font-file']['error'] == 0){

	$extension = pathinfo($_FILES['font-file']['name'], PATHINFO_EXTENSION);
  $filename = pathinfo($_FILES['font-file']['name'],PATHINFO_FILENAME);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}

  if(in_array(strtolower($extension), $archive_formats)){
    // its an archive 
    // extract
    // if it is zip use php class
    if(strtolower($extension) == "zip"){
      $file_entries = array();
      #
      # try uploading the zip file first
      #      
      if(move_uploaded_file($_FILES['font-file']['tmp_name'], $upload_dir.$_FILES['font-file']['name'])){
        $filepath = $upload_dir.$_FILES['font-file']['name'];
        // 
        $md5sum = md5_file($filepath);
        //
        $filename_with_ext = $_FILES['font-file']['name'];
        //
        $sql = "INSERT INTO uploads(`filename`,`md5sum`) VALUES('".$filename_with_ext."','".$md5sum."')";
        mysqli_query($db,$sql);

        $upload_id = mysqli_insert_id($db);


        #
        # try to open the file using zip class
        #
        $zip_file = $upload_dir.$_FILES['font-file']['name'];
        $zip = zip_open($zip_file);      
        if ($zip) {                    
          
          // count of files which are font @see $font_formats 
          $font_count = 0;
          while ($zip_entry = zip_read($zip)) {            
            $entry_name = zip_entry_name($zip_entry);

            // get extension
            $extension = pathinfo($entry_name, PATHINFO_EXTENSION);
            if(in_array($extension,$font_formats)){
              $font_count++;
            }

            $file_entries[] = $entry_name;
            
          }          
          zip_close($zip);
          // insert into font archives
          $sql = "INSERT INTO font_archives(`font_count`,`upload_id`) VALUES ('".$font_count."','".$upload_id."')";
          mysqli_query($db,$sql);
          #
          # Delete directory and zip file - remove after dev
          #
          //unlink($zip_file);
          //rmdir_recursive($zip_dir);

        }
      }
      $response = array();
      $response['status'] = "success";
      $response['files'] = $file_entries;
      $response['upload_id'] = $upload_id;
      echo json_encode($response);
      exit;
    }
  }

	if(move_uploaded_file($_FILES['font-file']['tmp_name'], $upload_dir.$_FILES['font-file']['name'])){
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;
