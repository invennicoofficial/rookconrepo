<?php error_reporting(0);
include_once('../include.php');
$folder = FOLDER_NAME;
if(!empty($_POST['folder'])) {
	$folder = $_POST['folder'];
}
$current_type = $_GET['type'];
if(!empty($_POST['type'])) {
	$current_type = $_POST['type'];
} ?>
<script>
function change_type(type_name) {
	<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'field_config_id_card_fields.php') { ?>
		contact_type = type_name;
        $.ajax({
            url: '../Contacts/field_config_id_card_fields.php',
            data: { folder: '<?= FOLDER_NAME ?>', type: contact_type },
            method: 'POST',
            response: 'html',
            success: function(response) {
                $(type_name).closest('.panel-body').html(response);
            }
        });
	<?php } else { ?>
		window.location.href = 'contacts_inbox.php?settings=id_card_fields&type='+type_name;
	<?php } ?>
}
function save_options() {
	var field_list = [];
	$('[name="contacts_id_card[]"]:checked').each(function() {
		field_list.push(this.value);
	});
    field_list = field_list.join(',');
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=general_config',
		method: 'POST',
		data: { name: '<?= config_safe_str($current_type) ?>_id_card_fields', value: field_list },
		response: 'html',
		success: function(response) {
			console.log(response);
		}
	});
}
$(document).ready(function() {
	$('input[type=checkbox]:checked').closest('.form-group').find('[name="accordion_option[]"]').prop('checked','checked');
	$('input[type=checkbox]:checked').closest('.block-group').show();
});
$(document).on('change', 'select[name="contact_type"]', function() { change_type(this.value); });
</script>
<div class="standard-dashboard-body-title">
    <h3>Settings - ID Card Fields</h3>
</div>
<div class="standard-dashboard-body-content full-height">
    <div class="dashboard-item dashboard-item2 full-height">
        <form class="form-horizontal">
        <div class="form-group block-group block-group-noborder">
            <div class="form-group">
                <label class="col-sm-4 control-label">Contact Type:</label>
                <div class="col-sm-8">
                    <select name="contact_type" data-placeholder="Select a Contact Type" class="chosen-select-deselect">
                        <?php $contact_types = explode(',', get_config($dbc, $folder."_tabs"));
                        $staff = array_search('Staff',$contact_types);
                        if($staff !== FALSE) {
                            unset($contact_types[$staff]);
                        }
                        foreach($contact_types as $type_name) {
                            if($current_type == '') {
                                $current_type = $type_name;
                            }
                            echo "<option ".($current_type == $type_name ? 'selected' : '')." value='$type_name'>$type_name</option>";
                        }

                        $id_card_fields = explode(',',get_config($dbc, config_safe_str($current_type).'_id_card_fields'));
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Fields:</label>
                <div class="col-sm-8">
                    <div class="block-group">
                        <label class="form-checkbox"><input type="checkbox" <?= in_array('Ticket Service Total Hours', $id_card_fields) ? 'checked' : '' ?> name="contacts_id_card[]" value="Ticket Service Total Hours" onchange="save_options();"><?= TICKET_NOUN ?> Service Total Hours</label>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div><!-- .dashboard-item -->
</div><!-- .standard-dashboard-body-content -->
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'contacts_dashboard_config_fields.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>