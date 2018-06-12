<?php error_reporting(0);
include_once('../include.php'); ?>
<script>
$(document).ready(function() {
	$('input').change(save_fields);
});
function save_fields() {
    <?php if(FOLDER_NAME == 'contacts') { ?>
    	var tile_name = $('[name="contacts_tile_name"]').val();
    	var tile_noun = $('[name="contacts_tile_noun"]').val();
    	var contacts_tile_name = tile_name+'#*#'+tile_noun;
    	$.ajax({
    		url: '../Contacts/contacts_ajax.php?action=general_config',
    		method: 'POST',
    		data: {
    			name: 'contacts_tile_name',
    			value: contacts_tile_name
    		}
    	});
    <?php } ?>
    var slider_layout = $('[name="<?= FOLDER_NAME ?>_slider_layout"]:checked').val();
    $.ajax({
        url: '../Contacts/contacts_ajax.php?action=general_config',
        method: 'POST',
        data: {
            name: '<?= FOLDER_NAME ?>_slider_layout',
            value: slider_layout
        }
    });
}
</script>
<div class="standard-dashboard-body-title">
    <h3>Settings - Tile:</h3>
</div>
<div class="standard-dashboard-body-content full-height">
    <div class="dashboard-item dashboard-item2 full-height">
        <div class="form-horizontal block-group block-group-noborder">
            <?php if(FOLDER_NAME == 'contacts') { ?>
                <div class="form-group">
                    <label class="col-sm-4">Tile Name:<br /><em>Enter the name you would like the Contacts tile to be labelled as.</em></label>
                    <div class="col-sm-8">
                        <input name="contacts_tile_name" type="text" value="<?= CONTACTS_TILE ?>" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4">Tile Noun:<br /><em>Enter the name you would like individual Contacts to be labelled as.</em></label>
                    <div class="col-sm-8">
                        <input name="contacts_tile_noun" type="text" value="<?= CONTACTS_NOUN ?>" class="form-control"/>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <label class="col-sm-4">Slider Default Layout:<br /><em>This is the default layout that will show up in slider windows.</em></label>
                <div class="col-sm-8">
                    <?php $contacts_slider_layout = get_config($dbc, FOLDER_NAME.'_slider_layout'); ?>
                    <label><input name="<?= FOLDER_NAME ?>_slider_layout" type="radio" value="full" <?= $contacts_slider_layout == 'full' ? 'checked' : '' ?>>Full View</label>
                    <label><input name="<?= FOLDER_NAME ?>_slider_layout" type="radio" value="accordion" <?= $contacts_slider_layout != 'full' ? 'checked' : '' ?>>Accordion View</label>
                </div>
            </div>
        </div>
    </div><!-- .dashboard-item -->
</div><!-- .standard-dashboard-body-content -->