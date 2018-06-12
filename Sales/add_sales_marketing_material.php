<?php
error_reporting(0);
?>
<script type="text/javascript">
function selectMaterialHeading(sel) {
	var maketingid = sel.value;
	var id = sel.id;
	var arr = id.split('_');
	var pdf_show = '#pdfshow_' + arr[1];
	var mcustomers_show = '#mcustomers_' + arr[1];
	jQuery(mcustomers_show).css('display','none');
	$.ajax({
		type: "GET",
		url: "sales_ajax_all.php?fill=material_heading&maketingid="+maketingid,
		dataType: "html",   //expect html to be returned
		success: function(response){
			jQuery(pdf_show).html(response);
			if(response != '' && response != null) {
				jQuery(mcustomers_show).css('display','block');
				if ($("#msend_"+arr[1]).length) {
					$("#msend_"+arr[1]).hide();
				}
			}
		}
	});
}

$(document).ready(function() {

	$('.send_cancel').click(
    function() {
        var id = $(this).val();
        $('.approve-box-'+id).hide();
        $('.getemailsapprove').val('');
    });

	//Materials
    var add_new_m = 1;
    $('#deletemm_0').hide();
    $('#add_row_m').on( 'click', function () {
        $('#deletemm_0').show();
        var clone = $('.additional_m').clone();
        clone.find('.form-control').val('');

        clone.find('#mmaterial_0').attr('id', 'mmaterial_'+add_new_m);
		clone.find('#mcategory_0').attr('id', 'mcategory_'+add_new_m);
        clone.find('#mheading_0').attr('id', 'mheading_'+add_new_m);
		clone.find('#mcustomers_0').attr('id', 'mcustomers_'+add_new_m);
		clone.find('#mcustomer_0').attr('id', 'mcustomer_'+add_new_m);
		
		clone.find('#pdfshow_0').attr('id', 'pdfshow_'+add_new_m).html('');

        clone.find('#mm_0').attr('id', 'mm_'+add_new_m);
        clone.find('#deletemm_0').attr('id', 'deletemm_'+add_new_m);
        $('#deletemm_0').hide();

        clone.removeClass("additional_m");
        $('#add_here_new_m').append(clone);

        resetChosen($("#mmaterial_"+add_new_m));
        resetChosen($("#mcategory_"+add_new_m));
        resetChosen($("#mheading_"+add_new_m));
		resetChosen($("#mcustomer_"+add_new_m));

        add_new_m++;

        return false;
    });
});
$(document).on('change', 'select.mmat_type_onchange', function() { selectMaterialMaterial(this); });
$(document).on('change', 'select.mmat_cat_onchange', function() { selectMaterialCat(this); });
$(document).on('change', 'select[name="marketingmaterialid[]"]', function() { selectMaterialHeading(this); });
function selectMaterialMaterial(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "sales_ajax_all.php?fill=m_material_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#mcategory_"+arr[1]).html(response);
			$("#mcategory_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectMaterialCat(sel) {
	var stage = encodeURIComponent(sel.value);
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "sales_ajax_all.php?fill=m_cat_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#mheading_"+arr[1]).html(response);
			$("#mheading_"+arr[1]).trigger("change.select2");
		}
	});
}

function seleteMaterial(sel, hide, blank) {
	var typeId = sel.id;
	var arr = typeId.split('_');

    $("#"+hide+arr[1]).hide();
    $("#"+blank+arr[1]).val('');

}

function approvebutton(sel) {
	var status = sel.id;
	$(".approve-box-"+status).show();
	return false;
}


</script>

<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
            <?php if (strpos($value_config, ','."Marketing Material Material Type".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Material Type</label>
            <?php } ?>
            <?php if (strpos($value_config, ','."Marketing Material Category".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Category</label>
            <?php } ?>
            <?php if (strpos($value_config, ','."Marketing Material Heading".',') !== FALSE) { ?>
            <label class="col-sm-3 text-center">Heading</label>
            <?php } ?>
			<label class="col-sm-2 text-center">PDF</label>
			<label class="col-sm-2 text-center">Send PDF</label>
        </div>

        <?php if(!empty($_GET['salesid'])) {
            $each_serviceid = explode(',',$marketingmaterialid);
            $total_count = mb_substr_count($marketingmaterialid,',');
            $id_loop = 500;

            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {
                $marketing_materialid = '';
                if(isset($each_serviceid[$inventory_loop])) {
                    $marketing_materialid = $each_serviceid[$inventory_loop];
                }

                if($marketing_materialid != '') {
            ?>

            <div class="form-group clearfix" id="<?php echo 'mm_'.$id_loop; ?>" >
                <?php if (strpos($value_config, ','."Marketing Material Material Type".',') !== FALSE) { ?>
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Type..." id="<?php echo 'mmaterial_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid mmat_type_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(marketing_material_type) FROM marketing_material WHERE deleted=0 order by marketing_material_type");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_marketing_material($dbc, $marketing_materialid, 'marketing_material_type') == $row['marketing_material_type']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['marketing_material_type']."'>".$row['marketing_material_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($value_config, ','."Marketing Material Category".',') !== FALSE) { ?>
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Category..." id="<?php echo 'mcategory_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid mmat_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM marketing_material WHERE deleted=0 order by category");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_marketing_material($dbc, $marketing_materialid, 'category') == $row['category']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($value_config, ','."Marketing Material Heading".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Heading..." id="<?php echo 'mheading_'.$id_loop; ?>" name="marketingmaterialid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT marketing_materialid, heading FROM marketing_material WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            if ($marketing_materialid == $row['marketing_materialid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['marketing_materialid']."'>".$row['heading'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
				<?php $query = mysqli_query($dbc,"SELECT document_link FROM marketing_material_uploads WHERE marketing_materialid='$marketing_materialid' AND type = 'Document'"); ?>
	
				<div class="col-sm-2" id='pdfshow_<?php echo $id_loop; ?>'>
					<?php while($row = mysqli_fetch_array($query)) {
						echo '<a href="'.WEBSITE_URL.'/Marketing Material/download/'.$row['document_link'].'" target="_blank">'.$row['document_link'].'</a>';
					} ?>
				</div>

				<div class="col-sm-2" id="mcustomers_<?php echo $id_loop; ?>" style='display:none'>
                    <select data-placeholder="Choose a Staff..." id="mcustomer_<?php echo $id_loop; ?>" name="email_address[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT contactid, email_address, first_name,last_name FROM contacts where deleted=0 and category IN('Sales Leads',".STAFF_CATS.",'Customers') AND ".STAFF_CATS_HIDE_QUERY."");
                        while($row = mysqli_fetch_array($query)) {
                            $get_email_address = get_email($dbc, $row['contactid']);
                            echo "<option value='".$get_email_address."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';

                        }
                        ?>
                    </select>
                </div>

				<div class="col-sm-2" id="msend_<?php echo $id_loop; ?>">
					<span style='margin-left:70px'class="open-approval" onclick="approvebutton(this)" id="<?php echo $marketing_materialid; ?>">Send</span>
				</div>

                <div class="col-sm-1" >
                    <div class="approve-box-<?php echo $marketing_materialid; ?> approve-box"><div style='text-align:left;'>Please enter the email(s) (separated by a comma) you would like to send this Marketing Material.</div><br><br>
                    <input type='text' style='max-width:300px;' name='getemailsapprove123_<?php echo $marketing_materialid; ?>' placeholder='email1@example.com,email2@example.com' class='form-control getemailsapprove'><br><br>
                    <div style='width:100%; text-align:left; margin:auto;'>
                    <?php
                    $query_check_credentials = "SELECT * FROM marketing_material_uploads WHERE marketing_materialid='$marketing_materialid' AND type = 'Document' ORDER BY certuploadid DESC";
                    $result = mysqli_query($dbc, $query_check_credentials);
                    $num_rows = mysqli_num_rows($result);
                    if($num_rows > 0) {
                        while($row = mysqli_fetch_array($result)) {
                            $certuploadid = $row['certuploadid'];
                            echo '<input type="radio" style="width:20px; height:20px;" id="pocheckings"  name="certuploadid" class="unchecker" value="'.$certuploadid.'">&nbsp;&nbsp;<a href="../Marketing Material/download/'.$row['document_link'].'" target="_blank">'.$row['document_link'].'</a><br>';
                        }
                    }
                    ?>
                    </div>
                    <br>
                    <button type='submit' name='send_drive_logs_approve' class='btn brand-btn sendemailapprovesubmit' value='<?php echo $_GET['salesid']; ?>_<?php echo $marketing_materialid; ?>'>Send</button>
                    <button onClick="hide-box" value="<?php echo $marketing_materialid; ?>" type='button' name='send_drive_logs' class='btn brand-btn send_cancel'>Cancel</button>
                    </div>

					<a href="#" onclick="seleteMaterial(this,'mm_','mheading_'); return false;" id="<?php echo 'deletemm_'.$id_loop; ?>" class="btn brand-btn">Delete</a>	
                </div>
            </div>
            <?php  $id_loop++;
                    }
                }
            } ?>

        <div class="additional_m clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="mm_0">
                <?php if (strpos($value_config, ','."Marketing Material Material Type".',') !== FALSE) { ?>
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Type..." id="mmaterial_0" class="chosen-select-deselect form-control equipmentid mmat_type_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(marketing_material_type) FROM marketing_material WHERE deleted=0 order by marketing_material_type");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['marketing_material_type']."'>".$row['marketing_material_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($value_config, ','."Marketing Material Category".',') !== FALSE) { ?>
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Category..." id="mcategory_0" class="chosen-select-deselect form-control equipmentid mmat_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM marketing_material WHERE deleted=0 order by category");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($value_config, ','."Marketing Material Heading".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Heading..." id="mheading_0" name="marketingmaterialid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT marketing_materialid, heading FROM marketing_material WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['marketing_materialid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

				<div class="col-sm-2" id='pdfshow_0'></div>

				<div class="col-sm-2" id="mcustomers_0" style='display:none'>
                    <select data-placeholder="Choose a Staff..." id="mcustomer_0" name="email_address[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT contactid,email_address, first_name,last_name FROM contacts where deleted=0 and category IN('Sales Leads',".STAFF_CATS.",'Customers') AND ".STAFF_CATS_HIDE_QUERY."");
                        while($row = mysqli_fetch_array($query)) {
                            $get_email_address = get_email($dbc, $row['contactid']);
                            echo "<option value='".$get_email_address."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';

                        }
                        ?>
                    </select>
                </div>

                <div class="col-sm-1" >
                    <a href="#" onclick="seleteMaterial(this,'mm_','mheading_'); return false;" id="deletemm_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_m"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_m" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>
