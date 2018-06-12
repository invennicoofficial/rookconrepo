<?php include_once('../include.php');
checkAuthorised('form_builder');

$category = $_GET['category'];

if(!empty($category)) { ?>
	<table class="table table-bordered">
		<tr>
			<th>Service</th>
		</tr>
		<?php $query = mysqli_query($dbc, "SELECT * FROM `services` WHERE `category` = '$category' AND `deleted` = 0 ORDER BY `heading`");
		while($row = mysqli_fetch_array($query)) { ?>
			<tr class="sortable-service" data-id="<?= $row['serviceid'] ?>">
				<td data-title="Service">
					<?= $row['heading'] ?>
					<div class="pull-right">
						<img src="../img/icons/ROOK-add-icon.png" class="inline-img add_img" onclick="addService(this);">
						<img src="../img/remove.png" class="inline-img added_img" onclick="removeService(this);" style="display: none;">
						<img src="../img/icons/drag_handle.png" class="inline-img added_img drag-handle" style="display: none;">
					</div>
				</td>
			</tr>
		<?php } ?>
	</table>
<?php } else {
	echo 'No Service Category Chosen.';
}