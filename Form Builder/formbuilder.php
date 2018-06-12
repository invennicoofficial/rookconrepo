<?php // Form Builder Main File
error_reporting(0);

if(!empty($_GET['tab']) && $_GET['tab'] == 'external_form' && !empty($_GET['id'])) {
	$guest_access = true;
}
include('../include.php');
mysqli_query($dbc, "INSERT INTO `field_config_user_forms` (`default_head_align`,`default_head_font`,`default_head_size`,`default_head_color`,`default_foot_align`,`default_foot_font`,`default_foot_size`,`default_foot_color`,`default_section_heading_font`,`default_section_heading_size`,`default_section_heading_color`,`default_body_heading_font`,`default_body_heading_size`,`default_body_heading_color`,`default_font`,`default_body_size`,`default_body_color`,`use_templates`) SELECT 'R','helvetica','9','#000000','C','helvetica','9','#000000','helvetica','14','#000000','helvetica','11','#000000','helvetica','9','#000000','1' FROM (SELECT COUNT(*) rows FROM `field_config_user_forms`) num WHERE num.rows=0");

if(empty($_GET['tab'])) {
	$_GET['tab'] = 'form_list';
}
$name = (empty($_GET['id']) ? '' : mysqli_fetch_array(mysqli_query($dbc, "SELECT `name` FROM `user_forms` WHERE `form_id`='{$_GET['id']}'"))['name']);
switch($_GET['tab']) {
	case 'add_form': $tab = 'add_form';
		$title = ($name == '' ? 'Create Form' : 'Edit Form: '.$name);
		break;
	case 'assign_form': $tab = 'assign_form';
		$title = 'Assign '.$name.' to be Completed';
		break;
	case 'generate_form': $tab = 'generate_form';
		$title = 'Complete Form '.$name;
		break;
	case 'external_form': $tab = 'external_form';
		$title = 'Complete Form '.$name;
		break;
	case 'reporting': $tab = 'reporting';
		$title = 'Complete Form '.$name;
		break;
	case 'field_config': $tab = 'field_config';
		$title = 'Form Builder Settings';
		break;
	default: $tab = 'form_list';
		$title = 'Custom Forms';
		break;
} ?>

<script type="text/javascript">
$(document).ready(function() {
	<?= (check_subtab_persmission($dbc, 'form_builder', ROLE, $tab) === FALSE ? "$('.tab-container button:first').click();" : '') ?>
});
</script>

</head>
<body>
<?php if(empty($_GET['tab']) || $_GET['tab'] != 'external_form') {
	include('../navigation.php');
}
checkAuthorised('form_builder');
$config_access = config_visible_function($dbc, 'form_builder');
$edit_access = vuaed_visible_function($dbc, 'form_builder');

if($tab == 'generate_form') {
	if(!empty($_GET['id'])) {
	    $user_form_id = $_GET['id'];
	    if($user_form_id > 0) {
	        $user_form_layout = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM `user_forms` WHERE `form_id` = '$user_form_id'"))['form_layout'];
	        $user_form_layout = !empty($user_form_layout) ? $user_form_layout : 'Accordions';   
	    }
	}
}
?>

<div class="container triple-pad-bottom" <?= $user_form_layout == 'Sidebar' ? 'style="padding: 0; margin: 0;"' : '' ?>>
	<div id="no-more-tables" class="row">
	    <?php if($user_form_layout == 'Sidebar') { ?>
			<h1 style="margin-top: 0; padding-top: 0;"><a href="?">Form Builder</a><?php if($config_access == 1) {
				echo '<a href="?tab=field_config" class="mobile-block pull-right "><img style="width: 30px;" title="Form Builder Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
	            echo '<span class="popover-examples list-inline pull-right"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here for the form builder settings. You will be able to set default and required values for forms that are built with this tool."><img src="' . WEBSITE_URL . '/img/info.png" width="20" height="20"></a></span>';
	        } ?></h1>
	    <?php } else { ?>
			<h1><?php echo $title; ?><?php if($config_access == 1) {
				echo '<a href="?tab=field_config" class="mobile-block pull-right "><img style="width: 50px;" title="Form Builder Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
	            echo '<span class="popover-examples list-inline pull-right"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here for the form builder settings. You will be able to set default and required values for forms that are built with this tool."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span><br><br>';
	        } ?></h1>
			<div class="tab-container"><?php
				$tab_info = [ 'form_list' => 'Displays all custom forms created by any user.',
					'assign_form' => 'Assign a form to be completed.',
					'generate_form' => 'Complete the form and generate a PDF.',
					'external_form' => 'Complete the form and generate a PDF.',
					'reporting' => 'View completed and assigned forms.',
					'add_form' => 'Add a new custom form.',
					'field_config' => 'Configure the default and required settings for forms created by a user. Defaults will only be used by new forms, required settings will affect all forms.' ];
				$tab_name = [ 'form_list' => ($tab == 'field_config' ? 'Back to Dashboard' : 'Custom Forms'),
					'reporting' => 'Reporting' ];

				foreach ($tab_name as $tab_id => $tab_label) {
					if ( check_subtab_persmission($dbc, 'form_builder', ROLE, $tab_id) === TRUE ) {
						$subtabs_nav = "<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='". $tab_info[$tab_id] ."'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>";
						$subtabs_nav .= "<a href='?tab=" . $tab_id . "'><button type='button' class='btn brand-btn mobile-100 mobile-block ". ($tab == $tab_id ? 'active_tab' : '') ."' >". $tab_label ."</button></a>";
						echo "<div class='pull-left tab'>" . $subtabs_nav . "</div>";
					}
				} ?>
				<div class="clearfix"></div>
			</div>
		
			<div class="notice double-gap-bottom popover-examples">
				<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL ?>/img/info.png" class="wiggle-me" width="25"></div>
				<div class="col-sm-11"><span class="notice-name">NOTE:</span> <?= $tab_info[$tab] ?></div>
				<div class="clearfix"></div>
			</div>
		<?php } ?>

		<?php include($tab.'.php'); ?>
	</div>
</div>

<?php include('../footer.php'); ?>