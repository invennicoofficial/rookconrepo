<?php
/*
Inventory Listing
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);
if (isset($_POST['export_pdf'])) {
    $checklistid = $_POST['checklistid'];
	include('checklist_pdf.php');
}
?>
<script type="text/javascript" src="checklist.js"></script>
<style type='text/css'>
.display-field {
  display: inline-block;
  /* padding-left: 50px; */
  text-indent: 2px;
  vertical-align: top;
  width: calc(100% - 2.5em);
}
.popped-field {
	width: calc(100% + 1em);
}
.popped-field .display-field {
	color: black;
	font-size: 1.2em;
}
</style>
<script>
setTimeout(function() {

var maxWidth = Math.max.apply( null, $( '.ui-sortable' ).map( function () {
    return $( this ).outerWidth( true );
}).get() );

var maxHeight = -1;

$('.ui-sortable').each(function() {
  maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();

});

$(function() {
  $(".connectedChecklist").width(maxWidth).height(maxHeight);
});
$( '.connectedChecklist' ).each(function () {
    this.style.setProperty( 'height', maxHeight, 'important' );
	this.style.setProperty( 'width', maxWidth, 'important' );

	<?php if($check_table_orient == 1) { ?>
		$(this).attr('style', 'height:'+maxHeight+'px !important; width:'+maxWidth+'px !important');
	<?php } else { ?>
		$(this).attr('style', 'height:'+maxHeight+'px !important;');
	<?PHP } ?>
});

}, 200);

$(document).ready(function() {
	$('.close_iframer').click(function(){
		$('.iframe_holder').hide();
		$('.hide_on_iframe').show();
	});
});
function choose_user(target, type, id, date) {
	var title	= 'Choose a User';
	$('iframe').load(function() {
		this.contentWindow.document.body.style.overflow = 'hidden';
		this.contentWindow.document.body.style.minHeight = '0';
		this.contentWindow.document.body.style.paddingBottom = '5em';
		var height = $(this).contents().find('option').length * $(this).contents().find('select').height();
		$(this).contents().find('select').data({type: type, id: id});
		this.style.height = (height + this.contentWindow.document.body.offsetHeight + 180) + 'px';
		$(this).contents().find('.btn').off();
		$(this).contents().find('.btn').click(function() {
			if($(this).closest('body').find('select').val() != '' && confirm('Are you sure you want to send the '+target+' to the selected user(s)?')) {
				if(target == 'alert') {
					$.ajax({
						method: 'POST',
						url: 'checklist_ajax.php?fill=checklistalert',
						data: { id: id, type: type, user: $(this).closest('body').find('select').val() },
						complete: function(result) { console.log(result.responseText); }
					});
				}
				else if(target == 'email') {
					$.ajax({
						method: 'POST',
						url: 'checklist_ajax.php?fill=checklistemail',
						data: { id: id, type: type, user: $(this).closest('body').find('select').val() },
						complete: function(result) { console.log(result.responseText); }
					});
				}
				else if(target == 'reminder') {
					$.ajax({
						method: 'POST',
						url: 'checklist_ajax.php?fill=checklistreminder',
						data: { id: id, type: type, schedule: date, user: $(this).closest('body').find('select').val() },
						complete: function(result) { console.log(result.responseText); }
					});
				}
				$(this).closest('body').find('select').val('');
				$('.close_iframer').click();
			}
			else if($(this).closest('body').find('select').val() == '') {
				$('.close_iframer').click();
			}
		});
	});
	$('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Staff/select_staff.php?target='+target+'&multiple=true');
	$('.iframe_title').text(title);
	$('.iframe_holder').show();
	$('.hide_on_iframe').hide();
}
function send_alert(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	choose_user('alert', type, checklist_id);
}
function send_email(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	choose_user('email', type, checklist_id);
}
function send_reminder(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	var name_id = (type == 'checklist board' ? 'board_' : '');
	$('[name=reminder_'+name_id+checklist_id+']').show().focus();
	$('[name=reminder_'+name_id+checklist_id+']').keyup(function(e) {
		if(e.which == 13) {
			$(this).blur();
		}
	});
	$('[name=reminder_'+name_id+checklist_id+']').change(function() {
		$(this).hide();
		var date = $(this).val().trim();
		$(this).val('');
		if(date != '') {
			choose_user('reminder', type, checklist_id, date);
		}
	});
}
function send_reply(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	$('[name=reply_'+checklist_id+']').show().focus();
	$('[name=reply_'+checklist_id+']').keyup(function(e) {
		if(e.which == 13) {
			$(this).blur();
		}
	});
	$('[name=reply_'+checklist_id+']').blur(function() {
		$(this).hide();
		var reply = $(this).val().trim();
		$(this).val('');
		if(reply != '') {
			var today = new Date();
			var save_reply = reply + " (Reply added by <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?> at "+today.toLocaleString()+")";
			$.ajax({
				method: 'POST',
				url: 'checklist_ajax.php?fill=checklistreply',
				data: { id: checklist_id, reply: save_reply },
				complete: function(result) { window.location.reload(); }
			})
		}
	});
}
function add_time(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	$('[name=checklist_time_'+checklist_id+']').show();
	$('[name=checklist_time_'+checklist_id+']').timepicker('option', 'onClose', function(time) {
		var time = $(this).val();
		$('[name=checklist_time_'+checklist_id+']').hide();
		$(this).val('00:00');
		if(time != '' && time != '00:00') {
			$.ajax({
				method: 'POST',
				url: 'checklist_ajax.php?fill=checklist_quick_time',
				data: { id: checklist_id, time: time+':00' },
				complete: function(result) { console.log(result.responseText); }
			})
		}
	});
	$('[name=checklist_time_'+checklist_id+']').timepicker('show');
}
function attach_file(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist_board';
		checklist_id = checklist_id.substring(5);
	}
	var file_id = 'attach_'+(type == 'checklist' ? '' : 'board_')+checklist_id;
	$('[name='+file_id+']').change(function() {
		var fileData = new FormData();
		fileData.append('file',$('[name='+file_id+']')[0].files[0]);
		$.ajax({
			contentType: false,
			processData: false,
			type: "POST",
			url: "checklist_ajax.php?fill=checklist_upload&type="+type+"&id="+checklist_id,
			data: fileData,
			complete: function(result) {
				console.log(result.responseText);
				window.location.reload();
			}
		});
	});
	$('[name='+file_id+']').click();
}
function flag_item(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist_board';
		checklist_id = checklist_id.substring(5);
	}
	$.ajax({
		method: "POST",
		url: "checklist_ajax.php?fill=checklistflag",
		data: { type: type, id: checklist_id },
		complete: function(result) {
			console.log(result.responseText);
			if(type == 'checklist') {
				$(checklist).closest('li').css('background-color',(result.responseText == '' ? '' : '#'+result.responseText));
			} else {
				$(checklist).closest('form').css('background-color',(result.responseText == '' ? '' : '#'+result.responseText));
			}
		}
	});
}
function archive(checklist) {
	checklist_id = $(checklist).parents('span').data('checklist');
	var type = 'checklist';
	if(checklist_id.toString().substring(0,5) == 'BOARD') {
		var type = 'checklist board';
		checklist_id = checklist_id.substring(5);
	}
	if(type == 'checklist' && confirm("Are you sure you want to archive this item?")) {
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "checklist_ajax.php?fill=delete_checklist&checklistid="+checklist_id,
			dataType: "html",   //expect html to be returned
			success: function(response){
				window.location.reload();
				console.log(response.responseText);
			}
		});
	}
	else if(confirm("Are you sure you want to archive this checklist?")) {
		window.location = "<?php echo WEBSITE_URL; ?>/delete_restore.php?action=delete&remove_checklist=all&checklistid=" + checklist_id;
	}
}
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('checklist');
$tab_config = get_config($dbc, 'checklist_tabs');
?>
<div class="container">
	<div class="iframe_holder" style="display:none;">
		<img src="<?php echo WEBSITE_URL; ?>/img/icons/close.png" class="close_iframer" width="45px" style="position:relative; right:10px; float:right; top:58px; cursor:pointer;">
		<span class="iframe_title" style="color:white; font-weight:bold; position:relative; top:58px; left:20px; font-size:30px;"></span>
		<iframe id="iframe_instead_of_window" style="width:100%; overflow:hidden; height:200px; border:0;" src=""></iframe>
	</div>
	<div class="row hide_on_iframe">

    <h1 class="single-pad-bottom pull-left">My Checklists</h1>
    <div class="clearfix"></div>
    <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        You must add a checklist in order to see your checklists in each sub tab. To do so, click Add Checklist and fill in all of the fields. Once you have hit Submit, you will see the checklist in the specific sub tab you have assigned it to.</div>
        <div class="clearfix"></div>
    </div>
    <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        This sub tab is designed for your own personal use. You will find here the checklists only viewable to you.</div>
        <div class="clearfix"></div>
    </div>

    <?php
	if(config_visible_function($dbc, 'checklist') == 1) {
		echo '<div class="pull-right">';
			echo '<span class="popover-examples list-inline" style="margin:0 7px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			echo '<a href="field_config.php?from_url=my_checklist.php" class="mobile-block"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
		echo '</div>';
	}
    //echo '<br><a href="add_task_board.php" class="pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';

	//echo '<div class="clearfix"></div>';
	$list_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(IF(`security`='My Checklist',1,0)) mylists, SUM(IF(`security`='My Checklist' AND `checklist_type`='ongoing',1,0)) myongoing, SUM(IF(`security`='My Checklist' AND `checklist_type`='daily',1,0)) mydaily, SUM(IF(`security`='My Checklist' AND `checklist_type`='weekly',1,0)) myweekly, SUM(IF(`security`='My Checklist' AND `checklist_type`='monthly',1,0)) mymonthly, SUM(IF(`security`='Company Checklist',1,0)) companylists, SUM(IF(`security`='Company Checklist' AND `checklist_type`='ongoing',1,0)) companyongoing, SUM(IF(`security`='Company Checklist' AND `checklist_type`='daily',1,0)) companydaily, SUM(IF(`security`='Company Checklist' AND `checklist_type`='weekly',1,0)) companyweekly, SUM(IF(`security`='Company Checklist' AND `checklist_type`='monthly',1,0)) companymonthly FROM `checklist` WHERE `deleted`=0 AND (`assign_staff` LIKE '%,".$_SESSION['contactid'].",%' OR `security`='My Checklist')"));

	echo '<div class="tab-container">';

		if(strpos($tab_config, 'my_') !== false) {
			echo "
				<div class='pull-left tab tab-nomargin'>
					<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click here to see your personal Checklists.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
					<a href='my_checklist.php'><button type='button' class='btn brand-btn mobile-block active_tab mobile-100'>My Checklists (".$list_count['mylists'].")</button></a>
				</div>";
		} else {
			echo "<script> window.location.replace('company_checklist.php'); </script>";
		}
		if(strpos($tab_config, 'company_') !== false) {
			echo "
				<div class='pull-left tab tab-nomargin'>
					<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click here to view company Checklists you have been provided access to.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
					<a href='company_checklist.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Company Checklists (".$list_count['companylists'].")</button></a>
				</div>";
		}
		if(strpos($tab_config, 'project_tab') !== false) {
			echo "
				<div class='pull-left tab tab-nomargin'>
					<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click here to see Checklists from Project Tickets.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
					<a href='project_checklist.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Project Checklists</button></a>
				</div>";
		}

		//echo "<a href='tasks.php?category=All'><button type='button' class='btn brand-btn mobile-block'>Community Checklist</button></a>";

		if(strpos($tab_config, 'reporting') !== false) {
			echo "
				<div class='pull-left tab tab-nomargin'>
					<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click here to see all Checklist activity.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
					<a href='checklist_report.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Reporting</button></a>
				</div>";
		}

	echo '</div><div class="clearfix"></div><br />';

    $type = $_GET['type'];
    $active_on = '';
    $active_daily = '';
    $active_weekly = '';
    $active_monthly = '';
	if($type == '') {
		$type = trim(explode('my_',$tab_config)[1],',');
	}
    if($type == 'ongoing') {
        $active_on = ' active_tab';
    } else if($type == 'daily') {
        $active_daily = ' active_tab';
    } else if($type == 'weekly') {
        $active_weekly = ' active_tab';
    } else {
        $active_monthly = ' active_tab';
    }

	echo '<div class="tab-container">';
		if(strpos($tab_config, 'my_ongoing') !== false) {
			echo "
				<div class='pull-left tab tab-nomargin'>
					<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Uncategorized/Ongoing Checklists.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
					<a href='my_checklist.php?type=ongoing'><button type='button' class='btn brand-btn mobile-block ".$active_on."'>Ongoing (".$list_count['myongoing'].")</button></a>
				</div>";
		}
		if(strpos($tab_config, 'my_daily') !== false) {
			echo "
				<div class='pull-left tab tab-nomargin'>
					<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Checklists used every day.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
					<a href='my_checklist.php?type=daily'><button type='button' class='btn brand-btn mobile-block ".$active_daily."'>Daily (".$list_count['mydaily'].")</button></a>
				</div>";
		}
		if(strpos($tab_config, 'my_weekly') !== false) {
			echo "
				<div class='pull-left tab tab-nomargin'>
					<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Checklists used for each week.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
					<a href='my_checklist.php?type=weekly'><button type='button' class='btn brand-btn mobile-block ".$active_weekly."'>Weekly (".$list_count['myweekly'].")</button></a>
				</div>";
		}
		if(strpos($tab_config, 'my_monthly') !== false) {
			echo "
				<div class='pull-left tab tab-nomargin'>
					<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Checklists used for each month.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
					<a href='my_checklist.php?type=monthly'><button type='button' class='btn brand-btn mobile-block ".$active_monthly."'>Monthly (".$list_count['mymonthly'].")</button></a>
				</div>";
		}
	echo '</div><div class="clearfix"></div><br />';

	echo '
		<div class="mobile-100-container">
			<a href="add_checklist.php" class="btn brand-btn mobile-block gap-bottom pull-right">Add Checklist</a>
			<span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a Checklist."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>
		</div>';
    ?>

	<br><br>

    <form name="form_sites" method="post" action="" class="form-inline" role="form">
        <?php

        $contactid = $_SESSION['contactid'];
        echo '<div class="mobile-100-container">';
        $result = mysqli_query($dbc, "SELECT * FROM checklist WHERE deleted = 0 AND security = 'My Checklist' AND checklist_type='$type'");

        $checklistid_url = $_GET['checklistid'];

        while($row = mysqli_fetch_array($result)) {
            $active_daily = '';
            if(($checklistid_url == $row['checklistid'])) {
                $active_daily = 'active_tab';
            }

            echo "<a href='my_checklist.php?type=".$type."&checklistid=".$row['checklistid']."'><button type='button' class='mobile-100 btn brand-btn mobile-block ".$active_daily."' >".$row['checklist_name']."</button></a>&nbsp;&nbsp;";
        }
        ?>
        <br><br>
		</div>
        <div>
        <?php
        if(!empty($_GET['checklistid'])) {
            $checklistid = $_GET['checklistid'];
			$checklist = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid`='$checklistid'"));
            $checklist_name = $checklist['checklist_name'];
			$checklist_flag = $checklist['flag_colour'];
			$checklist_type = $checklist['checklist_type'];
			$reset_day = $checklist['reset_day'];
			$reset_time = date('h:i:s', strtotime($checklist['reset_time']));
			$reset_date = '';
			if($checklist_type != 'ongoing') {
				$reset_date = '';
				if($reset_time > date('h:i:s')) {
					$reset = 'past';
				} else {
					$reset = 'last';
				}
				switch($checklist_type) {
				case 'daily':
					$reset_date = date('Y-m-').($reset == 'past' ? date('d') : date('d') - 1).' '.$reset_time;
					break;
				case 'weekly':
					$current_day_of_week = date('w');
					if($current_day_of_week == $reset_day && $reset == 'past') {
						$reset_date = date('Y-m-d ').$reset_time;
					} else {
						$weekdays = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
						$reset_date = date('Y-m-d ', strtotime('Last '.$weekdays[$reset_day])).$reset_time;
					}
					break;
				case 'monthly':
					if(date('d') == $reset_day && $reset == 'past') {
						$reset_date = date('Y-m-d ').$reset_time;
					} else {
						$day = date('d');
						$month = date('m');
						if($day < $reset_day) {
							$month--;
						}
						$reset_date = date('Y-m-d ', strtotime(date("Y-$month-$day"))).$reset_time;
					}
					break;
				}
				
				mysqli_query($dbc, "UPDATE `checklist_name` SET `checked`=0 WHERE `time_checked` < '$reset_date' AND `checklistid` = '$checklistid' AND `deleted`=0");
			}

            echo '</form>';

			$query_check_credentials = "SELECT * FROM checklist_document WHERE checklistid='$checklistid' ORDER BY checklistdocid DESC";
			$result = mysqli_query($dbc, $query_check_credentials);
			$num_rows = mysqli_num_rows($result);
			if($num_rows > 0) {
				echo "<table class='table table-bordered' style='width:100%;'>
				<tr class='hidden-xs hidden-sm'>
				<th>Document</th>
				<th>Date</th>
				<th>Uploaded By</th>
				</tr>";
				while($row = mysqli_fetch_array($result)) {
					echo '<tr>';
					$by = $row['created_by'];
					echo '<td data-title="Schedule"><a href="download/'.$row['document'].'" target="_blank">'.$row['document'].'</a></td>';
					echo '<td data-title="Schedule">'.$row['created_date'].'</td>';
					echo '<td data-title="Schedule">'.get_staff($dbc, $by).'</td>';
					//echo '<td data-title="Schedule"><a href=\'delete_restore.php?action=delete&ticketdocid='.$row['ticketdocid'].'&ticketid='.$row['ticketid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
					echo '</tr>';
				}
				echo '</table>';
			}

            echo '<form name="form_sites1" method="post" action="" class="form-inline" role="form" '.($checklist_flag == '' ? '' : 'style="background-color: #'.$checklist_flag.';"').'>';

			echo '<div class="tab-container">';

				echo '
					<div class="pull-right tab">
						<span class="popover-examples list-inline pull-left" style="margin-top:5px;"><a data-toggle="tooltip" data-placement="top" title="Click here to edit the current Checklist."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>
						<a href="add_checklist.php?checklistid='.$checklistid.'" class="btn brand-btn mobile-block mobile-100 gap-bottom pull-right">Edit</a>
					</div>';

				echo '
					<div class="pull-right tab">
						<span class="popover-examples list-inline pull-left" style="margin-top:5px;"><a data-toggle="tooltip" data-placement="top" title="Click here to delete the current Checklist."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>
						<a href=\'../delete_restore.php?action=delete&&subtab=my&type='.$_GET['type'].'&remove_checklist=all&checklistid='.$checklistid.'\' onclick="return confirm(\'Are you sure?\')" class="btn brand-btn mobile-block mobile-100 gap-bottom pull-right">Archive</a>
					</div>';

				//echo '<span id="'.$checklistid.'" class="iframe_open_edit adder btn brand-btn pull-right mobile-100-pull-right" style="width:auto;">Edit</span>';

				echo '
					<div class="pull-right tab">
						<span class="popover-examples list-inline pull-left" style="margin-top:5px;"><a data-toggle="tooltip" data-placement="top" title="Click here to export the current Checklist into a PDF."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>
						<button type="submit" name="export_pdf" value="Submit" class="btn brand-btn mobile-block mobile-100 pull-right">Export</button>
					</div>'; ?>
			<span class="pull-right" style="cursor: pointer;" data-checklist="BOARD<?php echo $checklistid; ?>">
				<span style="padding: 0.25em 0.5em;" title="Flag This!" onclick="flag_item(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-flag-icon.png" style="height:2.5em;"></span>
				<span style="padding: 0.25em 0.5em;" title="Send Alert" onclick="send_alert(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-alert-icon.png" style="height:2.5em;"></span>
				<span style="padding: 0.25em 0.5em;" title="Send Email" onclick="send_email(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-email-icon.png" style="height:2.5em;"></span>
				<span style="padding: 0.25em 0.5em;" title="Schedule Reminder" onclick="send_reminder(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-reminder-icon.png" style="height:2.5em;"></span>
				<span style="padding: 0.25em 0.5em;" title="Attach File" onclick="attach_file(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-attachment-icon.png" style="height:2.5em;"></span>
				<span style="padding: 0.25em 0.5em;" title="Archive Checklist" onclick="archive(this); return false;"><img src="<?php echo WEBSITE_URL; ?>/img/icons/ROOK-trash-icon.png" style="height:2.5em;"></span>
				<br /><input type="text" name="reminder_board_<?php echo $checklistid; ?>" style="display:none; margin-top: 2em;" class="form-control datepicker" />
			</span>
			<input type="file" name="attach_board_<?php echo $checklistid; ?>" style="display:none;" />
            <?php echo '<div class="clearfix"></div><br />';
			echo '<input type="hidden" name="checklistid" value="'.$checklistid.'" />';

            echo '<ul id="sortable'.$i.'" class="connectedChecklist">
            <li class="ui-state-default ui-state-disabled no-sort" style="cursor:pointer; font-size: 30px;">'.$checklist_name.'</li>';

            $result = mysqli_query($dbc, "SELECT * FROM checklist_name WHERE checklistid='$checklistid' AND checked = 0 AND deleted = 0 ORDER BY priority");

            while($row = mysqli_fetch_array( $result )) {
                echo '<li id="'.$row['checklistnameid'].'" class="ui-state-default" '.($row['flag_colour'] == '' ? '' : 'style="background-color: #'.$row['flag_colour'].';"').'>';
				echo '<span style="cursor:pointer; font-size: 25px;"><input type="checkbox" onclick="checklistChange(this);" value="'.$row['checklistnameid'].'" style="height: 1.25em; width: 1.25em;" name="checklistnameid[]">';
				echo '<span class="pull-right" style="display:inline-block; width:calc(100% - 2em);" data-checklist="'.$row['checklistnameid'].'">';
				echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Flag This!" onclick="flag_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-flag-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Send Alert" onclick="send_alert(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-alert-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Send Email" onclick="send_email(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-email-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Schedule Reminder" onclick="send_reminder(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reminder-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Attach File" onclick="attach_file(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Reply" onclick="send_reply(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Add Time" onclick="add_time(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-timer-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:12.5%;" title="Archive Item" onclick="archive(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-trash-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '</span>';
				echo '<input type="text" name="reply_'.$row['checklistnameid'].'" style="display:none; margin-top: 2em;" class="form-control" />';
				echo '<input type="text" name="checklist_time_'.$row['checklistnameid'].'" style="display:none; margin-top: 2em;" class="form-control timepicker" />';
				echo '<input type="text" name="reminder_'.$row['checklistnameid'].'" style="display:none; margin-top: 2em;" class="form-control datepicker" />';
				echo '<input type="file" name="attach_'.$row['checklistnameid'].'" style="display:none;" class="form-control" />';
				echo '<br /><span class="display-field">'.html_entity_decode($row['checklist']).'</span>&nbsp;&nbsp;';
				$documents = mysqli_query($dbc, "SELECT * FROM checklist_name_document WHERE checklistnameid='".$row['checklistnameid']."'");
				while($doc = mysqli_fetch_array($documents)) {
					echo '<br /><a href="download/'.$doc['document'].'">'.$doc['document'].' (Uploaded by '.get_staff($dbc, $doc['created_by']).' on '.$doc['created_date'].')</a>';
				}
				echo '<img class="drag_handle pull-right" src="'.WEBSITE_URL.'/img/icons/hold.png" style="height:1.5em; width:1.5em;" /></span>';

                echo '</li>';
            }

            echo '</form>';
            echo '<form name="form_sites2" method="post" action="" class="form-inline no-sort" role="form">';

            echo '<li class="new_task_box no-sort"><input type="checkbox" style="height: 30px; width: 30px;">&nbsp;&nbsp;&nbsp;<input onChange="changeEndAme(this)" name="add_checklist" placeholder="Add New Checklist Item" id="add_new_task '.$checklistid.'" type="text" class="form-control" /></li>';

            $result = mysqli_query($dbc, "SELECT * FROM checklist_name WHERE checklistid='$checklistid' AND checked = 1 AND deleted = 0");

            while($row = mysqli_fetch_array( $result )) {
                $info = ' : '.$row['updated_date']. ' : '.$row['updated_by'];
                echo '<li id="'.$row['checklistnameid'].'" class="ui-state-default no-sort"><span style="cursor:pointer; font-size: 20px;"><input type="checkbox" onclick="checklistChange(this);" checked value="'.$row['checklistnameid'].'" style="height: 30px; width: 30px;" name="checklistnameid[]">';

				echo '&nbsp;&nbsp;'.html_entity_decode($row['checklist']).$info;
				$documents = mysqli_query($dbc, "SELECT * FROM checklist_name_document WHERE checklistnameid='".$row['checklistnameid']."'");
				while($doc = mysqli_fetch_array($documents)) {
					echo '<br /><a href="download/'.$doc['document'].'">'.$doc['document'].' (Uploaded by '.get_staff($dbc, $doc['created_by']).' on '.$doc['created_date'].')</a>';
				}
				echo '</span>';

                echo '</li>';
            }

            //echo '<li class=""><span id="'.$status.'_'.$task_path.'_'.$taskboardid.'" class="iframe_open_add2 btn brand-btn pull-right">Add Task</span></li>';

            echo '</ul>';
            $i++;
        }
        ?>
        </div>

		</form>
	</div>
</div>

<?php include ('../footer.php'); ?>
