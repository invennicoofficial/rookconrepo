<?php
/*
Add Meeting Note FFM client
*/
include ('database_connection.php');
include ('global.php');
include ('header.php');
require('calendar/tc_calendar.php');

if (isset($_POST['submit_meeting'])) {
	$type = 'Agenda';
	$clientid = $_POST['clientid'];
	$client_uniqueid = $_POST['client_uniqueid'];
	$meeting_date = $_POST['meeting_date'];
	$ser = implode('*#*',$_POST['service']);
	$service = '*#*'.$ser.'*#*';
	$ss = implode('*#*',$_POST['sub_service']);
	$sub_service = '*#*'.$ss.'*#*';
	$agenda_notes = $_POST['agenda_notes'];
	$contain_s = "'";
	$contain_d = '"';
	$agenda_notes = str_replace($contain_s,"*single*",$agenda_notes);
	$agenda_notes = str_replace($contain_d,"*double*",$agenda_notes);

	$userid = ','.implode(',',$_POST['userid']).',';
	$contactid = ','.implode(',',$_POST['contactid']).',';
	$agenda_send_email = $_POST['agenda_send_email'];

	$client_email = implode(', ',$_POST['client_email']);

	$agenda_email_list = $_POST['agenda_email_list'].', '.$client_email;
    $added_by = $_SESSION['userid'];

	$client = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT company_name FROM clients WHERE clientid='$clientid'"));
	$client = $client['company_name'];

	if(empty($_POST['agenda_meetingid'])) {
		$query_insert_agenda = "INSERT INTO `client_agenda_meetingnote` (`clientid`, `client`, `client_uniqueid`, `type`, `meeting_date`, `service`, `sub_service`, `agenda_notes`, `userid`, `contactid`, `agenda_send_email`, `agenda_email_list`, `added_by`) VALUES ('$clientid', '$client', '$client_uniqueid', '$type', '$meeting_date', '$service', '$sub_service', '$agenda_notes', '$userid', '$contactid', '$agenda_send_email', '$agenda_email_list', '$added_by')";

		$result_insert_agenda = mysqli_query($dbc, $query_insert_agenda);
		$agenda_meetingid = mysqli_insert_id($dbc);

	} else {
		$agenda_meetingid = $_POST['agenda_meetingid'];
		$query_update = "UPDATE client_agenda_meetingnote SET meeting_date='$meeting_date', service='$service', sub_service='$sub_service', agenda_notes='$agenda_notes', userid='$userid', contactid='$contactid', agenda_send_email = '$agenda_send_email', agenda_email_list = '$agenda_email_list' WHERE agenda_meetingid='$agenda_meetingid'";
        $result_update = mysqli_query($dbc, $query_update);
	}

	// Email
	if($agenda_send_email == 1) {
		$company_name = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT company_name FROM clients WHERE clientid = '$clientid'"));
		$user_list = '';
		$user_email = '';
		for($i=0; $i<count($_POST['userid']); $i++) {
			$userid = $_POST['userid'][$i];
			$user = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT first_name, last_name, user_email FROM users WHERE userid = '$userid'"));
			$user_list .= $user['first_name'].' '.$user['last_name'].'<br>';
			$user_email .= $user['user_email'].',';
		}

		$contact_list = '';
        $contact_email = '';
		for($i=0; $i<count($_POST['contactid']); $i++) {
			$contactid = $_POST['contactid'][$i];
			$contact = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT first_name, last_name, email FROM client_contact_info WHERE contactid = '$contactid'"));
			$contact_list .= $contact['first_name'].' '.$contact['last_name'].'<br>';
            $contact_email .= $contact['email'].',';
		}

		$to = $agenda_email_list.', '.$user_email.', '.$contact_email;

		$subject = 'Agenda Note';

		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$message = '<html><body>';
		$message .= "<b>Meeting Date : </b>".$meeting_date ."<br/><br/>";
		$message .= "<b>Business : </b>". $company_name['company_name'] ."<br/><br/>";
		$message .= "<b>Business Unique ID : </b>".$client_uniqueid ."<br/><br/>";
		$message .= "<b>Service : </b><br/>".implode('<br>',$_POST['service']) ."<br/><br/>";
		$message .= "<b>Sub Service : </b><br/>".implode('<br>',$_POST['sub_service']) ."<br/><br/>";
		$message .= "<b>Agenda Note : </b><br/>".$_POST['agenda_notes'] ."<br/><br/>";
		$message .= "<b>FFM staff : </b><br/>".$user_list ."<br/><br/>";
		$message .= "<b>Contacts : </b><br/>".$contact_list ."<br/><hr><br/>";
		$message .= '<img src="http://www.freshfocussoftware.com/img/ffm-signature.png" width="154" height="77" border="0" alt="">';
		$message .= '</body></html>';
		$message .= '</body></html>';

		mail($to, $subject, $message, $headers);
	}
	// Email

	header('Location: client_tile_agenda_meeting.php');

   //     mysqli_close($dbc);//Close the DB Connection
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#clientid").change(function() {
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "ajax_all.php?fill=project&clientid="+this.value,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#client_uniqueid').html(response);
				$("#client_uniqueid").trigger("change.select2");
			}
		});

		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "ajax_all.php?fill=email&clientid="+this.value,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('.client_email').replaceWith(response);
			}
		});

		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "ajax_all.php?fill=contact&clientid="+this.value,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#client_contact').html(response);
				$("#client_contact").trigger("change.select2");
			}
		});

	});

    $(".popover-examples a").tooltip({
        placement : 'top',
        trigger: 'hover'
    });

});

tinymce.init({
    selector: "textarea",
    theme: "modern",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}
    ]
});

</script>
<style type="text/css">
	.chosen-container{ max-width: 380px !important; }
</style>
</head>
<body>
<?php include_once ('navigation.php');
?>

<div class="container">
    <div class="row">
		<div class="col-md-12">
        <form id="form1" name="form1" method="post" action="add_client_agenda_meeting.php" enctype="multipart/form-data" class="form-horizontal" role="form">

		<?php
		$clientid = '';
        $client_uniqueid = '';
        $meeting_date = '';
		$service = '';
		$sub_service = '';
		$agenda_notes = '';
        $userid = '';
        $contactid = '';

		if(!empty($_GET['agenda_meetingid']))	{
			$agenda_meetingid = $_GET['agenda_meetingid'];
			$get_agenda = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	client_agenda_meetingnote WHERE agenda_meetingid='$agenda_meetingid'"));

			$clientid = $get_agenda['clientid'];
			$get_client = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT company_name FROM clients WHERE clientid='$clientid'"));
            $company_name = $get_client['company_name'];

			$client_uniqueid = $get_agenda['client_uniqueid'];
			$meeting_date = $get_agenda['meeting_date'];
			$service = $get_agenda['service'];
			$sub_service = $get_agenda['sub_service'];
			$agenda_notes = $get_agenda['agenda_notes'];
			$userid = $get_agenda['userid'];
			$contactid = $get_agenda['contactid'];

			$contain_s = "*single*";
			$contain_d = '*double*';
			$agenda_notes = str_replace($contain_s,"'",$agenda_notes);
			$agenda_notes = str_replace($contain_d,'"',$agenda_notes);

		?>
		<input type="hidden" name="clientid" value="<?php echo $clientid; ?>">
		<input type="hidden" name="client_uniqueid" value="<?php echo $client_uniqueid; ?>">
		<input type="hidden" name="agenda_meetingid" value="<?php echo $agenda_meetingid; ?>">
		<?php } ?>

        <h1 class="triple-pad-bottom">Agenda/Meeting</h1>

        <div class="panel-group" id="accordion2">

		<!-- Display Business and Agenda info  -->

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
                            Business Info<span class="glyphicon glyphicon-minus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_info" class="panel-collapse collapse in">
                    <div class="panel-body">

					<?php if(empty($_GET['agenda_meetingid'])) { ?>
						<div class="form-group">
						  <label for="site_name" class="col-sm-4 control-label">Business Name<span class="text-red">*</span>:</label>
						  <div class="col-sm-8">
							<select data-placeholder="Choose a Business..." id="clientid" name="clientid" class="chosen-select-deselect form-control" required width="380">
							  <option value=""></option>
							  <?php
								$query = mysqli_query($dbc,"SELECT distinct(company_name), clientid FROM clients order by company_name");
								while($row = mysqli_fetch_array($query)) {
									echo "<option value='". $row['clientid']."'>".$row['company_name'].'</option>';
								}
							  ?>
							</select>
						  </div>
						</div>

						  <div class="form-group">
							<label for="site_name" class="col-sm-4 control-label"></label>
							<div class="col-sm-8">
							  <a href="add_client.php?from_client=meetingnote" target="_blank" class="btn brand-btn">Add New Business</a>
							</div>
						  </div>

						<div class="form-group">
							<label for="site_name" class="col-sm-4 control-label">Business Unique ID:</label>
							<div class="col-sm-8">
								<select id="client_uniqueid" data-placeholder="Choose a ID..." name="client_uniqueid" class="chosen-select-deselect form-control" width="380">
									<option value=""></option>
								</select>
							</div>
						</div>

						<?php } else { ?>

						<div class="form-group">
						  <label for="site_name" class="col-sm-4 control-label">Business Name:</label>
						  <div class="col-sm-8">
								<?php echo $company_name; ?>
						  </div>
						</div>

						<div class="form-group">
						  <label for="site_name" class="col-sm-4 control-label">Business Unique ID:</label>
						  <div class="col-sm-8">
								<?php echo $client_uniqueid; ?>
						  </div>
						</div>

						<?php }  ?>

					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_meeting_info" > Agenda Information<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_meeting_info" class="panel-collapse collapse">
					<div class="panel-body">

						<div class="form-group">
							<label for="site_name" class="col-sm-4 control-label">Meeting Date:</label>
							<div class="col-sm-8">
							  <?php
								if(($meeting_date != 'NULL') && ($meeting_date != '')) {
									$comp_date = explode("-",$meeting_date);
									$day = $comp_date[2];
									$month = $comp_date[1];
									$year = $comp_date[0];
								}
								$myCalendar = new tc_calendar("meeting_date", true, false);
								$myCalendar->setIcon("calendar/images/iconCalendar.gif");
								$myCalendar->setPath("calendar/");
								if(($meeting_date != 'NULL') && ($meeting_date != '')) {
									$myCalendar->setDate($day, $month, $year);
								}
								$myCalendar->setYearInterval(2000, 2020);
								//$myCalendar->dateAllow('2008-05-13', date('Y-m-d'));
								$myCalendar->setAlignment('left', 'bottom');
								//$myCalendar->autoSubmit(true, "form1");
								$myCalendar->writeScript()
							  ?>
							</div>
						</div>

						<div class="form-group">
						  <label for="site_name" class="col-sm-4 control-label">Service:</label>
						  <div class="col-sm-8">
							<select multiple data-placeholder="Service..." id="service" name="service[]" class="chosen-select-deselect form-control" width="380">
							  <option value=""></option>
							  <?php
								$query = mysqli_query($dbc,"SELECT distinct(main_service) FROM product_services WHERE deleted=0 AND main_service!='Ticket Heading' order by main_service");
								while($row = mysqli_fetch_array($query)) {
									?>
									<option <?php if (strpos($service, '*#*'.$row['main_service'].'*#*') !== FALSE) {
									echo " selected='selected'"; } ?> value="<?php echo $row['main_service'] ?>" ><?php echo $row['main_service'] ?></option>
									<?php
									}
							    ?>
							</select>
						  </div>
						</div>

						<div class="form-group">
							<label for="site_name" class="col-sm-4 control-label">Sub Service:</label>
							<div class="col-sm-8">
								<select id="sub_service" multiple data-placeholder="Subservice..." name="sub_service[]" class="chosen-select-deselect form-control" width="380">
								  <option value=""></option>
								  <?php
									$query = mysqli_query($dbc,"SELECT distinct(sub_service) FROM product_services WHERE deleted=0 AND main_service!='Ticket Heading' order by sub_service");

									while($row = mysqli_fetch_array($query)) {
										?>
										<option <?php if (strpos($sub_service, '*#*'.$row['sub_service'].'*#*') !== FALSE) {
										echo " selected='selected'"; } ?> value="<?php echo $row['sub_service'] ?>" ><?php echo $row['sub_service'] ?></option>
										<?php
										}
									?>
							  </select>
							</div>
						</div>

					  <div class="form-group">
						<label for="site_name" class="col-sm-4 control-label">Agenda Notes:</label>
						<div class="col-sm-8">
						  <textarea name="agenda_notes" rows="4" cols="50" class="form-control" ><?php echo $agenda_notes;?> </textarea>
						</div>
					  </div>

					  <div class="form-group">
						<label for="site_name" class="col-sm-4 control-label">Staff:</label>
						<div class="col-sm-8">
							<select data-placeholder="Staff..." name="userid[]" class="chosen-select-deselect form-control" multiple width="380">
							  <option value=""></option>
							  <?php
								$query = mysqli_query($dbc,"SELECT userid, first_name, last_name FROM users WHERE userid NOT IN(1,2) order by first_name");
								while($row = mysqli_fetch_array($query)) {
									?>
									<option <?php if (strpos($userid, ','.$row['userid'].',') !== false) { echo  'selected="selected"'; } ?> value='<?php echo $row['userid']; ?>' ><?php echo decryptIt($row['first_name']).' '.decryptIt($row['last_name']); ?></option>
								<?php }
							  ?>
							</select>
						</div>
					  </div>

					  <?php if(empty($_GET['agenda_meetingid'])) { ?>
						<div class="form-group">
							<label for="site_name" class="col-sm-4 control-label">Business Contact:</label>
							<div class="col-sm-8">
								<select id="client_contact" data-placeholder="Contact..." name="contactid[]" class="chosen-select-deselect form-control" multiple width="380">
									<option value=""></option>
								</select>
							</div>
						</div>
						<?php } else { ?>
						<div class="form-group">
							<label for="site_name" class="col-sm-4 control-label">Business Contact:</label>
							<div class="col-sm-8">
								<select id="client_contact" data-placeholder="Contact..." name="contactid[]" class="chosen-select-deselect form-control" multiple width="380">
								  <option value=""></option>
								  <?php
									$query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM client_contact_info WHERE clientid = '$clientid' order by first_name");
									while($row = mysqli_fetch_array($query)) {
										?>
										<option <?php if (strpos($contactid, ','.$row['contactid'].',') !== false) { echo  'selected="selected"'; } ?> value='<?php echo $row['contactid']; ?>' ><?php echo decryptIt($row['first_name']).' '.decryptIt($row['last_name']); ?></option>
									<?php }
								  ?>
								</select>
							</div>
						</div>
						<?php } ?>

					    <div class="form-group">
							<label for="site_name" class="col-sm-4 control-label">Email Notes:<br><em>(Please select this if you want to send email)</em></label>
							<div class="col-sm-8">
							  <div class="checkbox">
									<label><input type="checkbox" value="1" name="agenda_send_email"></label>
								</div>
							</div>
					    </div>

						<?php if(empty($_GET['agenda_meetingid'])) { ?>
						<div class="form-group">
							<label for="site_name" class="col-sm-4 control-label">Business Email:<br><em>(Choose to send email)</em></label>
							<div class="col-sm-8">
								<span class="client_email"></span>
							</div>
						</div>
						<?php } else { ?>
						<div class="form-group">
							<label for="site_name" class="col-sm-4 control-label">Business Email:<br><em>(Choose to send email)</em></label>
							<div class="col-sm-8">
								<div class="radio">
								<?php
								$query = mysqli_query($dbc,"SELECT email FROM client_contact_info WHERE clientid = '$clientid'");
								while($row = mysqli_fetch_array($query)) {
									$email = $row['email'];
									if($email != '') {
										echo '<label class="pad-right"><input type="checkbox" name="client_email[]" value="'.$email.'">&nbsp;'.$row['email'].'</label>';
									}
								}
								?>

								</div>
							</div>
						</div>
						<?php } ?>

						<div class="form-group">
							<label for="additional_note" class="col-sm-4 control-label">Additional Email(s) to send Note:<br><em>(Comma separated)</em></label>
							<div class="col-sm-8">
								<input name="agenda_email_list" type="text" class="form-control" />
							</div>
						</div>

					</div>
				</div>
			</div>

    		</div>

			<div class="form-group">
			  <div class="col-sm-4">
				  <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
			  </div>
			  <div class="col-sm-8"></div>
			</div>

		</div>

		<?php if(empty($_GET['clientaction'])) { ?>
		<div class="form-group">
			<div class="col-sm-4 clearfix">
				<a href="client_tile_agenda_meeting.php" class="btn brand-btn mobile-block pull-right">Back</a>
			</div>
			<div class="col-sm-8">
				<button type="submit" name="submit_meeting" value="Submit" class="btn brand-btn mobile-block btn-lg pull-right">Submit</button>
			</div>
		</div>
		<?php } else { ?>
		<div class="form-group">
			<div class="col-sm-4 clearfix">
				<a href="worklog.php" class="btn brand-btn mobile-block pull-right">Back</a>
			</div>
		</div>
		<?php } ?>

		</form>

        <?php include ('add_checklist.php'); ?>
     </div>


    </div>
  </div>
</div>

<?php include ('footer.php'); ?>
