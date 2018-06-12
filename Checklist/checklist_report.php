<?php
/*
Inventory Listing
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);
if (isset($_POST['printpdf'])) {
    $starttimepdf = $_POST['starttimepdf'];
    $endtimepdf = $_POST['endtimepdf'];

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
                $this->Image($image_file, 10, 10, '', '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = '<p style="text-align:right;">'.REPORT_HEADER.'</p>';
            $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);

            $this->SetFont('helvetica', '', 13);
            $footer_text = 'Checklist Report From <b>'.START_DATE.'</b> To <b>'.END_DATE.'</b>';
            $this->writeHTMLCell(0, 0, 0 , 35, $footer_text, 0, 0, false, "R", true);
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

    $pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage('L', 'LETTER');
    $pdf->SetFont('helvetica', '', 9);

    $html .= report_checklist($dbc, $starttimepdf, $endtimepdf, 'padding:3px; border:1px solid black;', 'background-color:grey; color:black;', 'background-color:lightgrey; color:black;');

    $today_date = date('Y-m-d');
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Download/checklist_report_'.$today_date.'.pdf', 'F');
    ?>

    <script type="text/javascript" language="Javascript">
    window.open('Download/checklist_report_<?php echo $today_date;?>.pdf', 'fullscreen=yes');
    </script>
    <?php
    $starttime = $starttimepdf;
    $endtime = $endtimepdf;
    } ?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('checklist');
$tab_config = get_config($dbc, 'checklist_tabs_' . $_SESSION['contactid']);
?>
<div class="container">
	<div class="row">

    <h1 class="single-pad-bottom pull-left">Checklists Reporting</h1>
    <?php
	if(config_visible_function($dbc, 'checklist') == 1) {
		echo '<br /><div class="pull-right">';
			echo '<span class="popover-examples list-inline" style="margin:0 7px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			echo '<a href="field_config.php?from_url=checklist_report.php" class="mobile-block"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
		echo '</div><br clear="all" /><br />';
	}
	echo '<div class="clearfix"></div>';
	// $list_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(IF(`security`='My Checklist',1,0)) mylists, SUM(IF(`security`='My Checklist' AND `checklist_type`='ongoing',1,0)) myongoing, SUM(IF(`security`='My Checklist' AND `checklist_type`='daily',1,0)) mydaily, SUM(IF(`security`='My Checklist' AND `checklist_type`='weekly',1,0)) myweekly, SUM(IF(`security`='My Checklist' AND `checklist_type`='monthly',1,0)) mymonthly, SUM(IF(`security`='Company Checklist',1,0)) companylists, SUM(IF(`security`='Company Checklist' AND `checklist_type`='ongoing',1,0)) companyongoing, SUM(IF(`security`='Company Checklist' AND `checklist_type`='daily',1,0)) companydaily, SUM(IF(`security`='Company Checklist' AND `checklist_type`='weekly',1,0)) companyweekly, SUM(IF(`security`='Company Checklist' AND `checklist_type`='monthly',1,0)) companymonthly FROM `checklist` WHERE `deleted`=0 AND (`assign_staff` LIKE '%,".$_SESSION['contactid'].",%' OR `security`='My Checklist')"));
    
	echo '<div class="tab-container">';

		$query_retrieve_subtabs = mysqli_query($dbc, "SELECT * FROM `checklist_subtab` WHERE (`created_by` = ".$_SESSION['contactid']." OR `shared` LIKE '%,".$_SESSION['contactid'].",%') AND `deleted`=0");

        while ($row = mysqli_fetch_array($query_retrieve_subtabs)) {
            if (strpos($tab_config, $row['subtabid'] . '_') !== false) {
                $subtabid_row = $row['subtabid'];
                $active_subtab = '';
                if ($subtabid == $subtabid_row) {
                    $active_subtab = ' active_tab';
                }
                $subtab_name = $row['name'];
                $query_retrieve_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(IF(`subtabid`='$subtabid_row',1,0)) subtabcount FROM `checklist` WHERE `deleted` = 0"));
                $subtab_count = $query_retrieve_count['subtabcount'];

                echo "
                    <div class='pull-left tab tab-nomargin'>
                        <a href='checklist.php?subtabid=$subtabid_row'><button type='button' class='btn brand-btn mobile-block mobile-100 $active_subtab'>$subtab_name ($subtab_count)</button></a>
                    </div>";
            }
        }

		//echo "<a href='tasks.php?category=All'><button type='button' class='btn brand-btn mobile-block'>Community Checklist</button></a>";

        if(strpos($tab_config, 'project_tab') !== false) {
            echo "
                <div class='pull-left tab tab-nomargin'>
                    <span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click here to see Checklists from ".PROJECT_NOUN." ".TICKET_TILE.".'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
                    <a href='project_checklist.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Project Checklists</button></a>
                </div>";
        }
		if(strpos($tab_config, 'reporting') !== false) {
			echo "
				<div class='pull-left tab tab-nomargin'>
					<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='Click here to see all Checklist activity.'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>
					<a href='checklist_report.php'><button type='button' class='btn brand-btn mobile-block active_tab mobile-100'>Reporting</button></a>
				</div>";
		}

	echo '</div><div class="clearfix"></div><br />';
    ?>

    <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        Sub Tabs, Checklist Types, Checklist Names, and Date Ranges can be selected below to narrow down the reports. Checklist Names will appear when a Sub Tab and Checklist Type is selected.</div>
        <div class="clearfix"></div>
    </div>

    <?php

    echo '<div class="tab-container"><div class="pull-left tab tab-nomargin">Sub Tab:</div>';

    $subtabid = $_GET['subtabid'];

    $query_retrieve_subtabs = mysqli_query($dbc, "SELECT * FROM `checklist_subtab` WHERE (`created_by` = ".$_SESSION['contactid']." OR `shared` LIKE '%,".$_SESSION['contactid'].",%') AND `deleted`=0");

    while ($row = mysqli_fetch_array($query_retrieve_subtabs)) {
        if (strpos($tab_config, $row['subtabid'] . '_') !== false) {
            $subtabid_row = $row['subtabid'];
            $active_subtab = '';
            if ($subtabid == $subtabid_row) {
                $active_subtab = ' active_tab';
                $subtab_shared = $row['shared'];
            }
            $subtab_name = $row['name'];
            $query_retrieve_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(IF(`subtabid`='$subtabid_row',1,0)) subtabcount FROM `checklist` WHERE `deleted` = 0"));
            $subtab_count = $query_retrieve_count['subtabcount'];

            echo "
                <div class='pull-left tab tab-nomargin'>
                    <a href='checklist_report.php?subtabid=$subtabid_row'><button type='button' class='btn brand-btn mobile-block mobile-100 $active_subtab'>$subtab_name ($subtab_count)</button></a>
                </div>";
        }
    }

    echo '</div><div class="clearfix"></div><br />';

    $type = $_GET['type'];
    $active_on = '';
    $active_daily = '';
    $active_weekly = '';
    $active_monthly = '';
    if($type == 'daily') {
        $active_daily = ' active_tab';
    } else if($type == 'weekly') {
        $active_weekly = ' active_tab';
    } else if($type == 'monthly') {
        $active_monthly = ' active_tab';
    } else if($type == 'ongoing') {
        $active_on = ' active_tab';
    }

    $list_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(IF(c.`subtabid`='$subtabid' AND c.`checklist_type`='ongoing',1,0)) ongoing, SUM(IF(c.`subtabid`='$subtabid' AND c.`checklist_type`='daily',1,0)) daily, SUM(IF(c.`subtabid`='$subtabid' AND c.`checklist_type`='weekly',1,0)) weekly, SUM(IF(c.`subtabid`='$subtabid' AND c.`checklist_type`='monthly',1,0)) monthly FROM `checklist` c INNER JOIN `checklist_subtab` s ON c.`subtabid` = s.`subtabid` AND s.`deleted`=0 WHERE c.`deleted` = 0 AND (c.`assign_staff` LIKE '%,".$_SESSION['contactid'].",%' OR s.`created_by` = '".$_SESSION['contactid']."' OR s.`shared` LIKE '%,".$_SESSION['contactid'].",%')"));

    echo '<div class="tab-container"><div class="pull-left tab tab-nomargin">Checklist Type:</div>';
        if (strpos($tab_config, $subtabid . '_ongoing') !== false) {
            echo "
                <div class='pull-left tab tab-nomargin'>
                    <a href='checklist_report.php?subtabid=$subtabid&type=ongoing'><button type='button' class='btn brand-btn mobile-block ".$active_on."'>Ongoing (".$list_count['ongoing'].")</button></a>
                </div>";
        }

        if (strpos($tab_config, $subtabid . '_daily') !== false) {
            echo "
                <div class='pull-left tab tab-nomargin'>
                    <a href='checklist_report.php?subtabid=$subtabid&type=daily'><button type='button' class='btn brand-btn mobile-block ".$active_daily."'>Daily (".$list_count['daily'].")</button></a>
                </div>";
        }

        if (strpos($tab_config, $subtabid . '_weekly') !== false) {
            echo "
                <div class='pull-left tab tab-nomargin'>
                    <a href='checklist_report.php?subtabid=$subtabid&type=weekly'><button type='button' class='btn brand-btn mobile-block ".$active_weekly."'>Weekly (".$list_count['weekly'].")</button></a>
                </div>";
        }

        if (strpos($tab_config, $subtabid . '_monthly') !== false) {
            echo "
                <div class='pull-left tab tab-nomargin'>
                    <a href='checklist_report.php?subtabid=$subtabid&type=monthly'><button type='button' class='btn brand-btn mobile-block ".$active_monthly."'>Monthly (".$list_count['monthly'].")</button></a>
                </div>";
        }
    echo '</div><div class="clearfix"></div><br />';


    if (!empty($_GET['subtabid']) && !empty($_GET['type'])) {
        echo '<div class="tab-container"><div class="pull-left tab tab-nomargin">Checklists:</div>';
        $contactid = $_SESSION['contactid'];
        $subtabid = $_GET['subtabid'];
        $type = $_GET['type'];

        $result = mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `deleted` = 0 AND `subtabid` = '$subtabid' AND checklist_type='$type'");

        $checklistid_url = $_GET['checklistid'];

        while($row = mysqli_fetch_array($result)) {
            $active_checklist = '';
            if(($checklistid_url == $row['checklistid'])) {
                $active_checklist = 'active_tab';
            }

            echo "
                <div class='pull-left tab tab-nomargin'>
                    <a href='checklist_report.php?subtabid=".$subtabid."&type=".$type."&checklistid=".$row['checklistid']."'><button type='button' class='mobile-100 btn brand-btn mobile-block ".$active_checklist."' >".$row['checklist_name']."</button></a>
                </div>";
        }
    }
    echo '</div><div class="clearfix"></div><br />';
    ?>

	<br><br>

    <form name="form_sites" method="post" action="" class="form-inline" role="form">
        <?php
            if (isset($_POST['search_checklist_report'])) {
                $starttime = $_POST['starttime'];
                $endtime = $_POST['endtime'];
            }

            if($starttime == 0000-00-00) {
                $starttime = date('Y-m-d');
            }

            if($endtime == 0000-00-00) {
                $endtime = date('Y-m-d');
            }
        ?>

            <center><div class="form-group">
                From: <input name="starttime" type="text" class="datepicker" value="<?php echo $starttime; ?>">
                &nbsp;&nbsp;&nbsp;
                Until: <input name="endtime" type="text" class="datepicker" value="<?php echo $endtime; ?>">
            <button type="submit" name="search_checklist_report" value="Search" class="btn brand-btn mobile-block">Submit</button></div></center>

            <input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>">
            <input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>">

            <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button>
            <br><br>

            <?php echo report_checklist($dbc, $starttime, $endtime, '', '', ''); ?>
        </div>

		</form>
	</div>
</div>

<?php include ('../footer.php'); ?>

<?php
function report_checklist($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style) {
    $query_filters = '';

    if (!empty($_GET['subtabid'])) {
        $subtabid = $_GET['subtabid'];
        $query_filters .= " AND `subtabid` = '$subtabid'";
    }
    if (!empty($_GET['checklist_type'])) {
        $type = $_GET['type'];
        $query_filters .= " AND `checklist_type` = '$type'";
    }
    if (!empty($_GET['checklistid'])) {
        $checklistid = $_GET['checklistid'];
        $query_filters .= " AND `checklistid` = '$checklistid'";
    }

    $query_check_credentials = "SELECT * FROM `checklist_report` WHERE `date` >= '$starttime' AND `date` <= '$endtime' $query_filters ORDER BY `checklistreportid` DESC";

    $result = mysqli_query($dbc, $query_check_credentials) or die(mysqli_error($dbc));

    $num_rows = mysqli_num_rows($result);
    if($num_rows > 0) {
        $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
        $report_data .= '<tr style="'.$table_row_style.'"><th>Date</th>
            <th>User</th>
            <th>Sub Tab</th>
            <th>Checklist Type</th>
            <th>Checklist Name</th>
            <th>Report</th>
            </tr>';
    } else {
        $report_data = "<h2>No Record Found.</h2>";
    }
    
    while($row = mysqli_fetch_array( $result ))
    {
        $user = $row['user'];
        $date = $row['date'];
        $subtab = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist_subtab` WHERE `subtabid` = '" . $row['subtabid'] . "'"))['name'];
        $query_checklist = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid` = '" . $row['checklistid'] . "'"));
        $checklist_type = $query_checklist['checklist_type'];
        $checklist_name = $query_checklist['checklist_name'];

        $pos1 = strlen($user) + 1;
        $pos2 = strrpos($row['report'], "on") - $pos1 - 1;
        $report = substr($row['report'], $pos1, $pos2);

        $report_data .= '<tr nobr="true">';
        $report_data .= '<td data-title="Date">' . $date . '</td>';
        $report_data .= '<td data-title="User">' . $user . '</td>';
        $report_data .= '<td data-title="Subtab">' . $subtab . '</td>';
        $report_data .= '<td data-title="Checklist Type">' . $checklist_type . '</td>';
        $report_data .= '<td data-title="Checklist Name">' . $checklist_name . '</td>';
        $report_data .= '<td data-title="Report">' . $report . '</td>';
        $report_data .= "</tr>";
    }

    $report_data .= '</table>';
    return $report_data;
}