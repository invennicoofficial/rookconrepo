<?php 
error_reporting(0);
?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        //var projectclientid = $("#projectclientid").val();
        var budget_name = $("input[name=budget_name]").val();
        var category = $("#category").val();
		var staff_lead = $("#staff_lead").val();
		var staff_co_lead = $("#staff_co_lead").val();
        var businessid = $("#businessid").val();
		var site = $("#site").val();
		var budget_created = $("#budget_created").val();
		var start_date = $("#start_date").val();
		var finish_date = $("#finish_date").val();
		
        if (businessid == '' || budget_name == '' || staff_lead == '' || staff_co_lead == '' || site == '' || budget_created == '' || start_date == '' || finish_date == '') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });
});
</script>
<script type='text/javascript'>
$(document).ready(function() {	
	
	$("#business").change(function() {	
	if($("#business option:selected").text() == 'New Business') {
			$( "#new_business" ).show();
	} else {
		$( "#new_business" ).hide();
	}

	var businessid = this.value;

	/*$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "call_log_ajax_all.php?fill=assigncontact&businessid="+businessid,
		dataType: "html",   //expect html to be returned
		success: function(response){
			var arr = response.split('**#**');
			$('#call_log_contact').html(arr[0]);
			$("#call_log_contact").trigger("change.select2");
		}
	});*/

});

	//Product Misc
	var add_new_p_misc = 1;
	$('#deleteproductsmisc_0').hide();
	$('#add_row_p_misc').on( 'click', function () {

		$('#deleteproductsmisc_0').show();
        var clone_misc = $('.additional_p_misc').clone();
        clone_misc.find('.form-control').val('');
		clone_misc.find('#category_0').attr('id', 'category_'+add_new_p_misc);
		clone_misc.find('#expense_0').attr('id', 'expense_'+add_new_p_misc);
		clone_misc.find('#daily_0').attr('id', 'daily_'+add_new_p_misc);
		clone_misc.find('#weekly_0').attr('id', 'weekly_'+add_new_p_misc);
		clone_misc.find('#monthly_0').attr('id', 'monthly_'+add_new_p_misc);
		clone_misc.find('#q1_0').attr('id', 'q1_'+add_new_p_misc);
		clone_misc.find('#q2_0').attr('id', 'q2_'+add_new_p_misc);
		clone_misc.find('#q3_0').attr('id', 'q3_'+add_new_p_misc);
		clone_misc.find('#q4_0').attr('id', 'q4_'+add_new_p_misc);
        clone_misc.find('#annually_0').attr('id', 'annually_'+add_new_p_misc);
		clone_misc.find('#productsmisc_0').attr('id', 'productsmisc_'+add_new_p_misc);
        clone_misc.find('#deleteproductsmisc_0').attr('id', 'deleteproductsmisc_'+add_new_p_misc);
        $('#deleteproductsmisc_0').hide();

        clone_misc.removeClass("additional_p_misc");

        $('#add_here_new_p_misc').append(clone_misc);

        add_new_p_misc++;
		if(add_new_p_misc > 8) {
			$('.products_heading').show();
		}

        return false;
    });
});

function deleteEstimate(sel, hide, blank) {
	var typeId = sel.id;
	var arr = typeId.split('_');
	$("#"+hide+arr[1]).hide();
    $("#"+blank+arr[1]).val('');
}

function changeOther(other)
{
	var typeId = other.id;
	var arr = typeId.split('_');
	if(arr[0] == 'daily')
	{
		var dailyVal = jQuery('#'+typeId).val();
		var weeklyVal = dailyVal * 7;
		var monthlyVal = weeklyVal * 4;
		var qVal = monthlyVal * 3;
		var annualVal = qVal * 4;
		jQuery('#weekly_' + arr[1]).val(weeklyVal);
		jQuery('#monthly_' + arr[1]).val(monthlyVal);
		jQuery('#q1_' + arr[1]).val(qVal);
		jQuery('#q2_' + arr[1]).val(qVal);
		jQuery('#q3_' + arr[1]).val(qVal);
		jQuery('#q4_' + arr[1]).val(qVal);
		jQuery('#annually_' + arr[1]).val(annualVal);
	}
	else if(arr[0] == 'weekly')
	{
		var weeklyVal = jQuery('#'+typeId).val();
		var dailyVal = weeklyVal / 7;
		var monthlyVal = weeklyVal * 4;
		var qVal = monthlyVal * 3;
		var annualVal = qVal * 4;
		jQuery('#daily_' + arr[1]).val(dailyVal);
		jQuery('#monthly_' + arr[1]).val(monthlyVal);
		jQuery('#q1_' + arr[1]).val(qVal);
		jQuery('#q2_' + arr[1]).val(qVal);
		jQuery('#q3_' + arr[1]).val(qVal);
		jQuery('#q4_' + arr[1]).val(qVal);
		jQuery('#annually_' + arr[1]).val(annualVal);
	}
	else if(arr[0] == 'monthly')
	{
		var monthlyVal = jQuery('#'+typeId).val();
		var weeklyVal = monthlyVal / 4;
		var dailyVal = weeklyVal / 7;
		var qVal = monthlyVal * 3;
		var annualVal = qVal * 4;
		jQuery('#daily_' + arr[1]).val(dailyVal);
		jQuery('#weekly_' + arr[1]).val(weeklyVal);
		jQuery('#q1_' + arr[1]).val(qVal);
		jQuery('#q2_' + arr[1]).val(qVal);
		jQuery('#q3_' + arr[1]).val(qVal);
		jQuery('#q4_' + arr[1]).val(qVal);
		jQuery('#annually_' + arr[1]).val(annualVal);
	}
	else if(arr[0] == 'q1' || arr[0] == 'q2' || arr[0] == 'q3' || arr[0] == 'q4')
	{
		var qVal = jQuery('#'+typeId).val();
		var monthlyVal = qVal / 3;
		var weeklyVal = monthlyVal / 4;
		var dailyVal = weeklyVal / 7;
		var annualVal = qVal * 4;
		jQuery('#daily_' + arr[1]).val(dailyVal);
		jQuery('#weekly_' + arr[1]).val(weeklyVal);
		jQuery('#monthly_' + arr[1]).val(monthlyVal);
		jQuery('#q1_' + arr[1]).val(qVal);
		jQuery('#q2_' + arr[1]).val(qVal);
		jQuery('#q3_' + arr[1]).val(qVal);
		jQuery('#q4_' + arr[1]).val(qVal);
		jQuery('#annually_' + arr[1]).val(annualVal);
	}
	else if(arr[0] == 'annually')
	{
		var annualVal = jQuery('#'+typeId).val();
		var qVal = annualVal / 4;
		var monthlyVal = qVal / 3;
		var weeklyVal = monthlyVal / 4;
		var dailyVal = weeklyVal / 7;
		jQuery('#daily_' + arr[1]).val(dailyVal);
		jQuery('#weekly_' + arr[1]).val(weeklyVal);
		jQuery('#monthly_' + arr[1]).val(monthlyVal);
		jQuery('#q1_' + arr[1]).val(qVal);
		jQuery('#q2_' + arr[1]).val(qVal);
		jQuery('#q3_' + arr[1]).val(qVal);
		jQuery('#q4_' + arr[1]).val(qVal);
	}
}

</script>
<?php
/*
Add Budget
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['add_budget'])) {
	if($_POST['add_budget'] == 'Save') {
		$status = 0;
	}
	elseif($_POST['add_budget'] == 'Submit') {
		$status = 1;
	}
	
	$created_date = date('Y-m-d');
    //$created_by = $_SESSION['contactid'];

    $m = 0;
	
	if($_POST['new_business'] != '') {
		$name = encryptIt($_POST['new_business']);
        $query_insert_inventory = "INSERT INTO `contacts` (`category`, `name`) VALUES ('Budget Contact', '$name')";
        $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
        $businessid = mysqli_insert_id($dbc);
        $m = 1;
	} else {
        $businessid = $_POST['business'];
	}

	if($_POST['new_contact'] != '') {
		$first_name = encryptIt($_POST['new_contact']);
        $query_insert_inventory = "INSERT INTO `contacts` (`category`, `businessid`, `name`, `first_name`, `office_phone`, `email_address`) VALUES ('Budget Business', '$businessid', '$name', '$first_name', '$office_phone', '$email_address')";
        $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
        $contactid = mysqli_insert_id($dbc);
        $m = 1;
	} else {
        $contactid = $_POST['contactid'];
	}

	$budget_name = filter_var($_POST['budget_name'],FILTER_SANITIZE_STRING);
    $staff_lead = filter_var($_POST['staff_lead'],FILTER_SANITIZE_STRING);
    $staff_co_lead = filter_var($_POST['staff_co_lead'],FILTER_SANITIZE_STRING);
    $site = filter_var($_POST['site'],FILTER_SANITIZE_STRING);
    $start_date = filter_var($_POST['start_date'],FILTER_SANITIZE_STRING);
    $created_date = filter_var($_POST['created_date'],FILTER_SANITIZE_STRING);
	$finish_date = filter_var($_POST['finish_date'],FILTER_SANITIZE_STRING);
	$category = $_POST['category'];
	//$encryptData = 
    //$wholeData = ;

	//$budgetid = 1;
	$logger = get_contact($dbc, $_SESSION['contactid']);
    if(empty($_POST['budgetid'])) {
		$history = '<b>' . $logger . '</b> Added a Budget on ' . date('Y-m-d H:i:s') . '<br>';
        $query_insert_vendor = "INSERT INTO `budget` (`budget_name`, `staff_lead`, `staff_co_lead`, `business`, `site`, `budget_created`, `start_date`, `finish_date`, `history`, `status`) VALUES 
		('$budget_name', '$staff_lead', '$staff_co_lead', '$businessid', '$site', '$created_date','$start_date', '$finish_date', '$history','$status')";
		$result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $budgetid = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $budgetid = $_POST['budgetid'];
		$query_check_credentials = "SELECT history FROM budget where budgetid = $budgetid";
		$historyResult = mysqli_fetch_assoc(mysqli_query($dbc, $query_check_credentials));
		$history = $historyResult['history'] . '<b>' . $logger . '</b> Updated a Budget on ' . date('Y-m-d H:i:s') . '<br>';
        $query_update_vendor = "UPDATE `budget` SET `budget_name` = '$budget_name', `staff_lead` = '$staff_lead', `staff_co_lead` = '$staff_co_lead', 
		`business` = '$businessid', `site` = '$site', `budget_created` = '$created_date', `start_date` = '$start_date', `finish_date` = '$finish_date', `status` = '$status', `history` = '$history' WHERE `budgetid` = '$budgetid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

	$count = 0;
	mysqli_query($dbc, "delete from budget_category where budgetid = $budgetid");
	foreach($_POST['category'] as $category) {
		$category = trim($category);
		if($category != '') {
			$query_check_credentials = "SELECT EC FROM budget_category where category = '$category' and budgetid = $budgetid";
			$result = mysqli_fetch_assoc(mysqli_query($dbc, $query_check_credentials));
			if(empty($result)) {
				$subquery_check_credentials = "SELECT EC FROM budget_category where budgetid = $budgetid order by EC DESC";
				$subResult = mysqli_fetch_assoc(mysqli_query($dbc, $subquery_check_credentials));
				if(!empty($subResult)) {
					$newEC =  $subResult['EC'] + 1000;
				}
				else {
					$newEC = 1000;
				}
			}
			else {
				$newEC = $result['EC'];
			}
			
			$query_gl_credentials = "SELECT GL FROM budget_category where EC = '$newEC' and category = '$category' and budgetid = $budgetid";
			$gl_result = mysqli_fetch_assoc(mysqli_query($dbc, $query_gl_credentials));
			if(empty($gl_result)) {
					$newGL = $newEC + 1;
			}
			else {
				$subquery_check_credentials = "SELECT GL FROM budget_category where EC = '$newEC' and budgetid = $budgetid order by GL DESC";
				$subResult = mysqli_fetch_assoc(mysqli_query($dbc, $subquery_check_credentials));
				if(!empty($subResult)) {
					$newGL =  $subResult['GL'] + 1;
				}
			}
			
			$expense = $_POST['expense'][$count];
			$daily = $_POST['daily'][$count];
			$weekly = $_POST['weekly'][$count];
			$monthly = $_POST['monthly'][$count];
			$q1 = $_POST['q1'][$count];
			$q2 = $_POST['q2'][$count];
			$q3 = $_POST['q3'][$count];
			$q4 = $_POST['q4'][$count];
			$anually = $_POST['anually'][$count];
			$query_insert_category = "INSERT INTO `budget_category` (`budgetid`, `category`, `expense`, `daily`,`weekly`, `monthly`, `q1`, `q2`, `q3`, `q4`, `annual`, `EC`, `GL`) VALUES 
			('$budgetid','$category', '$expense', '$daily', '$weekly', '$monthly', '$q1','$q2', '$q3', '$q4','$anually', '$newEC', '$newGL')";
			$result_insert_category = mysqli_query($dbc, $query_insert_category);
			$count++;
		}
	}
	
	//Comment
    $type = '';
    $note_heading = filter_var($_POST['note_heading'],FILTER_SANITIZE_STRING);

    if($note_heading == 'General') {
        $type = 'note';
    }
	else {
		$type = 'comment';
	}
	
	$created_by = $_SESSION['contactid'];
	$created_date = date('Y-m-d');
    $budget_comment = htmlentities($_POST['budget_comment']);
    $t_comment = filter_var($budget_comment,FILTER_SANITIZE_STRING);
    if($t_comment != '') {
        $email_comment = $_POST['email_comment'];

        if($type != '') {
            $query_insert_ca = "INSERT INTO `budget_comment` (`budgetid`, `comment`, `email_to`, `created_date`, `created_by`, `type`, 
			`note_heading`) VALUES ('$budgetid', '$t_comment', '$email_comment', '$created_date', '$created_by', '$type', '$note_heading')";
            $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
        } /*else {
            $query_update_report = "UPDATE `project_detail` SET `$note_heading` = CONCAT($note_heading,'$t_comment') WHERE `projectid` = '$projectid'";
            $result_update_report = mysqli_query($dbc, $query_update_report);
        }*/

        if ($_POST['send_email_on_comment'] == 'Yes') {
            //Code for Send Email
			$sender = [$_POST['email_sender']=>$_POST['email_name']];
            $email = get_email($dbc, $email_comment);
            $subject = $_POST['email_subject'];

            $email_body = str_replace(['[NOTE]','[BUDGETID]'], [$_POST['budget_comment'],$budgetid], $_POST['email_body']);

            if($email != '') {
				try {
					send_email($sender, $email, '', '', $subject, $email_body, '');
				} catch(Exception $e) {
					echo "<script>alert('Unable to send email. Please try again later.');</script>";
				}
            }
        }
    }
	
    //Notes	
    echo '<script type="text/javascript"> window.location.replace("budget.php?maintype=pending_budget"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}
?>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('budget');
?>
<?php
$note_add_view = '';
$info_view = '';
if(!empty($_GET['note'])) {
	$note_add_view = 'in';
} else {
	$info_view = 'in';
}
?>
<?php
$active_budgeting = '';
$expense_summary = '';
$income_summary = '';
$expense_tracking = '';
if(empty($_GET['type'])) {
    $_GET['type'] = 'active_budgeting';
}
if($_GET['type'] == 'active_budgeting') {
	$active_budgeting = 'active_tab';
}
if($_GET['type'] == 'expense_summary') {
	$expense_summary = 'active_tab';
}
if($_GET['type'] == 'income_summary') {
	$income_summary = 'active_tab';
}
if($_GET['type'] == 'pl_summary') {
	$pl_summary = 'active_tab';
}
?>

<div class="container">
	<div class="row">
	<?php if(isset($_GET['budgetid'])): ?>
		<span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title=""><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="<?php echo addOrUpdateUrlParam('type','active_budgeting'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $active_budgeting; ?>" type="button">Budgeting</button></a>
		<span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title=""><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="<?php echo addOrUpdateUrlParam('type','expense_summary'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $expense_summary; ?>" type="button">Expense Summary</button></a>
		<span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title=""><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="<?php echo addOrUpdateUrlParam('type','income_summary'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $income_summary; ?>" type="button">Income Summary</button></a>
		<span class="popover-examples list-inline" style="margin:10px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title=""><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="<?php echo addOrUpdateUrlParam('type','pl_summary'); ?>"><button class="btn brand-btn mobile-block mobile-100 <?php echo $pl_summary; ?>" type="button">Profit/Loss Summary</button></a>
	<?php endif; ?>
	
	<div class="gap-top double-gap-bottom"><a href="budget.php?maintype=pending_budget" class="btn config-btn">Back to Dashboard</a></div>

	<?php
		if($_GET['type'] == 'expense_summary') {
			include('expense_summary_details.php');
		}
		if($_GET['type'] == 'income_summary') {
			include('budget_income.php');
		}
		if($_GET['type'] == 'pl_summary') {
			include('budget_pl.php');
		}
	?>
	<?php if($_GET['type'] == 'active_budgeting' || empty($_GET['type'])): ?>
		<h1>Add Budget</h1>
		<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

		<?php
			$created_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
			$primary_staff = $_SESSION['contactid'];

			$budget_name = '';
			$staff_lead = '';
			$staff_co_lead = '';
			$business = '';
			$site = '';
			$start_date = '';
			$finish_date = '';
			$budget_created = '';

			if(!empty($_GET['budgetid'])) {

				$budgetid = $_GET['budgetid'];
				$get_budget = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM budget WHERE budgetid='$budgetid'"));

				$budget_name = $get_budget['budget_name'];
				$staff_lead = $get_budget['staff_lead'];
				$staff_co_lead = $get_budget['staff_co_lead'];
				$business = $get_budget['business'];
				$site = $get_budget['site'];
				$budget_created = $get_budget['budget_created'];
				$start_date = $get_budget['start_date'];
				$finish_date = $get_budget['finish_date'];

			?>
			<input type="hidden" id="budgetid" name="budgetid" value="<?php echo $budgetid ?>" />
			<?php   }      ?>

			<div class="panel-group" id="accordion2">

				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
								General Information<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>
					<?php $class = 'in'; ?>
					<?php if($_GET['note'])
							$class = '';
					?>
					
					<div id="collapse_info" class="panel-collapse collapse <?php echo $class; ?>">
						<div class="panel-body">

							<div class="form-group">
								<label for="budget_name" class="col-sm-4 control-label">Budget Name:<span class="brand-color">*</span></label>
								<div class="col-sm-8">
								  <input name="budget_name" value="<?php echo $budget_name; ?>" type="text" class="form-control">
								</div>
							</div>
							<?php $search_client = '';?>
							<div class="form-group">
								<label for="staff_lead" class="col-sm-4 control-label">Staff Lead:<span class="brand-color">*</span></label>
								<div class="col-sm-8">
									<select data-placeholder="Select a Staff" name="staff_lead" class="chosen-select-deselect form-control">
									  <option value=""></option>
									  <?php
										$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
										foreach($query as $id) {
											$selected = '';
											$selected = $id == $staff_lead ? 'selected = "selected"' : '';
											echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
										}
									  ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label for="staff_co_lead" class="col-sm-4 control-label">Staff Co-Lead:<span class="brand-color">*</span></label>
								<div class="col-sm-8">
								  <select data-placeholder="Select a Staff" name="staff_co_lead" class="chosen-select-deselect form-control">
									  <option value=""></option>
									  <?php
										$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
										foreach($query as $id) {
											$selected = '';
											$selected = $id == $staff_co_lead ? 'selected = "selected"' : '';
											echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
										}
									  ?>
									</select>
								</div>
							</div>
							<?php $businessid = ''; ?>
							<div class="form-group">
								<label for="business" class="col-sm-4 control-label">Business:<span class="brand-color">*</span></label>
								<div class="col-sm-8">
									<select data-placeholder="Choose a Business..." name="business" id="business" class="chosen-select-deselect form-control1" width="380">
									  <option value=""></option>
									  <?php
											$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE name != '' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
											foreach($query as $id) {
												$selected = '';
												$selected = $id == $business ? 'selected = "selected"' : '';
												echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id,'name').'</option>';
											}
										?>
									</select>
								</div>
								
								<div class="form-group" id="new_business" style="display: none;">
							<label for="new_business" class="col-sm-4 control-label">New Business
							</label>
							<div class="col-sm-8">
								<input name="new_business" type="text" class="form-control"/>
							</div>
							</div>
							</div>
							
							<div class="form-group">
								<label for="site" class="col-sm-4 control-label">Site:<span class="brand-color">*</span></label>
								<div class="col-sm-8">
									<select data-placeholder="Select a Site" name="site" class="chosen-select-deselect form-control">
										<option value=""></option>
										<?php
											$query = mysqli_query($dbc,"SELECT site_name, siteid FROM field_sites");
											while($row = mysqli_fetch_array($query)) {
											?><option <?php if ($row['siteid'] == $site) { echo " selected"; } ?> value='<?php echo  $row['siteid']; ?>' ><?php echo $row['site_name']; ?></option>
										<?php	} ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>

			 

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_desc" >
								Dates<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_desc" class="panel-collapse collapse">
						<div class="panel-body">
						   <div class="form-group clearfix">
								<label for="first_name" class="col-sm-4 control-label text-right">Date Budget Created:<span class="brand-color">*</span></label>
								<div class="col-sm-8">
									<input name="created_date" value="<?php echo $budget_created; ?>" type="text" class="datepicker"></p>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="first_name" class="col-sm-4 control-label text-right">Start Date:<span class="brand-color">*</span></label>
								<div class="col-sm-8">
									<input name="start_date" value="<?php echo $start_date; ?>" type="text" class="datepicker"></p>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="first_name" class="col-sm-4 control-label text-right">Estimated Completion Date:<span class="brand-color">*</span></label>
								<div class="col-sm-8">
									<input name="finish_date" value="<?php echo $finish_date; ?>" type="text" class="datepicker"></p>
								</div>
							</div>
						 </div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_notes" >
							   Notes<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_notes" class="panel-collapse collapse <?php echo $note_add_view; ?>">
						<div class="panel-body">
						 <?php include ('add_view_budget_comment.php'); ?>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cost" >
								Expense Categories & Headings<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_cost" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group clearfix products_heading">
								<label class="col-sm-1 text-center">Category</label>
								<label class="col-sm-2 text-center">Expense Heading</label>
								<label class="col-sm-1 text-center">Daily</label>
								<label class="col-sm-1 text-center">Weekly</label>
								<label class="col-sm-1 text-center">Monthly</label>
								<label class="col-sm-1 text-center">Q1</label>
								<label class="col-sm-1 text-center">Q2</label>
								<label class="col-sm-1 text-center">Q3</label>
								<label class="col-sm-1 text-center">Q4</label>
								<label class="col-sm-1 text-center">Annual</label>
							</div>

							<?php $id_loop = 500; ?>
							<?php 
								$select_query = "select * from budget_category where budgetid = $budgetid order by category";
								$select_result = mysqli_query($dbc, $select_query);
							?>
							<?php while ($row = mysqli_fetch_array($select_result)) { ?>
								<div class="form-group clearfix all_products" id="<?php echo 'productsmisc_'.$id_loop; ?>" >
									<div class="col-sm-1">
										<input name="category[]" value="<?php echo $row['category']; ?>" id="category_<?php echo $id_loop; ?>" type="text" class="form-control" />
									</div>
									<div class="col-sm-2">
										<input name="expense[]" value="<?php echo $row['expense']; ?>" id="expense_<?php echo $id_loop; ?>"  type="text" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="daily[]" value="<?php echo $row['daily']; ?>" id="daily_<?php echo $id_loop; ?>" onchange="changeOther(this);" type="text" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="weekly[]" value="<?php echo $row['weekly']; ?>" id="weekly_<?php echo $id_loop; ?>" onchange="changeOther(this);" type="text" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="monthly[]" value="<?php echo $row['monthly']; ?>" id="monthly_<?php echo $id_loop; ?>" onchange="changeOther(this);" type="text" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="q1[]" value="<?php echo $row['q1']; ?>" id="q1_<?php echo $id_loop; ?>" type="text" onchange="changeOther(this);" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="q2[]" value="<?php echo $row['q2']; ?>" id="q2_<?php echo $id_loop; ?>" type="text" onchange="changeOther(this);" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="q3[]" value="<?php echo $row['q3']; ?>" id="q3_<?php echo $id_loop; ?>" type="text" onchange="changeOther(this);" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="q4[]" value="<?php echo $row['q4']; ?>" id="q4_<?php echo $id_loop; ?>" type="text" onchange="changeOther(this);" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="anually[]" value="<?php echo $row['annual']; ?>" id="annually_<?php echo $id_loop; ?>"  type="text" onchange="changeOther(this);" class="form-control" />
									</div>
									<div class="col-sm-1" >
										<a href="#" onclick="deleteEstimate(this,'productsmisc_','expense_'); return false;" id="deleteproductsmisc_0" class="btn brand-btn">Delete</a>
									</div>
								</div>
							<?php $id_loop++; } ?>
							
							<div class="additional_p_misc clearfix">
								<div class="clearfix"></div>
								<div class="form-group all_products" id="productsmisc_0">
									<div class="col-sm-1">
										<input name="category[]" value="" id="category_0" type="text" class="form-control" />
									</div>
									<div class="col-sm-2">
										<input name="expense[]" value="" id="expense_0"  type="text" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="daily[]" value="" id="daily_0" onchange="changeOther(this);" type="text" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="weekly[]" value="" id="weekly_0" onchange="changeOther(this);" type="text" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="monthly[]" value="" id="monthly_0" onchange="changeOther(this);" type="text" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="q1[]" value="" id="q1_0" type="text" onchange="changeOther(this);" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="q2[]" value="" id="q2_0" type="text" onchange="changeOther(this);" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="q3[]" value="" id="q3_0" type="text" onchange="changeOther(this);" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="q4[]" value="" id="q4_0" type="text" onchange="changeOther(this);" class="form-control" />
									</div>
									<div class="col-sm-1">
										<input name="anually[]" value="" id="annually_0"  type="text" onchange="changeOther(this);" class="form-control" />
									</div>
									<div class="col-sm-1" >
										<a href="#" onclick="deleteEstimate(this,'productsmisc_','expense_'); return false;" id="deleteproductsmisc_0" class="btn brand-btn">Delete</a>
									</div>
								</div>
							</div>
							
							<div id="add_here_new_p_misc"></div>
							<div class="form-group clearfix products_heading" style="display:none;">
								<label class="col-sm-1 text-center">Category</label>
								<label class="col-sm-2 text-center">Expense Heading</label>
								<label class="col-sm-1 text-center">Daily</label>
								<label class="col-sm-1 text-center">Weekly</label>
								<label class="col-sm-1 text-center">Monthly</label>
								<label class="col-sm-1 text-center">Q1</label>
								<label class="col-sm-1 text-center">Q2</label>
								<label class="col-sm-1 text-center">Q3</label>
								<label class="col-sm-1 text-center">Q4</label>
								<label class="col-sm-1 text-center">Annual</label>
							</div>
							
							<div class="form-group triple-gapped clearfix">
								<div class="col-sm-offset-10">
									<button id="add_row_p_misc" class="btn brand-btn pull-left">Add More</button>
								</div>
							</div>			
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<p><span class="hp-red"><em>Required Fields *</em></span></p>
			</div>

			<div class="form-group">
				<div class="col-sm-6">
					<a href="budget.php?maintype=pending_budget" class="btn brand-btn btn-lg">Back</a>
					<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
					<button type="submit" name="add_budget" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
					<button type="submit" style='margin-right:10px' name="add_budget" value="Save" class="btn brand-btn btn-lg pull-right">Save</button>
					
				</div>
				
			</div>

		</form>
	<?php endif; ?>

  </div>
</div>
<?php include ('../footer.php'); ?>
