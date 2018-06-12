<?php
/*
Customer Listing
*/

if (isset($_POST['add_favicon'])) {
	require( dirname( __FILE__ ) . '/class-php-ico.php' );

	if (!file_exists('favicon_upload')) {
		mkdir('favicon_upload', 0777, true);
	}
	$pos_logo = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]), ENT_QUOTES);
	$target_dir = "../Admin Settings/favicon_upload/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


	//Generate favicon name
		$length = 50;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';

		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		echo '<script type="text/javascript"> alert("Sorry, only JPG, JPEG, PNG, & GIF files are allowed."); </script>';
		exit();
	}
	$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='favicon_upload'"));
	if($get_config['configid'] > 0) {
		if($pos_logo == '') {
			$logo_update = $_POST['logo_file'];
			$logo_update = htmlspecialchars($logo_update, ENT_QUOTES);
		} else {
			$logo_update = $pos_logo;
		}

		move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],"../Admin Settings/favicon_upload/" . $logo_update);
		$target_file = "../Admin Settings/favicon_upload/" . $logo_update;
		$query_update_employee = "UPDATE `general_configuration` SET value = '$logo_update' WHERE name='favicon_upload'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
		$query_update_employee = "UPDATE `general_configuration` SET value = '$randomString' WHERE name='favicon_upload_ico'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
	} else {
		move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../Admin Settings/favicon_upload/" . $_FILES["fileToUpload"]["name"]) ;
		$target_file = "../Admin Settings/favicon_upload/" . $_FILES["fileToUpload"]["name"];
		$query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('favicon_upload', '$pos_logo')";
		$result_insert_config = mysqli_query($dbc, $query_insert_config);
		$query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('favicon_upload_ico', '$randomString')";
		$result_insert_config = mysqli_query($dbc, $query_insert_config);
	}
		// Convert to a favicon

		$destination = '../Admin Settings/favicon_upload/'.$randomString.'.ico';
		if (file_exists($destination)) {
			unlink ($destination);
		}

		$source = $target_file;
		$sizes = array(
			array( 16, 16 ),
			array( 24, 24 ),
			array( 32, 32 ),
			array( 48, 48 ),
		);

		$ico_lib = new PHP_ICO( $source, $sizes );
		$ico_lib->save_ico( $destination );
}

if (isset($_POST['delete_favicon'])) {
	$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='favicon_upload'"));
	if($get_config['configid'] > 0) {
		$query_update_employee = "UPDATE `general_configuration` SET value = '' WHERE name='favicon_upload'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
	}
}

$notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab='setting_url_favicon'"));
$note = $notes['note'];
    
if ( !empty($note) ) { ?>
    <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="../img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11">
            <span class="notice-name">NOTE:</span>
            <?= $note; ?>
        </div>
        <div class="clearfix"></div>
    </div><?php
} ?>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="form-group">
		<label for="fax_number"	class="col-sm-4	control-label">Upload Favicon Image:</label>
		<div class="col-sm-8">
		 <?php
			$pos_logo = get_config($dbc, 'favicon_upload');
			if($pos_logo != '') {
				echo '<a href="'.WEBSITE_URL.'/Admin Settings/favicon_upload/'.$pos_logo.'" target="_blank">View Favicon Image</a> &nbsp;&nbsp;';
				echo '<button	type="submit" name="delete_favicon" onClick="var conf = confirm(\'Are you sure?\'); if(!conf) { return false; }" value="delete_favicon" class="btn config-btn btn-sm">Revert to Default Favicon</button>';
			}
		  ?>
		  <input type="hidden" name="logo_file" value="<?php echo $pos_logo; ?>" />
		  <input type="file" accept="image/*" name="fileToUpload" id="fileToUpload">
		</div>
	</div>
	<br><br>
	<div class="form-group">
		<!--<div class="col-sm-4 clearfix">
			<a href="contacts.php?category=Business&filter=Top" class="btn config-btn pull-right">Back</a>--
			<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
		</div>-->
		<div class="col-sm-12">
			<button	type="submit" name="add_favicon" value="add_favicon" class="btn config-btn btn-lg pull-right">Submit</button>
		</div>
	</div>
</form>