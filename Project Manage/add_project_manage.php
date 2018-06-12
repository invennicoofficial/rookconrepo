<?php
/*
Add	Inventory
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {
    $created_by = $_SESSION['contactid'];
    $today_date = date('Y-m-d');
    $businessid = $_POST['businessid'];
    $contactid = $_POST['business_contactid'];
    $ratecardid = $_POST['ratecardid'];
    $short_name = filter_var($_POST['short_name'],FILTER_SANITIZE_STRING);

    $piece_work = filter_var($_POST['piece_work'],FILTER_SANITIZE_STRING);
    $add_to_helpdesk = $_POST['add_to_helpdesk'];
    $heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
    $location = filter_var($_POST['location'],FILTER_SANITIZE_STRING);
    $job_number = filter_var($_POST['job_number'],FILTER_SANITIZE_STRING);
    $afe_number = filter_var($_POST['afe_number'],FILTER_SANITIZE_STRING);
    $created_date = filter_var($_POST['created_date'],FILTER_SANITIZE_STRING);
    $start_date = filter_var($_POST['start_date'],FILTER_SANITIZE_STRING);
    $estimated_completion_date = filter_var($_POST['estimated_completion_date'],FILTER_SANITIZE_STRING);
    $work_performed_date = filter_var($_POST['work_performed_date'],FILTER_SANITIZE_STRING);

    $detail_issue = filter_var(htmlentities($_POST['detail_issue']),FILTER_SANITIZE_STRING);
    $detail_problem = filter_var(htmlentities($_POST['detail_problem']),FILTER_SANITIZE_STRING);
    $detail_gap = filter_var(htmlentities($_POST['detail_gap']),FILTER_SANITIZE_STRING);
    $detail_technical_uncertainty = filter_var(htmlentities($_POST['detail_technical_uncertainty']),FILTER_SANITIZE_STRING);
    $detail_base_knowledge = filter_var(htmlentities($_POST['detail_base_knowledge']),FILTER_SANITIZE_STRING);
    $detail_do = filter_var(htmlentities($_POST['detail_do']),FILTER_SANITIZE_STRING);
    $detail_already_known = filter_var(htmlentities($_POST['detail_already_known']),FILTER_SANITIZE_STRING);
    $detail_sources = filter_var(htmlentities($_POST['detail_sources']),FILTER_SANITIZE_STRING);
    $detail_current_designs = filter_var(htmlentities($_POST['detail_current_designs']),FILTER_SANITIZE_STRING);

    $detail_known_techniques = filter_var(htmlentities($_POST['detail_known_techniques']),FILTER_SANITIZE_STRING);
    $detail_review_needed = filter_var(htmlentities($_POST['detail_review_needed']),FILTER_SANITIZE_STRING);
    $detail_looking_to_achieve = filter_var(htmlentities($_POST['detail_looking_to_achieve']),FILTER_SANITIZE_STRING);
    $detail_plan = filter_var(htmlentities($_POST['detail_plan']),FILTER_SANITIZE_STRING);
    $detail_next_steps = filter_var(htmlentities($_POST['detail_next_steps']),FILTER_SANITIZE_STRING);
    $detail_learnt = filter_var(htmlentities($_POST['detail_learnt']),FILTER_SANITIZE_STRING);
    $detail_discovered = filter_var(htmlentities($_POST['detail_discovered']),FILTER_SANITIZE_STRING);
    $detail_tech_advancements = filter_var(htmlentities($_POST['detail_tech_advancements']),FILTER_SANITIZE_STRING);
    $detail_work = filter_var(htmlentities($_POST['detail_work']),FILTER_SANITIZE_STRING);
    $detail_adjustments_needed = filter_var(htmlentities($_POST['detail_adjustments_needed']),FILTER_SANITIZE_STRING);
    $detail_future_designs = filter_var(htmlentities($_POST['detail_future_designs']),FILTER_SANITIZE_STRING);
    $detail_objective = filter_var(htmlentities($_POST['detail_objective']),FILTER_SANITIZE_STRING);
    $detail_targets = filter_var(htmlentities($_POST['detail_targets']),FILTER_SANITIZE_STRING);
    $detail_audience = filter_var(htmlentities($_POST['detail_audience']),FILTER_SANITIZE_STRING);
    $detail_strategy = filter_var(htmlentities($_POST['detail_strategy']),FILTER_SANITIZE_STRING);
    $detail_desired_outcome = filter_var(htmlentities($_POST['detail_desired_outcome']),FILTER_SANITIZE_STRING);
    $detail_actual_outcome = filter_var(htmlentities($_POST['detail_actual_outcome']),FILTER_SANITIZE_STRING);
    $detail_check = filter_var(htmlentities($_POST['detail_check']),FILTER_SANITIZE_STRING);

    $doing_start_date = $_POST['doing_start_date'];
    $doing_end_date = $_POST['doing_end_date'];
    $internal_qa_date = $_POST['internal_qa_date'];
    $client_qa_date = $_POST['client_qa_date'];
    $to_do_date = $_POST['to_do_date'];
    $deliverable_date = $_POST['deliverable_date'];

    $estimated_time_to_complete_work = $_POST['estimated_time_to_complete_work_hour'].':'.$_POST['estimated_time_to_complete_work_minute'];

    $project_path = $_POST['project_path'];
    $milestone_timeline = $_POST['milestone_timeline'];
    $service_type = $_POST['service_type'];
    $service_category = $_POST['service_category'];
    $service_heading = $_POST['service_heading'];
    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);
    $notes = filter_var(htmlentities($_POST['notes']),FILTER_SANITIZE_STRING);
    $status = $_POST['status'];
    $assign_to = implode(',',$_POST['assign_to']);
    $doing_assign_to = $_POST['doing_assign_to'];
    $internal_qa_assign_to = $_POST['internal_qa_assign_to'];
    $client_qa_assign_to = $_POST['client_qa_assign_to'];

    $budget_price = '';
    for($i=0; $i<=16; $i++) {
        $budget_price .= $_POST['budget_price_'.$i].'*#*';
    }
    $budget_price .= $_POST['total_budget'];

    include ('insert_project_manage_budget.php');

    if(empty($_POST['projectmanageid'])) {

        $query_insert_project_manage = "INSERT INTO `project_manage` (`businessid`, `contactid`, `ratecardid`, `short_name`, `piece_work`, `add_to_helpdesk`, `heading`, `location`, `job_number`, `afe_number`, `created_date`, `start_date`, `estimated_completion_date`, `work_performed_date`, `project_path`, `milestone_timeline`, `service_type`, `service_category`, `service_heading`, `status`, `doing_start_date`, `doing_end_date`, `internal_qa_date`, `client_qa_date`, `assign_to`, `doing_assign_to`, `internal_qa_assign_to`, `client_qa_assign_to`, `to_do_date`, `deliverable_date`, `estimated_time_to_complete_work`) VALUES ('$businessid', '$contactid', '$ratecardid', '$short_name', '$piece_work', '$add_to_helpdesk', '$heading', '$location', '$job_number', '$afe_number', '$created_date', '$start_date', '$estimated_completion_date', '$work_performed_date', '$project_path', '$milestone_timeline', '$service_type', '$service_category', '$service_heading', '$status', '$doing_start_date', '$doing_end_date', '$internal_qa_date', '$client_qa_date', '$assign_to', '$doing_assign_to', '$internal_qa_assign_to', '$client_qa_assign_to', '$to_do_date', '$deliverable_date', '$estimated_time_to_complete_work')";
        $result_insert_project_manage = mysqli_query($dbc, $query_insert_project_manage);
        $projectmanageid = mysqli_insert_id($dbc);

        $query_insert_detail = "INSERT INTO `project_manage_detail` (`projectmanageid`, `detail_issue`, `detail_problem`, `detail_gap`, `detail_technical_uncertainty`, `detail_base_knowledge`, `detail_do`, `detail_already_known`, `detail_sources`, `detail_current_designs`, `detail_known_techniques`, `detail_review_needed`, `detail_looking_to_achieve`, `detail_plan`, `detail_next_steps`, `detail_learnt`,  `detail_discovered`,  `detail_tech_advancements`, `detail_work`, `detail_adjustments_needed`, `detail_future_designs`, `detail_check`, `detail_objective`, `detail_targets`, `detail_audience`, `detail_strategy`, `detail_desired_outcome`, `detail_actual_outcome`, `description`, `notes`) VALUES ('$projectmanageid', '$detail_issue', '$detail_problem', '$detail_gap', '$detail_technical_uncertainty', '$detail_base_knowledge', '$detail_do', '$detail_already_known', '$detail_sources', '$detail_current_designs', '$detail_known_techniques', '$detail_review_needed', '$detail_looking_to_achieve', '$detail_plan', '$detail_next_steps', '$detail_learnt',  '$detail_discovered',  '$detail_tech_advancements', '$detail_work',  '$detail_adjustments_needed', '$detail_future_designs', '$detail_check', '$detail_objective', '$detail_targets', '$detail_audience', '$detail_strategy', '$detail_desired_outcome', '$detail_actual_outcome', '$description', '$notes')";
        $result_insert_detail = mysqli_query($dbc, $query_insert_detail);

        $query_insert_detail = "INSERT INTO `project_manage_budget` (`projectmanageid`, `completion_date`, `budget_price`, `financial_cost`, `financial_price`, `financial_plus_minus`, `package`, `promotion`, `material`, `services`, `products`, `sred`, `labour`, `client`, `customer`, `inventory`, `equipment`, `staff`, `contractor`, `expense`, `vendor`, `custom`, `other_detail`, `total_price`, `estimate_data`, `review_profit_loss`, `review_budget`, `status`, `when_added`, `history`, `follow_up_date`, `quote_send_date`, `deleted`, `front_company_logo`, `front_client_logo`, `front_client_info`, `front_other_info`, `front_content_pages`) VALUES ('$projectmanageid', '$completion_date', '$budget_price', '$financial_cost', '$financial_price', '$financial_plus_minus', '$package', '$promotion', '$material', '$services', '$products', '$sred', '$labour', '$client', '$customer', '$inventory', '$equipment', '$staff', '$contractor', '$expense', '$vendor', '$custom', '$other_detail', '$total_price', '$estimate_data', '$review_profit_loss', '$review_budget', '$status', '$when_added', '$history', '$follow_up_date', '$quote_send_date', '$deleted', '$front_company_logo', '$front_client_logo', '$front_client_info', '$front_other_info', '$front_content_pages')";
        $result_insert_detail = mysqli_query($dbc, $query_insert_detail);


        $url = 'Added';
    } else {
        $projectmanageid = $_POST['projectmanageid'];
        $query_update_project_manage = "UPDATE `project_manage` SET `businessid` = '$businessid', `contactid` = '$contactid', `ratecardid` = '$ratecardid', `short_name` = '$short_name', `piece_work` = '$piece_work', `add_to_helpdesk` = '$add_to_helpdesk', `heading` = '$heading', `location` = '$location', `job_number` = '$job_number', `afe_number` = '$afe_number', `created_date` = '$created_date', `start_date` = '$start_date', `estimated_completion_date` = '$estimated_completion_date', `work_performed_date` = '$work_performed_date', `project_path` = '$project_path', `milestone_timeline` = '$milestone_timeline', `service_type` = '$service_type', `service_category` = '$service_category', `service_heading` = '$service_heading', `status` = '$status', `doing_start_date` = '$doing_start_date', `doing_end_date` = '$doing_end_date', `internal_qa_date` = '$internal_qa_date', `client_qa_date` = '$client_qa_date', `assign_to` = '$assign_to', `doing_assign_to` = '$doing_assign_to', `internal_qa_assign_to` = '$internal_qa_assign_to', `client_qa_assign_to` = '$client_qa_assign_to', `to_do_date` = '$to_do_date', `deliverable_date` = '$deliverable_date', `estimated_time_to_complete_work` = '$estimated_time_to_complete_work'  WHERE `projectmanageid` = '$projectmanageid'";
        $result_update_project_manage	= mysqli_query($dbc, $query_update_project_manage);

        $query_update_report = "UPDATE `project_manage_detail` SET `detail_issue` = '$detail_issue', `detail_problem` = '$detail_problem', `detail_technical_uncertainty` = '$detail_technical_uncertainty', `detail_base_knowledge` = '$detail_base_knowledge', `detail_do` = '$detail_do', `detail_already_known` = '$detail_already_known', `detail_sources` = '$detail_sources', `detail_current_designs` = '$detail_current_designs', `detail_known_techniques` = '$detail_known_techniques', `detail_review_needed` = '$detail_review_needed', `detail_looking_to_achieve` = '$detail_looking_to_achieve', `detail_plan` = '$detail_plan', `detail_next_steps` = '$detail_next_steps', `detail_learnt` = '$detail_learnt', `detail_discovered` = '$detail_discovered', `detail_tech_advancements` = '$detail_tech_advancements', `detail_work` = '$detail_work', `detail_adjustments_needed` = '$detail_adjustments_needed', `detail_future_designs` = '$detail_future_designs', `detail_check` = '$detail_check', `detail_objective` = '$detail_objective', `detail_gap` = '$detail_gap', `detail_targets` = '$detail_targets', `detail_audience` = '$detail_audience', `detail_strategy` = '$detail_strategy', `detail_desired_outcome` = '$detail_desired_outcome', `detail_actual_outcome` = '$detail_actual_outcome', `description` = '$description', `notes` = '$notes' WHERE `projectmanageid` = '$projectmanageid'";
        $result_update_report = mysqli_query($dbc, $query_update_report);

        $query_update_report = "UPDATE `project_manage_budget` SET `completion_date` = '$completion_date', `budget_price` = '$budget_price', `financial_cost` = '$financial_cost', `financial_price` = '$financial_price', `financial_plus_minus` = '$financial_plus_minus', `package` = '$package', `promotion` = '$promotion', `material` = '$material', `services` = '$services', `products` = '$products', `sred` = '$sred', `labour` = '$labour', `client` = '$client', `customer` = '$customer', `inventory` = '$inventory', `equipment` = '$equipment', `staff` = '$staff', `contractor` = '$contractor', `expense` = '$expense', `vendor` = '$vendor', `custom` = '$custom', `other_detail` = '$other_detail', `total_price` = '$total_price', `estimate_data` = '$estimate_data', `review_profit_loss` = '$review_profit_loss', `review_budget` = '$review_budget', `when_added` = '$when_added', `history` = '$history', `follow_up_date` = '$follow_up_date', `quote_send_date` = '$quote_send_date', `front_company_logo` = '$front_company_logo', `front_client_logo` = '$front_client_logo', `front_client_info` = '$front_client_info', `front_other_info` = '$front_other_info', `front_content_pages` = '$front_content_pages' WHERE `projectmanageid` = '$projectmanageid'";
        $result_update_report = mysqli_query($dbc, $query_update_report);

        $url = 'Updated';
    }

    //Timer
    $timer = $_POST['timer'];
    $end_time = date('g:i A');

    $start_time = 0;
    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_ticket = "UPDATE `project_manage_assign_to_timer` SET `end_time` = '$end_time', `start_timer_time` = '$start_time', `timer` = '$timer' WHERE `projectmanageid` = '$projectmanageid' AND created_by='$created_by' AND created_date='$today_date' AND end_time IS NULL";
        $result_update_ticket = mysqli_query($dbc, $query_update_ticket);

        $query_update_ticket = "UPDATE `project_manage` SET `start_time` = '0' WHERE `projectmanageid` = '$projectmanageid'";
        $result_update_ticket = mysqli_query($dbc, $query_update_ticket);
    }
    //Timer

    //Document
    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        $document = $_FILES["upload_document"]["name"][$i];

        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "download/".$_FILES["upload_document"]["name"][$i]) ;

        if($document != '') {
            $query_insert_client_doc = "INSERT INTO `project_manage_document_link` (`projectmanageid`, `type`, `document`, `created_date`, `created_by`) VALUES ('$projectmanageid', 'Support Document', '$document', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    for($i = 0; $i < count($_POST['support_link']); $i++) {
        $support_link = $_POST['support_link'][$i];

        if($support_link != '') {
            $query_insert_client_doc = "INSERT INTO `project_manage_document_link` (`projectmanageid`, `type`, `link`, `created_date`, `created_by`) VALUES ('$projectmanageid', 'Support Link', '$support_link', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    for($i = 0; $i < count($_FILES['review_upload_document']['name']); $i++) {
        $review_document = $_FILES["review_upload_document"]["name"][$i];

        move_uploaded_file($_FILES["review_upload_document"]["tmp_name"][$i], "download/".$_FILES["review_upload_document"]["name"][$i]) ;

        if($review_document != '') {
            $query_insert_client_doc = "INSERT INTO `project_manage_document_link` (`projectmanageid`, `type`, `document`, `created_date`, `created_by`) VALUES ('$projectmanageid', 'Review Document', '$review_document', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    for($i = 0; $i < count($_POST['support_review_link']); $i++) {
        $support_review_link = $_POST['support_review_link'][$i];

        if($support_review_link != '') {
            $query_insert_client_doc = "INSERT INTO `project_manage_document_link` (`projectmanageid`, `type`, `link`, `created_date`, `created_by`) VALUES ('$projectmanageid', 'Review Link', '$support_review_link', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    //Document

    echo '<script type="text/javascript"> window.location.replace("project_manage.php"); </script>';

   // mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
    $(document).ready(function () {

        $("#form1").submit(function( event ) {
            var category = $("#category").val();
            var sub_category = $("#sub_category").val();

            var code = $("input[name=code]").val();
            var name = $("input[name=name]").val();
            var category_name = $("input[name=category_name]").val();
            var sub_category_name = $("input[name=sub_category_name]").val();

            if (code == '' || category == '' || sub_category == '' || name == '') {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
            if(((category == 'Other') && (category_name == '')) || ((sub_category == 'Other') && (sub_category_name == ''))) {
                //alert("Please make sure you have filled in all of the required fields.");
                //return false;
            }
        });

});

</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised();
?>
<div class="container">
  <div class="row">

		<h1	class="triple-pad-bottom">Add A	New	Project Model</h1>

		<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

		<?php
        $businessid = '';
        $contactid = '';
        $ratecardid = '';
        $short_name = '';
        $piece_work = '';
        $add_to_helpdesk = '';
        $heading = '';
        $location = '';
        $job_number = '';
        $afe_number = '';
        $created_date = '';
        $start_date = '';
        $estimated_completion_date = '';
        $work_performed_date = '';
        $detail_issue = '';
        $detail_problem = '';
        $detail_gap = '';
        $detail_technical_uncertainty = '';
        $detail_base_knowledge = '';
        $detail_do = '';
        $detail_already_known = '';
        $detail_sources = '';
        $detail_current_designs = '';
        $detail_known_techniques = '';
        $detail_review_needed = '';
        $detail_looking_to_achieve = '';
        $detail_plan = '';
        $detail_next_steps = '';
        $detail_learnt = '';
        $detail_discovered = '';
        $detail_tech_advancements = '';
        $detail_work = '';
        $detail_adjustments_needed = '';
        $detail_future_designs = '';
        $detail_objective = '';
        $detail_targets = '';
        $detail_audience = '';
        $detail_strategy = '';
        $detail_desired_outcome = '';
        $detail_actual_outcome = '';
        $detail_check = '';
        $doing_start_date = '';
        $doing_end_date = '';
        $internal_qa_date = '';
        $client_qa_date = '';
        $to_do_date = '';
        $deliverable_date = '';
        $estimated_time_to_complete_work = '';
        $project_path = '';
        $milestone_timeline = '';
        $service_type = '';
        $service_category = '';
        $service_heading = '';
        $description = '';
        $notes = '';
        $status = '';
        $assign_to = '';
        $doing_assign_to = '';
        $internal_qa_assign_to = '';
        $client_qa_assign_to = '';
        $url_type = '';
        if(!empty($_GET['type'])) {
            $url_type = 'view';
        }

		if(!empty($_GET['projectmanageid']))	{

			$projectmanageid = $_GET['projectmanageid'];
			$get_project_manage =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	project_manage WHERE	projectmanageid='$projectmanageid'"));
			$get_project_manage_detail =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	project_manage_detail WHERE	projectmanageid='$projectmanageid'"));
			$get_contact =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	project_manage_budget WHERE	projectmanageid='$projectmanageid'"));

            $businessid = $get_project_manage['businessid'];
            $contactid = $get_project_manage['contactid'];
            $ratecardid = $get_project_manage['ratecardid'];
            $short_name = $get_project_manage['short_name'];

            $piece_work = $get_project_manage['piece_work'];
            $add_to_helpdesk = $get_project_manage['add_to_helpdesk'];
            $heading = $get_project_manage['heading'];
            $location = $get_project_manage['location'];
            $job_number = $get_project_manage['job_number'];
            $afe_number = $get_project_manage['afe_number'];
            $created_date = $get_project_manage['created_date'];
            $start_date = $get_project_manage['start_date'];
            $estimated_completion_date = $get_project_manage['estimated_completion_date'];
            $work_performed_date = $get_project_manage['work_performed_date'];

            $detail_issue = html_entity_decode($get_project_manage_detail['detail_issue']);
            $detail_problem = html_entity_decode($get_project_manage_detail['detail_problem']);
            $detail_gap = html_entity_decode($get_project_manage_detail['detail_gap']);
            $detail_technical_uncertainty = html_entity_decode($get_project_manage_detail['detail_technical_uncertainty']);
            $detail_base_knowledge = html_entity_decode($get_project_manage_detail['detail_base_knowledge']);
            $detail_do = html_entity_decode($get_project_manage_detail['detail_do']);
            $detail_already_known = html_entity_decode($get_project_manage_detail['detail_already_known']);
            $detail_sources = html_entity_decode($get_project_manage_detail['detail_sources']);
            $detail_current_designs = html_entity_decode($get_project_manage_detail['detail_current_designs']);

            $detail_known_techniques = html_entity_decode($get_project_manage_detail['detail_known_techniques']);
            $detail_review_needed = html_entity_decode($get_project_manage_detail['detail_review_needed']);
            $detail_looking_to_achieve = html_entity_decode($get_project_manage_detail['detail_looking_to_achieve']);
            $detail_plan = html_entity_decode($get_project_manage_detail['detail_plan']);
            $detail_next_steps = html_entity_decode($get_project_manage_detail['detail_next_steps']);
            $detail_learnt = html_entity_decode($get_project_manage_detail['detail_learnt']);
            $detail_discovered = html_entity_decode($get_project_manage_detail['detail_discovered']);
            $detail_tech_advancements = html_entity_decode($get_project_manage_detail['detail_tech_advancements']);
            $detail_work = html_entity_decode($get_project_manage_detail['detail_work']);
            $detail_adjustments_needed = html_entity_decode($get_project_manage_detail['detail_adjustments_needed']);
            $detail_future_designs = html_entity_decode($get_project_manage_detail['detail_future_designs']);
            $detail_objective = html_entity_decode($get_project_manage_detail['detail_objective']);
            $detail_targets = html_entity_decode($get_project_manage_detail['detail_targets']);
            $detail_audience = html_entity_decode($get_project_manage_detail['detail_audience']);
            $detail_strategy = html_entity_decode($get_project_manage_detail['detail_strategy']);
            $detail_desired_outcome = html_entity_decode($get_project_manage_detail['detail_desired_outcome']);
            $detail_actual_outcome = html_entity_decode($get_project_manage_detail['detail_actual_outcome']);
            $detail_check = html_entity_decode($get_project_manage_detail['detail_check']);

            $doing_start_date = $get_project_manage['doing_start_date'];
            $doing_end_date = $get_project_manage['doing_end_date'];
            $internal_qa_date = $get_project_manage['internal_qa_date'];
            $client_qa_date = $get_project_manage['client_qa_date'];
            $to_do_date = $get_project_manage['to_do_date'];
            $deliverable_date = $get_project_manage['deliverable_date'];
            $estimated_time_to_complete_work = explode(':',$get_project_manage['estimated_time_to_complete_work']);
            $project_path = $get_project_manage['project_path'];
            $milestone_timeline = $get_project_manage['milestone_timeline'];
            $service_type = $get_project_manage['service_type'];
            $service_category = $get_project_manage['service_category'];
            $service_heading = $get_project_manage['service_heading'];
            $description = html_entity_decode($get_project_manage_detail['description']);
            $notes = html_entity_decode($get_project_manage_detail['notes']);
            $status = $get_project_manage['status'];
            $assign_to = $get_project_manage['assign_to'];
            $doing_assign_to = $get_project_manage['doing_assign_to'];
            $internal_qa_assign_to = $get_project_manage['internal_qa_assign_to'];
            $client_qa_assign_to = $get_project_manage['client_qa_assign_to'];

            $created_date = date('Y-m-d');
            $login_id = $_SESSION['contactid'];
            // AND timer_type='Break' AND end_time IS NULL

            $get_ticket_timer = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT start_timer_time, timer_type FROM project_manage_assign_to_timer WHERE projectmanageid='$projectmanageid' AND created_by='$login_id' AND created_date='$created_date' ORDER BY assigntotimerid DESC LIMIT 1"));

            $start_time = $get_ticket_timer['start_timer_time'];
            $timer_type = $get_ticket_timer['timer_type'];

            if($start_time == '0' || $start_time == '') {
                $time_seconds = 0;
            } else {
                $time_seconds = (time()-$start_time);
            }

		?>
		<input type="hidden" id="projectmanageid"	name="projectmanageid" value="<?php echo $projectmanageid ?>" />
        <input type="hidden" class="start_time" value="<?php echo $time_seconds ?>">
        <input type="hidden" id="timer_type" value="<?php echo $timer_type ?>" />
		<?php	} ?>

        <div class="panel-group" id="accordion2">

        <?php
        $query_pm = mysqli_query($dbc,"SELECT accordion, project_manage FROM field_config_project_manage WHERE	accordion IS NOT NULL AND `order` IS NOT NULL ORDER BY `order`");

        $j=0;
        while($row_pm = mysqli_fetch_array($query_pm)) {
        ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?php echo $j;?>" >
                        <?php echo $row_pm['accordion']; ?><span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_<?php echo $j;?>" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php
                    $accordion = $row_pm['accordion'];
                    $value_config = ','.$row_pm['project_manage'].',';

                    include ('add_project_manage_fields.php');

                    ?>

                </div>
            </div>
        </div>
        <?php $j++; } ?>

        </div>

		<div class="form-group">
			<div class="col-sm-4">
				<p><span class="brand-color pull-right"><em>Required	Fields *</em></span></p>
			</div>
			<div class="col-sm-8"></div>
		</div>

		  <div class="form-group">
			<div class="col-sm-4 clearfix">
				<!--<a href="project_manage.php?category=<?php echo $category; ?>"	class="btn brand-btn btn-lg pull-right">Back</a>-->
				<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>
                <?php if($url_type = '') { ?>
				<button	type="submit" name="submit"	value="Submit" class="btn brand-btn btn-lg	pull-right">Submit</button>
                <?php } ?>
			</div>
		  </div>

        

		</form>

	</div>
  </div>

<?php include ('../footer.php'); ?>