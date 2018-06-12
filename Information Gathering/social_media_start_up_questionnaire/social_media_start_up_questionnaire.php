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
$client_1 = '';
$client_2 = '';
$customer_service_1 = '';
$customer_service_2 = '';
$customer_service_3 = '';
$social_1 = '';
$social_2 = '';
$social_3 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_social_media_start_up_questionnaire WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $business = $get_field_level['business'];
	$business_1 = $get_field_level['business_1'];
	$business_2 = $get_field_level['business_2'];
	$business_3 = $get_field_level['business_3'];
	$business_4 = $get_field_level['business_4'];
	$business_5 = $get_field_level['business_5'];
	$business_6 = $get_field_level['business_6'];
	$client_1 = $get_field_level['client_1'];
	$client_2 = $get_field_level['client_2'];
	$customer_service_1 = $get_field_level['customer_service_1'];
	$customer_service_2 = $get_field_level['customer_service_2'];
	$customer_service_3 = $get_field_level['customer_service_3'];
	$social_1 = $get_field_level['social_1'];
	$social_2 = $get_field_level['social_2'];
	$social_3 = $get_field_level['social_3'];
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

			<?php if (strpos(','.$form_config.',', ',fields1,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Describe your business in one sentence.</label>
            <div class="col-sm-8">
            <textarea name="business_1" rows="5" cols="50" class="form-control"><?php echo $business_1; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields2,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Who are your main competitors, and what do they do well?</label>
            <div class="col-sm-8">
            <textarea name="business_2" rows="5" cols="50" class="form-control"><?php echo $business_2; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What separates you from your competitors? What do you do well by comparison?</label>
            <div class="col-sm-8">
            <textarea name="business_3" rows="5" cols="50" class="form-control"><?php echo $business_3; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields4,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What kind of tone would you like to convey with your social media?</label>
            <div class="col-sm-8">
            <textarea name="business_4" rows="5" cols="50" class="form-control"><?php echo $business_4; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What are 3-5 of the core values of your business? (e.g. innovation, humour, creativity).</label>
            <div class="col-sm-8">
            <textarea name="business_5" rows="5" cols="50" class="form-control"><?php echo $business_5; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields6,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">If you were to come up with 3 hashtags for your business, what might they be? Hashtags are keywords or word combinations used to label and search conversations in social media #yyc,#calgary,#yycweather, #calgaryweather, #yycstorm and #calgarystorm are all ways someone might find recent posts in social media about a current storm in Calgary.</label>
            <div class="col-sm-8">
            <textarea name="business_6" rows="5" cols="50" class="form-control"><?php echo $business_6; ?></textarea>
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
					Client<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Who is your ideal customer? What do they typically come to your business for?</label>
            <div class="col-sm-8">
            <textarea name="client_1" rows="5" cols="50" class="form-control"><?php echo $client_1; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What kind of topics and sources of information might your clients be interested in? What topics might be relevant to your clients?</label>
            <div class="col-sm-8">
            <textarea name="client_2" rows="5" cols="50" class="form-control"><?php echo $client_2; ?></textarea>
            </div>
            </div>
			<?php } ?>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info3" >
					Customer Service<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Do you have a specific project/product you would want promoted in social media? Please provide a brief description of the project/product.</label>
            <div class="col-sm-8">
            <textarea name="customer_service_1" rows="5" cols="50" class="form-control"><?php echo $customer_service_1; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What type of content would you prefer to see on your social media (e.g. articles, images, text).</label>
            <div class="col-sm-8">
            <textarea name="customer_service_2" rows="5" cols="50" class="form-control"><?php echo $customer_service_2; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Do you want to use social media to provide customer service (i.e. what should we do while acting on your behalf if someone has a question or concern? Alert you, reply and gather initial info and forward to you? Who should this information go to?)</label>
            <div class="col-sm-8">
            <textarea name="customer_service_3" rows="5" cols="50" class="form-control"><?php echo $customer_service_3; ?></textarea>
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
					Social Media<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

			<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Facebook</label>
            <div class="col-sm-8">
            <textarea name="social_1" rows="5" cols="50" class="form-control"><?php echo $social_1; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields13,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Twitter</label>
            <div class="col-sm-8">
            <textarea name="social_2" rows="5" cols="50" class="form-control"><?php echo $social_2; ?></textarea>
            </div>
            </div>
			<?php } ?>

			<?php if (strpos(','.$form_config.',', ',fields14,') !== FALSE) { ?>
			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">LinkedIn</label>
            <div class="col-sm-8">
            <textarea name="social_3" rows="5" cols="50" class="form-control"><?php echo $social_3; ?></textarea>
            </div>
            </div>
			<?php } ?>


			</div>
        </div>
    </div>

</div>