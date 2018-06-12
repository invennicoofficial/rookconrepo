<?php
/*
Client Listing
*/
include ('../include.php');
checkAuthorised('report');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

if (isset($_POST['printpdf'])) {
    $starttimepdf = $_POST['starttimepdf'];
    $endtimepdf = $_POST['endtimepdf'];
    $businessidpdf = $_POST['businessidpdf'];
    $siteidpdf = $_POST['siteidpdf'];
    $ticketidpdf = $_POST['ticketidpdf'];
    $projectidpdf = $_POST['projectidpdf'];
    $hide_staffpdf = $_POST['hide_staffpdf'];
    $hide_wopdf = $_POST['hide_wopdf'];
    $disable_staffpdf = $_POST['disable_staffpdf'];

    DEFINE('START_DATE', $starttimepdf);
    DEFINE('END_DATE', $endtimepdf);
    DEFINE('REPORT_LOGO', get_config($dbc, 'report_logo'));
    DEFINE('REPORT_HEADER', html_entity_decode(get_config($dbc, 'report_header')));
    DEFINE('REPORT_FOOTER', html_entity_decode(get_config($dbc, 'report_footer')));

	class MYPDF extends TCPDF {

		public function Header() {
			//$image_file = WEBSITE_URL.'/img/Clinic-Ace-Logo-Final-250px.png';
            if(REPORT_LOGO != '') {
                $image_file = 'download/'.REPORT_LOGO;
                $this->Image($image_file, 10, 10, 80, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = '<p style="text-align:right;">'.REPORT_HEADER.'</p>';
            $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);

            $this->SetFont('helvetica', '', 13);
            $footer_text = TICKET_NOUN.' Activity';
            $this->writeHTMLCell(0, 0, 0 , 15, $footer_text, 0, 0, false, "C", true);
		}

		// Page footer
		public function Footer() {
            $this->SetY(-24);
            $this->SetFont('helvetica', 'I', 9);
            $footer_text = '<span style="text-align:left;">'.REPORT_FOOTER.'</span>';
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);

			// Position at 15 mm from bottom
			$this->SetY(-15);
            $this->SetFont('helvetica', 'I', 9);
			$footer_text = '<span style="text-align:right;">Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().' printed on '.date('Y-m-d H:i:s').'</span>';
			$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "R", true);
    	}
	}

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	$pdf->SetMargins(PDF_MARGIN_LEFT, 25, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage('L', 'LETTER');
    $pdf->SetFont('helvetica', '', 9);

    $html .= report_output($dbc, $starttimepdf, $endtimepdf, $businessidpdf, $siteidpdf, $ticketidpdf, $projectidpdf, $hide_staffpdf, $hide_wopdf, $disable_staffpdf, 'padding:3px; border:1px solid black;', '', '', 'print');

    $today_date = date('Y_m_d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/activity_report_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.open('Download/activity_report_<?= $today_date ?>.pdf', 'fullscreen=yes');
	</script><?php
    $starttime = $starttimepdf;
    $endtime = $endtimepdf;
    $businessid = $businessidpdf;
    $siteid = $siteidpdf;
    $ticketid = $ticketidpdf;
    $projectid = $projectidpdf;
    $hide_staff = $hide_staffpdf;
    $hide_wo = $hide_wopdf;
    $disable_staff = $disable_staffpdf;
} ?>

<script type="text/javascript">

</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <?php echo reports_tiles($dbc);  ?>

        <div class="notice double-gap-bottom popover-examples">
            <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11"><span class="notice-name">NOTE:</span> Displays a list of work done by staff by date.</div>
            <div class="clearfix"></div>
        </div>

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <input type="hidden" name="report_type" value="<?php echo $_GET['type']; ?>">
        <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

            <?php
            if (isset($_POST['search_email_submit'])) {
                $starttime = $_POST['starttime'];
                $endtime = $_POST['endtime'];
                $businessid = $_POST['businessid'];
                $siteid = $_POST['siteid'];
                $ticketid = $_POST['ticketid'];
                $projectid = $_POST['projectid'];
                $hide_staff = $_POST['hide_staff'];
                $hide_wo = $_POST['hide_wo'];
                $disable_staff = implode('#',$_POST['disable_staff']);
            }
            if($starttime == 0000-00-00) {
                $starttime = date('Y-m-01');
            }

            if($endtime == 0000-00-00) {
                $endtime = date('Y-m-d');
            }
            if(!($businessid > 0)) {
                $businessid = '%';
            }
            if(!($siteid > 0)) {
                $siteid = '%';
            } ?>

            <center>
				<div class="form-group col-sm-5">
					<label class="col-sm-4"><?= BUSINESS_CAT ?>:</label>
					<div class="col-sm-8">
						<select name="businessid" class="chosen-select-deselect" data-placeholder="Select <?= BUSINESS_CAT ?>"><option />
							<?php foreach(sort_contacts_query($dbc->query("SELECT `contactid`, `name`, `last_name`, `first_name` FROM `contacts` WHERE `deleted`=0 AND `status` > 0 AND `category`='".BUSINESS_CAT."'")) as $business_row) { ?>
								<option <?= $business_row['contactid'] == $businessid ? 'selected' : '' ?> value="<?= $business_row['contactid'] ?>"><?= $business_row['full_name'] ?></option>
							<?php } ?>
						</select>
					</div>
                </div>
				<div class="form-group col-sm-5">
					<label class="col-sm-4"><?= SITES_CAT ?>:</label>
					<div class="col-sm-8">
						<select name="siteid" class="chosen-select-deselect" data-placeholder="Select <?= SITES_CAT ?>"><option />
							<?php foreach(sort_contacts_query($dbc->query("SELECT `contactid`, `site_name`, `display_name` FROM `contacts` WHERE `deleted`=0 AND `status` > 0 AND `category`='".SITES_CAT."'")) as $site_row) { ?>
								<option <?= $site_row['contactid'] == $siteid ? 'selected' : '' ?> value="<?= $site_row['contactid'] ?>"><?= $site_row['full_name'] ?></option>
							<?php } ?>
						</select>
					</div>
                </div>
				<div class="form-group col-sm-5">
					<label class="col-sm-4"><?= TICKET_NOUN ?>:</label>
					<div class="col-sm-8">
						<select name="ticketid" class="chosen-select-deselect" data-placeholder="Select <?= TICKET_NOUN ?>"><option />
							<?php $ticket_list = $dbc->query("SELECT * FROM `tickets` WHERE `deleted`=0 ORDER BY `ticket_label`, `ticketid`");
							while($ticket_row = $ticket_list->fetch_assoc()) { ?>
								<option <?= $ticket_row['ticketid'] == $ticketid ? 'selected' : '' ?> value="<?= $ticket_row['ticketid'] ?>"><?= get_ticket_label($dbc, $ticket_row) ?></option>
							<?php } ?>
						</select>
					</div>
                </div>
				<div class="form-group col-sm-5">
					<label class="col-sm-4"><?= PROJECT_NOUN ?>:</label>
					<div class="col-sm-8">
						<select name="projectid" class="chosen-select-deselect" data-placeholder="Select <?= PROJECT_NOUN ?>"><option />
							<?php $project_list = $dbc->query("SELECT * FROM `project` WHERE `deleted`=0 ORDER BY `projectid`");
							while($project_row = $project_list->fetch_assoc()) { ?>
								<option <?= $project_row['projectid'] == $projectid ? 'selected' : '' ?> value="<?= $project_row['projectid'] ?>"><?= get_project_label($dbc, $project_row) ?></option>
							<?php } ?>
						</select>
					</div>
                </div>
				<div class="form-group col-sm-5">
					<label class="col-sm-4">Date From:</label>
					<div class="col-sm-8"><input name="starttime" type="text" class="datepicker form-control" value="<?php echo $starttime; ?>"></div>
                </div>
				<div class="form-group col-sm-5">
					<label class="col-sm-4">Date Until:</label>
					<div class="col-sm-8"><input name="endtime" type="text" class="datepicker form-control" value="<?php echo $endtime; ?>"></div>
				</div>
				<div class="form-group col-sm-10">
					<label class="form-checkbox"><input name="hide_staff" type="checkbox" <?= $hide_staff == 'hide' ? 'checked' : '' ?> value="hide">Hide Staff on Report</label>
					<label class="form-checkbox"><input name="hide_wo" type="checkbox" <?= $hide_wo == 'hide' ? 'checked' : '' ?> value="hide">Hide <?= TICKET_NOUN ?> on Report</label>
					<?php if($disable_staff != '') { ?>
						<label class="form-checkbox any-width"><input name="disable_staff[]" type="checkbox" checked value="<?= $disable_staff ?>">Keep Selected Staff Hidden from Report</label>
					<?php } ?>
				</div>
				<button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
			</center>

            <input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>">
            <input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>">
            <input type="hidden" name="businessidpdf" value="<?php echo $businessid; ?>">
            <input type="hidden" name="siteidpdf" value="<?php echo $siteid; ?>">
            <input type="hidden" name="ticketidpdf" value="<?php echo $ticketid; ?>">
            <input type="hidden" name="projectidpdf" value="<?php echo $projectid; ?>">
            <input type="hidden" name="hide_staffpdf" value="<?php echo $hide_staff; ?>">
            <input type="hidden" name="hide_wopdf" value="<?php echo $hide_wo; ?>">
            <input type="hidden" name="disable_staffpdf" value="<?php echo $disable_staff; ?>">

            <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>
            <br><br>
			<div class="clearfix"></div>

            <?php
                echo report_output($dbc, $starttime, $endtime, $businessid, $siteid, $ticketid, $projectid, $hide_staff, $hide_wo, $disable_staff);
            ?>
        </form>


        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_output($dbc, $starttime, $endtime, $businessid, $siteid, $ticketid, $projectid, $hide_staff, $hide_wo, $disable_staff, $table_style, $table_row_style, $grand_total_style) {
	$report_fields = explode(',', get_config($dbc, 'report_operation_fields'));
	$report_data = '';
	$starttime = filter_var($starttime,FILTER_SANITIZE_STRING);
	$endtime = filter_var($endtime,FILTER_SANITIZE_STRING);
	$businessid = filter_var($businessid,FILTER_SANITIZE_STRING);
	$siteid = filter_var($siteid,FILTER_SANITIZE_STRING);
	$ticketid = filter_var($ticketid,FILTER_SANITIZE_STRING);
	$projectid = filter_var($projectid,FILTER_SANITIZE_STRING);
	$disable_staff = filter_var($disable_staff,FILTER_SANITIZE_STRING);
	$report_data .= '<table width="100%" style="border:0 solid black;">
		<tr>
			<td style="padding:0.5em;width:15%;">Date Range:</td>
			<td style="padding:0.5em;width:35%;">'.date('M-j-Y',strtotime($starttime)).' to '.date('M-j-Y',strtotime($endtime)).'</td>
			<td style="padding:0.5em;width:15%;">'.($ticketid > 0 ? TICKET_NOUN.':' : '').'</td>
			<td style="padding:0.5em;width:35%;">'.($ticketid > 0 ? get_ticket_label($dbc, $dbc->query("SELECT * FROM `tickets` WHERE `ticketid`='$ticketid'")->fetch_assoc()) : '').'</td>
		</tr>
	</table>';
	$site_list = [];
	if($siteid > 0) {
		$site_list[] = $siteid;
	} else {
		$site_ids = $dbc->query("SELECT `tickets`.`siteid` FROM `ticket_attached` `time` LEFT JOIN `tickets` ON `time`.`ticketid`=`tickets`.`ticketid` LEFT JOIN `project` ON `tickets`.`projectid`=`project`.`projectid` WHERE `time`.`deleted`=0 AND `tickets`.`deleted`=0 AND IFNULL(`project`.`deleted`,0)=0 AND `time`.`src_table` LIKE 'Staff%' AND `time`.`date_stamp` BETWEEN '$starttime' AND '$endtime' AND '$ticketid' IN (`time`.`ticketid`,'') AND '$projectid' IN (`tickets`.`projectid`,'') AND CONCAT(',',IFNULL(`tickets`.`siteid`,''),',',IFNULL(`project`.`siteid`,''),',') LIKE '%,$siteid,%' AND CONCAT(',',IFNULL(`tickets`.`businessid`,''),',',IFNULL(`tickets`.`clientid`,''),',',IFNULL(`project`.`clientid`,''),',',IFNULL(`project`.`businessid`,''),',') LIKE '%,$businessid,%' AND '#$disable_staff#' NOT LIKE CONCAT('%#',`tickets`.`siteid`,'|',`time`.`date_stamp`,'|',`time`.`item_id`,'#%') GROUP BY `tickets`.`siteid`");
		if($site_ids->num_rows > 0) {
			while($siteid = $site_ids->fetch_assoc()) {
				$site_list[] = $siteid['siteid'];
			}
		} else {
			$site_list[] = '';
		}
	}
	foreach($site_list as $siteid) {
		foreach(sort_contacts_query($dbc->query("SELECT `display_name`,`site_name`,`mailing_address`,`city`,`province`,`zip_code`,`ship_to_address`,`ship_city`,`ship_state`,`ship_zip`,`business_street`,`business_city`,`business_state`,`business_zip` FROM `contacts` WHERE `contactid`='$siteid'")) as $site) {
			$report_data .= '<table width="100%" style="border:1px solid black;">
				<tr>
					<td style="padding:0.5em;">'.$site['full_name'].'<br />
					'.trim(empty($site['mailing_address']) ? (empty($site['ship_to_address']) ? $site['business_street'].' '.$site['business_city'].', '.$site['business_state'].' '.$site['business_zip'] : $site['ship_to_address'].' '.$site['ship_city'].', '.$site['ship_state'].' '.$site['ship_zip']) : $site['mailing_address'].' '.$site['city'].', '.$site['province'].' '.$site['zip_code'],', ').'</td>
				</tr>
			</table>';
		}
		$date_range = $dbc->query("SELECT SUM(IFNULL(NULLIF(`hours_set`,0),`hours_tracked`)) `hours`, `date_stamp` FROM `ticket_attached` `time` LEFT JOIN `tickets` ON `time`.`ticketid`=`tickets`.`ticketid` LEFT JOIN `project` ON `tickets`.`projectid`=`project`.`projectid` WHERE `time`.`deleted`=0 AND `tickets`.`deleted`=0 AND IFNULL(`project`.`deleted`,0)=0 AND `time`.`src_table` LIKE 'Staff%' AND `time`.`date_stamp` BETWEEN '$starttime' AND '$endtime' AND '$ticketid' IN (`time`.`ticketid`,'') AND '$projectid' IN (`tickets`.`projectid`,'') AND CONCAT(',',IFNULL(`tickets`.`siteid`,''),',',IFNULL(`project`.`siteid`,''),',') LIKE '%,$siteid,%' AND CONCAT(',',IFNULL(`tickets`.`businessid`,''),',',IFNULL(`tickets`.`clientid`,''),',',IFNULL(`project`.`clientid`,''),',',IFNULL(`project`.`businessid`,''),',') LIKE '%,$businessid,%' AND '#$disable_staff#' NOT LIKE CONCAT('%#',`tickets`.`siteid`,'|',`time`.`date_stamp`,'|',`time`.`item_id`,'#%') GROUP BY `time`.`date_stamp`");
		if($date_range->num_rows > 0) {
			$report_data .= '<table border="0" class="table no-border" width="100%" style="'.$table_style.'">';
			$ticket_types = explode(',',get_config($dbc, 'ticket_tabs'));
			while($date = $date_range->fetch_assoc()) {
				// Date Title
				$report_data .= '<tr>
					<td colspan="3" style="background-color:#CCCCCC;border:0 solid black;">'.date('D-M-j-Y',strtotime($date['date_stamp'])).'</td>
					<td style="background-color:#CCCCCC;border:0 solid black;text-align:right;">Total Man Hours for day: '.number_format($date['hours'],2).'</td>
				</tr>';
				$details = $dbc->query("SELECT `item_id`, SUM(IFNULL(NULLIF(`hours_set`,0),`hours_tracked`)) `hours`, MIN(`checked_in`) `start`, MAX(`checked_out`) `end`, GROUP_CONCAT(`time`.`ticketid`) `tickets` FROM `ticket_attached` `time` LEFT JOIN `tickets` ON `time`.`ticketid`=`tickets`.`ticketid` LEFT JOIN `project` ON `tickets`.`projectid`=`project`.`projectid` WHERE `time`.`deleted`=0 AND `tickets`.`deleted`=0 AND IFNULL(`project`.`deleted`,0)=0 AND `time`.`src_table` LIKE 'Staff%' AND '$ticketid' IN (`time`.`ticketid`,'') AND '$projectid' IN (`tickets`.`projectid`,'') AND CONCAT(',',IFNULL(`tickets`.`siteid`,''),',',IFNULL(`project`.`siteid`,''),',') LIKE '%,$siteid,%' AND CONCAT(',',IFNULL(`tickets`.`businessid`,''),',',IFNULL(`tickets`.`clientid`,''),',',IFNULL(`project`.`clientid`,''),',',IFNULL(`project`.`businessid`,''),',') LIKE '%,$businessid,%' AND `time`.`date_stamp`='{$date['date_stamp']}' AND '#$disable_staff#' NOT LIKE CONCAT('%#',`tickets`.`siteid`,'|',`time`.`date_stamp`,'|',`time`.`item_id`,'#%') GROUP BY `time`.`item_id`");
				while($detail = $details->fetch_assoc()) {
					//Tasks Details
					$types = [];
					$notes = [];
					$tickets = [];
					foreach(explode(',',$detail['tickets']) as $ticket) {
						if($ticket > 0) {
							$note_list = $dbc->query("SELECT * FROM `ticket_comment` WHERE `ticketid`='$ticket' AND `deleted`=0 AND `created_date`='".date('D-M-j-Y',strtotime($date['date_stamp']))."' AND `created_by`='{$detail['item_id']}'");
							while($note = $note_list->fetch_assoc()) {
								$notes[] = html_entity_decode($note['comment']);
							}
							if(empty($tickets[$ticket])) {
								$tickets[$ticket] = get_ticket_label($dbc, $dbc->query("SELECT * FROM `tickets` WHERE `ticketid`='$ticket'")->fetch_assoc());
							}
							$type = get_field_value('ticket_type', 'tickets', 'ticketid', $ticket);
							if($type != '') {
								foreach($ticket_types as $type_name) {
									if(config_safe_str($type_name) == $type) {
										$types[] = $type_name;
									}
								}
							}
						}
					}
					$report_data .= '<tr>
						<td style="width:10%;">Task Name:</td>
						<td style="width:45%;">'.implode(', ',array_unique($types)).'</td>
						<td style="width:10%;">'.($hide_staff != 'hide' ? 'Staff:' : '').'</td>
						<td style="width:35%;"><label class="form-checkbox any-width">'.($hide_staff != 'hide' ? '<input type="checkbox" class="inline" style="display:none;" name="disable_staff[]" value="'.$siteid.'|'.$date['date_stamp'].'|'.$detail['item_id'].'">'.get_contact($dbc, $detail['item_id']) : '').'</label></td>
					</tr>';
					$report_data .= '<tr>
						<td style="width:10%;">Start Time:</td>
						<td style="width:45%;">'.$detail['start'].'</td>
						<td style="width:10%;">Total Hours:</td>
						<td style="width:35%;">'.number_format($detail['hours'],2).'</td>
					</tr>';
					$report_data .= '<tr>
						<td style="width:10%;">End Time:</td>
						<td style="width:45%;">'.$detail['end'].'</td>
						<td style="width:10%;">'.($hide_wo != 'hide' ? TICKET_NOUN : '').'</td>
						<td style="width:35%;" rowspan="2">'.($hide_wo != 'hide' ? implode(', ',$tickets) : '').'</td>
					</tr>';
					$report_data .= '<tr>
						<td style="width:10%;">Notes:</td>
						<td colspan="2">'.implode(', ',$notes).'</td>
					</tr>';
					// Task Title
					$report_data .= '<tr>
						<td></td>
						<td colspan="2" style="background-color:#CCCCCC;border:0 solid black;">Service</td>
						<td style="background-color:#CCCCCC;border:0 solid black;">Total Hours</td>
					</tr>';
					// Tasks List
					$detail_task = $dbc->query("SELECT `position`, SUM(IFNULL(NULLIF(`hours_set`,0),`hours_tracked`)) `hours` FROM `ticket_attached` WHERE `date_stamp`='".$date['date_stamp']."' AND `deleted`=0 AND `item_id`='{$detail['item_id']}' AND `src_table` LIKE 'Staff%' GROUP BY `position`");
					while($task = $detail_task->fetch_assoc()) {
						$report_data .= '<tr>
							<td></td>
							<td colspan="2">'.$task['position'].'</td>
							<td>'.number_format($task['hours'],2).'</td>
						</tr>';
					}
					$report_data .= '<tr><td colspan="4" style="border-bottom:1px solid black;"></td></tr>';
				}
			}
			$report_data .= '</table>';
		} else {
			$report_data .= '<h3>No Hours Found</h3>';
		}
	}
    return $report_data;
}

?>