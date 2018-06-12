<?php 
	
	$contacterid = $_SESSION['contactid'];
	$result = mysqli_query($dbc, "SELECT * FROM contacts WHERE contactid= '$contacterid'");
	while($row = mysqli_fetch_assoc($result)) {
		$role = $row['role'];
		$level_url = $role;
	}
	//check if user is allowed on the Settings tile:
	$result = mysqli_query($dbc, "SELECT * FROM security_privileges WHERE tile = 'software_config' AND level = '$role'");
	while($row = mysqli_fetch_assoc($result)) {
		$privies = $row['privileges'];
		if (strpos($privies, 'hide') !== false && stripos(','.$role.',',',super,') !== false) {
			header('location: home.php');
		}
	}
	//
	$resulter = mysqli_query($dbc, "SELECT * FROM `subtab_config` WHERE `tile`='software_config' AND `security_level`='$level_url' AND `subtab`='enable_disable_tiles'");
	if(mysqli_num_rows($resulter) > 0) {
		$row = mysqli_fetch_assoc ($resulter);
		$arr = explode('*#*',trim($row['status']));
		if($arr[0] == '*turn_on*') { 
			$turn_on_edt = 'turn_on';
		} else {
			$turn_on_edt = '';
		}
	} else {
		$turn_on_edt = 'turn_on';
	}
	$resulter = mysqli_query($dbc, "SELECT * FROM `subtab_config` WHERE `tile`='software_config' AND `security_level`='$level_url' AND `subtab`='security_levels'");
	if(mysqli_num_rows($resulter) > 0) {
		$row = mysqli_fetch_assoc ($resulter);
		$arr = explode('*#*',trim($row['status']));
		if($arr[0] == '*turn_on*') { 
			$turn_on_asl = 'turn_on';
		} else {
			$turn_on_asl = '';
		}
	} else {
		$turn_on_asl = 'turn_on';
	}
	$resulter = mysqli_query($dbc, "SELECT * FROM `subtab_config` WHERE `tile`='software_config' AND `security_level`='$level_url' AND `subtab`='security_privileges'");
	if(mysqli_num_rows($resulter) > 0) {
		$row = mysqli_fetch_assoc ($resulter);
		$arr = explode('*#*',trim($row['status']));
		if($arr[0] == '*turn_on*') { 
			$turn_on_ssp = 'turn_on';
		} else {
			$turn_on_ssp = '';
		}
	} else {
		$turn_on_ssp = 'turn_on';
	}
	$resulter = mysqli_query($dbc, "SELECT * FROM `subtab_config` WHERE `tile`='software_config' AND `security_level`='$level_url' AND `subtab`='software_style'");
	if(mysqli_num_rows($resulter) > 0) {
		$row = mysqli_fetch_assoc ($resulter);
		$arr = explode('*#*',trim($row['status']));
		if($arr[0] == '*turn_on*') { 
			$turn_on_ss = 'turn_on';
		} else {
			$turn_on_ss = '';
		}
	} else {
		$turn_on_ss = 'turn_on';
	}
	$resulter = mysqli_query($dbc, "SELECT * FROM `subtab_config` WHERE `tile`='software_config' AND `security_level`='$level_url' AND `subtab`='software_format'");
	if(mysqli_num_rows($resulter) > 0) {
		$row = mysqli_fetch_assoc ($resulter);
		$arr = explode('*#*',trim($row['status']));
		if($arr[0] == '*turn_on*') { 
			$turn_on_sf = 'turn_on';
		} else {
			$turn_on_sf = '';
		}
	} else {
		$turn_on_sf = 'turn_on';
	}
	$active_tab = '';
	$active_tab2 = '';
	$active_tab3 = '';
	$active_tab4 = '';
	$active_tab5 = '';
	$tile_nom = basename($_SERVER["SCRIPT_FILENAME"], '.php');
	if($tile_nom == 'software_config') {
		$active_tab = 'active_tab';
	} else if($tile_nom == 'security_levels') {
		$active_tab2 = 'active_tab';
	} else if($tile_nom == 'security_privileges' || $tile_nom == 'software_config_subtabs') {
		$active_tab3 = 'active_tab';
	} else if($tile_nom == 'style_config') {
		$active_tab4 = 'active_tab';
	} else if($tile_nom == 'menu_config') {
		$active_tab5 = 'active_tab';
	}
?>				
				<div class="mobile-100-container">
					<?php if($turn_on_edt == 'turn_on') { ?>
						<a href="software_config.php"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_tab; ?>">Enable/Disable Tiles</button></a>
					<?php } else if($tile_nom == 'software_config') {
						header('location: security_levels.php');
						die();
					}?>
					<?php if($turn_on_asl == 'turn_on') { ?>
						<a href="security_levels.php"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_tab2; ?>">Activate Security Levels</button></a>
					<?php } else if($tile_nom == 'security_levels') {
						header('location: security_privileges.php');
						die();
					} ?>
					<?php if($turn_on_ssp == 'turn_on') { ?>
						<a href="security_privileges.php"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_tab3; ?>">Set Security Privileges</button></a>
					<?php } else if($tile_nom == 'security_privileges' || $tile_nom == 'software_config_subtabs') {
						header('location: style_config.php');
						die();
					} ?>
					<?php if($turn_on_ss == 'turn_on') { ?>
						<a href="style_config.php"><button type="button" class="btn brand-btn mobile-block  mobile-100 <?php echo $active_tab4; ?>">Software Style</button></a>
					<?php } else if($tile_nom == 'style_config') {
						header('location: menu_config.php');
						die();
					} ?>
					<?php if($turn_on_sf == 'turn_on') { ?>
						<a href="menu_config.php"><button type="button" class="btn brand-btn mobile-block  mobile-100 <?php echo $active_tab5; ?>">Software Format</button></a>
					<?php } else if($tile_nom == 'menu_config') {
						header('location: home.php');
						die();
					} ?>
				</div>