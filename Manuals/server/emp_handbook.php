<?php
/*
Dashboard
*/
include ('../include.php');
include ('manual_checklist.php');
?>
</head>
<body>

<?php include_once ('../navigation.php');

?>

<div class="container">
	<div class="row">
        <div class="col-md-12">

        <h2> Employee Handbook Checklist - <?php echo decryptIt($_SESSION['first_name']); ?> <?php echo decryptIt($_SESSION['last_name']); ?>
        <?php
            echo '<a href="field_config_emp_handbook.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        ?>
        <button type="button" class="btn brand-btn mobile-block active_tab" >Dashboard</button>
        <a href='manual_follow_up.php?type=emp_handbook'><button type="button" class="btn brand-btn mobile-block" >Follow Up</button></a>

        <?php
            echo '<a href="add_manual.php?type=emp_handbook" class="btn brand-btn mobile-block pull-right">Add Employee Handbook</a>';
        ?>
        </h2>

        <div class="form-group triple-pad-top triple-pad-bottom clearfix location">
            <label for="site_name" class="col-sm-4 control-label text-right"></label>
            <div class="col-sm-12">
                <?php
                    $contactid = $_GET['contactid'];
                    echo manual_checklist($dbc, $contactid, '55', '20', '20', 'emp_handbook');
                ?>
            </div>
        </div>

        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>