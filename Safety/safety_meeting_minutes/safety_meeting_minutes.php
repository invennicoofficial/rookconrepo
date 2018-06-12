<?php
/*
Add	Sheet
*/
include ('../database_connection.php');
include_once('../tcpdf/tcpdf.php');
require_once('../phpsign/signature-to-image.php');
error_reporting(0);
?>
<style>
.form-control {
    width: 40%;
    display: inline;
}
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
$absent = '';
$follow_up_action = '';
$corrective_actions = '';
$vehicle_logs = '';
$vehicle_update = '';
$training = '';
$driving = '';
$safety_concerns = '';
$discussion_items = '';
$fields = '';
$all_task = '';
$desc = '';
$desc1 = '';
$desc2 = '';
$desc3 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_safety_meeting_minutes WHERE fieldlevelriskid='$formid'"));

    $today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $absent = $get_field_level['absent'];
    $follow_up_action = $get_field_level['follow_up_action'];
    $corrective_actions = $get_field_level['corrective_actions'];
    $vehicle_logs = $get_field_level['vehicle_logs'];
    $vehicle_update = $get_field_level['vehicle_update'];
    $training = $get_field_level['training'];
    $driving = $get_field_level['driving'];
    $safety_concerns = $get_field_level['safety_concerns'];
    $discussion_items = $get_field_level['discussion_items'];
    $fields = explode('**FFM**', $get_field_level['fields']);
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
	$desc3 = $get_field_level['desc3'];
	$all_task = $get_field_level['all_task'];
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

    <div class="panel-group" id="accordion2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
                    Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

		<div id="collapse_info" class="panel-collapse collapse">
			<div class="panel-body">

			<?php if (strpos($form_config, ','."fields0".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Contact ID:</label>
				<div class="col-sm-8">
				<input type="text" name="contactid" value="<?php echo $contactid; ?>" class="form-control" />
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Date:</label>
				<div class="col-sm-8">
				<input type="text" name="today_date" value="<?php echo $today_date; ?>" class="form-control" />
				</div>
				</div>
			<?php } ?>

            <?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Location</label>
                <div class="col-sm-8">
                <input type="text" name="fields_0" value="<?php echo $fields[0]; ?>" class="form-control" />
                </div>
                </div>
            <?php } ?>

            <?php if (strpos($form_config, ','."fields12".',') !== FALSE) { ?>
                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Meeting Leader</label>
                <div class="col-sm-8">
                <input type="text" name="fields_1" value="<?php echo $fields[1]; ?>" class="form-control" />
                </div>
                </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Absent:</label>
				<div class="col-sm-8">
				<input type="text" name="absent" value="<?php echo $absent; ?>" class="form-control" />
				</div>
				</div>
			<?php } ?>

			</div>
        </div>
    </div>

    <?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info100" >
                    Introduction Of Guests / New Personnel<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info100" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Introduction Of Guests / New Personnel:</label>
                    <div class="col-sm-8">
                      <textarea name="desc" rows="5" cols="50" class="form-control"><?php echo $desc; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info101" >
                    Review Minutes<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info101" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Review Minutes:</label>
				<div class="col-sm-8">
                <input type="text" name="fields_2" value="<?php echo $fields[2]; ?>" class="form-control" />
				</div>
				</div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info102" >
                    Incident Review<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info102" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Incident Review:</label>
                    <div class="col-sm-8">
                      <textarea name="desc1" rows="5" cols="50" class="form-control"><?php echo $desc1; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info103" >
                    Standards, Policies & Procedures<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info103" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Standards, Policies & Procedures:</label>
                    <div class="col-sm-8">
                      <textarea name="desc2" rows="5" cols="50" class="form-control"><?php echo $desc2; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info104" >
                    New Business<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info104" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">New Business:</label>
                    <div class="col-sm-8">
                      <textarea name="desc3" rows="5" cols="50" class="form-control"><?php echo $desc3; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info105" >
                    Action Items<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info105" class="panel-collapse collapse">
            <div class="panel-body">

                <?php
                $all_task_each = explode('**##**',$all_task);

                $total_count = mb_substr_count($all_task,'**##**');
                if($total_count > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                    <th>Item Description</th>
                    <th>Assigned To</th>
                    <th>Completion</th></tr>";
                }
                for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                    $task_item = explode('**',$all_task_each[$client_loop]);
                    $task = $task_item[0];
                    $hazard = $task_item[1];
					$hazard1 = $task_item[2];

                    if($task != '') {
                        echo '<tr>';
                        echo '<td data-title="Email">' . $task . '</td>';
                        echo '<td data-title="Email">' . $hazard . '</td>';
                        echo '<td data-title="Email">' . $hazard1. '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
                ?>
                <div class="additional_hazard clearfix">
                    <div class="row">
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Item Description</p>
                            <input type="text" name="task[]" class="task_list"/>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-6 padded">
                            <p>Assigned To</p>
                            <input type="text" name="hazard[]" class="task_list"/>
                        </div>
                        <div class="col-md-5 col-sm-6 col-xs-6 padded">
                            <p>Completion</p>
                            <input type="text" name="hazard1[]" class="task_list"/>
                        </div>
                    </div>
                </div>
                <div id="add_here_new_hazard"></div>
                <div class="form-group triple-gapped clearfix">
                    <div class="col-sm-offset-4 col-sm-8">
                        <button id="add_row_hazard" class="btn brand-btn pull-left">Add More</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info106" >
                    Meeting Adjourned<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info106" class="panel-collapse collapse">
            <div class="panel-body">

				<div class="form-group">
				<label for="business_street" class="col-sm-4 control-label">Meeting Adjourned:</label>
				<div class="col-sm-8">
                <input type="text" name="fields_3" value="<?php echo $fields[3] ?>" class="form-control" />
				</div>
				</div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info1" >
                    Follow Up Action Items<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Follow Up Action Items:</label>
                    <div class="col-sm-8">
                      <textarea name="follow_up_action" rows="5" cols="50" class="form-control"><?php echo $follow_up_action; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info2" >
                    Corrective Actions<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Corrective Actions:</label>
                    <div class="col-sm-8">
                      <textarea name="corrective_actions" rows="5" cols="50" class="form-control"><?php echo $corrective_actions; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info3" >
                    Vehicle Logs<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Vehicle Logs:</label>
                    <div class="col-sm-8">
                      <textarea name="vehicle_logs" rows="5" cols="50" class="form-control"><?php echo $vehicle_logs; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info4" >
                    Vehicle Update<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Vehicle Update:</label>
                    <div class="col-sm-8">
                      <textarea name="vehicle_update" rows="5" cols="50" class="form-control"><?php echo $vehicle_update; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info5" >
                    Training<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Training:</label>
                    <div class="col-sm-8">
                      <textarea name="training" rows="5" cols="50" class="form-control"><?php echo $training; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info6" >
                    Driving<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Driving:</label>
                    <div class="col-sm-8">
                      <textarea name="driving" rows="5" cols="50" class="form-control"><?php echo $driving; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info7" >
                    Safety Concerns<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info7" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Safety Concerns:</label>
                    <div class="col-sm-8">
                      <textarea name="safety_concerns" rows="5" cols="50" class="form-control"><?php echo $safety_concerns; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info8" >
                    Discussion Items<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info8" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Discussion Items:</label>
                    <div class="col-sm-8">
                      <textarea name="discussion_items" rows="5" cols="50" class="form-control"><?php echo $discussion_items; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
    <?php } ?>

    <?php if(!empty($_GET['formid'])) {
    $sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$formid' AND safetyid='$safetyid'");
    $sa_inc=  0;
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_sa = $row_sa['assign_staff'];
        $assign_staff_id = $row_sa['safetyattid'];
        $assign_staff_done = $row_sa['done'];
        ?>
    <div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sa<?php echo $sa_inc;?>" >
                <?php echo $assign_staff_sa; ?><span class="glyphicon glyphicon-plus"></span>
            </a>
        </h4>
    </div>

    <div id="collapse_sa<?php echo $sa_inc;?>" class="panel-collapse collapse">
        <div class="panel-body">

            <?php
            if($assign_staff_done == 0) { ?>
            <?php include ('../phpsign/sign3.php');
            ?>

            <?php if (strpos($assign_staff_sa, 'Extra') !== false) { ?>
               <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Name:</label>
                <div class="col-sm-8">
                    <input name="assign_staff_<?php echo $assign_staff_id;?>" type="text" class="form-control" />
                </div>
              </div>
            <?php } ?>

            <div class="sigPad" id="linear2" style="width:404px;">
            <ul class="sigNav">
            <li class="drawIt"><a href="#draw-it" >Draw It</a></li>
            <li class="clearButton"><a href="#clear">Clear</a></li>
            </ul>
            <div class="sig sigWrapper" style="height:auto;">
            <div class="typed"></div>
            <canvas class="pad" width="400" height="150" style="border:2px solid black;"></canvas>
            <input type="hidden" name="sign_<?php echo $assign_staff_id;?>" class="output">
            </div>
            </div>

            <?php } ?>

        </div>
    </div>
</div>
<?php $sa_inc++;
    }
} ?>

</div>