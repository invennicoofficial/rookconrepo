<?php
/*
Add	Sheet
*/
include_once ('../database_connection.php');
error_reporting(0);
?>
</style>

<script type="text/javascript">
	$(document).ready(function(){
        $("#form1").submit(function( event ) {
            var jobid = $("#jobid").val();
            var contactid = $("input[name=contactid]").val();
            var job_location = $("input[name=location]").val();
            if (contactid == '' || job_location == '') {
                //alert("Please make sure you have filled in all of the required fields.");
                //return false;
            }
        });
		var inc = 1;
        $('#add_row_hazard').on( 'click', function () {
            $(".hide_show_service").show();
            var clone = $('.additional_hazard').clone();
            clone.find('.task_list').val('');
            clone.removeClass("additional_hazard");
            $('#add_here_new_hazard').append(clone);
            inc++;
            return false;
        });
    });
</script>
</head>
<body>

<?php
$today_date = date('Y-m-d');
$contactid = $_SESSION['contactid'];
$fields = '';
$all_task = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_disclosure_of_outside_clients WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$all_task = $get_field_level['all_task'];
    $fields = explode('**FFM**', $get_field_level['fields']);
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<h3>Information</h3>
<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Employee Name:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Effective Date of Hire:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="datepicker" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
    <h3>Client and Project List</h3>
	<?php
	$all_task_each = explode('**##**',$all_task);

	$total_count = mb_substr_count($all_task,'**##**');
	if($total_count > 0) {
		echo "<table class='table table-bordered'>";
		echo "<tr class='hidden-xs hidden-sm'>
		<th>Client</th>
		<th>Project</th>";
	}
	for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
		$task_item = explode('**',$all_task_each[$client_loop]);
		$task = $task_item[0];
		$hazard = $task_item[1];
		if($task != '') {
			echo '<tr>';
			echo '<td data-title="Email">' . $task . '</td>';
			echo '<td data-title="Email">' . $hazard . '</td>';
			echo '</tr>';
		}
	}
	echo '</table>';
	?>
	<div class="additional_hazard clearfix">
		<div class="row">
			<div class="col-md-2 col-sm-6 col-xs-6 padded">
				<p>Client</p>
				<input type="text" name="task[]" class="task_list"/>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-6 padded">
				<p>Project</p>
				<input type="text" name="hazard[]" class="task_list"/>
			</div>
		</div>
	</div>
	<div id="add_here_new_hazard"></div>
	<div class="form-group triple-gapped clearfix">
		<div class="col-sm-offset-4 col-sm-8">
			<button id="add_row_hazard" class="btn brand-btn pull-left">Add More</button>
		</div>
	</div>
<?php } ?>

<h3>Validation</h3>
<h4><input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
</h4>
<?php include ('../phpsign/sign.php'); ?>