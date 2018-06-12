<?php
error_reporting(0);
$charts_time_format = get_config($dbc, 'charts_time_format');

global $config;

global $charts_tile_charts;
$charts_tile_charts = ','.get_config($dbc, 'charts_tile_charts').',';
$custom_monthly_charts = explode(',', get_config($dbc, 'custom_monthly_charts'));
$active_monthly_chart = $_GET['type'];

global $charts_time_format;
$charts_time_format = get_config($dbc, 'charts_time_format');


$config['tile_name'] = 'Medical Charts';

function config_visible_function_custom($dbc)
{
	//return (config_visible_function($dbc, 'charts') == 1);
	return true;
}

function vuaed_visible_function_custom($dbc)
{
	//return (vuaed_visible_function($dbc, 'charts') == 1);
	return true;
}

$config['tabs'] = array (
    'Bowel Movement' => 'bowel_movement',
    'Seizure Record' => 'seizure_record',
    'Blood Glucose' => 'blood_glucose',
    'Daily Water Temp (Client)' => 'daily_water_temp',
    'Daily Water Temp (Program)' => 'daily_water_temp_bus',
    'Daily Fridge Temp' => 'daily_fridge_temp',
    'Daily Freezer Temp' => 'daily_freezer_temp',
    'Daily Dishwasher Temp' => 'daily_dishwasher_temp'
);

/* Bowel Movement */
$config['settings']['Choose Fields for Bowel Movement']['config_field'] = 'bowel_movement';
$config['settings']['Choose Fields for Bowel Movement']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('BM', 'dropdown', 'bm'),
			array('Size', 'dropdown', 'size'),
			array('Form', 'dropdown', 'form'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

$config['settings']['Choose Fields for Bowel Movement Dashboard']['config_field'] = 'bowel_movement_dashboard';
$config['settings']['Choose Fields for Bowel Movement Dashboard']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'text', 'date'),
			array('Time', 'text', 'time'),
			array('BM', 'dropdown', 'bm'),
			array('Size', 'dropdown', 'size'),
			array('Form', 'dropdown', 'form'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		),
);

/* Seizure Record */
$config['settings']['Choose Fields for Seizure Record']['config_field'] = 'seizure_record';
$config['settings']['Choose Fields for Seizure Record']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'date', 'date'),
			array('Start Time', 'text', 'start_time'),
			array('End Time', 'text', 'end_time'),
			array('Type of Seizure', 'dropdown', 'form'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

$config['settings']['Choose Fields for Seizure Record Dashboard']['config_field'] = 'seizure_record_dashboard';
$config['settings']['Choose Fields for Seizure Record Dashboard']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'date', 'date'),
			array('Start Time', 'text', 'start_time'),
			array('End Time', 'text', 'end_time'),
			array('Type of Seizure', 'dropdown', 'form'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

/* Daily Water Temp (Client) */
$config['settings']['Choose Fields for Daily Water Temp (Client)']['config_field'] = 'daily_water_temp';
$config['settings']['Choose Fields for Daily Water Temp (Client)']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Water Temp', 'text', 'water_temp'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

$config['settings']['Choose Fields for Daily Water Temp (Client) Dashboard']['config_field'] = 'daily_water_temp_dashboard';
$config['settings']['Choose Fields for Daily Water Temp (Client) Dashboard']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Water Temp', 'text', 'water_temp'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

/* Blood Glucose */
$config['settings']['Choose Fields for Blood Glucose']['config_field'] = 'blood_glucose';
$config['settings']['Choose Fields for Blood Glucose']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('BG', 'text', 'bg'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

$config['settings']['Choose Fields for Blood Glucose Dashboard']['config_field'] = 'blood_glucose_dashboard';
$config['settings']['Choose Fields for Blood Glucose Dashboard']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('BG', 'text', 'bg'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

/* Daily Water Temp (Program) */
$config['settings']['Choose Fields for Daily Water Temp (Business)']['config_field'] = 'daily_water_temp_bus';
$config['settings']['Choose Fields for Daily Water Temp (Business)']['data'] = array(
	'General' => array(
			array('Program', 'dropdown', 'business'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Location', 'dropdown', 'location'),
			array('Water Temp', 'text', 'water_temp'),
			array('Note', 'textarea', 'note'),
			array('A/I Done', 'checkbox', 'ai_done'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

$config['settings']['Choose Fields for Daily Water Temp (Business) Dashboard']['config_field'] = 'daily_water_temp_bus_dashboard';
$config['settings']['Choose Fields for Daily Water Temp (Business) Dashboard']['data'] = array(
	'General' => array(
			array('Program', 'dropdown', 'business'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Location', 'dropdown', 'location'),
			array('Water Temp', 'text', 'water_temp'),
			array('Note', 'textarea', 'note'),
			array('A/I Done', 'checkbox', 'ai_done'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

/* Daily Fridge Temp */
$config['settings']['Choose Fields for Daily Fridge Temp']['config_field'] = 'daily_fridge_temp';
$config['settings']['Choose Fields for Daily Fridge Temp']['data'] = array(
	'General' => array(
			array('Program', 'dropdown', 'business'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Fridge', 'dropdown', 'fridge'),
			array('Temperature', 'text', 'temp'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

$config['settings']['Choose Fields for Daily Fridge Temp Dashboard']['config_field'] = 'daily_fridge_temp_dashboard';
$config['settings']['Choose Fields for Daily Fridge Temp Dashboard']['data'] = array(
	'General' => array(
			array('Program', 'dropdown', 'business'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Fridge', 'dropdown', 'fridge'),
			array('Temperature', 'text', 'temp'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

/* Daily Freezer Temp */
$config['settings']['Choose Fields for Daily Freezer Temp']['config_field'] = 'daily_freezer_temp';
$config['settings']['Choose Fields for Daily Freezer Temp']['data'] = array(
	'General' => array(
			array('Program', 'dropdown', 'business'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Freezer', 'dropdown', 'freezer'),
			array('Temperature', 'text', 'temp'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

$config['settings']['Choose Fields for Daily Freezer Temp Dashboard']['config_field'] = 'daily_freezer_temp_dashboard';
$config['settings']['Choose Fields for Daily Freezer Temp Dashboard']['data'] = array(
	'General' => array(
			array('Program', 'dropdown', 'business'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Freezer', 'dropdown', 'freezer'),
			array('Temperature', 'text', 'temp'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

/* Daily Dishwasher Temp */
$config['settings']['Choose Fields for Daily Dishwasher Temp']['config_field'] = 'daily_dishwasher_temp';
$config['settings']['Choose Fields for Daily Dishwasher Temp']['data'] = array(
	'General' => array(
			array('Program', 'dropdown', 'business'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Water Temp', 'text', 'water_temp'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

$config['settings']['Choose Fields for Daily Dishwasher Temp Dashboard']['config_field'] = 'daily_dishwasher_temp_dashboard';
$config['settings']['Choose Fields for Daily Dishwasher Temp Dashboard']['data'] = array(
	'General' => array(
			array('Program', 'dropdown', 'business'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Water Temp', 'text', 'water_temp'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

function get_tabs($tab = '')
{
	global $config;
	global $charts_tile_charts;
	global $custom_monthly_charts;
	global $active_monthly_chart;

	$html = '<div class="double-gap-top hide-on-mobile">';
	foreach($config['tabs'] as $title => $url) {
		if(strpos($charts_tile_charts, $url) !== FALSE) {
			$active = '';
            if ( check_subtab_persmission($_SERVER['DBC'], 'charts', ROLE, $url) === true ) {
                if($title == $tab) {
                    $active = 'active_tab';
                }
                $html .= "<a href='".$url.".php'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active."' >".$title."</button></a>";
            } else {
                $html .= "<button class='btn mobile-block mobile-100 disabled-btn'>".$title."</button>";
            }
		}
	}
	foreach ($custom_monthly_charts as $custom_monthly_chart) {
		if(!empty($custom_monthly_chart)) {
			$active = '';
			if($active_monthly_chart == $custom_monthly_chart) {
				$active = 'active_tab';
			}
			if ( check_subtab_persmission($_SERVER['DBC'], 'charts', ROLE, 'custom_chart') === true ) {
                $html .= "<a href='custom_chart.php?type=".$custom_monthly_chart."'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active."' >".$custom_monthly_chart."</button></a>";
            } else {
                $html .= "<button type='button' class='btn mobile-block mobile-100 disabled-btn'>".$custom_monthly_chart."</button>";
            }
		}
	}
	$html .= "</div>";
	$html .= '<div class="show-on-mob">';
	$html .= '<a href="index.php" class="btn brand-btn col-xs-12 gap-top gap-bottom">Back to Menu</a>';
	$html .= '</div>';
	return $html;
}

function get_all_inputs($data) {
	global $config;
	$fields = array();

	if(isset($data)) {
		foreach($data as $tabs) {
			foreach($tabs as $field) {
				$fields[] = $field[2];

			}
		}
	}
	return $fields;
}

function get_post_inputs($data) {
	global $config;
	$fields = array();
	foreach($data as $tabs) {
		foreach($tabs as $field) {
			if($field[1] == 'upload') {
				$fields[$field[2]] = $_FILES[$field[2]]["name"];
				if($fields[$field[2]] == '') {
					if(isset($_POST[$field[2].'_hidden'])) {
						$fields[$field[2]] = $_POST[$field[2].'_hidden'];
					}
				}
			} else {
				$fields[$field[2]] = filter_var(htmlentities($_POST[$field[2]], FILTER_SANITIZE_STRING));
			}
		}
	}
	return $fields;
}

function get_post_uploads($data) {
	global $config;
	$fields = array();

	foreach($data as $tabs) {
		foreach($tabs as $field) {
			if($field[1] == 'upload') {
				$fields[$field[2]] = $_FILES[$field[2]]["name"];
			}
		}
	}
	return $fields;
}

function move_files($files) {
	foreach($files as $file => $name) {
		move_uploaded_file($_FILES[$file]["tmp_name"], "download/". $_FILES[$file]["name"]);
	}
}

function prepare_insert($ins_data = array(), $table = '') {
	$columns = implode(", ",array_keys($ins_data));
	// $escaped_values = array_map('mysql_real_escape_string', array_values($ins_data));
	$values = '';
	foreach($ins_data as $tmp) {
		$values .= "'".$tmp."', ";
	}
	$values = trim($values,', ');
	$sql = "INSERT INTO `".$table."` ($columns) VALUES ($values)";
	return $sql;
}

function prepare_update($up_data = array(), $table = '', $key = '', $value = '') {
	$fields = array();
	foreach($up_data as $field => $val) {
		$fields[] = "$field = '$val'";
	}
	$sql = "UPDATE ".$table." SET " . join(', ', $fields) . " WHERE ".$key." = '".$value."'";
	return $sql;
}

function get_field($field, $value, $dbc = '')
{
	global $charts_time_format;
	$html = '';

   	if($field[2] == 'staff') {
   		return call_staff($dbc, $field[2], $value, 'staff');
   	} else if($field[2] == 'client') {
   		return call_staff($dbc, $field[2], $value, 'client');
   	} else if($field[2] == 'business') {
   		return call_staff($dbc, $field[2], $value, 'business');
   	} else if($field[1] == 'text') {
		$class = '';
		if($field[2] == 'start_time' || $field[2] == 'end_time') {
			if($charts_time_format == '24h') {
				$class = 'datetimepickerseconds-24h';
				$value = date('H:i:s', strtotime(date('Y-m-d').' '.$value));
			} else {
				$class = 'datetimepickerseconds';
				$value = date('h:i:s a', strtotime(date('Y-m-d').' '.$value));
			}
		} else if($field[2] == 'time') {
			if($charts_time_format == '24h') {
				$class = 'datetimepicker-24h';
				$value = date('H:i', strtotime(date('Y-m-d').' '.$value));
			} else {
				$class = 'datetimepicker';
				$value = date('h:i a', strtotime(date('Y-m-d').' '.$value));
			}
		}
		$html .= '<div class="form-group">
                    <label for="'.$field[2].'" class="col-sm-4 control-label">'.$field[0].': </label>
                    <div class="col-sm-8">
                      <input name="'.$field[2].'" id="'.$field[2].'" class="form-control '.$class.'" value="'.$value.'">
                    </div>
                  </div>';
   	} else if($field[2] == 'size') {

		if($value == 'Large') {
			$options = '<option value=""></option><option value="Large" selected>Large</option><option value="Medium">Medium</option><option value="Small">Small</option>';
		} elseif($value == 'Medium') {
			$options = '<option value=""></option><option value="Large" selected>Large</option><option value="Medium" selected>Medium</option><option value="Small">Small</option>';
		} elseif($value == 'Small') {
			$options = '<option value=""></option><option value="Large" selected>Large</option><option value="Medium">Medium</option><option value="Small" selected>Small</option>';
		} else {
			$options = '<option value=""></option><option value="Large">Large</option><option value="Medium">Medium</option><option value="Small">Small</option>';
		}

		$html .= '<div class="form-group">
            <label for="size" class="col-sm-4 control-label">Size:</label>
            <div class="col-sm-8">
              <select id="size" name="size" class="chosen-select-deselect form-control" width="380">
                '.$options.'
              </select>
            </div>
          </div>';
    } else if($field[2] == 'form' && $field[0] == 'Form') {

		if($value == 'Formed') {
			$options = '<option value=""></option><option value="Formed" selected>Formed</option><option value="Soft">Soft</option><option value="Loose">Loose</option><option value="Diarrhea">Diarrhea</option>';
		} elseif($value == 'Soft') {
			$options = '<option value=""></option><option value="Formed">Formed</option><option value="Soft" selected>Soft</option><option value="Loose">Loose</option><option value="Diarrhea">Diarrhea</option>';
		} elseif($value == 'Loose') {
			$options = '<option value=""></option><option value="Formed">Formed</option><option value="Soft">Soft</option><option value="Loose" selected>Loose</option><option value="Diarrhea">Diarrhea</option>';
		} elseif($value == 'Diarrhea') {
			$options = '<option value=""></option><option value="Formed">Formed</option><option value="Soft">Soft</option><option value="Loose">Loose</option><option value="Diarrhea" selected>Diarrhea</option>';
		} else {
			$options = '<option value=""></option><option value="Formed">Formed</option><option value="Soft">Soft</option><option value="Loose">Loose</option><option value="Diarrhea">Diarrhea</option>';
		}

		$html .= '<div class="form-group">
            <label for="form" class="col-sm-4 control-label">Form:</label>
            <div class="col-sm-8">
              <select id="form" name="form" class="chosen-select-deselect form-control" width="380">
                '.$options.'
              </select>
            </div>
          </div>';
    } else if($field[2] == 'form' && $field[0] == 'Type of Seizure') {

		$options = "<option></option>";
		$options .= "<option value='Tonic Clonic' ".($value == 'Tonic Clonic' ? 'selected' : '').">Tonic Clonic</option>";
		$options .= "<option value='Absence' ".($value == 'Absence' ? 'selected' : '').">Absence</option>";
		$options .= "<option value='Simple Partial' ".($value == 'Simple Partial' ? 'selected' : '').">Simple Partial</option>";
		$options .= "<option value='Complex Partial' ".($value == 'Complex Partial' ? 'selected' : '').">Complex Partial</option>";

		$html .= '<div class="form-group">
            <label for="form" class="col-sm-4 control-label">Seizure Type:</label>
            <div class="col-sm-8">
              <select id="form" name="form" class="chosen-select-deselect form-control" width="380">
                '.$options.'
              </select>
            </div>
          </div>';
    } else if($field[1] == 'date') {
		$html .= '<div class="form-group">
                    <label for="'.$field[2].'" class="col-sm-4 control-label">'.$field[0].': </label>
                    <div class="col-sm-8">
                      <input class="datepicker form-control" name="'.$field[2].'" id="'.$field[2].'" value="'.$value.'">
                    </div>
                  </div>';
    } else if($field[2] == 'bm') {

		if($value == 'Unsupported') {
			$options = '<option value=""></option><option value="Unsupported" selected>Unsupported</option><option value="Supported">Supported</option><option value="PRN">PRN</option>';
		} elseif($value == 'Supported') {
			$options = '<option value=""></option><option value="Unsupported" selected>Unsupported</option><option value="Supported" selected>Supported</option><option value="PRN">PRN</option>';
		} elseif($value == 'PRN') {
			$options = '<option value=""></option><option value="Unsupported" selected>Unsupported</option><option value="Supported">Supported</option><option value="PRN" selected>PRN</option>';
		} else {
			$options = '<option value=""></option><option value="Unsupported">Unsupported</option><option value="Supported">Supported</option><option value="PRN">PRN</option>';
		}

		$html .= '<div class="form-group">
            <label for="bm" class="col-sm-4 control-label">BM:</label>
            <div class="col-sm-8">
              <select id="bm" name="bm" class="chosen-select-deselect form-control" width="380">
                '.$options.'
              </select>
            </div>
          </div>';
    } else if($field[1] == 'textarea') {
		$html .= '<div class="form-group">
                    <label for="'.$field[2].'" class="col-sm-4 control-label">'.$field[0].': </label>
                    <div class="col-sm-8">
                      <textarea name="'.$field[2].'" rows="5" cols="50" class="form-control">'.$value.'</textarea>
                    </div>
                  </div>';
    } else if($field[2] == 'location') {
    	$daily_water_temp_bus_locations = get_config($dbc, 'daily_water_temp_bus_locations');
	    if(empty($daily_water_temp_bus_locations)) {
	        $daily_water_temp_bus_locations = 'Kitchen Double Sink,Kitchen Hand Wash Sink,North Bathroom Sink,North Showerhead,South Bathroom Sink,South Showerhead';
	    }
    	$daily_water_temp_bus_locations = explode(',', $daily_water_temp_bus_locations);
    	$options = '<option></option>';
    	foreach ($daily_water_temp_bus_locations as $location) {
    		$options .= '<option value="'.$location.'" '.($value == $location ? 'selected' : '').'>'.$location.'</option>';
    	}

		$html .= '<div class="form-group">
            <label for="location" class="col-sm-4 control-label">Location:</label>
            <div class="col-sm-8">
              <select id="location" name="location" class="chosen-select-deselect form-control" width="380">
                '.$options.'
              </select>
            </div>
          </div>';
    } else if($field[2] == 'ai_done') {
    	$html .= '<div class="form-group">
    		<label class="col-sm-4 control-label">A/I Done:</label>
    		<div class="col-sm-8">
    			<label class="form-checkbox"><input type="checkbox" name="ai_done" value="Yes"'.($value == 'Yes' ? 'checked' : '').'></label>
    		</div>
    	</div>';
    } else if($field[2] == 'fridge') {
    	$daily_fridge_temp_fridges = get_config($dbc, 'daily_fridge_temp_fridges');
	    if(empty($daily_fridge_temp_fridges)) {
            $daily_fridge_temp_fridges = 'Kitchen Fridge,Storage Fridge,Client Fridge';
	    }
    	$daily_fridge_temp_fridges = explode(',', $daily_fridge_temp_fridges);
    	$options = '<option></option>';
    	foreach ($daily_fridge_temp_fridges as $fridge) {
    		$options .= '<option value="'.$fridge.'" '.($value == $fridge ? 'selected' : '').'>'.$fridge.'</option>';
    	}

		$html .= '<div class="form-group">
            <label for="fridge" class="col-sm-4 control-label">Fridge:</label>
            <div class="col-sm-8">
              <select id="fridge" name="fridge" class="chosen-select-deselect form-control" width="380">
                '.$options.'
              </select>
            </div>
          </div>';
    } else if($field[2] == 'freezer') {
		$daily_freezer_temp_freezers = get_config($dbc, 'daily_freezer_temp_freezers');
		if(empty($daily_freezer_temp_freezers)) {
		    $daily_freezer_temp_freezers = 'Kitchen Freezer,Storage Freezer,Deep Freezer';
		}
    	$daily_freezer_temp_freezers = explode(',', $daily_freezer_temp_freezers);
    	$options = '<option></option>';
    	foreach ($daily_freezer_temp_freezers as $freezer) {
    		$options .= '<option value="'.$freezer.'" '.($value == $freezer ? 'selected' : '').'>'.$freezer.'</option>';
    	}

		$html .= '<div class="form-group">
            <label for="freezer" class="col-sm-4 control-label">Fridge:</label>
            <div class="col-sm-8">
              <select id="freezer" name="freezer" class="chosen-select-deselect form-control" width="380">
                '.$options.'
              </select>
            </div>
          </div>';
	}
	return $html;
}

function call_staff($dbc, $field, $value, $type = '') {

	if($type == 'client') {
		$title = 'Client';
		$category = 'Clients';
		$category = "IN ('Clients')";
	} else if($type == 'staff') {
		$title = 'Staff';
		$category = "IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY."";
	} else if($type == 'business') {
		$title = 'Program';
		$category = 'Business';
		$category = "IN ('".BUSINESS_CAT."')";
	}

	$html = '';
	$html .= '<div class="form-group">';
	$html .= '<label for="client" class="col-sm-4 control-label">'.$title.':</label>';
	$html .= '<div class="col-sm-8">';
	$html .= '<select class="chosen-select-deselect form-control" data-placeholder="Choose a '.$title.'..." name="'.$field.'" id="'.$field.'">';
	$html .= '<option value=""></option>';

	$query = mysqli_query($dbc,"SELECT contactid, name, first_name, last_name FROM contacts WHERE category ".$category." AND `deleted` = 0 AND `status` = 1 order by name");

	while($row = mysqli_fetch_array($query)) {
		$selected = '';
		if (strpos($value, $row['contactid']) !== FALSE) {
			$selected = 'selected';
		}

        if($row['name'] != '') {
            $html .= '<option '.$selected.' value="'.$row['contactid'].'">'.decryptIt($row['name']).'</option>';
        } else {
            $html .= '<option '.$selected.' value="'.$row['contactid'].'">'.decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
        }
    }

	$html .= '</select>';
	$html .= '</div>';
	$html .= '</div>';
	return $html;
}


