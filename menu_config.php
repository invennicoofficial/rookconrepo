<?php
/*
Customer Listing
*/
include ('include.php');

error_reporting(0);
?>
<script type="text/javascript">
	function tileclick(sel) {

		var stagee = sel.value;
		var contactid = $('.contacterid').val();

		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "ajax_all.php?fill=tile_menu_choice&contactid="+contactid+"&value="+stagee,
			dataType: "html",   //expect html to be returned
			success: function(response){
				location.reload();
			}
		});

	}

		function classicmenusize(sel) {
		var stagee = sel.value;
		var contactid = $('.contacterid').val();
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "ajax_all.php?fill=tile_menu_settings&settingtype=classic&contactid="+contactid+"&value="+stagee,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});
	}

	function dropdownmenusize(sel) {
		var stagee = sel.value;
		var contactid = $('.contacterid').val();
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "ajax_all.php?fill=tile_menu_settings&settingtype=dropdown&contactid="+contactid+"&value="+stagee,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});
	}

	function tilesize(sel) {
		var stagee = sel.value;
		var contactid = $('.contacterid').val();
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "ajax_all.php?fill=tile_menu_settings&settingtype=tilesize&contactid="+contactid+"&value="+stagee,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});
	}
	
	function newsboardredirect(sel) {
		var stagee = sel.value;
		var contactid = $('.contacterid').val();
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "ajax_all.php?fill=tile_menu_settings&settingtype=newsboardredirect&contactid="+contactid+"&value="+stagee,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});
	}

	function newsboardclick(sel) {
		var newsboard = sel.value;
		var contactid = $('.contacterid').val();

		$.ajax({
			type: "GET",
			url: "ajax_all.php?fill=newsboard_menu_choice&contactid="+contactid+"&value="+newsboard,
			dataType: "html",
			success: function(response){
				location.reload();
			}
		});
	}
$( document ).ready(function() {
	$('.settings-classic').click(function(){
		$('.settings-classic-config').slideToggle(500);
		$('.settings-dropdownmenu-config').hide(500);
		$('.settings-tile-config').hide(500);
		$('.settings-newsboard-config').hide(500);
	});
	$('.settings-dropdown').click(function(){
		$('.settings-dropdownmenu-config').slideToggle(500);
		$('.settings-classic-config').hide(500);
		$('.settings-tile-config').hide(500);
		$('.settings-newsboard-config').hide(500);
	});
	$('.settings-tiles').click(function(){
		$('.settings-tile-config').slideToggle(500);
		$('.settings-dropdownmenu-config').hide(500);
		$('.settings-classic-config').hide(500);
		$('.settings-newsboard-config').hide(500);
	});
	$('.settings-newsboard').click(function(){
		$('.settings-newsboard-config').slideToggle(500);
		$('.settings-dropdownmenu-config').hide(500);
		$('.settings-classic-config').hide(500);
		$('.settings-tile-config').hide(500);
	});
});
</script>
<style>
.settings-classic, .settings-dropdown, .settings-tiles, .settings-newsboard {
	cursor:pointer;
}
.settings-classic-config,.settings-dropdownmenu-config,.settings-tile-config, .settings-newsboard-config {
	width: 100%;
	margin:auto;
	max-width:400px;
	padding:10px;
	background-color:lightgrey;
	color:black;
	top:0px;
	position:relative;
	border: 10px outset grey;
	border-radius:10px;
	margin-bottom:20px;
	padding-bottom: 40px;
}


</style>
</head>
<body>
<?php include_once ('navigation.php');
checkAuthorised();
			$classicmenusize = '';
			$dropdownmenusize = '';
			$newsboardredirect = '';
			$tilesize = '';
			$contactidfortile = $_SESSION['contactid'];
			$result = mysqli_query($dbc, "SELECT * FROM contacts WHERE contactid= '$contactidfortile'");

			while($row = mysqli_fetch_assoc($result)) {
				$software_config2	= $row['software_tile_menu_choice'];
				$newsboard_config	= $row['newsboard_menu_choice'];
			}
			$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM user_settings WHERE contactid='$contactidfortile'"));
			$classicmenusize = $get_config['classic_menu_size'];
			$dropdownmenusize = $get_config['dropdown_menu_size'];
			$newsboardredirect = $get_config['newsboard_redirect'];
			$tilesize = $get_config['tile_size']; ?>
<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">
		<?php include('Settings/settings_navigation.php'); ?>
        <br><br>

			<div class='settings-classic-config' style='display:none;' >
				<h3 style='color:black;text-align:center;'>Classic Menu Settings</h3>
				<hr style='display: block;height: 1px;border: 0; border-top: 1px solid #0e0e0e;'>
				<div style='text-align:left; width:100%;'>
				<h4 style='color:black;'>Size</h4>
				<div style='padding-left:20px;'><span style='position:relative;top:-5px;'>Small: </span><input type='radio' onclick="classicmenusize(this);" name='classicmenusize' style='width:20px; height:20px;' <?php if($classicmenusize == '1' || $classicmenusize == '' || $classicmenusize == NULL) { echo "checked"; } ?> value='1'><br>
				<span style='position:relative;top:-5px;'>Large: </span><input type='radio' onclick="classicmenusize(this);" name='classicmenusize' style='width:20px; height:20px;' <?php if($classicmenusize == '2') { echo "checked"; } ?> value='2'>
				</div>
				<br><center><em>Please refresh your browser to see any changes.</em><br>
				<button type="button" class="btn brand-btn mobile-block settings-classic" >Close</button></center>
				</div>
			</div>

			<div class='settings-dropdownmenu-config' style='display:none;' >
				<h3 style='color:black; text-align:center;'>Drop Down Menu Settings</h3>
				<hr style='display: block;height: 1px;border: 0; border-top: 1px solid #0e0e0e;'>
				<div style='text-align:left; width:100%;'>
				<h4 style='color:black;'>Size</h4>
				<div style='padding-left:20px;'><span style='position:relative;top:-5px;'>Small: </span><input type='radio' onclick="dropdownmenusize(this);" name='dropdownmenusize' style='width:20px; height:20px;' <?php if($dropdownmenusize == '1' || $dropdownmenusize == '' || $dropdownmenusize == NULL) { echo "checked"; } ?> value='1'><br>
				<span style='position:relative;top:-5px;'>Large: </span><input type='radio' onclick="dropdownmenusize(this);" name='dropdownmenusize' style='width:20px; height:20px;' <?php if($dropdownmenusize == '2') { echo "checked"; } ?> value='2'>
				</div>
				<br><center><em>Please refresh your browser to see any changes.</em><br>
				<button type="button" class="btn brand-btn mobile-block settings-dropdown" >Close</button></center>
				</div>
			</div>
			
			<div class='settings-newsboard-config' style='display:none;' >
				<h3 style='color:black; text-align:center;'>Newsboard Settings</h3>
				<hr style='display: block;height: 1px;border: 0; border-top: 1px solid #0e0e0e;'>
				<div style='text-align:left; width:100%;'>
				<h4 style='color:black;'>View on Login</h4>
				<div style='padding-left:20px;'><span style='position:relative;top:-5px;'>Yes: </span><input type='radio' onclick="newsboardredirect(this);" name='newsboardredirect' style='width:20px; height:20px;' <?php if($newsboardredirect == '1') { echo "checked"; } ?> value='1'><br>
				<span style='position:relative;top:-5px;'>No: </span><input type='radio' onclick="newsboardredirect(this);" name='newsboardredirect' style='width:20px; height:20px;' <?php if($newsboardredirect == '2'  || $newsboardredirect == '' || $newsboardredirect == NULL) { echo "checked"; } ?> value='2'>
				</div><center>
				<button type="button" class="btn brand-btn mobile-block settings-newsboard" >Close</button></center>
				</div>
			</div>

			<div class='settings-tile-config' style='display:none;' >
				<h3 style='color:black; text-align:center;'>Tiles Settings</h3>
				<hr style='display: block;height: 1px;border: 0; border-top: 1px solid #0e0e0e;'>
				<div style='text-align:left; width:100%;'>
				<h4 style='color:black;'>Size</h4>
				<div style='padding-left:20px;'>
				<span style='position:relative;top:-5px;'>Extra Small: </span><input type='radio' onclick="tilesize(this);" name='tilesize' style='width:20px; height:20px;' <?php if($tilesize == '1') { echo "checked"; } ?> value='1'><br>
				<span style='position:relative;top:-5px;'>Small: </span><input type='radio' onclick="tilesize(this);" name='tilesize' style='width:20px; height:20px;' <?php if($tilesize == '2') { echo "checked"; } ?> value='2'><br>
				<span style='position:relative;top:-5px;'>Medium: </span><input type='radio' onclick="tilesize(this);" name='tilesize' style='width:20px; height:20px;' <?php if($tilesize == '3' || $tilesize == '' || $tilesize == NULL) { echo "checked"; } ?> value='3'><br>
				<span style='position:relative;top:-5px;'>Large: </span><input type='radio' onclick="tilesize(this);" name='tilesize' style='width:20px; height:20px;' <?php if($tilesize == '4') { echo "checked"; } ?> value='4'>
				</div>
				<br><center><em>Please refresh your browser to see any changes.</em><br>
				<button type="button" class="btn brand-btn mobile-block settings-tiles" >Close</button></center>
				</div>
			</div>

		<div id="">

			<table class='table table-bordered'>
				<tr class=''>
					<th  data-title="Software Style" width="70%">Software Format</th>
					<th  data-title="Activation" width="15%">Activation</th>
					<th width="15%">Settings</th>
				</tr>
				<tr>
					<td>Tile Menu</td>
					<td><input type='radio' onclick="tileclick(this);" name='stylertiles' style='width:20px; height:20px;' <?php if($software_config2 == '') { echo "checked"; } ?> value=''></td>
					<td><center><img style='width: 25px;' src='img/icons/settings-4.png' class='settings-tiles wiggle-me'></center></td>
				</tr>
				<tr>
					<td>Drop Down Menu & Tile Menu</td>
					<td><input type='radio' onclick="tileclick(this);" name='stylertiles' style='width:20px; height:20px;' <?php if($software_config2 == '1') { echo "checked"; } ?> value='1'></td>
					<td><center><img style='width: 25px;' src='img/icons/settings-4.png' class='settings-dropdown wiggle-me'></center></td>
				</tr>
				<tr>
					<td>Classic Software Menu</td>
					<td><input type='radio' onclick="tileclick(this);" name='stylertiles' style='width:20px; height:20px;' <?php if($software_config2 == '2') { echo "checked"; } ?> value='2'></td>
					<td><center><img style='width: 25px;' src='img/icons/settings-4.png' class='settings-classic wiggle-me'></center></td>
				</tr>
				<!--<tr>
					<td>Sticky Navigation (Right)</td>
					<td><input type='radio' onclick="tileclick(this);" name='stylertiles' style='width:20px; height:20px;' <?php // if($software_config2 == '3') { echo "checked"; } ?> value='3'></td>
				</tr>-->


			</table>

			<table class="table table-bordered">
				<tr class="">
					<th data-title="Newsboard" width="55%">Newsboard</th>
					<th data-title="Activate" width="15%">Activate</th>
					<th data-title="Disable" width="15%">Disable</th>
					<th data-title="Settings" width="15%">Settings</th>
				</tr>
				<tr>
					<td>Newsboard Menu</td>
					<td><input type="radio" onclick="newsboardclick(this);" name="newsboard" style="width:20px; height:20px;" <?php echo ($newsboard_config == '1') ? "checked" : ""; ?> value="1"></td>
					<td><input type="radio" onclick="newsboardclick(this);" name="newsboard" style="width:20px; height:20px;" <?php echo ($newsboard_config == NULL) ? "checked" : ""; ?> value=""></td>
					<td><center><img style='width: 25px;' src='img/icons/settings-4.png' class='settings-newsboard wiggle-me' style='cursor:pointer;'></center></td>
				</tr>
			</table>
		</div>
		<input type='hidden' value='<?php echo $_SESSION['contactid']; ?>' class='contacterid'>
		<input type='hidden' value='<?php echo $_SESSION['newsboard_menu_choice']; ?>'>
        </div>
    </div>
</div>
<?php include ('footer.php'); ?>