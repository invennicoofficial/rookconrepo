<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('treatment_charts');
include ('manual_checklist.php');
?>

</head>
<body>

<?php include_once ('../navigation.php');
$tab_config = ',front_desk,physiotherapy,massage,mvc,wcb,reporting,';
?>

<div class="container">
	<div class="row">
        <div class="col-md-12">

        <h2> Patient Forms
        <?php
            echo '<a href="field_config_patientform.php" class=" mobile-block pull-right"><img style="width:50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        ?>
        <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT treatment FROM config_treatment"));
        $value_config = ','.$get_field_config['treatment'].',';
        ?>

		<div class="mobile-100-container"><?php
            if (strpos($tab_config,',front_desk,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'front_desk') === TRUE) { ?>
                    <a href="index.php?tab=front_desk"><button type="button" class="btn brand-btn mobile-block mobile-100">Front Desk</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">Front Desk</button><?php
                }
            }
            if (strpos($tab_config,',physiotherapy,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'physiotherapy') === TRUE) { ?>
                    <a href="index.php?tab=physiotherapy"><button type="button" class="btn brand-btn mobile-block mobile-100">Physiotherapy</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">Physiotherapy</button><?php
                }
            }
            if (strpos($tab_config,',massage,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'massage') === TRUE) { ?>
                    <a href="index.php?tab=massage"><button type="button" class="btn brand-btn mobile-block mobile-100">Massage Therapy</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">Massage Therapy</button><?php
                }
            }
            if (strpos($tab_config,',mvc,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'mvc') === TRUE) { ?>
                    <a href="index.php?tab=mvc"><button type="button" class="btn brand-btn mobile-block mobile-100">MVC/MVA</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">MVC/MVA</button><?php
                }
            }
            if (strpos($tab_config,',wcb,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'wcb') === TRUE) { ?>
                    <a href="index.php?tab=wcb"><button type="button" class="btn brand-btn mobile-block mobile-100">WCB</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">WCB</button><?php
                }
            }
            $uncategorized = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) FROM `patientform` WHERE IFNULL(`tab`,'') = ''"))[0];
			if($uncategorized > 0) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'uncategorized') === TRUE) { ?>
                    <a href="patientform.php"><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Uncategorized Forms</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">Uncategorized Forms</button><?php
                }
            }
			if (strpos($tab_config,',reporting,') !== FALSE) {
                if (check_subtab_persmission($dbc, 'treatment_charts', ROLE, 'reporting') === TRUE) { ?>
                    <a href="index.php?tab=reporting"><button type="button" class="btn brand-btn mobile-block mobile-100">Reporting</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100">Reporting</button><?php
                }
            }
			?>
		</div>

		<br clear="all" />

        <?php
            echo '<a href="add_manual.php?type=patientform" class="btn brand-btn mobile-block pull-right">Add Patient Form</a>';
        ?>
        <br><br>

        <?php
        $tabs = mysqli_query($dbc, "SELECT distinct(category) FROM patientform WHERE IFNULL(`tab`,'')='' AND deleted=0");
        while($row_tab = mysqli_fetch_array($tabs)) {
            $class='';
            $category = $row_tab['category'];
            if(!empty($_GET['category'])) {
                if($category == $_GET['category']) {
                    $class= 'active_tab';
                }
            }
            echo '<a href="patientform.php?category='.$category.'"><button type="button" class="btn brand-btn mobile-block '.$class.'" >'.$category.'</button></a>&nbsp;&nbsp;';
        }
        ?>
        </h2>

        <div class="form-group triple-pad-top triple-pad-bottom clearfix location">
            <label for="site_name" class="col-sm-4 control-label text-right"></label>
            <div class="col-sm-12">
                <?php
                if(!empty($_GET['category'])) {
                    echo manual_checklist($dbc, '35', '20', '20', $_GET['category']);
                }
                ?>
            </div>
        </div>

        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>