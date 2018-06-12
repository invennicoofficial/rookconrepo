<!-- Lead Source -->
<script type="text/javascript">
$(document).ready(function() {
    $("#lead_source_businessid").change(function() {
		var businessid = this.value;
		if(businessid > 0) {
			$.ajax({
				type: "GET",
				url: "sales_ajax_all.php?fill=get_lead_source_contacts&businessid="+businessid,
				dataType: "html",
				success: function(response){
					$('#lead_source_contactid').html(response);
					$("#lead_source_contactid").trigger("change.select2");
				}
			});
		}
	});
});
function add_lead_source(type) {
    destroyInputs('.'+type);
    var block = $('.'+type).last();
    var clone = $(block).clone();

    clone.find('input').val('');
    clone.find('select').val('').trigger('change.select2');

    $(block).after(clone);
    initInputs('.'+type);
}
function remove_lead_source(btn, type) {
    if($('.'+type).length <= 1) {
        add_lead_source(type);
    }

    $(btn).closest('.'+type).remove();
}
</script>

<?php 
$lead_source_tabs = array_filter(explode(',',get_config($dbc, 'sales_lead_source')));
$lead_source = explode('#*#', $lead_source);
$lead_sources = [];
foreach($lead_source as $lead_source_type) {
    if(is_numeric($lead_source_type)) {
        if(get_contact($dbc, $lead_source_type, 'category') == 'Business') {
            $lead_sources['Business'][] = $lead_source_type;
        } else {
            $lead_sources['Contact'][] = $lead_source_type;
        }
    } else if(in_array($lead_source_type, $lead_source_tabs)) {
        $lead_sources['Dropdown'][] = $lead_source_type;
    } else {
        $lead_sources['Other'][] = $lead_source_type;
    }
}
if(empty($lead_sources['Business'])) {
    $lead_sources['Business'] = [''];
}
if(empty($lead_sources['Contact'])) {
    $lead_sources['Contact'] = [''];
}
if(empty($lead_sources['Dropdown'])) {
    $lead_sources['Dropdown'] = [''];
}
if(empty($lead_sources['Other'])) {
    $lead_sources['Other'] = [''];
}
if(strpos($value_config, ','."Lead Source Dropdown".',') === FALSE && strpos($value_config, ','."Lead Source Business".',') === FALSE && strpos($value_config, ','."Lead Source Contact".',') === FALSE && strpos($value_config, ','."Lead Source Other".',') === FALSE) {
    $value_config .= ",Lead Source Dropdown,Lead Source Business,Lead Source Contact,Lead Source Other,";
}
?>

<div class="accordion-block-details padded" id="leadsource">
    <div class="accordion-block-details-heading"><h4>Lead Source</h4></div>
    
    <?php if(strpos($value_config, ','."Lead Source Dropdown".',') !== FALSE) {
        foreach($lead_sources['Dropdown'] as $lead_source) { ?>
            <div class="row lead_source_dropdown">
                <div class="col-xs-12 col-sm-4 gap-md-left-15">Lead Source:</div>
                <div class="col-xs-12 col-sm-5">
                    <select data-placeholder="Choose a Lead Source..." name="lead_source[]" class="chosen-select-deselect form-control" width="380">
                        <option value=""></option>
                        <?php
                        foreach ($lead_source_tabs as $cat_tab) {
                            $selected = ($lead_source == $cat_tab) ? 'selected="selected"' : '';
                            echo '<option '. $selected .' value="'. $cat_tab .'">'. $cat_tab .'</option>';
                        } ?>
                    </select>
                </div>
                <div class="col-xs-12 col-sm-1">
                    <a href="" onclick="remove_lead_source(this, 'lead_source_dropdown'); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                    <a href="" onclick="add_lead_source('lead_source_dropdown'); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
                </div>
                <div class="clearfix"></div>
            </div><!-- .row -->
        <?php }
    } ?>
    
    <?php if(strpos($value_config, ','."Lead Source Business".',') !== FALSE) {
        foreach($lead_sources['Business'] as $lead_source) { ?>
            <div class="row lead_source_business">
                <div class="col-xs-12 col-sm-4 gap-md-left-15">Lead Source - Business:</div>
                <div class="col-xs-12 col-sm-5">
                    <select data-placeholder="Select a Business..." name="lead_source_businessid[]" class="chosen-select-deselect form-control">
                        <option value=""></option><?php
                        $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `name` FROM `contacts` WHERE `category` NOT IN (".STAFF_CATS.",'Sites') AND `category` = 'Business' AND `deleted`=0"), MYSQLI_ASSOC));
                        foreach($query as $id) {
                            echo '<option '. ($id==$lead_source ? 'selected' : '') .' value="'. $id .'">'. get_client($dbc, $id) .'</option>';
                        } ?>
                    </select>
                </div>
                <div class="col-xs-12 col-sm-1">
                    <a href="" onclick="remove_lead_source(this, 'lead_source_business'); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                    <a href="" onclick="add_lead_source('lead_source_business'); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
                </div>
                <div class="clearfix"></div>
            </div><!-- .row -->
        <?php }
    } ?>
    
    <?php if(strpos($value_config, ','."Lead Source Contact".',') !== FALSE) {
        foreach($lead_sources['Contact'] as $lead_source) { ?>
            <div class="row lead_source_contact">
                <div class="col-xs-12 col-sm-4 gap-md-left-15">Lead Source - Contact:</div>
                <div class="col-xs-12 col-sm-5">
                    <select data-placeholder="Select a Contact..." name="lead_source_contactid[]" class="chosen-select-deselect form-control">
                        <option value=""></option><?php
                        $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` NOT IN (".STAFF_CATS.",'Business') AND `deleted`=0 AND `status`>0"), MYSQLI_ASSOC));
                        foreach($query as $id) {
                            if ( get_contact($dbc, $id) != '-' ) {
                                echo '<option '. ($id==$lead_source ? 'selected' : '') .' value="'. $id .'">'. get_contact($dbc, $id) .'</option>';
                            }
                        } ?>
                    </select>
                </div>
                <div class="col-xs-12 col-sm-1">
                    <a href="" onclick="remove_lead_source(this, 'lead_source_contact'); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                    <a href="" onclick="add_lead_source('lead_source_contact'); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
                </div>
                <div class="clearfix"></div>
            </div><!-- .row -->
        <?php }
    } ?>
    
    <?php if(strpos($value_config, ','."Lead Source Other".',') !== FALSE) {
        foreach($lead_sources['Other'] as $lead_source) { ?>
            <div class="row lead_source_other">
                <div class="col-xs-12 col-sm-4 gap-md-left-15">Lead Source - Other:</div>
                <div class="col-xs-12 col-sm-5">
                    <input type="text" name="lead_source_other[]" value="<?= $lead_source; ?>" placeholder="Enter Lead Source..." class="form-control" />
                </div>
                <div class="col-xs-12 col-sm-1">
                    <a href="" onclick="remove_lead_source(this, 'lead_source_other'); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                    <a href="" onclick="add_lead_source('lead_source_other'); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
                </div>
                <div class="clearfix"></div>
            </div><!-- .row -->
        <?php }
    } ?>
    
</div><!-- .accordion-block-details -->