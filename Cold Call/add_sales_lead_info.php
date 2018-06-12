 <script type="text/javascript">
$(document).ready(function() {
    $("#task_businessid").change(function() {
		var businessid = this.value;

        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "call log_ajax_all.php?fill=assigncontact&businessid="+businessid,
            dataType: "html",   //expect html to be returned
            success: function(response){
                var arr = response.split('**#**');
				$('#call log_contact').html(arr[0]);
				$("#call log_contact").trigger("change.select2");
				$('#call log_number').html(arr[1]);
				$("#call log_number").trigger("change.select2");
				$('#call log_email').html(arr[2]);
				$("#call log_email").trigger("change.select2");
                if(arr[3] != 'No') {
                    $(".estimate").attr('onclick', "window.open('<?php echo WEBSITE_URL;?>/Estimate/add_estimate.php?estimateid="+arr[3]+"', \'newwindow\', \'width=900, height=900\'); return false;");
                }
                if(arr[4] != 'No') {
                    $(".quote").attr('onclick', "window.open('<?php echo WEBSITE_URL;?>/Quote/quotes.php?quoteid="+arr[4]+"', \'newwindow\', \'width=900, height=900\'); return false;");
                }
            }
        });
	});

    $("#task_businessid").change(function() {
        if($("#task_businessid option:selected").text() == 'New Business') {
                $( "#new_business" ).show();
        } else {
            $( "#new_business" ).hide();
        }
    });

    $("#call log_contact").change(function() {
        if($("#call log_contact option:selected").text() == 'New Contact') {
                $( "#new_contact" ).show();
        } else {
            $( "#new_contact" ).hide();
        }
    });

    $("#call log_number").change(function() {
        if($("#call log_number option:selected").text() == 'New Number') {
                $( "#new_number" ).show();
        } else {
            $( "#new_number" ).hide();
        }
    });

    $("#call log_email").change(function() {
        if($("#call log_email option:selected").text() == 'New Email') {
                $( "#new_email" ).show();
        } else {
            $( "#new_email" ).hide();
        }
    });
});

</script>
   <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Business:</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Business..." name="businessid" id="task_businessid" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT name, contactid FROM contacts WHERE name != '' AND deleted=0 ORDER BY name");
                echo "<option value = 'New Business'>New Business</option>";
                while($row = mysqli_fetch_array($query)) {
                    if ($businessid == $row['contactid']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
                }
              ?>
            </select>
        </div>
    </div>

   <div class="form-group" id="new_business" style="display: none;">
    <label for="travel_task" class="col-sm-4 control-label">New Business
    </label>
    <div class="col-sm-8">
        <input name="new_business" type="text" class="form-control"/>
    </div>
    </div>

    <?php if(!empty($_GET['call logid'])) { ?>
    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Contact:</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Client..." id="call log_contact" name="contactid" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE businessid = '$businessid' order by first_name");
                while($row = mysqli_fetch_array($query)) {
                    if ($contactid == $row['contactid']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='".$row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
                }
              ?>
            </select>
        </div>
    </div>
    <?php } else { ?>
    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Contact:</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Client..." id="call log_contact" name="contactid" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
            </select>
        </div>
    </div>

   <div class="form-group" id="new_contact" style="display: none;">
    <label for="travel_task" class="col-sm-4 control-label">New Contact
    </label>
    <div class="col-sm-8">
        <input name="new_contact" type="text" class="form-control"/>
    </div>
  </div>

    <?php } ?>

    <?php if(!empty($_GET['call logid'])) { ?>
    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Primary Number:</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Number..." id="call log_number" name="primary_number" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, office_phone, cell_phone FROM contacts WHERE businessid = '$businessid' order by office_phone");
                echo '<option value="">Please Select</option>';
                while($row = mysqli_fetch_array($query)) {
                    if($row['office_phone'] != '') {
                        if ($primary_number == $row['office_phone']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='".$row['office_phone']."'>".decryptIt($row['office_phone']).'</option>';
                    }
                    if($row['cell_phone'] != '') {
                        if ($primary_number == $row['cell_phone']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='".$row['cell_phone']."'>".$row['cell_phone'].'</option>';
                    }
                }
              ?>
            </select>
        </div>
    </div>
    <?php } else { ?>
    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Primary Number:</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Number..." id="call log_number" name="primary_number" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
            </select>
        </div>
    </div>

   <div class="form-group" id="new_number" style="display: none;">
    <label for="travel_task" class="col-sm-4 control-label">New Number
    </label>
    <div class="col-sm-8">
        <input name="new_number" type="text" class="form-control"/>
    </div>
  </div>
    <?php } ?>

    <?php if(!empty($_GET['call logid'])) { ?>
    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Email:</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Email..." id="call log_email" name="email_address" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, email_address FROM contacts WHERE businessid = '$businessid' order by email_address");
                echo '<option value="">Please Select</option>';
                while($row = mysqli_fetch_array($query)) {
                    if(decryptIt($row['email_address']) != '') {
                        if ($email_address == decryptIt($row['email_address'])) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='".decryptIt($row['email_address'])."'>".decryptIt($row['email_address']).'</option>';
                    }
                }
              ?>
            </select>
        </div>
    </div>
    <?php } else { ?>
    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Email:</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Email..." id="call log_email" name="email_address" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
            </select>
        </div>
    </div>

   <div class="form-group" id="new_email" style="display: none;">
    <label for="travel_task" class="col-sm-4 control-label">New Email
    </label>
    <div class="col-sm-8">
        <input name="new_email" type="text" class="form-control"/>
    </div>
  </div>
    <?php } ?>

    <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Lead Value($):</label>
        <div class="col-sm-8">
          <input name="lead_value" value="<?php echo $lead_value; ?>" type="text" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Estimated Close Date:</label>
        <div class="col-sm-8">
          <input name="estimated_close_date" value="<?php echo $estimated_close_date; ?>" type="text" class="datepicker">
        </div>
    </div>
