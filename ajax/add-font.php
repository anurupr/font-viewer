<?php
	try{
		require_once("../includes/config.php");
		global $db;

		$data = $_POST;
		$upload_id = $data['upload_id'];

		$files = $data['files'];

		$sql = "SELECT filename FROM uploads WHERE id = ".$upload_id;
		$filename_with_ext = "";
		$result = mysqli_query($db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$filename_with_ext = $row['filename'];
		}


		
		// move it from uploads to archives
		// since the flow reaches here only if the user wants to process it

		rename($upload_dir.$filename_with_ext,$fonts_archive_dir.$filename_with_ext);

		$zip = zip_open($fonts_archive_dir.$filename_with_ext);
	    if ($zip) {
	    	#
	      	#	 create the directory if it doesn't exist
	     	#
	     	$filename_without_ext = pathinfo($filename_with_ext,PATHINFO_FILENAME);
	      	$zip_dir = $fonts_dir.$filename_without_ext;
	      	if(!is_dir($zip_dir)){
	        	mkdir($zip_dir);
	      	}

	      	while ($zip_entry = zip_read($zip)) {
	      		$entry_name = zip_entry_name($zip_entry);
	      		// check if the user wants this entry	      		
	      		if(in_array($entry_name,$files)){
	      			$sql = "INSERT INTO fonts(`name`,`upload_id`) VALUES('".$entry_name."',".$upload_id.")";	      			
	      			mysqli_query($db,$sql);
					$zip_entry_file = $zip_dir."/".$entry_name;
				    $fp = fopen($zip_entry_file, "w");
				    chmod($zip_entry_file,0775);
				    if (zip_entry_open($zip, $zip_entry, "r")) {
				      $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
				      fwrite($fp,"$buf");
				      zip_entry_close($zip_entry);
				      fclose($fp);
				    }
				}
			}

		}
		zip_close($zip);
		$response = array();
		$response['code'] = 0;
		$response['message'] = "All files are processed.";
		die(json_encode($response));
	}
	catch(Exception $e){
		$response = array();
		$response['code'] = 0;
		$response['message'] = "All files are processed.";
		die(json_encode($response));
	}

?>