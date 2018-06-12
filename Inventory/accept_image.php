<?php
	/*
	 * This file accepts the images sent from websites (Cannpedia)
	 */
 
	include('../database_connection.php');
			
	$uploaddir	= realpath('./download') . '/';
	$uploadfile	= $uploaddir . basename($_FILES['file_contents']['name']);

	if ( 0 < $_FILES['file_contents']['error'] ) {
		/*
		$file_error  = 'Page: ' . $_SERVER['SERVER_NAME'] . '/Intake/accept_pdf.php' . '<br />';
		$file_error .= 'PDF Submitted From: ' . '<br>';
		$file_error .= 'Error: ' . $_FILES['file_contents']['error'] . '<br />';
		mail('jaylahiru@freshfocusmedia.com', 'Intake Tile - PDF Transfer Error', $file_error, $headers);
		echo $file_error;
		*/
	} else {
		if (move_uploaded_file($_FILES['file_contents']['tmp_name'], $uploadfile)) {
			//echo "File is valid, and was successfully uploaded.\n";
		} else {
			//echo "Possible file upload attack!\n";
		}
	}
	
	/*
	echo '<br /><br />Here is some more debugging info:<br />';
	print_r($_FILES);
	echo "\n<hr />\n";
	print_r($_POST);
	*/
?>