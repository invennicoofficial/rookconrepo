<?php
	/*
	 * Title:		Software Subtab Settings
	 * Function:	Settings for Subtabs within a main Tile
	 */
	
	include ('include.php');
	
	/* Check and set the $tile variable */
	if ( isset ( $_GET[ 'tile' ] ) ) {
		$tile = trim ( $_GET[ 'tile' ] );
		echo '<input type="hidden" name="tile" id="tile" value="'. $tile .'" />';
	} else {
		header( "Location: software_config.php" );
	}
?>

	<script type="text/javascript">
		$(document).on('change', 'select[name="sub_category"]', function() { changeLevel(this); });
		
		/* Called whenever the Security Level dropdown menu is changed */
		function changeLevel(sel) {
			var security_level = sel.value;
			var tile = $("#tile").val();
			window.location = 'software_config_subtabs.php?tile='+tile+'&level='+security_level;
		}
		
		
		/* Called whenever a turn on/off radio button is clicked */
		function subtabConfig(sel) {
			var tile		= $("#tile").val();
			var level_url	= $("#level_url").val();
			
			//Alert if a Security Level is not selected from the dropdown menu
			if (level_url === '') {
				alert("Please select a Security Level from the dropdown menu first.");
				window.location.reload();
			
			} else {
				var type = sel.type;
				var subtab = sel.name;
				var subtab_value = sel.value;
				var final_value = '*';

				if($("#"+subtab+"_turn_on").is(":checked")) {
					final_value += 'turn_on*';
				}
				if($("#"+subtab+"_turn_off").is(":checked")) {
					final_value += 'turn_off*';
				}

				var isTurnOff = $("#"+subtab+"_turn_off").is(':checked');
				if(isTurnOff) {
				   var turnOff = name;
				} else {
					var turnOff = '';
				}

				var isTurnOn = $("#"+subtab+"_turn_on").is(':checked');
				if(isTurnOn) {
				   var turnOn = name;
				} else {
					var turnOn = '';
				}

				$.ajax({
					type: "GET",
					url: "ajax_all.php?fill=subtab_config&tile="+tile+"&level="+level_url+"&subtab="+subtab+"&value="+final_value+"&turnOff="+turnOff+"&turnOn="+turnOn,
					dataType: "html",   //expect html to be returned
					success: function(response){
						console.log(response);
						window.location.reload();
					}
				});
			}
		}
	</script>
</head>

<body>
	<?php
		include_once ('navigation.php');
		checkAuthorised();
	?>
	
	<div class="container triple-pad-bottom">
		<div class="row">
			<div class="col-md-12">

				<?php include('Settings/settings_navigation.php');
				
				/* Set headings */
				if ( $tile == 'contacts' ) {
					echo '<h2 class="double-pad-bottom">Contacts Subtab Settings</h2>';
				}
				if ( $tile == 'software_config' ) {
					echo '<h2 class="double-pad-bottom">Settings Subtab Permissions</h2>';
				}?>
				
				<!-- Populate security level -->
				<div class="form-group">
					<label for="travel_task" class="col-sm-5 control-label">Select the Security Level you wish to set subtab access privileges to:</label>
					<div class="col-sm-7 double-pad-bottom">
						
						<?php
							if ( !empty ( $_GET[ 'level' ] ) ) {
								$level_url = $_GET[ 'level' ];
							
							} else {
								$contacterid	= $_SESSION['contactid'];
								$result			= mysqli_query ( $dbc, "SELECT * FROM contacts WHERE contactid='$contacterid'" );
								
								while ( $row = mysqli_fetch_assoc( $result ) ) {
									$role = $row[ 'role' ];
								}
								
								$level_url = (stripos(','.$role.',',',super,') !== false) ? 'admin' : $role;
							}
						?>
						<input type="hidden" name="level_url" id="level_url" value="<?= $level_url; ?>" />
						
						<select id="sub_category" name="sub_category" class="chosen-select-deselect form-control" width="380">
							<option value=""></option><?php
							
							$selected = '';
							$disabled = '';
						
							$sql = mysqli_query ( $dbc, "SELECT * FROM  security_level" );
							$on_security = '';

							while ( $fieldinfo = mysqli_fetch_field ( $sql ) ) {
								$field_name = $fieldinfo->name;
								$get_config = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT $field_name FROM security_level WHERE $field_name LIKE '%*turn_on*%'" ) );
								if ( $get_config[$field_name] ) {
									$on_security[] = $field_name;
								}
							}
							
							foreach ( $on_security as $category => $value ) {
								$select_value = get_securitylevel ( $dbc, $value );
								
								if ( $value == $level_url ) {
									$selected = ' selected';
								} else {
									$selected = '';
								}
								
								if ( $value == 'super' ) {
									$disabled = ' disabled';
								} else {
									$disabled = '';
								} ?>
								
								<option <?php echo $selected.' '.$disabled; ?> value="<?php echo $value; ?>"><?php echo $select_value; ?></option><?php
							} ?>
						</select>
					</div><!-- .col-sm-6 -->
				</div><!-- .form-group -->
			
				<table class="table table-bordered">
					<!-- Table headers -->
					<tr class="hidden-sm">
						<th width="40%">Subtab Name</th>
						<th width="20%">Enable Subtab</th>
						<th width="20%">Disable Subtab</th>
						<th width="20%">Last Date Edited</th>
					</tr><?php
						
					/* Contacts tile subtab settings */
					if ( $tile == 'contacts' ) {
						$tabs		= get_config ( $dbc, 'contacts_tabs' );
						$each_tab	= explode ( ',', $tabs );
						foreach ( $each_tab as $subtab ) {
							echo '<tr>';
								echo '<td>' . $subtab . '</td>';
								echo subtab_config_function( $dbc, $tile, $level_url, $subtab );
							echo '</tr>';
						}
					}
					/* End Contacts tile subtab settigns */ 
					/* Settings tile subtab settings */
					include ('Settings/settings_subtab_settings.php');
					/* End of Settings tile subtab settings */
					?>
					
				</table>
				
				<div class="double-pad-top pull-right"><a class="btn brand-btn btn-lg" href="security_privileges.php<?php if(isset($_GET['level']) && $_GET['level'] !== '') { echo '?level='.$_GET['level']; } ?>">Back</a></div>
				
			</div><!-- .col-md-12 -->
        </div><!-- .row -->
    </div><!-- .container -->
	
<?php include ('footer.php'); ?>