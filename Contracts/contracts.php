<?php // Contracts
include_once ('../include.php');
checkAuthorised('contracts');
error_reporting(0);
require_once ('list_contracts.php'); ?>
</head>
<body>

<?php include_once ('../navigation.php');
$config = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contract_tabs`, `header_logo`, `header_text`, `footer_logo`, `footer_text` FROM `field_config_contracts`
	UNION SELECT 'Follow Up#*#Reporting#*#Customer', '', '', '', ''"));
$contract_tabs = explode('#*#', $config['contract_tabs']);
$tab_name = (empty($_GET['tab']) ? array_values(array_filter($contract_tabs, function($val) { return ($val != 'Follow Up' && $val != 'Reporting'); }))[0] : $_GET['tab']); ?>

<div class="container triple-pad-bottom">
    <div class="row">
		<h1>Contracts: <?= $tab_name ?> Dashboard
			<?php if(config_visible_function($dbc, 'contracts') == 1) {
				echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			} ?></h1>
		<div class="clearfix"></div><br />

		<?php foreach(array_filter($contract_tabs, function($val) { return ($val != 'Follow Up' && $val != 'Reporting'); }) as $tab) {
			if(check_subtab_persmission($dbc, 'contracts', ROLE, $tab) === TRUE) {
				echo "<a href='contracts.php?tab=$tab' class='btn brand-btn mobile-block mobile-100 ".($tab_name == $tab ? 'active_tab' : '')."'>$tab</a>";
			}
		}
		if(in_array('Follow Up',$contract_tabs) && check_subtab_persmission($dbc, 'contracts', ROLE, 'Follow Up') === TRUE) {
			echo "<a href='follow_up.php' class='btn brand-btn mobile-block mobile-100 '>Follow Up</a>";
		}
		if(in_array('Reporting',$contract_tabs) && check_subtab_persmission($dbc, 'contracts', ROLE, 'Reporting') === TRUE) {
			echo "<a href='reporting.php' class='btn brand-btn mobile-block mobile-100 '>Reporting</a>";
		} ?>
		<div class="clearfix"></div><br />
		
		<?php if(vuaed_visible_function($dbc, 'contracts') == 1) {
			echo "<a class='btn brand-btn pull-right' href='add_contract.php?tab=$tab_name'>Add ".($tab_name == 'Templates' ? 'Template' :  $tab_name.' Contract')."</a>";
		} ?>
		<?php list_contracts($dbc, $tab_name, vuaed_visible_function($dbc, 'contracts')); ?>
		<?php if(vuaed_visible_function($dbc, 'contracts') == 1) {
			echo "<a class='btn brand-btn pull-right' href='add_contract.php?tab=$tab_name'>Add ".($tab_name == 'Templates' ? 'Template' :  $tab_name.' Contract')."</a>";
		} ?>
    </div>
</div>
<?php include ('../footer.php'); ?>