<?php //Daysheet functions
function daysheet_ticket_label ($dbc, $daysheet_ticket_fields, $ticket, $status_complete) {
    //Label stuff
    $label = '';
    if($ticket['businessid'] > 0 && in_array('Business', $daysheet_ticket_fields)) {
        $label .= get_client($dbc, $ticket['businessid']).'<br />';
    }
    if($ticket['projectid'] > 0 && in_array('Project', $daysheet_ticket_fields)) {
        if(!empty($label)) {
            $label .= ', ';
        }
        $label .= PROJECT_NOUN.' #'.$ticket['projectid'].' '.get_project($dbc,$ticket['projectid'],'project_name').'<br />';
    }
    $label .= get_ticket_label($dbc, $ticket);
    if(($ticket['delivery_type'] == 'warehouse') && in_array('Warehouse Indicator', $daysheet_ticket_fields)) {
        $label .= '<br />Warehouse';
    }
    if(($ticket['clientid'] > 0 || !empty($ticket['client_name'])) && in_array('Customer', $daysheet_ticket_fields)) {
        if($ticket['ticket_table'] == 'ticket_schedule') {
            $label .= '<br />Customer: '.$ticket['client_name'];
        } else {
            $label .= '<br />Customer: '.get_contact($dbc, $ticket['clientid']);
        }
    }
    if(!empty($ticket['delivery_type']) && in_array('Delivery Type', $daysheet_ticket_fields)) {
        $label .= '<br />Delivery Type: '.ucfirst($ticket['delivery_type']);
    }
    if(!empty($ticket['address']) && in_array('Address', $daysheet_ticket_fields)) {
        $label .= '<br />Address: '.$ticket['address'];
    }
    if((!empty($ticket['address']) || !empty($ticket['map_link'])) && in_array('Map Link', $daysheet_ticket_fields)) {
        $map_link = json_encode(!empty($ticket['map_link']) ? $ticket['map_link'] : 'http://maps.google.com/maps/place/'.$ticket['address'].','.$ticket['city']);
        $label .= '<br />Google Maps Link: <span onclick="googleMapsLink(this);" data-href=\''.$map_link.'\'><u class="no-slider">Click Here</u></span>';
    }
    if(!empty($ticket['to_do_start_time']) && in_array('Start Time', $daysheet_ticket_fields)) {
        $label .= '<br />Time: '.date('h:i a', strtotime($ticket['to_do_start_time']));
    }
    if(!empty($ticket['eta']) && in_array('ETA', $daysheet_ticket_fields)) {
        $label .= '<br />ETA: '.$ticket['eta'];
    }
    if(!empty($ticket['availability']) && $ticket['availability'] != '00:00:00 - 00:00:00' && in_array('Availability', $daysheet_ticket_fields)) {
        $label .= '<br />Availability: '.$ticket['availability'];
    }

    //Timer stuff
    $total_minutes = 0;
    $ticket_timer = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `ticket_timer` WHERE `ticketid` = '".$ticket['ticketid']."' AND `created_by` = '".$contactid."' AND `timer_type` != 'Break'"),MYSQLI_ASSOC);
    foreach ($ticket_timer as $timer) {
        $hours = intval(explode(':', $timer['timer'])[0]);
        $minutes = intval(explode(':', $timer['timer'])[1]);
        $total_minutes += ($hours * 60) + $minutes;
    }
    $total_time = sprintf('%02d:%02d', (floor($total_minutes / 60)), ($total_minutes % 60));

    //Status stuff
    $user_status = $ticket['status'];
    if (($ticket['status'] == 'Internal QA') && ($daily_date == $ticket['internal_qa_date']) && (strpos($ticket['internal_qa_contactid'], ','.$contactid.',') === FALSE)) {
        $user_status = 'To Do';
    }
    if (($ticket['status'] == 'Customer QA' || $ticket['status'] == 'Waiting On Customer') && ($daily_date == $ticket['deliverable_date']) && (strpos($ticket['deliverable_contactid'], ','.$contactid.',') === FALSE)) {
        $user_status = 'To Do';
    }
    // if (($ticket['status'] != 'Customer QA' && $ticket['status'] != 'Internal QA') && ($daily_date >= $ticket['to_do_date'] && $daily_date <= $ticket['to_do_end_date']) && (strpos($ticket['contactid'], ','.$contactid.',') !== FALSE)) {
        // $user_status = $ticket['status'];
    // }
	$eta = $ticket['eta'];
    $hours = intval(explode(':', $ticket['max_time'])[0]);
    $minutes = intval(explode(':', $ticket['max_time'])[1]);
    $ticket_minutes = ($hours * 60) + $minutes;

    if ($total_minutes <= $ticket_minutes && $user_status == $ticket['status']) {
        $total_time = '<h5 style="font-weight: normal; display: inline;">'.$total_time.'</h5>';
    } else if ($total_minutes > $ticket_minutes && $user_status == $ticket['status']) {
        $total_time = '<span style="color: red;">'.$total_time.'</span>';
    }

    $opacity_styling = '';
    if ($user_status != $ticket['status']) {
        echo '<i>';
        $opacity_styling = 'style="opacity: 0.5;"';
    }

    if(in_array('Time Estimate', $daysheet_ticket_fields)) {
        $label .= '<br />'.substr($ticket['max_time'], 0, 5).'/'.$total_time;
    }

    $label = '<span>'.$label.'</span>';

    if ($user_status != $ticket['status']) {
        $label .= '&nbsp;<h5 style="font-weight: normal; font-style: italic; display: inline;">currently in '.$ticket['status'].'</h5></i>';
    } else {
        $label .= ' ('.$ticket['status'].')<br />';
    }

    $ticket_documents = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) num_rows FROM `ticket_document` WHERE `ticketid` = '".$ticket['ticketid']."' AND `deleted` = 0"))['num_rows'];
    if(in_array('Attachment Indicator', $daysheet_ticket_fields) && $ticket_documents > 0) {
    	$label .= '<img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" class="inline-img" title="'.$ticket_documents.' Attachments">';
    }

    $ticket_comments = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) num_rows FROM `ticket_comment` WHERE `ticketid` = '".$ticket['ticketid']."' AND `deleted` = 0"))['num_rows'];
    if(in_array('Comment Indicator', $daysheet_ticket_fields) && $ticket_comments > 0) {
    	$label .= '<img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" class="inline-img" title="'.$ticket_comments.' Comments">';
    }
	
    if(in_array('Details with Confirm', $daysheet_ticket_fields)) {
    	$label .= '<label class="form-checkbox"><input type="checkbox" name="status" value="'.$status_complete.'">Mark '.$status_complete.'</label>';
    }

    return $label;
}