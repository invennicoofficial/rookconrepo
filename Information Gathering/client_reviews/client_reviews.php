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

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_client_reviews WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $business = $get_field_level['business'];
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

    <div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info1" >
					Business<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

            <div class="form-group">
              <label for="site_name" class="col-sm-4 control-label">Business Name<span class="text-red">*</span>:</label>
              <div class="col-sm-8">
                <select data-placeholder="Choose a Business..." name="business" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
					<?php $businesses = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `name` FROM `contacts` WHERE `category`='Business' AND `deleted`=0 AND IFNULL(`status`,1)>0"),MYSQLI_ASSOC));
					foreach($businesses as $businessid) {
						$row_name = get_client($dbc, $businessid);
						echo '<option'.($business == $row_name ? ' selected' : '').' value="'.$row_name.'">'.$row_name."</option>\n";
					} ?>
                </select>
              </div>
            </div>

			</div>
        </div>
    </div>

    <?php if (strpos(','.$form_config.',', ',fields1,') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info2" >
					What's working<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What's working</label>
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
					What's not working<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What's not working</label>
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
					What do we need to do better<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What do we need to do better</label>
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
					What's the Next Steps<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What's the Next Steps</label>
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
					Next Review<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">

            <div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Next Review Date:</label>
                <div class="col-sm-8">
                    <input name="business_5" value="<?php echo $business_5; ?>" type="text" class="datepicker">
                </div>
            </div>

            <div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Reminder Date:</label>
                <div class="col-sm-8">
                    <input name="business_6" value="<?php echo $business_6; ?>" type="text" class="datepicker">
                </div>
            </div>

			</div>
        </div>
    </div>
	<?php } ?>

</div>