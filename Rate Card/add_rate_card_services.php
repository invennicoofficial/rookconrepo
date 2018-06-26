<script>
$(document).ready(function() {
	//Services
    var add_new_s = 1;
    $('#deleteservices_0').hide();
    $('#add_row_s').on( 'click', function () {
        $('#deleteservices_0').show();
        var clone = $('.additional_s').clone();
        clone.find('.form-control').val('');

        clone.find('#sservice_0').attr('id', 'sservice_'+add_new_s);
		clone.find('#scategory_0').attr('id', 'scategory_'+add_new_s);
        clone.find('#sheading_0').attr('id', 'sheading_'+add_new_s);
        clone.find('#sfrp_0').attr('id', 'sfrp_'+add_new_s);
        clone.find('#sap_0').attr('id', 'sap_'+add_new_s);
        clone.find('#swp_0').attr('id', 'swp_'+add_new_s);
        clone.find('#scomp_0').attr('id', 'scomp_'+add_new_s);
        clone.find('#scp_0').attr('id', 'scp_'+add_new_s);
        clone.find('#smsrp_0').attr('id', 'smsrp_'+add_new_s);
		clone.find('#sfinalprice_0').attr('id', 'sfinalprice_'+add_new_s);
		clone.find('#squoteprice_0').attr('id', 'squoteprice_'+add_new_s);
        clone.find('#services_0').attr('id', 'services_'+add_new_s);
        clone.find('#deleteservices_0').attr('id', 'deleteservices_'+add_new_s);
        clone.find('#sunitmeasure_0').attr('id', 'sunitmeasure_'+add_new_s);

        $('#deleteservices_0').hide();

        clone.removeClass("additional_s");
        $('#add_here_new_s').append(clone);

        resetChosen($("#sservice_"+add_new_s));
        resetChosen($("#scategory_"+add_new_s));
        resetChosen($("#sheading_"+add_new_s));
        resetChosen($("#sunitmeasure_"+add_new_s));

        add_new_s++;

        return false;
    });
});
$(document).on('change', 'select.serv_serv_onchange', function() { selectServiceService(this); });
$(document).on('change', 'select.serv_cat_onchange', function() { selectServiceCat(this); });
$(document).on('change', 'select[name="serviceid[]"]', function() { selectServiceHeading(this); });
//Services
function selectServiceService(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=s_service_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#scategory_"+arr[1]).html(response);
			$("#scategory_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectServiceCat(sel) {
	var stage = encodeURIComponent(sel.value);
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=s_cat_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#sheading_"+arr[1]).html(response);
			$("#sheading_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectServiceHeading(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=s_head_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#sfrp_"+arr[1]).val(result[0]);
            $("#sap_"+arr[1]).val(result[1]);
            $("#swp_"+arr[1]).val(result[2]);
            $("#scomp_"+arr[1]).val(result[3]);
            $("#scp_"+arr[1]).val(result[4]);
            $("#smsrp_"+arr[1]).val(result[5]);
		}
	});
	
	var rate = $(sel).find('option:selected').data('rate');
	$(sel).closest('.form-group').find('[name="primary_rate[]"]').val(rate);
	setServiceSavings();
}

function setServiceSavings() {
	$('#collapse_service .form-group').find('[name="sfinalprice[]"]').each(function() {
		var current = this.value;
		var company = $(this).closest('.form-group').find('[name="primary_rate[]"]').val();
		$(this).closest('.form-group').find('[name="savings_dollar[]"]').val(round2Fixed(company - current));
		$(this).closest('.form-group').find('[name="savings_percent[]"]').val(round2Fixed(company > 0 ? (company - current) / company * 100 : 0));
	});
}
</script>
<?php
$get_field_config_service = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT services FROM field_config"));
$field_config_service = ','.$get_field_config_service['services'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <?php if (strpos($base_field_config, ','."Services Category".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Category</label>
            <?php } ?>
            <?php if (strpos($base_field_config, ','."Services Service Type".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Service Type</label>
            <?php } ?>
            <?php if (strpos($base_field_config, ','."Services Heading".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Heading</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Final Retail Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Final Retail Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Admin Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Admin Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Wholesale Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Wholesale Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Commercial Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Commercial Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Client Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Client Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."MSRP".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">MSRP</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Rate Card Price</label>
            <?php if (strpos($base_field_config, ','."Services Unit of Measurement".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">UoM</label>
            <?php } ?>
            <?php if (strpos($base_field_config, ','."Services Comments".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Comments</label>
            <?php } ?>
            <?php if (strpos($base_field_config, ','."savings".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Company Rate</label>
            <label class="col-sm-1 text-center">$ Savings</label>
            <label class="col-sm-1 text-center">% Savings</label>
            <?php } ?>
        </div>

        <?php if(!empty($_GET['ratecardid'])) {
            $each_services = explode('**', $services);
			$service_id_list = [];
			if($ref_card != '') {
				$each_services = array_filter($each_services);
				foreach($each_services as $serviceid) {
					$serviceid = explode('#',$serviceid);
					if($serviceid[0] > 0) {
						$service_id_list[] = $serviceid[0];
					}
				}
				$ref_services = $dbc->query("SELECT * FROM `company_rate_card` WHERE `deleted`=0 AND `rate_card_name`='$ref_card' AND `tile_name`='Services' AND `item_id` NOT IN ('".implode("','",$service_id_list)."')");
				while($ref_service = $ref_services->fetch_assoc()) {
					$each_services[] = $ref_service['item_id'];
				}
			}
            $total_count = mb_substr_count($services,'**');
            $id_loop = 500;
			foreach($each_services as $pid_loop => $service_row) {

                $serviceid = '';

                if(isset($each_services[$pid_loop])) {
                    $each_val = explode('#', $each_services[$pid_loop]);
                    $serviceid = $each_val[0];
                    $ratecardprice = $each_val[1];
                    $unit_of_measure = $each_val[2];
                }
				$service_comment = explode('#*#',$service_comments)[$pid_loop];

                if($serviceid != '') {
            ?>
            <div class="form-group clearfix" id="<?php echo 'services_'.$id_loop; ?>">
                <?php if (strpos($base_field_config, ','."Services Category".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="<?php echo 'scategory_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid serv_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE deleted=0 order by category");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_services($dbc, $serviceid, 'category') == $row['category']) {
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

                <?php if (strpos($base_field_config, ','."Services Service Type".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Service Type:</label>
                    <select data-placeholder="Choose a Type..." id="<?php echo 'sservice_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid serv_serv_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(service_type) FROM services WHERE deleted=0 order by service_type");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_services($dbc, $serviceid, 'service_type') == $row['service_type']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['service_type']."'>".$row['service_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." id="<?php echo 'sheading_'.$id_loop; ?>" name="serviceid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT `services`.`serviceid`, `services`.`heading`, MAX(`company_rate_card`.`cust_price`) `rate` FROM services LEFT JOIN `company_rate_card` ON `services`.`serviceid`=`company_rate_card`.`item_id` AND `company_rate_card`.`deleted`=0 AND `company_rate_card`.`tile_name`='Services' AND `rate_card_name`='$ref_card' WHERE `services`.`deleted`=0 GROUP BY `services`.`serviceid` order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            if ($serviceid == $row['serviceid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." data-rate='".$row['rate']."' value='". $row['serviceid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>

                    <!-- <input name="sheading[]" readonly id="<?php echo 'sheading_'.$id_loop; ?>" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_service, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="sfrp[]" value="<?php echo get_services($dbc, $serviceid, 'final_retail_price');?>" id="<?php echo 'sfrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="sap[]" value="<?php echo get_services($dbc, $serviceid, 'admin_price');?>" id="<?php echo 'sap_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="swp[]" value="<?php echo get_services($dbc, $serviceid, 'wholesale_price');?>" id="<?php echo 'swp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="scomp[]" value="<?php echo get_services($dbc, $serviceid, 'commercial_price');?>" id="<?php echo 'scomp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="scp[]" value="<?php echo get_services($dbc, $serviceid, 'client_price');?>" id="<?php echo 'scp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="smsrp[]" value="<?php echo get_services($dbc, $serviceid, 'msrp');?>" id="<?php echo 'smsrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="sfinalprice[]" value="<?php echo $ratecardprice;?>" id="<?php echo 'sfinalprice_'.$id_loop; ?>" type="text" class="form-control" onchange="setServiceSavings();" />
                </div>

                <?php if (strpos($base_field_config, ','."Services Unit of Measurement".',') !== FALSE) { ?>
                    <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Unit of Measurement:</label>
                        <select data-placeholder="Choose a Unit" name="sunitmeasure[]" id="<?php echo 'sunitmeasure_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid" width="380">
                            <option <?= $unit_of_measure != 'hourly' ? 'selected' : '' ?> value='mileage'>By Mileage</option>
                            <option <?= $unit_of_measure == 'hourly' ? 'selected' : '' ?> value='hourly'>By Hour</option>
                        </select>
                    </div>
                <?php } ?>

                <?php if (strpos($base_field_config, ','."Services Comments".',') !== FALSE) { ?>
                    <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Comments:</label>
                        <input name="service_comments[]" value="<?php echo $service_comment;?>" id="<?php echo 'service_comments_'.$id_loop; ?>" type="text" class="form-control" />
                    </div>
                <?php } ?>

                <?php if (strpos($base_field_config, ','."savings".',') !== FALSE) {
					$company_rate = $dbc->query("SELECT `cust_price` FROM `company_rate_card` WHERE `deleted`=0 AND `item_id`='$serviceid' AND `tile_name`='Services' AND '$unit_of_measure' IN (`uom`,'')")->fetch_assoc()['cust_price']; ?>
                    <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Company Rate</label>
                        <input name="primary_rate[]" value="<?= $company_rate ?>" disabled type="text" class="form-control" />
                    </div>
                    <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">$ Savings</label>
                        <input name="savings_dollar[]" value="<?= number_format($company_rate - $ratecardprice,2) ?>" disabled type="text" class="form-control" />
                    </div>
                    <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">% Savings</label>
                        <input name="savings_percent[]" value="<?= number_format(($company_rate - $ratecardprice) / $company_rate * 100,2) ?>" disabled type="text" class="form-control" />
                    </div>
                <?php } ?>

                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'services_','sheading_'); return false;" id="<?php echo 'deleteservices_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>

            </div>
        <?php  $id_loop++;
                }
            }
        } ?>

        <div class="additional_s clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="services_0">
                <?php if (strpos($base_field_config, ','."Services Category".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="scategory_0" class="chosen-select-deselect form-control equipmentid serv_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE deleted=0 order by category");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($base_field_config, ','."Services Service Type".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Service Type:</label>
                    <select data-placeholder="Choose a Type..." id="sservice_0" class="chosen-select-deselect form-control equipmentid serv_serv_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(service_type) FROM services WHERE deleted=0 order by service_type");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['service_type']."'>".$row['service_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." id="sheading_0" name="serviceid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['serviceid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>

                    <!-- <input name="sheading[]" readonly id="sheading_0" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_service, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="sfrp[]" id="sfrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="sap[]" id="sap_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="swp[]" id="swp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="scomp[]" id="scomp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="scp[]" id="scp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="smsrp[]" id="smsrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="sfinalprice[]" id="sfinalprice_0" type="text" class="form-control" onchange="setServiceSavings();" />
                </div>

                <?php if (strpos($base_field_config, ','."Services Unit of Measurement".',') !== FALSE) { ?>
                    <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Unit of Measurement:</label>
                        <select data-placeholder="Choose a Unit" name="sunitmeasure[]" id="sunitmeasure_0" class="chosen-select-deselect form-control equipmentid" width="380">
                            <option value='mileage' selected>By Mileage</option>
                            <option value='hourly'>By Hour</option>
                        </select>
                    </div>
                <?php } ?>

                <?php if (strpos($base_field_config, ','."Services Comments".',') !== FALSE) { ?>
                    <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Comments:</label>
                        <input name="service_comments[]" value="" id="service_comments_0" type="text" class="form-control" />
                    </div>
                <?php } ?>

                <?php if (strpos($base_field_config, ','."savings".',') !== FALSE) { ?>
                    <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Company Rate</label>
                        <input name="primary_rate[]" value="" type="text" class="form-control" />
                    </div>
                    <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">$ Savings</label>
                        <input name="savings_dollar[]" value="" type="text" class="form-control" />
                    </div>
                    <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">% Savings</label>
                        <input name="savings_percent[]" value="" type="text" class="form-control" />
                    </div>
                <?php } ?>

                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'services_','sheading_'); return false;" id="deleteservices_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_s"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_s" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>
