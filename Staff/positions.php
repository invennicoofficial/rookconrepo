<?php include_once('../include.php');
checkAuthorised('staff');
// Pagination Config
$rowsPerPage = 25;
$pageNum = 1;
	
if(isset($_GET['page'])) {
	$pageNum = $_GET['page'];
}
$offset = ($pageNum - 1) * $rowsPerPage;

$filter_query = "";
if(isset($_POST['search_contacts_submit'])) {
	$filter_query = "AND `name` LIKE '%".$_POST['search_contacts']."%'";
}

$sql = "select * from positions where deleted=0 $filter_query order by name LIMIT $offset, $rowsPerPage";
$result = mysqli_query($dbc, $sql);
$row_count = mysqli_num_rows($result);
$sql_count = "select count(*) numrows from positions where deleted=0 $filter_query";
$total_count = $dbc->query($sql_count)->fetch_assoc()['numrows'];

$db_config = explode(',', get_config($dbc, 'positions_db_config'));
$rc_db_config = explode(',', get_config($dbc, 'pos_db_rate_fields'));
$rc_view_access = tile_visible($dbc, 'rate_card');
$rc_edit_access = vuaed_visible_function($dbc, 'rate_card');
$rc_subtab_access = check_subtab_persmission($dbc, 'rate_card', ROLE, 'position');
?>
<div class='iframe_holder' style='display:none;'>
	<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
	<span class='iframe_title' style='color:white; font-weight:bold; position: relative;top:58px; left: 20px; font-size: 30px;'></span>
	<iframe id="iframe_instead_of_window" style='width: 100%; overflow: hidden;' height="200px; border:0;" src=""></iframe>
</div>
<div class="row hide_on_iframe">
	<!-- <form name="form_sites" method="post" action="staff.php?tab=positions&filter=All" class="form-inline" role="form">
		<div class="col-xs-12 col-sm-4 col-lg-2 pad-top" style="margin-top:7px;">
			<span class="popover-examples list-inline"><a style="margin:5px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="This will search within the tab you have selected at the top."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<label for="search_contacts">Search Within Tab:</label>
		</div>
		<div class="col-sm-4 col-xs-12 col-lg-3 pad-top"><?php
			if ( isset ( $_POST[ 'search_contacts_submit' ] ) ) { ?>
				<input type="text" name="search_contacts" value="<?php echo $_POST['search_contacts']?>" class="form-control"><?php
			} else { ?>
				<input type="text" name="search_contacts" class="form-control"><?php
			} ?>
		</div>
		<div class="col-sm-4 col-xs-12 col-lg-3 pad-top pull-xs-right">
			<button type="submit" name="search_contacts_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
			<span class="popover-examples list-inline hide-on-mobile"><a style="margin:5px 0 0 15px;" data-toggle="tooltip" data-placement="top" title="Refreshes the page to display all contact information under the specific tab."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<button type="submit" name="display_all_contacts" value="Display All" class="btn brand-btn mobile-block hide-on-mobile">Display All</button>
		</div>
	</form> -->
	<div class="clearfix"></div>
	<div class="pull-right">
		<span class="popover-examples list-inline"><a style="margin:7px 5px 0 15px;" data-toggle="tooltip" data-placement="top" title="Click to download a PDF about the positions."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<!-- <a href="position_edit.php?target=pdf"><img src="<?php echo WEBSITE_URL; ?>/img/pdf.png" /></a> -->
		<a href="position_edit.php?target=pdf" class="btn brand-btn">Export PDF</a>
	</div>
	<div id="no-more-tables" class="triple-pad-top contacts-list">
		<?php if($row_count > 0):
			$colspan_add = 0;
			// Use pagination at top of page
			echo '<div class="pagination_links">';
			echo display_pagination($dbc, $sql_count, $pageNum, $rowsPerPage);
			echo '</div>';
			
			// Position Table Header
			echo "<table class='table table-bordered'>";
			echo "<tr class='hidden-xs hidden-sm'>";
			echo '<th>Position</th>';
			if(in_array('Rate Card Price', $db_config) && check_dashboard_persmission($dbc, 'staff', ROLE, 'Positions Rate Card Price')) {
				$colspan_add++;
				echo '<th>Rate Card Price</th>';
			}
			if(in_array('Rate Card', $db_config) && check_dashboard_persmission($dbc, 'staff', ROLE, 'Positions Rate Card') && $rc_view_access > 0 && $rc_subtab_access) {
				$colspan_add++;
				echo '<th>Rate Card</th>';
			}
			echo '<th>History</th>';
			echo '<th>Functions</th>';
			echo "</tr>";
			
			// Position Table Results
			while($row = mysqli_fetch_array($result)) {
				echo "<tr>";
				echo "<td data-title='Position'>{$row['name']}</td>";
				if(in_array('Rate Card Price', $db_config) && check_dashboard_persmission($dbc, 'staff', ROLE, 'Positions Rate Card Price')) {
	                $ratecards = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `position_rate_table` WHERE `position_id` = '{$row['position_id']}' AND `deleted` = 0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')"),MYSQLI_ASSOC);
                	$rate_fields = ['annual'=>'Annual Rate','monthly'=>'Monthly Rate','semi_month'=>'Semi-Monthly Rate','weekly'=>'Weekly Rate','daily'=>'Daily Rate','hourly'=>'Hourly Rate','hourly_work'=>'Hourly Rate (Work)','hourly_travel'=>'Hourly Rate (Travel)','field_day_actual'=>'Field Day Rate (Actual Cost)','field_day_bill'=>'Field Day Rate (Billable Rate)','cost'=>'Cost','price_admin'=>'Admin Price','price_wholesale'=>'Wholesale Price','price_commercial'=>'Commercial Price','price_client'=>'Client Price','minimum'=>'Minimum Billable','unit_price'=>'Unit Price','unit_cost'=>'Unit Cost','rent_price'=>'Rent Price','fee'=>'Fee'];
                	$rate_html = [];
	                foreach($ratecards as $ratecard) {
	                	foreach($rate_fields as $rate_key => $rate_field) {
	                		if(in_array($rate_key, $rc_db_config) && !empty($ratecard[$rate_key]) && $ratecard[$rate_key] != '0.00') {
	                			$rate_html[] = $rate_field.': $'.$ratecard[$rate_key];
	                		}
	                	}
	                }
	                echo '<td data-title="Rate Card Price">'.implode('<br>',$rate_html).'</td>';
				}
				if(in_array('Rate Card', $db_config) && check_dashboard_persmission($dbc, 'staff', ROLE, 'Positions Rate Card') && $rc_view_access > 0 && $rc_subtab_access) {
					echo '<td data-title="Rate Card"><a href="" onclick="overlayIFrameSlider(\''.WEBSITE_URL.'/Staff/edit_position_rate_card.php?id='.$row['position_id'].'&from_type=dashboard\', \'auto\', false, true, $(\'#staff_div\').height() + 20); return false;">View'.($rc_edit_access > 0 && $rc_subtab_access ? '/Edit': '').' Rate Card</a></td>';
				}
				echo "<td data-title='History'><a data-id='{$row['position_id']}' class='history-link'>View All</a></td>";
				echo "<td data-title='Function' style='text-align:center; width:8em;'>";
				$function_urls = [];
				if(edit_visible_function($dbc, 'staff') > 0) {
					$function_urls[] = '<a href="position_edit.php?id='.$row['position_id'].'">Edit</a>';
				}
				if(archive_visible_function($dbc, 'staff') > 0) {
					$function_urls[] = '<a href="position_edit.php?delete=true&id='.$row['position_id'].'" onclick="return confirm(\'Are you sure you wish to delete this position?\')">Delete</a>';
				}
				echo implode(' | ', $function_urls);
				echo "</td>";
				echo "</tr>";
			}
			
			// Position Table Footer
			echo "<tfoot><td colspan=".(3+$colspan_add++).">$total_count Total Positions</td></tfoot>";
			echo "</table>";
			
			// Use pagination at bottom of page
			echo '<div class="pagination_links">';
			echo display_pagination($dbc, $sql_count, $pageNum, $rowsPerPage);
			echo '</div>';
		else:
			echo "<h2>No Positions found!</h2><!--$sql-->";
		endif; ?>
	</div>
	<script>
	$(document).ready(function() {
		$('.history-link').click(function() {
			var id = $(this).data('id');
			$('#iframe_instead_of_window').attr('src', 'position_history.php?id='+id);
			$('.iframe_title').text('Position History');
			$('.iframe_holder').show();
			$('.hide_on_iframe').hide();
			$('#iframe_instead_of_window').on('load', function() {
			   $(this).height($(this).get(0).contentWindow.document.body.scrollHeight);
			});
		});

		$('.close_iframer').click(function(){
			$('.iframe_holder').hide();
			$('.hide_on_iframe').show();
		});

		$('iframe').load(function() {
			this.contentWindow.document.body.style.overflow = 'hidden';
			this.contentWindow.document.body.style.minHeight = '0';
			this.contentWindow.document.body.style.paddingBottom = '5em';
			this.style.height = (this.contentWindow.document.body.offsetHeight + 80) + 'px';
		});
	});
	</script>
	<style type='text/css'>
		.history-link {
			cursor: pointer;
		}
	</style>
</div>