<?php include_once('../include.php');
$template = get_config($dbc, 'ticket_log_template');
if(isset($_GET['template'])) {
	$template = $_GET['template'];
} ?>
<script type="text/javascript">
function setTicketTemplate() {
	var template = $('#template').val();
	$.ajax({
		url: '../Ticket/ticket_ajax_all.php?action=ticket_log_template',
		method: 'POST',
		data: { template: template },
		success: function(response) {

		}
	});
}
</script>
<div class="clearfix"></div>
<div class="form-group">
	<label for="fax_number"	class="col-sm-4	control-label">Template:</label>
	<div class="col-sm-8">
		<select data-placeholder="Select a Template..." id="template" name="template" class="chosen-select-deselect form-control" onchange="setTicketTemplate(); location='?settings=ticket_log&template='+this.value">
		  <option value=""></option>
		  <option <?php echo "template_a" == $template ? "selected " : ""; ?>value="template_a">Template A</option>
		</select>
	</div>
</div>

<?php if(!empty($template)) { ?>
	<div style="width: 100%; text-align: center;">
		<iframe id="preview_template" style="width: 300px; height: 400px;" src="../Ticket/ticket_log_templates/<?= $template ?>_pdf.php?preview_template=true" type="application/pdf"></iframe>
	</div>
	<div class="clearfix"></div>
	<?php include('ticket_log_templates/'.$template.'.php'); ?>
<?php } ?>