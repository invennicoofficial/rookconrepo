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
$today_date = '';
$business_1 = '';
$business_2 = '';
$business_3 = '';
$business_4 = '';
$business_5 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_swot WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $business = $get_field_level['business'];
	$business_1 = $get_field_level['business_1'];
	$business_2 = $get_field_level['business_2'];
	$business_3 = $get_field_level['business_3'];
	$business_4 = $get_field_level['business_4'];
	$business_5 = $get_field_level['business_5'];
}
	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_infogathering WHERE form='$form'"));
	$form_config = ','.$get_field_config['fields'].',';
	?>

<div class="panel-group" id="accordion">

    <?php if (strpos(','.$form_config.',', ',fields1,') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info2" >
					Problem/Issue<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Problem/Issue</label>
            <div class="col-sm-8">
            <textarea name="business_1" rows="5" cols="50" class="form-control"><?php echo $business_1; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>
	<?php } ?>

    <?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info3" >
					Strengths<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Strengths</label>
            <div class="col-sm-8">
            <textarea name="business_2" rows="5" cols="50" class="form-control"><?php echo $business_2; ?></textarea>
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
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info4" >
					Weaknesses<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Weaknesses</label>
            <div class="col-sm-8">
            <textarea name="business_3" rows="5" cols="50" class="form-control"><?php echo $business_3; ?></textarea>
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
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info5" >
					Opportunities<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Opportunities</label>
            <div class="col-sm-8">
            <textarea name="business_4" rows="5" cols="50" class="form-control"><?php echo $business_4; ?></textarea>
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
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info6" >
					Next Steps/Action Plan<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Next Steps/Action Plan</label>
            <div class="col-sm-8">
            <textarea name="business_5" rows="5" cols="50" class="form-control"><?php echo $business_5; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>
	<?php } ?>

</div>