<?php
$each_tab = array('Target Market', 'Objections', 'Scripts');
$status = isset($_GET['status']) && in_array($_GET['status'], $each_tab) ? $_GET['status'] : $each_tab[0];

$query = "SELECT * FROM `calllog_preparation` WHERE `tab_name`='$status'";
$result = mysqli_query($dbc, $query);

$in = " in";
?>
<?php while($row = mysqli_fetch_array( $result )) { ?>
	<h4><?= $row['heading'] ?></h4>

	<div class="form-group">
		<label class="col-sm-4 control-label">Heading:</label>
		<div class="col-sm-8" style="top: 0.5em;">
			<?php echo $row['heading'] ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Preparation Information:</label>
		<div class="col-sm-8" style="top: 0.5em;">
			<?php echo $row['editor']; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Document(s):</label>
		<div class="col-sm-8" style="top: 0.5em;">
			<?php $file_list = explode(',',$row['doc_upload']);
			foreach($file_list as $file):
				if($file != ''): ?>
						<a href="download/<?php echo $file; ?>"><?php echo $file; ?></a><br />
				<?php endif;
			endforeach; ?>
		</div>
	</div>
<?php } ?>