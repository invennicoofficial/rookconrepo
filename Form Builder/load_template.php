<?php include_once('../include.php');
checkAuthorised('form_builder');
if(isset($_POST['load_template'])) {
    $history = '';
    $templateid = $_POST['templateid'];
	$formid = $_POST['formid'];
    if (!empty($formid)) {
    	mysqli_query($dbc, "UPDATE `user_forms` u, `user_forms` t SET u.`contents` = t.`contents`, u.`header` = t.`header`, u.`header_logo` = t.`header_logo`, u.`header_align` = t.`header_align`, u.`header_font` = t.`header_font`, u.`header_size` = t.`header_size`, u.`header_color` = t.`header_color`, u.`footer` = t.`footer`, u.`footer_logo` = t.`footer_logo`, u.`footer_align` = t.`footer_align`, u.`footer_font` = t.`footer_font`, u.`footer_size` = t.`footer_size`, u.`footer_color` = t.`footer_color`, u.`body_heading_font` = t.`body_heading_font`, u.`body_heading_size` = t.`body_heading_size`, u.`body_heading_color` = t.`body_heading_color`, u.`section_heading_font` = t.`section_heading_font`, u.`section_heading_size` = t.`section_heading_size`, u.`section_heading_color` = t.`section_heading_color`, u.`font` = t.`font`, u.`body_size` = t.`body_size`, u.`body_color` = t.`body_color`, u.`display_form` = t.`display_form`, u.`advanced_styling` = t.`advanced_styling`, u.`intake_field` = t.`intake_field`, u.`page_by_page` = t.`page_by_page`, u.`hide_labels` = t.`hide_labels`, u.`header_styling` = t.`header_styling`, u.`footer_styling` = t.`footer_styling`, u.`section_heading_styling` = t.`section_heading_styling`, u.`body_heading_styling` = t.`body_heading_styling`, u.`body_styling` = t.`body_styling` WHERE u.`form_id` = '$formid' AND t.`form_id` = '$templateid'");
    }
        $date_of_archival = date('Y-m-d');
    mysqli_query($dbc, "UPDATE `user_form_fields` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `form_id` = '$formid'");

    $template_fields = mysqli_query($dbc, "SELECT * FROM `user_form_fields` WHERE `form_id` = '$templateid' AND `deleted` = 0");
    while ($row = mysqli_fetch_array($template_fields)) {
    	if(!empty($_POST['form_fields'][$row['field_id']])) {
    		$field_id = $row['field_id'];
    		mysqli_query($dbc, "INSERT INTO `user_form_fields` (`form_id`,`name`,`label`,`type`,`default`,`references`,`totaled`,`source_table`,`source_conditions`,`sort_order`,`content`,`styling`,`mandatory`) SELECT '$formid',`name`,`label`,`type`,`default`,`references`,`totaled`,`source_table`,`source_conditions`,`sort_order`,`content`,`styling`,`mandatory` FROM `user_form_fields` WHERE `field_id` = '$field_id'");
    		$template_option_fields = mysqli_query($dbc, "SELECT * FROM `user_form_fields` WHERE `form_id` = '$templateid' AND `type` = 'OPTION' AND `name` = '".$row['name']."'");
	        while ($row2 = mysqli_fetch_array($template_option_fields)) {
	    		$field_id2 = $row2['field_id'];
	    		mysqli_query($dbc, "INSERT INTO `user_form_fields` (`form_id`,`name`,`label`,`type`,`default`,`references`,`totaled`,`source_table`,`source_conditions`,`sort_order`,`content`,`styling`,`mandatory`) SELECT '$formid',`name`,`label`,`type`,`default`,`references`,`totaled`,`source_table`,`source_conditions`,`sort_order`,`content`,`styling`,`mandatory` FROM `user_form_fields` WHERE `field_id` = '$field_id2'");
	        }
    	}
    }
        $date_of_archival = date('Y-m-d');

    mysqli_query($dbc, "UPDATE `user_form_page` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `form_id` = '$formid'");
    $template_pages = mysqli_query($dbc, "SELECT * FROM `user_form_page` WHERE `form_id` = '$templateid' AND `deleted` = 0");
    while ($row = mysqli_fetch_array($template_pages)) {
        $page_id = $row['page_id'];
        mysqli_query($dbc, "INSERT INTO `user_form_page` (`form_id`,`page`,`img`) SELECT '$formid',`page`,`img` FROM `user_form_page` WHERE `page_id` = '$page_id'");
        $new_page_id = mysqli_insert_id($dbc);
        $template_page_details = mysqli_query($dbc, "SELECT * FROM `user_form_page_detail` WHERE `page_id` = '$page_id' AND `deleted` = 0");
        while ($row2 = mysqli_fetch_array($template_page_details)) {
            $page_detail_id = $row2['page_detail_id'];
            mysqli_query($dbc, "INSERT INTO `user_form_page_detail` (`page_id`,`field_name`,`field_label`,`top`,`left`,`width`,`height`,`white_space`) SELECT '$new_page_id',`field_name`,`field_label`,`top`,`left`,`width`,`height`,`white_space` FROM `user_form_page_detail` WHERE `page_detail_id` = '$page_detail_id'");
        }
    }
    echo '<script>
            alert("Template loaded successfully.");
            window.top.location.href = "'. WEBSITE_URL .'/Form Builder/edit_form.php?edit='.$formid.'";
        </script>';
}

$formid = $_GET['formid'];
?>
<script type="text/javascript">
$(document).ready(function() {
	$('#templateid').change(function() {
		loadTemplate(this);
	});
});
function loadTemplate(sel) {
	$('.template_block').html('Loading...');
	var templateid = sel.value;
	if(templateid > 0) {
		$.ajax({
			type: 'GET',
			url: '../Form Builder/load_template_inc.php?templateid='+templateid,
			dataType: 'html',
			success: function(response) {
				destroyInputs();
				$('.template_block').html(response);
				$('#load_template').show();
				initInputs();
				$('#templateid').change(function() {
					loadTemplate(this);
				});
			}
		});
	} else {
		$('.template_block').html('No Template Selected.');
		$('#load_template').hide();
	}
}
</script>
<div class="padded">
	<h3>Load Template</h3>
	<div class="block-group" style="height: calc(100% - 8em); overflow-y: auto;">
		<form class="form-horizontal" action="" method="post">
			<input type="hidden" name="formid" value="<?= $formid ?>">
			<div class="form-group">
				<label class="col-sm-3">Template:</label>
				<div class="col-sm-9">
					<select name="templateid" id="templateid" class="chosen-select-deselect">
						<option></option><?php
		        		$template_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `user_forms` WHERE `deleted` = 0 AND `is_template` = 1"),MYSQLI_ASSOC);
		        		foreach ($template_list as $template) {
		        			echo '<option value="'.$template['form_id'].'">'.$template['name'].'</option>';
		        		} ?>
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			<hr>
			<div class="template_block">
				No Template Selected.
			</div>
			<div class="clearfix"></div>
			<div class="pull-right gap-top gap-right">
			    <a href="?" class="btn brand-btn">Cancel</a>
			    <button type="submit" id="load_template" name="load_template" value="Submit" class="btn brand-btn" onclick="return confirm('WARNING: Loading a Template into this Form will overwrite all existing Settings and Fields in this Form. Are you sure you want to load this Template? Pressing OK will reload the page and load all Settings and Fields from the Template into this Form.');" style="display: none;">Submit</button>
			</div>
		</form>
	</div>
</div>