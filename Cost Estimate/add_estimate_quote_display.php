<?php $est_opt_label = " Only";
if(count($mandatory_details_quote_config) > 0) {
	$mandatory_details = [];
	foreach($mandatory_details_quote_config as $detail_config_name) {
		foreach(explode('#*#',mysqli_fetch_array(mysqli_query($dbc,"SELECT `custom_accordions` FROM `field_config_cost_estimate`"))['custom_accordions']) as $acc_name) {
			if(preg_replace('/[^a-z]/','',strtolower(explode(',',$acc_name)[0])) == $detail_config_name) {
				$mandatory_details[] = explode(',',$acc_name)[0];
			}
		}
	}
	$est_opt_label = " And ".implode(', ',$mandatory_details)." Details";
}
?>
<label><input type="radio" name="quote_mode[]" <?= !in_array('Category',$quote_mode) && !in_array('Total',$quote_mode) ? 'checked' : '' ?> value="All" style="height:1.5em; width:1.5em;"> Display All Line Totals</label>
<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="The estimate will display exactly as outlined."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><br />
<label><input type="radio" name="quote_mode[]" <?= in_array('Category',$quote_mode) ? 'checked' : '' ?> value="Category" style="height:1.5em; width:1.5em;"> Display Category Totals <?= $est_opt_label ?></label>
<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="The estimate will display only the totals for each accordion."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><br />
<label><input type="radio" name="quote_mode[]" <?= in_array('Total',$quote_mode) ? 'checked' : '' ?> value="Total" style="height:1.5em; width:1.5em;"> Display Estimate Total <?= $est_opt_label ?></label>
<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="The estimate will only display the total with no other totals."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><br />
<?php if(in_array_starts('Total Multiple',$field_order) || $quote_multiple > 1) { ?>
	<label><input type="number" name="quote_multiple" value="<?= $quote_multiple ?>" min="1" step="1" style="display:inline-block; width:4.5em;" class="form-control" onchange="toggleMultiples(this.value);"> Use Line Total Multiples</label>
	<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="The estimate will add a column displaying multiples for each item."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span><br />
<?php } ?>