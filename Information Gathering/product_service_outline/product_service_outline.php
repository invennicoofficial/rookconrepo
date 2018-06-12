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
$business_6 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_product_service_outline WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
	$business_1 = $get_field_level['business_1'];
	$business_2 = $get_field_level['business_2'];
	$business_3 = $get_field_level['business_3'];
	$business_4 = $get_field_level['business_4'];
	$business_5 = $get_field_level['business_5'];
	$business_6 = $get_field_level['business_6'];
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
					What makes your company different?<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What makes your company different?</label>
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
					What makes you or your business valuable to your customers?<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What makes you or your business valuable to your customers?</label>
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
					Who are your customers?<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Who are your customers?</label>
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
					Top things your customers should say when they use your products or services<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Top things your customers should say when they use your products or services</label>
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
					What's your ongoing support plan?<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What's your ongoing support plan?</label>
            <div class="col-sm-8">
            <textarea name="business_5" rows="5" cols="50" class="form-control"><?php echo $business_5; ?></textarea>
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
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info7" >
					What calls to action have worked?<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info7" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What calls to action have worked?</label>
            <div class="col-sm-8">
            <textarea name="business_6" rows="5" cols="50" class="form-control"><?php echo $business_6; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>
	<?php } ?>

</div>