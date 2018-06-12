<script type="text/javascript">
$(document).ready(function() {

	$("#service_type").change(function() {
		var main_service = $("#service_type").find(":selected").text();
		var main_service1 = main_service.replace(/ /g,'');
		var main_service2 = main_service1.replace("&", "__");
		$.ajax({
			type: "GET",
			url: "estimated_gantt_chart_ajax_all.php?fill=ticketservice&service_type="+main_service2,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#service_category').html(response);
				$("#service_category").trigger("change.select2");
			}
		});
	});

    $("#service_category").change(function() {
		var main_service = $("#service_type").find(":selected").text();
		var main_service1 = main_service.replace(/ /g,'');
		var main_service2 = main_service1.replace("&", "__");

		var subservice = $("#service_category").find(":selected").text();
		var subservice1 = subservice.replace(/ /g,'');
		var subservice2 = subservice1.replace("&", "__");

		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "estimated_gantt_chart_ajax_all.php?fill=ticketheading&service_category="+subservice2+"&service_type="+main_service2,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#serviceid').html(response);
				$("#serviceid").trigger("change.select2");
			}
		});
	});
});
</script>

<div class="col-md-12">

    <div class="form-group clearfix completion_date">
        <label for="first_name" class="col-sm-4 control-label text-right">Business<span class="brand-color">*</span>:</label>
        <div class="col-sm-8">
            <select name="businessid" id="businessid" data-placeholder="Select a Client..." class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
				<?php
						$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Business' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
						foreach($query as $id) {
							$selected = '';
							$selected = $id == $businessid ? 'selected = "selected"' : '';
							echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id,'name').'</option>';
						}
					  ?>
            </select>
        </div>
    </div>

    <div class="form-group">
      <label for="site_name" class="col-sm-4 control-label">Service Type<span class="text-red">*</span>:</label>
      <div class="col-sm-8">
        <select data-placeholder="Choose a Type..." id="service_type" name="service_type" class="chosen-select-deselect form-control" width="380">
          <option value=""></option>
          <?php
            $query = mysqli_query($dbc,"SELECT distinct(service_type) FROM services WHERE deleted=0");
            while($row = mysqli_fetch_array($query)) {
                echo "<option value='". $row['service_type']."'>".$row['service_type'].'</option>';
            }
          ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="site_name" class="col-sm-4 control-label">Service Category<span class="text-red">*</span>:</label>
      <div class="col-sm-8">
        <select data-placeholder="Choose a Category..." name="service" id="service_category"  class="chosen-select-deselect form-control" width="580">
          <option value=""></option>
          <?php
            $query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE deleted=0");
            while($row = mysqli_fetch_array($query)) {
                echo "<option value='". $row['category']."'>".$row['category'].'</option>';
            }
          ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="site_name" class="col-sm-4 control-label">Service Heading<span class="text-red">*</span>:</label>
      <div class="col-sm-8">
        <select data-placeholder="Choose a Heading..." name="sub_heading" id="serviceid"  class="chosen-select-deselect form-control" width="580">
          <option value=""></option>
          <?php
            $query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE deleted=0");
            while($row = mysqli_fetch_array($query)) {
                echo "<option value='". $row['serviceid']."'>".$row['heading'].'</option>';
            }
          ?>
        </select>
      </div>
    </div>

    <div class="form-group">
        <label for="first_name" class="col-sm-4 control-label">Heading<span class="text-red">*</span>:</label>
        <div class="col-sm-8">
            <input name="heading" type="text" value="<?php echo $heading; ?>" class="form-control">

            <!--<select data-placeholder="Choose a Heading..." name="heading" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php
                $tabs = get_config($dbc, 'ticket_heading');
                $each_tab = explode(',', $tabs);
                foreach ($each_tab as $cat_tab) {
                    if ($heading == $cat_tab) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                }
              ?>
            </select>
            -->
        </div>
    </div>

    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Phase#:</label>
        <div class="col-sm-8">
            <input name="phase" value="<?php echo $phase; ?>" type="text" class="form-control"></p>
        </div>
    </div>

    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Estimated Start Date:</label>
        <div class="col-sm-8">
            <input name="start_date" value="<?php echo $to_do_date; ?>" type="text" class="datepicker"></p>
        </div>
    </div>

    <div class="form-group clearfix">
        <label for="first_name" class="col-sm-4 control-label text-right">Estimated End Date:</label>
        <div class="col-sm-8">
            <input name="end_date" value="<?php echo $internal_qa_date; ?>" type="text" class="datepicker"></p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-6">
            <a href="estimated_gantt_chart.php" class="btn brand-btn">Back</a>
			<!--<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>-->
        </div>
        <div class="col-sm-6">
            <button type="submit" name="submit" value="submit" class="btn brand-btn pull-right">Submit</button>
        </div>
    </div>

</div>