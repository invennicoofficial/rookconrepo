<?php
/*
Dashboard
*/
if(!isset($_GET['from_manual']))
	include ('../include.php');
checkAuthorised('safety_manual');
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
                <h2>Safety Manual Checklist - <?php echo decryptIt($_SESSION['first_name']); ?> <?php echo decryptIt($_SESSION['last_name']); ?></h2>
            <?php } ?>
            </div>

            <div class="col-sm-2">
                <?php
                    if(config_visible_function($dbc, 'safety_manual') == 1) {
                        if(isset($_GET['from_manual'])) {
                            echo '<a href="field_config_safety_manual.php?maintype=pp" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
                        }
                        else {
                            echo '<a href="field_config_safety_manual.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
                        }
                    }
                ?>
            </div>

            <div class="clearfix double-gap-bottom"></div>

            <div class="tab-container mobile-100-container double-gap-top triple-gap-bottom">
                <div class="pull-left tab">
                    <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See all manuals."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <?php if ( check_subtab_persmission($dbc, 'safety_manual', ROLE, 'manuals') === TRUE ) { ?>
                        <button type="button" class="btn brand-btn mobile-block active_tab mobile-100">Manuals</button>
                    <?php } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block mobile-100">Manuals</button>
                    <?php } ?>
                </div>
                <div class="pull-left tab">
                    <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See manuals that require your attention."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <?php if ( check_subtab_persmission($dbc, 'safety_manual', ROLE, 'follow_up') === TRUE ) { ?>
                        <?php if(isset($_GET['from_manual'])): ?>
                            <a href="manual_follow_up.php?type=safety_manual&from_manual=1"><button type="button" class="btn brand-btn mobile-block mobile-100">Follow Up</button></a>
                        <?php else: ?>
                            <a href="manual_follow_up.php?type=safety_manual"><button type="button" class="btn brand-btn mobile-block mobile-100">Follow Up</button></a>
                        <?php endif; ?>
                    <?php } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block mobile-100">Follow Up</button>
                    <?php } ?>
                </div>
                <div class="pull-left tab">
                    <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="See reports of the manuals."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <?php if ( check_subtab_persmission($dbc, 'safety_manual', ROLE, 'reporting') === TRUE ) { ?>
                        <?php if(isset($_GET['from_manual'])): ?>
                            <a href="manual_reporting.php?type=safety_manual&from_manual=1"><button type="button" class="btn brand-btn mobile-block mobile-100">Reporting</button></a>
                        <?php else: ?>
                            <a href="manual_reporting.php?type=safety_manual"><button type="button" class="btn brand-btn mobile-block mobile-100">Reporting</button></a>
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
                <?php
                    if(isset($_GET['from_manual'])) {
                        echo '<a href="add_manual.php?type=safety_manual&from_manual=1&maintype='.$maintype.'" class="btn brand-btn mobile-block pull-right mobile-100-pull-right">Add Safety Manual</a>';
                    }
                    else {
                        echo '<a href="add_manual.php?type=safety_manual" class="btn brand-btn mobile-block pull-right mobile-100-pull-right">Add Safety Manual</a>';
                    }
                ?>
            </div>

            <div class="clearfix gap-bottom"></div>

            <div class="tab-container mobile-100-container">
                <?php
                $tabs = mysqli_query($dbc, "SELECT distinct(category) FROM manuals WHERE deleted=0 AND manual_type='safety_manual'");
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
                        echo '<a href="safety_manual.php?category='.$category.'"><button type="button" class="btn brand-btn mobile-block mobile-100 '.$class.'" style="margin-right:3px;" >'.$category.'</button></a>';
                    }
                }
                ?>
            </div>

            <div class="notice gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
									<?php
										$notes = mysqli_fetch_assoc(mysqli_query($dbc, "select note from notes_setting where subtab = 'pp_reporting'"));
										$note = $notes['note'];
									?>
									<?php echo $note; ?></div>
								<div class="clearfix"></div>
            </div>

            <div class="form-group triple-gap-top triple-gap-bottom clearfix location x123x">
                <label for="site_name" class="col-sm-4 control-label text-right"></label>
                <div class="col-sm-12">
                    <?php
                        if ( isset($_GET['category']) && $_GET['category']!=0 ) {
                            echo manual_checklist($dbc, '35', '20', '20', 'safety_manual', $_GET['category']);
                        }
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>
