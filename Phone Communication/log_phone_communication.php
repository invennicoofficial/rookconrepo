<?php

/*
Equipment Listing
*/
include ('../include.php');
?>
</head>

<body>

<?php include_once ('../navigation.php');
checkAuthorised('phone_communication');
?>
<div class="container">
    <div class="row">

    <div class="col-sm-10">
		<h1>Phone Communication</h1>
	</div>
	<div class="col-sm-2 double-gap-top">
		<?php
			if(config_visible_function($dbc, 'phone_communication') == 1) {
				echo '<a href="field_config.php?type=tab" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			}
		?>
	</div>
	
	<div class="clearfix double-gap-bottom"></div>
	
	<div class="gap-left tab-container mobile-100-container">
		<?php if ( check_subtab_persmission($dbc, 'phone_communication', ROLE, 'internal') === TRUE ) { ?>
			<a href="phone_communication.php?type=Internal"><button type="button" class="btn mobile-100 brand-btn mobile-block">Internal</button></a>&nbsp;&nbsp;
		<?php } else { ?>
			<button type="button" class="btn disabled-btn mobile-block mobile-100">Internal</button>&nbsp;&nbsp;
		<?php } ?>
		
		<?php if ( check_subtab_persmission($dbc, 'phone_communication', ROLE, 'external') === TRUE ) { ?>
			<a href="phone_communication.php?type=External"><button type="button" class="btn mobile-100 brand-btn mobile-block">External</button></a>&nbsp;&nbsp;
		<?php } else { ?>
			<button type="button" class="btn disabled-btn mobile-block mobile-100">External</button>&nbsp;&nbsp;
		<?php } ?>
		
		<?php if ( check_subtab_persmission($dbc, 'phone_communication', ROLE, 'log') === TRUE ) { ?>
			<a href="log_phone_communication.php"><button type="button" class="btn brand-btn mobile-100 mobile-block active_tab">Log</button></a>&nbsp;&nbsp;
		<?php } else { ?>
			<button type="button" class="btn disabled-btn mobile-block mobile-100">Log</button>&nbsp;&nbsp;
		<?php } ?>
    </div>

    <form name="form_sites" method="post" action="" class="form-inline" role="form">

    <?php include('log_display.php'); ?>
</div>
</div>

<?php include ('../footer.php'); ?>