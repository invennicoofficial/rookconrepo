<?php
/*
Survey : Custom form builder for survey and send to patient after treatment.
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

if (isset($_POST['add_survey'])) {
    $field_set1 =	$_POST['field_set1'];
    $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);

	if($_POST['new_service'] != '') {
		$service = filter_var($_POST['new_service'],FILTER_SANITIZE_STRING);
	} else {
		$service = filter_var($_POST['service'],FILTER_SANITIZE_STRING);
	}

    $id1 = filter_var($_POST['id1'],FILTER_SANITIZE_STRING);
    $question1 =	filter_var($_POST['question1'],FILTER_SANITIZE_STRING);
    $o11 = implode('*#*',$_POST['option1']);
    $option1 = filter_var($o11,FILTER_SANITIZE_STRING);

    $field_set2 =	$_POST['field_set2'];
    $id2 = filter_var($_POST['id2'],FILTER_SANITIZE_STRING);
    $question2 =	filter_var($_POST['question2'],FILTER_SANITIZE_STRING);
    $o12 = implode('*#*',$_POST['option2']);
    $option2 = filter_var($o12,FILTER_SANITIZE_STRING);

    $field_set3 =	$_POST['field_set3'];
    $id3 = filter_var($_POST['id3'],FILTER_SANITIZE_STRING);
    $question3 =	filter_var($_POST['question3'],FILTER_SANITIZE_STRING);
    $o13 = implode('*#*',$_POST['option3']);
    $option3 = filter_var($o13,FILTER_SANITIZE_STRING);

    $field_set4 =	$_POST['field_set4'];
    $id4 = filter_var($_POST['id4'],FILTER_SANITIZE_STRING);
    $question4 =	filter_var($_POST['question4'],FILTER_SANITIZE_STRING);
    $o14 = implode('*#*',$_POST['option4']);
    $option4 = filter_var($o14,FILTER_SANITIZE_STRING);

    $field_set5 =	$_POST['field_set5'];
    $id5 = filter_var($_POST['id5'],FILTER_SANITIZE_STRING);
    $question5 =	filter_var($_POST['question5'],FILTER_SANITIZE_STRING);
    $o15 = implode('*#*',$_POST['option5']);
    $option5 = filter_var($o15,FILTER_SANITIZE_STRING);

    $referral_request = $_POST['referral_request'];
    $testimonial_request = $_POST['testimonial_request'];

    $query_insert_inventory = "INSERT INTO `crm_feedback_survey_form` (`name`, `service`, `field_set1`, `id1`, `question1`, `option1`, `field_set2`, `id2`, `question2`, `option2`, `field_set3`, `id3`, `question3`, `option3`, `field_set4`, `id4`, `question4`, `option4`, `field_set5`, `id5`, `question5`, `option5`, `referral_request`, `testimonial_request`) VALUES	('$name', '$service', '$field_set1', '$id1', '$question1', '$option1',	'$field_set2', '$id2', '$question2', '$option2', '$field_set3', '$id3', '$question3', '$option3', '$field_set4', '$id4', '$question4', '$option4', '$field_set5', '$id5', '$question5', '$option5', '$referral_request', '$testimonial_request')";
    $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);

    echo '<script type="text/javascript"> window.location.replace("survey.php"); </script>';

   // mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function () {
    var i;
    for(i=1;i<=5;i++) {
        $('.option_hideshow'+i).hide();
        $('#add_row_option'+i).on( 'click', function () {
            var id = this.id;
            var lastChar = id.substr(id.length - 1);
            $(".hide_show_option"+lastChar).show();
            var clone = $('.additional_option'+lastChar).clone();
            clone.find('.form-control').val('');
            clone.removeClass("additional_option"+lastChar);
            $('#add_here_new_option'+lastChar).append(clone);
            return false;
        });
    }

    $("#service").change(function() {
        if($("#service option:selected").text() == 'New Service') {
                $( "#new_service" ).show();
        } else {
            $( "#new_service" ).hide();
        }
    });

});

$(document).on('change.select2', 'select.field_set', function() { selectField(this); });

function selectField(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
    if(status == 'Dropdown' || status == 'Options' || status == 'Checkbox' || status == 'Scale') {
        $('.option_hideshow'+arr[1]).show();
    }
    if(status == 'Scale') {
        $(".option_value"+arr[1]).html('Start-End Scale<br><em>(Ex:1-5 or 1-10)</e>');
        $("#add_row_option"+arr[1]).hide();
    } else {
        $(".option_value"+arr[1]).html('Option');
    }
}
</script>
</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">

		<h1	class="gap-bottom">Add A New Survey Form</h1>

		<div class="double-gap-bottom"><a href="survey.php" class="btn config-btn">Back To Dashbaord</a></div>

		<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_name" >
                            Name & Service<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_name" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">Name:</label>
                            <div class="col-sm-8">
                                <input name="name" type="text" class="form-control">
                            </div>
                        </div>

                      <div class="form-group">
                        <label for="position[]" class="col-sm-4 control-label">Service:</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Select a Service..." id="service" name="service" class="chosen-select-deselect form-control" width="380">
                              <option value=""></option>
                              <?php
                                $query = mysqli_query($dbc,"SELECT distinct(service) FROM crm_feedback_survey_form");
                                while($row = mysqli_fetch_array($query)) {
                                    echo "<option value='". $row['service']."'>".$row['service'].'</option>';
                                }
                                echo "<option value = 'Other'>New Service</option>";
                              ?>
                            </select>
                        </div>
                      </div>

                       <div class="form-group" id="new_service" style="display: none;">
                        <label for="travel_task" class="col-sm-4 control-label">New Service:</label>
                        <div class="col-sm-8">
                            <input name="new_service" type="text" class="form-control"/>
                        </div>
                      </div>


                    </div>
                </div>
            </div>

            <?php for($i=1;$i<=5;$i++) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_q<?php echo $i;?>" >
                            Question <?php echo $i;?><span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_q<?php echo $i;?>" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="first_name" class="col-sm-4 control-label text-right">Field Set:</label>
                            <div class="col-sm-8">
                                <select name="field_set<?php echo $i;?>" data-placeholder="Select a Field Set..." id="field_<?php echo $i; ?>" class="chosen-select-deselect form-control field_set" width="380">
                                    <option value=''></option>
                                    <option value='Textbox'>Textbox</option>
                                    <option value='Dropdown'>Dropdown</option>
                                    <option value='Datepicker'>Datepicker</option>
                                    <option value='Scale'>Scale</option>
                                    <option value='Textarea'>Text Area</option>
                                    <option value='Options'>Options</option>
                                    <option value='Checkbox'>Checkbox</option>
                                </select>
                            </div>
                        </div>

                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">ID:</label>
                        <div class="col-sm-8">
                          <input name="id<?php echo $i;?>" readonly value="field<?php echo $i;?>" type="text" class="form-control">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Question:</label>
                        <div class="col-sm-8">
                          <input name="question<?php echo $i;?>" type="text" class="form-control">
                        </div>
                      </div>

                      <span class="option_hideshow<?php echo $i;?>">
                      <div class="form-group additional_option<?php echo $i;?>">
                        <label for="company_name" class="col-sm-4 control-label option_value<?php echo $i;?>">Option:</label>
                        <div class="col-sm-8">
                          <input name="option<?php echo $i;?>[]" type="text" class="form-control">
                        </div>
                      </div>

                        <div id="add_here_new_option<?php echo $i;?>"></div>

                        <div class="form-group triple-gapped clearfix">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button id="add_row_option<?php echo $i;?>" class="btn brand-btn pull-left">Add Option</button>
                            </div>
                        </div>
                        </span>

                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_rr" >
                            Referral Request<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_rr" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">Referral Request:</label>
                            <div class="col-sm-8">
                              <input type="radio" checked="checked" name="referral_request" value="Yes"> Yes &nbsp;&nbsp;
                              <input type="radio" name="referral_request" value="No"> No
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_tr" >
                            Testimonial Request<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_tr" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="company_name" class="col-sm-4 control-label">Testimonial Request:</label>
                            <div class="col-sm-8">
                              <input type="radio" checked="checked" name="testimonial_request" value="Yes"> Yes &nbsp;&nbsp;
                              <input type="radio" name="testimonial_request" value="No"> No
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="form-group">
            <div class="col-sm-6 clearfix">
                <a href="survey.php" class="btn brand-btn btn-lg">Back</a>
            </div>
          <div class="col-sm-6">
            <button type="submit" name="add_survey" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
		  </div>
        </div>

		</form>

	</div>
  </div>

<?php include ('../footer.php'); ?>