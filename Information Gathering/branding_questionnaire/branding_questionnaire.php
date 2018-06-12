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
$client_name = '';
$phone_number = '';
$client_email = '';
$gen_info_1 = '';
$gen_info_2 = '';
$gen_info_3 = '';
$gen_info_4 = '';
$gen_info_5 = '';
$gen_info_6 = '';
$gen_info_7 = '';
$your_market_1 = '';
$your_market_2 = '';
$your_market_3 = '';
$your_market_4 = '';
$your_market_5 = '';
$your_market_6 = '';
$your_market_7 = '';
$identity_brand_1 = '';
$identity_brand_2 = '';
$identity_brand_3 = '';
$identity_brand_4 = '';
$identity_brand_5 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_branding_questionnaire WHERE fieldlevelriskid='$formid'"));

	$today_date = $get_field_level['today_date'];
	$client_name = $get_field_level['client_name'];
	$phone_number = $get_field_level['phone_number'];
	$client_email = $get_field_level['client_email'];
	$gen_info_1 = $get_field_level['gen_info_1'];
	$gen_info_2 = $get_field_level['gen_info_2'];
	$gen_info_3 = $get_field_level['gen_info_3'];
	$gen_info_4 = $get_field_level['gen_info_4'];
	$gen_info_5 = $get_field_level['gen_info_5'];
	$gen_info_6 = $get_field_level['gen_info_6'];
	$gen_info_7 = $get_field_level['gen_info_7'];
	$your_market_1 = $get_field_level['your_market_1'];
	$your_market_2 = $get_field_level['your_market_2'];
	$your_market_3 = $get_field_level['your_market_3'];
	$your_market_4 = $get_field_level['your_market_4'];
	$your_market_5 = $get_field_level['your_market_5'];
	$your_market_6 = $get_field_level['your_market_6'];
	$your_market_7 = $get_field_level['your_market_7'];
	$identity_brand_1 = $get_field_level['identity_brand_1'];
	$identity_brand_2 = $get_field_level['identity_brand_2'];
	$identity_brand_3 = $get_field_level['identity_brand_3'];
	$identity_brand_4 = $get_field_level['identity_brand_4'];
	$identity_brand_5 = $get_field_level['identity_brand_5'];
}
	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_infogathering WHERE form='$form'"));
	$form_config = ','.$get_field_config['fields'].',';
	?>

<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info1" >
                    Client Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Business:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Business..." name="client_name" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
					<?php $businesses = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `name` FROM `contacts` WHERE `category`='Business' AND `deleted`=0 AND IFNULL(`status`,1)>0"),MYSQLI_ASSOC));
					foreach($businesses as $businessid) {
						$row_name = get_client($dbc, $businessid);
						echo '<option'.($client_name == $row_name ? ' selected' : '').' value="'.$row_name.'">'.$row_name."</option>\n";
					} ?>
                </select>
            </div>
            </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Phone Number:</label>
            <div class="col-sm-8">
            <input type="text" name="phone_number" value="<?php echo $phone_number; ?>" class="form-control" />
            </div>
            </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Client Email:</label>
            <div class="col-sm-8">
            <input type="text" name="client_email" value="<?php echo $client_email; ?>" class="form-control" />
            </div>
            </div>
            <?php } ?>

			</div>
        </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info2" >
                    Detail<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

			<P> It is important for our creative team to have as much information as possible and an in depth understanding of your company to have the basis for creating a great logo and striking identity to strengthen your brand. A good brand communicates a clear message about your company; what  is  stands  for  and  how  it  differs  from  competitors.  The  logo  is  the  visual  marker  of  the brand, and needs to instantly reflect the same information. </p>

			<P>A great logo design begins with insightful information about your company. Please answer the questions below  and  feel  free  to  add  additional  information  that  you  feel  is pertinent  to  your company. The more thorough you can be now, the more creative and effective the results.</P>

			</div>
        </div>
    </div>


    <div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info3" >
					General Information<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What products/services does your business provide?</label>
            <div class="col-sm-8">
            <textarea name="gen_info_1" rows="5" cols="50" class="form-control"><?php echo $gen_info_1; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Please describe your business in one sentence.</label>
            <div class="col-sm-8">
            <textarea name="gen_info_2" rows="5" cols="50" class="form-control"><?php echo $gen_info_2; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What  taglines/slogans/keywords  are  associated  with  your  business?  What  would  you like those keywords to be (if different than what they currently are)?</label>
            <div class="col-sm-8">
            <textarea name="gen_info_3" rows="5" cols="50" class="form-control"><?php echo $gen_info_3; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">How did your company get its start? Is there a unique story?</label>
            <div class="col-sm-8">
            <textarea name="gen_info_4" rows="5" cols="50" class="form-control"><?php echo $gen_info_4; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What shapes/symbols represent your industry/company? Do you feel that any of these are overused?</label>
            <div class="col-sm-8">
            <textarea name="gen_info_5" rows="5" cols="50" class="form-control"><?php echo $gen_info_5; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What would a typical customer Google to find your business?</label>
            <div class="col-sm-8">
            <textarea name="gen_info_6" rows="5" cols="50" class="form-control"><?php echo $gen_info_6; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Will  the  new  brand  we  are  creating  be  in  any  way  connected  to  another  brand  or business?</label>
            <div class="col-sm-8">
            <textarea name="gen_info_7" rows="5" cols="50" class="form-control"><?php echo $gen_info_7; ?></textarea>
            </div>
            </div>
			<?php } ?>

            </div>
        </div>
    </div>


	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info4" >
					Your Market<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Who would you consider your ideal customer to be? Why?</label>
            <div class="col-sm-8">
            <textarea name="your_market_1" rows="5" cols="50" class="form-control"><?php echo $remarks; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">How does the market see your company today? How would you like it to be viewed in the future?</label>
            <div class="col-sm-8">
            <textarea name="your_market_2" rows="5" cols="50" class="form-control"><?php echo $your_market_2; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What aspects of your image need improvement?</label>
            <div class="col-sm-8">
            <textarea name="your_market_3" rows="5" cols="50" class="form-control"><?php echo $your_market_3; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields15,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Who are your main competitors?</label>
            <div class="col-sm-8">
            <textarea name="your_market_4" rows="5" cols="50" class="form-control"><?php echo $your_market_4; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields16,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">How are their products/services better or worse?</label>
            <div class="col-sm-8">
            <textarea name="your_market_5" rows="5" cols="50" class="form-control"><?php echo $your_market_5; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields17,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What competitive edge does your business have?</label>
            <div class="col-sm-8">
            <textarea name="your_market_6" rows="5" cols="50" class="form-control"><?php echo $your_market_6; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields18,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">How have you been generating business recently? What has worked best? What hasn't? Any idea why?</label>
            <div class="col-sm-8">
            <textarea name="your_market_7" rows="5" cols="50" class="form-control"><?php echo $your_market_7; ?></textarea>
            </div>
            </div>
			<?php } ?>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info5" >
					Identity & Brand<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos(','.$form_config.',', ',fields19,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Describe your current brand/logo (if relevant). Where do you believe it is failing?</label>
            <div class="col-sm-8">
            <textarea name="identity_brand_1" rows="5" cols="50" class="form-control"><?php echo $identity_brand_1; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields20,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What impression would you like customers to get?</label>
            <div class="col-sm-8">
            <textarea name="identity_brand_2" rows="5" cols="50" class="form-control"><?php echo $identity_brand_2; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields21,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What brands in todays market are you most impressed by and why?</label>
            <div class="col-sm-8">
            <textarea name="identity_brand_3" rows="5" cols="50" class="form-control"><?php echo $identity_brand_3; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields22,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Which colours represent your industry or area of business (if relevant)?</label>
            <div class="col-sm-8">
            <textarea name="identity_brand_4" rows="5" cols="50" class="form-control"><?php echo $identity_brand_4; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields23,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Please use this space to list any additional information about your business, products, services or target markets that may be useful</label>
            <div class="col-sm-8">
            <textarea name="identity_brand_5" rows="5" cols="50" class="form-control"><?php echo $identity_brand_5; ?></textarea>
            </div>
            </div>
			<?php } ?>

			</div>
        </div>
    </div>

</div>