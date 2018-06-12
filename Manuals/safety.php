<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised();
include ('manual_checklist.php');
?>

</head>
<body>

<?php include_once ('../navigation.php');

?>

<div class="container">
	<div class="row">
        <div class="col-md-12">

        <h2> Safety Checklist - <?php echo decryptIt($_SESSION['first_name']); ?> <?php echo decryptIt($_SESSION['last_name']); ?>
        <?php
        if(config_visible_function($dbc, 'safety') == 1) {
            echo '<a href="field_config_safety.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>

        <button type="button" class="btn brand-btn mobile-block active_tab" >Dashboard</button>
        <a href='manual_follow_up.php?type=safety'><button type="button" class="btn brand-btn mobile-block" >Follow Up</button></a>
        <a href='manual_reporting.php?type=safety'><button type="button" class="btn brand-btn mobile-block" >Reporting</button></a>

        <?php
            echo '<a href="add_manual.php?type=safety" class="btn brand-btn mobile-block pull-right">Add a Safety Item</a>';
        ?>
        <br><br>

        <?php
        $tabs = mysqli_query($dbc, "SELECT distinct(category) FROM manuals WHERE deleted=0 AND manual_type='safety'");
        while($row_tab = mysqli_fetch_array( $tabs )) {
            $class='';
            $category = $row_tab['category'];
            if($category == $_GET['category']) {
                $class= 'active_tab';
            }
            echo '<a href="safety.php?category='.$category.'"><button type="button" class="btn brand-btn mobile-block '.$class.'" >'.$category.'</button></a>&nbsp;&nbsp;';
        }
        ?>
        </h2>

        <div class="form-group triple-pad-top triple-pad-bottom clearfix location">
            <label for="site_name" class="col-sm-4 control-label text-right"></label>
            <div class="col-sm-12">
                <?php
                    echo manual_checklist($dbc, '35', '20', '20', 'safety', $_GET['category']);
                ?>
            </div>
        </div>

        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>