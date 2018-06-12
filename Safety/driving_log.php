<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('safety');
error_reporting(0);


	$contactide = $_SESSION['contactid'];
	$get_table_orient = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM contacts WHERE contactid='$contactide'"));
	$accordion = $get_table_orient['safety_manual_view'];

	if($accordion == 'on') {
		include ('manual_checklist_accordion.php');
	} else {
		include ('manual_checklist.php');
	}

if(!empty($_GET['type'])) {
if($_GET['type'] == 'delete') {
    $uploadid = $_GET['uploadid'];

    $doc = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM safety_upload WHERE uploadid = '$uploadid'"));

    $type = $doc['type'];
    $upload = $doc['upload'];
    $safetyid = $doc['safetyid'];

    unlink('download/'.$upload);

    $result_delete_doc = mysqli_query($dbc, "DELETE FROM `safety_upload` WHERE `uploadid` = '$uploadid'");
    header('Location: add_manual.php?safetyid='.$safetyid.'&action=view&formid=0');
}
}
	if($accordion == 'on') {
?>
<script>
$(document).ready(function() {
	$('.accordion-show').on('click', function() {
		$('.hider').hide();
		$(this).next().toggle();
	});
		$('h4.tbl-orient').hide();
	$('h3.tbl-orient').on('click', function() {
		$('table.tbl-orient').hide();
		$('h4.tbl-orient').removeClass('showhideh4');
		$('h4.tbl-orient').hide();
		$(this).nextUntil( 'h3', 'h4' ).show();
		if($(this).next().is(":hidden")) {
			$(this).next().show();
			$(this).next().next().show();
		} else {
			$(this).next().toggle();
			$(this).next().next().toggle();
		}

		if($(this).hasClass('showhideh3')) {
			$(this).next().hide();
			$(this).next().next().hide();
			$(this).removeClass('showhideh3');
			$('h4.tbl-orient').removeClass('showhideh4');
			$(this).nextUntil( 'h3', 'h4' ).hide();
		} else {
			$('h3.tbl-orient').removeClass('showhideh3');
			$(this).addClass('showhideh3');
		}
	});

	$('h4.tbl-orient').on('click', function() {
		$('.subheading').hide();
		$(this).next().toggle();
		if($(this).hasClass('showhideh4')) {
			$(this).next().hide();
			$(this).removeClass('showhideh4');
		} else {
			$('h4.tbl-orient').removeClass('showhideh4');
			$(this).addClass('showhideh4');
		}
	});


	$('.show-thirdacc').on('click', function() {
		$('.hide_third_acc').hide();
	});
});
</script>
<?php
	}
?>
	<style>
	<?php  if($accordion == 'on') {
	?>
	.mobile-100 {
		width:80%;
		margin:auto;
		margin-bottom:5px;
	}
	.mobile-100-container {
		text-align:center;
		margin-top:5px;
		width:100%;
	}
	table.tbl-orient {
		display:none;
	}
	.hideshower {
		display:none;
	}
	.dropdowndiv .mobile-100 {
		width:95%;
	}
	@media(min-width:768px) {
		.dropdowndiv {
			position:relative;
			left:-3px;
		}
		.td_container {
			background-color:lightgrey;
			min-height:50px;
			display:none;
			border:1px solid black;
			padding:0;
			margin:0;
		}
		.td_divs {
			background-color:lightgrey;

			left:0px;
			top:0px;
			position:relative;
			width:24%;
			display:inline-block;
			vertical-align:top;
			height:100%;

			word-break: break;
			border:2px solid black;
			border:1px solid black;

		}
	}
	@media (max-width:768px) {
		.hideonmobile {
			display:none;
		}
		.td_container {
			display:block;
		}
	}


		<?php
}
?>

h3.tbl-orient {
	cursor:pointer;
	min-height:40px;
	height:auto !important;
	text-align:left;
	}
	h4.tbl-orient {
	cursor:pointer;
	min-height:40px;
	height:auto !important;
	text-align:left;
	}
	@media(max-width:991px) {
		.tbl-orient td {
			padding:0px;
		}
	}
	@media(max-width:510px) {
		.tbl-orient td {
			padding: 0px;
			display: block;
			width: 270px;
			height:auto;
		}
		h3.tbl-orient {
			height:auto !important;
			width:270px;
		}
		h4.tbl-orient {
			height:auto !important;
			width:270px;
		}
		.tbl-orient tr {
			padding: 0px;
			display: table-row;
			width: 270px;
			height:auto;
			/* max-width: 200px; */
			/* width: 100px; */
			border-bottom:3px solid black;
		}
	}
	</style>

<script>
function handleClick(sel) {

    var stagee = sel.value;
	var contactide = $('.contacterid').val();

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "manual_ajax_all.php?fill=accordionview&contactid="+contactide+"&value="+stagee,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});

}
</script>
</head>
<body>

<?php include_once ('../navigation.php');

?>

<div class="container">
	<div class="row">
        <div class="col-md-12">

        <div class="col-sm-10">
			<h2>Safety Checklist - <?php echo decryptIt($_SESSION['first_name']); ?> <?php echo decryptIt($_SESSION['last_name']); ?></h2>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php
				if(config_visible_function($dbc, 'safety') == 1) {
					echo '<a href="field_config_safety.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
				}
			?>
        </div>

		<div class="clearfix double-gap-bottom"></div>

		<input type='hidden' value='<?php echo $contactide; ?>' class='contacterid' />
		<span style='padding:5px; font-weight:bold;'>Admin View: </span><input onclick="handleClick(this);" type='radio' style='width:20px; height:20px;' <?php if($accordion !== 'on') { echo 'checked'; } ?> name='horizo_vert' class='horizo_vert' value=''>
	<span style='padding:5px; font-weight:bold;'>Mobile View: </span><input onclick="handleClick(this);" <?php if($accordion == 'on') { echo 'checked'; } ?> type='radio' style='width:20px; height:20px;' name='horizo_vert' class='horizo_vert' value='on'>

		<div class='mobile-100-container' style='margin-top:10px;'>
			<a href='manual_reporting.php?type=safety'><button type="button" class="mobile-100-pull-right btn brand-btn mobile-block pull-right" >Reporting</button></a>
			<span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see all of your safety reports."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		</div><br><br><?php
		$tab = '';
		if(isset($_GET['tab'])) {
			$tab = $_GET['tab'];
		}
        $site_get = '';
        if(!empty($_GET['site'])) {
            $site_get = $_GET['site'];
        }
        $category_get = '';
        if(!empty($_GET['category'])) {
            $category_get = $_GET['category'];
        }
        
		$value_config = explode(',',get_config($dbc, 'safety_dashboard'));
		$bypass_config = get_config($dbc, 'safety_bypass_list');
		foreach($value_config as $i => $value_field) {
			if($value_field == 'Driving Log' && !check_subtab_persmission($dbc,'safety',ROLE,'driving_log')) {
				unset($value_config[$i]);
			} else if($value_field == 'FLHA' && !check_subtab_persmission($dbc,'safety',ROLE,'flha')) {
				unset($value_config[$i]);
			} else if($value_field == 'Toolbox' && !check_subtab_persmission($dbc,'safety',ROLE,'toolbox')) {
				unset($value_config[$i]);
			} else if($value_field == 'Tailgate' && !check_subtab_persmission($dbc,'safety',ROLE,'tailgate')) {
				unset($value_config[$i]);
			} else if($value_field == 'Form' && !check_subtab_persmission($dbc,'safety',ROLE,'forms')) {
				unset($value_config[$i]);
			} else if($value_field == 'Manual' && !check_subtab_persmission($dbc,'safety',ROLE,'manual')) {
				unset($value_config[$i]);
			} else if($value_field == 'Incident Reports' && !check_subtab_persmission($dbc,'safety',ROLE,'incidents')) {
				unset($value_config[$i]);
			}
		}
		$value_config = ','.implode(',',$value_config).',';

		// ACCORDION STYLING CODE //


		$fourbuttons = '';
	if($accordion == 'on') {
		$checklists = '';
		$checklist_show = '';
		if(!empty($_GET['category'])) {
                    $checklists = $fourthaccordion;
        }
		$checklists = '<div style="overflow:auto;background-color:grey; border:1px solid black;margin-left:5px;margin-right:5px;padding:5px; width:95%; margin: auto; margin-bottom:10px; position:relative; left:-3px; border-radius: 0px 0px 5px 5px; top:-5px;">'.$checklists.'</div>';

        $tabs = mysqli_query($dbc, "SELECT distinct(category) FROM safety WHERE deleted=0 AND tab='$tab'");
        //$tabs = mysqli_query($dbc, "SELECT distinct(category) FROM safety WHERE deleted=0");

		$thirdaccordion = '<div style=\'background-color:#C6B4B4; border:1px solid black;margin-left:5px;margin-right:5px;padding:5px; width:95%; margin:auto; margin-bottom:10px; border-radius: 0px 0px 5px 5px; top:-5px; position:relative;"\' class=\'hide_third_acc\'>';

		if(mysqli_num_rows($tabs) < 1) {
				$thirdaccordion .= '<p style=\'font-size:15px\'>There are currently no Safety Items to display for this drop-down.</p>';
		}
        while($row_tab = mysqli_fetch_array($tabs)) {
            $class='';
            $category = $row_tab['category'];
            if(!empty($_GET['category'])) {
                if($category == $_GET['category']) {
                    $class= 'active_tab';
					$checklist_show = $checklists;
                } else {
					$checklist_show = '';
				}
            }
			if($category !== '') {
				$thirdaccordion .= '<a href="safety.php?site='.$site_get.'&tab='.$tab.'&category='.$category.'"><button type="button" class="mobile-100 btn brand-btn mobile-block  '.$class.'" style="margin-right:9px; width:95%;" >'.$category.'</button></a>'.$checklist_show;
			}
        }
		$thirdaccordion .= '</div>';

        $active_drivinglog = '';
        $active_flha = '';
		$active_toolbox = '';
        $active_taligate = '';
        $active_form = '';
        $active_manual = '';
        $active_incident_reports = '';
		$dl_third = '';
		$flha_third = '';
		$tb_third = '';
		$tg_third = '';
		$f_third = '';
		$m_third = '';
		$ir_third = '';
        
        if($tab == 'Driving Log') {
            $active_drivinglog = 'active_tab';
            $dl_third = $thirdaccordion;
        }
        if($tab == 'FLHA') {
            $active_flha = 'active_tab';
            $flha_third = $thirdaccordion;
        }
        if($tab == 'Toolbox') {
            $active_toolbox = 'active_tab';
			$tb_third = $thirdaccordion;
        }
        if($tab == 'Tailgate') {
            $active_taligate = 'active_tab';
			$tg_third = $thirdaccordion;
        }
        if($tab == 'Form') {
            $active_form = 'active_tab';
			$f_third = $thirdaccordion;
        }
        if($tab == 'Manual') {
            $active_manual = 'active_tab';
			$m_third = $thirdaccordion;
        }
        if($tab == 'Incident Reports') {
            $active_incident_reports = 'active_tab';
			$ir_third = $thirdaccordion;
        }

        $fourbuttons = "<div style='background:#8C92AB;padding:5px;border:1px solid black; border-radius:0px 0px 5px 5px; width:80%; margin:auto; margin-bottom:10px;position:relative; top:-5px;' class='dropdowndiv'>";
            if ( strpos($value_config, ',Driving Log,') !== false ) {
                $fourbuttons .= "<a href='driving_log.php?site=$site_get&category=$category_get' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block ".$active_drivinglog."\">Driving Log</button></a>".$dl_third;
            }
			$top_form = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `safetyid` FROM `safety` where tab='FLHA' ORDER BY `heading_number`, `sub_heading_number`, `third_heading_number`"))['safetyid'];
            if ( strpos($value_config, ',FLHA,') !== false && strpos($bypass_config, ',FLHA,') !== false && $top_form > 0) {
				$fourbuttons .= "<a href='add_manual.php?safetyid=".$top_form."&action=view' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block\">FLHA</button></a>".$flha_third;
            } else if ( strpos($value_config, ',FLHA,') !== false ) {
                $fourbuttons .= "<a href='safety.php?site=$site_get&tab=FLHA&category=$category_get' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block ".$active_flha."\">FLHA</button></a>".$flha_third;
            }
			$top_form = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `safetyid` FROM `safety` where tab='Toolbox' ORDER BY `heading_number`, `sub_heading_number`, `third_heading_number`"))['safetyid'];
            if ( strpos($value_config, ',Toolbox,') !== false && strpos($bypass_config, ',Toolbox,') !== false && $top_form > 0) {
				$fourbuttons .= "<a href='add_manual.php?safetyid=".$top_form."&action=view' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block\">Toolbox</button></a>".$tb_third;
            } else if ( strpos($value_config, ',Toolbox,') !== false ) {
                $fourbuttons .= "<a href='safety.php?site=$site_get&tab=Toolbox&category=$category_get' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block ".$active_toolbox."\">Toolbox</button></a>".$tb_third;
            }
			$top_form = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `safetyid` FROM `safety` where tab='Tailgate' ORDER BY `heading_number`, `sub_heading_number`, `third_heading_number`"))['safetyid'];
            if ( strpos($value_config, ',Tailgate,') !== false && strpos($bypass_config, ',Tailgate,') !== false && $top_form > 0) {
				$fourbuttons .= "<a href='add_manual.php?safetyid=".$top_form."&action=view' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block\">Tailgate</button></a>".$tg_third;
            } else if ( strpos($value_config, ',Tailgate,') !== false ) {
                $fourbuttons .= "<a href='safety.php?site=$site_get&tab=Tailgate&category=$category_get' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block ".$active_taligate."\">Tailgate</button></a>".$tg_third;
            }
			$top_form = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `safetyid` FROM `safety` where tab='Form' ORDER BY `heading_number`, `sub_heading_number`, `third_heading_number`"))['safetyid'];
            if ( strpos($value_config, ',Forms,') !== false && strpos($bypass_config, ',Forms,') !== false && $top_form > 0) {
				$fourbuttons .= "<a href='add_manual.php?safetyid=".$top_form."&action=view' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block\">Forms</button></a>".$f_third;
            } else if ( strpos($value_config, ',Forms,') !== false ) {
                $fourbuttons .= "<a href='safety.php?site=$site_get&tab=Form&category=$category_get' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block ".$active_form."\">Forms</button></a>".$f_third;
            }
            if ( strpos($value_config, ',Manuals,') !== false ) {
                $fourbuttons .= "<a href='safety.php?site=$site_get&tab=Manual&category=$category_get' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block ".$active_manual."\">Manuals</button></a>".$m_third;
            }
            if ( strpos($value_config, ',Incident Reports,') !== false ) {
                $fourbuttons .= "<a href='incident_report.php?site=".$site_get."&tab=Incident Reports' class='show-thirdacc'><button type=\"button\" class=\"mobile-100 btn brand-btn mobile-block ".$active_incident_reports."\">Incident Reports</button></a>".$ir_third;
            }
		$fourbuttons .= "</div>";
}

		// END ACCORDION STYLE CODE

        $active_site = '';
        $safety_main = get_config($dbc, 'safety_main_site_tabs');
        $each_safety_main = explode(',', $safety_main);
		echo "<div class='mobile-100-container'>";
        foreach ($each_safety_main as $cat_safety_main) {
            $class_site='';
			$show_accordion = '';
            $site = $cat_safety_main;
            if(!empty($_GET['site'])) {
                if($site == $_GET['site']) {
                    $class_site= 'active_tab';
					if($accordion == 'on') {
						$show_accordion = 'display:block;';
					}
                }
            }
			if($site !== '') {
                if ( check_subtab_persmission($dbc, 'safety', ROLE, strtolower(str_replace(' ', '_', $site))) === TRUE ) {
                    echo '<a class="accordion-show" href="safety.php?site='.$site.'&tab='.$tab.'&category='.$category_get.'"><button type="button" class="mobile-100 btn brand-btn mobile-block '.$class_site.'" style="margin-right:9px;">'.$site.'</button></a>
                    <span class="hider" style="display:none;'.$show_accordion.'">'.$fourbuttons.'</span>';
                } else {
                    echo '<button type="button" class="mobile-100 btn disabled-btn mobile-block" style="margin-right:9px;">'.$site.'</button>';
                }
			} else {
				echo $fourbuttons;
			}
		}
		echo '</div>';

        $active_drivinglog = '';
        $active_flha = '';
        $active_toolbox = '';
        $active_taligate = '';
        $active_form = '';
        $active_manual = '';
        $active_incident_reports = '';
        
        if($tab == 'Driving Log') {
            $active_drivinglog = 'active_tab';
        }
        if($tab == 'FLHA') {
            $active_flha = 'active_tab';
        }
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
        if($tab == 'Incident Reports') {
            $active_incident_reports = 'active_tab';
        }

        ?>
        <?php if($accordion !== 'on') {
		?><br>
		<div class='mobile-100-container'><?php
            if ( strpos($value_config, ',Driving Log,') !== false ) { ?>
                <a href="driving_log.php?site=<?= $site_get; ?>&tab=Driving Log"><button type="button" class="mobile-100 btn brand-btn mobile-block <?= $active_drivinglog; ?>">Driving Log</button></a><?php
            }
			$top_form = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `safetyid` FROM `safety` where tab='FLHA' ORDER BY `heading_number`, `sub_heading_number`, `third_heading_number`"))['safetyid'];
            if ( strpos($value_config, ',FLHA,') !== false && strpos($bypass_config, ',FLHA,') !== false && $top_form > 0) {
				echo '<a href="add_manual.php?action=view&safetyid='.$top_form.'"><button type="button" class="mobile-100 btn brand-btn mobile-block">FLHA</button></a>';
            } else if ( strpos($value_config, ',FLHA,') !== false ) { ?>
                <a href='safety.php?site=<?php echo $site_get; ?>&tab=FLHA&category=<?php echo $active_flha == '' ? '' : $category_get; ?>'><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo $active_flha; ?>">FLHA</button></a><?php
            }
			$top_form = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `safetyid` FROM `safety` where tab='Toolbox' ORDER BY `heading_number`, `sub_heading_number`, `third_heading_number`"))['safetyid'];
            if ( strpos($value_config, ',Toolbox,') !== false && strpos($bypass_config, ',Toolbox,') !== false && $top_form > 0) {
				echo '<a href="add_manual.php?action=view&safetyid='.$top_form.'"><button type="button" class="mobile-100 btn brand-btn mobile-block">Toolbox</button></a>';
            } else if ( strpos($value_config, ',Toolbox,') !== false ) { ?>
                <a href='safety.php?site=<?php echo $site_get; ?>&tab=Toolbox&category=<?php echo $active_toolbox == '' ? '' : $category_get; ?>'><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo $active_toolbox; ?>">Toolbox</button></a><?php
            }
			$top_form = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `safetyid` FROM `safety` where tab='Tailgate' ORDER BY `heading_number`, `sub_heading_number`, `third_heading_number`"))['safetyid'];
            if ( strpos($value_config, ',Tailgate,') !== false && strpos($bypass_config, ',Tailgate,') !== false && $top_form > 0) {
				echo '<a href="add_manual.php?action=view&safetyid='.$top_form.'"><button type="button" class="mobile-100 btn brand-btn mobile-block">Tailgate</button></a>';
            } else if ( strpos($value_config, ',Tailgate,') !== false ) { ?>
                <a href='safety.php?site=<?php echo $site_get; ?>&tab=Tailgate&category=<?php echo $active_taligate == '' ? '' : $category_get; ?>'><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo $active_taligate; ?>">Tailgate</button></a><?php
            }
			$top_form = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `safetyid` FROM `safety` where tab='Form' ORDER BY `heading_number`, `sub_heading_number`, `third_heading_number`"))['safetyid'];
            if ( strpos($value_config, ',Forms,') !== false && strpos($bypass_config, ',Forms,') !== false && $top_form > 0) {
				echo '<a href="add_manual.php?action=view&safetyid='.$top_form.'"><button type="button" class="mobile-100 btn brand-btn mobile-block">Forms</button></a>';
            } else if ( strpos($value_config, ',Forms,') !== false ) { ?>
                <a href='safety.php?site=<?php echo $site_get; ?>&tab=Form&category=<?php echo $active_form == '' ? '' : $category_get; ?>'><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo $active_form; ?>">Forms</button></a><?php
            }
            if ( strpos($value_config, ',Manuals,') !== false ) { ?>
                <a href='safety.php?site=<?php echo $site_get; ?>&tab=Manual&category=<?php echo $active_manual == '' ? '' : $category_get; ?>'><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo $active_manual; ?>">Manuals</button></a><?php
            }
            if ( strpos($value_config, ',Incident Reports,') !== false ) { ?>
                <a href='incident_report.php?site=<?= $site_get; ?>&tab=Incident Reports'><button type="button" class="mobile-100 btn brand-btn mobile-block <?php echo $active_incident_reports; ?>">Incident Reports</button></a><?php
            } ?>
		</div>
        <br>
		<div class='mobile-100-container'>
        <?php
        $tabs = mysqli_query($dbc, "SELECT distinct(category) FROM safety WHERE deleted=0 AND tab='$tab'");
        //$tabs = mysqli_query($dbc, "SELECT distinct(category) FROM safety WHERE deleted=0");
        while($row_tab = mysqli_fetch_array($tabs)) {
            $class='';
            $category = $row_tab['category'];
            if(!empty($_GET['category'])) {
                if($category == $_GET['category']) {
                    $class= 'active_tab';
                }
            }
		if($category !== '') {
            echo '<a href="safety.php?site='.$site_get.'&tab='.$tab.'&category='.$category.'"><button type="button" class="mobile-100 btn brand-btn mobile-block  '.$class.'" style="margin-right:9px;" >'.$category.'</button></a>';
        }
		}
        ?>
		</div>
		<?php } ?>


        <!-- <button type="button" class="btn brand-btn mobile-block active_tab" >Dashboard</button> -->
        <!-- <a href='manual_follow_up.php?type=safety'><button type="button" class="btn brand-btn mobile-block" >Follow Up</button></a> -->

        <?php
            //echo '<a href="add_manual.php?type=safety" class="btn brand-btn mobile-block pull-right">Add a Safety Item</a>';
        ?><br>

		<?php //if($accordion !== 'on') { ?>
        <div class="form-group triple-pad-bottom clearfix location">
            <label for="site_name" class="col-sm-4 control-label text-right"></label>
            <div class="col-sm-12">
                <div id="main_tabs">
                    <?php $logs = mysqli_query($dbc, "SELECT `log_id` FROM `site_work_driving_log` WHERE `staff`='".$_SESSION['contactid']."' AND IFNULL(`end_drive_time`,'')=''"); ?>
                    <?php if(mysqli_num_rows($logs) == 0) { ?>
                        <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12 lead_btn">
                            <a href="add_driving_log.php">Driving Log</a>
                        </div>
                    <?php } else { 
                        $log_id = mysqli_fetch_array($logs)['log_id']; ?>
                        <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12 lead_btn">
                            <a href="add_driving_log.php?log_id=<?= $log_id ?>">End Driving Log</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
		<?php //} ?>

        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>