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
	$msr1 = '';
	$msr2 = '';
	$msr3 = '';
	$msr4 = '';
	$msr5 = '';
	$msr6 = '';
	$msr7 = '';
	$msr8 = '';
	$msr9 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_marketing_strategies_review WHERE fieldlevelriskid='$formid'"));

	$today_date = $get_field_level['today_date'];
    $business = $get_field_level['business'];

	$msr1 = $get_field_level['msr1'];
	$msr2 = $get_field_level['msr2'];
	$msr3 = $get_field_level['msr3'];
	$msr4 = $get_field_level['msr4'];
	$msr5 = $get_field_level['msr5'];
	$msr6 = $get_field_level['msr6'];
	$msr7 = $get_field_level['msr7'];
	$msr8 = $get_field_level['msr8'];
	$msr9 = $get_field_level['msr9'];
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

    <?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
	<div class="panel panel-default">
	    <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info2" >
                    Competitor Analysis<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Pros/Cons</label>
                    <div class="col-sm-8">
                      <textarea name="msr1" rows="5" cols="50" class="form-control"><?php echo $msr1; ?></textarea>
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
                    SWOT<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Strength</label>
                    <div class="col-sm-8">
                      <textarea name="msr2" rows="5" cols="50" class="form-control"><?php echo $msr2; ?></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Weakness</label>
                    <div class="col-sm-8">
                      <textarea name="msr3" rows="5" cols="50" class="form-control"><?php echo $msr3; ?></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Opportunities</label>
                    <div class="col-sm-8">
                      <textarea name="msr4" rows="5" cols="50" class="form-control"><?php echo $msr4; ?></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Threats</label>
                    <div class="col-sm-8">
                      <textarea name="msr5" rows="5" cols="50" class="form-control"><?php echo $msr5; ?></textarea>
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
                    Unique Value Propositions<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Unique Value Propositions</label>
                    <div class="col-sm-8">
                      <textarea name="msr6" rows="5" cols="50" class="form-control"><?php echo $msr6; ?></textarea>
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
                    Process<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Product/Service Sales Process</label>
                    <div class="col-sm-8">
                      <textarea name="msr7" rows="5" cols="50" class="form-control"><?php echo $msr7; ?></textarea>
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
                    Call to Action<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">How is this used, Pros, Cons</label>
                    <div class="col-sm-8">
                      <textarea name="msr8" rows="5" cols="50" class="form-control"><?php echo $msr8; ?></textarea>
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
                    Keywords<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

		<div id="collapse_info7" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Industry Keywords</label>
                    <div class="col-sm-8">
                      <textarea name="msr9" rows="5" cols="50" class="form-control"><?php echo $msr9; ?></textarea>
                    </div>
                  </div>

			</div>
        </div>
    </div>
    <?php } ?>

</div>