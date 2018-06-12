<?php include_once('../include.php');
if(!empty($_GET['tile_name'])) {
	checkAuthorised(false,false,'documents_all_'.$_GET['tile_name']);
} else {
	checkAuthorised('documents_all');
}
include_once('document_settings.php'); ?>

<div class="tile-sidebar sidebar hide-titles-mob standard-collapsible">
	<ul>
		<li><a href="?tile_name=<?= $tile_name ?>&tab=<?= $_GET['tab'] ?>">Back to Dashboard</a></li>
		<li class="active blue"><?= $tab_type ?> Details</li>
	</ul>
</div>

<div class="scale-to-fill has-main-screen">
	<div class="main-screen standard-body form-horizontal">
		<div class="standard-body-title">
			<h3><?= !empty($_GET['edit']) ? 'Edit' : 'Add' ?> <?= $tab_type ?></h3>
		</div>

		<div class="standard-body-content" style="padding: 1em;">
			<?php if($_GET['tab'] == 'client_documents') {
				include('edit_client.php');
			} else if($_GET['tab'] == 'staff_documents') {
				include('edit_staff.php');
			} else if($_GET['tab'] == 'internal_documents') {
				include('edit_internal.php');
			} else if($_GET['tab'] == 'marketing_material') {
				include('edit_marketing.php');
			} else {
				include('edit_custom.php');
			} ?>
		</div>
	</div>
</div>