<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);
$from_url = 'certificate.php';
if(!empty($_GET['from_url'])) {
	$from_url = urldecode($_GET['from_url']);
}

if(!empty($_GET['certuploadid'])) {
    $certuploadid = $_GET['certuploadid'];
    $query = mysqli_query($dbc,"DELETE FROM certificate_uploads WHERE certuploadid='$certuploadid'");
    $certificateid = $_GET['certificateid'];

    echo '<script type="text/javascript"> window.location.replace("add_certificate.php?certificateid='.$certificateid.'&from_url='.$from_url.'"); </script>';
}

if (isset($_POST['add_certificate'])) {
    $contactid = $_POST['contactid'];
    $jobid = $_POST['jobid'];
    $projectid = $_POST['projectid'];
    $client_projectid = $_POST['client_projectid'];
	if($_POST['new_certificate'] != '') {
		$certificate_type = filter_var($_POST['new_certificate'],FILTER_SANITIZE_STRING);
	} else {
		$certificate_type = filter_var($_POST['certificate_type'],FILTER_SANITIZE_STRING);
	}

	if($_POST['new_category'] != '') {
		$category = filter_var($_POST['new_category'],FILTER_SANITIZE_STRING);
	} else {
		$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	}
    $invoice_description = filter_var(htmlentities($_POST['invoice_description']),FILTER_SANITIZE_STRING);
    $ticket_description = filter_var(htmlentities($_POST['ticket_description']),FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $fee = filter_var($_POST['fee'],FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);

    $issue_date = $_POST['issue_date'];
    $reminder_date = $_POST['reminder_date'];
    $expiry_date = $_POST['expiry_date'];

    $certificate_code = filter_var($_POST['certificate_code'],FILTER_SANITIZE_STRING);
    //$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
    $heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
    $cost = filter_var($_POST['cost'],FILTER_SANITIZE_STRING);
    //$description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);

    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);

    if($_POST['same_desc'] == 1) {
        $quote_description = $description;
    } else {
        $quote_description = filter_var(htmlentities($_POST['quote_description']),FILTER_SANITIZE_STRING);
    }
    $final_retail_price = filter_var($_POST['final_retail_price'],FILTER_SANITIZE_STRING);
    $admin_price = filter_var($_POST['admin_price'],FILTER_SANITIZE_STRING);
    $wholesale_price = filter_var($_POST['wholesale_price'],FILTER_SANITIZE_STRING);
    $commercial_price = filter_var($_POST['commercial_price'],FILTER_SANITIZE_STRING);
    $client_price = filter_var($_POST['client_price'],FILTER_SANITIZE_STRING);
    $minimum_billable = filter_var($_POST['minimum_billable'],FILTER_SANITIZE_STRING);
    $estimated_hours = filter_var($_POST['estimated_hours'],FILTER_SANITIZE_STRING);
    $actual_hours = filter_var($_POST['actual_hours'],FILTER_SANITIZE_STRING);
    $msrp = filter_var($_POST['msrp'],FILTER_SANITIZE_STRING);

    $unit_price = filter_var($_POST['unit_price'],FILTER_SANITIZE_STRING);
    $unit_cost = filter_var($_POST['unit_cost'],FILTER_SANITIZE_STRING);
    $rent_price = filter_var($_POST['rent_price'],FILTER_SANITIZE_STRING);
    $rental_days = filter_var($_POST['rental_days'],FILTER_SANITIZE_STRING);
    $rental_weeks = filter_var($_POST['rental_weeks'],FILTER_SANITIZE_STRING);
    $rental_months = filter_var($_POST['rental_months'],FILTER_SANITIZE_STRING);
    $rental_years = filter_var($_POST['rental_years'],FILTER_SANITIZE_STRING);
    $certificate_reminder = filter_var($_POST['certificate_reminder'],FILTER_SANITIZE_STRING);
    $reminder_alert = filter_var($_POST['reminder_alert'],FILTER_SANITIZE_STRING);
    $daily = filter_var($_POST['daily'],FILTER_SANITIZE_STRING);
    $weekly = filter_var($_POST['weekly'],FILTER_SANITIZE_STRING);
    $monthly = filter_var($_POST['monthly'],FILTER_SANITIZE_STRING);
    $annually = filter_var($_POST['annually'],FILTER_SANITIZE_STRING);
    $total_days = filter_var($_POST['total_days'],FILTER_SANITIZE_STRING);
    $total_hours = filter_var($_POST['total_hours'],FILTER_SANITIZE_STRING);
    $total_km = filter_var($_POST['total_km'],FILTER_SANITIZE_STRING);
    $total_miles = filter_var($_POST['total_miles'],FILTER_SANITIZE_STRING);

    if(empty($_POST['certificateid'])) {
       $query_insert_vendor = "INSERT INTO `certificate` (`contactid`, `jobid`, `projectid`, `client_projectid`, `certificate_type`, `category`, `certificate_code`, `heading`, `cost`, `description`, `quote_description`, `invoice_description`, `ticket_description`, `final_retail_price`, `admin_price`, `wholesale_price`, `commercial_price`, `client_price`, `minimum_billable`, `estimated_hours`, `actual_hours`, `msrp`, `name`, `title`,  `fee`, `unit_price`, `unit_cost`, `rent_price`, `rental_days`, `rental_weeks`, `rental_months`, `rental_years`, `certificate_reminder`, `reminder_alert`, `daily`,`weekly`, `monthly`, `annually`,  `total_days`, `total_hours`, `total_km`, `total_miles`, `issue_date`, `reminder_date`, `expiry_date`) VALUES ('$contactid', '$jobid', '$projectid', '$client_projectid', '$certificate_type', '$category', '$certificate_code', '$heading', '$cost', '$description', '$quote_description', '$invoice_description', '$ticket_description', '$final_retail_price', '$admin_price', '$wholesale_price', '$commercial_price', '$client_price', '$minimum_billable', '$estimated_hours', '$actual_hours', '$msrp', '$name', '$title', '$fee', '$unit_price', '$unit_cost', '$rent_price', '$rental_days', '$rental_weeks', '$rental_months', '$rental_years', '$certificate_reminder', '$reminder_alert', '$daily', '$weekly', '$monthly', '$annually', '$total_days', '$total_hours', '$total_km', '$total_miles', '$issue_date', '$reminder_date', '$expiry_date')";

        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $certificateid = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $certificateid = $_POST['certificateid'];
        $query_update_vendor = "UPDATE `certificate` SET `contactid` = '$contactid', `jobid` = '$jobid', `projectid` = '$projectid', `client_projectid` = '$client_projectid', `certificate_type` = '$certificate_type', `category` = '$category',`certificate_code` = '$certificate_code', `heading` = '$heading', `cost` = '$cost', `description` = '$description', `quote_description` = '$quote_description', `invoice_description` = '$invoice_description', `ticket_description` = '$ticket_description', `final_retail_price` = '$final_retail_price', `admin_price` = '$admin_price', `wholesale_price` = '$wholesale_price', `commercial_price` = '$commercial_price', `client_price` = '$client_price', `minimum_billable` = '$minimum_billable', `estimated_hours` = '$estimated_hours', `actual_hours` = '$actual_hours', `msrp` = '$msrp', `name` = '$name', `title` = '$title', `fee` = '$fee', `unit_price` = '$unit_price', `unit_cost` = '$unit_cost', `rent_price` = '$rent_price', `rental_days` = '$rental_days', `rental_weeks` = '$rental_weeks', `rental_months` = '$rental_months', `rental_years` = '$rental_years', `certificate_reminder` = '$certificate_reminder', `reminder_alert` = '$reminder_alert', `daily` = '$daily', `weekly` = '$weekly', `monthly` = '$monthly', `annually` = '$annually', `total_days` = '$total_days', `total_hours` = '$total_hours', `total_km` = '$total_km', `total_miles` = '$total_miles', `issue_date` = '$issue_date', `reminder_date` = '$reminder_date', `expiry_date` = '$expiry_date' WHERE `certificateid` = '$certificateid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        $document = htmlspecialchars($_FILES["upload_document"]["name"][$i], ENT_QUOTES);

        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "download/".$_FILES["upload_document"]["name"][$i]) ;

        if($document != '') {
            $query_insert_client_doc = "INSERT INTO `certificate_uploads` (`certificateid`, `type`, `document_link`) VALUES ('$certificateid', 'Document', '$document')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    for($i = 0; $i < count($_POST['support_link']); $i++) {
        $support_link = $_POST['support_link'][$i];

        if($support_link != '') {
            $query_insert_client_doc = "INSERT INTO `certificate_uploads` (`certificateid`, `type`, `document_link`) VALUES ('$certificateid', 'Link', '$support_link')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }
	
	if(mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `status` FROM `contacts` WHERE `contactid`='$contactid'"))['status'] == 2) {
		$cert_types = explode('#*#',get_config($dbc,'probation_certificates'));
		$incomplete = false;
		foreach($cert_types as $cert_type) {
			if($incomplete && mysqli_query($dbc, "SELECT * FROM `certificate` WHERE `contactid`='$contactid' AND `certificate_type`='$cert_type")->num_rows() == 0) {
				$incomplete = true;
			}
		}
		foreach(explode('#*#',get_config($dbc,'probation_forms')) as $hrid) {
			if($incomplete && mysqli_query($dbc, "SELECT * FROM `hr_attendance` WHERE `done`=1 AND `assign_staffid`='$contactid' AND `hrid`='$hrid")->num_rows() == 0) {
				$incomplete = true;
			}
		}
		if(!$incomplete && in_array($certificate_type,$cert_types)) {
			$recipient = get_config($dbc, 'probation_email');
			if($recipient != '') {
				$staff = get_contact($dbc, $contactid);
				try {
					send_email('', $recipient, '', '', "$staff has completed all necessary forms and certificates to complete probation", "This is to notify you that $staff has completed the necessary certificates and forms to not be on probation.<br />
					<br />
					Please <a href='".WEBSITE_URL."/Staff/staff.php'>log in</a> to the software and review the forms and certificate for $staff.");
				} catch (Exception $e) { }
			}
		}
	}

    echo '<script type="text/javascript"> window.location.replace("'.$from_url.'"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var certificate_type = $("#certificate_type").val();
        var category = $("input[name=category]").val();
        var title = $("input[name=title]").val();
        if (certificate_type == '' || category == '' || title == '' ) {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });

    $("#certificate_type").change(function() {
        if($("#certificate_type option:selected").text() == 'New Certificate') {
                $( "#new_certificate" ).show();
        } else {
            $( "#new_certificate" ).hide();
        }
    });

    $("#category").change(function() {
        if($("#category option:selected").text() == 'New Category') {
                $( "#new_category" ).show();
        } else {
            $( "#new_category" ).hide();
        }
    });

    $('#add_row_doc').on( 'click', function () {
        var clone = $('.additional_doc').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_doc");
        $('#add_here_new_doc').append(clone);
        return false;
    });

    $('#add_row_link').on( 'click', function () {
        var clone = $('.additional_link').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_link");
        $('#add_here_new_link').append(clone);
        return false;
    });

    $.datepicker.setDefaults({
        onSelect: function(value) {
            if(this.id == 'expiry_date') {
                var date = new Date(value);
                date.setDate(date.getDate() - 30);
                $("#reminder_date").datepicker("setDate", date);
            }
        }
    });


});
</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('certificate');
?>
<div class="container">
  <div class="row">

    <h1>
		Certificate<?php
        if(config_visible_function($dbc, 'certificate') == 1) {
            echo '<a href="field_config_certificate.php" class="mobile-block pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
            echo '<span class="popover-examples list-inline pull-right"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
        }
        ?>
	</h1>

	<div class="gap-top double-gap-bottom"><a href="<?php echo $from_url; ?>" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT certificate FROM field_config"));
        $value_config = ','.$get_field_config['certificate'].',';
        $contactid = (!empty($_GET['staffid']) ? $_GET['staffid'] : '');
        $jobid = '';
        $projectid = '';
        $client_projectid = '';
        $certificate_type = '';
        $category = '';
        $certificate_code = '';
        $heading = '';
        $cost = '';
        $description = '';
        $quote_description = '';
        $invoice_description = '';
        $ticket_description = '';

        $final_retail_price = '';
        $name = '';
        $fee = '';
        $admin_price = '';
        $wholesale_price = '';
        $commercial_price = '';
        $client_price = '';
        $minimum_billable = '';
        $estimated_hours = '';
        $actual_hours = '';
        $msrp = '';

        $unit_price = '';
        $unit_cost = '';
        $rent_price = '';
        $rental_days = '';
        $rental_weeks = '';
        $rental_months = '';
        $rental_years = '';
        $certificate_reminder = '';
        $reminder_alert = '';
        $daily = '';
        $weekly = '';
        $monthly = '';
        $annually = '';
        $total_days = '';
        $total_hours = '';
        $total_km = '';
        $total_miles = '';
        $title = '';

        $issue_date = '';
        $reminder_date = '';
        $expiry_date = '';

        if(!empty($_GET['certificateid'])) {

            $certificateid = $_GET['certificateid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM certificate WHERE certificateid='$certificateid'"));
            $contactid = $get_contact['contactid'];
            $jobid = $get_contact['jobid'];
            $projectid = $get_contact['projectid'];
            $client_projectid = $get_contact['client_projectid'];
            $certificate_type = $get_contact['certificate_type'];
            $category = $get_contact['category'];
            $certificate_code = $get_contact['certificate_code'];
            $heading = $get_contact['heading'];
            $cost = $get_contact['cost'];
            $description = $get_contact['description'];
            $quote_description = $get_contact['quote_description'];
            $invoice_description = $get_contact['invoice_description'];
            $ticket_description = $get_contact['ticket_description'];
            $name = $get_contact['name'];
            $title = $get_contact['title'];
            $fee = $get_contact['fee'];

            $issue_date = $get_contact['issue_date'];
            $reminder_date = $get_contact['reminder_date'];
            $expiry_date = $get_contact['expiry_date'];

            $final_retail_price = $get_contact['final_retail_price'];
            $admin_price = $get_contact['admin_price'];
            $wholesale_price = $get_contact['wholesale_price'];
            $commercial_price = $get_contact['commercial_price'];
            $client_price = $get_contact['client_price'];
            $minimum_billable = $get_contact['minimum_billable'];
            $estimated_hours = $get_contact['estimated_hours'];
            $actual_hours = $get_contact['actual_hours'];
            $msrp = $get_contact['msrp'];

            $unit_price = $get_contact['unit_price'];
            $unit_cost = $get_contact['unit_cost'];
            $rent_price = $get_contact['rent_price'];
            $rental_days = $get_contact['rental_days'];
            $rental_weeks = $get_contact['rental_weeks'];
            $rental_months = $get_contact['rental_months'];
            $rental_years = $get_contact['rental_years'];
            $certificate_reminder = $get_contact['certificate_reminder'];
            $reminder_alert = $get_contact['reminder_alert'];
            $daily = $get_contact['daily'];
            $weekly = $get_contact['weekly'];
            $monthly = $get_contact['monthly'];
            $annually = $get_contact['annually'];
            $total_days = $get_contact['total_days'];
            $total_hours = $get_contact['total_hours'];
            $total_km = $get_contact['total_km'];
            $total_miles = $get_contact['total_miles'];
        ?>
        <input type="hidden" id="certificateid" name="certificateid" value="<?php echo $certificateid ?>" />
        <?php   } else if(isset($_GET['clientprojectid'])) {
			$client_projectid = $_GET['clientprojectid'];
		} else if(isset($_GET['projectid'])) {
			$projectid = $_GET['projectid'];
		} else if(isset($_GET['jobid'])) {
			$jobid = $_GET['jobid'];
		}      ?>

        <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { ?>
        <div class="form-group">
          <label for="site_name" class="col-sm-4 control-label">Staff:</label>
          <div class="col-sm-8">
            <select data-placeholder="Select a Staff Member..." name="contactid" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND status = 1"),MYSQLI_ASSOC));
				foreach($query as $id) {
					echo "<option ".($contactid == $id ? 'selected' : '')." value='". $id."'>".get_contact($dbc, $id).'</option>';
				} ?>
            </select>
          </div>
        </div>
        <?php } ?>

        <?php if (strpos($value_config, ','."Projects".',') !== FALSE) { ?>
        <div class="form-group">
          <label for="site_name" class="col-sm-4 control-label">Project:</label>
          <div class="col-sm-8">
            <select data-placeholder="Select a Project..." name="projectid" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php $query = mysqli_query($dbc,"SELECT `projectid`, `project_name` FROM `project` WHERE `deleted`=0");
				while($row = mysqli_fetch_array($query)) {
					echo "<option ".($projectid == $row['projectid'] ? 'selected' : '')." value='". $row['projectid']."'>Project #".$row['projectid'].': '.$row['project_name'].'</option>';
				} ?>
            </select>
          </div>
        </div>
        <?php } ?>

        <?php if (strpos($value_config, ','."Client Project".',') !== FALSE) { ?>
        <div class="form-group">
          <label for="site_name" class="col-sm-4 control-label">Client Project:</label>
          <div class="col-sm-8">
            <select data-placeholder="Select a Client Project..." name="client_projectid" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php $query = mysqli_query($dbc,"SELECT `projectid`, `project_name` FROM `client_project` WHERE `deleted`=0");
				while($row = mysqli_fetch_array($query)) {
					echo "<option ".($client_projectid == $row['projectid'] ? 'selected' : '')." value='". $row['projectid']."'>Project #".$row['projectid'].': '.$row['project_name'].'</option>';
				} ?>
            </select>
          </div>
        </div>
        <?php } ?>

        <?php if (strpos($value_config, ','."Jobs".',') !== FALSE) { ?>
        <div class="form-group">
          <label for="site_name" class="col-sm-4 control-label">Job:</label>
          <div class="col-sm-8">
            <select data-placeholder="Select a Job..." name="jobid" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php $query = mysqli_query($dbc,"SELECT `projectid`, `project_name` FROM `jobs` WHERE `deleted`=0");
				while($row = mysqli_fetch_array($query)) {
					echo "<option ".($jobid == $row['projectid'] ? 'selected' : '')." value='". $row['projectid']."'>Project #".$row['projectid'].': '.$row['project_name'].'</option>';
				} ?>
            </select>
          </div>
        </div>
        <?php } ?>

        <?php if (strpos($value_config, ','."Certificate Type".',') !== FALSE) { ?>
       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Certificate Type<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
            <select id="certificate_type" name="certificate_type" class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <?php
                $query = mysqli_query($dbc,"SELECT distinct(certificate_type) FROM certificate");
				$required_certificates = array_filter(explode('#*#',get_config($dbc, 'probation_certificates')));
                while($row = mysqli_fetch_array($query)) {
                    if ($certificate_type == $row['certificate_type']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
					if($i = array_search($row['certificate_type'],$required_certificates)) {
						unset($required_certificates[$i]);
					}
                    echo "<option ".$selected." value='". $row['certificate_type']."'>".$row['certificate_type'].'</option>';
                }
				foreach($required_certificates as $required_type) {
					echo "<option value='$required_type'>$required_type</option>";
				}
                echo "<option value = 'Other'>New Certificate</option>";
                ?>
            </select>
        </div>
      </div>

       <div class="form-group" id="new_certificate" style="display: none;">
        <label for="travel_task" class="col-sm-4 control-label">New Certificate Type:
        </label>
        <div class="col-sm-8">
            <input name="new_certificate" type="text" class="form-control" />
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>

      <!-- <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="category" value="<?php echo $category; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      -->

       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
            <select id="category" name="category" class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <?php
                $query = mysqli_query($dbc,"SELECT distinct(category) FROM certificate order by category");
                while($row = mysqli_fetch_array($query)) {
                    if ($category == $row['category']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';

                }
                echo "<option value = 'Other'>New Category</option>";
                ?>
            </select>
        </div>
      </div>

       <div class="form-group" id="new_category" style="display: none;">
        <label for="travel_task" class="col-sm-4 control-label">New Category:
        </label>
        <div class="col-sm-8">
            <input name="new_category" type="text" class="form-control" />
        </div>
      </div>

      <?php } ?>

       <?php if (strpos($value_config, ','."Title".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Title<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="title" value="<?php echo $title; ?>" type="text" id="title" class="form-control">
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Issue Date".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Issue Date:</label>
        <div class="col-sm-8">
          <input name="issue_date" value="<?php echo $issue_date; ?>" type="text" class="datepicker">
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Expiry Date".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Expiry Date:</label>
        <div class="col-sm-8">
          <input name="expiry_date" value="<?php echo $expiry_date; ?>" id="expiry_date" type="text" class="datepicker">
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Reminder Date".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Reminder Date:</label>
        <div class="col-sm-8">
          <input name="reminder_date" id="reminder_date" value="<?php echo $reminder_date; ?>" type="text" class="datepicker">
        </div>
      </div>
      <?php } ?>

    <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) {
    ?>

    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">
			<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a></span>
			Upload Document(s):
        </label>
        <div class="col-sm-8">
            <?php
            if(!empty($_GET['certificateid'])) {
                $query_check_credentials = "SELECT * FROM certificate_uploads WHERE certificateid='$certificateid' AND type = 'Document' ORDER BY certuploadid DESC";
                $result = mysqli_query($dbc, $query_check_credentials);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    while($row = mysqli_fetch_array($result)) {
                        $certuploadid = $row['certuploadid'];
                        echo '<ul>';
                        echo '<li><a href="download/'.$row['document_link'].'" target="_blank">'.$row['document_link'].'</a> - <a href="add_certificate.php?certuploadid='.$certuploadid.'&certificateid='.$certificateid.'"> Delete</a></li>';
                        echo '</ul>';
                    }
                }
            }
            ?>
            <div class="enter_cost additional_doc clearfix">
                <div class="clearfix"></div>

                <div class="form-group clearfix">
                    <div class="col-sm-5">
                        <input name="upload_document[]" multiple type="file" data-filename-placement="inside" class="form-control" />
                    </div>
                </div>

            </div>

            <div id="add_here_new_doc"></div>

            <div class="form-group triple-gapped clearfix">
                <div class="col-sm-offset-4 col-sm-8">
                    <button id="add_row_doc" class="btn brand-btn pull-left">Add Another Document</button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($value_config, ','."Link".',') !== FALSE) {
    ?>
    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Link(s):<br><em>(e.g. - https://www.google.com)</em>
        </label>
        <div class="col-sm-8">
            <?php
            if(!empty($_GET['certificateid'])) {
                $query_check_credentials = "SELECT * FROM certificate_uploads WHERE certificateid='$certificateid' AND type = 'Link' ORDER BY certuploadid DESC";
                $result = mysqli_query($dbc, $query_check_credentials);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    $link_no = 1;
                    while($row = mysqli_fetch_array($result)) {
                        $certuploadid = $row['certuploadid'];
                        echo '<ul>';
                        echo '<li><a target="_blank" href=\''.$row['document_link'].'\'">Link '.$link_no.'</a> - <a href="add_certificate.php?certuploadid='.$certuploadid.'&certificateid='.$certificateid.'"> Delete</a></li>';
                        echo '</ul>';
                        $link_no++;
                    }
                }
            }
            ?>
            <div class="enter_cost additional_link clearfix">
                <div class="clearfix"></div>

                <div class="form-group clearfix">
                    <div class="col-sm-5">
                        <input name="support_link[]" type="text" class="form-control">
                    </div>
                </div>

            </div>

            <div id="add_here_new_link"></div>

            <div class="form-group triple-gapped clearfix">
                <div class="col-sm-offset-4 col-sm-8">
                    <button id="add_row_link" class="btn brand-btn pull-left">Add Another Link</button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

       <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Heading:</label>
        <div class="col-sm-8">
          <input name="heading" value="<?php echo $heading; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Name".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Name<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="name" value="<?php echo $name; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Certificate Code".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Certificate Code:</label>
        <div class="col-sm-8">
          <input name="certificate_code" value="<?php echo $certificate_code; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Description:</label>
        <div class="col-sm-8">
          <textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label"></label>
        <div class="col-sm-8">
          <input type="checkbox" value="1" name="same_desc">Check this if Quote Description is same as Description.
        </div>
      </div>

      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Quote Description:</label>
        <div class="col-sm-8">
          <textarea name="quote_description" rows="5" cols="50" class="form-control"><?php echo $quote_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Invoice Description:</label>
        <div class="col-sm-8">
          <textarea name="invoice_description" rows="5" cols="50" class="form-control"><?php echo $invoice_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label"><?= TICKET_NOUN ?> Description:</label>
        <div class="col-sm-8">
          <textarea name="ticket_description" rows="5" cols="50" class="form-control"><?php echo $ticket_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Fee".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Fee<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="fee" value="<?php echo $fee; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Cost:</label>
        <div class="col-sm-8">
          <input name="cost" value="<?php echo $cost; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Final Retail Price:</label>
        <div class="col-sm-8">
          <input name="final_retail_price" value="<?php echo $final_retail_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

        <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Admin Price:</label>
        <div class="col-sm-8">
          <input name="admin_price" value="<?php echo $admin_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Wholesale Price:</label>
        <div class="col-sm-8">
          <input name="wholesale_price" value="<?php echo $wholesale_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Commercial Price:</label>
        <div class="col-sm-8">
          <input name="commercial_price" value="<?php echo $commercial_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Client Price:</label>
        <div class="col-sm-8">
          <input name="client_price" value="<?php echo $client_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Minimum Billable:</label>
        <div class="col-sm-8">
          <input name="minimum_billable" value="<?php echo $minimum_billable; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Estimated Hours:</label>
        <div class="col-sm-8">
          <input name="estimated_hours" value="<?php echo $estimated_hours; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Actual Hours:</label>
        <div class="col-sm-8">
          <input name="actual_hours" value="<?php echo $actual_hours; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">MSRP:</label>
        <div class="col-sm-8">
          <input name="msrp" value="<?php echo $msrp; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

    <?php if (strpos($value_config, ','."Unit Price".',') !== FALSE) { ?>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Unit Price:</label>
    <div class="col-sm-8">
      <input name="unit_price" value="<?php echo $unit_price; ?>" type="text" class="form-control">
    </div>
    </div>
    <?php } ?>

    <?php if (strpos($value_config, ','."Unit Cost".',') !== FALSE) { ?>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Unit Cost:</label>
    <div class="col-sm-8">
      <input name="unit_cost" value="<?php echo $unit_cost; ?>" type="text" class="form-control">
    </div>
    </div>
    <?php } ?>

  <?php if (strpos($value_config, ','."Rent Price".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rent Price:</label>
    <div class="col-sm-8">
      <input name="rent_price" value="<?php echo $rent_price; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>


  <?php if (strpos($value_config, ','."Rental Days".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Days:</label>
    <div class="col-sm-8">
      <input name="rental_days" value="<?php echo $rental_days; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."Rental Weeks".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Weeks:</label>
    <div class="col-sm-8">
      <input name="rental_weeks" value="<?php echo $rental_weeks; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Rental Months".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Months:</label>
    <div class="col-sm-8">
      <input name="rental_months" value="<?php echo $rental_months; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Rental Years".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Years:</label>
    <div class="col-sm-8">
      <input name="rental_years" value="<?php echo $rental_years; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Certificate Reminder Email".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Certificate Reminder Email:</label>
    <div class="col-sm-8">
      <select name="certificate_reminder" class="form-control chosen-select-deselect"><option></option>
		<?php $staff_result = mysqli_query($dbc, "select concat(first_name, ' ', last_name) name, contactid from contacts where category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY."");
		while($row = mysqli_fetch_array($staff_result)) {
			echo "<option ".($certificate_reminder == $row['contactid'] ? "selected " : "")."value='{$row['contactid']}'>".decryptIt($row['name'])."</option>";
		}
		?>
	  </select>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Reminder/Alert".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Reminder/Alert:</label>
    <div class="col-sm-8">
      <input name="reminder_alert" value="<?php echo $reminder_alert; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>


  <?php if (strpos($value_config, ','."Daily".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Daily:</label>
    <div class="col-sm-8">
      <input name="daily" value="<?php echo $daily; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."Weekly".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Weekly:</label>
    <div class="col-sm-8">
      <input name="weekly" value="<?php echo $weekly; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Monthly".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Monthly:</label>
    <div class="col-sm-8">
      <input name="monthly" value="<?php echo $monthly; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Annually".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Annually:</label>
    <div class="col-sm-8">
      <input name="annually" value="<?php echo $annually; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."#Of Days".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Days:</label>
    <div class="col-sm-8">
      <input name="total_days" value="<?php echo $total_days; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."#Of Hours".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Hours:</label>
    <div class="col-sm-8">
      <input name="total_hours" value="<?php echo $total_hours; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."#Of Kilometers".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Kilometers:</label>
    <div class="col-sm-8">
      <input name="total_km" value="<?php echo $total_km; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."#Of Miles".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Miles:</label>
    <div class="col-sm-8">
      <input name="total_miles" value="<?php echo $total_miles; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

        <div class="form-group">
          <div class="col-sm-4">
              <p><span class="hp-red pull-right"><em>Required Fields *</em></span></p>
          </div>
          <div class="col-sm-8"></div>
        </div>

        <div class="form-group">
            <div class="col-sm-12 clearfix">
                <span class="popover-examples list-inline pull-left" style="margin-top:12px;"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Clicking this will not save this entry."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a href="<?php echo $from_url; ?>" class="btn brand-btn btn-lg pull-left">Back</a>
				<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
				<button type="submit" name="add_certificate" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
				<span class="popover-examples list-inline pull-right" style="margin-top:12px;"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to add this entry."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		  </div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
