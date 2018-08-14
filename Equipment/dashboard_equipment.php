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
include('dashboard_equipment_list.php');
?>

</div>
<script type="text/javascript">
function export_csv()
{
	//var hreflocation = window.location.href;
	window.location.href += "&export=yes";
}
</script>