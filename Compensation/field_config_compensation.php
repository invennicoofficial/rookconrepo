<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('goals_compensation');
error_reporting(0);

if (isset($_POST['submit'])) {
    //Base Pay

    $spp = implode('*#*',$_POST['staff_performance_pay_name']);
    $staff_performance_pay = filter_var($spp,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='staff_performance_pay'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$staff_performance_pay' WHERE name='staff_performance_pay'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('staff_performance_pay', '$staff_performance_pay')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $sbp = implode(',',$_POST['staff_base_pay']);
    $staff_base_pay = filter_var($sbp,FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='staff_base_pay'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$staff_base_pay' WHERE name='staff_base_pay'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('staff_base_pay', '$staff_base_pay')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $comp_staff_groups = filter_var(implode(',',$_POST['staff_groups']),FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='comp_staff_groups'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$comp_staff_groups' WHERE name='comp_staff_groups'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('comp_staff_groups', '$comp_staff_groups')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_compensation.php"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
    var add_new_base = 1;
    $('#deletebase_0').hide();
    $('#add_pay_button').on( 'click', function () {
        $('#deletebase_0').show();
        var clone = $('.additional_pay').clone();
        clone.find('.form-control').val('');
        clone.find('.rate').val('0');

        clone.find('#base_0').attr('id', 'performance_'+add_new_base);
        clone.find('#deletebase_0').attr('id', 'deletebase_'+add_new_base);
        $('#deletebase_0').hide();

        clone.removeClass("additional_pay");
        $('#add_here_new_pay').append(clone);

        add_new_base++;
        return false;
    });

    var add_new_cl = 1;
    $('#deleteperformance_0').hide();
    $('#add_performance_button').on( 'click', function () {
        $('#deleteperformance_0').show();
        var clone = $('.additional_performance').clone();
        clone.find('.form-control').val('');
        clone.find('.rate').val('0');
        clone.find('#category_0').attr('id', 'category_'+add_new_cl);
        clone.find('#service_0').attr('id', 'service_'+add_new_cl);

        clone.find('#performance_0').attr('id', 'performance_'+add_new_cl);
        clone.find('#deleteperformance_0').attr('id', 'deleteperformance_'+add_new_cl);
        $('#deleteperformance_0').hide();

        clone.removeClass("additional_performance");
        $('#add_here_new_performance').append(clone);

        add_new_cl++;
        return false;
    });
});
function changeCategory(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=compensation&category="+action,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#service_"+arr[1]).html(response);
		}
	});
}

function deletePay(sel, hide, blank) {
	var typeId = sel.id;
	var arr = typeId.split('_');

    $("#"+hide+arr[1]).hide();
    $("#"+blank+arr[1]).val('');
    return false;
}
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

    <a href='field_config_compensation.php'><button type="button" class="btn brand-btn mobile-block active_tab" >Pay Fields</button></a>
    <a href='field_config_compensation_holiday.php'><button type="button" class="btn brand-btn mobile-block" >Statutory Holiday</button></a>

    <?php
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='staff_performance_pay'"));
    $value_config = $get_field_config['value'];
    ?>

    <h2>Set Performance Pay Fields</h2>
    <div class="form-group clearfix">
        <label class="col-sm-5 text-center">Name</label>
        <label class="col-sm-1 text-center">Delete</label>
    </div>

    <?php
    if($value_config != '') {
        $staff_performance_pay = explode('*#*',$value_config);

        $total_count = mb_substr_count($value_config,'*#*');
        $id_loop = 500;
        for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
            if($staff_performance_pay[$eq_loop] != '') {
        ?>
            <div class="clearfix"></div>
            <div class="form-group clearfix" id="<?php echo 'base_'.$id_loop; ?>" >
                <div class="col-sm-5">
                    <select data-placeholder="Choose a Name..." id="<?php echo 'basename_'.$id_loop; ?>" name="staff_performance_pay_name[]" class="chosen-select-deselect1 form-control serviceid" width="380">
                        <option value=""></option>
                        <option <?php if(strtolower($staff_performance_pay[$eq_loop]) == strtolower('Arrival Rate %')) { echo "selected='selected'"; } ?> value="Arrival Rate %">Arrival Rate %</option>
                        <option <?php if(strtolower($staff_performance_pay[$eq_loop]) == strtolower('Average Visits to Discharge')) { echo "selected='selected'"; } ?> value="Average Visits to Discharge">Average Visits to Discharge</option>
                        <option <?php if(strtolower($staff_performance_pay[$eq_loop]) == strtolower('% Of Available Hours Scheduled')) { echo "selected='selected'"; } ?> value="% Of Available Hours Scheduled">% Of Available Hours Scheduled</option>
                        <option <?php if(strtolower($staff_performance_pay[$eq_loop]) == strtolower('# of New Clients')) { echo "selected='selected'"; } ?> value="# of New Clients"># of New Clients</option>
                        <option <?php if(strtolower($staff_performance_pay[$eq_loop]) == strtolower('# of Assessments')) { echo "selected='selected'"; } ?> value="# of Assessments"># of Assessments</option>
                        <option <?php if(strtolower($staff_performance_pay[$eq_loop]) == strtolower('Block Booking')) { echo "selected='selected'"; } ?> value="Block Booking">Block Booking</option>
                        <option <?php if(strtolower($staff_performance_pay[$eq_loop]) == strtolower('Testimonials Submitted')) { echo "selected='selected'"; } ?> value="Testimonials Submitted">Testimonials Submitted</option>
                        <option <?php if(strtolower($staff_performance_pay[$eq_loop]) == strtolower('Manual Therapy Intermediate Certification')) { echo "selected='selected'"; } ?> value="Manual Therapy Intermediate Certification">Manual Therapy Intermediate Certification</option>
                        <option <?php if(strtolower($staff_performance_pay[$eq_loop]) == strtolower('Manual Therapy Advanced Diploma Certification')) { echo "selected='selected'"; } ?> value="Manual Therapy Advanced Diploma Certification">Manual Therapy Advanced Diploma Certification</option>
                    </select>
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deletePay(this,'base_','basename_'); return false;" id="<?php echo 'deletebase_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
        <?php
        $id_loop++;
        } }
    }
    ?>

    <div class="additional_pay">
    <div class="clearfix"></div>
    <div class="form-group clearfix" width="100%" id="base_0">
        <div class="col-sm-5">
            <select data-placeholder="Choose a Name..." id="basename_0" name="staff_performance_pay_name[]" class="chosen-select-deselect1 form-control serviceid" width="380">
                <option value=""></option>
                <option value="Arrival Rate %">Arrival Rate %</option>
                <option value="Average Visits to Discharge">Average Visits to Discharge</option>
                <option value="% Of Available Hours Scheduled">% Of Available Hours Scheduled</option>
                <option value="# of New Clients"># of New Clients</option>
                <option value="# of Assessments"># of Assessments</option>
                <option value="Block Booking">Block Booking</option>
                <option value="Testimonials Submitted">Testimonials Submitted</option>
                <option value="Manual Therapy Intermediate Certification">Manual Therapy Intermediate Certification</option>
                <option value="Manual Therapy Advanced Diploma Certification">Manual Therapy Advanced Diploma Certification</option>
                <option value="All Services">All Services</option>
            </select>
        </div>
        <div class="col-sm-1" >
            <a href="#" id="sservice_0" onclick="deletePay(this,'base_','basename_'); return false;" id="deletebase_0" class="btn brand-btn">Delete</a>
        </div>
    </div>

    </div>

    <div id="add_here_new_pay"></div>

    <div class="col-sm-12 col-sm-offset-1 triple-gap-bottom">
        <button id="add_pay_button" class="btn brand-btn mobile-block">Add</button>
    </div>

    <h2>Set Base Pay Fields</h2>
    <?php $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='staff_base_pay'"));
    $value_config_base = $get_field_config['value']; ?>
	<div class="form-goup">
		<label class="col-sm-4 control-label">Base Pay Fields:</label>
		<div class="col-sm-8">
			<label class="form-checkbox"><input type="checkbox" <?php if (strpos(','.$value_config_base.',', ','."All Base Pay Services".',') !== FALSE) { echo " checked"; } ?> value="All Base Pay Services" style="height: 20px; width: 20px;" name="staff_base_pay[]"> All Base Pay Services</label>
			<label class="form-checkbox"><input type="checkbox" <?php if (strpos(','.$value_config_base.',', ','."All Base Pay Inventory".',') !== FALSE) { echo " checked"; } ?> value="All Base Pay Inventory" style="height: 20px; width: 20px;" name="staff_base_pay[]"> All Base Pay Inventory</label>
		</div>
	</div>

	<h2>Apply Compensation</h2>
	<div class="form-group">
		<label class="col-sm-4 control-label">Groups:</label>
		<div class="col-sm-8">
			<?php $staff_groups = explode(',',get_config($dbc, 'comp_staff_groups')); ?>
			<label class="form-checkbox"><input type="checkbox" name="staff_groups[]" <?= in_array('ALL',$staff_groups) ? 'checked' : '' ?> value="ALL"> Show All Staff</label>
			<?php foreach(explode(',',mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `categories` FROM `field_config_contacts` WHERE `categories` IS NOT NULL"))['categories']) as $group) { ?>
				<label class="form-checkbox"><input type="checkbox" name="staff_groups[]" <?= in_array($group,$staff_groups) ? 'checked' : '' ?> value="<?= $group ?>"> Show <?= $group ?> Staff</label>
			<?php } ?>
			<label class="form-checkbox"><input type="checkbox" name="staff_groups[]" <?= in_array('',$staff_groups) ? 'checked' : '' ?> value=""> Show Uncategorized Staff</label>
		</div>
	</div>
	
    <div class="form-group">
        <div class="col-sm-4 clearfix">
            <a href="compensation.php" class="btn config-btn pull-right">Back</a>
        </div>
        <div class="col-sm-8">
            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
        </div>
    </div>

        

</form>
</div>
</div>

<?php include ('../footer.php'); ?>