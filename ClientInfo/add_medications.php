<?php
/*
Add Vendor
*/
error_reporting(0);
$from_url = (!empty($_GET['from_url']) ? $_GET['from_url'] : 'medication.php');

function history($dbc, $operation, $medicationid) {
        $user = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO `medication_history` (`user`,`datetime`,`operation`,`medicationid`) VALUES ('$user', '$date', '$operation', '$medicationid')";
        $result = mysqli_query($dbc, $query);
}

if(isset($_GET['action']) && $_GET['action'] == 'delete') {
    $medicationid = $_GET['medicationid'];
    $query = mysqli_query($dbc,"DELETE FROM medication WHERE medicationid='$medicationid'");
    history($dbc, 'Delete', $medicationid);
    echo '<script type="text/javascript"> window.location.replace("'.$from_url.'"); </script>';
}

if(isset($_GET['action']) && $_GET['action'] == 'archive') {
    $medicationid = $_GET['medicationid'];
    $query = mysqli_query($dbc,"UPDATE medication SET deleted = 1 WHERE medicationid='$medicationid'");
    history($dbc, 'Archive', $medicationid);
    echo '<script type="text/javascript"> window.location.replace("'.$from_url.'"); </script>';
}


if(!empty($_GET['meduploadid'])) {
    $meduploadid = $_GET['meduploadid'];
    $query = mysqli_query($dbc,"DELETE FROM medication_uploads WHERE meduploadid='$meduploadid'");
    $medicationid = $_GET['medicationid'];

    echo '<script type="text/javascript"> window.location.replace("add_medication.php?medicationid='.$medicationid.'&from_url='.$from_url.'"); </script>';
}

?>
<script type="text/javascript">
$(document).ready(function() {
    $("#medication_type").change(function() {
        if($("#medication_type option:selected").text() == 'New Medication') {
                $( "#new_medication" ).show();
        } else {
            $( "#new_medication" ).hide();
        }
    });

    $("#category").change(function() {
        if($("#category option:selected").text() == 'New Category') {
                $( "#new_category" ).show();
        } else {
            $( "#new_category" ).hide();
        }
    });

    $('#add_row_doc').on( 'click', function () {
        var clone = $('.additional_doc').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_doc");
        $('#add_here_new_doc').append(clone);
        return false;
    });

    $('#add_row_link').on( 'click', function () {
        var clone = $('.additional_link').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_link");
        $('#add_here_new_link').append(clone);
        return false;
    });

});
function deleteMedicationUpload(list, meduploadid) {
    $.ajax({
        method: "POST",
        url: "../Contacts/contacts_ajax.php?action=delete_medication_upload",
        data: { meduploadid: meduploadid },
        success: function(response) {
            $(list).closest('li').remove();
        }
    });
}
</script>

    <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT medication FROM field_config"));
        $value_config = ','.$get_field_config['medication'].',';
        $medicationcontactid = '';
        $medication_type = '';
        $category = !empty(get_config($dbc, 'medication_category_default')) ? get_config($dbc, 'medication_category_default') : '';
        $medication_code = '';
        $heading = '';
        $cost = '';
        $description = '';
        $quote_description = '';
        $invoice_description = '';
        $ticket_description = '';

        $final_retail_price = '';
        $name = '';
        $fee = '';
        $admin_price = '';
        $wholesale_price = '';
        $commercial_price = '';
        $client_price = '';
        $minimum_billable = '';
        $estimated_hours = '';
        $actual_hours = '';
        $msrp = '';

        $unit_price = '';
        $unit_cost = '';
        $rent_price = '';
        $rental_days = '';
        $rental_weeks = '';
        $rental_months = '';
        $rental_years = '';
        $reminder_alert = '';
        $daily = '';
        $weekly = '';
        $monthly = '';
        $annually = '';
        $total_days = '';
        $total_hours = '';
        $total_km = '';
        $total_miles = '';
        $title = '';

        $administration_times = '';
        $side_effects = '';
        $delivery_method = '';
        $clientid = '';

        $start_date = '';
        $end_date = '';
        $reminder_date = '';


        if(!empty($_GET['contactid'])) {
            $clientid = $_GET['contactid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM medication WHERE clientid='$clientid'"));
            $medicationid = $get_contact['medicationid'];
            $medicationcontactid = $get_contact['contactid'];

            $administration_times = $get_contact['administration_times'];
            $side_effects = $get_contact['side_effects'];
            $delivery_method = $get_contact['delivery_method'];

            $medication_type = $get_contact['medication_type'];
            $category = !empty(get_config($dbc, 'medication_category_default')) ? get_config($dbc, 'medication_category_default') : '';
            $medication_code = $get_contact['medication_code'];
            $heading = $get_contact['heading'];
            $cost = $get_contact['cost'];
            $description = $get_contact['description'];
            $quote_description = $get_contact['quote_description'];
            $invoice_description = $get_contact['invoice_description'];
            $ticket_description = $get_contact['ticket_description'];
            $name = $get_contact['name'];
            $title = $get_contact['title'];
            $fee = $get_contact['fee'];

            $final_retail_price = $get_contact['final_retail_price'];
            $admin_price = $get_contact['admin_price'];
            $wholesale_price = $get_contact['wholesale_price'];
            $commercial_price = $get_contact['commercial_price'];
            $client_price = $get_contact['client_price'];
            $minimum_billable = $get_contact['minimum_billable'];
            $estimated_hours = $get_contact['estimated_hours'];
            $actual_hours = $get_contact['actual_hours'];
            $msrp = $get_contact['msrp'];

            $unit_price = $get_contact['unit_price'];
            $unit_cost = $get_contact['unit_cost'];
            $rent_price = $get_contact['rent_price'];
            $rental_days = $get_contact['rental_days'];
            $rental_weeks = $get_contact['rental_weeks'];
            $rental_months = $get_contact['rental_months'];
            $rental_years = $get_contact['rental_years'];
            $reminder_alert = $get_contact['reminder_alert'];
            $daily = $get_contact['daily'];
            $weekly = $get_contact['weekly'];
            $monthly = $get_contact['monthly'];
            $annually = $get_contact['annually'];
            $total_days = $get_contact['total_days'];
            $total_hours = $get_contact['total_hours'];
            $total_km = $get_contact['total_km'];
            $total_miles = $get_contact['total_miles'];

            $start_date = $get_contact['start_date'];
            $end_date = $get_contact['end_date'];
            $reminder_date = $get_contact['reminder_date'];
        ?>
        <input type="hidden" id="medicationid" name="medicationid" value="<?php echo $medicationid; ?>" />
        <input type="hidden" id="clientid" name="clientid" value="<?php echo $clientid; ?>" />
        <?php   }      ?>
        <input type="hidden" id="submit_type" name="submit_type" value="medications" />

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse1" >
                Medications<span class="glyphicon glyphicon-plus"></span>
            </a>
        </h4>
    </div>

    <div id="collapse1" class="panel-collapse collapse">
        <div class="panel-body">
       
        <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { ?>
        <div class="form-group">
          <label for="site_name" class="col-sm-4 control-label">Staff:</label>
          <div class="col-sm-8">
            <select data-placeholder="Choose a Staff Member..." name="medicationcontactid" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category='Staff' order by first_name");
                while($row = mysqli_fetch_array($query)) {
                    if ($medicationcontactid == $row['contactid']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
                }
              ?>
            </select>
          </div>
        </div>
        <?php } ?>

        <?php if (strpos($value_config, ','."Client".',') !== FALSE) { ?>
        <div class="form-group">
          <label for="site_name" class="col-sm-4 control-label">Client:</label>
          <div class="col-sm-8">
            <select disabled="true" data-placeholder="Choose a Client..." name="clientid" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category='Clients' order by first_name");
                while($row = mysqli_fetch_array($query)) {
                    if ($clientid == $row['contactid']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
                }
              ?>
            </select>
          </div>
        </div>
        <?php } ?>

        <?php if (strpos($value_config, ','."Medication Type".',') !== FALSE) { ?>
       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Medication Type<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
            <select id="medication_type" name="medication_type" class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <?php
                $types = array('Prescribed', 'Over the Counter', 'PRN');
                if(!empty(get_config($dbc, 'medication_medtype_custom'))) {
                    $types = explode(',', get_config($dbc, 'medication_medtype_custom'));
                }
                foreach($types as $type) {
                    if ($medication_type == $type) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $type."'>".$type.'</option>';

                }
                //echo "<option value = 'Other'>New Medication</option>";
                ?>
            </select>
        </div>
      </div>

       <!--div class="form-group" id="new_medication" style="display: none;">
        <label for="travel_task" class="col-sm-4 control-label">New Medication Type:
        </label>
        <div class="col-sm-8">
            <input name="new_medication" type="text" class="form-control" />
        </div>
      </div-->
      <?php } ?>

      <?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>

      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="med_category" value="<?php echo $category; ?>" type="text" id="name" class="form-control">
        </div>
      </div>

      <?php /* ?>
       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
            <select id="category" name="category" class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <?php
                $query = mysqli_query($dbc,"SELECT distinct(category) FROM medication order by category");
                while($row = mysqli_fetch_array($query)) {
                    if ($category == $row['category']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';

                }
                echo "<option value = 'Other'>New Category</option>";
                ?>
            </select>
        </div>
      </div>

       <div class="form-group" id="new_category" style="display: none;">
        <label for="travel_task" class="col-sm-4 control-label">New Category:
        </label>
        <div class="col-sm-8">
            <input name="new_category" type="text" class="form-control" />
        </div>
      </div>
      <?php */ ?>

      <?php } ?>

       <?php if (strpos($value_config, ','."Title".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label"><?= (!empty(get_config($dbc, 'medication_title_custom')) ? get_config($dbc, 'medication_title_custom') : 'Title') ?><span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="title" value="<?php echo $title; ?>" type="text" id="title" class="form-control">
        </div>
      </div>
      <?php } ?>

<?php if (strpos($value_config, ','."Delivery Method".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="delivery_method" class="col-sm-4 control-label">Delivery Method:</label>
    <div class="col-sm-8">
      <textarea name="delivery_method" type="text" class="form-control"><?php echo $delivery_method; ?></textarea>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Side Effects".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="side_effects" class="col-sm-4 control-label">Side Effects:</label>
    <div class="col-sm-8">
      <textarea name="side_effects" type="text" class="form-control"><?php echo $side_effects; ?></textarea>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Administration Times".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="administration_times" class="col-sm-4 control-label">Administration Times:</label>
    <div class="col-sm-8">
      <input name="administration_times" value="<?php echo $administration_times; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

    <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) {
    ?>

    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Upload Document(s):
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                </span>
        </label>
        <div class="col-sm-8">
            <?php
            if(!empty($medicationid)) {
                $query_check_credentials = "SELECT * FROM medication_uploads WHERE medicationid='$medicationid' AND type = 'Document' ORDER BY meduploadid DESC";
                $result = mysqli_query($dbc, $query_check_credentials);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    while($row = mysqli_fetch_array($result)) {
                        $meduploadid = $row['meduploadid'];
                        echo '<ul>';
                        echo '<li><a href="download/medications/'.$row['document_link'].'" target="_blank">'.$row['document_link'].'</a> - <a onclick="deleteMedicationUpload(this,'.$meduploadid.'); return false;" href=""> Delete</a></li>';
                        echo '</ul>';
                    }
                }
            }
            ?>
            <div class="enter_cost additional_doc clearfix">
                <div class="clearfix"></div>

                <div class="form-group clearfix">
                    <div class="col-sm-5">
                        <input name="upload_document[]" multiple type="file" data-filename-placement="inside" class="form-control" />
                    </div>
                </div>

            </div>

            <div id="add_here_new_doc"></div>

            <div class="form-group triple-gapped clearfix">
                <div class="col-sm-offset-4 col-sm-8">
                    <button id="add_row_doc" class="btn brand-btn pull-left">Add Another Document</button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($value_config, ','."Link".',') !== FALSE) {
    ?>
    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Link(s):<br><em>(e.g. - http://www.google.com)</em>
        </label>
        <div class="col-sm-8">
            <?php
            if(!empty($_GET['medicationid'])) {
                $query_check_credentials = "SELECT * FROM medication_uploads WHERE medicationid='$medicationid' AND type = 'Link' ORDER BY meduploadid DESC";
                $result = mysqli_query($dbc, $query_check_credentials);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    $link_no = 1;
                    while($row = mysqli_fetch_array($result)) {
                        $meduploadid = $row['meduploadid'];
                        echo '<ul>';
                        echo '<li><a target="_blank" href=\''.$row['document_link'].'\'">Link '.$link_no.'</a> - <a href="add_medication.php?meduploadid='.$meduploadid.'&medicationid='.$medicationid.'"> Delete</a></li>';
                        echo '</ul>';
                        $link_no++;
                    }
                }
            }
            ?>
            <div class="enter_cost additional_link clearfix">
                <div class="clearfix"></div>

                <div class="form-group clearfix">
                    <div class="col-sm-5">
                        <input name="support_link[]" type="text" class="form-control">
                    </div>
                </div>

            </div>

            <div id="add_here_new_link"></div>

            <div class="form-group triple-gapped clearfix">
                <div class="col-sm-offset-4 col-sm-8">
                    <button id="add_row_link" class="btn brand-btn pull-left">Add More Links</button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

       <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Heading:</label>
        <div class="col-sm-8">
          <input name="heading" value="<?php echo $heading; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Name".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Name<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="name" value="<?php echo $name; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Medication Code".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Medication Code:</label>
        <div class="col-sm-8">
          <input name="medication_code" value="<?php echo $medication_code; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Description:</label>
        <div class="col-sm-8">
          <textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label"></label>
        <div class="col-sm-8">
          <input type="checkbox" value="1" name="same_desc">Check this if Quote Description is same as Description.
        </div>
      </div>

      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Quote Description:</label>
        <div class="col-sm-8">
          <textarea name="quote_description" rows="5" cols="50" class="form-control"><?php echo $quote_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Invoice Description:</label>
        <div class="col-sm-8">
          <textarea name="invoice_description" rows="5" cols="50" class="form-control"><?php echo $invoice_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Ticket Description:</label>
        <div class="col-sm-8">
          <textarea name="ticket_description" rows="5" cols="50" class="form-control"><?php echo $ticket_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Fee".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Fee<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="fee" value="<?php echo $fee; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Cost:</label>
        <div class="col-sm-8">
          <input name="cost" value="<?php echo $cost; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Final Retail Price:</label>
        <div class="col-sm-8">
          <input name="final_retail_price" value="<?php echo $final_retail_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

        <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Admin Price:</label>
        <div class="col-sm-8">
          <input name="admin_price" value="<?php echo $admin_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Wholesale Price:</label>
        <div class="col-sm-8">
          <input name="wholesale_price" value="<?php echo $wholesale_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Commercial Price:</label>
        <div class="col-sm-8">
          <input name="commercial_price" value="<?php echo $commercial_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Client Price:</label>
        <div class="col-sm-8">
          <input name="client_price" value="<?php echo $client_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Minimum Billable:</label>
        <div class="col-sm-8">
          <input name="minimum_billable" value="<?php echo $minimum_billable; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Estimated Hours:</label>
        <div class="col-sm-8">
          <input name="estimated_hours" value="<?php echo $estimated_hours; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Actual Hours:</label>
        <div class="col-sm-8">
          <input name="actual_hours" value="<?php echo $actual_hours; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">MSRP:</label>
        <div class="col-sm-8">
          <input name="msrp" value="<?php echo $msrp; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

    <?php if (strpos($value_config, ','."Unit Price".',') !== FALSE) { ?>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Unit Price:</label>
    <div class="col-sm-8">
      <input name="unit_price" value="<?php echo $unit_price; ?>" type="text" class="form-control">
    </div>
    </div>
    <?php } ?>

    <?php if (strpos($value_config, ','."Unit Cost".',') !== FALSE) { ?>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Unit Cost:</label>
    <div class="col-sm-8">
      <input name="unit_cost" value="<?php echo $unit_cost; ?>" type="text" class="form-control">
    </div>
    </div>
    <?php } ?>

  <?php if (strpos($value_config, ','."Rent Price".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rent Price:</label>
    <div class="col-sm-8">
      <input name="rent_price" value="<?php echo $rent_price; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>


  <?php if (strpos($value_config, ','."Rental Days".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Days:</label>
    <div class="col-sm-8">
      <input name="rental_days" value="<?php echo $rental_days; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."Rental Weeks".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Weeks:</label>
    <div class="col-sm-8">
      <input name="rental_weeks" value="<?php echo $rental_weeks; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Rental Months".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Months:</label>
    <div class="col-sm-8">
      <input name="rental_months" value="<?php echo $rental_months; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Rental Years".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Years:</label>
    <div class="col-sm-8">
      <input name="rental_years" value="<?php echo $rental_years; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Reminder/Alert".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Reminder/Alert:</label>
    <div class="col-sm-8">
      <input name="reminder_alert" value="<?php echo $reminder_alert; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>


  <?php if (strpos($value_config, ','."Daily".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Daily:</label>
    <div class="col-sm-8">
      <input name="daily" value="<?php echo $daily; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."Weekly".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Weekly:</label>
    <div class="col-sm-8">
      <input name="weekly" value="<?php echo $weekly; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Monthly".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Monthly:</label>
    <div class="col-sm-8">
      <input name="monthly" value="<?php echo $monthly; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Annually".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Annually:</label>
    <div class="col-sm-8">
      <input name="annually" value="<?php echo $annually; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."#Of Days".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Days:</label>
    <div class="col-sm-8">
      <input name="total_days" value="<?php echo $total_days; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."#Of Hours".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Hours:</label>
    <div class="col-sm-8">
      <input name="total_hours" value="<?php echo $total_hours; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."#Of Kilometers".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Kilometers:</label>
    <div class="col-sm-8">
      <input name="total_km" value="<?php echo $total_km; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."#Of Miles".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Miles:</label>
    <div class="col-sm-8">
      <input name="total_miles" value="<?php echo $total_miles; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Start Date".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Start Date:</label>
    <div class="col-sm-8">
      <input name="start_date" value="<?php echo $start_date; ?>" type="text" class="datepicker form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."End Date".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">End Date:</label>
    <div class="col-sm-8">
      <input name="end_date" value="<?php echo $end_date; ?>" type="text" class="datepicker form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Reminder Date".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Reminder Date:
        <span class="popover-examples list-inline">&nbsp;
            <a data-toggle="tooltip" data-placement="top" title="" data-original-title="An email will be sent out on this date as a reminder that a medication requires attention."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a>
        </span>
    </label>
    <div class="col-sm-8">
      <input name="reminder_date" value="<?php echo $reminder_date; ?>" type="text" class="datepicker form-control">
    </div>
  </div>
  <?php } ?>
</div>
</div>
</div>