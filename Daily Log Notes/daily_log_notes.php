<?php
include('../include.php');
?>
</head>
<body>
<?php
include_once ('../navigation.php');
checkAuthorised('daily_log_notes');
include_once('config.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <h1 class="">Daily Log Notes Dashboard
        <?php
        if(config_visible_function_log_notes($dbc)) {
            echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        </h1>

        <?php echo get_tabs_log_notes('Daily Log Notes'); ?>
        <br><br>
		
		<?php $min_display = 0;
		$from_url = 'daily_log_notes.php';
		include('log_note_list.php'); ?>
		
        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
