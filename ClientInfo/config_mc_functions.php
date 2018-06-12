<?php

function get_tabs($tab = '')
{
	global $config;

	$html = '';
	foreach($config['tabs'] as $title => $url) {
		$active = '';
		if($title == $tab) {
			$active = 'active_tab';
		}
		$html .= "<a href='".$url.".php'><button type='button' class='btn brand-btn mobile-block ".$active."' >".$title."</button></a>";
	}
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
	$escaped_values = array_map('mysql_real_escape_string', array_values($ins_data));
	$values = '';
	foreach($escaped_values as $tmp) {
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
	$html = '';

   	if($field[2] == 'staff') {
   		return call_staff($dbc, $field[3].$field[2], $value, 'staff');
   	} else if($field[2] == 'client') {
   		return call_staff($dbc, $field[3].$field[2], $value, 'client', 'disabled="true"');
   	} else if($field[1] == 'text') {
		$class = '';
		if($field[2] == 'start_time' || $field[2] == 'end_time') {
			$class = 'datetimepicker';
		}
		$html .= '<div class="form-group">
                    <label for="'.$field[2].'" class="col-sm-4 control-label">'.$field[0].': </label>
                    <div class="col-sm-8">
                      <input name="'.$field[3].$field[2].'" id="'.$field[3].$field[2].'" class="form-control '.$class.'" value="'.$value.'">
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
              <select id="'.$field[3].'size" name="'.$field[3].'size" class="chosen-select-deselect form-control" width="380">
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
              <select id="'.$field[3].'form" name="'.$field[3].'form" class="chosen-select-deselect form-control" width="380">
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
              <select id="'.$field[3].'form" name="'.$field[3].'form" class="chosen-select-deselect form-control" width="380">
                '.$options.'
              </select>
            </div>
          </div>';
    } else if($field[1] == 'date') {
		$html .= '<div class="form-group">
                    <label for="'.$field[2].'" class="col-sm-4 control-label">'.$field[0].': </label>
                    <div class="col-sm-8">
                      <input class="datepicker form-control" name="'.$field[3].$field[2].'" id="'.$field[3].$field[2].'" value="'.$value.'">
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
              <select id="'.$field[3].'bm" name="'.$field[3].'bm" class="chosen-select-deselect form-control" width="380">
                '.$options.'
              </select>
            </div>
          </div>';
    } else if($field[1] == 'textarea') {
		$html .= '<div class="form-group">
                    <label for="'.$field[2].'" class="col-sm-4 control-label">'.$field[0].': </label>
                    <div class="col-sm-8">
                      <textarea name="'.$field[3].$field[2].'" rows="5" cols="50" class="form-control">'.$value.'</textarea>
                    </div>
                  </div>';
    }
	return $html;
}

function call_staff($dbc, $field, $value, $type = '', $disabled = '') {

	if($type == 'client') {
		$title = 'Client';
		$category = 'Clients';
	} else if($type == 'staff') {
		$title = 'Staff';
		$category = 'Staff';
	}

	$html = '';
	$html .= '<div class="form-group">';
	$html .= '<label for="client" class="col-sm-4 control-label">'.$title.':</label>';
	$html .= '<div class="col-sm-8">';
	$html .= '<select '.$disabled.' class="chosen-select-deselect form-control" data-placeholder="Choose a '.$title.'..." name="'.$field.'" id="'.$field.'">';
	$html .= '<option value=""></option>';

	$query = mysqli_query($dbc,"SELECT contactid, name, first_name, last_name FROM contacts WHERE category = '".$category."' order by name");

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
?>