<?php
/*
 * List all function here.
 * Update Reports switch-case in root/tile_data.php whenever this page updates
 */
function reports_tiles($dbc) { ?>
    <h1>Reports Dashboard </h1>
	<script>
	$(document).ready(function() {
		$('[name=select_report]').off('change').change(function() {
			if(this.value != '') {
				window.location.replace(this.value);
			}
		});
	});
	</script>

    <?php if(config_visible_function($dbc, 'report') == 1) {
        echo '<a href="config_reports.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
    } ?>
    <br>
    <?php $file_name = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

    $active1 = $active2 = $active3 = $active4 = $active5 = $active6 = $active7a = $active7b = $active8 = $active9 = $active10 = $active11 = $active12 = $active13a = $active13b = $active14 = $active15 = '';

    $active16 = $active17 = $active18 = $active19 = $active20 = $active21 = $active22 = $active23 = $active24 = $active25 = $active26 = $active27 = $active28 = $active29 = $active29b = $active30 = $active31 = $active32 = $active32a = $active33 = $active34a = $active34b = $active35 = $active36 = $active37 = $active38 = $active39 = $active40 = $active41 = $active42 = $active43 = $active44 = $active45 = $active46 = $active47 = $active48 = $active49 = $active50 = $active51 = $active52 = $active53 = '';

    $active54 = $active55 = $active55b = '';

    $active56 = $active56a = $active57 = $active58 = $active59 = $active60 = $active61 = $active62 = $active63 = $active64 = $active65 = $active66 = $active67 = $active68 = $active69 = $active70 = $active71 = $active72 = $active73 = $active74 = $active75 = $active76 = $active77 = $active78 = $active101 = '';

    $active79 = $active80 = $active81 = $active82 = $active83 = $active84 = $active85 = $active86 = $active87 = $active89 = $active90 = $active91 = $active92 = $active93 = $active94 = $active95 = $active96 = $active97 = $active98 = $active100 = '';

    if($file_name == 'report_daysheet.php') {
        $active1 = 'active_tab';
    }
    if($file_name == 'report_stat.php') {
        $active2 = 'active_tab';
    }
    if($file_name == 'report_bb_vs_not_bb.php') {
        $active3 = 'active_tab';
    }
    if($file_name == 'report_injury.php') {
        $active4 = 'active_tab';
    }
    if($file_name == 'report_treatment.php') {
        $active5 = 'active_tab';
    }
    if($file_name == 'report_pos_coupons.php') {
        $active6 = 'active_tab';
    }
    if($file_name == 'report_equipment_list.php') {
        $active7a = 'active_tab';
    }
    if($file_name == 'report_equip_transfer.php') {
        $active7b = 'active_tab';
    }
    if($file_name == 'report_work_order.php') {
        $active8= 'active_tab';
    }
    if($file_name == 'reports_staff_tickets.php') {
        $active9 = 'active_tab';
    }
	if($file_name == 'reports_daysheet_reports.php') {
        $active10 = 'active_tab';
    }
	if($file_name == 'report_daily_appoint_summary.php' || $file_name == 'report_daily_appoint_summary_breakdown.php') {
        $active11 = 'active_tab';
    }
	if($file_name == 'report_patient_block_booking.php') {
		$active12 = 'active_tab';
	}
	if($file_name == 'report_tally_board.php') {
		$active13a = 'active_tab';
	}
	if($file_name == 'report_assessment_followup.php') {
		$active13b = 'active_tab';
	}
    if($file_name == 'report_postalcode.php') {
        $active14 = 'active_tab';
    }
    if($file_name == 'report_field_jobs.php') {
        $active15 = 'active_tab';
    }
    if($file_name == 'report_shop_work_orders.php') {
        $active16 = 'active_tab';
    }
    if($file_name == 'report_site_work_orders.php') {
        $active17 = 'active_tab';
    }
	if($file_name == 'reports_scrum_business_productivity_summary.php') {
		$active18 = 'active_tab';
	}
	if($file_name == 'reports_scrum_staff_productivity_summary.php') {
		$active19 = 'active_tab';
	}
	if($file_name == 'reports_scrum_status_report.php') {
		$active20 = 'active_tab';
	}
	if($file_name == 'report_drop_off_analysis.php') {
		$active21 = 'active_tab';
	}
	if($file_name == 'report_discharge.php') {
		$active22 = 'active_tab';
	}
    if($file_name == 'report_ticket.php') {
        $active23 = 'active_tab';
    }
    if($file_name == 'report_web_referral.php') {
        $active24 = 'active_tab';
    }
    if($file_name == 'report_site_work_time.php') {
        $active25 = 'active_tab';
    }
    if($file_name == 'report_site_work_driving.php') {
        $active26 = 'active_tab';
    }
    if($file_name == 'reports_purchase_orders.php') {
        $active27 = 'active_tab';
    }
    if($file_name == 'report_inventory_log.php') {
        $active28 = 'active_tab';
    }
    if($file_name == 'reports_pos.php') {
        $active29 = 'active_tab';
    }
    if($file_name == 'reports_pos_advanced.php') {
        $active29b = 'active_tab';
    }
    if($file_name == 'report_cc_on_file.php') {
        $active30 = 'active_tab';
    }
    if($file_name == 'report_daily_validation.php') {
        $active31 = 'active_tab';
    }
    if($file_name == 'report_pos_daily_validation.php') {
        $active32 = 'active_tab';
    }
    if($file_name == 'report_pos_advanced_daily_validation.php') {
        $active32a = 'active_tab';
    }
    if($file_name == 'report_daily_deposit.php') {
        $active33 = 'active_tab';
    }
    if($file_name == 'report_compensation.php') {
        $active34a = 'active_tab';
    }
    if($file_name == 'report_compensation_adjustments.php') {
        $active34b = 'active_tab';
    }
    if($file_name == 'report_hourly_compensation.php') {
        $active35 = 'active_tab';
    }
    if($file_name == 'report_review_sales.php') {
        $active36 = 'active_tab';
    }
    if($file_name == 'report_invoice_sales_summary.php') {
        $active37 = 'active_tab';
    }
    if($file_name == 'report_sales_by_customer_summary.php') {
        $active38 = 'active_tab';
    }
    if($file_name == 'report_sales_by_customer_detail.php') {
        $active39 = 'active_tab';
    }
    if($file_name == 'report_sales_by_product_service_summary.php') {
        $active40 = 'active_tab';
    }
    if($file_name == 'report_sales_by_inventory_summary.php') {
        $active41 = 'active_tab';
    }
    if($file_name == 'report_daily_sales_summary.php') {
        $active42 = 'active_tab';
    }
    if($file_name == 'report_general_inventory.php') {
        $active43 = 'active_tab';
    }
    if($file_name == 'report_unassigned_invoices.php') {
        $active44 = 'active_tab';
    }
    if($file_name == 'report_revenue.php') {
        $active45 = 'active_tab';
    }
    if($file_name == 'report_expenses.php') {
        $active46 = 'active_tab';
    }
    if($file_name == 'report_sales_by_product_service_detail.php') {
        $active47 = 'active_tab';
    }
    if($file_name == 'report_customer_contact_list.php') {
        $active48 = 'active_tab';
    }
    if($file_name == 'report_payment_method_list.php') {
        $active49 = 'active_tab';
    }
    if($file_name == 'report_patient_appoint_history.php') {
        $active50 = 'active_tab';
    }
    if($file_name == 'report_receipt_summary.php') {
        $active51 = 'active_tab';
    }
    if($file_name == 'report_referral.php') {
        $active52 = 'active_tab';
    }
    if($file_name == 'report_gross_revenue_by_staff.php') {
        $active53 = 'active_tab';
    }
    if($file_name == 'report_patient_unpaid_invoices.php' || $file_name == 'report_patient_paid_invoices.php') {
        $active54 = 'active_tab';
    }
    if($file_name == 'report_pos_daily_sales_summary.php') {
        $active55 = 'active_tab';
    }
    if($file_name == 'report_pos_advanced_daily_sales_summary.php') {
        $active55b = 'active_tab';
    }
    if($file_name == 'report_profit_loss.php') {
        $active56 = 'active_tab';
    }
    if($file_name == 'report_profit_loss_pos_advanced.php') {
        $active56a = 'active_tab';
    }
    if($file_name == 'report_transaction_list_by_customer.php') {
        $active57 = 'active_tab';
    }
    if($file_name == 'report_unbilled_charges.php') {
        $active58 = 'active_tab';
    }
    if($file_name == 'report_deposit_detail.php') {
        $active59 = 'active_tab';
    }
    if($file_name == 'report_ar_aging_summary.php') {
        $active60 = 'active_tab';
    }
    if($file_name == 'report_receivables_patient_summary.php') {
        $active61 = 'active_tab';
    }
    if($file_name == 'report_receivables_summary.php') {
        $active62 = 'active_tab';
    }
    if($file_name == 'report_receivables.php') {
        $active63 = 'active_tab';
    }
    if($file_name == 'report_account_receivable.php') {
        $active64 = 'active_tab';
    }
    if($file_name == 'report_customer_balance_detail.php') {
        $active65 = 'active_tab';
    }
    if($file_name == 'report_collections_report.php') {
        $active66 = 'active_tab';
    }
    if($file_name == 'report_invoice_list.php') {
        $active67 = 'active_tab';
    }
    if($file_name == 'report_pos_receivables.php') {
        $active68 = 'active_tab';
    }
    if($file_name == 'report_customer_stats.php') {
        $active69 = 'active_tab';
    }
	if($file_name == 'report_demographics.php') {
        $active70 = 'active_tab';
    }
	if($file_name == 'ui_invoice_reports.php') {
        $active71 = 'active_tab';
    }
	if($file_name == 'report_crm_recommend_date.php') {
        $active72 = 'active_tab';
    }
	if($file_name == 'report_crm_recommend_customer.php') {
        $active73 = 'active_tab';
    }
	if($file_name == 'report_marketing_pro_bono.php') {
        $active74 = 'active_tab';
    }
	if($file_name == 'report_checklist_time.php') {
        $active75 = 'active_tab';
    }
	if($file_name == 'report_phone_communication.php') {
        $active76 = 'active_tab';
    }
	if($file_name == 'report_marketing_net_promoter_score.php') {
        $active77 = 'active_tab';
    }
    if($file_name == 'report_sales_by_product_service_category.php') {
        $active78 = 'active_tab';
    }
    if($file_name == 'report_contact_report_by_status.php') {
        $active101 = 'active_tab';
    }
    // Profit & Loss
    $active80 = ( $file_name=='report_pnl_revenue_receivables.php' ) ? 'active_tab' : '';
    $active81 = ( $file_name=='report_pnl_staff_compensation.php' ) ? 'active_tab' : '';
    $active82 = ( $file_name=='report_pnl_expenses.php' ) ? 'active_tab' : '';
    $active83 = ( $file_name=='report_pnl_costs.php' ) ? 'active_tab' : '';
    $active84 = ( $file_name=='report_pnl_summary.php' ) ? 'active_tab' : '';

	// Stat Holiday Pay Breakdown
    $active85 = ( $file_name=='report_stat_holiday_pay.php' ) ? 'active_tab' : '';
    $active100 = ( $file_name=='report_compensation_timesheet_payroll.php' ) ? 'active_tab' : '';

    $active86 = ( $file_name=='report_operations_shop_task_time.php' ) ? 'active_tab' : '';
    $active87 = ( $file_name=='report_operations_shop_time.php' ) ? 'active_tab' : '';
	  $active88 = ( $file_name=='report_ticket_time_summary.php' ) ? 'active_tab' : '';

    $active89 = ( $file_name=='report_operation_service_usage.php' ) ? 'active_tab' : '';
	  $active90 = ( $file_name=='report_operations_ticket_attached.php' ) ? 'active_tab' : '';

    $active91 = ( $file_name=='report_marketing_contact_pc.php' ) ? 'active_tab' : '';
    $active92 = ( $file_name=='report_operation_ticket_notes.php' ) ? 'active_tab' : '';
    $active93 = ( $file_name=='report_task_time.php' ) ? 'active_tab' : '';
    $active94 = ( $file_name=='report_estimates.php' ) ? 'active_tab' : '';
    $active95 = ( $file_name=='report_download_tracking.php' ) ? 'active_tab' : '';

    $active96 = ( $file_name=='report_marketing_site_visitors.php' ) ? 'active_tab' : '';
    $active97 = ( $file_name=='report_marketing_cart_abandonment.php' ) ? 'active_tab' : '';

    $active98 = ( $file_name=='report_operation_ticket_by_task.php' ) ? 'active_tab' : '';

    $value_config = ','.get_config($dbc, 'reports_dashboard').',';
    $report_tabs = !empty(get_config($dbc, 'report_tabs')) ? get_config($dbc, 'report_tabs') : 'operations,sales,ar,marketing,compensation,pnl,customer,staff';
    $report_tabs = explode(',', $report_tabs);
    if(empty($_GET['type'])) {
        $_GET['type'] = $report_tabs[0];
    } ?>
	<div class="tab-container">
        <?php if(in_array('operations',$report_tabs)) { ?>
            <div class="tab pull-left"><a href="report_tiles.php?type=operations"><button type="button" class="btn brand-btn mobile-block mobile-100 <?= ( $_GET['type']=='operations' || empty($_GET['type']) ? 'active_tab' : '' ) ?>">Operations</button></a></div>
        <?php } ?>
        <?php if(in_array('sales',$report_tabs)) { ?>
            <div class="tab pull-left"><a href="report_tiles.php?type=sales"><button type="button" class="btn brand-btn mobile-block mobile-100 <?= ( $_GET['type']=='sales' ? 'active_tab' : '' ) ?>">Sales</button></a></div>
        <?php } ?>
        <?php if(in_array('ar',$report_tabs)) { ?>
            <div class="tab pull-left"><a href="report_tiles.php?type=ar"><button type="button" class="btn brand-btn mobile-block mobile-100 <?= ( $_GET['type']=='ar' ? 'active_tab' : '' ) ?>">Accounts Receivable</button></a></div>
        <?php } ?>
        <?php if(in_array('marketing',$report_tabs)) { ?>
            <div class="tab pull-left"><a href="report_tiles.php?type=marketing"><button type="button" class="btn brand-btn mobile-block mobile-100 <?= ( $_GET['type']=='marketing' ? 'active_tab' : '' ) ?>">Marketing</button></a></div>
        <?php } ?>
        <?php if(in_array('compensation',$report_tabs)) { ?>
            <div class="tab pull-left"><a href="report_tiles.php?type=compensation"><button type="button" class="btn brand-btn mobile-block mobile-100 <?= ( $_GET['type']=='compensation' ? 'active_tab' : '' ) ?>">Compensation</button></a></div>
        <?php } ?>
        <?php if(in_array('pnl',$report_tabs)) { ?>
            <div class="tab pull-left"><a href="report_tiles.php?type=pnl"><button type="button" class="btn brand-btn mobile-block mobile-100 <?= ( $_GET['type']=='pnl' ? 'active_tab' : '' ) ?>">Profit &amp; Loss</button></a></div>
        <?php } ?>
        <?php if(in_array('customer',$report_tabs)) { ?>
            <div class="tab pull-left"><a href="report_tiles.php?type=customer"><button type="button" class="btn brand-btn mobile-block mobile-100 <?= ( $_GET['type']=='customer' ? 'active_tab' : '' ) ?>">Customer</button></a></div>
        <?php } ?>
        <?php if(in_array('staff',$report_tabs)) { ?>
            <div class="tab pull-left"><a href="report_tiles.php?type=staff"><button type="button" class="btn brand-btn mobile-block mobile-100 <?= ( $_GET['type']=='staff' ? 'active_tab' : '' ) ?>">Staff</button></a></div>
        <?php } ?>
        <div class="clearfix"></div>
	</div>

	<div class="form-group form-horizontal">
		<label class="col-sm-4 control-label">Report:</label>
		<div class="col-sm-8">
			<select class="chosen-select-deselect" data-placeholder="Select Report" name="select_report">
				<option></option>
				<?php
					/* Hide Kristi from accessing Profit & Loss report on SEA (temp fix)
					 * Code also added on report_profit_loss.php */
					$contactid = $_SESSION['contactid'];
					if ( $_SERVER['SERVER_NAME'] == 'sea-alberta.rookconnect.com' || $_SERVER['SERVER_NAME'] == 'sea-regina.rookconnect.com' || $_SERVER['SERVER_NAME'] == 'sea-saskatoon.rookconnect.com' || $_SERVER['SERVER_NAME'] == 'sea-vancouver.rookconnect.com' || $_SERVER['SERVER_NAME'] == 'sea.freshfocussoftware.com' ) {
						$results = mysqli_query ( $dbc, "SELECT `user_name` FROM `contacts` WHERE `contactid`='$contactid'");
						while ( $row = mysqli_fetch_assoc ( $results) ) {
							$user_name = $row[ 'user_name' ];
							if ( $user_name == 'kristi' ) {
								$sea_kristi = true;
								break;
							}
						}
					}

				// Operations
				if($_GET['type'] == 'operations' || empty($_GET['type'])) {
					if (strpos($value_config, ',Daysheet,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'pt_daysheet' ) === true) { ?>
						<option value='report_daysheet.php?type=operations' <?= 'active_tab' == $active1 ? 'selected' : '' ?>>Therapist Day Sheet</option><?php
					}
					if (strpos($value_config, ',Therapist Stats,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'pt_stats' ) === true) { ?>
						<option value='report_stat.php?type=operations' <?= 'active_tab' == $active2 ? 'selected' : '' ?>>Therapist Stats</option><?php
					}
					if (strpos($value_config, ',Block Booking vs Not Block Booking,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'bb_v_nbb' ) === true) { ?>
						<option value='report_bb_vs_not_bb.php?type=operations' <?= 'active_tab' == $active3 ? 'selected' : '' ?>>Block Booking vs Not Block Booking</option><?php
					}
					if (strpos($value_config, ',Injury Type,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'injury_type' ) === true) { ?>
						<option value='report_injury.php?type=operations' <?= 'active_tab' == $active4 ? 'selected' : '' ?>>Injury Type</option><?php
					}
					if (strpos($value_config, ',Treatment Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'treatment' ) === true) { ?>
						<option value='report_treatment.php?type=operations' <?= 'active_tab' == $active5 ? 'selected' : '' ?>>Treatment Report</option><?php
					}
					if (strpos($value_config, ',Equipment List,') !== false && $sea_kristi !== TRUE && check_subtab_persmission( $dbc, 'report', ROLE, 'equipment_list' ) === true) { ?>
						<option value='report_equipment_list.php?type=operations' <?= 'active_tab' == $active7a ? 'selected' : '' ?>>Equipment List</option><?php
					}
					if (strpos($value_config, ',Equipment Transfer,') !== false && $sea_kristi !== TRUE && check_subtab_persmission( $dbc, 'report', ROLE, 'equip_transfer' ) === true) { ?>
						<option value='report_equip_transfer.php?type=operations' <?= 'active_tab' == $active7b ? 'selected' : '' ?>>Equipment Transfer History</option><?php
					}
					if (strpos($value_config, ',Work Order,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'work_order' ) === true) { ?>
						<option value='report_work_order.php?type=operations' <?= 'active_tab' == $active8 ? 'selected' : '' ?>>Work Order</option><?php
					}
					if (strpos($value_config, ',Staff Tickets,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_tickets' ) === true) { ?>
						<option value='reports_staff_tickets.php?type=operations' <?= 'active_tab' == $active9 ? 'selected' : '' ?>>Staff <?= TICKET_TILE ?></option><?php
					}
					if (strpos($value_config, ',Day Sheet Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'day_sheet_report' ) === true) { ?>
						<option value='reports_daysheet_reports.php?type=operations' <?= 'active_tab' == $active10 ? 'selected' : '' ?>>Day Sheet Report</option><?php
					}
					if (strpos($value_config, ',Appointment Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'appt_summary' ) === true) { ?>
						<option value='report_daily_appoint_summary.php?type=operations' <?= 'active_tab' == $active11 ? 'selected' : '' ?>>Appointment Summary</option><?php
					}
					if (strpos($value_config, ',Patient Block Booking,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'block_booking' ) === true) { ?>
						<option value='report_patient_block_booking.php?type=operations' <?= 'active_tab' == $active12 ? 'selected' : '' ?>>Block Booking</option><?php
					}
					if (strpos($value_config, ',Assessment Tally Board,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'assessment_tallyboard' ) === true) { ?>
						<option value='report_tally_board.php?type=operations' <?= 'active_tab' == $active13a ? 'selected' : '' ?>>Assessment Tally Board</option><?php
					}
					if (strpos($value_config, ',Assessment Follow Up,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'assessment_followup' ) === true) { ?>
						<option value='report_assessment_followup.php?type=operations' <?= 'active_tab' == $active13b ? 'selected' : '' ?>>Assessment Follow Ups</option><?php
					}
					if (strpos($value_config, ',Field Jobs,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'field_jobs' ) === true) { ?>
						<option value='report_field_jobs.php?type=operations' <?= 'active_tab' == $active15 ? 'selected' : '' ?>>Field Jobs</option><?php
					}
					if (strpos($value_config, ',Shop Work Orders,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'shop_work_orders' ) === true) { ?>
						<option value='report_shop_work_orders.php?type=operations' <?= 'active_tab' == $active16 ? 'selected' : '' ?>>Shop Work Orders</option><?php
					}
					if (strpos($value_config, ',Shop Work Order Time') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'shop_work_order_time' ) === true) { ?>
						<option value='report_operations_shop_time.php?type=operations' <?= 'active_tab' == $active87 ? 'selected' : '' ?>>Shop Work Order Time</option><?php
					}
					if (strpos($value_config, ',Shop Work Order Task Time,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'shop_work_order_task' ) === true) { ?>
						<option value='report_operations_shop_task_time.php?type=operations' <?= 'active_tab' == $active86 ? 'selected' : '' ?>>Shop Work Order Time by Task</option><?php
					}
					if (strpos($value_config, ',Site Work Orders,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'site_work_orders' ) === true) { ?>
						<option value='report_site_work_orders.php?type=operations' <?= 'active_tab' == $active17 ? 'selected' : '' ?>>Site Work Orders</option><?php
					}
					if (strpos($value_config, ',Scrum Business Productivity Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'scrum_business_productivity_summary' ) === true) { ?>
						<option value='reports_scrum_business_productivity_summary.php?type=operations' <?= 'active_tab' == $active18 ? 'selected' : '' ?>>Scrum Business Productivity Summary</option><?php
					}
					if (strpos($value_config, ',Scrum Staff Productivity Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'scrum_staff_productivity_summary' ) === true) { ?>
						<option value='reports_scrum_staff_productivity_summary.php?type=operations' <?= 'active_tab' == $active19 ? 'selected' : '' ?>>Scrum Staff Productivity Summary</option><?php
					}
					if (strpos($value_config, ',Scrum Status Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'scrum_status_report' ) === true) { ?>
						<option value='reports_scrum_status_report.php?type=operations' <?= 'active_tab' == $active20 ? 'selected' : '' ?>>Scrum Status Report</option><?php
					}
					if (strpos($value_config, ',Drop Off Analysis,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'dropoff_analysis' ) === true) { ?>
						<option value='report_drop_off_analysis.php?type=operations' <?= 'active_tab' == $active21 ? 'selected' : '' ?>>Drop Off Analysis</option><?php
					}
					if (strpos($value_config, ',Discharge Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'discharge' ) === true) { ?>
						<option value='report_discharge.php?type=operations' <?= 'active_tab' == $active22 ? 'selected' : '' ?>>Discharge Report</option><?php
					}
					if (strpos($value_config, ',Ticket Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ticket_report' ) === true) { ?>
						<option value='report_ticket.php?type=operations' <?= 'active_tab' == $active23 ? 'selected' : '' ?>><?= TICKET_NOUN ?> Report</option><?php
					}
					if (strpos($value_config, ',Site Work Time,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'site_work_time' ) === true) { ?>
						<option value='report_site_work_time.php?type=operations' <?= 'active_tab' == $active25 ? 'selected' : '' ?>>Site Work Order Time on Site</option><?php
					}
					if (strpos($value_config, ',Site Work Driving,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'site_work_driving_logs' ) === true) { ?>
						<option value='report_site_work_driving.php?type=operations' <?= 'active_tab' == $active26 ? 'selected' : '' ?>>Site Work Order Driving Logs</option><?php
					}
					if (strpos($value_config, ',Purchase Orders,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'purchase_orders' ) === true) { ?>
						<option value='reports_purchase_orders.php?type=operations' <?= 'active_tab' == $active27 ? 'selected' : '' ?>>Purchase Orders</option><?php
					}
					if (strpos($value_config, ',Inventory Log,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'inventory_log' ) === true) { ?>
						<option value='report_inventory_log.php?type=operations' <?= 'active_tab' == $active28 ? 'selected' : '' ?>>Inventory Log</option><?php
					}
					if (strpos($value_config, ',Point of Sale,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'point_of_sale' ) === true) {
						if(get_tile_title($dbc) == '' || get_tile_title($dbc) == NULL ) { $poser = "Point of Sale"; } else { $poser = get_tile_title($dbc); } ?>
						<option value='reports_pos.php?type=operations' <?= 'active_tab' == $active29 ? 'selected' : '' ?>><?php echo $poser; ?></option><?php
					}
					if (strpos($value_config, ',POS,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'point_of_sale_advanced' ) === true) {
						if(get_tile_title($dbc) == '' || get_tile_title($dbc) == NULL ) { $poser = "Point of Sale (Advanced)"; } else { $poser = get_tile_title($dbc) .' (Advanced)'; } ?>
						<option value='reports_pos_advanced.php?type=operations' <?= 'active_tab' == $active29b ? 'selected' : '' ?>><?php echo $poser; ?></option><?php
					}
					if (strpos($value_config, ',Credit Card on File,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'credit_card_on_file' ) === true) { ?>
						<option value='report_cc_on_file.php?type=operations' <?= 'active_tab' == $active30 ? 'selected' : '' ?>>Credit Card on File</option><?php
					}
					if (strpos($value_config, ',Checklist Time,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'checklist_time' ) === true) { ?>
						<option value='report_checklist_time.php?type=operations' <?= 'active_tab' == $active75 ? 'selected' : '' ?>>Checklist Time Tracking</option><?php
					}
					if (strpos($value_config, ',Tasklist Time,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'tasklist_time' ) === true) { ?>
						<option value='report_task_time.php?type=operations' <?= 'active_tab' == $active75 ? 'selected' : '' ?>>Task Time Tracking</option><?php
					}
						if (strpos($value_config, ',Ticket Time Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ticket_time_summary' ) === true) { ?>
						<option value='report_ticket_time_summary.php?type=operations' <?= 'active_tab' == $active88 ? 'selected' : '' ?>>Ticket Time Summary</option><?php
					}
					if (strpos($value_config, ',Service Usage Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'service_usage' ) === true) { ?>
						<option value='report_operation_service_usage.php?type=operations' <?= 'active_tab' == $active89 ? 'selected' : '' ?>>% BREAKDOWN OF SERVICES SOLD</option><?php
					}
					if (strpos($value_config, ',Ticket Attached,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ticket_attached' ) === true) { ?>
						<option value='report_operations_ticket_attached.php?type=operations' <?= 'active_tab' == $active90 ? 'selected' : '' ?>>Attached to <?= TICKET_TILE ?></option><?php
					}
					if (strpos($value_config, ',Ticket Deleted Notes,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ticket_deleted_notes' ) === true) { ?>
						<option value='report_operation_ticket_notes.php?type=operations' <?= 'active_tab' == $active92 ? 'selected' : '' ?>>Archived <?= TICKET_NOUN ?> Notes</option><?php
					}
					if (strpos($value_config, ',Download Tracker,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'report_download_tracker' ) === true) { ?>
						<option value='report_download_tracking.php?type=operations' <?= 'active_tab' == $active95 ? 'selected' : '' ?>>Downloaded Reports Tracker</option><?php
					}
					if (strpos($value_config, ',Ticket Inventory Transport,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ticket_inventory_transport' ) === true) { ?>
						<option value='report_inventory_transport.php?type=operations' <?= $file_name == 'report_inventory_transport.php' ? 'selected' : '' ?>><?= TICKET_NOUN ?> Transport of Inventory</option><?php
					}
					if (strpos($value_config, ',Dispatch Travel Time,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'dispatch_time' ) === true) { ?>
						<option value='report_operation_ticket_dispatch_time.php?type=operations' <?= $file_name == 'report_operation_ticket_dispatch_time.php' ? 'selected' : '' ?>>Dispatch <?= TICKET_NOUN ?> Travel Time</option><?php
					}
                    if (strpos($value_config, ',Time Sheet,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'time_sheet' ) === true) { ?>
                        <option value='report_operations_time_sheet.php?type=operations' <?= $file_name == 'report_operations_time_sheet.php' ? 'selected' : '' ?>>Time Sheets Report</option><?php
                    }
                    if (strpos($value_config, ',Ticket Activity Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ticket_activity' ) === true) { ?>
                        <option value='report_operation_ticket_tasks.php?type=operations' <?= $file_name == 'report_operation_ticket_tasks.php' ? 'selected' : '' ?>><?= TICKET_NOUN ?> Activity Report per Customer</option><?php
                    }
                    if (strpos($value_config, ',Ticket by Task,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ticket_by_task' ) === true) { ?>
                        <option value='report_operation_ticket_by_task.php?type=operations' <?= $file_name == 'report_operation_ticket_by_task.php' ? 'selected' : '' ?>><?= TICKET_NOUN ?> by Task</option><?php
                    }
                    if (strpos($value_config, ',Rate Card Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'rate_card_report' ) === true) { ?>
                        <option value='report_operations_rate_cards.php?type=operations' <?= $file_name == 'report_operations_rate_cards.php' ? 'selected' : '' ?>>Rate Cards Report</option><?php
                    }
                    if (strpos($value_config, ',Import Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'import_summary' ) === true) { ?>
                        <option value='report_import_summary.php?type=operations' <?= $file_name == 'report_import_summary.php' ? 'selected' : '' ?>>Import Summary Report</option><?php
                    }
                    if (strpos($value_config, ',Import Details,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'import_details' ) === true) { ?>
                        <option value='report_import_details.php?type=operations' <?= $file_name == 'report_import_details.php' ? 'selected' : '' ?>>Detailed Import Report</option><?php
                    }
                    if (strpos($value_config, ',Ticket Manifest Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ticket_manifest_summary' ) === true) { ?>
                        <option value='report_daily_manifest_summary.php?type=operations' <?= $file_name == 'report_daily_manifest_summary.php' ? 'selected' : '' ?>>Manifest Daily Summary</option><?php
                    }
				}
				// Sales
				else if($_GET['type'] == 'sales') {
					if (strpos($value_config, ',Validation by Therapist,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'pt_validation' ) === true) { ?>
						<option value='report_daily_validation.php?type=sales' <?= 'active_tab' == $active31 ? 'selected' : '' ?>>Validation by Therapist</option><?php
					}
					if (strpos($value_config, ',POS Validation,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'validation' ) === true) { ?>
						<option value='report_pos_daily_validation.php?type=sales' <?= 'active_tab' == $active32 ? 'selected' : '' ?>>POS Validation</option><?php
					}
					if (strpos($value_config, ',POS Advanced Validation,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'validation_advanced' ) === true) { ?>
						<option value='report_pos_advanced_daily_validation.php?type=sales' <?= 'active_tab' == $active32a ? 'selected' : '' ?>>POS Advanced Validation</option><?php
					}
					if (strpos($value_config, ',Daily Deposit Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'daily_deposit' ) === true) { ?>
						<option value='report_daily_deposit.php?type=sales' <?= 'active_tab' == $active33 ? 'selected' : '' ?>>Daily Deposit Report</option><?php
					}
					if (strpos($value_config, ',Monthly Sales by Injury Type,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_injury_monthly' ) === true) { ?>
						<option value='report_review_sales.php?type=sales' <?= 'active_tab' == $active36 ? 'selected' : '' ?>>Monthly Sales by Injury Type</option><?php
					}
					if (strpos($value_config, ',Invoice Sales Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_invoice' ) === true) { ?>
						<option value='report_invoice_sales_summary.php?type=sales' <?= 'active_tab' == $active37 ? 'selected' : '' ?>>Invoice Sales Summary</option><?php
					}
                    if (strpos($value_config, ',Sales by Customer Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_customer' ) === true) { ?>
                        <option value='report_sales_by_customer_summary.php?type=sales' <?= 'active_tab' == $active38 ? 'selected' : '' ?>>Sales by Customer Summary</option><?php
                    }
					if (strpos($value_config, ',Sales History by Customer,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_customer_history' ) === true) { ?>
						<option value='report_sales_by_customer_detail.php?type=sales' <?= 'active_tab' == $active39 ? 'selected' : '' ?>>Sales History by Customer</option><?php
					}
					if (strpos($value_config, ',Sales by Service Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_service' ) === true) { ?>
						<option value='report_sales_by_product_service_summary.php?type=sales' <?= 'active_tab' == $active40 ? 'selected' : '' ?>>Sales by Service Summary</option><?php
					}
					if (strpos($value_config, ',Sales by Service Category,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_service_category' ) === true) { ?>
						<option value='report_sales_by_product_service_category.php?type=sales' <?= 'active_tab' == $active78 ? 'selected' : '' ?>>Sales by Service Category</option><?php
					}
					if (strpos($value_config, ',Sales by Inventory Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_inventory' ) === true) { ?>
						<option value='report_sales_by_inventory_summary.php?type=sales' <?= 'active_tab' == $active41 ? 'selected' : '' ?>>Sales by Inventory Summary</option><?php
					}
					if (strpos($value_config, ',Sales Summary by Injury Type,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_injury' ) === true) { ?>
						<option value='report_daily_sales_summary.php?type=sales' <?= 'active_tab' == $active42 ? 'selected' : '' ?>>Sales Summary by Injury Type</option><?php
					}
					if (strpos($value_config, ',Inventory Analysis,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'inventory_analysis' ) === true) { ?>
						<option value='report_general_inventory.php?type=sales' <?= 'active_tab' == $active43 ? 'selected' : '' ?>>Inventory Analysis</option><?php
					}
					if (strpos($value_config, ',Unassigned/Error Invoices,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'error_invoice' ) === true) { ?>
						<option value='report_unassigned_invoices.php?type=sales' <?= 'active_tab' == $active44 ? 'selected' : '' ?>>Unassigned/Error Invoices</option><?php
					}
					if (strpos($value_config, ',Staff Revenue Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_revenue' ) === true) { ?>
						<option value='report_revenue.php?type=sales' <?= 'active_tab' == $active45 ? 'selected' : '' ?>>Staff Revenue Report</option><?php
					}
					if (strpos($value_config, ',Expense Summary Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'expense_report' ) === true) { ?>
						<option value='report_expenses.php?type=sales' <?= 'active_tab' == $active46 ? 'selected' : '' ?>>Expense Summary Report</option><?php
					}
					if (strpos($value_config, ',Phone Communication,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'project' ) === true) { ?>
						<option value='report_phone_communication.php?type=sales' <?= 'active_tab' == $active76 ? 'selected' : '' ?>>Phone Communication</option><?php
					}
					if (strpos($value_config, ',Sales by Inventory/Service Detail,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_inv_service_detail' ) === true) { ?>
						<option value='report_sales_by_product_service_detail.php?type=sales' <?= 'active_tab' == $active47 ? 'selected' : '' ?>>Sales by Inventory/Service Detail</option><?php
					}
					if (strpos($value_config, ',Payment Method List,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'pay_methods' ) === true) { ?>
						<option value='report_payment_method_list.php?type=sales' <?= 'active_tab' == $active49 ? 'selected' : '' ?>>Payment Method List</option><?php
					}
					if (strpos($value_config, ',Patient History,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'patient_history' ) === true) { ?>
						<option value='report_patient_appoint_history.php?type=sales' <?= 'active_tab' == $active50 ? 'selected' : '' ?>>Customer History</option><?php
					}
					if (strpos($value_config, ',Receipts Summary Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_receipts' ) === true) { ?>
						<option value='report_receipt_summary.php?type=sales' <?= 'active_tab' == $active51 ? 'selected' : '' ?>>Receipts Summary Report</option><?php
					}
					if (strpos($value_config, ',Gross Revenue by Staff,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_gross_revenue' ) === true) { ?>
						<option value='report_gross_revenue_by_staff.php?type=sales' <?= 'active_tab' == $active53 ? 'selected' : '' ?>>Gross Revenue by Staff</option><?php
					}
					if (strpos($value_config, ',Patient Invoices,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'patient_invoice' ) === true) { ?>
						<option value='report_patient_unpaid_invoices.php?type=sales' <?= 'active_tab' == $active54 ? 'selected' : '' ?>>Customer Invoices</option><?php
					}
					if (strpos($value_config, ',POS Sales Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_summary' ) === true) { ?>
						<option value='report_pos_daily_sales_summary.php?type=sales' <?= 'active_tab' == $active55 ? 'selected' : '' ?>>POS Sales Summary</option><?php
					}
					if (strpos($value_config, ',POS Advanced Sales Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_summary_advanced' ) === true) { ?>
						<option value='report_pos_advanced_daily_sales_summary.php?type=sales' <?= 'active_tab' == $active55b ? 'selected' : '' ?>>POS Advanced Sales Summary</option><?php
					}
					if (strpos($value_config, ',Profit-Loss,') !== false && $sea_kristi !== TRUE && check_subtab_persmission( $dbc, 'report', ROLE, 'profit_loss' ) === true) { ?>
						<option value='report_profit_loss.php?type=sales' <?= 'active_tab' == $active56 ? 'selected' : '' ?>>Profit-Loss</option><?php
					}
					if (strpos($value_config, ',Profit-Loss POS Advanced,') !== false && $sea_kristi !== TRUE && check_subtab_persmission( $dbc, 'report', ROLE, 'profit_loss_pos_advanced' ) === true) { ?>
						<option value='report_profit_loss_pos_advanced.php?type=sales' <?= 'active_tab' == $active56a ? 'selected' : '' ?>>Profit-Loss (POS Advanced)</option><?php
					}
					if (strpos($value_config, ',Transaction List by Customer,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'transaction_list' ) === true) { ?>
						<option value='report_transaction_list_by_customer.php?type=sales' <?= 'active_tab' == $active57 ? 'selected' : '' ?>>Transaction List by Customer</option><?php
					}
					if (strpos($value_config, ',Unbilled Invoices,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'unbilled_invoices' ) === true) { ?>
						<option value='report_unbilled_charges.php?type=sales' <?= 'active_tab' == $active58 ? 'selected' : '' ?>>Unbilled Invoices</option><?php
					}
					if (strpos($value_config, ',Deposit Detail,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'deposit_detail' ) === true) { ?>
						<option value='report_deposit_detail.php?type=sales' <?= 'active_tab' == $active59 ? 'selected' : '' ?>>Deposit Detail</option><?php
					}
					if (strpos($value_config, ',Sales Estimates,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'sales_estimates' ) === true) { ?>
						<option value='report_sales_estimates.php?type=sales' <?= 'active_tab' == $active94 ? 'selected' : '' ?>>Sales Estimates</option><?php
					}
				}
				// Accounts Receivables
				else if($_GET['type'] == 'ar') {
					if (strpos($value_config, ',A/R Aging Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ar_aging' ) === true) { ?>
						<option value='report_ar_aging_summary.php?type=ar' <?= 'active_tab' == $active60 ? 'selected' : '' ?>>A/R Aging Summary</option><?php
					}
					if (strpos($value_config, ',Patient Aging Receivable Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ar_patient_aging' ) === true) { ?>
						<option value='report_receivables_patient_summary.php?type=ar' <?= 'active_tab' == $active61 ? 'selected' : '' ?>>Customer Aging Receivable Summary</option><?php
					}
					if (strpos($value_config, ',Insurer Aging Receivable Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ar_insurer_aging' ) === true) { ?>
						<option value='report_receivables_summary.php?type=ar' <?= 'active_tab' == $active62 ? 'selected' : '' ?>>Insurer Aging Receivable Summary</option><?php
					}
					if (strpos($value_config, ',By Invoice#,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ar_invoice' ) === true) { ?>
						<option value='report_receivables.php?type=ar' <?= 'active_tab' == $active63 ? 'selected' : '' ?>>By Invoice#</option><?php
					}
					if (strpos($value_config, ',Customer Balance Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ar_customer_balance' ) === true) { ?>
						<option value='report_account_receivable.php?type=ar' <?= 'active_tab' == $active64 ? 'selected' : '' ?>>Customer Balance Summary</option><?php
					}
					if (strpos($value_config, ',Customer Balance by Invoice,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ar_customer_invoice' ) === true) { ?>
						<option value='report_customer_balance_detail.php?type=ar' <?= 'active_tab' == $active65 ? 'selected' : '' ?>>Customer Balance by Invoice</option><?php
					}
					if (strpos($value_config, ',Collections Report by Customer,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ar_customer_collections' ) === true) { ?>
						<option value='report_collections_report.php?type=ar' <?= 'active_tab' == $active66 ? 'selected' : '' ?>>Collections Report by Customer</option><?php
					}
					if (strpos($value_config, ',Invoice List,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'invoice_list' ) === true) { ?>
						<option value='report_invoice_list.php?type=ar' <?= 'active_tab' == $active67 ? 'selected' : '' ?>>Invoice List</option><?php
					}
					if (strpos($value_config, ',POS Receivables,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'receivables' ) === true) { ?>
						<option value='report_pos_receivables.php?type=ar' <?= 'active_tab' == $active68 ? 'selected' : '' ?>>POS Receivables</option><?php
					}
					if (strpos($value_config, ',UI Invoice Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'ar_ui_invoice' ) === true) { ?>
						<option value='ui_invoice_reports.php?type=ar' <?= 'active_tab' == $active71 ? 'selected' : '' ?>>UI Invoice Report</option><?php
					}
				}
				// Profit & Loss
				else if ( $_GET['type']=='pnl' ) {
					if ( strpos($value_config, ',Revenue Receivables,') !== false && check_subtab_persmission($dbc, 'report', ROLE, 'revenue_receivables') === true ) { ?>
						<option value="report_pnl_revenue_receivables.php?type=pnl" <?= 'active_tab' == $active80 ? 'selected' : '' ?>>Revenue &amp; Receivables</option><?php
					}
					if ( strpos($value_config, ',Staff Compensation,') !== false && check_subtab_persmission($dbc, 'report', ROLE, 'staff_compensation') === true ) { ?>
						<option value="report_pnl_staff_compensation.php?type=pnl" <?= 'active_tab' == $active81 ? 'selected' : '' ?>>Staff Compensation</option><?php
					}
					if ( strpos($value_config, ',Expenses,') !== false && check_subtab_persmission($dbc, 'report', ROLE, 'expenses') === true ) { ?>
						<option value="report_pnl_expenses.php?type=pnl" <?= 'active_tab' == $active82 ? 'selected' : '' ?>>Expenses</option><?php
					}
					if ( strpos($value_config, ',Costs,') !== false && check_subtab_persmission($dbc, 'report', ROLE, 'costs') === true ) { ?>
						<option value="report_pnl_costs.php?type=pnl" <?= 'active_tab' == $active83 ? 'selected' : '' ?>>Costs</option><?php
					}
					if ( strpos($value_config, ',Summary,') !== false && check_subtab_persmission($dbc, 'report', ROLE, 'summary') === true ) { ?>
						<option value="report_pnl_summary.php?type=pnl" <?= 'active_tab' == $active84 ? 'selected' : '' ?>>Summary</option><?php
					}
				}
				// Marketing
				else if($_GET['type'] == 'marketing') {
					if (strpos($value_config, ',Customer Contact List,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'customer_list' ) === true) { ?>
						<option value='report_customer_contact_list.php?type=marketing' <?= 'active_tab' == $active48 ? 'selected' : '' ?>>Customer Contact List</option><?php
					}
					if (strpos($value_config, ',Customer Stats,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'customer_stats' ) === true) { ?>
						<option value='report_customer_stats.php?type=marketing' <?= 'active_tab' == $active69 ? 'selected' : '' ?>>Customer Stats</option><?php
					}
					if (strpos($value_config, ',Demographics,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'demographs' ) === true) { ?>
						<option value='report_demographics.php?type=marketing' <?= 'active_tab' == $active70 ? 'selected' : '' ?>>Demographics</option><?php
					}
					if (strpos($value_config, ',CRM Recommendations - By Date,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'crm_recommend_date' ) === true) { ?>
						<option value='report_crm_recommend_date.php?type=marketing' <?= 'active_tab' == $active72 ? 'selected' : '' ?>>CRM Recommendations - By Date</option><?php
					}
					if (strpos($value_config, ',CRM Recommendations - By Customer,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'crm_recommend_customer' ) === true) { ?>
						<option value='report_crm_recommend_customer.php?type=marketing' <?= 'active_tab' == $active73 ? 'selected' : '' ?>>CRM Recommendations - By Customer</option><?php
					}
					if (strpos($value_config, ',POS Coupons,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'pos_coupons' ) === true) { ?>
						<option value='report_pos_coupons.php?type=marketing' <?= 'active_tab' == $active6 ? 'selected' : '' ?>>POS Coupons</option><?php
					}
					if (strpos($value_config, ',Postal Code,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'postal_code' ) === true) { ?>
						<option value='report_postalcode.php?type=marketing' <?= 'active_tab' == $active14 ? 'selected' : '' ?>>Postal Code</option><?php
					}
					if (strpos($value_config, ',Referral,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'referral' ) === true) { ?>
						<option value='report_referral.php?type=marketing' <?= 'active_tab' == $active52 ? 'selected' : '' ?>>Referrals</option><?php
					}
					if (strpos($value_config, ',Web Referrals Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'web_referrals' ) === true) { ?>
						<option value='report_web_referral.php?type=marketing' <?= 'active_tab' == $active24 ? 'selected' : '' ?>>Web Referrals</option><?php
					}
					if (strpos($value_config, ',Pro Bono Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'pro_bono' ) === true) { ?>
						<option value='report_marketing_pro_bono.php?type=marketing' <?= 'active_tab' == $active74 ? 'selected' : '' ?>>Pro-Bono</option><?php
					}
					if (strpos($value_config, ',Net Promoter Score,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'net_promoter_score' ) === true) { ?>
						<option value='report_marketing_net_promoter_score.php?type=marketing' <?= 'active_tab' == $active77 ? 'selected' : '' ?>>Net Promoter Score</option><?php
					}

					if (strpos($value_config, ',Contact Report by Status,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'contact_report_by_status' ) === true) { ?>
						<option value='report_contact_report_by_status.php?type=marketing' <?= 'active_tab' == $active101 ? 'selected' : '' ?>>Contact Report by Status</option><?php
					}

					if (strpos($value_config, ',Contact Postal Code,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'contact_postal_code' ) === true) { ?>
						<option value='report_marketing_contact_pc.php?type=marketing&subtype=Staff' <?= 'active_tab' == $active91 ? 'selected' : '' ?>>Contact Postal Code</option><?php
					}
					if (strpos($value_config, ',Site Visitors,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'site_visitors' ) === true) { ?>
						<option value='report_marketing_site_visitors.php?type=marketing' <?= 'active_tab' == $active96 ? 'selected' : '' ?>>Website Visitors</option><?php
					}
					if (strpos($value_config, ',Cart Abandonment,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'cart_abandonment' ) === true) { ?>
						<option value='report_marketing_cart_abandonment.php?type=marketing' <?= 'active_tab' == $active97 ? 'selected' : '' ?>>Cart Abandonment</option><?php
					}
				}
				// Compensation
				else if($_GET['type'] == 'compensation') {
					if (strpos($value_config, ',Adjustment Compensation,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'compensation_adjust' ) === true) { ?>
						<option value='report_compensation_adjustments.php?type=compensation' <?= 'active_tab' == $active34b ? 'selected' : '' ?>>Adjustment Compensation</option><?php
					}
					if (strpos($value_config, ',Hourly Compensation,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'compensation_hourly' ) === true) { ?>
						<option value='report_hourly_compensation.php?type=compensation' <?= 'active_tab' == $active35 ? 'selected' : '' ?>>Hourly Compensation</option><?php
					}
					if (strpos($value_config, ',Therapist Compensation,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'compensation_pt' ) === true) { ?>
						<option value='report_compensation.php?type=compensation' <?= 'active_tab' == $active34a ? 'selected' : '' ?>>Therapist Compensation</option><?php
					}
					if (strpos($value_config, ',Statutory Holiday Pay Breakdown,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'compensation_statutory_breakdown' ) === true) { ?>
						<option value='report_stat_holiday_pay.php?type=compensation' <?= 'active_tab' == $active85 ? 'selected' : '' ?>>Statutory Holiday Pay Breakdown</option><?php
					}
					if (strpos($value_config, ',Timesheet Payroll,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'timesheet_payroll' ) === true) { ?>
						<option value='report_compensation_timesheet_payroll.php?type=compensation' <?= 'active_tab' == $active100 ? 'selected' : '' ?>>Time Sheet Payroll</option><?php
					}
				}
                // Customer
                else if($_GET['type'] == 'customer') {
                    if (strpos($value_config, ',Customer Sales by Customer Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'customer_sales_customer' ) === true) { ?>
                        <option value='report_sales_by_customer_summary.php?type=customer' <?= 'active_tab' == $active38 ? 'selected' : '' ?>>Sales by Customer Summary</option><?php
                    }
                    if (strpos($value_config, ',Customer Sales History by Customer,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'customer_sales_customer_history' ) === true) { ?>
                        <option value='report_sales_by_customer_detail.php?type=customer' <?= 'active_tab' == $active39 ? 'selected' : '' ?>>Sales History by Customer</option><?php
                    }
                    if (strpos($value_config, ',Customer Patient Invoices,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'customer_patient_invoice' ) === true) { ?>
                        <option value='report_patient_unpaid_invoices.php?type=customer' <?= 'active_tab' == $active54 ? 'selected' : '' ?>>Customer Invoices</option><?php
                    }
                    if (strpos($value_config, ',Customer Transaction List by Customer,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'customer_transaction_list' ) === true) { ?>
                        <option value='report_transaction_list_by_customer.php?type=customer' <?= 'active_tab' == $active57 ? 'selected' : '' ?>>Transaction List by Customer</option><?php
                    }
                    if (strpos($value_config, ',Customer Patient History,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'customer_patient_history' ) === true) { ?>
                        <option value='report_patient_appoint_history.php?type=customer' <?= 'active_tab' == $active50 ? 'selected' : '' ?>>Customer History</option><?php
                    }
                    if (strpos($value_config, ',Customer Customer Balance Summary,') !== false && check_subtab_persmission( $dbc, 'report', Role, 'customer_ar_customer_balance' ) === true) { ?>
                        <option value='report_account_receivable.php?type=customer' <?= 'active_tab' == $active64 ? 'selected' : '' ?>>Customer Balance Summary</option><?php
                    }
                    if (strpos($value_config, ',Customer Customer Balance by Invoice,') !== false && check_subtab_persmission( $dbc, 'report', Role, 'customer_ar_customer_invoice' ) === true) { ?>
                        <option value='report_customer_balance_detail.php?type=customer' <?= 'active_tab' == $active65 ? 'selected' : '' ?>>Customer Balance by Invoice</option><?php
                    }
                    if (strpos($value_config, ',Customer Collections Report by Customer,') !== false && check_subtab_persmission( $dbc, 'report', Role, 'customer_ar_customer_collections' ) === true) { ?>
                        <option value='report_collections_report.php?type=customer' <?= 'active_tab' == $active66 ? 'selected' : '' ?>>Collections Report by Customer</option><?php
                    }
                    if (strpos($value_config, ',Customer Patient Aging Receivable Summary,') !== false && check_subtab_persmission( $dbc, 'report', Role, 'customer_ar_patient_aging' ) === true) { ?>
                        <option value='report_receivables_patient_summary.php?type=customer' <?= 'active_tab' == $active61 ? 'selected' : '' ?>>Customer Aging Receivable Summary</option><?php
                    }
                    if (strpos($value_config, ',Customer Customer Contact List,') !== false && check_subtab_persmission( $dbc, 'report', Role, 'customer_customer_list' ) === true) { ?>
                        <option value='report_customer_contact_list.php?type=customer' <?= 'active_tab' == $active48 ? 'selected' : '' ?>>Customer Contact List</option><?php
                    }
                    if (strpos($value_config, ',Customer Customer Stats,') !== false && check_subtab_persmission( $dbc, 'report', Role, 'customer_customer_stats' ) === true) { ?>
                        <option value='report_customer_stats.php?type=customer' <?= 'active_tab' == $active69 ? 'selected' : '' ?>>Customer Stats</option><?php
                    }
                    if (strpos($value_config, ',Customer CRM Recommendations - By Customer,') !== false && check_subtab_persmission( $dbc, 'report', Role, 'customer_crm_recommend_customer' ) === true) { ?>
                        <option value='report_crm_recommend_customer.php?type=customer' <?= 'active_tab' == $active73 ? 'selected' : '' ?>>CRM Recommendations - By Customer</option><?php
                    }
                    if (strpos($value_config, ',Customer Contact Postal Code,') !== false && check_subtab_persmission( $dbc, 'report', Role, 'customer_contact_postal_code' ) === true) { ?>
                        <option value='report_marketing_contact_pc.php?type=customer&subtype=Staff' <?= 'active_tab' == $active91 ? 'selected' : '' ?>>Contact Postal Code</option><?php
                    }
                    if (strpos($value_config, ',Customer Service Rates,') !== false && check_subtab_persmission( $dbc, 'report', Role, 'customer_service_rates' ) === true) { ?>
                        <option value='report_contact_service_rates.php?type=customer' <?= $file_name == 'report_contact_service_rates.php' ? 'selected' : '' ?>>Service Rates</option><?php
                    }
                }
                // Staff
                else if($_GET['type'] == 'staff') {
                    if (strpos($value_config, ',Staff Staff Tickets,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_staff_tickets' ) === true) { ?>
                        <option value='reports_staff_tickets.php?type=staff' <?= 'active_tab' == $active9 ? 'selected' : '' ?>>Staff <?= TICKET_TILE ?></option><?php
                    }
                    if (strpos($value_config, ',Staff Scrum Staff Productivity Summary,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_scrum_staff_productivity_summary' ) === true) { ?>
                        <option value='reports_scrum_staff_productivity_summary.php?type=staff' <?= 'active_tab' == $active19 ? 'selected' : '' ?>>Scrum Staff Productivity Summary</option><?php
                    }
                    if (strpos($value_config, ',Staff Daysheet,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_pt_daysheet' ) === true) { ?>
                        <option value='report_daysheet.php?type=staff' <?= 'active_tab' == $active1 ? 'selected' : '' ?>>Therapist Day Sheet</option><?php
                    }
                    if (strpos($value_config, ',Staff Therapist Stats,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_pt_stats' ) === true) { ?>
                        <option value='report_stat.php?type=staff' <?= 'active_tab' == $active2 ? 'selected' : '' ?>>Therapist Stats</option><?php
                    }
                    if (strpos($value_config, ',Staff Day Sheet Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_day_sheet_report' ) === true) { ?>
                        <option value='reports_daysheet_reports.php?type=staff' <?= 'active_tab' == $active10 ? 'selected' : '' ?>>Day Sheet Report</option><?php
                    }
                    if (strpos($value_config, ',Staff Staff Revenue Report,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_staff_revenue' ) === true) { ?>
                        <option value='report_revenue.php?type=staff' <?= 'active_tab' == $active45 ? 'selected' : '' ?>>Staff Revenue Report</option><?php
                    }
                    if (strpos($value_config, ',Staff Gross Revenue by Staff,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_staff_gross_revenue' ) === true) { ?>
                        <option value='report_gross_revenue_by_staff.php?type=staff' <?= 'active_tab' == $active53 ? 'selected' : '' ?>>Gross Revenue by Staff</option><?php
                    }
                    if (strpos($value_config, ',Staff Validation by Therapist,') !== false && check_subtab_persmission( $dbc, 'report', ROLE, 'staff_pt_validation' ) === true) { ?>
                        <option value='report_daily_validation.php?type=staff' <?= 'active_tab' == $active31 ? 'selected' : '' ?>>Validation by Therapist</option><?php
                    }
                    if ( strpos($value_config, ',Staff Staff Compensation,') !== false && check_subtab_persmission($dbc, 'report', ROLE, 'staff_staff_compensation') === true ) { ?>
                        <option value="report_pnl_staff_compensation.php?type=staff" <?= 'active_tab' == $active81 ? 'selected' : '' ?>>Staff Compensation</option><?php
                    }
                }
                else { ?>
					<option selected value="report_tiles.php">Please Select a Tab to view the Reports</option><?php
				} ?>
			</select>
		</div>
	</div>
	<div class="clearfix"></div>
    <div class="tab-container1 mobile-100-container live-search-list-report">

</div>
<?php }
