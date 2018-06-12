<?php
/*
Add	Sheet
*/
include ('../database_connection.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);
?>
<style>
.form-control {
    width: 40%;
    display: inline;
}
</style>
</head>
<body>

<?php
$today_date = date('Y-m-d');
$business_name = '';
$business_services = '';
$business_products = '';
$business_vision = '';
$business_goals = '';
$target_markets = '';
$competitors = '';
$current_areas_of_concern = '';
$estimated_project_timeline_budget = '';
$communication_expectations_methods = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_client_business_introduction WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
	$business_name = $get_field_level['business_name'];
	$business_services = $get_field_level['business_services'];
	$business_products = $get_field_level['business_products'];
	$business_vision = $get_field_level['business_vision'];
	$business_goals = $get_field_level['business_goals'];
	$target_markets = $get_field_level['target_markets'];
	$competitors = $get_field_level['competitors'];
	$current_areas_of_concern = $get_field_level['current_areas_of_concern'];
	$estimated_project_timeline_budget = $get_field_level['estimated_project_timeline_budget'];
	$communication_expectations_methods = $get_field_level['communication_expectations_methods'];
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_infogathering WHERE form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>
<div class="panel-group" id="accordion">

	<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info2" >
                    Business Name<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Business Name:</label>
                    <div class="col-sm-8">
                      <textarea name="business_name" rows="5" cols="50" class="form-control"><?php echo $business_name; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info3" >
                    Business Services<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Business Services:</label>
                    <div class="col-sm-8">
                      <textarea name="business_services" rows="5" cols="50" class="form-control"><?php echo $business_services; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info4" >
                    Business Products<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Business Products:</label>
                    <div class="col-sm-8">
                      <textarea name="business_products" rows="5" cols="50" class="form-control"><?php echo $business_products; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info5" >
                    Business Vision<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Business Vision:</label>
                    <div class="col-sm-8">
                      <textarea name="business_vision" rows="5" cols="50" class="form-control"><?php echo $business_vision; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info6" >
                    Business Goals<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Business Goals:</label>
                    <div class="col-sm-8">
                      <textarea name="business_goals" rows="5" cols="50" class="form-control"><?php echo $business_goals; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info7" >
                    Target Markets<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info7" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Target Markets:</label>
                    <div class="col-sm-8">
                      <textarea name="target_markets" rows="5" cols="50" class="form-control"><?php echo $target_markets; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info8" >
                    Competitors<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info8" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Competitors:</label>
                    <div class="col-sm-8">
                      <textarea name="competitors" rows="5" cols="50" class="form-control"><?php echo $competitors; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info9" >
                    Current Areas of Concern<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info9" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Current Areas of Concern:</label>
                    <div class="col-sm-8">
                      <textarea name="current_areas_of_concern" rows="5" cols="50" class="form-control"><?php echo $current_areas_of_concern; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info10" >
                    Estimated Project Timeline & Budget<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info10" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Estimated Project Timeline & Budget:</label>
                    <div class="col-sm-8">
                      <textarea name="estimated_project_timeline_budget" rows="5" cols="50" class="form-control"><?php echo $estimated_project_timeline_budget; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
	<?php } ?>

	<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
    <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info11" >
                    Communication Expectations & Methods<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info11" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Communication Expectations & Methods:</label>
                    <div class="col-sm-8">
                      <textarea name="communication_expectations_methods" rows="5" cols="50" class="form-control"><?php echo $communication_expectations_methods; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
	<?php } ?>

</div>