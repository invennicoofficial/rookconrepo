 <script type="text/javascript">
$(document).ready(function() {
    $("#task_businessid").change(function() {
		var businessid = this.value;

        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "sales_ajax_all.php?fill=assigncontact&businessid="+businessid,
            dataType: "html",   //expect html to be returned
            success: function(response){
                var arr = response.split('**#**');
				$('#sales_contact').html(arr[0]);
				$("#sales_contact").trigger("change.select2");
				//$('#sales_number').html(arr[1]);
				//$("#sales_number").trigger("change.select2");
				//$('#sales_email').html(arr[2]);
				//$("#sales_email").trigger("change.select2");
                if(arr[4] != 'No' && arr[3] != 'No') {
                    $(".estimate").html("<a href=\"#\" onclick=\"window.open('<?php echo WEBSITE_URL; ?>/Estimate/add_estimate.php?estimateid="+arr[3]+"', \'newwindow\', \'width=900, height=900\'); return false;\">Click to View Estimate</a>");

                    var quote_html = "<a href=\"#\" onclick=\"window.open('<?php echo WEBSITE_URL; ?>/Quote/quotes.php?quoteid="+arr[4]+"', \'newwindow\', \'width=900, height=900\'); return false;\">Click to View Quote</a>";
                    quote_html += "<br><a href=\"<?php echo WEBSITE_URL; ?>/Estimate/download/quote_"+arr[3]+".pdf\" target=\"_blank\"><img src=\"<?php echo WEBSITE_URL; ?>/img/pdf.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"View\">View PDF</a>";
                    $(".quote").html(quote_html);
                } else if(arr[3] != 'No') {
                    $(".estimate").html("<a href=\"#\" onclick=\"window.open('<?php echo WEBSITE_URL; ?>/Estimate/add_estimate.php?estimateid="+arr[3]+"', \'newwindow\', \'width=900, height=900\'); return false;\">Click to View Estimate</a>");

                    var quote_html = "No Quote<br>";
                    quote_html += "<a href=\"#\" onclick=\"window.open('<?php echo WEBSITE_URL; ?>/Estimate/estimate.php?businessid="+businessid+"&from=<?php echo urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']); ?>', \'newwindow\', \'width=900, height=900\'); return false;\">Click to View/Approve Estimate</a>";
                    $(".quote").html(quote_html);
                } else {
                    $(".estimate").html("<a href=\"#\" onclick=\"window.open('<?php echo WEBSITE_URL; ?>/Estimate/add_estimate.php?from=<?php echo urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']); ?>', \'newwindow\', \'width=900, height=900\'); return false;\">Click to Add Estimate</a>");

                    $(".quote").html("No Estimate or Quote");
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

    $("#sales_contact").change(function() {
        var contactid = this.value;

        if($("#sales_contact option:selected").text() == 'New Contact') {
                $( "#new_contact" ).show();
        } else {
            $( "#new_contact" ).hide();
        }

        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "sales_ajax_all.php?fill=assignphoneemail&contactid="+contactid,
            dataType: "html",   //expect html to be returned
            success: function(response){
                var arr = response.split('**#**');
				$('#sales_number').html(arr[0]);
				$("#sales_number").trigger("change.select2");
				$('#sales_email').html(arr[1]);
				$("#sales_email").trigger("change.select2");
            }
        });

    });

    $("#sales_number").change(function() {
        if($("#sales_number option:selected").text() == 'New Number') {
                $( "#new_number" ).show();
        } else {
            $( "#new_number" ).hide();
        }
    });

    $("#sales_email").change(function() {
        if($("#sales_email option:selected").text() == 'New Email') {
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
            <select data-placeholder="Select a Business..." name="businessid" id="task_businessid" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
              <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT name, contactid FROM contacts WHERE (category IN ('Business','Sales Lead') AND deleted=0) OR `contactid`='$businessid'"),MYSQLI_ASSOC));
                echo "<option value = 'New Business'>New Business</option>";
                foreach($query as $id) {
                    echo "<option ".($businessid == $id ? 'selected' : '')." value='". $id."'>".get_client($dbc, $id).'</option>';
                } ?>
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

    <?php if(!empty($_GET['salesid'])) { ?>
    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Contact:</label>
        <div class="col-sm-8">
            <select data-placeholder="Select a Client..." id="sales_contact" name="contactid" class="chosen-select-deselect form-control1" width="380">
				<option value=""></option>
				<option value = 'New Contact'>New Contact</option>
              <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE businessid = '$businessid' OR `contactid`='$contactid' order by first_name"),MYSQLI_ASSOC));
				foreach($query as $id) {
					echo "<option ".($contactid == $id ? 'selected' : '')." value='".$id."'>".get_contact($dbc, $id).'</option>';
				} ?>
            </select>
        </div>
    </div>
    <?php } else { ?>
    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Contact:</label>
        <div class="col-sm-8">
            <select data-placeholder="Select a Client..." id="sales_contact" name="contactid" class="chosen-select-deselect form-control1" width="380">
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

    <?php if(!empty($_GET['salesid'])) { ?>
    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Primary Number:</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Number..." id="sales_number" name="primary_number" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, office_phone, cell_phone FROM contacts WHERE businessid = '$businessid' order by office_phone");
                while($row = mysqli_fetch_array($query)) {
                    if($row['office_phone'] != '') {
                        if ($primary_number == decryptIt($row['office_phone'])) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='".decryptIt($row['office_phone'])."'>".decryptIt($row['office_phone']).'</option>';
                    }
                    if($row['cell_phone'] != '') {
                        if ($primary_number == decryptIt($row['cell_phone'])) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='".decryptIt($row['cell_phone'])."'>".decryptIt($row['cell_phone']).'</option>';
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
            <select data-placeholder="Choose a Number..." id="sales_number" name="primary_number" class="chosen-select-deselect form-control1" width="380">
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

    <?php if(!empty($_GET['salesid'])) { ?>
    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Email:</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Email..." id="sales_email" name="email_address" class="chosen-select-deselect form-control1" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, email_address FROM contacts WHERE businessid = '$businessid' order by email_address");
                while($row = mysqli_fetch_array($query)) {
                    $get_email_address = get_email($dbc, $row['contactid']);
                    if($get_email_address != '') {
                        if ($email_address == $get_email_address) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='".$get_email_address."'>".$get_email_address.'</option>';
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
            <select data-placeholder="Choose a Email..." id="sales_email" name="email_address" class="chosen-select-deselect form-control1" width="380">
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
