<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
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
	$created_date = date('Y-m-d');
    //$created_by = $_SESSION['contactid'];

    $m = 0;
	if($_POST['new_business'] != '') {
		$name = $_POST['new_business'];
        $query_insert_inventory = "INSERT INTO `contacts` (`category`, `name`) VALUES ('Call Log Contact', '$name')";
        $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
        $businessid = mysqli_insert_id($dbc);
        $m = 1;
	} else {
        $businessid = $_POST['business'];
	}

	if($_POST['new_contact'] != '') {
		$first_name = $_POST['new_contact'];
        $query_insert_inventory = "INSERT INTO `contacts` (`category`, `businessid`, `name`, `first_name`, `office_phone`, `email_address`) VALUES ('Call Log Business', '$businessid', '$name', '$first_name', '$office_phone', '$email_address')";
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
    if(empty($_POST['budgetid'])) {
		$logger = $_SESSION['first_name'].' '.$_SESSION['last_name'];
		$history = '<b>' . $logger . '</b> Added a Budget on ' . date('Y-m-d H:i:s') . '<br>';
        $query_insert_vendor = "INSERT INTO `budget` (`budget_name`, `staff_lead`, `staff_co_lead`, `business`, `site`, `budget_created`, `start_date`, `finish_date`, `history`) VALUES 
		('$budget_name', '$staff_lead', '$staff_co_lead', '$businessid', '$site', '$created_date','$start_date', '$finish_date', '$history')";
		$result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $budgetid = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $budgetid = $_POST['budgetid'];
		$query_check_credentials = "SELECT history FROM budget where budgetid = $budgetid";
		$historyResult = mysqli_fetch_assoc(mysqli_query($dbc, $query_check_credentials));
		$history = $historyResult['history'] . '<b>' . $logger . '</b> Updated a Budget on ' . date('Y-m-d H:i:s') . '<br>';
        $query_update_vendor = "UPDATE `budget` SET `budget_name` = '$budget_name', `staff_lead` = '$staff_lead', `staff_co_lead` = '$staff_co_lead', 
		`business` = '$businessid', `site` = '$site', `budget_created` = '$created_date', `start_date` = '$start_date', `finish_date` = '$finish_date' WHERE `budgetid` = '$budgetid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

	$count = 0;
	mysqli_query($dbc, "delete from budget_category where budgetid = $budgetid");
	foreach($_POST['category'] as $category) {
		$category = trim($category);
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
<div class="container">
	<div class="row">

    <h1>Add Budget</h1>
	<div class="gap-top double-gap-bottom"><a href="budget.php?maintype=pending_budget" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pipeline_fields FROM field_config_calllog"));
        $value_config = ','.$get_field_config['pipeline_fields'].',';

        $created_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
        $primary_staff = $_SESSION['contactid'];

        $max_calllogid = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT MAX(calllogid) AS max_calllogid FROM calllog_pipeline"));

        $calllogid = $max_calllogid['max_calllogid']+1;
        $businessid = '';
        $contactid = '';
        $call_subject = '';
        $call_duration = '';
        $call_notes = '';
        $next_action = '';
        $new_reminder = '';
        $status = '';

        if(!empty($_GET['calllogid'])) {

            $calllogid = $_GET['calllogid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM calllog_pipeline WHERE calllogid='$calllogid'"));

            $businessid = $get_contact['businessid'];
            $contactid = $get_contact['contactid'];
            $call_subject = $get_contact['call_subject'];
            $call_duration = $get_contact['call_duration'];
            $call_notes = $get_contact['call_notes'];
            $next_action = $get_contact['next_action'];
            $new_reminder = $get_contact['new_reminder'];
            $status = $get_contact['status'];

        ?>
        <input type="hidden" id="calllogid" name="calllogid" value="<?php echo $calllogid ?>" />
        <?php   }      ?>

        <div class="panel-group" id="accordion2">

            <?php if (strpos($value_config, ','."CL#".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
                            General Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_info" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="budget_name" class="col-sm-4 control-label">Budget Name:</label>
                            <div class="col-sm-8">
                              <input name="budget_name" value="<?php echo $budget_name; ?>" type="text" class="form-control">
                            </div>
                        </div>
						<?php $search_client = '';?>
						<div class="form-group">
                            <label for="staff_lead" class="col-sm-4 control-label">Staff Lead#:</label>
                            <div class="col-sm-8">
								<select data-placeholder="Select a Staff" name="staff_lead" class="chosen-select-deselect form-control">
								  <option value=""></option>
								  <?php
									$query = mysqli_query($dbc,"SELECT DISTINCT(c.name), c.contactid FROM contacts c");
									while($row = mysqli_fetch_array($query)) {
									?><option <?php if ($row['contactid'] == $search_client) { echo " selected"; } ?> value='<?php echo  $row['contactid']; ?>' ><?php echo $row['name']; ?></option>
								<?php	} ?>
								</select>
                            </div>
                        </div>

						<div class="form-group">
                            <label for="staff_co_lead" class="col-sm-4 control-label">Staff Co-Lead:</label>
                            <div class="col-sm-8">
                              <select data-placeholder="Select a Staff" name="staff_co_lead" class="chosen-select-deselect form-control">
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
						<?php $businessid = ''; ?>
						<div class="form-group">
                            <label for="business" class="col-sm-4 control-label">Business:</label>
                            <div class="col-sm-8">
								<select data-placeholder="Choose a Business..." name="business" id="business" class="chosen-select-deselect form-control1" width="380">
                                  <option value=""></option>
                                  <?php
                                    $query = mysqli_query($dbc,"SELECT name, contactid FROM contacts WHERE name != '' AND deleted=0 ORDER BY name");
                                    echo "<option value = 'New Business'>New Business</option>";
                                    while($row = mysqli_fetch_array($query)) {
                                        if ($businessid == $row['contactid']) {
                                            $selected = 'selected="selected"';
                                        } else {
                                            $selected = '';
                                        }
                                        echo "<option ".$selected." value='". $row['contactid']."'>".$row['name'].'</option>';
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
                            <label for="site" class="col-sm-4 control-label">Site:</label>
                            <div class="col-sm-8">
								<select data-placeholder="Select a Site" name="site" class="chosen-select-deselect form-control">
									<option value=""></option>
									<?php
										$query = mysqli_query($dbc,"SELECT site_name, siteid FROM field_sites");
										while($row = mysqli_fetch_array($query)) {
										?><option <?php if ($row['siteid'] == $search_client) { echo " selected"; } ?> value='<?php echo  $row['siteid']; ?>' ><?php echo $row['site_name']; ?></option>
									<?php	} ?>
								</select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php } ?>

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
							<label for="first_name" class="col-sm-4 control-label text-right">Date Budget Created:</label>
							<div class="col-sm-8">
								<input name="created_date" value="<?php echo $created_date; ?>" type="text" class="datepicker"></p>
							</div>
						</div>
						<div class="form-group clearfix">
							<label for="first_name" class="col-sm-4 control-label text-right">Start Date:</label>
							<div class="col-sm-8">
								<input name="start_date" value="<?php echo $start_date; ?>" type="text" class="datepicker"></p>
							</div>
						</div>
						<div class="form-group clearfix">
							<label for="first_name" class="col-sm-4 control-label text-right">Estimated Completion Date:</label>
							<div class="col-sm-8">
								<input name="finish_date" value="<?php echo $expiry_date; ?>" type="text" class="datepicker"></p>
							</div>
						</div>
                     </div>
                </div>
            </div>

			<!--<div class="form-group">
            <label for="first_name" class="col-sm-4 control-label">Note Heading:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Heading..." name="note_heading" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option value="detail_issue">Issue</option>
                  <option value="detail_problem">Problem</option>
                  <option value="detail_gap">GAP</option>
                  <option value="detail_technical_uncertainty">Technical Uncertainty</option>
                  <option value="detail_base_knowledge">Base Knowledge</option>
                  <option value="detail_do">Do</option>
                  <option value="detail_already_known">Already Known</option>
                  <option value="detail_sources">Sources</option>
                  <option value="detail_current_designs">Current Designs</option>
                  <option value="detail_known_techniques">Known Techniques</option>
                  <option value="detail_review_needed">Review Needed</option>
                  <option value="detail_looking_to_achieve">Looking to Achieve</option>
                  <option value="detail_plan">Plan</option>
                  <option value="detail_next_steps">Next Steps</option>
                  <option value="detail_learnt">Learned</option>
                  <option value="detail_discovered">Discovered</option>
                  <option value="detail_tech_advancements">Tech Advancements</option>
                  <option value="detail_work">Work</option>
                  <option value="detail_adjustments_needed">Adjustments Needed</option>
                  <option value="detail_future_designs">Future Designs</option>
                  <option value="detail_targets">Targets</option>
                  <option value="detail_audience">Audience</option>
                  <option value="detail_strategy">Strategy</option>
                  <option value="detail_desired_outcome">Desired Outcome</option>
                  <option value="detail_actual_outcome">Actual Outcome</option>
                  <option value="detail_check">Check</option>
                  <option value="detail_objective">Objective</option>
                  <option value="General">General</option>
                </select>

            </div>
        </div>

      <div class="form-group">
        <label for="site_name" class="col-sm-4 control-label">Note:</label>
        <div class="col-sm-8">
          <textarea name="project_comment" rows="4" cols="50" class="form-control" ></textarea>
        </div>
      </div>

        <div class="form-group">
		  <label for="site_name" class="col-sm-4 control-label">Send Email:</label>
		  <div class="col-sm-8">
			<input type="checkbox" value="Yes" name="send_email_on_comment">
		  </div>
		</div>

        <div class="form-group">
		  <label for="site_name" class="col-sm-4 control-label">Assign/Email To:</label>
		  <div class="col-sm-8">
			<select data-placeholder="Choose a Staff Member..." name="email_comment" class="chosen-select-deselect form-control" width="380">
			  <option value=""></option>
			  <?php
                $cat = '';
                $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name, category FROM contacts WHERE deleted=0 AND (category IN (".STAFF_CATS.") OR businessid='$businessid') AND ".STAFF_CATS_HIDE_QUERY."  ORDER BY category");
				while($row = mysqli_fetch_array($query)) {
                    if($cat != $row['category']) {
                        echo '<optgroup label="'.$row['category'].'">';
                        $cat = $row['category'];
                    }
					echo "<option value='". $row['contactid']."'>".$row['first_name'].' '.$row['last_name'].'</option>';
				}
			  ?>
			</select>
		  </div>
		</div>-->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cost" >
                            Expense Category's & Headings<span class="glyphicon glyphicon-plus"></span>
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

						<div class="additional_p_misc clearfix">
							<div class="clearfix"></div>
							<div class="form-group all_products" id="productsmisc_0">
								<div class="col-sm-1">
									<input name="category[]" value="<?php echo '';?>" id="category_0" type="text" class="form-control" />
								</div>
								<div class="col-sm-2">
									<input name="expense[]" value="<?php echo '';?>" id="expense_0"  type="text" class="form-control" />
								</div>
								<div class="col-sm-1">
									<input name="daily[]" value="<?php echo '';?>" id="daily_0" onchange="changeOther(this);" type="text" class="form-control" />
								</div>
								<div class="col-sm-1">
									<input name="weekly[]" value="<?php echo '';?>" id="weekly_0" onchange="changeOther(this);" type="text" class="form-control" />
								</div>
								<div class="col-sm-1">
									<input name="monthly[]" value="<?php echo '';?>" id="monthly_0" onchange="changeOther(this);" type="text" class="form-control" />
								</div>
								<div class="col-sm-1">
									<input name="q1[]" value="<?php echo '';?>" id="q1_0" type="text" onchange="changeOther(this);" class="form-control" />
								</div>
								<div class="col-sm-1">
									<input name="q2[]" value="<?php echo '';?>" id="q2_0" type="text" onchange="changeOther(this);" class="form-control" />
								</div>
								<div class="col-sm-1">
									<input name="q3[]" value="<?php echo '';?>" id="q3_0" type="text" onchange="changeOther(this);" class="form-control" />
								</div>
								<div class="col-sm-1">
									<input name="q4[]" value="<?php echo '';?>" id="q4_0" type="text" onchange="changeOther(this);" class="form-control" />
								</div>
								<div class="col-sm-1">
									<input name="anually[]" value="<?php echo '';?>" id="annually_0"  type="text" onchange="changeOther(this);" class="form-control" />
								</div>
								<div class="col-sm-1" >
									<a href="#" onclick="deleteEstimate(this,'productsmisc_','expense_'); return false;" id="deleteproductsmisc_0" class="btn brand-btn">Delete</a>
								</div>
							</div>
						</div>
						
						<div id="add_here_new_p_misc"></div>
						
						<div class="form-group triple-gapped clearfix">
							<div class="col-sm-offset-10">
								<button id="add_row_p_misc" class="btn brand-btn pull-left">Add More Expenses</button>
							</div>
						</div>			
					</div>
                </div>
            </div>

            <?php if (strpos($value_config, ','."Lead Notes".',') !== FALSE) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <span class="popover-examples list-inline" style="margin:0 5px 0 0;">
							<a data-toggle="tooltip" data-placement="top" title="Add any notes related to this call log lead."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a>
						</span>
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff_pos" >
                            Lead Notes<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <?php
                $accord = '';
                if(!empty($_GET['go'])) {
                    $accord = ' in';
                } ?>
                <div id="collapse_staff_pos" class="panel-collapse collapse <?php echo $accord; ?>">
                    <div class="panel-body">

                        <?php
                        include ('add_call_log_note.php');
                        ?>

                    </div>
                </div>
            </div>
            <?php } ?>

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
			</div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
