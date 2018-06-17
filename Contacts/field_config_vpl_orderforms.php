<?php
include_once('../include.php');
checkAuthorised('vpl');
if (isset($_POST['inv_orderforms'])) {
	set_config($dbc, 'show_orderforms_vpl', $_POST['show_orderforms_vpl']);
	set_config($dbc, 'vpl_orderforms_fields', filter_var(implode(',',$_POST['vpl_orderforms_fields']),FILTER_SANITIZE_STRING));
}
?>
<div class="standard-body-title">
    <h3>Vendor Price List Settings - Order Forms</h3>
</div>
<div class="standard-body-content full-height">
    <div class="dashboard-item dashboard-item2 full-height">
		<form id="form1" name="form1" method="post"	enctype="multipart/form-data" class="form-horizontal" role="form">
			<div class="form-group">
	            <label for="fax_number"	class="col-sm-4	control-label"><span class="popover-examples list-inline"><a class="" style="margin:7px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="The Order Forms functionality allows users to order from a Vendor Price List, which creates a pending Purchase Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Enable Order Forms:</label>
	            <div class="col-sm-8">
	            	<?php $show_orderforms_vpl = get_config($dbc, 'show_orderforms_vpl'); ?>
	            	<label class="form-checkbox"><input type='checkbox' name='show_orderforms_vpl' class='show_orderforms_vpl' value='1' <?= $show_orderforms_vpl == 1 ? 'checked' : '' ?>></label>
	            </div>
	        </div>

    		<?php $vpl_orderforms_fields = ','.get_config($dbc, 'vpl_orderforms_fields').','; ?>

	        <div class="form-group">
	        	<label class="col-sm-4 control-label">Fields:</label>
	        	<div class="col-sm-8">
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Project" <?= strpos($vpl_orderforms_fields, ',Project,') !== FALSE ? 'checked' : '' ?>> <?= PROJECT_NOUN ?></label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Ticket" <?= strpos($vpl_orderforms_fields, ',Ticket,') !== FALSE ? 'checked' : '' ?>> <?= TICKET_NOUN ?></label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Business" <?= strpos($vpl_orderforms_fields, ',Business,') !== FALSE ? 'checked' : '' ?>> <?= BUSINESS_CAT ?></label>
	        	</div>
	        </div>

	        <div class="form-group">
	        	<label class="col-sm-4 control-label">Vendor Price List Fields:</label>
	        	<div class="col-sm-8">
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Category" <?= strpos($vpl_orderforms_fields, ',Category,') !== FALSE ? 'checked' : '' ?>> Category</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Part #" <?= strpos($vpl_orderforms_fields, ',Part #,') !== FALSE ? 'checked' : '' ?>> Part #</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Name" <?= strpos($vpl_orderforms_fields, ',Name,') !== FALSE ? 'checked' : '' ?>> Name</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Price" <?= strpos($vpl_orderforms_fields, ',Price,') !== FALSE ? 'checked' : '' ?>> Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Quantity" <?= strpos($vpl_orderforms_fields, ',Quantity,') !== FALSE ? 'checked' : '' ?>> Quantity</label>
	        	</div>
	        </div>

	        <div class="form-group">
	        	<label class="col-sm-4 control-label">Pricing Options:</label>
	        	<div class="col-sm-8">
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Client Price" <?= strpos($vpl_orderforms_fields, ',Client Price,') !== FALSE ? 'checked' : '' ?>> Client Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Admin Price" <?= strpos($vpl_orderforms_fields, ',Admin Price,') !== FALSE ? 'checked' : '' ?>> Admin Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Commercial Price" <?= strpos($vpl_orderforms_fields, ',Commercial Price,') !== FALSE ? 'checked' : '' ?>> Commercial Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Wholesale Price" <?= strpos($vpl_orderforms_fields, ',Wholesale Price,') !== FALSE ? 'checked' : '' ?>> Wholesale Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Final Retail Price" <?= strpos($vpl_orderforms_fields, ',Final Retail Price,') !== FALSE ? 'checked' : '' ?>> Final Retail Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Preferred Price" <?= strpos($vpl_orderforms_fields, ',Preferred Price,') !== FALSE ? 'checked' : '' ?>> Preferred Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Purchase Order Price" <?= strpos($vpl_orderforms_fields, ',Purchase Order Price,') !== FALSE ? 'checked' : '' ?>> Purchase Order Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Sales Order Price" <?= strpos($vpl_orderforms_fields, ',Sales Order Price,') !== FALSE ? 'checked' : '' ?>> Sales Order Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Web Price" <?= strpos($vpl_orderforms_fields, ',Web Price,') !== FALSE ? 'checked' : '' ?>> Web Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Drum Unit Cost" <?= strpos($vpl_orderforms_fields, ',Drum Unit Cost,') !== FALSE ? 'checked' : '' ?>> Drum Unit Cost</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Drum Unit Price" <?= strpos($vpl_orderforms_fields, ',Drum Unit Price,') !== FALSE ? 'checked' : '' ?>> Drum Unit Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Tote Unit Cost" <?= strpos($vpl_orderforms_fields, ',Tote Unit Cost,') !== FALSE ? 'checked' : '' ?>> Tote Unit Cost</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Tote Unit Price" <?= strpos($vpl_orderforms_fields, ',Tote Unit Price,') !== FALSE ? 'checked' : '' ?>> Tote Unit Price</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="Average Cost" <?= strpos($vpl_orderforms_fields, ',Average Cost,') !== FALSE ? 'checked' : '' ?>> Average Cost</label>
	        		<label class="form-checkbox"><input type="checkbox" name="vpl_orderforms_fields[]" value="USD Cost Per Unit" <?= strpos($vpl_orderforms_fields, ',USD Cost Per Unit,') !== FALSE ? 'checked' : '' ?>> USD Cost Per Unit</label>
	        	</div>
	        </div>

	        <div class="form-group pull-right">
	                <a href="?" class="btn brand-btn">Back</a>
	                <button	type="submit" name="inv_orderforms" value="inv_orderforms" class="btn brand-btn">Submit</button>
	            </div>
	        </div>

			<div class="clearfix"></div>
        </form>
    </div>
</div>