<?php include_once('../include.php');
checkAuthorised('equipment');
$security = get_security($dbc, 'equipment');

if($_GET['export']) {
  $file_name = "report_equipment_" . date("Y-m-d_m") . '.csv';

  ob_end_clean();
  $fp = fopen('php://output','w');
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename='.$file_name);

  $total = 0;
  $data[] = 'Category,Description,Type,Make,Model,Unit of Measure,Model Year,Equipment Label,Total Kilometres,Style,Vehicle Size,Color,Trim,Staff,Fuel Type,
	Tire Type,Drive Train,Serial #,Unit #,VIN #,Licence Plate,Nickname,Year Purchased,Mileage,Hours Operated,Cost,CDN Cost Per Unit,USD Cost Per Unit,
	inance,Lease,Insurance Renewal Date,Insurance Company,Insurance Contact,Insurance Phone,Hourly Rate,Daily Rate,Semi Monthly Rate,Monthly Rate,
	Field Day Cost,Field Day Billable,HR Rate Work,HR Rate Travel,Next Service Date,Next Service Hours,Next Service Description,Service Location,
	Last Oil Filter Change (date),Last Oil Filter Change (km),Next Oil Filter Change (date),Next Oil Filter Change (km),Last Inspection & Tune Up (date),
	Last Inspection & Tune Up (km),Next Inspection & Tune Up (date),Next Inspection & Tune Up (km),Tire Condition,Last Tire Rotation (date),
	Last Tire Rotation (km),Next Tire Rotation (date),Next Tire Rotation (km),Registration Renewal Date,Location,LSD,Status,Ownership Status,
	Quote Description,Notes,CVIP Ticket Renewal Date,Vehicle Access Code,Cargo,Lessor,Group,Use';

  $report_validation = mysqli_query($dbc,"SELECT * FROM equipment");

  $num_rows = mysqli_num_rows($report_validation);
  while($row_report = mysqli_fetch_array($report_validation)) {
      $row = '';
				$row .= $row_report['category'] . ',';
				$row .= $row_report['equ_description'] . ',';
				$row .= $row_report['type'] . ',';
				$row .= $row_report['make'] . ',';
				$row .= $row_report['model'] . ',';
				$row .= $row_report['submodel'] . ',';
				$row .= $row_report['model_year'] . ',';
				$row .= $row_report['label'] . ',';
				$row .= $row_report['total_kilometres'] . ',';
				$row .= $row_report['leased'] . ',';
				$row .= $row_report['style'] . ',';
				$row .= $row_report['vehicle_size'] . ',';
				 $row .= $row_report['color'] . ',';
				 $row .= $row_report['trim'] . ',';
				 $row .= $row_report['staffid'] . ',';
				 $row .= $row_report['fuel_type'] . ',';
				 $row .= $row_report['tire_type'] . ',';
				 $row .= $row_report['drive_train'] . ',';
				 $row .= $row_report['serial_number'] . ',';
				 $row .= $row_report['unit_number'] . ',';
				 $row .= $row_report['vin_number'] . ',';
				 $row .= $row_report['licence_plate'] . ',';
				 $row .= $row_report['nickname'] . ',';
				 $row .= $row_report['year_purchased'] . ',';
				 $row .= $row_report['mileage'] . ',';
				 $row .= $row_report['hours_operated'] . ',';
				 $row .= $row_report['cost'] . ',';
				 $row .= $row_report['cnd_cost_per_unit'] . ',';
				 $row .= $row_report['usd_cost_per_unit'] . ',';
				 $row .= $row_report['finance'] . ',';
				 $row .= $row_report['lease'] . ',';
				 $row .= $row_report['insurance'] . ',';
				 $row .= $row_report['insurance_contact'] . ',';
				 $row .= $row_report['insurance_phone'] . ',';
				 $row .= $row_report['hourly_rate'] . ',';
				 $row .= $row_report['daily_rate'] . ',';
				 $row .= $row_report['semi_monthly_rate'] . ',';
				 $row .= $row_report['monthly_rate'] . ',';
				 $row .= $row_report['field_day_cost'] . ',';
				 $row .= $row_report['field_day_billable'] . ',';
				 $row .= $row_report['hr_rate_work'] . ',';
				 $row .= $row_report['hr_rate_travel'] . ',';
				 $row .= $row_report['next_service_date'] . ',';
				 $row .= $row_report['next_service'] . ',';
				 $row .= $row_report['next_serv_desc'] . ',';
				 $row .= $row_report['service_location'] . ',';
				 $row .= $row_report['last_oil_filter_change_date'] . ',';
				 $row .= $row_report['last_oil_filter_change'] . ',';
				 $row .= $row_report['next_oil_filter_change_date'] . ',';
				 $row .= $row_report['next_oil_filter_change'] . ',';
				 $row .= $row_report['last_insp_tune_up_date'] . ',';
				 $row .= $row_report['last_insp_tune_up'] . ',';
				 $row .= $row_report['next_insp_tune_up_date'] . ',';
				 $row .= $row_report['next_insp_tune_up'] . ',';
				 $row .= $row_report['tire_condition'] . ',';
				 $row .= $row_report['last_tire_rotation_date'] . ',';
				 $row .= $row_report['last_tire_rotation'] . ',';
				 $row .= $row_report['next_tire_rotation_date'] . ',';
				 $row .= $row_report['next_tire_rotation'] . ',';
				 $row .= $row_report['reg_renewal_date'] . ',';
				 $row .= $row_report['insurance_renewal'] . ',';
				 $row .= $row_report['location'] . ',';
				 $row .= $row_report['lsd'] . ',';
				 $row .= $row_report['status'] . ',';
				 $row .= $row_report['ownership_status'] . ',';
				 $row .= $row_report['quote_description'] . ',';
				 $row .= $row_report['notes'] . ',';
				 $row .= $row_report['cvip_renewal_date'] . ',';
				 $row .= $row_report['vehicle_access_code'] . ',';
				 $row .= $row_report['cargo'] . ',';
				 $row .= $row_report['lessor'] . ',';
				 $row .= $row_report['group'] . ',';
				 $row .= $row_report['use'] . ',';
				 $data[] = $row;
  }

  foreach ($data as $line) {
      $val = explode(",", $line);
      fputcsv($fp, $val);
  }
  exit();
}
if(!empty($_FILES['upload']['name'])) {
	include('upload_csv.php');
}
?>
<script>
$(document).on('change', 'select[name="search_category"]', function() { changeCategory(this); });
function changeCategory(sel) {
	var value = sel.value;
	<?php if($_GET['mobile_view'] == 1) { ?>
		var panel = $(sel).closest('.panel').find('.panel-body');
		panel.html('Loading...');
		$.ajax({
			url: 'dashboard_equipment.php'+value+'&mobile_view=1',
			method: 'GET',
			response: 'html',
			success: function(response) {
				panel.html(response);
				$('.pagination_links a').click(pagination_load);
			}
		});
	<?php } else { ?>
		location = value;
	<?php } ?>
}
function send_csv() {
	$('[name=upload]').change(function() {
		$('form').submit();
	});
	$('[name=upload]').click();
}
</script>

<?php $status = (empty($_GET['status']) ? 'Active' : $_GET['status']);
$equipment_main_tabs = explode(',',get_config($dbc, 'equipment_main_tabs'));

include_once('../Equipment/region_location_access.php'); ?>

<?php
$category = $_GET['category'];
$each_tab = explode(',', get_config($dbc, 'equipment_tabs')); ?>
<div class="notice double-gap-bottom popover-examples">
	<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
	<div class="col-sm-11"><span class="notice-name">NOTE:</span>
	Tracking and maintaining equipment is an essential element for every business. Through this section you’ll be able to Add/Edit/Archive equipment you wish to use throughout projects or capture essential data on.</div>
	<div class="clearfix"></div>
</div>

<?php if (get_config($dbc, 'show_category_dropdown_equipment') == '1') { ?>
    <div class="gap-left tab-container col-sm-10">
        <div class="row">
			<label class="control-label col-sm-2">
                <span class="popover-examples" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Filter equipment by Category."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                Category:
            </label>
			<div class="col-sm-4">
				<select name="search_category" class="chosen-select-deselect form-control mobile-100-pull-right category_actual">
					<option value="?category=Top">Last 25 Added</option>
					<?php
						foreach ($each_tab as $cat_tab) {
							echo "<option ".(!empty($_GET['category']) && $_GET['category'] == $cat_tab ? 'selected' : '')." value='?status=".$_GET['status']."&category=".$cat_tab."'>".$cat_tab."</option>";
						}
					?>
				</select>
			</div>
        </div>
	</div>
	<div class="clearfix"></div>
<?php } ?>
	<div>
    <form name="form_sites" method="post" action="" class="form-inline" role="form" enctype="multipart/form-data">
    <?php

    if(vuaed_visible_function($dbc, 'equipment') == 1) {
		echo '<input type="file" name="upload" style="display:none;" />';
		echo '<button type="button" name="send_upload" value="export" class="btn brand-btn mobile-block gap-bottom pull-right" onclick="export_csv();">Export CSV <img src="../img/csv.png" style="height:1em;"></button>';
		echo '<button type="submit" name="send_upload" value="upload" class="btn brand-btn mobile-block gap-bottom pull-right" onclick="send_csv(); return false;">Upload CSV <img src="../img/csv.png" style="height:1em;"></button>';
		echo "<a href='template.csv' class='pull-right'>Uploader <br />Template </a>";
		echo '<span class="popover-examples list-inline hide-on-mobile pull-right"><a style="margin:0 0 0 15px;" data-toggle="tooltip" data-placement="top" title="You can upload several pieces of equipment at once by entering them into the Template, and then uploading them using the Upload CSV button to the right. Note that not all fields in the spreadsheet need to be filled in."><img src="'.WEBSITE_URL.'/img/info.png" style="width:1em;"></a></span>';
    } ?>
	</form>
</div>
<div class="clearfix double-gap-top"></div>

<div id="no-more-tables"> <?php

// Display Pager

$equipment = '';

if (isset($_GET['search_equipment_submit'])) {
    $equipment = $_GET['search_equipment'];

    if (!empty($_GET['search_equipment'])) {
        $equipment = $_GET['search_equipment'];
    }
    if (!empty($_GET['search_category'])) {
        $equipment = $_GET['search_category'];
    }
}

if (isset($_GET['display_all_equipment'])) {
    $equipment = '';
}

/* Pagination Counting */
$rowsPerPage = 25;
$pageNum = 1;

if(isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rowsPerPage;

$status_search = '';
if($status == 'Active') {
	$status_search = " AND IFNULL(`equipment`.`status`,'') NOT IN ('Inactive')";
} else {
	$status_search = " AND IFNULL(`equipment`.`status`,'') IN ('Inactive')";
}
if($equipment != '') {
	if(!empty($_GET['category']) && $_GET['category'] != 'Top') {
		$category_query = " AND `equipment`.category='$category'";
	}
    $query_check_credentials = "SELECT `equipment`.*, SUM(`equipment_expenses`.`total`) expense_total, `invoiced_hours`, `invoiced_amt`, 0 `invoiced_daily` FROM `equipment` LEFT JOIN (SELECT `item_id` `equipmentid`, SUM(`hours_estimated`) `invoiced_hours`, SUM(`hours_estimated` * `rate`) `invoiced_amt` FROM `ticket_attached` WHERE `src_table` LIKE 'equipment' AND `deleted`=0 GROUP BY `item_id`) `invoiced` ON `equipment`.`equipmentid`=`invoiced`.`equipmentid` LEFT JOIN `equipment_expenses` ON `equipment`.`equipmentid`=`equipment_expenses`.`equipmentid` AND `equipment_expenses`.`status` != 'Rejected' WHERE `equipment`.deleted=0 $status_search AND (`equipment`.unit_number LIKE '%" . $equipment . "%' OR `equipment`.type LIKE '%" . $equipment . "%' OR `equipment`.category LIKE '%" . $equipment . "%' OR `equipment`.ownership_status LIKE '%" . $equipment . "%' OR `equipment`.make LIKE '%" . $equipment . "%' OR `equipment`.model LIKE '%" . $equipment . "%' OR `equipment`.model_year LIKE '%" . $equipment . "%' OR `equipment`.cost LIKE '%" . $equipment . "%' OR `equipment`.region LIKE '%" . $equipment . "%' OR `equipment`.location LIKE '%" . $equipment . "%' OR `equipment`.classification LIKE '%" . $equipment . "%') $category_query $access_query GROUP BY `equipment`.`equipmentid` ORDER BY ABS(`equipment`.unit_number) LIMIT $offset, $rowsPerPage";
    $query = "SELECT count(*) as numrows FROM equipment WHERE deleted=0 $status_search AND (unit_number LIKE '%" . $equipment . "%' OR type LIKE '%" . $equipment . "%' OR category LIKE '%" . $equipment . "%' OR ownership_status LIKE '%" . $equipment . "%' OR make LIKE '%" . $equipment . "%' OR model LIKE '%" . $equipment . "%' OR model_year LIKE '%" . $equipment . "%' OR cost LIKE '%" . $equipment . "%' OR region LIKE '%" . $equipment . "%' OR location LIKE '%" . $equipment . "%' OR classification LIKE '%" . $equipment . "%') $category_query $access_query ORDER BY ABS(unit_number)";
} else {
    if((empty($_GET['category'])) || ($_GET['category'] == 'Top')) {
        $query_check_credentials = "SELECT `equipment`.*, SUM(`equipment_expenses`.`total`) expense_total, `invoiced_hours`, `invoiced_amt`, 0 `invoiced_daily` FROM `equipment` LEFT JOIN (SELECT `item_id` `equipmentid`, SUM(`hours_estimated`) `invoiced_hours`, SUM(`hours_estimated` * `rate`) `invoiced_amt` FROM `ticket_attached` WHERE `src_table` LIKE 'equipment' AND `deleted`=0 GROUP BY `item_id`) `invoiced` ON `equipment`.`equipmentid`=`invoiced`.`equipmentid` LEFT JOIN `equipment_expenses` ON `equipment`.`equipmentid`=`equipment_expenses`.`equipmentid` AND `equipment_expenses`.`status` != 'Rejected' WHERE `equipment`.deleted = 0 $status_search $access_query GROUP BY `equipment`.`equipmentid` ORDER BY ABS(`equipment`.unit_number) DESC LIMIT 25";
    } else {
        $category = $_GET['category'];
        $query_check_credentials = "SELECT `equipment`.*, SUM(`equipment_expenses`.`total`) expense_total, `invoiced_hours`, `invoiced_amt`, 0 `invoiced_daily` FROM `equipment` LEFT JOIN (SELECT `item_id` `equipmentid`, SUM(`hours_estimated`) `invoiced_hours`, SUM(`hours_estimated` * `rate`) `invoiced_amt` FROM `ticket_attached` WHERE `src_table` LIKE 'equipment' AND `deleted`=0 GROUP BY `item_id`) `invoiced` ON `equipment`.`equipmentid`=`invoiced`.`equipmentid` LEFT JOIN `equipment_expenses` ON `equipment`.`equipmentid`=`equipment_expenses`.`equipmentid` AND `equipment_expenses`.`status` != 'Rejected' WHERE `equipment`.deleted = 0 $status_search AND `equipment`.category='$category' $access_query GROUP BY `equipment`.`equipmentid` ORDER BY ABS(`equipment`.unit_number) LIMIT $offset, $rowsPerPage";
        $query = "SELECT count(*) as numrows FROM equipment WHERE deleted = 0 $status_search AND category='$category'".$access_query;
    }
}

$result = mysqli_query($dbc, $query_check_credentials);

$num_rows = mysqli_num_rows($result);
if($num_rows > 0) {
    if(empty($_GET['category']) || $_GET['category'] == 'Top') {
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT equipment_dashboard FROM field_config_equipment WHERE `tab` NOT IN ('service_record','service_request') AND equipment_dashboard IS NOT NULL"));
        $value_config = ',Category,'.$get_field_config['equipment_dashboard'].',';
    } else {
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT equipment_dashboard FROM field_config_equipment WHERE tab='$category' AND accordion IS NULL UNION SELECT equipment_dashboard FROM field_config_equipment WHERE `tab` NOT IN ('service_record','service_request') AND equipment_dashboard IS NOT NULL"));
		$value_config = ','.$get_field_config['equipment_dashboard'].',';
    }

    // Added Pagination //
    if(isset($query)) {
    	echo '<div class="pagination_links">';
        echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
        echo '</div>';
    }
    // Pagination Finish //

    while($row = mysqli_fetch_array( $result )) {

	    $color = '';
	    if($row['status'] == 'In equipment') {
	        $color = 'style="color: white;"';
	    }
	    if($row['status'] == 'In transit from vendor') {
	        $color = 'style="color: red;"';
	    }
	    if($row['status'] == 'In transit between yards') {
	        $color = 'style="color: blue;"';
	    }
	    if($row['status'] == 'Not confirmed in yard by equipment check') {
	        $color = 'style="color: yellow;"';
	    }
	    if($row['status'] == 'Assigned to job') {
	        $color = 'style="color: green;"';
	    }
	    if($row['status'] == 'In transit and assigned') {
	        $color = 'style="color: purple;"';
	    }

	    echo '<div class="dashboard-item">';
	    echo '<h3 style="margin-top: 0.5em;">'.(vuaed_visible_function($dbc, 'equipment') == 1 ? '<a href="?edit='.$row['equipmentid'].'">' : '').get_equipment_label($dbc, $row).(vuaed_visible_function($dbc, 'equipment') == 1 ? '</a>' : '').'</h3>';
	    if (!empty($row['equipment_image']) && file_exists('download/'.$row['equipment_image'])) {
	    	echo '<div class="col-sm-6"><img src="download/'.$row['equipment_image'].'" class="pull-left thumbnail-small" style="margin: 0.5em; max-width: 150px; height: auto;"></div>';
	    }
	    if (strpos($value_config, ','."Unit #".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Unit #:</label>
			<div class="col-sm-8">' . $row['unit_number'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."VIN #".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">VIN #:</label>
			<div class="col-sm-8">' . $row['vin_number'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Serial #".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Serial #:</label>
			<div class="col-sm-8">' . $row['serial_number'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Description".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Description:</label>
			<div class="col-sm-8">' . $row['equ_description'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Category".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Category:</label>
			<div class="col-sm-8">' . $row['category'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Type".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Type:</label>
			<div class="col-sm-8">' . $row['type'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Make".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Make:</label>
			<div class="col-sm-8">' . $row['make'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Model".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Model:</label>
			<div class="col-sm-8">' . $row['model'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Unit of Measure".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Unit of Measure:</label>
			<div class="col-sm-8">' . $row['submodel'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Model Year".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Model Year:</label>
			<div class="col-sm-8">' . $row['model_year'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Label".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Equipment Label:</label>
			<div class="col-sm-8">' . $row['label'] . '</div>
		</div>';
	    }
		if (strpos($value_config, ','."Total Kilometres".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Total Kilometres:</label>
			<div class="col-sm-8">' . $row['total_kilometres'] . '</div>
		</div>';
	    }
		if (strpos($value_config, ','."Total Kilometres".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Leased:</label>
			<div class="col-sm-8">' . ($row['leased'] > 0 ? 'Yes' : 'No') . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Style".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Style:</label>
			<div class="col-sm-8">' . $row['style'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Vehicle Size".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Vehicle Size:</label>
			<div class="col-sm-8">' . $row['vehicle_size'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Color".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Color:</label>
			<div class="col-sm-8">' . $row['color'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Trim".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Trim:</label>
			<div class="col-sm-8">' . $row['trim'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Staff".',') !== FALSE) {
	    $staff_list = [];
	    foreach(explode(',', $row['staffid']) as $staff_id) {
	    	if($staff_id > 0) {
	    		$staff_list[] = get_contact($dbc, $staff_id);
	    	}
	    }
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Staff:</label>
			<div class="col-sm-8">' . implode(', ', $staff_list) . '</div>
		</div>';
	    }

	    if (strpos($value_config, ','."Fuel Type".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Fuel Type:</label>
			<div class="col-sm-8">' . $row['fuel_type'] . '</div>
		</div>';
	    }

	    if (strpos($value_config, ','."Tire Type".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Tire Type:</label>
			<div class="col-sm-8">' . $row['tire_type'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Drive Train".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Drive Train:</label>
			<div class="col-sm-8">' . $row['drive_train'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Licence Plate".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Licence Plate:</label>
			<div class="col-sm-8">' . $row['licence_plate'] . '</div>
		</div>';
	    }

	    if (strpos($value_config, ','."Nickname".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Nickname:</label>
			<div class="col-sm-8">' . $row['nickname'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Year Purchased".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Year Purchased:</label>
			<div class="col-sm-8">' . $row['year_purchased'] . '</div>
		</div>';

	    }

	    if (strpos($value_config, ','."Mileage".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Mileage:</label>
			<div class="col-sm-8">' . $row['mileage'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Hours Operated".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Hours Operated:</label>
			<div class="col-sm-8">' . $row['hours_operated'] . '</div>
		</div>';
	    }

	    if (strpos($value_config, ','."Cost".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Cost:</label>
			<div class="col-sm-8">' . $row['cost'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."CDN Cost Per Unit".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">CDN Cost Per Unit:</label>
			<div class="col-sm-8">' . $row['cnd_cost_per_unit'] . '</div>
		</div>';
	    }

	    if (strpos($value_config, ','."USD Cost Per Unit".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">USD Cost Per Unit:</label>
			<div class="col-sm-8">' . $row['usd_cost_per_unit'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Finance".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Finance:</label>
			<div class="col-sm-8">' . $row['finance'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Lease".',') !== FALSE) {

	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Lease:</label>
			<div class="col-sm-8">' . $row['lease'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Insurance".',') !== FALSE) {

	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Insurance:</label>
			<div class="col-sm-8">' . $row['insurance'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Hourly Rate:</label>
			<div class="col-sm-8">' . $row['hourly_rate'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Daily Rate".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Daily Rate:</label>
			<div class="col-sm-8">' . $row['daily_rate'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Semi-monthly Rate".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Semi-monthly Rate:</label>
			<div class="col-sm-8">' . $row['semi_monthly_rate'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Monthly Rate".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Monthly Rate:</label>
			<div class="col-sm-8">' . $row['monthly_rate'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Field Day Cost".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Field Day Cost:</label>
			<div class="col-sm-8">' . $row['field_day_cost'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Field Day Billable".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Field Day Billable:</label>
			<div class="col-sm-8">' . $row['field_day_billable'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."HR Rate Work".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">HR Rate Work:</label>
			<div class="col-sm-8">' . $row['hr_rate_work'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."HR Rate Travel".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">HR Rate Travel:</label>
			<div class="col-sm-8">' . $row['hr_rate_travel'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Billing Rate".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Billing Rate:</label>
			<div class="col-sm-8">$' . number_format($row['invoiced_amt'] / $row['invoiced_hours'],2) . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Billed Hours".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Total Billed Hours:</label>
			<div class="col-sm-8">' . round($row['invoiced_hours'],3) . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Billed Total".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Total Billed Amount:</label>
			<div class="col-sm-8">$' . number_format($row['invoiced_amt'],2) . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Expense Total".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Total Expenses:</label>
			<div class="col-sm-8">$' . number_format($row['expense_total'],2) . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Profit Total".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Total Profit:</label>
			<div class="col-sm-8">$' . number_format($row['invoiced_amt'] - $row['expense_total'],2) . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Next Service Date".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Next Service Date:</label>
			<div class="col-sm-8">' . $row['next_service_date'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Next Service Hours".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Next Service Hours:</label>
			<div class="col-sm-8">' . $row['next_service'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Next Service Description".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Next Service Description:</label>
			<div class="col-sm-8">' . $row['next_serv_desc'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Service Location".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Service Location:</label>
			<div class="col-sm-8">' . $row['service_location'] . '</div>
		</div>';

	    }

	    if (strpos($value_config, ','."Last Oil Filter Change (date)".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Last Oil Filter Change (date):</label>
			<div class="col-sm-8">' . $row['last_oil_filter_change_date'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Last Oil Filter Change (km)".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Last Oil Filter Change (km):</label>
			<div class="col-sm-8">' . $row['last_oil_filter_change'] . '</div>
		</div>';
	    }

	    if (strpos($value_config, ','."Next Oil Filter Change (date)".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Next Oil Filter Change (date):</label>
			<div class="col-sm-8">' . $row['next_oil_filter_change_date'] . '</div>
		</div>';
	    }

	    if (strpos($value_config, ','."Next Oil Filter Change (km)".',') !== FALSE) {

	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Next Oil Filter Change (km):</label>
			<div class="col-sm-8">' . $row['next_oil_filter_change'] . '</div>
		</div>';

	    }
	    if (strpos($value_config, ','."Last Inspection & Tune Up (date)".',') !== FALSE) {
	                echo '<div class="col-sm-6">
			<label class="col-sm-4">Last Inspection & Tune Up (date):</label>
			<div class="col-sm-8">' . $row['last_insp_tune_up_date'] . '</div>
		</div>';
	    }

	    if (strpos($value_config, ','."Last Inspection & Tune Up (km)".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Last Inspection & Tune Up (km):</label>
			<div class="col-sm-8">' . $row['last_insp_tune_up'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Next Inspection & Tune Up (date)".',') !== FALSE) {
	                echo '<div class="col-sm-6">
			<label class="col-sm-4">Next Inspection & Tune Up (date):</label>
			<div class="col-sm-8">' . $row['next_insp_tune_up_date'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Next Inspection & Tune Up (km)".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Next Inspection & Tune Up (km):</label>
			<div class="col-sm-8">' . $row['next_insp_tune_up'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Tire Condition".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Tire Condition:</label>
			<div class="col-sm-8">' . $row['tire_condition'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Last Tire Rotation (date)".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Last Tire Rotation (date):</label>
			<div class="col-sm-8">' . $row['last_tire_rotation_date'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Last Tire Rotation (km)".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Last Tire Rotation (km):</label>
			<div class="col-sm-8">' . $row['last_tire_rotation'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Next Tire Rotation (date)".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Next Tire Rotation (date):</label>
			<div class="col-sm-8">' . $row['next_tire_rotation_date'] . '</div>
		</div>';
		}
	    if (strpos($value_config, ','."Next Tire Rotation (km)".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Next Tire Rotation (km):</label>
			<div class="col-sm-8">' . $row['next_tire_rotation'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Registration Renewal date".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Registration Renewal Date:</label>
			<div class="col-sm-8"><a href="?edit='.$row['equipmentid'].'&target_field=reg_renewal_date">' . $row['reg_renewal_date'] . '</a></div>
		</div>';
	    }
	    if (strpos($value_config, ','."Insurance Renewal Date".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Insurance Renewal Date:</label>
			<div class="col-sm-8"><a href="?edit='.$row['equipmentid'].'&target_field=insurance_renewal">' . $row['insurance_renewal'] . '</a></div>
		</div>';
	    }
		if (strpos($value_config, ','."CVIP Ticket Renewal Date".',') !== FALSE) {
	         echo '<div class="col-sm-6">
			<label class="col-sm-4">CVIP Ticket Renewal Date:</label>
			<div class="col-sm-8">' . $row['cvip_renewal_date'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Region Dropdown".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Region:</label>
			<div class="col-sm-8">' . implode(', ',explode('*#*',$row['region'])) . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Location Dropdown".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Location:</label>
			<div class="col-sm-8">' . implode(', ',explode('*#*',$row['location'])) . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Classification Dropdown".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Classification:</label>
			<div class="col-sm-8">' . implode(', ',explode('*#*',$row['classification'])) . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Location".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Location:</label>
			<div class="col-sm-8">' . $row['location'] . '</div>
		</div>';
	    }

	    if (strpos($value_config, ','."LSD".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">LSD:</label>
			<div class="col-sm-8">' . $row['lsd'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Service History Link".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Service History:</label>
			<div class="col-sm-8"><a href="?edit='.$row['equipmentid'].'&subtab=service_schedules">View</a></div>
		</div>';
	    }
	    if (strpos($value_config, ','."Status".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Status:</label>
			<div class="col-sm-8">' . $row['status'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Ownership Status".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Ownership Status:</label>
			<div class="col-sm-8">' . $row['ownership_status'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Assigned Status".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Assigned Status:</label>
			<div class="col-sm-8">' . $row['assigned_status'] . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Quote Description:</label>
			<div class="col-sm-8">' . html_entity_decode($row['quote_description']) . '</div>
		</div>';
	    }
	    if (strpos($value_config, ','."Notes".',') !== FALSE) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Notes:</label>
			<div class="col-sm-8">' . $row['notes'] . '</div>
		</div>';
	    }

	    if(vuaed_visible_function($dbc, 'equipment') == 1) {
	    echo '<div class="col-sm-6">
			<label class="col-sm-4">Function:</label>
	        <div class="col-sm-8"><a href=\'?edit='.$row['equipmentid'].'\'>Edit</a> | '; echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&equipmentid='.$row['equipmentid'].'\' onclick="return confirm(\'Are you sure?\')">Archive</a></div>
	    </div>';
	    }
	    echo '<div class="clearfix"></div>';
	    echo '</div>';
	}
} else {
	echo '<h2>No Equipment Found.</h2>';
}

// Added Pagination //
if(isset($query)) {
	echo '<div class="pagination_links">';
    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
    echo '</div>';
}
// Pagination Finish //
?>

</div>
<script type="text/javascript">
function export_csv()
{
	//var hreflocation = window.location.href;
	window.location.href += "&export=yes";
}
</script>