<?php
/*
Dashboard
*/
if(!isset($_GET['from_manual']))
include ('../include.php');
checkAuthorised('emp_handbook');
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
			<h2>Employee Handbook Checklist - <?php echo decryptIt($_SESSION['first_name']); ?> <?php echo decryptIt($_SESSION['last_name']); ?></h2>
        <?php } ?>
		</div>
		<div class="col-sm-2">
			<?php
				if(config_visible_function($dbc, 'emp_handbook') == 1) {
					if(isset($_GET['from_manual'])) {
						echo '<a href="field_config_emp_handbook.php?maintype=eh" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					}
					else {
						echo '<a href="field_config_emp_handbook.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					}
				}
			?>
        </div>

		<div class="clearfix double-gap-bottom"></div>
        <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        This contains anything the company deems to be part of their requirements for staff.</div>
        <div class="clearfix"></div>
        </div>
        
        <?php if ( check_subtab_persmission($dbc, 'emp_handbook', ROLE, 'manuals') === TRUE ) { ?>
            <button type="button" class="btn brand-btn mobile-block active_tab" >Manuals</button>
        <?php } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Manuals</button>
        <?php } ?>

		<?php if ( check_subtab_persmission($dbc, 'emp_handbook', ROLE, 'follow_up') === TRUE ) { ?>
            <?php if(isset($_GET['from_manual'])): ?>
                <a href='manual_follow_up.php?type=emp_handbook&from_manual=1&maintype=eh'><button type="button" class="btn brand-btn mobile-block">Follow Up</button></a>
            <?php else: ?>
                <a href='manual_follow_up.php?type=emp_handbook'><button type="button" class="btn brand-btn mobile-block">Follow Up</button></a>
            <?php endif; ?>
        <?php } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Follow Up</button>
        <?php } ?>
        
		<?php if ( check_subtab_persmission($dbc, 'emp_handbook', ROLE, 'reporting') === TRUE ) { ?>
            <?php if(isset($_GET['from_manual'])): ?>
                <a href='manual_reporting.php?type=emp_handbook&from_manual=1&maintype=eh'><button type="button" class="btn brand-btn mobile-block">Reporting</button></a>
            <?php else: ?>
                <a href='manual_reporting.php?type=emp_handbook'><button type="button" class="btn brand-btn mobile-block">Reporting</button></a>
            <?php endif; ?>
        <?php } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Reporting</button>
        <?php } ?>


		<?php
			if(isset($_GET['maintype'])) {
				$maintype=$_GET['maintype'];
			}
		?>
        <?php
			if(isset($_GET['from_manual'])) {
				echo '<a href="add_manual.php?type=emp_handbook&from_manual=1&maintype='.$maintype.'" class="btn brand-btn mobile-block pull-right">Add Employee Handbook</a>';
			}
			else {
				echo '<a href="add_manual.php?type=emp_handbook" class="btn brand-btn mobile-block pull-right">Add Employee Handbook</a>';
			}
        ?>
        <br><br>

        <?php
        $tabs = mysqli_query($dbc, "SELECT distinct(category) FROM manuals WHERE deleted=0 AND manual_type='emp_handbook'");
        while($row_tab = mysqli_fetch_array( $tabs )) {
            $class='';
            $category = $row_tab['category'];
            if($category == $_GET['category']) {
                $class= 'active_tab';
            }

			if(isset($_GET['from_manual'])) {
				echo '<a href="manual.php?maintype=eh&category='.$category.'"><button type="button" class="btn brand-btn mobile-block '.$class.'" >'.$category.'</button></a>&nbsp;&nbsp;';
			}
			else {
				echo '<a href="emp_handbook.php?category='.$category.'"><button type="button" class="btn brand-btn mobile-block '.$class.'" >'.$category.'</button></a>&nbsp;&nbsp;';
			}
        }
        ?>
        </h2>

        <div class="form-group triple-pad-top triple-pad-bottom clearfix location">
            <label for="site_name" class="col-sm-4 control-label text-right"></label>
            <div class="col-sm-12">
                <?php
                    echo manual_checklist($dbc, '35', '20', '20', 'emp_handbook', $_GET['category']);
                ?>
            </div>
        </div>

        </div>
    </div>
</div>