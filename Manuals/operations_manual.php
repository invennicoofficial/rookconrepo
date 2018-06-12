<?php
/*
Dashboard
*/
if(!isset($_GET['from_manual']))
include ('../include.php');
checkAuthorised('ops_manual');
include ('manual_checklist.php');
?>

</head>
<body>

<?php include_once ('../navigation.php');

?>

<div class="container">
	<div class="row">
        <div class="col-md-12">

        <div class="col-sm-10">
        <?php if(!isset($_GET['maintype'])) { ?>
			<h2>Operations Manual Checklist - <?php echo decryptIt($_SESSION['first_name']); ?> <?php echo decryptIt($_SESSION['last_name']); ?></h2>
        <?php } ?>
		</div>

		<div class="col-sm-2">
			<?php
				if(config_visible_function($dbc, 'ops_manual') == 1) {
					if(isset($_GET['from_manual'])) {
						echo '<a href="field_config_operations_manual.php?maintype=pp" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					}
					else {
						echo '<a href="field_config_operations_manual.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					}
					echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
				}
			?>
        </div>
		<div class="clearfix double-gap-bottom"></div>

		<div class="tab-container mobile-100-container">
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See all manuals."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<?php if ( check_subtab_persmission($dbc, 'ops_manual', ROLE, 'dashboard') === TRUE ) { ?>
					<button type="button" class="btn brand-btn mobile-block active_tab mobile-100">Manuals</button>
				<?php } else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100">Manuals</button>
				<?php } ?>
			</div>

			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See manuals that require your attention."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<?php if ( check_subtab_persmission($dbc, 'ops_manual', ROLE, 'followup') === TRUE ) { ?>
					<?php if(isset($_GET['from_manual'])): ?>
						<a href="manual_follow_up.php?type=operations_manual&from_manual=1&maintype=om"><button type="button" class="btn brand-btn mobile-block mobile-100">Follow Up</button></a>
					<?php else: ?>
						<a href="manual_follow_up.php?type=operations_manual"><button type="button" class="btn brand-btn mobile-block mobile-100">Follow Up</button></a>
					<?php endif; ?>
				<?php } else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100">Follow Up</button>
				<?php } ?>
			</div>

			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See reports of the manuals."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<?php if ( check_subtab_persmission($dbc, 'ops_manual', ROLE, 'reporting') === TRUE ) { ?>
					<?php if(isset($_GET['from_manual'])): ?>
						<a href="manual_reporting.php?type=operations_manual&from_manual=1&maintype=om"><button type="button" class="btn brand-btn mobile-block mobile-100">Reporting</button></a>
					<?php else: ?>
						<a href="manual_reporting.php?type=operations_manual"><button type="button" class="btn brand-btn mobile-block mobile-100">Reporting</button></a>
					<?php endif; ?>
				<?php } else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100">Reporting</button>
				<?php } ?>
			</div>
			<?php
				if(isset($_GET['maintype'])) {
					$maintype=$_GET['maintype'];
				}
			?>
			<div class="pull-right tab">
				<?php if(isset($_GET['from_manual'])): ?>
					<a href="add_manual.php?type=operations_manual&from_manual=1&maintype=<?php echo $maintype; ?>" class="btn brand-btn mobile-block pull-right">Add Operations Manual</a>
				<?php else: ?>
					<a href="add_manual.php?type=operations_manual" class="btn brand-btn mobile-block pull-right">Add Operations Manual</a>
				<?php endif; ?>
				<span class="popover-examples pull-right" style="margin-top:7px;"><a data-toggle="tooltip" data-placement="top" title="Click here to add your manuals."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class='tab-container mobile-100-container'>
			<?php
			$tabs = mysqli_query($dbc, "SELECT distinct(category) FROM manuals WHERE deleted=0 AND manual_type='operations_manual'");
			while($row_tab = mysqli_fetch_array( $tabs )) {
				$class='';
				$category = $row_tab['category'];
				if($category == $_GET['category']) {
					$class= 'active_tab';
				}

				if(isset($_GET['from_manual'])) {
					echo '<a href="manual.php?maintype=om&category='.$category.'"><button type="button" class="btn brand-btn mobile-block mobile-100 '.$class.'" style="margin-right:3px;" >'.$category.'</button></a>';
				}
				else {
					echo '<a href="operations_manual.php?category='.$category.'"><button type="button" class="btn brand-btn mobile-block mobile-100 '.$class.'">'.$category.'</button></a>';
				}
			}
			?>
		</div>

		<div class="notice gap-bottom popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
			<div class="col-sm-11"><span class="notice-name">NOTE:</span>
				<?php
					$notes = mysqli_fetch_assoc(mysqli_query($dbc, "select note from notes_setting where subtab = 'pp_manuals'"));
					$note = $notes['note'];
				?>
				<?php echo $note; ?></div>			<div class="clearfix"></div>
		</div>

        <div class="form-group triple-gap-top triple-gap-bottom clearfix location">
            <label for="site_name" class="col-sm-4 control-label text-right"></label>
            <div class="col-sm-12">
                <?php
                    echo manual_checklist($dbc, '35', '20', '20', 'operations_manual', $_GET['category']);
                ?>
            </div>
        </div>

        </div>
    </div>
</div>