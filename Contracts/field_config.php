<?php
include ('../include.php');
checkAuthorised('contracts');
error_reporting(0);

if (isset($_POST['submit'])) {
	$contract_tabs = filter_var(implode('#*#',array_filter($_POST['contract_tabs'])),FILTER_SANITIZE_STRING);
	$header_text = filter_var(htmlentities($_POST['header_text']),FILTER_SANITIZE_STRING);
	$footer_text = filter_var(htmlentities($_POST['footer_text']),FILTER_SANITIZE_STRING);
	
	//Save Header Logo
	$basename = $_FILES['header_logo']['name'];
	if($basename != '') {
		if (!file_exists('download')) {
			mkdir('download', 0777, true);
		}
		$basename = $header_logo = preg_replace('/[^A-Za-z0-9\.]/','_',$basename);
		$i = 0;
		while(file_exists('download/'.$header_logo)) {
			$header_logo = preg_replace('/(\.[A-Za-z0-9]*)/', '('.++$i.')$1', $basename);
		}
		if(!move_uploaded_file($_FILES['header_logo']['tmp_name'], 'download/'.$header_logo)) {
			echo "Error Saving Attachment: ".$header_logo."\n";
		}
	} else {
		$header_logo = filter_var($_POST['header_logo_file'],FILTER_SANITIZE_STRING);
	}
	//Save Footer Logo
	$basename = $_FILES['footer_logo']['name'];
	if($basename != '') {
		if (!file_exists('download')) {
			mkdir('download', 0777, true);
		}
		$basename = $footer_logo = preg_replace('/[^A-Za-z0-9\.]/','_',$basename);
		$i = 0;
		while(file_exists('download/'.$footer_logo)) {
			$footer_logo = preg_replace('/(\.[A-Za-z0-9]*)/', '('.++$i.')$1', $basename);
		}
		if(!move_uploaded_file($_FILES['footer_logo']['tmp_name'], 'download/'.$footer_logo)) {
			echo "Error Saving Attachment: ".$footer_logo."\n";
		}
	} else {
		$footer_logo = filter_var($_POST['footer_logo_file'],FILTER_SANITIZE_STRING);
	}

	mysqli_query($dbc,"INSERT INTO `field_config_contracts` (`contract_tabs`) SELECT 'contract_tabs' FROM (SELECT COUNT(*) rows FROM `field_config_contracts`) NUM WHERE `rows`=0");
	$query_update = "UPDATE `field_config_contracts` SET `contract_tabs`='$contract_tabs', `header_text`='$header_text', `header_logo`='$header_logo', `footer_text`='$footer_text', `footer_logo`='$footer_logo'";
	$result = mysqli_query($dbc, $query_update);
	
	echo '<script type="text/javascript"> window.location.replace("'.$url.'"); </script>';
}
?>
<style>
label input[type=checkbox] {
	height: 1.5em;
	width: 1.5em;
}
</style>
<script>
function addTabRow(btn) {
	var clone = $('input[name="contract_tabs[]"][type=text]').last().clone();
	clone.val('');
	$(btn).before(clone);
	$(btn).before('<br />');
	$('input[name="contract_tabs[]"][type=text]').last().focus();
}
</script>
</head>
<body>
<?php include('../navigation.php');
$config = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contract_tabs`, `header_logo`, `header_text`, `footer_logo`, `footer_text` FROM `field_config_contracts`
	UNION SELECT 'Follow Up#*#Reporting#*#Customer', '', '', '', ''")); ?>

<div class="container">
	<div class="row">
		<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

		<h2>Contract Settings</h2>
		<span class="popover-examples list-inline pull-left"><a data-toggle="tooltip" data-placement="top" title="Click here to discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="contracts.php" class="btn brand-btn pull-left">Back to Dashboard</a>
		<div class="clearfix"></div><br />

		<div class="panel-group" id="accordion2">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tabs" >
							Contract Tabs<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				
				<div id="collapse_tabs" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="form-group" style='border:solid 1px black;'>
							<?php $contract_tabs = explode('#*#', $config['contract_tabs']); ?>
							<label class="col-sm-12">Select the default tabs that you wish to appear:</label>
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><input type="checkbox" <?= (in_array('Follow Up',$contract_tabs) ? 'checked' : '') ?> value="Follow Up" name="contract_tabs[]"> Follow Up</label>
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><input type="checkbox" <?= (in_array('Reporting',$contract_tabs) ? 'checked' : '') ?> value="Reporting" name="contract_tabs[]"> Reporting</label>
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><input type="checkbox" <?= (in_array('Customer',$contract_tabs) ? 'checked' : '') ?> value="Customer" name="contract_tabs[]"> Customer</label>
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><input type="checkbox" <?= (in_array('Vendor',$contract_tabs) ? 'checked' : '') ?> value="Vendor" name="contract_tabs[]"> Vendor</label>
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><input type="checkbox" <?= (in_array('Contractor',$contract_tabs) ? 'checked' : '') ?> value="Contractor" name="contract_tabs[]"> Contractor</label>
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><input type="checkbox" <?= (in_array('Therapist',$contract_tabs) ? 'checked' : '') ?> value="Therapist" name="contract_tabs[]"> Therapist</label>
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><input type="checkbox" <?= (in_array('Templates',$contract_tabs) ? 'checked' : '') ?> value="Templates" name="contract_tabs[]"> Templates</label>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Enter additional tab names:</label>
							<div class="col-sm-8">
								<?php $contract_tabs = array_filter($contract_tabs, function($val) {
									return ($val != '' && $val != 'Customer' && $val != 'Vendor' && $val != 'Contractor' && $val != 'Therapist' && $val != 'Templates' && $val != 'Follow Up' && $val != 'Reporting');
								});
								foreach($contract_tabs as $tab_name) {
									echo "<input type='text' name='contract_tabs[]' value='$tab_name' class='form-control'><br />";
								} ?>
								<input type="text" name="contract_tabs[]" value="" class='form-control'><br />
								<button onclick="addTabRow(this); return false;" class="btn brand-btn pull-right">Add Tab</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pdf" >
							PDF Settings<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				
				<div id="collapse_pdf" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="form-group">
							<label for="header_logo" class="col-sm-4 control-label">Header Logo:</label>
							<div class="col-sm-8">
								<input type="hidden" name="header_logo_file" value="<?php echo $config['header_logo']; ?>" />
								<?php if($config['header_logo'] != '') {
									echo "<a href='download/".$config['header_logo']."' target='_blank'>View Logo</a>";
								} ?>
								<input name="header_logo" type="file" data-filename-placement="inside" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label for="header_text" class="col-sm-4 control-label">Header Information:<br /><em>(e.g. - company name, address, phone, etc.)<br />You can also use the following variables:<br />[YEAR]: Current Year<br />They will populate when the document is generated.</em></label>
							<div class="col-sm-8">
								<textarea name="header_text" type="text" class="form-control"><?= $config['header_text'] ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="footer_logo" class="col-sm-4 control-label">Footer Logo:</label>
							<div class="col-sm-8">
								<input type="hidden" name="footer_logo_file" value="<?php echo $config['footer_logo']; ?>" />
								<?php if($config['footer_logo'] != '') {
									echo "<a href='download/".$config['footer_logo']."' target='_blank'>View Logo</a>";
								} ?>
								<input name="footer_logo" type="file" data-filename-placement="inside" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label for="footer_text" class="col-sm-4 control-label">Footer Information:<br /><em>(e.g. - company name, address, phone, etc.)<br />You can also use the following variables:<br />[YEAR]: Current Year<br />They will populate when the document is generated.</em></label>
							<div class="col-sm-8">
								<textarea name="footer_text" type="text" class="form-control"><?= $config['footer_text'] ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<span class="popover-examples list-inline pull-left"><a data-toggle="tooltip" data-placement="top" title="Click here to discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <a href="contracts.php" class="btn brand-btn btn-lg pull-left">Back</a>
			<button	type="submit" name="submit"	value="submit" class="btn brand-btn btn-lg pull-right">Submit</button>
			<span class="popover-examples list-inline pull-right"><a data-toggle="tooltip" data-placement="top" title="Click here to save your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		</div>

		</form>
	</div>
</div>
<?php include('../footer.php'); ?>