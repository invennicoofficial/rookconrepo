<?php
/*
Add	Inventory
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {
    $tab = $_POST['tab'];
    $tile = $_POST['tile'];

    $created_by = $_SESSION['contactid'];
    $today_date = date('Y-m-d');
    $businessid = $_POST['businessid'];
    $contactid = $_POST['business_contactid'];
    $ratecardid = $_POST['ratecardid'];
    $short_name = filter_var($_POST['short_name'],FILTER_SANITIZE_STRING);
	$unique_id=$_POST['unique_id'];
	$detail_workorder=$_POST['workorder'];
	$detail_procedureid=$_POST['procedureid'];
	$detail_quote=$_POST['quote'];
	$detail_dwg=$_POST['dwg'];
	$detail_quantity=$_POST['quantity'];
	$detail_sn=$_POST['sn'];
	$detail_totalprojectbudget=$_POST['totalprojectbudget'];
	$effective_date=$_POST['effective_date'];

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
    $expiration_date = filter_var($_POST['expiration_date'],FILTER_SANITIZE_STRING);

    $task_start_date = filter_var($_POST['task_start_date'],FILTER_SANITIZE_STRING);
    $time_clock_start_date = filter_var($_POST['time_clock_start_date'],FILTER_SANITIZE_STRING);

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

    $general_description = filter_var(htmlentities($_POST['general_description']),FILTER_SANITIZE_STRING);
    $fabrication = filter_var(htmlentities($_POST['fabrication']),FILTER_SANITIZE_STRING);
    $paint = filter_var(htmlentities($_POST['paint']),FILTER_SANITIZE_STRING);
    $structure = filter_var(htmlentities($_POST['structure']),FILTER_SANITIZE_STRING);
    $rigging = filter_var(htmlentities($_POST['rigging']),FILTER_SANITIZE_STRING);
    $sandblast = filter_var(htmlentities($_POST['sandblast']),FILTER_SANITIZE_STRING);
    $primer = filter_var(htmlentities($_POST['primer']),FILTER_SANITIZE_STRING);
    $foam = filter_var(htmlentities($_POST['foam']),FILTER_SANITIZE_STRING);
    $rockguard = filter_var(htmlentities($_POST['rockguard']),FILTER_SANITIZE_STRING);

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
    $status = $_POST['project_status'];
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
		$history = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Added on '.date('Y-m-d H:i:s').'<br>';

        $query_insert_project_manage = "INSERT INTO `project_manage` (`tile`, `tab`, `status`, `unique_id`, `businessid`, `contactid`, `ratecardid`, `short_name`, `piece_work`, `add_to_helpdesk`, `heading`, `location`, `job_number`, `afe_number`, `created_date`, `start_date`, `estimated_completion_date`, `work_performed_date`, `expiration_date`, `effective_date`, `project_path`, `milestone_timeline`, `service_type`, `service_category`, `service_heading`, `doing_start_date`, `doing_end_date`, `internal_qa_date`, `client_qa_date`, `assign_to`, `doing_assign_to`, `internal_qa_assign_to`, `client_qa_assign_to`, `to_do_date`, `deliverable_date`, `estimated_time_to_complete_work`,`history`, `task_start_date`, `time_clock_start_date`) VALUES ('$tile', '$tab', '$status', '$unique_id', '$businessid', '$contactid', '$ratecardid', '$short_name', '$piece_work', '$add_to_helpdesk', '$heading', '$location', '$job_number', '$afe_number', '$created_date', '$start_date', '$estimated_completion_date', '$work_performed_date', '$expiration_date','$effective_date', '$project_path', '$milestone_timeline', '$service_type', '$service_category', '$service_heading', '$$doing_start_date', '$doing_end_date', '$internal_qa_date', '$client_qa_date', '$assign_to', '$doing_assign_to', '$internal_qa_assign_to', '$client_qa_assign_to', '$to_do_date', '$deliverable_date', '$estimated_time_to_complete_work', '$history', '$task_start_date', '$time_clock_start_date')";
        $result_insert_project_manage = mysqli_query($dbc, $query_insert_project_manage);
        $projectmanageid = mysqli_insert_id($dbc);

        $query_insert_detail = "INSERT INTO `project_manage_detail` (`projectmanageid`, `detail_issue`, `detail_problem`, `detail_gap`, `detail_technical_uncertainty`, `detail_base_knowledge`, `detail_do`, `detail_already_known`, `detail_sources`, `detail_current_designs`, `detail_known_techniques`, `detail_review_needed`, `detail_looking_to_achieve`, `detail_plan`, `detail_next_steps`, `detail_learnt`,  `detail_discovered`,  `detail_tech_advancements`, `detail_work`, `detail_adjustments_needed`, `detail_future_designs`, `detail_check`, `detail_objective`, `detail_targets`, `detail_audience`, `detail_strategy`, `detail_desired_outcome`, `detail_actual_outcome`, `detail_workorder`, `detail_procedure_id`, `detail_quote`, `detail_dwg`, `detail_quantity`, `detail_sn`, `detail_total_project_budget`, `description`, `notes`, `general_description`, `fabrication`, `paint`, `structure`, `rigging`, `sandblast`, `primer`, `foam`, `rockguard`) VALUES ('$projectmanageid', '$detail_issue', '$detail_problem', '$detail_gap', '$detail_technical_uncertainty', '$detail_base_knowledge', '$detail_do', '$detail_already_known', '$detail_sources', '$detail_current_designs', '$detail_known_techniques', '$detail_review_needed', '$detail_looking_to_achieve', '$detail_plan', '$detail_next_steps', '$detail_learnt',  '$detail_discovered',  '$detail_tech_advancements', '$detail_work',  '$detail_adjustments_needed', '$detail_future_designs', '$detail_check', '$detail_objective', '$detail_targets', '$detail_audience', '$detail_strategy', '$detail_desired_outcome', '$detail_actual_outcome','$detail_workorder', '$detail_procedureid', '$detail_quote', '$detail_dwg', '$detail_quantity', '$detail_sn', '$detail_totalprojectbudget', '$description', '$notes', '$general_description', '$fabrication', '$paint', '$structure', '$rigging', '$sandblast', '$primer', '$foam', '$rockguard')";
        $result_insert_detail = mysqli_query($dbc, $query_insert_detail);

        $query_insert_detail = "INSERT INTO `project_manage_budget` (`projectmanageid`, `completion_date`, `budget_price`, `financial_cost`, `financial_price`, `financial_plus_minus`, `package`, `promotion`, `material`, `services`, `products`, `sred`, `labour`, `client`, `customer`, `inventory`, `equipment`, `staff`, `contractor`, `expense`, `vendor`, `custom`, `other_detail`, `total_price`, `estimate_data`, `review_profit_loss`, `review_budget`, `status`, `when_added`, `history`, `follow_up_date`, `quote_send_date`, `deleted`, `front_company_logo`, `front_client_logo`, `front_client_info`, `front_other_info`, `front_content_pages`) VALUES ('$projectmanageid', '$completion_date', '$budget_price', '$financial_cost', '$financial_price', '$financial_plus_minus', '$package', '$promotion', '$material', '$services', '$products', '$sred', '$labour', '$client', '$customer', '$inventory', '$equipment', '$staff', '$contractor', '$expense', '$vendor', '$custom', '$other_detail', '$total_price', '$html_1', '$review_profit_loss_1', '$review_budget_1', '$status', '$today_date', '$history', '$follow_up_date', '$quote_send_date', '$deleted', '$front_company_logo', '$front_client_logo', '$front_client_info', '$front_other_info', '$front_content_pages')";
        $result_insert_detail = mysqli_query($dbc, $query_insert_detail);

        $url = 'Added';
    } else if(empty($_GET['tab_from_tile_view'])) {
		$history = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Edit on '.date('Y-m-d H:i:s').'<br>';
        $projectmanageid = $_POST['projectmanageid'];
        $query_update_project_manage = "UPDATE `project_manage` SET  `status` = '$status', `businessid` = '$businessid', `contactid` = '$contactid', `ratecardid` = '$ratecardid', `short_name` = '$short_name', `piece_work` = '$piece_work', `add_to_helpdesk` = '$add_to_helpdesk', `heading` = '$heading', `location` = '$location', `job_number` = '$job_number', `afe_number` = '$afe_number', `created_date` = '$created_date', `start_date` = '$start_date', `estimated_completion_date` = '$estimated_completion_date', `work_performed_date` = '$work_performed_date', `expiration_date` = '$expiration_date', `effective_date` = '$effective_date', `project_path` = '$project_path', `milestone_timeline` = '$milestone_timeline', `service_type` = '$service_type', `service_category` = '$service_category', `service_heading` = '$service_heading', `doing_start_date` = '$doing_start_date', `doing_end_date` = '$doing_end_date', `internal_qa_date` = '$internal_qa_date', `client_qa_date` = '$client_qa_date', `assign_to` = '$assign_to', `doing_assign_to` = '$doing_assign_to', `internal_qa_assign_to` = '$internal_qa_assign_to', `client_qa_assign_to` = '$client_qa_assign_to', `to_do_date` = '$to_do_date', `deliverable_date` = '$deliverable_date', `estimated_time_to_complete_work` = '$estimated_time_to_complete_work', `history` = CONCAT(history,'$history'), `task_start_date` = '$task_start_date', `time_clock_start_date` = '$time_clock_start_date'  WHERE `projectmanageid` = $projectmanageid";
        $result_update_project_manage	= mysqli_query($dbc, $query_update_project_manage);

        $query_insert_detail = "INSERT INTO `project_manage_detail` (`projectmanageid`, `detail_issue`, `detail_problem`, `detail_gap`, `detail_technical_uncertainty`, `detail_base_knowledge`, `detail_do`, `detail_already_known`, `detail_sources`, `detail_current_designs`, `detail_known_techniques`, `detail_review_needed`, `detail_looking_to_achieve`, `detail_plan`, `detail_next_steps`, `detail_learnt`,  `detail_discovered`,  `detail_tech_advancements`, `detail_work`, `detail_adjustments_needed`, `detail_future_designs`, `detail_check`, `detail_objective`, `detail_targets`, `detail_audience`, `detail_strategy`, `detail_desired_outcome`, `detail_actual_outcome`, `detail_workorder`, `detail_procedure_id`, `detail_quote`, `detail_dwg`, `detail_quantity`, `detail_sn`, `detail_total_project_budget`, `description`, `notes`, `general_description`, `fabrication`, `paint`, `structure`, `rigging`, `sandblast`, `primer`, `foam`, `rockguard`) VALUES ('$projectmanageid', '$detail_issue', '$detail_problem', '$detail_gap', '$detail_technical_uncertainty', '$detail_base_knowledge', '$detail_do', '$detail_already_known', '$detail_sources', '$detail_current_designs', '$detail_known_techniques', '$detail_review_needed', '$detail_looking_to_achieve', '$detail_plan', '$detail_next_steps', '$detail_learnt',  '$detail_discovered',  '$detail_tech_advancements', '$detail_work',  '$detail_adjustments_needed', '$detail_future_designs', '$detail_check', '$detail_objective', '$detail_targets', '$detail_audience', '$detail_strategy', '$detail_desired_outcome', '$detail_actual_outcome','$detail_workorder', '$detail_procedureid', '$detail_quote', '$detail_dwg', '$detail_quantity', '$detail_sn', '$detail_totalprojectbudget', '$description', '$notes', '$general_description', '$fabrication', '$paint', '$structure', '$rigging', '$sandblast', '$primer', '$foam', '$rockguard')";
        $result_insert_detail = mysqli_query($dbc, $query_insert_detail);
        //$query_update_report = "UPDATE `project_manage_detail` SET `detail_issue` = '$detail_issue', `detail_problem` = '$detail_problem', `detail_technical_uncertainty` = '$detail_technical_uncertainty', `detail_base_knowledge` = '$detail_base_knowledge', `detail_do` = '$detail_do', `detail_already_known` = '$detail_already_known', `detail_sources` = '$detail_sources', `detail_current_designs` = '$detail_current_designs', `detail_known_techniques` = '$detail_known_techniques', `detail_review_needed` = '$detail_review_needed', `detail_looking_to_achieve` = '$detail_looking_to_achieve', `detail_plan` = '$detail_plan', `detail_next_steps` = '$detail_next_steps', `detail_learnt` = '$detail_learnt', `detail_discovered` = '$detail_discovered', `detail_tech_advancements` = '$detail_tech_advancements', `detail_work` = '$detail_work', `detail_adjustments_needed` = '$detail_adjustments_needed', `detail_future_designs` = '$detail_future_designs', `detail_check` = '$detail_check', `detail_objective` = '$detail_objective', `detail_gap` = '$detail_gap', `detail_targets` = '$detail_targets', `detail_audience` = '$detail_audience', `detail_strategy` = '$detail_strategy', `detail_desired_outcome` = '$detail_desired_outcome', `detail_actual_outcome` = '$detail_actual_outcome', `detail_workorder` = '$detail_workorder',`detail_procedure_id` = '$detail_procedureid',`detail_quote` = '$detail_quote',`detail_dwg` = '$detail_dwg',`detail_quantity` = '$detail_quantity',`detail_sn` = '$detail_sn',`detail_total_project_budget` = '$detail_totalprojectbudget', `description` = '$description', `notes` = '$notes', `general_description` = '$general_description', `fabrication` = '$fabrication', `paint` = '$paint', `structure` = '$structure', `rigging` = '$rigging', `sandblast` = '$sandblast', `primer` = '$primer', `foam` = '$foam', `rockguard` = '$rockguard'  WHERE `projectmanageid` = $projectmanageid";
        //$result_update_report = mysqli_query($dbc, $query_update_report);

        $query_update_report = "UPDATE `project_manage_budget` SET `completion_date` = '$completion_date', `budget_price` = '$budget_price', `financial_cost` = '$financial_cost', `financial_price` = '$financial_price', `financial_plus_minus` = '$financial_plus_minus', `package` = '$package', `promotion` = '$promotion', `material` = '$material', `services` = '$services', `products` = '$products', `sred` = '$sred', `labour` = '$labour', `client` = '$client', `customer` = '$customer', `inventory` = '$inventory', `equipment` = '$equipment', `staff` = '$staff', `contractor` = '$contractor', `expense` = '$expense', `vendor` = '$vendor', `custom` = '$custom', `other_detail` = '$other_detail', `total_price` = '$total_price', `estimate_data` = '$html_1', `review_profit_loss` = '$review_profit_loss_1', `review_budget` = '$review_budget_1', `when_added` = '$today_date', `history` = '$history', `follow_up_date` = '$follow_up_date', `quote_send_date` = '$quote_send_date', `front_company_logo` = '$front_company_logo', `front_client_logo` = '$front_client_logo', `front_client_info` = '$front_client_info', `front_other_info` = '$front_other_info', `front_content_pages` = '$front_content_pages' WHERE `projectmanageid` = $projectmanageid";
        $result_update_report = mysqli_query($dbc, $query_update_report);

        $url = 'Updated';
    }

    //Timer
    $timer = $_POST['timer'];
    $regular_hrs = $_POST['regular_hrs'];
    $overtime_hrs = $_POST['overtime_hrs'];
    $travel_hrs = $_POST['travel_hrs'];
    $subsist_hrs = $_POST['subsist_hrs'];
    $end_time = date('g:i A');

    $start_time = 0;
    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
		// Round times to the nearest 15 minutes
		$timer = date('H:i:s', round(strtotime($timer)/(15*60))*(15*60));
		$regular_hrs = date('H:i:s', round(strtotime($regular_hrs)/(15*60))*(15*60));
		$overtime_hrs = date('H:i:s', round(strtotime($overtime_hrs)/(15*60))*(15*60));
		$travel_hrs = date('H:i:s', round(strtotime($travel_hrs)/(15*60))*(15*60));
		$subsist_hrs = date('H:i:s', round(strtotime($subsist_hrs)/(15*60))*(15*60));
		$query_update_ticket = "UPDATE `project_manage_assign_to_timer` SET `end_time` = '$end_time', `start_timer_time` = '$start_time', `timer` = '$timer', `regular_hrs`='$regular_hrs', `overtime_hrs`='$overtime_hrs', `travel_hrs`='$travel_hrs', `subsist_hrs`='$subsist_hrs' WHERE `projectmanageid` = '$projectmanageid' AND created_by='$created_by' AND created_date='$today_date' AND end_time IS NULL";
		$result_update_ticket = mysqli_query($dbc, $query_update_ticket);

		$query_update_ticket = "UPDATE `project_manage` SET `start_time` = '0' WHERE `projectmanageid` = '$projectmanageid'";
		$result_update_ticket = mysqli_query($dbc, $query_update_ticket);
    }
	else if(!empty($_POST['timer_staff'])) {
		for ( $i=0; $i<count($_POST['timer_staff']); $i++ ) {
			$query_insert_timer = "INSERT INTO `project_manage_assign_to_timer` (`projectmanageid`, `timer_type`, `timer`, `regular_hrs`, `overtime_hrs`, `travel_hrs`, `subsist_hrs`, `start_time`, `end_time`, `timer_task`, `created_by`, `created_date`) VALUES ('$projectmanageid', 'Work', '{$_POST['duration'][$i]}', '{$_POST['regular_hrs'][$i]}', '{$_POST['overtime_hrs'][$i]}', '{$_POST['travel_hrs'][$i]}', '{$_POST['subsist_hrs'][$i]}', '{$_POST['start_clock'][$i]}', '{$_POST['end_clock'][$i]}', '{$_POST['task'][$i]}', '{$_POST['timer_staff'][$i]}', '{$_POST['timer_date'][$i]}')";
            $result_insert_timer = mysqli_query($dbc, $query_insert_timer);
        }
        /*
        $query_insert_timer = "INSERT INTO `project_manage_assign_to_timer` (`projectmanageid`, `timer_type`, `timer`, `start_time`, `end_time`, `timer_task`, `created_by`, `created_date`)
			VALUES ('$projectmanageid', 'Work', '{$_POST['duration']}', '{$_POST['start_clock']}', '{$_POST['end_clock']}', '{$_POST['task']}', '{$_POST['timer_staff']}', '{$_POST['timer_date']}')";
		echo $query_insert_timer;
		$result_insert_timer = mysqli_query($dbc, $query_insert_timer);
        */
	}
    //Timer
	
	// Update Timer
	foreach($_POST['project_timer_id'] as $i => $timerid) {
		$start_clock = '';
		$end_clock = '';
		$timer_date = '';
		$duration = '';
		$regular_hrs = '';
		$overtime_hrs = '';
		$travel_hrs = '';
		$subsist_hrs = '';
		if(!empty($_POST['timer_date'][$i])) {
			$timer_date = $_POST['timer_date'][$i];
		}
		if(!empty($_POST['duration'][$i])) {
			$duration = $_POST['duration'][$i];
		}
		if(!empty($_POST['regular_hrs'][$i])) {
			$regular_hrs = $_POST['regular_hrs'][$i];
		} else {
			$regular_hrs = $duration;
		}
		if(!empty($_POST['overtime_hrs'][$i])) {
			$overtime_hrs = $_POST['overtime_hrs'][$i];
		}
		if(!empty($_POST['travel_hrs'][$i])) {
			$travel_hrs = $_POST['travel_hrs'][$i];
		}
		if(!empty($_POST['subsist_hrs'][$i])) {
			$subsist_hrs = $_POST['subsist_hrs'][$i];
		}
		if(!empty($_POST['start_clock'][$i])) {
			$start_clock = $_POST['start_clock'][$i];
		}
		if(!empty($_POST['end_clock'][$i])) {
			$end_clock = $_POST['end_clock'][$i];
		}
		if($start_clock != '' || $end_clock != '' || $duration != '') {
			$current_values = mysqli_fetch_array(mysqli_query($dbc, "SELECT `start_time`, `end_time`, `timer` FROM `project_manage_assign_to_timer` WHERE `assigntotimerid`='".$timerid."'"));
			if($duration == $current_values['timer'] && ($start_clock != $current_values['start_time'] || $end_clock != $current_values['end_time'])) {
				$duration = "TIMEDIFF(STR_TO_DATE( '$end_clock', '%l:%i %p' ), STR_TO_DATE( '$start_clock', '%l:%i %p' ))";
			} else {
				$duration = "'$duration'";
			}
			$query_update_ticket = "UPDATE `project_manage_assign_to_timer` SET `start_time`='$start_clock', `end_time`='$end_clock', `created_date`='$timer_date', `timer`=$duration, `regular_hrs`='$regular_hrs', `overtime_hrs`='$overtime_hrs', `travel_hrs`='$travel_hrs', `subsist_hrs`='$subsist_hrs' WHERE `assigntotimerid`='".$timerid."'";
			mysqli_query($dbc, $query_update_ticket);
		}
	}
	// Update Timer

    //Document
    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        $document = htmlspecialchars($_FILES["upload_document"]["name"][$i], ENT_QUOTES);

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
	if($status == 'Approved' && $tab == 'Pending Work Order') {
		$tab = 'Shop Work Order';
	}
    echo '<script type="text/javascript"> window.location.replace("'.$_POST['return_to'].'"); </script>';

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
$title = "Add A New Project Model";
$return_url = "project_workflow_dashboard.php?tile=".$_GET['tile']."&tab=".$_GET['tab'];
if(isset($_GET['tab_from_tile_view'])) {
	$tab_from_tile_view = $_GET['tab_from_tile_view'];
	$title = "Time Clock Timer";
	switch($tab_from_tile_view) {
		case 'Shop Time Sheets':
			$return_url = "project_workflow_dashboard.php?tile=".$_GET['tile']."&tab=".$_GET['tab_from_tile_view'];
			$return_contact = "&contactid=".$_GET['contactid'];
			break;
		case 'Shop Time Clock':
			$return_url = "project_workflow_dashboard.php?tile=".$_GET['tile']."&tab=".$_GET['tab'];
			$return_contact = "&contactid=".$_GET['contactid'];
			break;
		case 'Time Clock':
			$return_url = "../Punch Card/punch_card.php";
			$return_contact = "?contactid=".$_GET['contactid'];
			break;
		case 'Shop Work Order':
			$title = 'Add Time Sheet';
			$return_url = "project_workflow_dashboard.php?tile=".$_GET['tile']."&tab=Shop Work Order";
			$return_contact = "?contactid=".$_GET['contactid'];
			break;
	}

}
else if(!empty($_GET['from_url'])) {
	$title = "Add A New Project Model";
	$return_url = urldecode($_GET['from_url']);
}
?>
<div class="container">
  <div class="row">

		<h1	class="triple-pad-bottom"><?php echo $title; ?></h1>

		<div class="pad-left double-gap-bottom"><a href="<?php echo $return_url; ?>" class="btn config-btn">Back to Dashboard</a></div>

		<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

		<?php
        $tile=$_GET['tile'];
        $tab_url = $_GET['tab'];
		$status = '';
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
        $expiration_date = '';
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
		$detail_workorder='';
		$detail_procedureid='';
		$detail_quote='';
		$detail_dwg='';
		$detail_quantity='';
		$detail_sn='';
		$detail_totalprojectbudget='';
		$effective_date='';
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

        $general_description = '';
        $fabrication = '';
        $paint = '';
        $structure = '';
        $rigging = '';
        $sandblast = '';
        $primer = '';
        $foam = '';
        $rockguard = '';

        $notes = '';
        $assign_to = '';
        $doing_assign_to = '';
        $internal_qa_assign_to = '';
        $client_qa_assign_to = '';
        $unique_id = '';
        $task_start_date = '';
        $time_clock_start_date = '';
		$start_clock = '';
		$end_clock = '';
		$duration = '';
		$regular_hrs = '';
		$overtime_hrs = '';
		$travel_hrs = '';
		$subsist_hrs = '';
		$task = '';
        $display_type = '';
        if(!empty($_GET['display_type'])) {
            $display_type = 'view';
        }
		$projectmanageid = (empty($_GET['projectmanageid']) ? null : $_GET['projectmanageid']);
		$timerid = '';

		if(!empty($_GET['timerid'])) {
			$timerid = $_GET['timerid'];
			$get_ticket_timer = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `project_manage_assign_to_timer` WHERE `assigntotimerid`='".$_GET['timerid']."'"));
			$projectmanageid = $get_ticket_timer['projectmanageid'];
			$_GET['contactid'] = $get_ticket_timer['created_by'];
		}
		
        if($projectmanageid != null) {
			$get_project_manage =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	project_manage WHERE	projectmanageid='$projectmanageid'"));
			$result_detail = mysqli_query($dbc,"SELECT * FROM	project_manage_detail WHERE	projectmanageid='$projectmanageid' ORDER BY detailid DESC");
			$get_project_manage_detail =	mysqli_fetch_assoc($result_detail);
			$get_contact =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	project_manage_budget WHERE	projectmanageid='$projectmanageid'"));

            $tile = $get_project_manage['tile'];
			$status = $get_project_manage['status'];

            if(empty($_GET['tab_from_tile_view'])) {
                $tab_url = $get_project_manage['tab'];
            } else {
                $tab_url = $_GET['tab'];
            }
            $unique_id = $get_project_manage['unique_id'];
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
            $expiration_date = $get_project_manage['expiration_date'];
			$effective_date = $get_project_manage['effective_date'];
            $task_start_date = $get_project_manage['task_start_date'];
            $time_clock_start_date = $get_project_manage['time_clock_start_date'];

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

			$detail_workorder=$get_project_manage_detail['detail_workorder'];
			$detail_procedureid = $get_project_manage_detail['detail_procedure_id'];
			$detail_quote = $get_project_manage_detail['detail_quote'];
			$detail_dwg = $get_project_manage_detail['detail_dwg'];
			$detail_quantity = $get_project_manage_detail['detail_quantity'];
			$detail_sn = $get_project_manage_detail['detail_sn'];
			$detail_totalprojectbudget = $get_project_manage_detail['detail_total_project_budget'];

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
			$details_date_updated = $get_project_manage_detail['detail_date'];
            $description = html_entity_decode($get_project_manage_detail['description']);
            $notes = html_entity_decode($get_project_manage_detail['notes']);

            $general_description = html_entity_decode($get_project_manage_detail['general_description']);
            $fabrication = html_entity_decode($get_project_manage_detail['fabrication']);
            $paint = html_entity_decode($get_project_manage_detail['paint']);
            $structure = html_entity_decode($get_project_manage_detail['structure']);
            $rigging = html_entity_decode($get_project_manage_detail['rigging']);
            $sandblast = html_entity_decode($get_project_manage_detail['sandblast']);
            $primer = html_entity_decode($get_project_manage_detail['primer']);
            $foam = html_entity_decode($get_project_manage_detail['foam']);
            $rockguard = html_entity_decode($get_project_manage_detail['rockguard']);

            $status = $get_project_manage['status'];
            $assign_to = $get_project_manage['assign_to'];
            $doing_assign_to = $get_project_manage['doing_assign_to'];
            $internal_qa_assign_to = $get_project_manage['internal_qa_assign_to'];
            $client_qa_assign_to = $get_project_manage['client_qa_assign_to'];

            $created_date = date('Y-m-d');
            // AND timer_type='Break' AND end_time IS NULL
            if(empty($_GET['contactid'])) {
                $login_id = $_SESSION['contactid'];
            } else {
                $login_id = $_GET['contactid'];
            }

            if($get_ticket_timer = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM project_manage_assign_to_timer WHERE projectmanageid='$projectmanageid' AND created_by='$login_id' AND assigntotimerid = '$timerid' ORDER BY assigntotimerid DESC LIMIT 1"))) {
				$start_time = $get_ticket_timer['start_timer_time'];
				$start_clock = date('h:i a', strtotime($get_ticket_timer['start_time']));
				$end_clock = date('h:i a', strtotime($get_ticket_timer['end_time']));
				$timer_date = $get_ticket_timer['created_date'];
				$duration = date('H:i', strtotime($get_ticket_timer['timer']));
				$regular_hrs = date('H:i', strtotime($get_ticket_timer['regular_hrs']));
				$overtime_hrs = date('H:i', strtotime($get_ticket_timer['overtime_hrs']));
				$travel_hrs = date('H:i', strtotime($get_ticket_timer['travel_hrs']));
				$subsist_hrs = date('H:i', strtotime($get_ticket_timer['subsist_hrs']));
				$timer_type = $get_ticket_timer['timer_type'];
				$task = $get_ticket_timer['timer_task'];
			}

            if($start_time == '0' || $start_time == '') {
                $time_seconds = 0;
            } else {
                $time_seconds = (time()-$start_time);
            }

			?>
			<input type="hidden" id="projectmanageid"	name="projectmanageid" value="<?php echo $projectmanageid ?>" />
			<input type="hidden" class="start_time" value="<?php echo $time_seconds ?>">
			<input type="hidden" id="timer_type" value="<?php echo $timer_type ?>" />
			<input type="hidden" id="timer_contactid" value="<?php echo $_GET['contactid'] ?>" /><?php
        
        } ?>

        <div class="panel-group" id="accordion2">
        <input type="hidden" name="tab" id="tab" value="<?php echo $tab_url; ?>" />
        <input type="hidden" name="tile" id="tile" value="<?php echo $_GET['tile']; ?>" />
        <input type="hidden" name="project_status" id="project_status" value="<?php echo $status; ?>" />
		<input type="hidden" name="return_to" value="<?php echo $return_url; ?>" />

        <?php
        $tile_get = $_GET['tile'];
        //$tab_get = $_GET['tab'];

        if($_GET['tab_from_tile_view'] == 'Time Clock' || $_GET['tab_from_tile_view'] == 'Shop Time Clock') {
			echo "<style>input[type='radio']:checked+label a { border-width: 0.35em; font-weight: bold; }</style>";

            $all_task = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT task FROM field_config_project_manage WHERE tile='$tile' AND tab='$tab_url' AND accordion= 'Deliverables'"));

            echo '<div class="form-group">
                <div class="col-sm-12">';

            $each_tab = explode(',', $all_task['task']);
			$radio_id = 0;
            foreach ($each_tab as $cat_tab) { ?>
				<div class="ffmbtnpic dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><input id="cat_tab_<?php echo $j; ?>" type="radio" style="display:none; /*height: 35px; width: 35px;*/" <?php if($task == $cat_tab) { echo "checked"; } ?>
						class="timer_task" name="task" value="<?php echo $cat_tab; ?>" onchange="if($('[name=task]').val() != '') { $('[name=start_timer_btn]').click(); }" />
					<label for="cat_tab_<?php echo $j++; ?>" style="width:100%;"><a style="display:block; padding-top:1em; width:100%;"><?php echo $cat_tab; ?></a></label></div>
			<?php }
            echo '</div></div>';

            include ('add_workorder_timer.php');

        } else {
            $query_pm = mysqli_query($dbc,"SELECT accordion, project_manage, status, task, unique_id_start FROM field_config_project_manage WHERE tile='$tile_get' AND tab='$tab_url' AND accordion IS NOT NULL AND `order` IS NOT NULL ORDER BY `order`");

            $j=1;
            while($row_pm = mysqli_fetch_array($query_pm)) {
                if($j==1){
                    $in='in';
                }else{
                    $in='';
                }
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?php echo $j; ?>" >
                            <?php echo $row_pm['accordion']; ?><span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_<?php echo $j; ?>" class="panel-collapse collapse <?php echo $in; ?>">
                    <div class="panel-body">
                        <?php
                        $accordion = $row_pm['accordion'];
                        $value_config = ','.$row_pm['project_manage'].',';

                        include ('add_project_manage_fields.php');

                        ?>

                    </div>
                </div>
            </div>
            <?php $j++; }

			if(mysqli_num_rows($result_detail) > 1) {
				$changes = [];
				$count = 0;
				mysqli_data_seek($result_detail, 0);
				$row = mysqli_fetch_assoc($result_detail);
				$date = '';
				foreach($row as $key => $value) {
					if($key == 'detail_date') {
						$date = $value;
					}
					else if($key != 'detailid' && $key != 'projectmanageid') {
						$changes[$key] = [['date'=>$date,'field'=>$value]];
					}
				}
				while($row = mysqli_fetch_assoc($result_detail)) {
					foreach($row as $key => $value) {
						if($key == 'detail_date') {
							$date = $value;
						}
						else if($key != 'detailid' && $key != 'projectmanageid') {
							if($changes[$key][count($changes[$key]) - 1]['field'] != $value) {
								$changes[$key][] = ['date'=>$date,'field'=>$value];
								if($key == 'fabrication' || $key == 'paint' || $key == 'rigging' || $key == 'structure') {
									$count++;
								}
							}
						}
					}
				}
				if($count > 0): ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_history_changes" >
									History of Detail Updates<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_history_changes" class="panel-collapse collapse <?php echo $in; ?>">
							<div class="panel-body">
								<table class='table table-bordered'>
								<?php foreach($changes as $key => $arr_changes) {
									if($key == 'fabrication') {
										echo "<tr><th colspan=2>Fabrication Changes</th></tr>
										<tr class='hidden-xs hidden-sm'><th>Date</th><th>Content</th></tr>";
										foreach($arr_changes as $changes) {
											echo '<tr>';
											echo '<td data-title="Date">'.$changes['date'].'</td>';
											echo '<td data-title="Content">'.html_entity_decode($changes['field']).'</td>';
											echo '</tr>';
										}
									}
									if($key == 'paint') {
										echo "<tr><th colspan=2>Paint Changes</th></tr>
										<tr class='hidden-xs hidden-sm'><th>Date</th><th>Content</th></tr>";
										foreach($arr_changes as $changes) {
											echo '<tr>';
											echo '<td data-title="Date">'.$changes['date'].'</td>';
											echo '<td data-title="Content">'.html_entity_decode($changes['field']).'</td>';
											echo '</tr>';
										}
									}
									if($key == 'rigging') {
										echo "<tr><th colspan=2>Rigging Changes</th></tr>
										<tr class='hidden-xs hidden-sm'><th>Date</th><th>Content</th></tr>";
										foreach($arr_changes as $changes) {
											echo '<tr>';
											echo '<td data-title="Date">'.$changes['date'].'</td>';
											echo '<td data-title="Content">'.html_entity_decode($changes['field']).'</td>';
											echo '</tr>';
										}
									}
									if($key == 'structure') {
										echo "<tr><th colspan=2>Structure Changes</th></tr>
										<tr class='hidden-xs hidden-sm'><th>Date</th><th>Content</th></tr>";
										foreach($arr_changes as $changes) {
											echo '<tr>';
											echo '<td data-title="Date">'.$changes['date'].'</td>';
											echo '<td data-title="Content">'.html_entity_decode($changes['field']).'</td>';
											echo '</tr>';
										}
									}
								} ?>
								</table>
							</div>
						</div>
					</div>
				<?php endif;
			}
        }
        ?>

        </div>

		<div class="form-group">
			<div class="col-sm-4">
				<p><span class="brand-color pull-right"><em>Required Fields *</em></span></p>
			</div>
			<div class="col-sm-8"></div>
		</div>

		  <div class="form-group">
			<div class="clearfix">
				<a href="<?php echo $return_url.$return_contact; ?>" class="btn brand-btn btn-lg pull-left">Back</a>
				<!--<a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile']; ?>&tab=<?php echo $_GET['tab']; ?>"	class="btn brand-btn btn-lg pull-left">Back</a>-->
				<!--<a href="project_manage.php?category=<?php echo $category; ?>"	class="btn brand-btn btn-lg pull-right">Back</a>
				<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
                <?php if(isset($_GET['tab_from_tile_view']) && empty($_GET['timerid']) && !empty($_GET['contactid'])): ?>
                    <!-- Show Nothing -->
                <?php else: ?>
                    <?php if($display_type == '') { ?>
                    <button	type="submit" name="submit"	value="Submit" class="btn brand-btn btn-lg	pull-right">Submit</button>
                    <?php } ?>
                <?php endif; ?>

			</div>
		  </div>

        

		</form>

	</div>
  </div>

<?php include ('../footer.php'); ?>