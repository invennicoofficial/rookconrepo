<?php
/*
 * Sales Lead Main Page
 */
error_reporting(0);
include ('../include.php');

// Form submission from details.php
if (isset($_POST['add_sales'])) {
	$created_date = date('Y-m-d');
    $created_by = $_SESSION['contactid'];
    $lead_created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
    $primary_staff = $_POST['primary_staff'];
    $share_lead = (!empty($_POST['share_lead'])) ? implode(',',$_POST['share_lead']) : '';
    $name = '';
    $b = '';
    $m = 0;
    $businessid = '';
    $contactid = implode(',', $_POST['contactid']);
    $new_contactid = '';
    $first_name = '';
    $last_name = '';
    $new_contact_title = '';
    $new_contact_phone = '';
    $new_contact_email = '';
	$new_contact_region = filter_var($_POST['new_contact_region'],FILTER_SANITIZE_STRING);
	$new_contact_location = filter_var($_POST['new_contact_location'],FILTER_SANITIZE_STRING);
	$new_contact_classification = filter_var($_POST['new_contact_classification'],FILTER_SANITIZE_STRING);
	
    if ( $_POST['new_business'] != '' ) {
		$name = encryptIt($_POST['new_business']);
        $b = 1;
	} else {
        $businessid = $_POST['businessid'];
	}

	if ( $_POST['new_contact_fname'] != '' ) {
		$first_name = encryptIt(filter_var($_POST['new_contact_fname'], FILTER_SANITIZE_STRING));
        $m = 1;
	}
    if ( $_POST['new_contact_lname'] != '' ) {
		$last_name = encryptIt(filter_var($_POST['new_contact_lname'], FILTER_SANITIZE_STRING));
        $m = 1;
	}
	
    $primary_number = encryptIt(filter_var($_POST['primary_number'], FILTER_SANITIZE_STRING));
	$email_address = encryptIt(filter_var($_POST['email_address'],FILTER_SANITIZE_EMAIL));
    
    // New Business
    if ( $b==1 ) {
        if ( $_POST['new_number'] != '' ) {
            $primary_number = filter_var($_POST['new_number'], FILTER_SANITIZE_STRING);
        }
        if ( $_POST['new_email'] != '' ) {
            $email_address = encryptIt(filter_var($_POST['new_email'], FILTER_SANITIZE_EMAIL));
        }
        $query_insert = "INSERT INTO `contacts` (`category`, `name`, `office_phone`, `email_address`) VALUES ('Business', '$name', '$primary_number', '$email_address')";
        $result_insert = mysqli_query($dbc, $query_insert);
        $businessid = mysqli_insert_id($dbc);
    }

    // New Sales Lead
    if ( $m==1 ) {
        if ( $_POST['new_contact_title'] != '' ) {
            $new_contact_title = filter_var($_POST['new_contact_title'], FILTER_SANITIZE_STRING);
        }
        if ( $_POST['new_contact_phone'] != '' ) {
            $new_contact_phone = encryptIt(filter_var($_POST['new_contact_phone'], FILTER_SANITIZE_STRING));
        }
        if ( $_POST['new_contact_email'] != '' ) {
            $new_contact_email = encryptIt(filter_var($_POST['new_contact_email'], FILTER_SANITIZE_STRING));
        }
        if ( $_POST['new_contact_region'] != '' ) {
            $new_contact_region = filter_var($_POST['new_contact_region'], FILTER_SANITIZE_STRING);
        }
        if ( $_POST['new_contact_location'] != '' ) {
            $new_contact_location = filter_var($_POST['new_contact_location'], FILTER_SANITIZE_STRING);
        }
        if ( $_POST['new_contact_classification'] != '' ) {
            $new_contact_classification = filter_var($_POST['new_contact_classification'], FILTER_SANITIZE_STRING);
        }
        
        $query_insert = "INSERT INTO `contacts` (`category`, `businessid`, `first_name`, `last_name`, `office_phone`, `email_address`, `title`, `region`, `con_locations`, `classification`) VALUES ('Sales Leads', '$businessid', '$first_name', '$last_name', '$new_contact_phone', '$new_contact_email', '$new_contact_title', '$new_contact_region', '$new_contact_location', '$new_contact_classification')";
        $result_insert = mysqli_query($dbc, $query_insert);
        $new_contactid = mysqli_insert_id($dbc);

        /* $query_update = "UPDATE `contacts` SET `businessid`='$businessid' WHERE `contactid`='$businessid'";
        $result_update_inventory = mysqli_query($dbc, $query_update);
        $contactid = $businessid; */
    }
    
    /* $query_update = "UPDATE `contacts` SET `region`='$region', `location`='$location', `classification`='$classification' WHERE `contactid`='$contact'";
    $result_update_inventory = mysqli_query($dbc, $query_update); */
        
    $marketingmaterialid  = implode(',', $_POST['marketingmaterialid']);
    /* $marketing_materials = $_POST['marketingmaterialid'];
    foreach ($marketing_materials as $mm) {
        $to_emails = $_POST['getemailsapprove123_'.$mm];
    }
	$email_marketing = $_POST['email_marketing'];
    if ( !empty($email_marketing) ) {
        $marketingmatids = $_POST['marketingmaterialid'];
        $result_marketing = mysqli_query($dbc, "SELECT `document_link` FROM `marketing_material_uploads` WHERE FIND_IN_SET(`marketing_materialid`, '3,5,7') AND `type`='Document'");
        while ( $row=mysqli_fetch_assoc($result_marketing) ) {
            $urls[] = '<a href="'. WEBSITE_URL .'/Marketing Material/download/'. $row['document_link'] .'">'. $row['document_link'] .'</a>';
        }
        $url_list = implode('<br />', $urls);
        $from     = 'info@rookconnect.com';
        $email_marketing = $_POST['email_marketing'];
        $subject  = "Marketing Material(s)";
        $message  = "<h2>Marketing Material(s)</h2>Click on below link(s) to download Marketing Material(s).<br /><br />" . $urls;
        send_email($from, $email_marketing, '', '', $subject, $message, '');
    } */

    $lead_value           = filter_var($_POST['lead_value'], FILTER_SANITIZE_STRING);
    $estimated_close_date = filter_var($_POST['estimated_close_date'], FILTER_SANITIZE_STRING);
    $serviceid            = implode(',', $_POST['serviceid']);
    $productid            = implode(',', $_POST['productid']);
    
    // $lead_source_select     = filter_var($_POST['lead_source'], FILTER_SANITIZE_STRING);
    // $lead_source_businessid = filter_var($_POST['lead_source_businessid'], FILTER_SANITIZE_STRING);
    // $lead_source_contactid  = filter_var($_POST['lead_source_contactid'], FILTER_SANITIZE_STRING);
    // $lead_source_other      = filter_var($_POST['lead_source_other'], FILTER_SANITIZE_STRING);
    // $lead_is_contact  = false;
    // $lead_is_business = false;
    
    // if ( !empty($lead_source_other) ) {
    //     $lead_source = $lead_source_other;
    // } elseif ( !empty($lead_source_contactid) ) {
    //     $lead_source = get_contact($dbc, $lead_source_contactid);
    //     $lead_is_contact  = true;
    // } elseif ( empty($lead_source_contactid) && !empty($lead_source_businessid) ) {
    //     $row = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT `name` FROM `contacts` WHERE `contactid`='$contactid'"));
    //     $lead_source = decryptIt($row['name']);
    //     $lead_is_business = true;
    // } else {
    //     $lead_source = $lead_source_select;
    // }

    $lead_source = [];
    if(!empty($_POST['lead_source'])) {
        $lead_source = array_merge($lead_source, $_POST['lead_source']);
    }
    if(!empty($_POST['lead_source_businessid'])) {
        $lead_source = array_merge($lead_source, $_POST['lead_source_businessid']);
    }
    if(!empty($_POST['lead_source_contactid'])) {
        $lead_source = array_merge($lead_source, $_POST['lead_source_contactid']);
    }
    if(!empty($_POST['lead_source_other'])) {
        $lead_source = array_merge($lead_source, $_POST['lead_source_other']);
    }
    $lead_source = implode('#*#', array_filter(array_unique($lead_source)));
    
    $next_action    = filter_var($_POST['next_action'], FILTER_SANITIZE_STRING);
    $new_reminder   = filter_var($_POST['new_reminder'], FILTER_SANITIZE_STRING);
    $status         = $_POST['status'];
    if(empty($status)) {
        $status = explode(',',get_config ( $dbc, 'sales_lead_status' ))[0];
    }
    $status_won     = get_config($dbc, 'lead_status_won');
    
    $contactid = $contactid .','. $new_contactid;
	$old_details = [];
    if ( empty($_POST['salesid']) ) {
        $query_insert = "INSERT INTO `sales` (`created_date`, `lead_created_by`, `primary_staff`, `share_lead`, `businessid`, `contactid`, `primary_number`, `email_address`, `lead_value`, `estimated_close_date`, `serviceid`, `productid`, `lead_source`, `marketingmaterialid`, `next_action`, `new_reminder`, `status`, `region`, `location`, `classification`) VALUES ('$created_date', '$lead_created_by', '$primary_staff', '$share_lead', '$businessid', '$contactid', '". decryptIt($primary_number) ."', '". decryptIt($email_address) ."', '$lead_value', '$estimated_close_date', '$serviceid', '$productid', '$lead_source', '$marketingmaterialid', '$next_action', '$new_reminder', '$status', '$region', '$location', '$classification')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert);
        $salesid = mysqli_insert_id($dbc);
		mysqli_query($dbc, "INSERT INTO `sales_history` (`created_by`,`salesid`,`history`) VALUES (".$_SESSION['contactid'].",$salesid,'Sales Lead Added')");
        $url = 'Added';
		$old_action = '';
    } else {
        $salesid = $_POST['salesid'];
		$old_details = $dbc->query("SELECT * FROM `sales` WHERE `salesid`='$salesid'")->fetch_assoc();
        $query_update = "UPDATE `sales` SET `primary_staff`='$primary_staff', `share_lead`='$share_lead', `businessid`='$businessid', `contactid`='$contactid', `primary_number`='". decryptIt($primary_number) ."', `email_address`='". decryptIt($email_address) ."', `lead_value`='$lead_value', `estimated_close_date`='$estimated_close_date', `serviceid`='$serviceid', `productid`='$productid', `lead_source`='$lead_source', `marketingmaterialid`='$marketingmaterialid', `next_action`='$next_action', `new_reminder`='$new_reminder', `status`='$status', `region`='$region', `location`='$location', `classification`='$classification' WHERE `salesid`='$salesid'";
        $result_update_vendor = mysqli_query($dbc, $query_update);
        $url = 'Updated';
		$old_action = mysqli_fetch_array(mysqli_query($dbc, "SELECT `next_action` FROM `sales` WHERE `salesid`='$salesid'"))['next_action'];
    }
	$new_details = $dbc->query("SELECT * FROM `sales` WHERE `salesid`='$salesid'")->fetch_assoc();
	$changes = [];
	foreach($new_details as $field => $value) {
		if($value != $old_details[$field]) {
			$changes[] = filter_var(htmlentities("'$field' updated to $value"),FILTER_SANITIZE_STRING);
		}
	}
	if(!empty($changes)) {
		mysqli_query($dbc, "INSERT INTO `sales_history` (`created_by`,`salesid`,`history`) VALUES (".$_SESSION['contactid'].",$salesid,'Updated: ".implode(', ',$changes)."')");
	}
    
    //Convert Sales Lead to a Customer
    if ( $status_won==$status ) {
        $lead_convert_to = get_config($dbc, 'lead_convert_to');
        if ( empty($lead_convert_to) ) {
            $lead_convert_to = 'Customers';
        }
        foreach ( array_filter(explode(',',$contactid)) as $cid ) {
            mysqli_query($dbc, "UPDATE contacts SET category='$lead_convert_to' WHERE contactid='$cid'");
        }
    }
    
    if ( $lead_is_contact ) {
        mysqli_query($dbc, "UPDATE `contacts` SET `referred_contactid`=IF(`referred_contactid` IS NULL, '$businessid', CONCAT(`referred_contactid`, ',$businessid')) WHERE `contactid`='$lead_source_contactid'");
    }
    if ( $lead_is_business ) {
        mysqli_query($dbc, "UPDATE `contacts` SET `referred_contactid`=IF(`referred_contactid` IS NULL, '$businessid', CONCAT(`referred_contactid`, ',$businessid')) WHERE `contactid`='$lead_source_businessid'");
    }
	
	//Schedule Reminders
	if ($new_reminder != '' && $new_reminder != '0000-00-00' && $old_action != $next_action ) {
		$body = filter_var(htmlentities('This is a reminder about a '.SALES_NOUN.' that needs to be followed up with.<br />
			The scheduled next action is: '.$next_action.'<br />
			Click <a href="'.WEBSITE_URL.'/Sales/add_sales.php?salesid='.$salesid.'">here</a> to review the lead.'), FILTER_SANITIZE_STRING);
		$verify = "sales#*#next_action#*#salesid#*#".$salesid."#*#".$next_action;
        mysqli_query($dbc, "UPDATE `reminders` SET `done` = 1 WHERE `contactid` = '$primary_staff' AND `src_table` = 'sales' AND `src_tableid` = '$salesid'");
		$reminder_result = mysqli_query($dbc, "INSERT INTO `reminders` (`contactid`, `reminder_date`, `reminder_type`, `subject`, `body`, `src_table`, `src_tableid`)
			VALUES ('$primary_staff', '$new_reminder', 'Sales Reminder', 'Reminder of ".SALES_NOUN."', '$body', 'sales', '$salesid')");
	}

    //Notes
    //$note_heading   = filter_var($_POST['note_heading'], FILTER_SANITIZE_STRING);
    $ticket_comment = htmlentities($_POST['comment']);
    $t_comment      = filter_var($ticket_comment, FILTER_SANITIZE_STRING);

    if($t_comment != '') {
        $email_comment = $_POST['email_comment'];
        $query_insert_ca = "INSERT INTO `sales_notes` (`salesid`, `comment`, `email_comment`, `created_date`, `created_by`) VALUES ('$salesid', '$t_comment', '$email_comment', '$created_date', '$created_by')";
        $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
		mysqli_query($dbc, "INSERT INTO `sales_history` (`created_by`,`salesid`,`history`) VALUES (".$_SESSION['contactid'].",$salesid,'Note added: $t_comment')");

        if ($_POST['send_email_on_comment'] == 'Yes') {
            $email = get_email($dbc, $email_comment);
            $subject = 'Note Added on '.SALES_TILE;

            $email_body = 'Note : '.$_POST['comment'].'<br><br>';

            send_email('', $email, '', '', $subject, $email_body, '');
        }
    }

    //Documents
    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    
    foreach($_FILES['upload_document']['name'] as $i => $document) {
        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "download/".$_FILES["upload_document"]["name"][$i]) ;

        if($document != '') {
			$label = filter_var($_POST['document_label'][$i], FILTER_SANITIZE_STRING);
            $query_insert_client_doc = "INSERT INTO `sales_document` (`salesid`, `label`, `document`, `created_date`, `created_by`) VALUES ('$salesid', '$label', '$document', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
			mysqli_query($dbc, "INSERT INTO `sales_history` (`created_by`,`salesid`,`history`) VALUES (".$_SESSION['contactid'].",$salesid,'Document added: $label')");
        }
    }

    foreach($_POST['support_link'] as $i => $support_link) {
        if($support_link != '') {
			$label = filter_var($_POST['link_label'][$i], FILTER_SANITIZE_STRING);
            $query_insert_client_doc = "INSERT INTO `sales_document` (`salesid`, `label`, `link`, `created_date`, `created_by`) VALUES ('$salesid', '$label', '$support_link', '$created_date', '$created_by')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
			mysqli_query($dbc, "INSERT INTO `sales_history` (`created_by`,`salesid`,`history`) VALUES (".$_SESSION['contactid'].",$salesid,'Link added: $label')");
        }
    }
    
    foreach($_FILES['upload_infodoc']['name'] as $i => $document) {
        move_uploaded_file($_FILES["upload_infodoc"]["tmp_name"][$i], "download/".$_FILES["upload_infodoc"]["name"][$i]) ;

        if($document != '') {
			$label = filter_var($_POST['infodoc_label'][$i], FILTER_SANITIZE_STRING);
            $query_insert_infodoc = "INSERT INTO `sales_document` (`salesid`, `document_type`, `label`, `document`, `created_date`, `created_by`) VALUES ('$salesid', 'Information Gathering', '$label', '$document', '$created_date', '$created_by')";
            $result_insert_infodoc = mysqli_query($dbc, $query_insert_infodoc);
			mysqli_query($dbc, "INSERT INTO `sales_history` (`created_by`,`salesid`,`history`) VALUES (".$_SESSION['contactid'].",$salesid,'Information Gathering Added: $label')");
        }
    }
    
    foreach($_FILES['upload_estimate']['name'] as $i => $document) {
        move_uploaded_file($_FILES["upload_estimate"]["tmp_name"][$i], "download/".$_FILES["upload_estimate"]["name"][$i]) ;

        if($document != '') {
			$label = filter_var($_POST['estimate_label'][$i], FILTER_SANITIZE_STRING);
            $query_insert_estimate = "INSERT INTO `sales_document` (`salesid`, `document_type`, `label`, `document`, `created_date`, `created_by`) VALUES ('$salesid', 'Estimate', '$label', '$document', '$created_date', '$created_by')";
            $result_insert_estimate = mysqli_query($dbc, $query_insert_estimate);
			mysqli_query($dbc, "INSERT INTO `sales_history` (`created_by`,`salesid`,`history`) VALUES (".$_SESSION['contactid'].",$salesid,'Estimate added: $label')");
        }
    }

    echo '<script type="text/javascript"> window.location.replace("index.php");</script>';
} ?>

<script type="text/javascript">
$(document).ready(function() {
    $(window).resize(function() {
		var available_height = window.innerHeight - $('footer:visible').outerHeight() - $('#sales_div .tile-container').offset().top - 1;
		if(available_height > 200) {
            $('#sales_div .tile-sidebar, #sales_div .tile-content').height(available_height);
            $('#sales_div .main-screen-white').height(available_height - 6);
		}
	}).resize();
    
    $('.main-screen').height($('#sales_div').height());
    $('.tile-sidebar, .tile-content').height($('#sales_div').height() - $('.tile-header').height() + 15);
    //$('.main-screen-white').height($('.tile-content').height() - 96);
    $('.main-screen-white').css('overflow-x','hidden');
    var $sections = $('.accordion-block-details');
    $('.main-screen-white').on('scroll', function(){
        var currentScroll = $('.main-screen .tile-container').offset().top + $('.main-screen-white').find('.preview-block-header').height();
        var $currentSection;
        $sections.each(function(){
            var divPosition = $(this).offset().top;
            if( divPosition - 1 < currentScroll ){
                $currentSection = $(this);
				var id = $currentSection.attr('id');
				$('.tile-sidebar li').removeClass('active');
				$('.tile-sidebar [href=#'+id+'] li').addClass('active');
            }
        });
    });
    
    $('#nav_salespath').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='salespath' ) {
            echo 'window.location.replace("?p=salespath&id='.$_GET['id'].'");';
        } ?>
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
        $(this).addClass('active');
    });
    
    $('#nav_staffinfo').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=staffinfo");';
        } ?>
        $('#nav_salespath, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
        $(this).addClass('active');
    });
    
    $('#nav_leadinfo').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=leadinfo");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_services').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=services");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_products').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=products");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_leadsource').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=leadsource");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_refdocs').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=refdocs");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_marketing').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=marketing");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_infogathering').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=infogathering");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_estimate').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=estimate");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_quote').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=quote");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_nextaction').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=nextaction");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_leadstatus, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_leadstatus').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=leadstatus");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadnotes, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_leadnotes').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=leadnotes");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_tasks, #nav_history').removeClass('active');
    });
    
    $('#nav_tasks').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=tasks");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_history').removeClass('active');
    });
    
    $('#nav_history').click(function() {<?php
        if ( isset($_GET['p']) && $_GET['p']!='details' ) {
            echo 'window.location.replace("?p=details&id='.$_GET['id'].'&a=history");';
        } ?>
        $(this).addClass('active');
        $('#nav_salespath, #nav_staffinfo, #nav_leadinfo, #nav_services, #nav_products, #nav_leadsource, #nav_refdocs, #nav_marketing, #nav_infogathering, #nav_estimate, #nav_quote, #nav_nextaction, #nav_leadstatus, #nav_leadnotes, #nav_tasks').removeClass('active');
    });
    
    <?php
        if ( $_GET['p'] == 'details' && (!isset($_GET['a']) || $_GET['a']=='staffinfo') ) { echo "$('#nav_staffinfo').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='leadinfo' ) { echo "$('#nav_leadinfo').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='services' ) { echo "$('#nav_services').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='products' ) { echo "$('#nav_products').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='leadsource' ) { echo "$('#nav_leadsource').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='refdocs' ) { echo "$('#nav_refdocs').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='marketing' ) { echo "$('#nav_marketing').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='infogathering' ) { echo "$('#nav_infogathering').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='estimate' ) { echo "$('#nav_estimate').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='quote' ) { echo "$('#nav_quote').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='nextaction' ) { echo "$('#nav_nextaction').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='leadstatus' ) { echo "$('#nav_leadstatus').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='leadnotes' ) { echo "$('#nav_leadnotes').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='tasks' ) { echo "$('#nav_tasks').trigger('click');"; }
        if ( isset($_GET['a']) && $_GET['a']=='history' ) { echo "$('#nav_history').trigger('click');"; }
    ?>
    
    $('form').submit(function(e){
        if ($('[name=status]').length==0) {
            e.preventDefault();
            alert('Please select the Lead Status.');
        }
    });
});
</script>
</head>

<body>
<?php
    if($_GET['iframe_slider'] != 1) {
    	include_once ('../navigation.php');
    }
    checkAuthorised('sales');
    $statuses     = get_config($dbc, 'sales_lead_status');
    $next_actions = get_config($dbc, 'sales_next_action');
    $field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `sales` FROM `field_config`"));
    $value_config = ','.$field_config['sales'].',';
?>

<div id="sales_div" class="container">
    <div class="iframe_overlay" style="display:none;">
		<div class="iframe">
			<div class="iframe_loading">Loading...</div>
			<iframe src=""></iframe>
		</div>
	</div>
    
    <div class="row">
		<div class="main-screen"><?php
            if($_GET['iframe_slider'] != 1) {
                include('tile_header.php');
            }
            
            $page      = preg_replace('/\PL/u', '', $_GET['p']);
            $salesid   = preg_replace('/[^0-9]/', '', $_GET['id']); ?>
            
            <!--<div class="tile-bar">
                <ul>
                    <li class="<?= ( $page=='details' ) ? 'active' : ''; ?>"><a href="?p=details&id=<?=$salesid;?>">Details</a></li>
                    <li class="<?= ( $page=='template' ) ? 'active' : ''; ?>"><a href="?p=template">Template</a></li>
                    <li class="<?= ( $page=='design' ) ? 'active' : ''; ?>"><a href="?p=design">Design</a></li>
                    <li class="<?= ( $page=='preview' ) ? 'active' : ''; ?>"><a href="?p=preview&id=<?=$salesid;?>">Preview</a></li>
                </ul>
            </div> .tile-bar -->
            
            <div class="tile-container">
                <!-- Sidebar -->
                <div class="standard-collapsible tile-sidebar tile-sidebar-noleftpad hide-on-mobile" <?= $_GET['iframe_slider'] == 1 ? 'style="display:none;"' : '' ?>>
                    <ul><?php
                        if (strpos($value_config, ',Sales Path,') !== false) { ?>
                            <a href="#salespath"><li class="collapsed cursor-hand <?= $_GET['p'] == 'salespath' ? 'active' : '' ?>" data-toggle="collapse" data-target="#collapse_salespath" id="nav_salespath"><?= SALES_NOUN ?> Path</li></a><?php
                        }
                        if (strpos($value_config, ',Staff Information,') !== false) { ?>
                            <a href="#staffinfo"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_staff_information" id="nav_staffinfo">Staff Information</li></a><?php
                        }
                        if (strpos($value_config, ',Lead Information,') !== false) { ?>
                            <a href="#leadinfo"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_lead_information" id="nav_leadinfo">Lead Information</li></a><?php
                        }
                        if (strpos($value_config, ',Service,') !== false) { ?>
                            <a href="#services"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_services" id="nav_services">Services</li></a><?php
                        }
                        if (strpos($value_config, ',Products,') !== false) { ?>
                            <a href="#products"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_products" id="nav_products">Products</li></a><?php
                        }
                        if (strpos($value_config, ',Lead Source,') !== false) { ?>
                            <a href="#leadsource"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_lead_source" id="nav_leadsource">Lead Source</li></a><?php
                        }
                        if (strpos($value_config, ',Reference Documents,') !== false) { ?>
                            <a href="#refdocs"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_reference_documents" id="nav_refdocs">Reference Documents</li></a><?php
                        }
                        if (strpos($value_config, ',Marketing Material,') !== false) { ?>
                            <a href="#marketing"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_reference_documents" id="nav_marketing">Marketing Material</li></a><?php
                        }
                        if (strpos($value_config, 'Information Gathering,') !== false) { ?>
                            <a href="#infogathering"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_information_gathering" id="nav_infogathering">Information Gathering</li></a><?php
                        }
                        if (strpos($value_config, ',Estimate,') !== false) { ?>
                            <a href="#estimate"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_estimate" id="nav_estimate">Estimate</li></a><?php
                        }
                        if (strpos($value_config, ',Next Action,') !== false) { ?>
                            <a href="#nextaction"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_next_action" id="nav_nextaction">Next Action</li></a><?php
                        }
                        if (strpos($value_config, ',Lead Status,') !== false) { ?>
                            <a href="#leadstatus"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_lead_status" id="nav_leadstatus">Lead Status</li></a><?php
                        }
                        if (strpos($value_config, ',Lead Notes,') !== false) { ?>
                            <a href="#leadnotes"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_lead_notes" id="nav_leadnotes">Lead Notes</li></a><?php
                        }
                        if (strpos($value_config, ',Tasks,') !== false) { ?>
                            <a href="#tasks"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_tasks" id="nav_tasks">Tasks</li></a><?php
                        }
                        if (strpos($value_config, ',History,') !== false) { ?>
                            <a href="#history"><li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_history" id="nav_history">History</li></a><?php
                        } ?>
                    </ul>
                </div><!-- .tile-sidebar -->
                
                <!-- Main Screen -->
                <div class="scale-to-fill has-main-screen tile-content set-section-height"><?php
                    if ( $page=='preview' || empty($page) ) {
                        include('preview.php');
                    } elseif ( $page=='salespath' ) {
                        include('salespath.php');
                    } elseif ( $page=='details' ) {
                        include('details.php');
                    } else {
                        include('details.php');
                    } ?>
                </div><!-- .tile-content -->
                
                <div class="clearfix"></div>
            </div><!-- .tile-container -->

        </div><!-- .main-screen -->
    </div><!-- .row -->
</div><!-- .container -->

<?php if($_GET['iframe_slider'] != 1) {
    include ('../footer.php');
} ?>