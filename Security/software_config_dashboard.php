<?php
	/*
	 * Title:		Software Dashboard Settings
	 * Function:	Settings for Dashboard permissions within a main Tile
	 */
	
	include ('../include.php');
	error_reporting(0);
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
			window.location = 'software_config_dashboard.php?tile='+tile+'&level='+security_level;
		}
		
		
		/* Called whenever a turn on/off radio button is clicked */
		function dashboardPermissionConfig(sel) {
			var tile		= $("#tile").val();
			var level_url	= $("#level_url").val();
			
			//Alert if a Security Level is not selected from the dropdown menu
			if (level_url === '') {
				alert("Please select a Security Level from the dropdown menu first.");
				window.location.reload();
			
			} else {
				var type = sel.type;
				var field = sel.name;
				var fieldid = field.replace(/ /gi, '_');
				var field_value = sel.value;
				var final_value = '*';

				if($("#"+fieldid+"_turn_on").is(":checked")) {
					final_value += 'turn_on*';
				}
				if($("#"+fieldid+"_turn_off").is(":checked")) {
					final_value += 'turn_off*';
				}

				var isTurnOff = $("#"+fieldid+"_turn_off").is(':checked');
				if(isTurnOff) {
				   var turnOff = name;
				} else {
					var turnOff = '';
				}

				var isTurnOn = $("#"+fieldid+"_turn_on").is(':checked');
				if(isTurnOn) {
				   var turnOn = name;
				} else {
					var turnOn = '';
				}

				$.ajax({
					type: "GET",
					url: "../ajax_all.php?fill=dashboard_permission_config&tile="+tile+"&level="+level_url+"&field="+field+"&value="+final_value+"&turnOff="+turnOff+"&turnOn="+turnOn,
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
		include_once ('../navigation.php');
checkAuthorised('security');
	?>
	
	<div class="container triple-pad-bottom">
		<div class="row">
			<div class="col-md-12">

				<?php /* Set headings */
				switch($tile) {
				case 'labour':
					echo '<h2 class="double-pad-bottom">Labour Dashboard Permissions</h2>';
					break;
				default:
					echo '<h2 class="double-pad-bottom">Dashboard Permissions</h2>';
					break;
				} ?>
				
				<!-- Populate security level -->
				<div class="form-group">
					<label for="travel_task" class="col-sm-5 control-label">Select the Security Level you wish to set sub tab access privileges to:</label>
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
							<option value=""></option><?php foreach(get_security_levels($dbc) as $security_name => $security_level) { ?>
								<option <?= $security_level == 'super' ? 'disabled' : '' ?> <?= $security_level == $level_url ? 'selected' : '' ?> value="<?= $security_level ?>"><?= $security_name ?></option>
							<?php } ?>
						</select>
					</div><!-- .col-sm-6 -->
				</div><!-- .form-group -->
			
				<table class="table table-bordered">
					<!-- Table headers -->
					<tr class="hidden-sm">
						<th width="40%">Dashboard Field</th>
						<th width="20%">Enable Field</th>
						<th width="20%">Disable Field</th>
						<th width="20%">Last Date Edited</th>
					</tr><?php
					
					/* Labour dashboard settings */
					if ( $tile == 'labour' ) {
                        $value_config = ',Labour Type,Heading,'.get_field_config($dbc, 'labour_dashboard').',';
                        if (strpos($value_config, ','."Labour Code".',') !== FALSE) {
                            echo '<tr><td>Labour Code</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Labour Code').'</tr>';
                        }
                        if (strpos($value_config, ','."Labour Type".',') !== FALSE) {
                            echo '<tr><td>Labour Type</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Labour Type').'</tr>';
                        }
                        if (strpos($value_config, ','."Category".',') !== FALSE) {
                            echo '<tr><td>Category</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Category').'</tr>';
                        }
                        if (strpos($value_config, ','."Heading".',') !== FALSE) {
                            echo '<tr><td>Heading</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Heading').'</tr>';
                        }
                        if (strpos($value_config, ','."Name".',') !== FALSE) {
                            echo '<tr><td>Name</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Name').'</tr>';
                        }
                        if (strpos($value_config, ','."Description".',') !== FALSE) {
                            echo '<tr><td>Description</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Description').'</tr>';
                        }
                        if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
                            echo '<tr><td>Quote Description</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Quote Description').'</tr>';
                        }
                        if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                            echo '<tr><td>Invoice Description</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Invoice Description').'</tr>';
                        }
                        if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                            echo '<tr><td>'.TICKET_NOUN.' Description</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Ticket Description').'</tr>';
                        }
                        if (strpos($value_config, ','."WCB".',') !== FALSE) {
                            echo '<tr><td>WCB</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'WCB').'</tr>';
                        }
                        if (strpos($value_config, ','."Benefits".',') !== FALSE) {
                            echo '<tr><td>Benefits</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Benefits').'</tr>';
                        }
                        if (strpos($value_config, ','."Salary".',') !== FALSE) {
                            echo '<tr><td>Salary</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Salary').'</tr>';
                        }
                        if (strpos($value_config, ','."Bonus".',') !== FALSE) {
                            echo '<tr><td>Bonus</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Bonus').'</tr>';
                        }
                        if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) {
                            echo '<tr><td>Minimum Billable</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Minimum Billable').'</tr>';
                        }
                        if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) {
                            echo '<tr><td>Estimated Hours</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Estimated Hours').'</tr>';
                        }
                        if (strpos($value_config, ','."Actual Hours".',') !== FALSE) {
                            echo '<tr><td>Actual Hours</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Actual Hours').'</tr>';
                        }
                        if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                            echo '<tr><td>MSRP</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'MSRP').'</tr>';
                        }
                        if (strpos($value_config, ','."Rate Card Price".',') !== FALSE) {
                            echo '<tr><td>Rate Card Price</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Rate Card Price').'</tr>';
                        }
                        if (strpos($value_config, ','."Rate Card".',') !== FALSE) {
                            echo '<tr><td>Rate Card</td>';
                            echo dashboard_config_function( $dbc, $tile, $level_url, 'Rate Card').'</tr>';
                        }
                    }

                    /* Labour dashboard settings */
					if ( $tile == 'staff' ) {
                        echo '<tr><td>Staff Rate Card Price</td>';
                        echo dashboard_config_function( $dbc, $tile, $level_url, 'Staff Rate Card Price').'</tr>';
                        echo '<tr><td>Staff Rate Card</td>';
                        echo dashboard_config_function( $dbc, $tile, $level_url, 'Staff Rate Card').'</tr>';
                        echo '<tr><td>Positions Rate Card Price</td>';
                        echo dashboard_config_function( $dbc, $tile, $level_url, 'Positions Rate Card Price').'</tr>';
                        echo '<tr><td>Positions Rate Card</td>';
                        echo dashboard_config_function( $dbc, $tile, $level_url, 'Positions Rate Card').'</tr>';
                    } ?>

				</table>
				
				<div class="double-pad-top"><a class="btn brand-btn btn-lg" href="security.php?tab=privileges<?php if(isset($_GET['level']) && $_GET['level'] !== '') { echo '&level='.$_GET['level']; } ?>">Back</a></div>
				
			</div><!-- .col-md-12 -->
        </div><!-- .row -->
    </div><!-- .container -->
	
<?php include ('../footer.php'); ?>