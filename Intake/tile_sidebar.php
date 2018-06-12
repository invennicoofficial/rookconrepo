<?php $tab = $_GET['tab'];
if(empty($tab)) {
	$tab = 'webforms';
}
$type = $_GET['type'];
$web_type = $_GET['web_type'];
$intake_cat = $_GET['cat'];
if(!empty($intake_cat)) {
	$title = 'Configure '.$intake_cat.' Forms.';
}
if(!empty($type)) {
	$intake = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `intake_forms` WHERE `intakeformid` = '$type'"));
	$intake_cat = $intake['category'];
}
?>
<ul>
	<li class="standard-sidebar-searchbox"><input type="text" name="search_term" value="<?= $_POST['search_term'] ?>" class="form-control search_list" placeholder="Search by Any">
		<input type="hidden" name="search_submit" value="Search"></li>
	<!-- <a href="intake.php?tab=webforms"><li <?= $tab == 'webforms' ? 'class="active"' : '' ?>>Website Forms</li></a> -->
	<li class="sidebar-higher-level"><a class="<?= $_GET['tab'] == 'webforms' ? 'active blue' : 'collapsed' ?> cursor-hand" data-toggle="collapse" data-target="#collapse_web">Website Forms<span class="arrow"></span></a>
		<ul id="collapse_web" class="collapse <?= $_GET['tab'] == 'webforms' ? 'in' : '' ?>">
			<?php $form_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) num_rows FROM `intake` WHERE `intakeformid` = 0 AND `deleted` = 0 AND `assigned_date` = '0000-00-00'"))['num_rows']; ?>
			<a href="intake.php?tab=webforms"><li <?= empty($_GET['web_type']) && $tab == 'webforms' ? 'class="active"' : '' ?>>All Website Forms<span class="pull-right"><?= $form_count ?></span></li></a>
			<?php $intake_cats_web = array_column(mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT(`category`) FROM `intake` WHERE `intakeformid` = 0 AND `deleted` = 0 AND `assigned_date` = '0000-00-00' ORDER BY `category`"),MYSQLI_ASSOC),'category');
			foreach($intake_cats_web as $intake_cat_web) {
				$form_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) num_rows FROM `intake` WHERE `intakeformid` = 0 AND `category` = '$intake_cat' AND `deleted` = 0 AND `assigned_date` = '0000-00-00'"))['num_rows']; ?>
				<a href="intake.php?tab=webforms&web_type=<?= $intake_cat ?>"><li <?= $intake_cat_web == $_GET['web_type'] && $tab == 'webforms' ? 'class="active"' : '' ?>><?= $intake_cat_web ?><span class="pull-right"><?= $form_count ?></span></li></a>
			<?php } ?>
		</ul>
	</li>
	<a href="intake.php?tab=softwareforms"><li <?= $tab == 'softwareforms' ? 'class="active"' : '' ?>>Forms</li></a>
	<?php if($tab == 'softwareforms') { ?>
		<ul style="margin: 0px;">
		<?php $form_categories = get_config($dbc, 'intake_software_tabs');
		$form_types = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `intake_forms` WHERE `deleted` = 0 ORDER BY `form_name` ASC"),MYSQLI_ASSOC);
		if(!empty($form_categories)) {
			$form_tabs = [];
			$form_categories = explode('*#*', $form_categories);
			foreach($form_categories as $form_cat) {
				foreach($form_types as $form_i => $form_type) {
					if($form_type['category'] == $form_cat) {
						$form_tabs[$form_cat][] = $form_type;
						unset($form_types[$form_i]);
					}
				}
			}
			ksort($form_categories);
			foreach($form_types as $form_i => $form_type) {
				$form_tabs['(Uncategorized)'][] = $form_type;
			}
			foreach($form_tabs as $form_cat => $form_tab) { ?>
				<li class="sidebar-higher-level"><a class="<?= (!empty($type) && $intake_cat == $form_cat) ? 'active blue' : 'collapsed' ?> cursor-hand" data-toggle="collapse" data-target="#cat_<?= config_safe_str($form_cat) ?>"><?= $form_cat ?><span class="arrow"></span></a>
					<ul id="cat_<?= config_safe_str($form_cat) ?>" class="collapse <?= (!empty($type) && ($intake_cat == $form_cat || !in_array($intake_cat, $form_categories)) || ($intake_cat == $form_cat && empty($type))) ? 'in' : '' ?>">
						<a href="intake.php?tab=softwareforms&cat=<?= $form_cat ?>"><li <?= ($intake_cat == $form_cat && empty($type)) ? 'class="active"' : '' ?>>Configure <?= $form_cat ?> Forms</li></a>
						<?php foreach($form_tab as $form_type) {
							$form_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) num_rows FROM `intake` WHERE `assigned_date` = '0000-00-00' AND `deleted` = 0 AND `intakeformid` = '".$form_type['intakeformid']."'"))['num_rows']; ?>
							<a href="intake.php?tab=softwareforms&type=<?= $form_type['intakeformid'] ?>"><li <?= $type == $form_type['intakeformid'] ? 'class="active"' : '' ?>><?= $form_type['form_name'] ?><span class="pull-right"><?= $form_count ?></span></li></a>
						<?php } ?>
					</ul>
				</li>
			<?php }
		} else { ?>
			<?php foreach ($form_types as $form_type) {
				$form_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) num_rows FROM `intake` WHERE `assigned_date` = '0000-00-00' AND `deleted` = 0 AND `intakeformid` = '".$form_type['intakeformid']."'"))['num_rows']; ?>
				<a href="intake.php?tab=softwareforms&type=<?= $form_type['intakeformid'] ?>"><li <?= $type == $form_type['intakeformid'] ? 'class="active"' : '' ?>><?= $form_type['form_name'] ?><span class="pull-right"><?= $form_count ?></span></li></a>
			<?php }
		} ?>
		</ul>
	<?php } ?>
</ul>