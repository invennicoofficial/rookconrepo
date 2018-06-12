<?php 		// Used in the Software_config_subtabs.php //
if ( $tile == 'software_config' ) { 
	$resulter = mysqli_query($dbc, "SELECT * FROM `subtab_config` WHERE `tile`='$tile' AND `security_level`='$level_url' AND `subtab`='enable_disable_tiles'");
	if(mysqli_num_rows($resulter) > 0) {
		$row = mysqli_fetch_assoc ($resulter);
		$arr = explode('*#*',trim($row['status']));
		if($arr[0] == '*turn_on*') { 
			$turn_on = 'checked';
			$turn_off = '';	
		} else {
			$turn_off = 'checked';	
			$turn_on = '';
		}
		
	} else {
		$turn_on = 'checked';
		$turn_off = '';
	} ?>
	<tr>
		<td align="center">Enable/Disable Tiles</td>
		<td align="center"><input type="radio" name="enable_disable_tiles" id="enable_disable_tiles_turn_on" value="turn_on" <?php echo $turn_on; ?> onchange="subtabConfig(this)" /></td>
		<td align="center"><input type="radio" name="enable_disable_tiles" id="enable_disable_tiles_turn_off" value="turn_off" <?php echo $turn_off; ?> onchange="subtabConfig(this)" /></td>
		<td align="center"><?php echo $arr[1]; ?></td>
	</tr>
<?php } 

if ( $tile == 'software_config' ) { 
	$resulter = mysqli_query($dbc, "SELECT * FROM `subtab_config` WHERE `tile`='$tile' AND `security_level`='$level_url' AND `subtab`='security_levels'");
	if(mysqli_num_rows($resulter) > 0) {
		$row = mysqli_fetch_assoc ($resulter);
		$arr = explode('*#*',trim($row['status']));
		if($arr[0] == '*turn_on*') { 
			$turn_on = 'checked';
			$turn_off = '';	
		} else {
			$turn_off = 'checked';	
			$turn_on = '';
		}
		
	} else {
		$turn_on = 'checked';
		$turn_off = '';
	} ?>
	<tr>
		<td align="center">Activate Security Levels</td>
		<td align="center"><input type="radio" name="security_levels" id="security_levels_turn_on" value="turn_on" <?php echo $turn_on; ?> onchange="subtabConfig(this)" /></td>
		<td align="center"><input type="radio" name="security_levels" id="security_levels_turn_off" value="turn_off" <?php echo $turn_off; ?> onchange="subtabConfig(this)" /></td>
		<td align="center"><?php echo $arr[1]; ?></td>
	</tr>
<?php } 

if ( $tile == 'software_config' ) { 
	$resulter = mysqli_query($dbc, "SELECT * FROM `subtab_config` WHERE `tile`='$tile' AND `security_level`='$level_url' AND `subtab`='security_privileges'");
	if(mysqli_num_rows($resulter) > 0) {
		$row = mysqli_fetch_assoc ($resulter);
		$arr = explode('*#*',trim($row['status']));
		if($arr[0] == '*turn_on*') { 
			$turn_on = 'checked';
			$turn_off = '';	
		} else {
			$turn_off = 'checked';	
			$turn_on = '';
		}
		
	} else {
		$turn_on = 'checked';
		$turn_off = '';
	} ?>
	<tr>
		<td align="center">Set Security Privileges</td>
		<td align="center"><input type="radio" name="security_privileges" id="security_privileges_turn_on" value="turn_on" <?php echo $turn_on; ?> onchange="subtabConfig(this)" /></td>
		<td align="center"><input type="radio" name="security_privileges" id="security_privileges_turn_off" value="turn_off" <?php echo $turn_off; ?> onchange="subtabConfig(this)" /></td>
		<td align="center"><?php echo $arr[1]; ?></td>
	</tr>
<?php } 

if ( $tile == 'software_config' ) { 
	$resulter = mysqli_query($dbc, "SELECT * FROM `subtab_config` WHERE `tile`='$tile' AND `security_level`='$level_url' AND `subtab`='software_style'");
	if(mysqli_num_rows($resulter) > 0) {
		$row = mysqli_fetch_assoc ($resulter);
		$arr = explode('*#*',trim($row['status']));
		if($arr[0] == '*turn_on*') { 
			$turn_on = 'checked';
			$turn_off = '';	
		} else {
			$turn_off = 'checked';	
			$turn_on = '';
		}
		
	} else {
		$turn_on = 'checked';
		$turn_off = '';
	} ?>
	<tr>
		<td align="center">Software Style</td>
		<td align="center"><input type="radio" name="software_style" id="software_style_turn_on" value="turn_on" <?php echo $turn_on; ?> onchange="subtabConfig(this)" /></td>
		<td align="center"><input type="radio" name="software_style" id="software_style_turn_off" value="turn_off" <?php echo $turn_off; ?> onchange="subtabConfig(this)" /></td>
		<td align="center"><?php echo $arr[1]; ?></td>
	</tr>
<?php } 

if ( $tile == 'software_config' ) { 
	$resulter = mysqli_query($dbc, "SELECT * FROM `subtab_config` WHERE `tile`='$tile' AND `security_level`='$level_url' AND `subtab`='software_format'");
	if(mysqli_num_rows($resulter) > 0) {
		$row = mysqli_fetch_assoc ($resulter);
		$arr = explode('*#*',trim($row['status']));
		if($arr[0] == '*turn_on*') { 
			$turn_on = 'checked';
			$turn_off = '';	
		} else {
			$turn_off = 'checked';	
			$turn_on = '';
		}
		
	} else {
		$turn_on = 'checked';
		$turn_off = '';
	} ?>
	<tr>
		<td align="center">Software Format</td>
		<td align="center"><input type="radio" name="software_format" id="software_format_turn_on" value="turn_on" <?php echo $turn_on; ?> onchange="subtabConfig(this)" /></td>
		<td align="center"><input type="radio" name="software_format" id="software_format_turn_off" value="turn_off" <?php echo $turn_off; ?> onchange="subtabConfig(this)" /></td>
		<td align="center"><?php echo $arr[1]; ?></td>
	</tr>
<?php } 