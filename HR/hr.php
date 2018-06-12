<?php
/*
Dashboard
*/
error_reporting(0);
include ('../include.php');
checkAuthorised('hr');
include ('manual_checklist.php');
$hr_tabs = get_config($dbc, 'hr_tabs');
if(empty($hr_tabs)) {
    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('hr_tabs', 'Form,Manual,Onboarding,Orientation')");
}
$hr_tabs = get_config($dbc, 'hr_tabs');
?>

</head>
<body>

<?php include_once ('../navigation.php');

?>

<div class="container">
	<div class="row">
        <div class="col-md-12">

        <div class="col-md-10">
			<h1>HR Checklist - <?php echo decryptIt($_SESSION['first_name']); ?> <?php echo decryptIt($_SESSION['last_name']); ?></h1>
		</div>
		<div class="col-md-2 double-gap-top">
			<?php
				if(config_visible_function($dbc, 'hr') == 1) {
					echo '<a href="field_config_hr.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
				}
			?>
		</div>
		<div class="clearfix double-gap-bottom"></div>

		<?php
        $tab = $_GET['tab'];
        $active_toolbox = '';
        $active_taligate = '';
        $active_form = '';
        $active_manual = '';
        $active_onboarding = '';
        $active_orientation = '';
        if($tab == 'Toolbox') {
            $active_toolbox = 'active_tab';
        }
        if($tab == 'Tailgate') {
            $active_taligate = 'active_tab';
        }
        if($tab == 'Form') {
            $active_form = 'active_tab';
        }
        if($tab == 'Manual') {
            $active_manual = 'active_tab';
        }
		if($tab == 'p&p') {
            $active_pp = 'active_tab';
        }
        if($tab == 'Onboarding') {
            $active_onboarding = 'active_tab';
        }
        if($tab == 'Orientation') {
            $active_orientation = 'active_tab';
        }

        ?>

        <div class="gap-left tab-container mobile-100-container double-gap-bottom">
			<!--
			<a href='hr.php?tab=Toolbox'><button type="button" class="btn brand-btn mobile-block <?php echo $active_toolbox; ?>">Toolbox</button></a>
			<a href='hr.php?tab=Tailgate'><button type="button" class="btn brand-btn mobile-block <?php echo $active_taligate; ?>">Tailgate</button></a>-->
            <?php foreach (explode(',', $hr_tabs) as $hr_tab) { ?>
            <div class="pull-left tab">
                <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all Forms and Manuals for the <?= $hr_tab ?> tab."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php if ( check_subtab_persmission($dbc, 'hr', ROLE, '$hr_tab') === TRUE ) { ?>
                    <a href='hr.php?tab=<?= $hr_tab ?>'><button type="button" class="btn brand-btn mobile-block mobile-100 <?= ($tab == $hr_tab ? 'active_tab' : '') ?>"><?= $hr_tab ?></button></a>
                <?php } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100"><?= $hr_tab ?></button>
                <?php } ?>
            </div>
            <?php } ?>
			<!-- <div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all Forms you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'forms') === TRUE ) { ?>
					<a href='hr.php?tab=Form'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_form; ?>">Forms</button></a>
				<?php } else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100">Forms</button>
				<?php } ?>
			</div>
            <div class="pull-left tab">
                <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all the Manuals you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'manuals') === TRUE ) { ?>
                    <a href='hr.php?tab=Manual'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_manual; ?>">Manuals</button></a>
                <?php } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $active_manual; ?>">Manuals</button>
                <?php } ?>
            </div>
            <div class="pull-left tab">
                <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all the Onboarding Forms and Templates you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'onboarding') === TRUE ) { ?>
                    <a href='hr.php?tab=Onboarding'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_onboarding; ?>">Onboarding</button></a>
                <?php } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $active_onboarding; ?>">Onboarding</button>
                <?php } ?>
            </div>
            <div class="pull-left tab">
                <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all the Orientation Forms and Templates you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'orientation') === TRUE ) { ?>
                    <a href='hr.php?tab=Orientation'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_orientation; ?>">Orientation</button></a>
                <?php } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $active_orientation; ?>">Orientation</button>
                <?php } ?>
            </div> -->
			<!-- <button type="button" class="btn brand-btn mobile-block active_tab" >Dashboard</button>
			<a href='manual_follow_up.php?type=hr'><button type="button" class="btn brand-btn mobile-block">Follow Up</button></a>-->
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to search through the HR Reports."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'reporting') === TRUE ) { ?>
					<a href='manual_reporting.php?type=hr'><button type="button" class="btn brand-btn mobile-block mobile-100">Reporting</button></a>
				<?php } else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100">Reporting</button>
				<?php } ?>
			</div>
			<!-- <div class="pull-left tab">
				<a href='policy_procedures.php?category=Policies and Procedures&source=hr'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_manual; ?>">Policies & Procedures</button></a>
			</div> -->
			<div class="pull-right tab">
                <?php if ($_GET['type'] == 'manuals') { ?>
                    <a href="add_hr_manual.php?tab=<?= $_GET['tab'] ?>&category=<?= $_GET['category'] ?>" class="btn brand-btn mobile-block pull-right">Add Manual</a>
                    <span class="popover-examples pull-right" style="margin:7px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add manuals into your HR tile."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php } else { ?>
    				<a href="add_manual.php?type=hr" class="btn brand-btn mobile-block pull-right">Add HR</a>
    				<span class="popover-examples pull-right" style="margin:7px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add forms into your HR tile."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <?php } ?>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="mobile-100-container">
			<?php
            if ( $tab==='Manual' ) {
                $active_pnp = '';
                if ($_GET['category'] == 'Policies and Procedures') {
                    $active_pnp = 'active_tab';
                }
            ?>
                <a href="hr.php?tab=Manual&category=Policies and Procedures"><button type="button" style="margin-right:3px;" class="btn brand-btn mobile-block mobile-100 <?php echo $active_pnp; ?>">Policies &amp; Procedures</button></a>
            <?php }
			$tabs = mysqli_query($dbc, "SELECT distinct(category) FROM hr WHERE deleted=0 AND tab='$tab'");
			while($row_tab = mysqli_fetch_array($tabs)) {
				$class='';
				$category = $row_tab['category'];
				if(!empty($_GET['category'])) {
					if($category == $_GET['category']) {
						$class= 'active_tab';
					}
				}
				echo '<a href="hr.php?tab='.$tab.'&category='.$category.'"><button type="button" style="margin-right:3px;" class="btn brand-btn mobile-block mobile-100 '.$class.'" >'.$category.'</button></a>';
			}
            $tabs_manuals = mysqli_query($dbc, "SELECT * FROM `field_config_hr_manuals` WHERE `tab` = '$tab'");
            while($row_tab = mysqli_fetch_array($tabs_manuals)) {
                $class='';
                $category = $row_tab['category'];
                if(!empty($_GET['category'])) {
                    if($category == $_GET['category']) {
                        $class= 'active_tab';
                    }
                }
                echo '<a href="hr.php?tab='.$tab.'&category='.$category.'&type=manuals"><button type="button" style="margin-right:3px;" class="btn brand-btn mobile-block mobile-100 '.$class.'">'.$category.'</button></a>';
            }
			?>
		</div><?php

		if ( $tab==='Form' ) { ?>
			<div class="notice double-gap-bottom double-gap-top popover-examples">
				<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
				<div class="col-sm-11"><span class="notice-name">NOTE:</span>
          <?php
            $notes = mysqli_fetch_assoc(mysqli_query($dbc, "select note from notes_setting where subtab = 'hr_form'"));
            $note = $notes['note'];
          ?>
          <?php echo $note; ?></div>
          <div class="clearfix"></div>
			</div><?php
		}

		if ( $tab==='Manual' ) { ?>
			<div class="notice double-gap-bottom double-gap-top popover-examples">
				<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
				<div class="col-sm-11"><span class="notice-name">NOTE:</span>
          <?php
            $notes = mysqli_fetch_assoc(mysqli_query($dbc, "select note from notes_setting where subtab = 'hr_manual'"));
            $note = $notes['note'];
          ?>
          <?php echo $note; ?></div>				<div class="clearfix"></div>
			</div><?php
		}

		if ( $tab==='Onboarding' ) { ?>
			<div class="notice double-gap-bottom double-gap-top popover-examples">
				<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
				<div class="col-sm-11"><span class="notice-name">NOTE:</span>
          <?php
            $notes = mysqli_fetch_assoc(mysqli_query($dbc, "select note from notes_setting where subtab = 'hr_on_boarding'"));
            $note = $notes['note'];
          ?>
          <?php echo $note; ?></div>				<div class="clearfix"></div>
			</div><?php
		}

		if ( $tab==='Orientation' ) { ?>
			<div class="notice double-gap-bottom double-gap-top popover-examples">
				<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
				<div class="col-sm-11"><span class="notice-name">NOTE:</span>
          <?php
            $notes = mysqli_fetch_assoc(mysqli_query($dbc, "select note from notes_setting where subtab = 'hr_orientation'"));
            $note = $notes['note'];
          ?>
          <?php echo $note; ?></div>				<div class="clearfix"></div>
			</div><?php
		}

		if ( $tab==='pnp' ) { ?>
			<div class="notice double-gap-bottom double-gap-top popover-examples">
				<?php include('policy_procedures.php'); ?>
			</div><?php
		} ?>

        <div class="form-group triple-pad-top triple-pad-bottom clearfix location">
            <label for="site_name" class="col-sm-4 control-label text-right"></label>
            <div class="col-sm-12">
                <?php
                if($tab == 'Manual' && $_GET['category'] == 'Policies and Procedures') {
                    include('manual_checklist_pp.php'); ?>

                <div class="tab-container mobile-100-container">
                    <?php
                    $tabs = mysqli_query($dbc, "SELECT distinct(category) FROM manuals WHERE deleted=0 AND manual_type='policy_procedures'");
                    while($row_tab = mysqli_fetch_array( $tabs )) {
                        $class='';
                        $category = $row_tab['category'];
                        if($category == $_GET['category_pnp']) {
                            $class= 'active_tab';
                        }

                        if(isset($_GET['from_manual'])) {
                            echo '<a href="manual.php?category='.$category.'"><button type="button" class="btn brand-btn mobile-block mobile-100 '.$class.'" style="margin-right:3px;" >'.$category.'</button></a>';
                        }
                        else {
                            echo '<a href="hr.php?tab=Manual&category=Policies and Procedures&category_pnp='.$category.'"><button type="button" class="btn brand-btn mobile-block mobile-100 '.$class.'" style="margin-right:3px;" >'.$category.'</button></a>';
                        }
                    }
                    ?>
                </div>
                <?php
                    echo manual_checklist_pp($dbc, '35', '20', '20', 'policy_procedures', $_GET['category_pnp']);
                } else if(!empty($_GET['category'])) {
                    if ($_GET['type'] == 'manuals') {
                        include('manual_checklist_pp.php');
                        echo manual_checklist_m($dbc, '35', '20', '20', $_GET['tab'], $_GET['category']);
                    } else {
                        echo manual_checklist($dbc, '35', '20', '20', $_GET['tab'], $_GET['category']);
                    }
                }
                ?>
            </div>
        </div>

        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>
