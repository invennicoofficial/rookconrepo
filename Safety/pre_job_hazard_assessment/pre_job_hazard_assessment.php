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
            clone.find('.form-control').val('');
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

$job_name = '';
$job_number = '';
$site_address = '';
$scope_of_work = '';
$all_task = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_pre_job_hazard_assessment WHERE fieldlevelriskid='$formid'"));

	$today_date = $get_field_level['today_date'];
    $job_name = $get_field_level['job_name'];
    $job_number = $get_field_level['job_number'];
    $site_address = $get_field_level['site_address'];
    $scope_of_work = $get_field_level['scope_of_work'];
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

			<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Date:</label>
                    <div class="col-sm-8">
                        <input type="text" name="today_date" value="<?php echo $today_date; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Job Name:</label>
                    <div class="col-sm-8">
                        <input type="text" name="job_name" value="<?php echo $job_name; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Job Number</label>
                    <div class="col-sm-8">
                        <input type="text" name="job_number" value="<?php echo $job_number; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

				<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Site Address:</label>
                    <div class="col-sm-8">
                        <input type="text" name="site_address" value="<?php echo $site_address; ?>" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

			</div>
        </div>
    </div>

	<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info2" >
                    Scope of Work<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Scope of Work:</label>
                    <div class="col-sm-8">
                      <textarea name="scope_of_work" rows="5" cols="50" class="form-control"><?php echo $scope_of_work; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info3" >
                    Potential Hazards<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

              <?php
                $all_task_each = explode('**##**',$all_task);

                $total_count = mb_substr_count($all_task,'**##**');
                if($total_count > 0) {
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                        <th>Hazard</th>
                        <th>Risk 1</th>
                        <th>Risk 2</th>
                    ";
                }
                for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                    $task_item = explode('**',$all_task_each[$client_loop]);
                    $hazard = $task_item[0];
                    $risk1 = $task_item[1];
                    $risk2 = $task_item[2];
                    if($hazard != '') {
                        echo '<tr>';
                        echo '<td data-title="Email">' . $hazard . '</td>';
                        echo '<td data-title="Email">' . $risk1 . '</td>';
                        echo '<td data-title="Email">' . $risk2 . '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
                ?>
                <div class="additional_hazard clearfix">
                    <div class="row">
                        <div class="col-sm-4">
                            <p>Hazard</p>
                            <input type="text" name="task[]" class="form-control"/>
                        </div>
                        <div class="col-sm-4">
                            <p>Risk 1</p>
                            <select name="hazard[]" class="form-control">
                                <option value=""></option>
                                <option value="Imminent Danger">Imminent Danger</option>
                                <option value="Serious">Serious</option>
                                <option value="Minor">Minor</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <p>Risk 2</p>
                            <select name="hazard_level[]" class="form-control">
                                <option value=""></option>
                                <option value="Probable">Probable</option>
                                <option value="Reasonably Probable">Reasonably Probable</option>
                                <option value="Remote">Remote</option>
                                <option value="Extremely Remote">Extremely Remote</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="add_here_new_hazard"></div>
                <div class="form-group triple-gapped clearfix">
                    <div class="col-sm-offset-4 col-sm-8">
                        <button id="add_row_hazard" class="btn brand-btn pull-left">Add Hazard</button>
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
