<?php
/*
Dashboard
*/
?>
<?php if($_POST['submit_tab']): ?>
	<?php
	$tabNames = explode(',', $_POST['estimate_tab_name']);
	foreach($tabNames as $tabName) {
		$tab .= "'" . $tabName . "',";
	}

	$tab = rtrim($tab, ",");
	$get_current_tabs = mysqli_fetch_all(mysqli_query($dbc,"delete FROM bid_tab where estimate_tab NOT IN (" . $tab . ")"));
	foreach($tabNames as $tabName) {
		$get_tabs = mysqli_fetch_assoc(mysqli_query($dbc,"select estimate_tab FROM bid_tab where estimate_tab = '" . $tabName . "'"));
		if(empty($get_tabs)) {
			$query_insert_tab = "INSERT INTO `bid_tab` (`estimate_tab`) VALUES ('" . $tabName . "')";
			mysqli_query($dbc, $query_insert_tab);
		}
	}

	?>
<?php endif; ?>
<div class="pad-left">
	<?php $_tab_1 = ''; $_tab_2 = ''; ?>
	<?php if($_GET['tab'] == 1): ?>
		<?php $_tab_1 = 'active_tab'; ?>
	<?php elseif($_GET['tab'] == 2): ?>
		<?php $_tab_2 = 'active_tab'; ?>
	<?php endif; ?>
	<a href='?tab=1'><button type="button" class="btn brand-btn mobile-block <?php echo $_tab_1 ?>" >Add Bid Tabs</button></a>
	<a href='?tab=2'><button type="button" class="btn brand-btn mobile-block <?php echo $_tab_2 ?>" >Configure Bid Tabs</button></a>
</div>
<br><br>
<?php if($_GET['tab'] == 1): ?>
<form action='' method='POST'>
	<div class="form-group">
		<?php $get_tabs = mysqli_fetch_all(mysqli_query($dbc,"select estimate_tab FROM bid_tab")); ?>
		<?php foreach($get_tabs as $get_tab): ?>
			<?php $get_implode_tabs[] = $get_tab[0]; ?>
		<?php endforeach; ?>
		<?php $get_implode_tab = implode(',', $get_implode_tabs); ?>
		<label for="first_name" class="col-sm-1 control-label text-left">Tabs<span class="brand-color">*</span>:</label>
		<div class="col-sm-8">
			<input name="estimate_tab_name" id="estimate_tab_name" value='<?php echo $get_implode_tab; ?>' type="text" class="form-control"><p></p>
		</div>
	</div>
    
	<div class="form-group double-gap-top">
		<div class="col-sm-6"><a href="estimate.php" class="btn config-btn btn-lg">Back</a></div>
		<div class="col-sm-6"><button	type="submit" name="submit_tab"	value="submit_tab" class="btn config-btn btn-lg	pull-right">Submit</button></div>
		</div>
		<div class="clearfix"></div>
	</div>
</form>
<?php elseif($_GET['tab'] == 2): ?>
	<?php include('tab_estimate_config.php') ?>
<?php endif; ?>