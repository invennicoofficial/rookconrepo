<?php include_once('../include.php');
checkAuthorised('staff');
if(isset($_GET['template'])) {
	$template = $_GET['template'];
} ?>

<div class="clearfix"></div>
<div class="form-group">
	<label for="fax_number"	class="col-sm-4	control-label">Template:</label>
	<div class="col-sm-8">
		<select data-placeholder="Select a Template..." id="template" name="template" class="chosen-select-deselect form-control" onchange="location='?settings=bus_card&template='+this.value">
		  <option value=""></option>
		  <option <?php echo "template_a" == $template ? "selected " : ""; ?>value="template_a">Template A</option>
		</select>
	</div>
</div>

<?php if(isset($_GET['template'])) { ?>
	<div style="width: 100%; text-align: center;"><iframe style="width: 300px; height: 400px" src="../Staff/business_card_templates/<?= $template ?>_pdf.php?preview_template=true" type="application/pdf"></iframe></div>
	<h3>Choose Fields</h3>
	<?php include('business_card_templates/'.$template.'.php'); ?>
<?php } ?>