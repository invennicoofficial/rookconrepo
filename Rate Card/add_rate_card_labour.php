<script>
$(document).ready(function() {
	//Labour
    $('#deletelabour_0').hide();
    var add_new_l = 1;
    $('#add_row_l').on( 'click', function () {
        $('#deletelabour_0').show();
        var clone = $('.additional_l').clone();
        clone.find('.form-control').val('');

        clone.find('#labour_0').attr('id', 'labour_'+add_new_l);
        clone.find('#lheading_0').attr('id', 'lheading_'+add_new_l);
		clone.find('#lhr_0').attr('id', 'lhr_'+add_new_l);
		clone.find('#lfinalprice_0').attr('id', 'lfinalprice_'+add_new_l);
		clone.find('#lquoteprice_0').attr('id', 'lquoteprice_'+add_new_l);
        clone.find('#labourfull_0').attr('id', 'labourfull_'+add_new_l);
        clone.find('#deletelabour_0').attr('id', 'deletelabour_'+add_new_l);

        $('#deletelabour_0').hide();

        clone.removeClass("additional_l");
        $('#add_here_new_l').append(clone);

        resetChosen($("#labour_"+add_new_l));
        resetChosen($("#lheading_"+add_new_l));

        add_new_l++;

        return false;
    });
});
$(document).on('change', 'select.labour_onchange', function() { selectLabour(this); });
$(document).on('change', 'select[name="labourid[]"]', function() { selectLabourHeading(this); });

//Labour
function selectLabour(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=labour_type_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#lheading_"+arr[1]).html(response);
			$("#lheading_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectLabourHeading(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=l_head_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#lhr_"+arr[1]).val(result[0]);
		}
	});
	
	var rate = $(sel).find('option:selected').data('rate');
	$(sel).closest('.form-group').find('[name="primary_rate[]"]').val(rate);
	setLabourSavings();
}
function setLabourSavings() {
	$('#collapse_labour .form-group').find('[name="lfinalprice[]"]').each(function() {
		var current = this.value;
		var company = $(this).closest('.form-group').find('[name="primary_rate[]"]').val();
		$(this).closest('.form-group').find('[name="savings_dollar[]"]').val(round2Fixed(company - current));
		$(this).closest('.form-group').find('[name="savings_percent[]"]').val(round2Fixed(company > 0 ? (company - current) / company * 100 : 0));
	});
}
</script>
<?php
$get_field_config_labour = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT labour FROM field_config"));
$field_config_labour = ','.$get_field_config_labour['labour'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <?php if (strpos($base_field_config, ','."Labour Type".',') !== FALSE) { ?>
				<label class="col-sm-2 text-center">Labour Type</label>
            <?php } ?>
            <label class="col-sm-2 text-center">Heading</label>
            <?php if (strpos($field_config_labour, ','."Hourly Rate".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center">Hourly Rate</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Rate Card Price</label>
            <?php if (strpos($base_field_config, ','."savings".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center">Company Rate</label>
				<label class="col-sm-1 text-center">$ Savings</label>
				<label class="col-sm-1 text-center">% Savings</label>
            <?php } ?>
        </div>

        <?php if(!empty($_GET['ratecardid'])) {
            $each_labour = explode('**', $labour);
			$labour_id_list = [];
			if($ref_card != '') {
				$each_labour = array_filter($each_labour);
				foreach($each_labour as $labourid) {
					$labourid = explode('#',$labourid);
					if($labourid[0] > 0) {
						$labour_id_list[] = $labourid[0];
					}
				}
				$ref_labour_list = $dbc->query("SELECT * FROM `company_rate_card` WHERE `deleted`=0 AND `rate_card_name`='$ref_card' AND `tile_name` LIKE 'Labour' AND `item_id` NOT IN ('".implode("','",$labour_id_list)."')");
				while($ref_labour = $ref_labour_list->fetch_assoc()) {
					$each_labour[] = $ref_labour['item_id'];
				}
			}
            $total_count = mb_substr_count($labour,'**');
            $id_loop = 500;
			foreach($each_labour as $pid_loop => $labour_row) {

                $labourid = '';

                if(isset($each_labour[$pid_loop])) {
                    $each_val = explode('#', $each_labour[$pid_loop]);
                    $labourid = $each_val[0];
                    $ratecardprice = $each_val[1];
                }

                if($labourid != '') {
            ?>
            <div class="form-group clearfix" id="<?php echo 'labourfull_'.$id_loop; ?>">
                <?php if (strpos($base_field_config, ','."Labour Type".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Labour Type:</label>
                    <select data-placeholder="Choose a Labour Type..." id="<?php echo 'labour_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid labour_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(labour_type) FROM labour WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_labour($dbc, $labourid, 'labour_type') == $row['labour_type']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['labour_type']."'>".$row['labour_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." id="<?php echo 'lheading_'.$id_loop; ?>" name="labourid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT `labour`.`labourid`, `labour`.`heading`, MAX(`company_rate_card`.`cust_price`) `rate` FROM labour LEFT JOIN `company_rate_card` ON `labour`.`labourid`=`company_rate_card`.`item_id` AND `company_rate_card`.`deleted`=0 AND `company_rate_card`.`tile_name` LIKE 'Labour' AND `rate_card_name`='$ref_card' WHERE `labour`.`deleted`=0 GROUP BY `labour`.`labourid` order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            if ($labourid == $row['labourid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." data-rate='".$row['rate']."' value='". $row['labourid']."'>".$row['heading'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <?php if (strpos($field_config_labour, ','."Hourly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Hourly Rate:</label>
                    <input name="lhr[]" value="<?php echo get_labour($dbc, $labourid, 'hourly_rate');?>" id="<?php echo 'lhr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="lfinalprice[]" value="<?php echo $ratecardprice;?>" id="<?php echo 'lfinalprice_'.$id_loop; ?>" type="text" onchange="setLabourSavings();" class="form-control" />
                </div>

                <?php if (strpos($base_field_config, ','."savings".',') !== FALSE) {
					$company_rate = $dbc->query("SELECT `cust_price` FROM `company_rate_card` WHERE `deleted`=0 AND `item_id`='$labourid' AND `tile_name` LIKE 'Labour' AND '$unit_of_measure' IN (`uom`,'')")->fetch_assoc()['cust_price']; ?>
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
                    <a href="#" onclick="deleteRatecard(this,'labourfull_','lheading_'); return false;" id="<?php echo 'deletelabour_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>

        <div class="additional_l clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="labourfull_0">
                <?php if (strpos($base_field_config, ','."Labour Type".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Labour Type:</label>
                    <select data-placeholder="Choose a Labour Type..." id="labour_0" class="chosen-select-deselect form-control equipmentid labour_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(labour_type) FROM labour WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['labour_type']."'>".$row['labour_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." id="lheading_0" name="labourid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT labourid, heading FROM labour WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['labourid']."'>".$row['heading'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <?php if (strpos($field_config_labour, ','."Hourly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Hourly Rate:</label>
                    <input name="lhr[]" id="lhr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="lfinalprice[]" id="lfinalprice_0" type="text" class="form-control" />
                </div>

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
                    <a href="#" onclick="deleteRatecard(this,'labourfull_','lheading_'); return false;" id="deletelabour_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_l"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_l" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>
