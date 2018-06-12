<!-- Marketing Material -->
<script type="text/javascript">
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
		dataType: "html",
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

function selectMaterialMaterial(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "sales_ajax_all.php?fill=m_material_config&value="+stage,
		dataType: "html",
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
		dataType: "html",
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

<div class="accordion-block-details padded" id="marketing">
    <div class="accordion-block-details-heading"><h4>Marketing Material</h4></div>
    
    <div class="row"><?php
        if (strpos($value_config, ',Marketing Material Material Type,') !== false) { echo '<div class="col-sm-12 col-md-2 gap-md-left-15 gap-bottom"><b>Material Type</b></div>'; }
        if (strpos($value_config, ',Marketing Material Category,') !== false) { echo '<div class="col-sm-12 col-md-2 gap-md-left-15 gap-bottom"><b>Category</b></div>'; }
        if (strpos($value_config, ',Marketing Material Heading,') !== false) { echo '<div class="col-sm-12 col-md-3 gap-md-left-15 gap-bottom"><b>Heading</b></div>'; } ?>
        <!--
        <div class="col-sm-12 col-md-1 gap-md-left-15 gap-bottom"><b>PDF</b></div>
        <div class="col-sm-12 col-md-2 gap-md-left-15 gap-bottom"><b>Send PDF</b></div>
        -->
        <div class="clearfix"></div><?php
        
        if ( !empty($salesid) ) {
            $each_serviceid = explode(',', $marketingmaterialid);
            $total_count    = mb_substr_count($marketingmaterialid, ',');
            $id_loop        = 500;

            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {
                $marketing_materialid = '';
                
                if (isset($each_serviceid[$inventory_loop])) {
                    $marketing_materialid = $each_serviceid[$inventory_loop];
                }
                
                if ($marketing_materialid != '') { ?>
                    <div class="set-row-height" id="<?= 'mm_'.$id_loop; ?>"><?php
                        if (strpos($value_config, ',Marketing Material Material Type,') !== false) { ?>
                            <div class="col-sm-12 col-md-2 gap-md-left-15">
                                <select data-placeholder="Choose a Type..." id="<?= 'mmaterial_'.$id_loop; ?>" class="chosen-select-deselect form-control mmat_type_onchange">
                                    <option value=""></option><?php
                                    $query = mysqli_query($dbc, "SELECT DISTINCT(`marketing_material_type`) FROM `marketing_material` WHERE `deleted`=0 ORDER BY `marketing_material_type`");
                                    while ( $row=mysqli_fetch_array($query) ) {
                                        $selected = (get_marketing_material($dbc, $marketing_materialid, 'marketing_material_type') == $row['marketing_material_type']) ?'selected="selected"' : '';
                                        echo '<option '. $selected .' value="'. $row['marketing_material_type'] .'">'. $row['marketing_material_type'] .'</option>';
                                    } ?>
                                </select>
                            </div><?php
                        }
                        
                        if (strpos($value_config, ',Marketing Material Category,') !== false) { ?>
                            <div class="col-sm-12 col-md-2 gap-md-left-15">
                                <select data-placeholder="Choose a Category..." id="<?php echo 'mcategory_'.$id_loop; ?>" class="chosen-select-deselect form-control mmat_cat_onchange">
                                    <option value=""></option><?php
                                    $query = mysqli_query($dbc,"SELECT DISTINCT(`category`) FROM `marketing_material` WHERE `deleted`=0 ORDER BY `category`");
                                    while($row = mysqli_fetch_array($query)) {
                                        $selected = (get_marketing_material($dbc, $marketing_materialid, 'category') == $row['category']) ? 'selected="selected"' : '';
                                        echo '<option '. $selected .' value="'. $row['category'] .'">'. $row['category'] .'</option>';
                                    } ?>
                                </select>
                            </div><?php
                        }
                        
                        if (strpos($value_config, ',Marketing Material Heading,') !== false) { ?>
                            <div class="col-sm-12 col-md-3 gap-md-left-15">
                                <select data-placeholder="Choose a Heading..." id="<?= 'mheading_'.$id_loop; ?>" name="marketingmaterialid[]" class="chosen-select-deselect form-control">
                                    <option value=""></option><?php
                                    $query = mysqli_query($dbc,"SELECT `marketing_materialid`, `heading` FROM `marketing_material` WHERE `deleted`=0 ORDER BY `heading`");
                                    while($row = mysqli_fetch_array($query)) {
                                        $selected = ($marketing_materialid == $row['marketing_materialid']) ? 'selected="selected"' : '';
                                        echo '<option '. $selected .' value="'. $row['marketing_materialid'] .'">'. $row['heading'] .'</option>';
                                    } ?>
                                </select>
                            </div><?php
                        }
                        
                        $query = mysqli_query($dbc, "SELECT `document_link` FROM `marketing_material_uploads` WHERE `marketing_materialid`='{$marketing_materialid}' AND `type`='Document'"); ?>
                        
                        <div class="col-sm-1 pad-5" id="pdfshow_<?= $id_loop; ?>"><?php
                            while($row = mysqli_fetch_array($query)) {
                                echo '<a href="'. WEBSITE_URL .'/Marketing Material/download/'. $row['document_link'] .'" target="_blank">View</a>';
                            } ?>
                        </div>
                        
                        <!--
                        <div class="col-sm-12 col-md-2" id="mcustomers_<?= $id_loop; ?>" style="display:none">
                            <select data-placeholder="Choose a Staff..." id="mcustomer_<?= $id_loop; ?>" name="email_marketing[]" class="chosen-select-deselect form-control">
                                <option value=""></option><?php
                                /* $query = mysqli_query($dbc, "SELECT `email_address`, `first_name`, `last_name` FROM `contacts` WHERE `deleted`=0 AND `category` IN('Sales Leads', 'Staff','Customers')");
                                while($row = mysqli_fetch_array($query)) {
                                    echo '<option value="'. decryptIt($row['email_address']) .'">'. decryptIt($row['first_name']) .' '. decryptIt($row['last_name']) .'</option>';
                                } */ ?>
                            </select>
                        </div>
                        -->
                        
                        <!--
                        <div class="col-sm-2" id="msend_<?= $id_loop; ?>">
                            <span class="open-approval" onclick="approvebutton(this)" id="<?= $marketing_materialid; ?>">Send</span>
                        </div>
                        -->
                        
                        <!-- NEW WORKING
                        <div class="col-sm-12 col-md-2" >
                            <div class="approve-box-<?= $marketing_materialid; ?> approve-box">
                                <div class="popover-examples pull-left"><a class="pad-5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Please enter the email(s) (separated by a comma) you would like to send this Marketing Material."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20" /></a></div>
                                <div class="pull-left"><input type="text" style="width:140px;" name="getemailsapprove123_<?= $marketing_materialid; ?>" placeholder="email1@example.com,email2@example.com" class="form-control getemailsapprove" /></div>
                                <div class="clearfix"></div>
                                <!--
                                <div style="width:100%; text-align:left; margin:auto;"><?php
                                    /* $result = mysqli_query($dbc, "SELECT * FROM `marketing_material_uploads` WHERE `marketing_materialid`='$marketing_materialid' AND `type`='Document' ORDER BY `certuploadid` DESC");
                                    if($result->num_rows > 0) {
                                        while($row = mysqli_fetch_array($result)) {
                                            $certuploadid = $row['certuploadid'];
                                            echo '<input type="radio" style="width:20px; height:20px;" id="pocheckings"  name="certuploadid" class="unchecker" value="'.$certuploadid.'">&nbsp;&nbsp;<a href="../Marketing Material/download/'.$row['document_link'].'" target="_blank">'.$row['document_link'].'</a><br>';
                                        }
                                    } */ ?>
                                </div>
                                --
                                <button type="submit" name="send_drive_logs_approve" class="btn brand-btn sendemailapprovesubmit triple-gap-left gap-bottom" value="<?= $salesid .'_'. $marketing_materialid; ?>">Send</button>
                                <!--<button onClick="hide-box" value="<?= $marketing_materialid; ?>" type="button" name="send_drive_logs" class="btn brand-btn send_cancel">Cancel</button>--
                            </div>
                        </div>
                        -->
                        
                        <div class="col-sm-12 col-md-1">
                            <a href="#" onclick="seleteMaterial(this,'mm_','mheading_'); return false;" id="<?= 'deletemm_'.$id_loop; ?>"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                        </div>
                        
                        <div class="clearfix"></div>
                    </div><?php
                    
                    $id_loop++;
                }
            }
        } ?>
        
        <div class="additional_m">

            <div class="set-row-height" id="mm_0"><?php
                if (strpos($value_config, ',Marketing Material Material Type,') !== false) { ?>
                    <div class="col-sm-12 col-md-2 gap-md-left-15">
                        <select data-placeholder="Choose a Type..." id="mmaterial_0" class="chosen-select-deselect form-control mmat_type_onchange">
                            <option value=""></option><?php
                            $query = mysqli_query($dbc,"SELECT DISTINCT(`marketing_material_type`) FROM `marketing_material` WHERE `deleted`=0 ORDER BY `marketing_material_type`");
                            while($row = mysqli_fetch_array($query)) {
                                echo '<option value="'. $row['marketing_material_type'] .'">'. $row['marketing_material_type'] .'</option>';
                            } ?>
                        </select>
                    </div><?php
                }
                
                if (strpos($value_config, ',Marketing Material Category,') !== false) { ?>
                    <div class="col-sm-12 col-md-2 gap-md-left-15">
                        <select data-placeholder="Choose a Category..." id="mcategory_0" class="chosen-select-deselect form-control mmat_cat_onchange">
                            <option value=""></option><?php
                            $query = mysqli_query($dbc, "SELECT DISTINCT(`category`) FROM `marketing_material` WHERE `deleted`=0 ORDER BY `category`");
                            while($row = mysqli_fetch_array($query)) {
                                echo '<option value="'. $row['category'] .'">'. $row['category'] .'</option>';
                            } ?>
                        </select>
                    </div><?php
                }
                
                if (strpos($value_config, ',Marketing Material Heading,') !== false) { ?>
                    <div class="col-sm-12 col-md-3 gap-md-left-15">
                        <select data-placeholder="Choose a Heading..." id="mheading_0" name="marketingmaterialid[]" class="chosen-select-deselect form-control">
                            <option value=""></option><?php
                            $query = mysqli_query($dbc, "SELECT `marketing_materialid`, `heading` FROM `marketing_material` WHERE `deleted`=0 ORDER BY `heading`");
                            while($row = mysqli_fetch_array($query)) {
                                echo '<option value="'. $row['marketing_materialid'] .'">'. $row['heading'] .'</option>';
                            } ?>
                        </select>
                    </div><?php
                } ?>
                
                <div class="col-sm-1 pad-5">
                    <a href="#" onclick="seleteMaterial(this,'mm_','mheading_'); return false;" id="deletemm_0"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                </div>
                
                <div class="clearfix"></div>
            </div>
        </div><!-- .additional_s -->

        <div id="add_here_new_m"></div>
        
        <div class="col-sm-12 gap-md-left-10 gap-top">
            <a href="#" id="add_row_m" class="gap-md-left-15"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
        </div>
        
        <div class="clearfix"></div>
        
    </div>
    <div class="clearfix"></div>
    
</div><!-- .accordion-block-details -->