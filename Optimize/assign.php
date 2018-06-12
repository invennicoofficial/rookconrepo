<?php $contact_regions = array_filter(array_unique(explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') FROM `general_configuration` WHERE `name` LIKE '%_region'"))[0])));
$region_colours = explode(',',get_config($dbc, '%_region_colour', true));
$contact_security = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts_security` WHERE `contactid`='{$_SESSION['contactid']}'"));
$allowed_regions = array_filter(explode('#*#',$contact_security['region_access']));
if(count($allowed_regions) == 0) {
    $allowed_regions = $contact_regions;
}
$contact_locations = array_filter(explode(',', mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `con_locations` FROM `field_config_contacts` WHERE `con_locations` IS NOT NULL"))['con_locations']));
$allowed_locations = array_filter(explode('#*#',$contact_security['location_access']));
if(count($allowed_locations) == 0) {
    $allowed_locations = $contact_locations;
}
$class_regions = explode(',',get_config($dbc, '%_class_regions', true, ','));
$contact_classifications = [];
$classification_regions = [];
$classification_logos = [];
$allowed_classifications = array_filter(explode('#*#',$contact_security['classification_access']));
foreach(explode(',',get_config($dbc, '%_classification', true, ',')) as $i => $contact_classification) {
    if(in_array($contact_classification, $allowed_classifications) || empty($allowed_classifications)) {
    	$row = array_search($contact_classification, $contact_classifications);
    	if($class_regions[$i] == 'ALL') {
    		$class_regions[$i] = '';
    	}
    	if($row !== FALSE && $class_regions[$i] != '') {
    		$classification_regions[$row][] = $class_regions[$i];
            $classification_logos[$row] = $class_logos[$i];
    	} else {
    		$contact_classifications[] = $contact_classification;
    		$classification_regions[] = array_filter([$class_regions[$i]]);
            $classification_logos[] = $class_logos[$i];
    	}
    }
}
$allowed_classifications = $contact_classifications; ?>
<script>
$(document).ready(function() {
	$('[name=region]').change(filterRegions);
	$('[name=location]').change(filterLocation);
	$('[name=classification]').change(filterClass);
	$('[name=date]').change(get_ticket_list);
	<?php if(!empty($_GET['date'])) { ?>
		get_ticket_list();
	<?php } ?>
});
var opt_region = '<?= $_GET['region'] ?>';
var opt_location = '<?= $_GET['location'] ?>';
var opt_classification = '<?= $_GET['classification'] ?>';
var opt_date = '<?= $_GET['date'] ?>';
var lock_timer = null;
function filterRegions() {
	opt_region = $('[name=region]').val();
	$('[name=classification] option[data-region]').each(function() {
		if($(this).data('region').length > 0 && $(this).data('region').indexOf(opt_region) < 0) {
			$(this).hide();
		}
	});
	$('[name=classification]').trigger('change.select2');
	get_ticket_list();
}
function filterLocation() {
	opt_location = $('[name=location]').val();
	get_ticket_list();
}
function filterClass() {
	opt_classification = $('[name=classification]').val();
	get_ticket_list();
}
function get_ticket_list() {
	var equip_scroll = $('.equip_list').scrollTop();
	$('.equip_list').html('<h4>Loading Equipment...</h4>').load('assign_equipment_list.php?date='+$('[name=date]').val()+'&region='+opt_region+'&location='+opt_location+'&classification='+opt_classification, function() { setTicketSave(); $('.equip_list').scrollTop(equip_scroll); });
	$('.map_view').html('<h4>Loading Map...</h4>').load('assign_map_view.php?date='+$('[name=date]').val()+'&region='+opt_region+'&location='+opt_location+'&classification='+opt_classification, setTicketSave);
	$('.ticket_list').html('<h4>Loading <?= TICKET_TILE ?>...</h4>').load('assign_ticket_list.php?date='+$('[name=date]').val()+'&region='+opt_region+'&location='+opt_location+'&classification='+opt_classification, setTicketSave);
	lockTickets();
	initOptions();
}
function lockTickets() {
	clearTimeout(lock_timer);
	lock_timer = setTimeout(lockTickets,30000);
	$.post('optimize_ajax.php?action=lock', {
		region: opt_region,
		location: opt_location,
		classification: opt_classification
	});
}
function setTicketSave() {
	$('.ticket_list select,.ticket_list input').change(function() {
		console.log($(this).data('id'));
		console.log($(this).data('table'));
		console.log(this.name);
		console.log(this.value);
	});
	initInputs();
}
function initOptions() {
	$( ".assign_list_box" ).sortable({
		beforeStop: function(e, ticket) {
			ticket.helper.css('width','auto');
			var block = $('.block-item.equipment.active').first();
			if(block.length > 0) {
				$.post('optimize_ajax.php?action=assign_ticket', {
					equipment: block.data('id'),
					table: ticket.item.data('table'),
					id_field: ticket.item.data('id-field'),
					id: ticket.item.data('id'),
					date: $('[name=date]').val()
				}, function(response) {
					// console.log(response);
					get_ticket_list();
				});
			}
		},
		delay: 0,
		handle: ".drag-handle",
		items: "span.block-item.ticket",
		sort: function(e, ticket) {
			block = $(document.elementsFromPoint(e.clientX, e.clientY)).filter('.block-item.equipment').not('.ui-sortable-helper').first();
			$('.block-item.equipment.active').removeClass('active');
			block.addClass('active');
		},
		start: function(e, ticket) {
			ticket.helper.css('width','18em');
		}
	});
	console.log('INITED');
}
</script>
<div class="main-screen standard-body override-main-screen form-horizontal">
	<div class="standard-body-title">
		<h3>Assign <?= TICKET_TILE ?></h3>
	</div>
	<div class="standard-body-content pad-top">
		<div class="col-sm-12">
			<?php $columns = 1 + (count($allowed_regions) > 0 ? 1 : 0) + (count($allowed_locations) > 0 ? 1 : 0) + (count($contact_classifications) > 0 ? 1 : 0); ?>
			<?php if(count($allowed_regions) > 0) { ?>
				<div class="form-group col-sm-<?= 12 / $columns ?> col-xs-12">
					<label class="col-sm-4">Region:</label>
					<div class="col-sm-8">
						<select class="chosen-select-deselect" name="region" data-placeholder="Select Region"><option />
							<?php foreach($allowed_regions as $region_name) { ?>
								<option value="<?= $region_name ?>"><?= $region_name ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			<?php } ?>
			<?php if(count($allowed_locations) > 0) { ?>
				<div class="form-group col-sm-<?= 12 / $columns ?> col-xs-12">
					<label class="col-sm-4">Location:</label>
					<div class="col-sm-8">
						<select class="chosen-select-deselect" name="location" data-placeholder="Select Location"><option />
							<?php foreach($allowed_locations as $location) { ?>
								<option value="<?= $location ?>"><?= $location ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			<?php } ?>
			<?php if(count($contact_classifications) > 0) { ?>
				<div class="form-group col-sm-<?= 12 / $columns ?> col-xs-12">
					<label class="col-sm-4">Classification:</label>
					<div class="col-sm-8">
						<select class="chosen-select-deselect" name="classification" data-placeholder="Select Classification"><option />
							<?php foreach($contact_classifications as $i => $class) { ?>
								<option data-region='<?= json_encode($classification_regions[$i]) ?>' value="<?= $class ?>"><?= $class ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			<?php } ?>
			<div class="form-group col-sm-<?= 12 / $columns ?> col-xs-12">
				<label class="col-sm-4">Date:</label>
				<div class="col-sm-8">
					<input type="text" name="date" class="form-control datepicker" placeholder="<?= TICKET_NOUN ?> Date" value="<?= $_GET['date'] ?>">
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="assign_list_box" style="height: 20em;position:relative;">
			<div class="equip_list" style="display:inline-block; height:100%; width:20%; float:left; overflow-y:auto;"></div>
			<div class="map_view" style="display:inline-block; height:100%; width:60%; overflow:hidden;"></div>
			<div class="ticket_list" style="display:inline-block; height:100%; width:20%; overflow-y:auto; float:right;"></div>
		</div>
	</div>
</div>