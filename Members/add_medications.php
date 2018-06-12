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
</script>

    <?php
        $med_get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT medication FROM field_config"));
        $med_value_config = ','.$med_get_field_config['medication'].',';
        $med_medicationcontactid = '';
        $med_medication_type = '';
        $med_category = '';
        $med_medication_code = '';
        $med_heading = '';
        $med_cost = '';
        $med_description = '';
        $med_quote_description = '';
        $med_invoice_description = '';
        $med_ticket_description = '';

        $med_final_retail_price = '';
        $med_name = '';
        $med_fee = '';
        $med_admin_price = '';
        $med_wholesale_price = '';
        $med_commercial_price = '';
        $med_client_price = '';
        $med_minimum_billable = '';
        $med_estimated_hours = '';
        $med_actual_hours = '';
        $med_msrp = '';

        $med_unit_price = '';
        $med_unit_cost = '';
        $med_rent_price = '';
        $med_rental_days = '';
        $med_rental_weeks = '';
        $med_rental_months = '';
        $med_rental_years = '';
        $med_reminder_alert = '';
        $med_daily = '';
        $med_weekly = '';
        $med_monthly = '';
        $med_annually = '';
        $med_total_days = '';
        $med_total_hours = '';
        $med_total_km = '';
        $med_total_miles = '';
        $med_title = '';

        $med_administration_times = '';
        $med_side_effects = '';
        $med_delivery_method = '';
        $med_clientid = '';

        $med_start_date = '';
        $med_end_date = '';
        $med_reminder_date = '';


        if(!empty($_GET['contactid'])) {
            $med_clientid = $_GET['contactid'];
            $med_get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM medication WHERE clientid='$med_clientid'"));
            $med_medicationid = $med_get_contact['medicationid'];
            $med_medicationcontactid = $med_get_contact['contactid'];

            $med_administration_times = $med_get_contact['administration_times'];
            $med_side_effects = $med_get_contact['side_effects'];
            $med_delivery_method = $med_get_contact['delivery_method'];

            $med_medication_type = $med_get_contact['medication_type'];
            $med_category = $med_get_contact['category'];
            $med_medication_code = $med_get_contact['medication_code'];
            $med_heading = $med_get_contact['heading'];
            $med_cost = $med_get_contact['cost'];
            $med_description = $med_get_contact['description'];
            $med_quote_description = $med_get_contact['quote_description'];
            $med_invoice_description = $med_get_contact['invoice_description'];
            $med_ticket_description = $med_get_contact['ticket_description'];
            $med_name = $med_get_contact['name'];
            $med_title = $med_get_contact['title'];
            $med_fee = $med_get_contact['fee'];

            $med_final_retail_price = $med_get_contact['final_retail_price'];
            $med_admin_price = $med_get_contact['admin_price'];
            $med_wholesale_price = $med_get_contact['wholesale_price'];
            $med_commercial_price = $med_get_contact['commercial_price'];
            $med_client_price = $med_get_contact['client_price'];
            $med_minimum_billable = $med_get_contact['minimum_billable'];
            $med_estimated_hours = $med_get_contact['estimated_hours'];
            $med_actual_hours = $med_get_contact['actual_hours'];
            $med_msrp = $med_get_contact['msrp'];

            $med_unit_price = $med_get_contact['unit_price'];
            $med_unit_cost = $med_get_contact['unit_cost'];
            $med_rent_price = $med_get_contact['rent_price'];
            $med_rental_days = $med_get_contact['rental_days'];
            $med_rental_weeks = $med_get_contact['rental_weeks'];
            $med_rental_months = $med_get_contact['rental_months'];
            $med_rental_years = $med_get_contact['rental_years'];
            $med_reminder_alert = $med_get_contact['reminder_alert'];
            $med_daily = $med_get_contact['daily'];
            $med_weekly = $med_get_contact['weekly'];
            $med_monthly = $med_get_contact['monthly'];
            $med_annually = $med_get_contact['annually'];
            $med_total_days = $med_get_contact['total_days'];
            $med_total_hours = $med_get_contact['total_hours'];
            $med_total_km = $med_get_contact['total_km'];
            $med_total_miles = $med_get_contact['total_miles'];

            $med_start_date = $med_get_contact['start_date'];
            $med_end_date = $med_get_contact['end_date'];
            $med_reminder_date = $med_get_contact['reminder_date'];
        ?>
        <input type="hidden" id="medicationid" name="medicationid" value="<?php echo $med_medicationid; ?>" />
        <input type="hidden" id="clientid" name="clientid" value="<?php echo $med_clientid; ?>" />
        <?php   }      ?>
       
        <?php if (strpos($med_value_config, ','."Staff".',') !== FALSE) { ?>
        <div class="form-group">
          <label for="site_name" class="col-sm-4 control-label">Staff:</label>
          <div class="col-sm-8">
            <select data-placeholder="Choose a Staff Member..." name="medicationcontactid" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category='Staff' order by first_name");
                while($row = mysqli_fetch_array($query)) {
                    if ($med_medicationcontactid == $row['contactid']) {
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

        <?php if (strpos($med_value_config, ','."Client".',') !== FALSE) { ?>
        <div class="form-group">
          <label for="site_name" class="col-sm-4 control-label">Member:</label>
          <div class="col-sm-8">
            <select disabled="true" data-placeholder="Choose a Member..." name="clientid" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category='Members' order by first_name");
                while($row = mysqli_fetch_array($query)) {
                    if ($med_clientid == $row['contactid']) {
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

        <?php if (strpos($med_value_config, ','."Medication Type".',') !== FALSE) { ?>
       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Medication Type<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
            <select id="medication_type" name="medication_type" class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <?php
                $types = array('Prescribed', 'Over the Counter', 'PRN');
                foreach($types as $type) {
                    if ($med_medication_type == $type) {
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

      <?php if (strpos($med_value_config, ','."Category".',') !== FALSE) { ?>

      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="med_category" value="<?php echo $med_category; ?>" type="text" id="name" class="form-control">
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

       <?php if (strpos($med_value_config, ','."Title".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Title<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="title" value="<?php echo $med_title; ?>" type="text" id="title" class="form-control">
        </div>
      </div>
      <?php } ?>

<?php if (strpos($med_value_config, ','."Delivery Method".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="delivery_method" class="col-sm-4 control-label">Delivery Method:</label>
    <div class="col-sm-8">
      <textarea name="delivery_method" type="text" class="form-control"><?php echo $med_delivery_method; ?></textarea>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."Side Effects".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="side_effects" class="col-sm-4 control-label">Side Effects:</label>
    <div class="col-sm-8">
      <textarea name="side_effects" type="text" class="form-control"><?php echo $med_side_effects; ?></textarea>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."Administration Times".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="administration_times" class="col-sm-4 control-label">Administration Times:</label>
    <div class="col-sm-8">
      <input name="administration_times" value="<?php echo $med_administration_times; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

    <?php if (strpos($med_value_config, ','."Uploader".',') !== FALSE) {
    ?>

    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Upload Document(s):
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                </span>
        </label>
        <div class="col-sm-8">
            <?php
            if(!empty($_GET['medicationid'])) {
                $query_check_credentials = "SELECT * FROM medication_uploads WHERE medicationid='$medicationid' AND type = 'Document' ORDER BY meduploadid DESC";
                $result = mysqli_query($dbc, $query_check_credentials);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    while($row = mysqli_fetch_array($result)) {
                        $med_meduploadid = $row['meduploadid'];
                        echo '<ul>';
                        echo '<li><a href="download/'.$row['document_link'].'" target="_blank">'.$row['document_link'].'</a> - <a href="add_medication.php?meduploadid='.$med_meduploadid.'&medicationid='.$med_medicationid.'"> Delete</a></li>';
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

    <?php if (strpos($med_value_config, ','."Link".',') !== FALSE) {
    ?>
    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Link(s):<br><em>(e.g. - https://www.google.com)</em>
        </label>
        <div class="col-sm-8">
            <?php
            if(!empty($_GET['medicationid'])) {
                $query_check_credentials = "SELECT * FROM medication_uploads WHERE medicationid='$med_medicationid' AND type = 'Link' ORDER BY meduploadid DESC";
                $result = mysqli_query($dbc, $query_check_credentials);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    $link_no = 1;
                    while($row = mysqli_fetch_array($result)) {
                        $med_meduploadid = $row['meduploadid'];
                        echo '<ul>';
                        echo '<li><a target="_blank" href=\''.$row['document_link'].'\'">Link '.$link_no.'</a> - <a href="add_medication.php?meduploadid='.$med_meduploadid.'&medicationid='.$med_medicationid.'"> Delete</a></li>';
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

       <?php if (strpos($med_value_config, ','."Heading".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Heading:</label>
        <div class="col-sm-8">
          <input name="heading" value="<?php echo $med_heading; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($med_value_config, ','."Name".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Name<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="name" value="<?php echo $med_name; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Medication Code".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Medication Code:</label>
        <div class="col-sm-8">
          <input name="medication_code" value="<?php echo $med_medication_code; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Description:</label>
        <div class="col-sm-8">
          <textarea name="description" rows="5" cols="50" class="form-control"><?php echo $med_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Quote Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label"></label>
        <div class="col-sm-8">
          <input type="checkbox" value="1" name="same_desc">Check this if Quote Description is same as Description.
        </div>
      </div>

      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Quote Description:</label>
        <div class="col-sm-8">
          <textarea name="quote_description" rows="5" cols="50" class="form-control"><?php echo $med_quote_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Invoice Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Invoice Description:</label>
        <div class="col-sm-8">
          <textarea name="invoice_description" rows="5" cols="50" class="form-control"><?php echo $med_invoice_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Ticket Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Ticket Description:</label>
        <div class="col-sm-8">
          <textarea name="ticket_description" rows="5" cols="50" class="form-control"><?php echo $med_ticket_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($med_value_config, ','."Fee".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Fee<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="fee" value="<?php echo $med_fee; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Cost".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Cost:</label>
        <div class="col-sm-8">
          <input name="cost" value="<?php echo $med_cost; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Final Retail Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Final Retail Price:</label>
        <div class="col-sm-8">
          <input name="final_retail_price" value="<?php echo $med_final_retail_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

        <?php if (strpos($med_value_config, ','."Admin Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Admin Price:</label>
        <div class="col-sm-8">
          <input name="admin_price" value="<?php echo $med_admin_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Wholesale Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Wholesale Price:</label>
        <div class="col-sm-8">
          <input name="wholesale_price" value="<?php echo $med_wholesale_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Commercial Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Commercial Price:</label>
        <div class="col-sm-8">
          <input name="commercial_price" value="<?php echo $med_commercial_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Client Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Client Price:</label>
        <div class="col-sm-8">
          <input name="client_price" value="<?php echo $med_client_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Minimum Billable".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Minimum Billable:</label>
        <div class="col-sm-8">
          <input name="minimum_billable" value="<?php echo $med_minimum_billable; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Estimated Hours".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Estimated Hours:</label>
        <div class="col-sm-8">
          <input name="estimated_hours" value="<?php echo $med_estimated_hours; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."Actual Hours".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Actual Hours:</label>
        <div class="col-sm-8">
          <input name="actual_hours" value="<?php echo $med_actual_hours; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($med_value_config, ','."MSRP".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">MSRP:</label>
        <div class="col-sm-8">
          <input name="msrp" value="<?php echo $med_msrp; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

    <?php if (strpos($med_value_config, ','."Unit Price".',') !== FALSE) { ?>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Unit Price:</label>
    <div class="col-sm-8">
      <input name="unit_price" value="<?php echo $med_unit_price; ?>" type="text" class="form-control">
    </div>
    </div>
    <?php } ?>

    <?php if (strpos($med_value_config, ','."Unit Cost".',') !== FALSE) { ?>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Unit Cost:</label>
    <div class="col-sm-8">
      <input name="unit_cost" value="<?php echo $med_unit_cost; ?>" type="text" class="form-control">
    </div>
    </div>
    <?php } ?>

  <?php if (strpos($med_value_config, ','."Rent Price".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rent Price:</label>
    <div class="col-sm-8">
      <input name="rent_price" value="<?php echo $med_rent_price; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>


  <?php if (strpos($med_value_config, ','."Rental Days".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Days:</label>
    <div class="col-sm-8">
      <input name="rental_days" value="<?php echo $med_rental_days; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($med_value_config, ','."Rental Weeks".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Weeks:</label>
    <div class="col-sm-8">
      <input name="rental_weeks" value="<?php echo $med_rental_weeks; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."Rental Months".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Months:</label>
    <div class="col-sm-8">
      <input name="rental_months" value="<?php echo $med_rental_months; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."Rental Years".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Years:</label>
    <div class="col-sm-8">
      <input name="rental_years" value="<?php echo $med_rental_years; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."Reminder/Alert".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Reminder/Alert:</label>
    <div class="col-sm-8">
      <input name="reminder_alert" value="<?php echo $med_reminder_alert; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>


  <?php if (strpos($med_value_config, ','."Daily".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Daily:</label>
    <div class="col-sm-8">
      <input name="daily" value="<?php echo $med_daily; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($med_value_config, ','."Weekly".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Weekly:</label>
    <div class="col-sm-8">
      <input name="weekly" value="<?php echo $med_weekly; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."Monthly".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Monthly:</label>
    <div class="col-sm-8">
      <input name="monthly" value="<?php echo $med_monthly; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."Annually".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Annually:</label>
    <div class="col-sm-8">
      <input name="annually" value="<?php echo $med_annually; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($med_value_config, ','."#Of Days".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Days:</label>
    <div class="col-sm-8">
      <input name="total_days" value="<?php echo $med_total_days; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."#Of Hours".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Hours:</label>
    <div class="col-sm-8">
      <input name="total_hours" value="<?php echo $med_total_hours; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($med_value_config, ','."#Of Kilometers".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Kilometers:</label>
    <div class="col-sm-8">
      <input name="total_km" value="<?php echo $med_total_km; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."#Of Miles".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Miles:</label>
    <div class="col-sm-8">
      <input name="total_miles" value="<?php echo $med_total_miles; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."Start Date".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Start Date:</label>
    <div class="col-sm-8">
      <input name="start_date" value="<?php echo $med_start_date; ?>" type="text" class="datepicker form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."End Date".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">End Date:</label>
    <div class="col-sm-8">
      <input name="end_date" value="<?php echo $med_end_date; ?>" type="text" class="datepicker form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($med_value_config, ','."Reminder Date".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Reminder Date:
        <span class="popover-examples list-inline">&nbsp;
            <a data-toggle="tooltip" data-placement="top" title="" data-original-title="An email will be sent out on this date as a reminder that a medication requires attention."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a>
        </span>
    </label>
    <div class="col-sm-8">
      <input name="reminder_date" value="<?php echo $med_reminder_date; ?>" type="text" class="datepicker form-control">
    </div>
  </div>
  <?php } ?>