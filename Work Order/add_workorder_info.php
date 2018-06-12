<script type="text/javascript">
$(document).ready(function() {
	$("#service_type").change(function() {
		var main_service = $("#service_type").find(":selected").text();
		var main_service1 = main_service.replace(/ /g,'');
		var main_service2 = main_service1.replace("&", "__");
		$.ajax({
			type: "GET",
			url: "workorder_ajax_all.php?fill=workorderservice&service_type="+main_service2,
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
			url: "workorder_ajax_all.php?fill=workorderheading&service_category="+subservice2+"&service_type="+main_service2,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#serviceid').html(response);
				$("#serviceid").trigger("change.select2");
			}
		});
	});

});

function changeDesc(cb) {
    //if(cb.checked == 'true') {

		var service_type = $("#service_type").find(":selected").text();
		//var service_type = service_type.replace(/ /g,'++');
		var service_type = service_type.replace("&", "__");

		var service_category = $("#service_category").find(":selected").text();
		//var service_category = service_category.replace(/ /g,'++');
		var service_category = service_category.replace("&", "__");

		var heading = $("#serviceid").find(":selected").text();
		//var heading = heading.replace(/ /g,'++');
		var heading = heading.replace("&", "__");

        $.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "workorder_ajax_all.php?fill=workorderdesc&service_category="+service_category+"&service_type="+service_type+"&heading="+heading,
			dataType: "html",   //expect html to be returned
			success: function(response){
               tinyMCE.get('assign_work').setContent(response, {format : 'raw'});
			}
		});

    //}
}
</script><?php

//New workorders should not show deleted Services but old workorders with deleted Services should
$query_mod = (empty($_GET['workorderid'])) ? 'deleted=0' : '(deleted=0 OR deleted=1)';
$oldservice = mysqli_fetch_array(mysqli_query($dbc, "SELECT `serviceid` FROM `services` WHERE `heading`='{$get_workorder['sub_heading']}' AND `category`='{$get_workorder['service']}' AND `service_type`='{$get_workorder['service_type']}'"))[0];
if($oldservice > 0) {
	mysqli_query($dbc, "UPDATE `workorder` SET `service_type`='', `service`='', `sub_heading`='', `serviceid`=CONCAT('$oldservice,',`serviceid`) WHERE `workorderid`='$workorderid'");
}

foreach(explode(',',mysqli_fetch_array(mysqli_query($dbc, "SELECT `serviceid` FROM `workorder` WHERE `workorderid`='$workorderid'"))[0]) as $serviceid) {
	$service = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid`='$serviceid'")); ?>
	<div class="multi-block">

			<?php if(empty($_GET['supportid'])) {
			if(empty($_GET['workorderid'])) {
			?>
			<div class="form-group">
				<label for="site_name" class="col-sm-4 control-label">Add to Helpdesk</label>
				<div class="col-sm-8">
					<input type="checkbox" value="1" name="add_to_helpdesk">
				</div>
			</div>
			<?php } } ?>

			<div class="form-group">
			  <label for="site_name" class="col-sm-4 control-label"><span class="text-red">*</span> Service Type:</label>
			  <div class="col-sm-8">
				<select data-placeholder="Select a Type..." id="service_type" name="service_type" class="chosen-select-deselect form-control" width="380">
				  <option value=""></option>
				  <?php
					$query = mysqli_query($dbc,"SELECT distinct(service_type) FROM services WHERE ". $query_mod ." order by service_type");
					while($row = mysqli_fetch_array($query)) {
						if($service['service_type'] == $row['service_type']) {
							$selected = ' selected';
						} else {
							$selected = '';
						}
						echo "<option ".$selected." value='". $row['service_type']."'>".$row['service_type'].'</option>';
					}
				  ?>
				</select>
			  </div>
			</div>

			<div class="form-group">
			  <label for="site_name" class="col-sm-4 control-label"><span class="text-red">*</span> Service Category:</label>
			  <div class="col-sm-8">
				<select data-placeholder="Select a Category..." name="service" id="service_category"  class="chosen-select-deselect form-control" width="580">
				  <option value=""></option>
				  <?php
					if($service_type != '') {
						$query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE ". $query_mod ." AND service_type = '$service_type' order by category");
					} else {
						$query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE ". $query_mod ." order by category");
					}
					while($row = mysqli_fetch_array($query)) {
						if($service['category'] == $row['category']) {
							$selected = ' selected';
						} else {
							$selected = '';
						}
						echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';
					}
				  ?>
				</select>
			  </div>
			</div>

			<div class="form-group">
			  <label for="site_name" class="col-sm-4 control-label"><span class="text-red">*</span> Service Heading:</label>
			  <div class="col-sm-7">
				<select data-placeholder="Select a Heading..." name="sub_heading" id="serviceid" data-table="workorder" data-id="<?= $workorderid ?>" data-id-field="workorderid" data-concat="," class="chosen-select-deselect form-control" width="580">
				  <option value=""></option>
				  <?php
					if($service_type != '' && $service != '') {
						$query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE ". $query_mod ." AND service_type = '$service_type' AND category='$service' order by heading");
					} else {
						$query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE ". $query_mod ." order by heading");
					}
					while($row = mysqli_fetch_array($query)) {
						if($serviceid == $row['serviceid']) {
							$selected = ' selected';
						} else {
							$selected = '';
						}
						echo "<option ".$selected." value='". $row['heading']."'>".$row['heading'].'</option>';
					}
				  ?>
				</select>
			  </div>
				<div class="col-sm-1">
					<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
					<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
				</div>
			</div>

	</div>
<?php } ?>