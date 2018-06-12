<?php
/* Treatment Page */
include ('../include.php');
checkAuthorised('treatment_charts');
include('manual_checklist.php');
error_reporting(0);
?>

</head>
<body>
<?php include_once ('../navigation.php');
error_reporting(0);

// Default Forms Configuration for new unconfigured software
mysqli_query($dbc, "DELETE FROM `patientform` WHERE `sub_heading` LIKE 'WCB AB-%'");
mysqli_query($dbc, "INSERT INTO `patientform` (`tab`, `category`, `heading_number`, `heading`, `sub_heading_number`, `sub_heading`, `form`) SELECT * FROM
	(SELECT 'front_desk' `tab`, 'forms' `cat`, '1' `head_num`, 'Consent Forms' `head`, '1.1' `sub_num`, 'Personal Consent Form' `sub_head`, 'Personal Consent Form' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'forms' `cat`, '1' `head_num`, 'Consent Forms' `head`, '1.1' `sub_num`, 'Personal Consent Form' `sub_head`, 'Personal Consent Form' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'forms' `cat`, '2' `head_num`, 'Questionnaires' `head`, '2.1' `sub_num`, 'Roland Morris Questionnaire' `sub_head`, 'Roland Morris Questionnaire' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'forms' `cat`, '2' `head_num`, 'Questionnaires' `head`, '2.2' `sub_num`, 'Neck Disability Questionnaire' `sub_head`, 'Neck Disability Questionnaire' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'assess' `cat`, '1' `head_num`, 'Functional Scales' `head`, '1.1' `sub_num`, 'Medical History Form' `sub_head`, 'Medical History Form' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'assess' `cat`, '2' `head_num`, 'Whiplash' `head`, '2.1' `sub_num`, 'Whiplash Associated Disorders' `sub_head`, 'Whiplash Associated Disorders' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'assess' `cat`, '3' `head_num`, 'General' `head`, '3.1' `sub_num`, 'General Assessment' `sub_head`, 'General Assessment' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'treatment' `cat`, '1' `head_num`, 'Treatment Notes' `head`, '1.1' `sub_num`, 'Treatment Notes' `sub_head`, 'Treatment Notes' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'treatment' `cat`, '2' `head_num`, 'General' `head`, '2.1' `sub_num`, 'General Treatment' `sub_head`, 'General Treatment' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'treatment' `cat`, '2' `head_num`, 'General' `head`, '2.2' `sub_num`, 'General Treatment Plan' `sub_head`, 'General Treatment Plan' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'treatment' `cat`, '2' `head_num`, 'General' `head`, '2.3' `sub_num`, 'Body Targeted Assessment' `sub_head`, 'Body Targeted Assessment' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'discharge' `cat`, '1' `head_num`, 'Spinal' `head`, '1.1' `sub_num`, 'Spinal Discharge Checklist' `sub_head`, 'Spinal Discharge Checklist' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'discharge' `cat`, '2' `head_num`, 'General' `head`, '1.1' `sub_num`, 'General Discharge' `sub_head`, 'General Discharge' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'massage' `tab`, 'treatment' `cat`, '1' `head_num`, 'Treatment Notes' `head`, '1.1' `sub_num`, 'Treatment Notes' `sub_head`, 'Treatment Notes' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'massage' `tab`, 'treatment' `cat`, '1' `head_num`, 'Treatment Notes' `head`, '1.2' `sub_num`, 'Massage Treatment Notes' `sub_head`, 'Massage Treatment Notes' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'mvc' `tab`, 'assess' `cat`, '1' `head_num`, 'Whiplash' `head`, '1.1' `sub_num`, 'Whiplash Associated Disorders' `sub_head`, 'Whiplash Associated Disorders' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0 UNION
	SELECT 'wcb' `tab`, 'forms' `cat`, '1' `head_num`, 'WCB' `head`, '1.1' `sub_num`, 'WCB Provider Employer Contact' `sub_head`, 'WCB Provider Employer Contact' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='') AS num WHERE num.rows = 0) AS `default_forms`");
mysqli_query($dbc, "INSERT INTO `patientform` (`tab`, `category`, `heading_number`, `heading`, `sub_heading_number`, `sub_heading`, `form`) SELECT * FROM
	(SELECT 'physiotherapy' `tab`, 'treatment' `cat`, '3' `head_num`, 'MVA Forms' `head`, '3.1' `sub_num`, 'AB-1 Initial Claim Form' `sub_head`, 'AB-1 Initial Claim Form' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='' AND `heading_number`='3' AND `heading`='MVA Forms') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'treatment' `cat`, '3' `head_num`, 'MVA Forms' `head`, '3.2' `sub_num`, 'AB-2 Treatment Plan' `sub_head`, 'AB-2 Treatment Plan' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='' AND `heading_number`='3' AND `heading`='MVA Forms') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'treatment' `cat`, '3' `head_num`, 'MVA Forms' `head`, '3.3' `sub_num`, 'AB-3 Progress Report' `sub_head`, 'AB-3 Progress Report' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='' AND `heading_number`='3' AND `heading`='MVA Forms') AS num WHERE num.rows = 0 UNION
	SELECT 'physiotherapy' `tab`, 'treatment' `cat`, '3' `head_num`, 'MVA Forms' `head`, '3.4' `sub_num`, 'AB-4 Concluding Report' `sub_head`, 'AB-4 Concluding Report' `form` FROM (SELECT COUNT(*) rows FROM `patientform` WHERE IFNULL(`tab`,'')!='' AND `heading_number`='3' AND `heading`='MVA Forms') AS num WHERE num.rows = 0) AS `default_forms`");

$tab_config = ',front_desk,physiotherapy,massage,mvc,wcb,reporting,';
if(empty($_GET['tab'])) {
	$current_tab = explode(',',trim($tab_config,','))[0];
} else {
	$current_tab = $_GET['tab'];
}

switch($current_tab) {
	case 'front_desk':
		$current_tab_name = 'Front Desk';
		break;
	case 'physiotherapy':
		$current_tab_name = 'Physiotherapy';
		break;
	case 'massage':
		$current_tab_name = 'Massage Therapy';
		break;
	case 'mvc':
		$current_tab_name = 'MVC/MVA';
		break;
	case 'wcb':
		$current_tab_name = 'WCB';
		break;
	case 'reporting':
		$current_tab_name = 'Reporting';
		break;
}
?>

<div class="container">
    <div class="row">

        <div class="notice popover-examples">
            <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11"><span class="notice-name">NOTE:</span>
            Treatment Charts stores the forms staff need to complete the treatment process.</div>
            <div class="clearfix"></div>
        </div>
        
        <div class="col-sm-10">
			<h1><?php echo $current_tab_name; ?> Dashboard</h1>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php if(config_visible_function($dbc, 'treatment') == 1) {
				echo '<a href="field_config_patientform.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			} ?>
        </div>
		<div class="clearfix double-gap-bottom"></div>

		<div class="tab-container mobile-100-container"><?php
            if (strpos($tab_config,',front_desk,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'front_desk') === TRUE) { ?>
                    <a href="?tab=front_desk"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'front_desk' ? 'active_tab' : ''); ?>">Front Desk</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">Front Desk</button><?php
                }
            }
            if (strpos($tab_config,',physiotherapy,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'physiotherapy') === TRUE) { ?>
                    <a href="?tab=physiotherapy"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'physiotherapy' ? 'active_tab' : ''); ?>">Physiotherapy</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">Physiotherapy</button><?php
                }
            }
            if (strpos($tab_config,',massage,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'massage') === TRUE) { ?>
                    <a href="?tab=massage"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'massage' ? 'active_tab' : ''); ?>">Massage Therapy</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">Massage Therapy</button><?php
                }
            }
            if (strpos($tab_config,',mvc,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'mvc') === TRUE) { ?>
                    <a href="?tab=mvc"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'mvc' ? 'active_tab' : ''); ?>">MVC/MVA</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">MVC/MVA</button><?php
                }
            }
            if (strpos($tab_config,',wcb,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'wcb') === TRUE) { ?>
                    <a href="?tab=wcb"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'wcb' ? 'active_tab' : ''); ?>">WCB</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">WCB</button><?php
                }
            }
            $uncategorized = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) FROM `patientform` WHERE IFNULL(`tab`,'') = ''"))[0];
			if($uncategorized > 0) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'uncategorized') === TRUE) { ?>
                    <a href="patientform.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Uncategorized Forms</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">Uncategorized Forms</button><?php
                }
            } 
			if (strpos($tab_config,',reporting,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'reporting') === TRUE) { ?>
                    <a href="?tab=reporting"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($current_tab == 'reporting' ? 'active_tab' : ''); ?>">Reporting</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">WCB</button><?php
                }
            }
			?>
		</div>
		<?php if($current_tab != 'reporting') { ?><a href="add_manual.php?type=patientform" class="btn brand-btn mobile-block pull-right">Add Patient Form</a><?php } ?>

		<div id="no-more-tables">
			<?php include($current_tab.'.php'); ?>
        </div><!-- test -->
    </div>
</div>

<?php include ('../footer.php'); ?>