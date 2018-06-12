<?php include_once('../include.php');

$service_fields = explode(',',get_field_config($dbc, 'services'));
$templateid = $_GET['templateid'];
$template = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services_service_templates` WHERE `templateid` = '$templateid'"));
$serviceids = explode(',',$template['serviceid']);
?>
<script type="text/javascript">
function removeService(img) {
	$(img).closest('tr').remove();
}
</script>
<div id="no-more-tables" class="service_table">
	<table class="table table-bordered">
		<tr class="hidden-xs">
			<?php if(in_array('Category',$service_fields)) { ?>
				<th>Category</th>
			<?php } ?>
			<?php if(in_array('Service Type',$service_fields)) { ?>
				<th>Service Type</th>
			<?php } ?>
			<th>Heading</th>
			<th>Function</th>
		</tr>
		<?php foreach($serviceids as $serviceid) {
			if($serviceid > 0) {
				$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid` = '$serviceid'")); ?>
				<tr>
					<input type="hidden" name="serviceid[]" value="<?= $service['serviceid'] ?>">
					<?php if(in_array('Category',$service_fields)) { ?>
						<td data-title="Category"><?= $service['category'] ?></td>
					<?php } ?>
					<?php if(in_array('Service Type',$service_fields)) { ?>
						<td data-title="Service Type"><?= $service['service_type'] ?></td>
					<?php } ?>
					<td data-title="Heading"><?= $service['heading'] ?></td>
					<td data-title="Function">
                        <img src="../img/remove.png" class="inline-img pull-right remove_btn" onclick="removeService(this);">
					</td>
				</tr>
			<?php }
		} ?>
	</table>
</div>