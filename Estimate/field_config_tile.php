<?php error_reporting(0);
include_once('../include.php');

if (isset($_POST['submit'])) {
	$estimate_tile = $_POST['estimate_tile'];
    set_config($dbc, 'estimate_tile_name', $estimate_tile);
}

?>
<script>

</script>

<form class="form-horizontal margin-vertical margin-horizontal" action="" method="POST" enctype="multipart/form-data">

<h3>Estimate Tile Name</h3>
<div class="form-group type-option">
	<label class="col-sm-4">Tile Name:<br /><em>Enter the name you would like the Estimate tile to be labelled as.</em></label>
	<div class="col-sm-8">
		<input type="text" name="estimate_tile" class="form-control" value="<?= ESTIMATE_TILE ?>">
	</div>
</div>

    <div class="form-group">
        <div class="col-sm-4">
        </div>
        <div class="col-sm-8">
            <button type="submit" name="submit" value="submit" class="btn brand-btn pull-right">Submit</button>
        </div>
    </div>

</form>

<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'field_config_tile.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>