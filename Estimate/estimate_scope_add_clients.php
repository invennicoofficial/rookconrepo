<?php include_once('../include.php'); ?>
<?php $contact_list = $dbc->query("SELECT `contacts`.`contactid`,`contacts`.`first_name`,`contacts`.`last_name`,`contacts`.`name`, `company_rate_card`.`cust_price`,`company_rate_card`.`cost` FROM `contacts` LEFT JOIN `company_rate_card` ON `contacts`.`contactid`=`company_rate_card`.`item_id` AND `company_rate_card`.`tile_name`='Clients' AND `company_rate_card`.`deleted`=0 WHERE `contacts`.`deleted`=0 AND `contacts`.`category`='Clients' GROUP BY `contacts`.`contactid`");
echo '<div class="form-group hide-titles-mob">
	<div class="col-sm-8">Client</div>
	<div class="col-sm-2">Rate</div>
	<div class="col-sm-2">Quantity</div>
</div>';
foreach(sort_contacts_query($contact_list) as $client) {
	echo '<div class="form-group">
		<div class="col-sm-8"><label class="show-on-mob">Client:</label><input type="hidden" name="client_id[]" value="'.$client['contactid'].'">'.$client['full_name'].'</div>
		<div class="col-sm-2"><label class="show-on-mob">Rate:</label><input type="hidden" name="cost[]" value="'.$client['cost'].'"><input type="number" readonly class="form-control" name="price[]" value="'.$client['cust_price'].'"></div>
		<div class="col-sm-2"><label class="show-on-mob">Qty:</label><input type="number" class="form-control" name="qty[]" value=""></div>
	</div>';
} ?>