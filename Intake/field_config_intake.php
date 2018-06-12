<?php
	/*
	 * Intake Settings Dashboard
	 */
	
	include ('../include.php');
	checkAuthorised('intake');
	error_reporting(0);

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) ) {
		$intake_dashboard  = implode(',', $_POST['intake_dashboard']);
        $intake_dashboard .= ','.$_POST['intake_assign'];
        $intake_dashboard .= ','.$_POST['intake_create'];
        $intake_dashboard .= ','.$_POST['intake_project'];
        $intake_dashboard .= ','.$_POST['intake_archive'];
		
		$query_update	= "UPDATE `field_config` SET `intake_dashboard` = '{$intake_dashboard}' WHERE `fieldconfigid` = 1";
		$result_update	= mysqli_query($dbc, $query_update);

		echo '<script type="text/javascript"> window.location.replace("field_config_intake.php");</script>';
	} ?>
	
	<script>
		$(document).ready(function(){
			$("#selectall").change(function(){
			  $("input[name='intake_dashboard[]']").prop('checked', $(this).prop("checked"));
			});
		});
	</script>
</head>

<body>
	<?php include ('../navigation.php'); ?>

	<div class="container">
		<div class="row">
			<h1>Intake Forms</h1>
			<div class="pad-left gap-top double-gap-bottom"><a href="intake.php" class="btn config-btn">Back to Dashboard</a></div>

		    <div class="tab-container">
		        <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure your settings for Web Intake Forms."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config.php?tab=web"><button type="button" class="btn brand-btn mobile-block <?= ($_GET['tab'] == 'web' || empty($_GET['tab']) ? 'active_tab' : '') ?>">Web Forms</button></a></div>

		        <div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this to configure your settings for Software Intake FOrms."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><a href="field_config.php?tab=software"><button type="button" class="btn brand-btn mobile-block <?= $_GET['tab'] == 'software' ? 'active_tab' : '' ?>">Software Forms</button></a></div>
		    </div>

		    <div class="clearfix"></div>

			<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

				<div class="panel-group" id="accordion2">

					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dashboard" >
									Choose Fields for Intake Forms Dashboard<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_dashboard" class="panel-collapse collapse">
							<div class="panel-body"><?php
								$get_field_config	= mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `intake_dashboard` FROM `field_config` WHERE `fieldconfigid`=1" ) );
								$value_config		= ',' . $get_field_config['intake_dashboard'] . ','; ?>
								
								<div id="no-more-tables">
									<input type="checkbox" id="selectall" />&nbsp;&nbsp;Select All<br />
									<br />
									<table border="2" cellpadding="10" class="table">
										<tr>
											<td>
												<input type="checkbox" <?php if (strpos($value_config, ',Form ID,') !== false) { echo " checked"; } ?> value="Form ID" name="intake_dashboard[]">&nbsp;&nbsp;Form ID
											</td>
											<td>
												<input type="checkbox" <?php if (strpos($value_config, ',Category,') !== false) { echo " checked"; } ?> value="Category" name="intake_dashboard[]">&nbsp;&nbsp;Category
											</td>
											<td>
												<input type="checkbox" <?php if (strpos($value_config, ',Name,') !== false) { echo " checked"; } ?> value="Name" name="intake_dashboard[]">&nbsp;&nbsp;Name
											</td>
											<td>
												<input type="checkbox" <?php if (strpos($value_config, ',Email,') !== false) { echo " checked"; } ?> value="Email" name="intake_dashboard[]">&nbsp;&nbsp;Email
											</td>
                                        </tr>
                                        <tr>
											<td>
												<input type="checkbox" <?php if (strpos($value_config, ',Phone,') !== false) { echo " checked"; } ?> value="Phone" name="intake_dashboard[]">&nbsp;&nbsp;Phone
											</td>
											<td>
												<input type="checkbox" <?php if (strpos($value_config, ',Received Date,') !== false) { echo " checked"; } ?> value="Received Date" name="intake_dashboard[]">&nbsp;&nbsp;Received Date
											</td>
											<td>
												<input type="checkbox" <?php if (strpos($value_config, ',PDF Form,') !== false) { echo " checked"; } ?> value="PDF Form" name="intake_dashboard[]">&nbsp;&nbsp;PDF Form
											</td>
										</tr>
									</table>
							   </div><!-- #no-more-tables -->
							   
							</div><!-- .panel-body -->
						</div><!-- #collapse_dashboard -->
					</div><!-- .panel .panel-default -->

					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_function" >
									Choose Functions for Intake Forms<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_function" class="panel-collapse collapse">
							<div class="panel-body"><?php
								$get_field_config	= mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `intake_dashboard` FROM `field_config` WHERE `fieldconfigid`=1" ) );
								$value_config		= ',' . $get_field_config['intake_dashboard'] . ','; ?>
                                
								<div class="form-group">
									<label class="col-sm-4 control-label">Assign Intake Form To Contact</label>
									<div class="col-sm-8">
										<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Assign,') !== false || preg_match('(,Hide Assign,|,New Injury Existing Patient,)', $value_config) === 0 ) { echo " checked"; } ?> value="Assign" name="intake_assign">Assign To A Profile</label>
										<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',New Injury Existing Patient,') !== false ) { echo " checked"; } ?> value="New Injury Existing Patient" name="intake_assign">New Injury Existing Patient</label>
										<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Hide Assign,') !== false ) { echo " checked"; } ?> value="Hide Assign" name="intake_assign">Hide</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Create New Contact With Intake Form</label>
									<div class="col-sm-8">
										<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Create,') !== false || preg_match('(,New Injury New Patient,|,Hide Create,)', $value_config) === 0 ) { echo " checked"; } ?> value="Create" name="intake_create">Create New Profile</label>
										<label class="form-checkbox"><input type="radio" <?php if (strpos($value_config, ',New Injury New Patient,') !== false) { echo " checked"; } ?> value="New Injury New Patient" name="intake_create">New Injury New Patient</label>
										<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Hide Create,') !== false ) { echo " checked"; } ?> value="Hide Create" name="intake_create">Hide</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Create New <?= PROJECT_NOUN ?></label>
									<div class="col-sm-8">
										<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Project,') !== false || preg_match('(,Assign Project,|,Hide Project,)', $value_config) === 0 ) { echo " checked"; } ?> value="Project" name="intake_project">Create New <?= PROJECT_NOUN ?></label>
										<!--<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Assign Project,') !== false ) { echo " checked"; } ?> value="Assign Project" name="intake_project">Assign to Project</label>-->
										<br />
                                        <label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Hide Project,') !== false ) { echo " checked"; } ?> value="Hide Project" name="intake_project">Hide</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Archive Submission</label>
									<div class="col-sm-8">
										<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Project,') !== false || preg_match('(,Archive Submission,|,Hide Archive,)', $value_config) === 0 ) { echo " checked"; } ?> value="Archive" name="intake_archive">Archive</label>
										<br />
                                        <label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Hide Archive,') !== false ) { echo " checked"; } ?> value="Hide Archive" name="intake_archive">Hide</label>
									</div>
								</div>
							</div><!-- .panel-body -->
						</div><!-- #collapse_dashboard -->
					</div><!-- .panel .panel-default -->
                    
				</div><!-- .panel-group -->

				<div class="form-group">
					<div class="col-sm-6">
						<a href="intake.php" class="btn config-btn btn-lg">Back</a>
						<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
					</div>
					<div class="col-sm-6">
						<button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
					</div>
				</div>

			</form>
		
		</div><!-- .row -->
	</div><!-- .container -->

<?php include ('../footer.php'); ?>