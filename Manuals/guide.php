<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('manual');
include ('manual_checklist.php');
?>

</head>
<body>

<?php include_once ('../navigation.php');
if($_GET['category'] == 0) {
	$_GET['category'] = '';
}
?>

<div class="container">
	<div class="row">
        <div class="col-md-12">

        <div class="col-sm-10">
			<h2>Manuals - <?php echo decryptIt($_SESSION['first_name']); ?> <?php echo decryptIt($_SESSION['last_name']); ?></h2>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php
				if(config_visible_function($dbc, 'how_to_guide') == 1) {
					echo '<a href="field_config_guide.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
				}
			?>
        </div>
		<div class="clearfix double-gap-bottom"></div>

        <div class="tab-container mobile-100-container double-gap-top triple-gap-bottom">
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See all manuals."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<button type="button" class="btn brand-btn mobile-block active_tab">Dashboard</button>
			</div>
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See manuals that require your attention."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a href='manual_follow_up.php?type=guide'><button type="button" class="btn brand-btn mobile-block">Follow Up</button></a>
			</div>
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See reports of the manuals."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a href="manual_reporting.php?type=guide"><button type="button" class="btn brand-btn mobile-block">Reporting</button></a>
			</div>
			<?php
				echo '<a href="add_manual.php?type=guide" class="btn brand-btn mobile-block pull-right">Add How to Guide</a>';
			?>
		</div>
		<div class="clearfix gap-bottom"></div>

        <div class="tab-container mobile-100-container"><?php
			$tabs = mysqli_query($dbc, "SELECT distinct(category) FROM manuals WHERE deleted=0 AND manual_type='guide' AND `category`!=''");
			while($row_tab = mysqli_fetch_array( $tabs )) {
				$class='';
				$category = $row_tab['category'];
				if($category == $_GET['category']) {
					$class= 'active_tab';
				}
				echo '<a href="guide.php?category='.$category.'"><button type="button" class="btn brand-btn mobile-block '.$class.'" >'.$category.'</button></a>&nbsp;&nbsp;';
			} ?>
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

        <div class="form-group triple-gap-top double-gap-bottom clearfix location">
            <label for="site_name" class="col-sm-4 control-label text-right"></label>
            <div class="col-sm-12">
                <?php
                    echo manual_checklist($dbc, '35', '20', '20', 'guide', $_GET['category']);
                ?>
            </div>
        </div>

        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>
