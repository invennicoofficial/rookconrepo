<?php
	/*
	 * Title:		Software Subtab Settings
	 * Function:	Settings for Subtabs within a main Tile
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
			window.location = 'software_config_subtabs.php?tile='+tile+'&level='+security_level;
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
				case 'staff':
					echo '<h2 class="double-pad-bottom">Staff Field Permissions</h2>';
					break;
				default:
					echo '<h2 class="double-pad-bottom">Field Permissions</h2>';
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

				<?php switch($tile) {
					case 'staff':
						include('../Security/software_config_fields_staff.php');
						break;
				} ?>
				
				<div class="double-pad-top"><a class="btn brand-btn btn-lg" href="security.php?tab=privileges<?php if(isset($_GET['level']) && $_GET['level'] !== '') { echo '&level='.$_GET['level']; } ?>">Back</a></div>
				
			</div><!-- .col-md-12 -->
        </div><!-- .row -->
    </div><!-- .container -->
	
<?php include ('../footer.php'); ?>