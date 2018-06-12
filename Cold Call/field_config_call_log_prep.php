<?php
/*
Dashboard
*/
include ('../include.php');
error_reporting(1);
checkAuthorised('calllog');

if (isset($_POST['submit'])) {
	$each_tab = array('Target Market', 'Objections', 'Scripts');
	$status = isset($_GET['status']) && in_array($_GET['status'], $each_tab) ? $_GET['status'] : $each_tab[0];

	// Check for Document Folder
	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}

	foreach($_POST['id'] as $id) {
		$heading = filter_var($_POST['heading_'.$id],FILTER_SANITIZE_STRING);
		$editor = filter_var($_POST['editor_'.$id],FILTER_SANITIZE_STRING);
		$documents = $_POST['current_documents_'.$id];
		//Document
		for($i = 0; $i < count($_FILES['new_documents_'.$id]['name']); $i++) {
			$document = $_FILES['new_documents_'.$id]["name"][$i];
			if($document != '') {
				move_uploaded_file($_FILES['new_documents_'.$id]["tmp_name"][$i], "download/".$document) ;
				$documents[] = htmlspecialchars($document, ENT_QUOTES);
			}
		}
		$docList = implode(",", $documents);
		$query = "UPDATE `calllog_preparation` SET `heading`='$heading', `editor`='$editor', `doc_upload`='$docList' WHERE `calllog_preparation_id`='$id'";
		$result = mysqli_query($dbc, $query);
	}

	$heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
	$editor = filter_var($_POST['editor'],FILTER_SANITIZE_STRING);
	if($heading != '' || $editor != '' || count($_FILES['upload_document']['name']) == 0) {
		$documents = [];

		//Document
		for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
			$document = $_FILES["upload_document"]["name"][$i];
			if($document != '') {
				move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "download/".$document) ;
				$documents[] = htmlspecialchars($document, ENT_QUOTES);
			}
		}
		$docList = implode(",", $documents);

		$query_insert_config = "INSERT INTO `calllog_preparation` (`tab_name`, `heading`, `editor`, `doc_upload`) VALUES ('$status','$heading','$editor','$docList')";
		$result_insert_config = mysqli_query($dbc, $query_insert_config);
	}
	echo '<script type="text/javascript"> window.location.replace(""); </script>';
}
?>
<script>
$(document).ready(function(){
	$("#selectall").change(function(){
		$(".all_check").prop('checked', $(this).prop("checked"));
	});
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<div class="pad-left gap-top"><a href="call_log.php?maintype=preparation" class="btn config-btn">Back to Dashboard</a>
<br><br>
<?php
$each_tab = array('Target Market', 'Objections', 'Scripts');
$status = isset($_GET['status']) && in_array($_GET['status'], $each_tab) ? $_GET['status'] : $each_tab[0];

$query = "SELECT * FROM `calllog_preparation` WHERE `tab_name`='$status'";
$result = mysqli_query($dbc, $query);

foreach ($each_tab as $cat_tab) { ?>
	<a href='field_config_call_log_prep.php?maintype=preparation&status=<?php echo $cat_tab; ?>'>
		<button type='button' class='btn brand-btn mobile-block	mobile-100 <?php echo $status == $cat_tab ? 'active_tab' : ''; ?>'>
		<?php echo $cat_tab; ?></button></a>&nbsp;&nbsp;
<?php } ?>
</div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->
<br><br>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">
	<?php while($row = mysqli_fetch_array($result)) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_prep_<?php echo $row['calllog_preparation_id']; ?>" >
						<?php echo $row['heading'] ?><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_prep_<?php echo $row['calllog_preparation_id']; ?>" class="panel-collapse collapse">
				<div class="panel-body">
					<div class="form-group">
						<input type="hidden" name="id[]" value="<?php echo $row['calllog_preparation_id']; ?>">
						<label for="heading_<?php echo $row['calllog_preparation_id']; ?>" class="col-sm-4 control-label">Heading:</label>
						<div class="col-sm-8">
							<input name="heading_<?php echo $row['calllog_preparation_id']; ?>" type="text" value="<?php echo $row['heading'] ?>" class="form-control">
						</div>
						<label for="editor_<?php echo $row['calllog_preparation_id']; ?>" class="col-sm-4 control-label">Text Editor Content:</label>
						<div class="col-sm-8">
							<textarea name="editor_<?php echo $row['calllog_preparation_id']; ?>" rows="3" cols="50" class="form-control"><?php echo $row['editor']; ?></textarea>
						</div>
						<label for="additional_note" class="col-sm-4 control-label">Document(s):
							<span class="popover-examples list-inline">&nbsp;
							<a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
							</span>
						</label>
						<div class="col-sm-8">
							<div class="enter_cost additional_doc_<?php echo $row['calllog_preparation_id']; ?> clearfix">
								<div class="clearfix"></div>

								<div class="form-group clearfix">
									<?php $file_list = explode(',',$row['doc_upload']);
									$i = 0;
									foreach($file_list as $file):
										if($file != ''): ?>
											<div id="prep_<?php echo $row['calllog_preparation_id']; ?>_row_<?php echo $i; ?>">
												<input name="current_documents_<?php echo $row['calllog_preparation_id']; ?>[]" type="hidden" value="<?php echo $file; ?>" />
												<a href='<?php echo WEBSITE_URL . '/Cold Call/download/'.$file ?>'><?php echo $file; ?></a>
												<a href="" onclick="if(confirm('Are you sure you want to remove this file?')) {$('#prep_<?php echo $row['calllog_preparation_id']; ?>_row_<?php echo $i; ?>').remove();} return false;">(DELETE)</a><br>
											</div>
										<?php endif;
										$i++;
									endforeach; ?>
									<input name="new_documents_<?php echo $row['calllog_preparation_id']; ?>[]" multiple type="file" data-filename-placement="inside" class="form-control" />
								</div>
							</div>

							<div id="add_here_new_doc_<?php echo $row['calllog_preparation_id']; ?>"></div>

							<div class="form-group triple-gapped clearfix">
								<div class="col-sm-offset-4 col-sm-8">
									<button name="add_row_doc" data-id="<?php echo $row['calllog_preparation_id']; ?>" class="btn brand-btn pull-left">Add Another Document</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_new_prep" >
					New Preparation<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_new_prep" class="panel-collapse collapse in">
			<div class="panel-body">
				<div class="form-group">
					<label for="heading" class="col-sm-4 control-label">Heading:</label>
					<div class="col-sm-8">
						<input name="heading" type="text" value="<?php echo $heading ?>" class="form-control">
					</div>
					<label for="editor" class="col-sm-4 control-label">Text Editor Content:</label>
					<div class="col-sm-8">
						<textarea name="editor" <?php echo $disabled; ?> rows="3" cols="50" class="form-control"><?php echo $editor; ?></textarea>
					</div>
					<label for="upload_document" class="col-sm-4 control-label">Upload Document(s):
							<span class="popover-examples list-inline">&nbsp;
							<a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
							</span>
					</label>
					<div class="col-sm-8">
						<div class="enter_cost additional_doc_ clearfix">
							<div class="clearfix"></div>

							<div class="form-group clearfix">
								<input name="upload_document[]" multiple type="file" data-filename-placement="inside" class="form-control" />
							</div>

						</div>

						<div id="add_here_new_doc_"></div>

						<div class="form-group triple-gapped clearfix">
							<div class="col-sm-offset-4 col-sm-8">
								<button name="add_row_doc" data-id="" class="btn brand-btn pull-left">Add Another Document</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-4 clearfix">
		<a href="call_log.php" class="btn config-btn pull-right">Back</a>
		<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
	</div>
	<?php if($_GET['calllog_id'] == ''): ?>
		<div class="col-sm-8">
			<button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
		</div>
	<?php endif; ?>
</div>

</form>
</div>
</div>

<script type="text/javascript">
$('[name=add_row_doc]').on( 'click', function () {
	var id = $(this).data('id')
	var clone = $('.additional_doc_'+id+':last').clone();
	clone.find('.form-control').val('');
	clone.find('[id^=prep]').remove();
	$('#add_here_new_doc_'+id).append(clone);
	return false;
});
</script>
<?php include ('../footer.php'); ?>
