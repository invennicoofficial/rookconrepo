<!-- Lead Information -->
<script type="text/javascript">
$(document).ready(function() {
    $("#task_businessid").change(function() {
		var businessid = this.value;
		if(businessid > 0) {
			$.ajax({
				type: "GET",
				url: "sales_ajax_all.php?fill=assigncontact&businessid="+businessid,
				dataType: "html",
				success: function(response){
					var arr = response.split('**#**');
					$('#sales_contact').html(arr[0]);
					$("#sales_contact").trigger("change.select2");
					
					if(arr[4] != 'No' && arr[3] != 'No') {
						$(".estimate").html("<a href=\"#\" onclick=\"window.open('<?= WEBSITE_URL; ?>/Estimate/add_estimate.php?estimateid="+arr[3]+"', \'newwindow\', \'width=900, height=900\'); return false;\">Click to View Estimate</a>");

						var quote_html = "<a href=\"#\" onclick=\"window.open('<?= WEBSITE_URL; ?>/Quote/quotes.php?quoteid="+arr[4]+"', \'newwindow\', \'width=900, height=900\'); return false;\">Click to View Quote</a>";
						quote_html += "<br><a href=\"<?= WEBSITE_URL; ?>/Estimate/download/quote_"+arr[3]+".pdf\" target=\"_blank\"><img src=\"<?= WEBSITE_URL; ?>/img/pdf.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"View\">View PDF</a>";
						$(".quote").html(quote_html);
					} else if(arr[3] != 'No') {
						$(".estimate").html("<a href=\"#\" onclick=\"window.open('<?= WEBSITE_URL; ?>/Estimate/add_estimate.php?estimateid="+arr[3]+"', \'newwindow\', \'width=900, height=900\'); return false;\">Click to View Estimate</a>");

						var quote_html = "No Quote<br>";
						quote_html += "<a href=\"#\" onclick=\"window.open('<?= WEBSITE_URL; ?>/Estimate/estimate.php?businessid="+businessid+"&from=<?= urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']); ?>', \'newwindow\', \'width=900, height=900\'); return false;\">Click to View/Approve Estimate</a>";
						$(".quote").html(quote_html);
					} else {
						$(".estimate").html("<a href=\"#\" onclick=\"window.open('<?= WEBSITE_URL; ?>/Estimate/add_estimate.php?from=<?= urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']); ?>', \'newwindow\', \'width=900, height=900\'); return false;\">Click to Add Estimate</a>");

						$(".quote").html("No Estimate or Quote");
					}
				}
			});
		}
	});

    $("#task_businessid").change(function() {
        if($("#task_businessid option:selected").text() == 'New Business') {
            $("#new_business").show();
            $("#contacts_list").hide();
            $("#new_contact").show();
            $("#primary_number_list").hide();
            $("#new_number").show();
            $("#email_list").hide();
            $("#new_email").show();
        } else {
            $("#new_business").hide();
            $("#contacts_list").show();
            $("#new_contact").hide();
            $("#primary_number_list").show();
            $("#new_number").hide();
            $("#email_list").show();
            $("#new_email").hide();
        }
    });

    $("#sales_contact").change(function() {
        var contactid = $(this).val();
        
        if(contactid.indexOf('NEW') != -1) {
            $("#new_contact").show();
            $("#sales_contact option[value='NEW']").remove();
            $("#sales_contact").trigger("change.select2");
        } else {
            $("#new_contact").hide();
        }

        $.ajax({
            type: "GET",
            url: "sales_ajax_all.php?fill=assignphoneemail&contactid="+contactid,
            dataType: "html",
            success: function(response){
                var arr = response.split('**#**');
				if ( $('#sales_number')=='' ) {
                    $('#sales_number').html(arr[0]);
                    $("#sales_number").trigger("change.select2");
                }
                if ( $('#sales_email')=='' ) {
                    $('#sales_email').html(arr[1]);
                    $("#sales_email").trigger("change.select2");
                }
            }
        });

    });

    $("#sales_number").change(function() {
        if($("#sales_number option:selected").text() == 'New Number') {
                $( "#new_number" ).show();
        } else {
            $( "#new_number" ).hide();
        }
    });

    $("#sales_email").change(function() {
        if($("#sales_email option:selected").text() == 'New Email') {
                $( "#new_email" ).show();
        } else {
            $( "#new_email" ).hide();
        }
    });
});
</script>

<div class="accordion-block-details padded" id="leadinfo">
    <div class="accordion-block-details-heading"><h4>Lead Information</h4></div>
    
    <div class="row set-row-height">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Business:</div>
        <div class="col-xs-12 col-sm-5">
            <select data-placeholder="Select a Business..." name="businessid" id="task_businessid" class="chosen-select-deselect form-control1">
                <option value=""></option><?php
                $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `name`, `contactid` FROM `contacts` WHERE (category IN ('Business','Sales Lead') AND deleted=0 AND `status`>0) OR `contactid`='$businessid'"), MYSQLI_ASSOC));
                echo '<option value="New Business">New Business</option>';
                foreach($query as $id) {
                    echo '<option '. ($businessid==$id ? 'selected' : '') .' value="'. $id .'">'. get_client($dbc, $id) .'</option>';
                } ?>
            </select>
        </div>
		<div class="col-xs-12 col-sm-1"><a href="../Contacts/contacts_inbox.php?fields=all_fields&edit=<?= $businessid ?>" class="no-toggle" title="<?= get_contact($dbc, $businessid, 'name_company') ?>" onclick="overlayIFrameSlider(this.href.replace(/edit=.*/,'edit='+$('#task_businessid').find('option:selected').first().val()),'auto',true,true); return false;"><img src="../img/icons/eyeball.png" class="inline-img"></a></div>
        <div class="clearfix"></div>
    </div>
    
    <div class="row set-row-height" id="new_business" style="display:none;">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">New Business:</div>
        <div class="col-xs-12 col-sm-5"><input name="new_business" type="text" class="form-control" /></div>
        <div class="clearfix"></div>
    </div>
    
    <div class="row set-row-height" id="contacts_list">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Contact:</div>
        <div class="col-xs-12 col-sm-5">
            <select data-placeholder="Select Sales Lead(s)..." id="sales_contact" name="contactid[]" class="chosen-select-deselect form-control1" multiple>
                <option value=""></option>
                <option value="NEW">New Contact</option><?php
                $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE (`businessid`='$businessid' OR `contactid`='$contactid' OR (''='$businessid' AND ''='$contactid' AND `category` IN ('Customers','Contacts','Customer','Contact','Sales Leads','Sales Lead'))) AND `deleted`=0 AND `status`>0"), MYSQLI_ASSOC));
                if ( empty($salesid) ) {
                    foreach($query as $id) {
                        if ( get_contact($dbc, $id) != '-' ) {
                            echo '<option '. ($contactid==$id ? "selected" : '') .' value="'. $id .'">'. get_contact($dbc, $id) .'</option>';
                        }
                    }
                } else {
                    foreach(explode(',', $contactid) as $id) {
                        if ( get_contact($dbc, $id) != '-' ) {
                            echo '<option selected value="'. $id .'">'. get_contact($dbc, $id) .'</option>';
                        }
                    }
                }/* 
                foreach($query as $id) {
                    if ( get_contact($dbc, $id) != '-' ) { ?>
                        <option <?= (strpos(','.$share_lead.',', ','.$id.',') !== false) ? 'selected' : ''; ?> value="<?= $id; ?>"><?= get_contact($dbc, $id); ?></option><?php
                    }
                } */ ?>
            </select>
        </div>
		<div class="col-xs-12 col-sm-1"><?php foreach(array_filter(explode(',',$contactid)) as $contact) { ?>
			<a href="../Contacts/contacts_inbox.php?fields=all_fields&edit=<?= $contact ?>" class="no-toggle" title="<?= get_contact($dbc, $contact) ?>" onclick="overlayIFrameSlider(this.href.replace(/edit=.*/,'edit='+$('#contacts_list').find('option:selected').first().val()),'auto',true,true); return false;"><img src="../img/icons/eyeball.png" class="inline-img"></a>
		<?php } ?></div>
        <div class="clearfix"></div>
    </div>
    
    <div class="row" id="new_contact" style="display:none;">
        <div class="col-xs-12 gap-md-left-15">New Contact:</div>
        <div class="clearfix"></div>
        
        <div class="col-xs-12 col-sm-4 gap-md-left-15 gap-top hidden-xs"><div class="triple-gap-left">First Name:</div></div>
        <div class="col-xs-12 col-sm-5"><input name="new_contact_fname" type="text" placeholder="First Name" class="form-control" /></div>
        <div class="clearfix"></div>
        
        <div class="col-xs-12 col-sm-4 gap-md-left-15 hidden-xs"><div class="triple-gap-left">Last Name:</div></div>
        <div class="col-xs-12 col-sm-5"><input name="new_contact_lname" type="text" placeholder="Last Name" class="form-control" /></div>
        <div class="clearfix"></div>
        
        <div class="col-xs-12 col-sm-4 gap-md-left-15 hidden-xs"><div class="triple-gap-left">Title:</div></div>
        <div class="col-xs-12 col-sm-5"><input name="new_contact_title" type="text" placeholder="Title" class="form-control" /></div>
        <div class="clearfix"></div>
        
        <div class="col-xs-12 col-sm-4 gap-md-left-15 hidden-xs"><div class="triple-gap-left">Phone Number:</div></div>
        <div class="col-xs-12 col-sm-5"><input name="new_contact_phone" type="text" placeholder="Phone Number" class="form-control" /></div>
        <div class="clearfix"></div>
        
        <div class="col-xs-12 col-sm-4 gap-md-left-15 hidden-xs"><div class="triple-gap-left">Email:</div></div>
        <div class="col-xs-12 col-sm-5"><input name="new_contact_email" type="text" placeholder="Email" class="form-control" /></div>
        <div class="clearfix"></div>

        <?php $get_regions = array_unique(array_filter(explode(',', mysqli_fetch_assoc(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') AS `regions_list` FROM `general_configuration` WHERE `name` LIKE '%_region'"))['regions_list'])));
        if ( count($get_regions) > 0 ) { ?>
            <div class="col-xs-12 col-sm-4 gap-md-left-15 hidden-xs"><div class="triple-gap-left">Region:</div></div>
            <div class="col-xs-12 col-sm-5">
                <select data-placeholder="Select a Region..." name="new_contact_region" class="chosen-select-deselect">
                    <option value=""></option><?php
                    foreach ($get_regions as $cat_tab) {
                        $selected = ( $region==$cat_tab ) ? 'selected="selected"' : '';
                        echo '<option '. $selected .' value="'. $cat_tab .'">'. $cat_tab .'</option>';
                    } ?>
                </select>
            </div>
            <div class="clearfix"></div><?php
        } ?>

        <?php $get_locations = array_filter(array_unique(explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `con_locations` SEPARATOR ',') FROM `field_config_contacts`"))[0])));
        if ( count($get_locations) > 0 ) { ?>
            <div class="col-xs-12 col-sm-4 gap-md-left-15 hidden-xs"><div class="triple-gap-left">Location:</div></div>
            <div class="col-xs-12 col-sm-5">
                <select data-placeholder="Select a Location..." name="new_contact_location" class="chosen-select-deselect">
                    <option value=""></option><?php
                    foreach ($get_locations as $cat_tab) {
                        $selected = ( $location==$cat_tab ) ? 'selected="selected"' : '';
                        echo '<option '. $selected .' value="'. $cat_tab .'">'. $cat_tab .'</option>';
                    } ?>
                </select>
            </div>
            <div class="clearfix"></div><?php
        } ?>

        <?php $get_classifications = array_unique(array_filter(explode(',', mysqli_fetch_assoc(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') AS `classifications_list` FROM `general_configuration` WHERE `name` LIKE '%_classification'"))['classifications_list'])));
        if ( count($get_classifications) > 0 ) { ?>
            <div class="col-xs-12 col-sm-4 gap-md-left-15 hidden-xs"><div class="triple-gap-left">Classification:</div></div>
            <div class="col-xs-12 col-sm-5">
                <select data-placeholder="Select a Classification..." name="new_contact_classification" class="chosen-select-deselect">
                    <option value=""></option><?php
                    foreach ($get_classifications as $cat_tab) {
                        $selected = ( $classification==$cat_tab ) ? 'selected="selected"' : '';
                        echo '<option '. $selected .' value="'. $cat_tab .'">'. $cat_tab .'</option>';
                    } ?>
                </select>
            </div>
            <div class="clearfix"></div><?php
        } ?>
    </div>
    
    <div class="row set-row-height" id="primary_number_list">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Primary Number:</div>
        <div class="col-xs-12 col-sm-5"><?php
            if ( !empty($salesid) ) { ?>
                <select data-placeholder="Select a Number..." id="sales_number" name="primary_number" class="chosen-select-deselect form-control1">
                    <option value=""></option><?php
                    $query = mysqli_query($dbc, "SELECT `contactid`, `office_phone`, `cell_phone` FROM `contacts` WHERE `businessid`='$businessid' ORDER BY `office_phone`");
                    while($row = mysqli_fetch_array($query)) {echo $primary_number . '<br>';
                        if($row['office_phone'] != '') {
                            $selected = ($primary_number==decryptIt($row['office_phone'])) ? 'selected="selected"' : '';
                            echo '<option '. $selected .' value="'. decryptIt($row['office_phone']) .'">'. decryptIt($row['office_phone']) .'</option>';
                        }
                        if($row['cell_phone'] != '') {
                            $selected = ($primary_number==decryptIt($row['cell_phone'])) ? 'selected="selected"' : '';
                            echo '<option '. $selected .' value="'. decryptIt($row['cell_phone']) .'">'. decryptIt($row['cell_phone']) .'</option>';
                        }
                    } ?>
                </select><?php
            } else { ?>
                <select data-placeholder="Select a Number..." id="sales_number" name="primary_number" class="chosen-select-deselect form-control1">
                    <option value=""></option>
                </select><?php
            } ?>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <div class="row set-row-height" id="new_number" style="display:none;">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">New Number:</div>
        <div class="col-xs-12 col-sm-5"><input name="new_number" type="text" class="form-control" /></div>
        <div class="clearfix"></div>
    </div>
    
    <div class="row set-row-height" id="email_list">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Email:</div>
        <div class="col-xs-12 col-sm-5"><?php
            if ( !empty($salesid) ) { ?>
                <select data-placeholder="Select an Email..." id="sales_email" name="email_address" class="chosen-select-deselect form-control1">
                    <option value=""></option><?php
                    $query = mysqli_query($dbc,"SELECT `contactid`, `email_address` FROM `contacts` WHERE `businessid`='$businessid' ORDER BY `email_address`");
                    while($row = mysqli_fetch_array($query)) {
                        $get_email_address = get_email($dbc, $row['contactid']);
                        if($get_email_address != '') {
                            $selected = ($email_address==$get_email_address) ? 'selected="selected"' : '';
                            echo '<option '. $selected .' value="'. $get_email_address .'">'. $get_email_address .'</option>';
                        }
                    } ?>
                </select><?php
            } else { ?>
                <select data-placeholder="Select an Email..." id="sales_email" name="email_address" class="chosen-select-deselect form-control1">
                    <option value=""></option>
                </select><?php
            } ?>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <div class="row set-row-height" id="new_email" style="display:none;">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">New Email:</div>
        <div class="col-xs-12 col-sm-5"><input name="new_email" type="text" class="form-control" /></div>
        <div class="clearfix"></div>
    </div>
    
    <?php if (strpos($value_config, ',Lead Information Lead Value,') !== false) { ?>
        <div class="row set-row-height">
            <div class="col-xs-12 col-sm-4 gap-md-left-15">Lead Value:</div>
            <div class="col-xs-12 col-sm-5"><input name="lead_value" value="<?= $lead_value; ?>" type="text" class="form-control" /></div>
            <div class="clearfix"></div>
        </div>
    <?php } ?>
    
    <div class="row set-row-height">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Estimated Close Date:</div>
        <div class="col-xs-12 col-sm-5"><input name="estimated_close_date" value="<?= $estimated_close_date; ?>" type="text" class="datepicker form-control" /></div>
        <div class="clearfix"></div>
    </div>
    
    <div class="clearfix"></div>
</div>