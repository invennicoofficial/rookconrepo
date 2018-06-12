<?php
/*
Dashboard
*/
if(!isset($_GET['from_manual']))
	include ('../include.php');
checkAuthorised('hr');
include ('manual_checklist_pp.php');
?>

</head>
<body>

<?php include_once ('../navigation.php');

?>

<div class="container">
	<div class="row">
		

        <div class="col-md-10">
			<h1>Policy & Procedures</h1>
		</div>
		<div class="clearfix double-gap-bottom"></div>

		<div class="gap-left tab-container mobile-100-container double-gap-bottom">
			<!--
			<a href='hr.php?tab=Toolbox'><button type="button" class="btn brand-btn mobile-block <?php echo $active_toolbox; ?>" >Toolbox</button></a>
			<a href='hr.php?tab=Tailgate'><button type="button" class="btn brand-btn mobile-block <?php echo $active_taligate; ?>" >Tailgate</button></a>-->
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all Forms you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'forms') === TRUE ) { ?>
					<a href='hr.php?tab=Form'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_form; ?>" >Forms</button></a>
				<?php } else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $active_form; ?>">Forms</button>
				<?php } ?>
			</div>
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to see all the Manuals you have created."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'manuals') === TRUE ) { ?>
					<a href='hr.php?tab=Manual'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_manual; ?>" >Manuals</button></a>
				<?php } else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $active_manual; ?>">Manuals</button>
				<?php } ?>
			</div>
			<!-- <a href='<?php echo $type; ?>.php?category=<?php echo $manual_category; ?>'><button type="button" class="btn brand-btn mobile-block" >Dashboard</button></a>
			<a href='manual_follow_up.php?type=<?php echo $type; ?>'><button type="button" class="btn brand-btn mobile-block" >Follow Up</button></a>-->
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to search through the HR Reports."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<?php if ( check_subtab_persmission($dbc, 'hr', ROLE, 'reporting') === TRUE ) { ?>
					<a href='manual_reporting.php?type=<?php echo $type; ?>'><button type="button" class="btn mobile-100 brand-btn mobile-block" >Reporting</button></a>
				<?php } else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100">Reporting</button>
				<?php } ?>
			</div>
			<div class="pull-left tab">
				<a href='policy_procedures.php?category=Policies and Procedures&source=hr'><button type="button" class="btn brand-btn active_tab mobile-block mobile-100 <?php echo $active_manual; ?>">Policies & Procedures</button></a>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="clearfix double-gap-bottom"></div>

		<div class="tab-container mobile-100-container double-gap-top triple-gap-bottom">
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See all manuals."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<button type="button" class="btn brand-btn mobile-block active_tab mobile-100">Dashboard</button>
			</div>
			<?php
				if(isset($_GET['maintype'])) {
					$maintype=$_GET['maintype'];
				}
			?>
        </div>
		<div class="clearfix gap-bottom"></div>

		<div class="tab-container mobile-100-container">
			<?php
			$tabs = mysqli_query($dbc, "SELECT distinct(category) FROM manuals WHERE deleted=0 AND manual_type='policy_procedures'");
			while($row_tab = mysqli_fetch_array( $tabs )) {
				$class='';
				$category = $row_tab['category'];
				if($category == $_GET['category']) {
					$class= 'active_tab';
				}

				if(isset($_GET['from_manual'])) {
					echo '<a href="manual.php?category='.$category.'"><button type="button" class="btn brand-btn mobile-block mobile-100 '.$class.'" style="margin-right:3px;" >'.$category.'</button></a>';
				}
				else {
					echo '<a href="policy_procedures.php?category='.$category.'"><button type="button" class="btn brand-btn mobile-block mobile-100 '.$class.'" style="margin-right:3px;" >'.$category.'</button></a>';
				}
			}
			?>
		</div>

		<div class="notice gap-bottom popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
			<div class="col-sm-11"><span class="notice-name">NOTE:</span>
			In this section manuals can be applied. Manuals can be used as an ongoing resource to review as needed, portions of manuals can be assigned for review, update portions can be emailed out for review and a full reporting log of who signed off on what, when and where is kept. Maintaining the proper manuals for your business is essential, and this section has the ability to make it easier than ever. Click the heading to review in detail any portion of the manual. Red review needed alerts show which aspects of the manual you have not completed.</div>
			<div class="clearfix"></div>
		</div>

        <div class="form-group triple-gap-top triple-gap-bottom clearfix location x123x">
            <label for="site_name" class="col-sm-4 control-label text-right"></label>
            <div class="col-sm-12">
                <?php
                    echo manual_checklist($dbc, '35', '20', '20', 'policy_procedures', $_GET['category']);
                ?>
            </div>
        </div>

        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>