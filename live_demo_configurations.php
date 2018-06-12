<?php include_once('include.php');
if(stripos(','.$role.',',',super,') === false) {
	header('location: admin_software_config.php?software_settings');
	die();
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$('.count_diffs').each(function() {
		$(this).find('tr:not(:first)').each(function() {
			if($(this).find('.different-value').length == 0) {
				$(this).remove();
			}
		});
	});
	$('.compare_div').each(function() {
		if($(this).find('tr').length <= 1) {
			$(this).closest('.compare_div').remove();
		}
	});
	if($('.compare_div:visible').length == 0) {
		$('#no_differences_found').show();
	}
});
</script>

<div id="no-more-tables">
	<div class="notice double-gap-bottom popover-examples">
	<div class="col-sm-1 notice-icon"><img src="img/info.png" class="wiggle-me" width="25px"></div>
	<div class="col-sm-16"><span class="notice-name">NOTE:</span>
		View your software's Live Configurations vs Demo Configurations. Fields/Identifiers are labelled in bold font (eg. field_config_contacts table has a combined identifier of tile_name, tab, subtab, and accordion). All differences are highlighted in yellow. Only rows with at least one difference will be displayed. If there are no differences in a table, the table will not display.</div>
		<div class="clearfix"></div>
	</div>

	<?php if(!$dbc2) {
		echo '<h3>Second database not configured. Please configure the second database before you compare configurations.</h3>';
	} else { ?>
		<div id="no_differences_found" style="display:none;">
			<h3>No differences found.</h3>
		</div>
		<div class="compare_div">
			<h3>general_configuration</h3>
			<table class="table table-bordered">
				<tr class="hidden-xs">
					<th>Field</th>
					<th><?= DATABASE_NAME ?></th>
					<th><?= DATABASE_NAME2 ?></th>
				</tr>
				<?php $field_config = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `general_configuration`"),MYSQLI_ASSOC);
				$config = [];
				foreach($field_config as $field) {
					$config[$field['name']] = $field;
				}
				$field_config2 = mysqli_fetch_all(mysqli_query($dbc2, "SELECT * FROM `general_configuration`"),MYSQLI_ASSOC);
				$config2 = [];
				foreach($field_config2 as $field) {
					$config2[$field['name']] = $field;
				}
				foreach($config as $key => $value) {
					if($config[$key]['value'] != $config2[$key]['value']) { ?>
						<tr>
							<td data-title="Field"><b><?= $key ?></b></td>
							<td class="different-value" style="word-break: break-word;" data-title="<?= DATABASE_NAME ?>"><?= $config[$key]['value']; ?></td>
							<td class="different-value" style="word-break: break-word;" data-title="<?= DATABASE_NAME2 ?>"><?= $config2[$key]['value']; ?></td>
						</tr>
					<?php }
					unset($config[$key]);
					unset($config2[$key]);
				}
				foreach($config2 as $key => $value) { ?>
					<tr>
						<td data-title="Field"><b><?= $key ?></b></td>
						<td class="different-value" style="word-break: break-word;" data-title="<?= DATABASE_NAME ?>"><?= $config[$key]['value']; ?></td>
						<td class="different-value" style="word-break: break-word;" data-title="<?= DATABASE_NAME2 ?>"><?= $config2[$key]['value']; ?></td>
					</tr>
				<?php } ?>

			</table>
			<hr>
		</div>

		<div class="compare_div">
			<h3>field_config</h3>
			<table class="table table-bordered">
				<tr class="hidden-xs">
					<th>Field</th>
					<th><?= DATABASE_NAME ?></th>
					<th><?= DATABASE_NAME2 ?></th>
				</tr>
				<?php $field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `field_config`"));
				$field_config2 = mysqli_fetch_assoc(mysqli_query($dbc2, "SELECT * FROM `field_config`"));
				foreach($field_config as $key => $value) {
					if($field_config[$key] != $field_config2[$key]) { ?>
						<tr>
							<td data-title="Field"><b><?= $key ?></b></td>
							<td class="different-value" style="word-break: break-word;" data-title="<?= DATABASE_NAME ?>"><?= $field_config[$key]; ?></td>
							<td class="different-value" style="word-break: break-word;" data-title="<?= DATABASE_NAME2 ?>"><?= $field_config2[$key]; ?></td>
						</tr>
					<?php }
				} ?>
			</table>
			<hr>
		</div>

		<?php
		$field_config_tables = mysqli_fetch_all(mysqli_query($dbc, "SHOW TABLES WHERE `Tables_in_".DATABASE_NAME."` LIKE 'field_config_%'"),MYSQLI_ASSOC);
		foreach($field_config_tables as $table) {
			$identifiers_set = false;
			$identifiers = [];
			$config_field = '';
			$table_name = 'Tables_in_'.DATABASE_NAME; ?>
			<div class="compare_div">
				<h3><?= $table[$table_name] ?></h3>
				<?php switch($table[$table_name]) {
					//CUSTOM CHARTS
					case 'field_config_custom_charts_lines':
						break;
					case 'field_config_custom_charts': ?>
						<table class="table table-bordered count_diffs">
							<tr class="hidden-xs">
								<th>Name</th>
								<th>Heading</th>
								<th>Field (<?= DATABASE_NAME ?>)</th>
								<th>Field (<?= DATABASE_NAME2 ?>)</th>
							</tr>
							<?php $field_config = mysqli_fetch_all(mysqli_query($dbc, "SELECT c.`name`, c.`heading`, cl.`field` FROM `field_config_custom_charts` c LEFT JOIN `field_config_custom_charts_lines` cl ON c.`fieldconfigid` = cl.`headingid` WHERE c.`deleted` = 0 AND cl.`deleted` = 0"),MYSQLI_ASSOC);
							$config = [];
							$config2 = [];
							foreach($field_config as $field) {
								$config[$field['name'].'*#*'.$field['heading']][] = $field['field'];
								$config2[$field['name'].'*#*'.$field['heading']][] = [];
							}
							$field_config2 = mysqli_fetch_all(mysqli_query($dbc2, "SELECT c.`name`, c.`heading`, cl.`field` FROM `field_config_custom_charts` c LEFT JOIN `field_config_custom_charts_lines` cl ON c.`fieldconfigid` = cl.`headingid` WHERE c.`deleted` = 0 AND cl.`deleted` = 0"),MYSQLI_ASSOC);
							foreach($field_config2 as $field) {
								$config2[$field['name'].'*#*'.$field['heading']][] = $field['field'];
								if(empty($config[$field['name'].'*#*'.$field['heading']])) {
									$config[$field['name'].'*#*'.$field['heading']] = [];
								}
							}

							foreach($config as $key => $value) {
								$identifiers = explode('*#*', $key);
								$config_diff = array_diff($config[$key], $config2[$key]);
								$config_diff2 = array_diff($config2[$key], $config[$key]);
								foreach($config_diff as $config_field) {
									if(!empty($config_field)) { ?>
										<tr>
											<td data-title="Name"><b><?= $identifiers[0] ?></b></td>
											<td data-title="Heading"><b><?= $identifiers[1] ?></b></td>
											<td class="different-value" style="word-break: break-word;" data-title="Field (<?= DATABASE_NAME ?>)"><?= $config_field ?></td>
											<td class="different-value" data-title="Field (<?= DATABASE_NAME2 ?>)"></td>
										</tr>
									<?php }
								}
								foreach($config_diff2 as $config_field) {
									if(!empty($config_field)) { ?>
										<tr>
											<td data-title="Name"><b><?= $identifiers[0] ?></b></td>
											<td data-title="Heading"><b><?= $identifiers[1] ?></b></td>
											<td class="different-value" style="word-break: break-word;" data-title="Field (<?= DATABASE_NAME ?>)"></td>
											<td class="different-value" style="word-break: break-word;" data-title="Field (<?= DATABASE_NAME2 ?>)"><?= $config_field ?></td>
										</tr>
									<?php }
								}
								unset($config[$key]);
								unset($config2[$key]);
							}
							foreach($config2 as $key => $value) {
								$identifiers = explode('*#*', $key);
								$config_diff = array_diff($config2[$key], $config[$key]);
								foreach($config_diff as $config_field) {
									if(!empty($config_field)) { ?>
										<tr>
											<td data-title="Name"><b><?= $identifiers[0] ?></b></td>
											<td data-title="Heading"><b><?= $identifiers[1] ?></b></td>
											<td class="different-value" style="word-break: break-word;" data-title="Field (<?= DATABASE_NAME ?>)"></td>
											<td class="different-value" data-title="Field (<?= DATABASE_NAME2 ?>)"><?= $config_field ?></td>
										</tr>
									<?php }
								}
							} ?>
						</table>
						<?php break;

					//CONFIGS WITH IDENTIFIERS AND MULTIPLE ROWS
					case 'field_config_asset':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab','accordion'];
							$config_field = 'configassetid';
						}
					case 'field_config_budget':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab'];
							$config_field = 'configid';
						}
					case 'field_config_charts_pdf_times':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['chart','label'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_client_project':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['type'];
							$config_field = 'fieldconfigprojectid';
						}
					case 'field_config_communication':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['type'];
							$config_field = 'fieldconfigtasklistid';
						}
					case 'field_config_contacts':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tile_name','tab','subtab','accordion'];
							$config_field = 'configcontactid';
						}
					case 'field_config_contacts_security':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tile_name','category','security_level'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_custom_charts_settings':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['name'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_custom_documents':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab_name'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_equipment':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab','accordion'];
							$config_field = 'configinvid';
						}
					case 'field_config_equipment_inspection':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_expense':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_field_jobs':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab'];
							$config_field = 'config_id';
						}
					case 'field_config_holidays':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['name'];
							$config_field = 'holiday_id';
						}
					case 'field_config_hr':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab','form'];
							$config_field = 'fieldconfighrid';
						}
					case 'field_config_hr_manuals':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab','category'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_incident_report':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['row_type'];
							$config_field = 'fieldconfighrid';
						}
					case 'field_config_infogathering':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['form'];
							$config_field = 'fieldconfiginfogatheringid';
						}
					case 'field_config_inventory':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab','accordion'];
							$config_field = 'configinvid';
						}
					case 'field_config_jobs':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['type'];
							$config_field = 'fieldconfigprojectid';
						}
					case 'field_config_password':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['category'];
							$config_field = 'fieldconfigpasswordid';
						}
					case 'field_config_patientform':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['form'];
							$config_field = 'fieldconfigpatientformid';
						}
					case 'field_config_project':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['type'];
							$config_field = 'fieldconfigprojectid';
						}
					case 'field_config_project_admin':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['name'];
							$config_field = 'id';
						}
					case 'field_config_project_custom_details':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['type','tab','heading'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_project_form':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['project_type','project_heading'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_project_manage':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tile','tab','accordion'];
							$config_field = 'configpmid';
						}
					case 'field_config_safety':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab','form'];
							$config_field = 'fieldconfigsafetyid';
						}
					case 'field_config_services':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['category'];
							$config_field = 'fieldconfigserviceid';
						}
					case 'field_config_so_contacts':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['sales_order_type'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_so_security':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['security_level'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_supervisor':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['position','supervisor'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_ticket_accordion_names':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['ticket_type','accordion'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_ticket_delivery_color':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['delivery'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_ticket_fields':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['ticket_type','accordion'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_ticket_headings':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['ticket_type','accordion'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_ticket_log':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['template'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_ticket_status_color':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['status'];
							$config_field = 'fieldconfigid';
						}
					case 'field_config_treatment_presets':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['form','field'];
							$config_field = 'configid';
						}
					case 'field_config_vendors':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab','subtab','accordion'];
							$config_field = 'configvendorid';
						}
					case 'field_config_vpl':
						if(!$identifiers_set) {
							$identifiers_set = true;
							$identifiers = ['tab','accordion'];
							$config_field = 'configinvid';
						}

						$columns = mysqli_fetch_all(mysqli_query($dbc, "SHOW COLUMNS from `{$table[$table_name]}` where `Field` != '$config_field' AND `Field` NOT IN ('".implode("','",$identifiers)."')"),MYSQLI_ASSOC); ?>
						<table class="table table-bordered count_diffs">
							<tr class="hidden-xs">
								<?php foreach($identifiers as $identifier) { ?>
									<th><?= $identifier ?></th>
								<?php }
								foreach($columns as $column) { ?>
									<th><?= $column['Field'].' ('.DATABASE_NAME.')' ?></th>
									<th><?= $column['Field'].' ('.DATABASE_NAME2.')' ?></th>
								<?php } ?>
							</tr>
							<?php $field_config = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `{$table[$table_name]}`"),MYSQLI_ASSOC);
							$config = [];
							foreach($field_config as $field) {
								$key = '';
								foreach($identifiers as $identifier) {
									$key .= $field[$identifier];
								}
								$config[$key] = $field;
							}
							$field_config2 = mysqli_fetch_all(mysqli_query($dbc2, "SELECT * FROM `{$table[$table_name]}`"),MYSQLI_ASSOC);
							$config2 = [];
							foreach($field_config2 as $field) {
								$key = '';
								foreach($identifiers as $identifier) {
									$key .= $field[$identifier];
								}
								$config2[$key] = $field;
							}
							
							foreach($config as $key => $value) { ?>
								<tr>
									<?php foreach($identifiers as $identifier) { ?>
										<td data-title="<?= $identifier ?>"><b><?= $value[$identifier] ?></b></td>
									<?php }
									foreach($columns as $column) {
										$diff_value = '';
										if($config[$key][$column['Field']] != $config2[$key][$column['Field']]) {
											$diff_value = 'class="different-value"';
										} ?>
										<td data-title="<?= $column['Field'] ?> (<?= DATABASE_NAME ?>)" <?= $diff_value ?>><?= $config[$key][$column['Field']] ?></td>
										<td data-title="<?= $column['Field'] ?> (<?= DATABASE_NAME2 ?>)" <?= $diff_value ?>><?= $config2[$key][$column['Field']] ?></td>
									<?php }
									unset($config[$key]);
									unset($config2[$key]); ?>
								</tr>
							<?php }
							foreach($config2 as $key => $value) { ?>
								<tr>
									<?php foreach($identifiers as $identifier) { ?>
										<td data-title="<?= $identifier ?>"><b><?= $value[$identifier] ?></b></td>
									<?php }
									foreach($columns as $column) {
										$diff_value = '';
										if($config[$key][$column['Field']] != $config2[$key][$column['Field']]) {
											$diff_value = 'class="different-value"';
										} ?>
										<td data-title="<?= $column['Field'] ?> (<?= DATABASE_NAME ?>)" <?= $diff_value ?>><?= $config[$key][$column['Field']] ?></td>
										<td data-title="<?= $column['Field'] ?> (<?= DATABASE_NAME2 ?>)" <?= $diff_value ?>><?= $config2[$key][$column['Field']] ?></td>
									<?php } ?>
								</tr>
							<?php } ?>
						</table>
						<?php break;

					//CONFIGS WITH ONE ROW
					default: ?>
						<table class="table table-bordered">
							<tr class="hidden-xs">
								<th>Field</th>
								<th><?= DATABASE_NAME ?></th>
								<th><?= DATABASE_NAME2 ?></th>
							</tr>
							<?php $field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `{$table[$table_name]}`"));
							$field_config2 = mysqli_fetch_assoc(mysqli_query($dbc2, "SELECT * FROM `{$table[$table_name]}`"));
							foreach($field_config as $key => $value) {
								if($field_config[$key] != $field_config2[$key]) { ?>
									<tr>
										<td data-title="Field"><b><?= $key ?></b></td>
										<td class="different-value" style="word-break: break-word;" data-title="<?= DATABASE_NAME ?>"><?= $field_config[$key]; ?></td>
										<td class="different-value" style="word-break: break-word;" data-title="<?= DATABASE_NAME2 ?>"><?= $field_config2[$key]; ?></td>
									</tr>
								<?php }
							} ?>
						</table>
						<?php break;

				} ?>
				<hr>
			</div>
		<?php }
	} ?>
</div>