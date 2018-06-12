<?php include_once('../include.php');
checkAuthorised('estimate');
if(!isset($estimate)) {
	$estimateid = filter_var($estimateid,FILTER_SANITIZE_STRING);
	$estimate = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
}
$documents = mysqli_query($dbc, "SELECT * FROM `estimate_document` WHERE `estimateid`='$estimateid' AND `deleted`=0") ?>
<div id="no_more_tables" class="form-horizontal col-sm-12" data-tab-name="documents">
	<h3>Reference Documents</h3>
	<table class="table table_bordered">
		<?php if(mysqli_num_rows($documents) > 0) { ?>
			<tr class="hidden-sm hidden-xs">
				<th>Document</th>
				<th>Category</th>
				<th>Added By</th>
			</tr>
		<?php } ?>
		<?php while($document = mysqli_fetch_array($documents)) { ?>
			<tr>
				<td data-title="Document"><?php if($document['upload'] != '') { ?>
					<a href="download/<?= $document['upload'] ?>"><?= $document['label'] == '' ? $document['upload'] : $document['label'] ?></a>
				<?php } else if($document['link'] != '') { ?>
					<a href="<?= (strpos($document['link'],'http') === FALSE ? 'http://' : '').$document['link'] ?>"><?= $document['label'] == '' ? $document['link'] : $document['label'] ?></a>
				<?php } ?>
				- <input type="text" name="label" value="<?= $document['label'] ?>" data-table="estimate_document" data-id-field="uploadid" data-id="<?= $document['uploadid'] ?>" style="display:none;" onchange="$(this).closest('td').find('a').first().text(this.value); $(this).hide();">
				<a href="" onclick="$(this).closest('td').find('[name=label]').show().focus(); return false;">Rename</a>
				- <input type="hidden" name="deleted" value="1" data-table="estimate_document" data-id-field="uploadid" data-id="<?= $document['uploadid'] ?>">
				<a href="" onclick="$(this).closest('td').find('[name=deleted]').change(); $(this).closest('tr').remove(); return false;">Delete</a></td>
				<td data-title="Category"><?= $document['category'] ?></td>
				<td data-title="Added By"><?= get_contact($dbc, $document['created_by']) ?></td>
			</tr>
		<?php } ?>
	</table>
	<div class="form-group">
		<label class="col-sm-4">Support Documents:</label>
		<div class="col-sm-8">
			<input type="file" multiple name="upload" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Support Links:</label>
		<div class="col-sm-8">
			<input type="text" name="link" class="form-control" data-table="estimate_document" data-id-field="uploadid" data-estimate="<?= $estimateid ?>">
		</div>
	</div>
</div>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'edit_documents.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>