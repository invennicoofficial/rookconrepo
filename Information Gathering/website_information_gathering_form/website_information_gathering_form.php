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
$phone_num = '';
$email = '';
$work_completed = '';
$project_details = '';
$branding_1 = '';
$branding_2 = '';
$branding_3 = '';
$branding_4 = '';
$hosting_email_1 = '';
$hosting_email_2 = '';
$hosting_email_3 = '';
$hosting_email_4 = '';
$hosting_email_5 = '';
$hosting_email_6 = '';
$hosting_email_7 = '';
$hosting_email_8 = '';
$hosting_email_9 = '';
$hosting_email_10 = '';
$hosting_email_11 = '';
$hosting_email_12 = '';
$hosting_email_13 = '';
$hosting_email_14 = '';
$web_development_1 = '';
$web_development_2 = '';
$web_development_3 = '';
$web_development_4 = '';
$web_development_5 = '';
$web_development_6 = '';
$web_development_7 = '';
$web_development_8 = '';
$web_development_9 = '';
$web_development_10 = '';
$web_development_11 = '';
$web_development_12 = '';
$web_development_13 = '';
$web_development_14 = '';
$web_development_15 = '';
$web_development_16 = '';
$web_development_17 = '';
$web_development_18 = '';
$web_development_19 = '';
$web_development_20 = '';
$web_development_21 = '';
$web_development_22 = '';
$web_development_23 = '';
$web_development_24 = '';
$web_development_25 = '';
$web_development_26 = '';
$web_development_27 = '';
$notes_comments = '';

$project_details_1 = '';
$branding_5 = '';
$landing_1 = '';
$web_development_28 = '';
$web_development_29 = '';
$web_development_30 = '';
$web_development_31 = '';
$web_development_32 = '';
$web_development_33 = '';
$web_development_34 = '';
$web_development_35 = '';
$web_development_36 = '';
$web_development_37 = '';
$web_development_38 = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_website_information_gathering_form WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $client_name = $get_field_level['client_name'];
    $phone_num = $get_field_level['phone_num'];
    $email = $get_field_level['email'];
    $work_completed = $get_field_level['work_completed'];
    $project_details = $get_field_level['project_details'];
    $branding_1 = $get_field_level['branding_1'];
    $branding_2 = $get_field_level['branding_2'];
    $branding_3 = $get_field_level['branding_3'];
    $branding_4 = $get_field_level['branding_4'];
    $hosting_email_1 = $get_field_level['hosting_email_1'];
    $hosting_email_2 = $get_field_level['hosting_email_2'];
    $hosting_email_3 = $get_field_level['hosting_email_3'];
    $hosting_email_4 = $get_field_level['hosting_email_4'];
    $hosting_email_5 = $get_field_level['hosting_email_5'];
    $hosting_email_6 = $get_field_level['hosting_email_6'];
    $hosting_email_7 = $get_field_level['hosting_email_7'];
    $hosting_email_8 = $get_field_level['hosting_email_8'];
    $hosting_email_9 = $get_field_level['hosting_email_9'];
    $hosting_email_10 = $get_field_level['hosting_email_10'];
    $hosting_email_11 = $get_field_level['hosting_email_11'];
    $hosting_email_12 = $get_field_level['hosting_email_12'];
    $hosting_email_13 = $get_field_level['hosting_email_13'];
    $hosting_email_14 = $get_field_level['hosting_email_14'];
    $web_development_1 = $get_field_level['web_development_1'];
    $web_development_2 = $get_field_level['web_development_2'];
    $web_development_3 = $get_field_level['web_development_3'];
    $web_development_4 = $get_field_level['web_development_4'];
    $web_development_5 = $get_field_level['web_development_5'];
    $web_development_6 = $get_field_level['web_development_6'];
    $web_development_7 = $get_field_level['web_development_7'];
    $web_development_8 = $get_field_level['web_development_8'];
    $web_development_9 = $get_field_level['web_development_9'];
    $web_development_10 = $get_field_level['web_development_10'];
    $web_development_11 = $get_field_level['web_development_'];
    $web_development_12 = $get_field_level['web_development_12'];
    $web_development_13 = $get_field_level['web_development_13'];
    $web_development_14 = $get_field_level['web_development_14'];
    $web_development_15 = $get_field_level['web_development_15'];
    $web_development_16 = $get_field_level['web_development_16'];
    $web_development_17 = $get_field_level['web_development_17'];
    $web_development_18 = $get_field_level['web_development_18'];
    $web_development_19 = $get_field_level['web_development_19'];
    $web_development_20 = $get_field_level['web_development_20'];
    $web_development_21 = $get_field_level['web_development_21'];
    $web_development_22 = $get_field_level['web_development_22'];
    $web_development_23 = $get_field_level['web_development_23'];
    $web_development_24 = $get_field_level['web_development_24'];
    $web_development_25 = $get_field_level['web_development_25'];
    $web_development_26 = $get_field_level['web_development_26'];
    $web_development_27 = $get_field_level['web_development_27'];
    $notes_comments = $get_field_level['notes_comments'];

    $project_details_1 = $get_field_level['project_details_1'];
    $branding_5 = $get_field_level['branding_5'];
    $landing_1 = $get_field_level['landing_1'];
    $web_development_28 = $get_field_level['web_development_28'];
    $web_development_29 = $get_field_level['web_development_29'];
    $web_development_30 = $get_field_level['web_development_30'];
    $web_development_31 = $get_field_level['web_development_31'];
    $web_development_32 = $get_field_level['web_development_32'];
    $web_development_33 = $get_field_level['web_development_33'];
    $web_development_34 = $get_field_level['web_development_34'];
    $web_development_35 = $get_field_level['web_development_35'];
    $web_development_36 = $get_field_level['web_development_36'];
    $web_development_37 = $get_field_level['web_development_37'];
    $web_development_38 = $get_field_level['web_development_38'];
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

			<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
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

			<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Phone Number:</label>
            <div class="col-sm-8">
            <input type="text" name="phone_num" value="<?php echo $phone_num; ?>" class="form-control" />
            </div>
            </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Email:</label>
            <div class="col-sm-8">
            <input type="text" name="email" value="<?php echo $email; ?>" class="form-control" />
            </div>
            </div>
            <?php } ?>

			<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Individual/Team to approve work completed:</label>
            <div class="col-sm-8">
            <input type="text" name="work_completed" value="<?php echo $work_completed; ?>" class="form-control" />
            </div>
            </div>
            <?php } ?>
	        <p>*Confirm that information above is for the main point of contact for the project</p>

			</div>
        </div>
    </div>


    <?php if (strpos(','.$form_config.',', ',fields5,') !== FALSE) { ?>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info5" >
                    Website Project Details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

			<ul style="list-style-type: none;">
				<li><input type="checkbox" <?php if (strpos(','.$project_details.',', ',New Website,') !== FALSE) { echo " checked"; } ?>  name="project_details[]" value="New Website">New Website</li>

				<li><input type="checkbox" <?php if (strpos(','.$project_details.',', ',Website Update,') !== FALSE) { echo " checked"; } ?>  name="project_details[]" value="Website Update">Website Update</li>

				<li><input type="checkbox" <?php if (strpos(','.$project_details.',', ',Support & Maintenance,') !== FALSE) { echo " checked"; } ?>  name="project_details[]" value="Support & Maintenance">Support & Maintenance</li>
			</ul>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Do you have a coding language preference for the website?</label>
            <div class="col-sm-8">
            <textarea name="project_details_1" rows="5" cols="50" class="form-control"><?php echo $project_details_1; ?></textarea>
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
                    Branding<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">

			Is new business branding required? &nbsp;&nbsp;
			<input type="radio" <?php if ($branding_1 == 'Yes') { echo " checked"; } ?>  name="branding_1" value="Yes">Yes
			<input type="radio" <?php if ($branding_1 == 'No') { echo " checked"; } ?>  name="branding_1" value="No">No<br><br>

			Would you like FFM to update or modernize your brand? &nbsp;&nbsp;
			<input type="radio" <?php if ($branding_2 == 'Yes') { echo " checked"; } ?>  name="branding_2" value="Yes">Yes
			<input type="radio" <?php if ($branding_2 == 'No') { echo " checked"; } ?>  name="branding_2" value="No">No<br><br>

			Do you have a brand standard for your business that FFM must adhere to? &nbsp;&nbsp;
			<input type="radio" <?php if ($branding_3 == 'Yes') { echo " checked"; } ?>  name="branding_3" value="Yes">Yes
			<input type="radio" <?php if ($branding_3 == 'No') { echo " checked"; } ?>  name="branding_3" value="No">No<br><br>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">How can FFM acquire your current logo and branding elements? FFM will provide a specific list of our needs</label>
            <div class="col-sm-8">
            <textarea name="branding_4" rows="5" cols="50" class="form-control"><?php echo $branding_4; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What tone and level of sophistication do you believe would be best for your online visitors?</label>
            <div class="col-sm-8">
            <textarea name="branding_5" rows="5" cols="50" class="form-control"><?php echo $branding_5; ?></textarea>
            </div>
            </div>

            <p>*If any level of business branding is required, FFM branding information gathering must be
            completed. Please note that web development time will not begin until all branding has been
            completed and approved.</p>

			</div>
        </div>
    </div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info7" >
                    Hosting & Email Packages<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info7" class="panel-collapse collapse">
            <div class="panel-body">

			Do you currently own the domain(s)/URL you wish the website to get posted to?&nbsp;&nbsp;
			<input type="radio" <?php if ($hosting_email_1 == 'Yes') { echo " checked"; } ?>  name="hosting_email_1" value="Yes">Yes
			<input type="radio" <?php if ($hosting_email_1 == 'No') { echo " checked"; } ?>  name="hosting_email_1" value="No">No<br><br>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">If so list all domains/URLs</label>
            <div class="col-sm-8">
            <textarea name="hosting_email_2" rows="5" cols="50" class="form-control"><?php echo $hosting_email_2; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">How can FFM gain access to the domain(s)/URL</label>
            <div class="col-sm-8">
            <textarea name="hosting_email_3" rows="5" cols="50" class="form-control"><?php echo $hosting_email_3; ?></textarea>
            </div>
            </div>

			Do you need FFM to purchase domains/URLs on your behalf?&nbsp;&nbsp;
			<input type="radio" <?php if ($hosting_email_4 == 'Yes') { echo " checked"; } ?>  name="hosting_email_4" value="Yes">Yes
			<input type="radio" <?php if ($hosting_email_4 == 'No') { echo " checked"; } ?>  name="hosting_email_4" value="No">No<br><br>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">If so what domains/URLS</label>
            <div class="col-sm-8">
            <textarea name="hosting_email_5" rows="5" cols="50" class="form-control"><?php echo $hosting_email_5; ?></textarea>
            </div>
            </div>

			Do you currently have or will you be hosting your website through a third party?&nbsp;&nbsp;
			<input type="radio" <?php if ($hosting_email_6 == 'Yes') { echo " checked"; } ?>  name="hosting_email_6" value="Yes">Yes
			<input type="radio" <?php if ($hosting_email_6 == 'No') { echo " checked"; } ?>  name="hosting_email_6" value="No">No<br><br>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">If so will you be posting the website once complete or will FFM?</label>
            <div class="col-sm-8">
            <input type="text" name="hosting_email_7" value="<?php echo $hosting_email_7; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">How can FFM gain access to your hosting:</label>
            <div class="col-sm-8">
            <textarea name="hosting_email_8" rows="5" cols="50" class="form-control"><?php echo $hosting_email_8; ?></textarea>
            </div>
            </div>

			Do you have any other sub domains, sub folders, micro sites or software applications that could be affected by FFM uploading a new website on your behalf?&nbsp;&nbsp;
			<input type="radio" <?php if ($hosting_email_9 == 'Yes') { echo " checked"; } ?>  name="hosting_email_9" value="Yes">Yes
			<input type="radio" <?php if ($hosting_email_9 == 'No') { echo " checked"; } ?>  name="hosting_email_9" value="No">No<br><br>

			Would you like FFM to setup and manage your hosting?&nbsp;&nbsp;
			<input type="radio" <?php if ($hosting_email_10 == 'Yes') { echo " checked"; } ?>  name="hosting_email_10" value="Yes">Yes
			<input type="radio" <?php if ($hosting_email_10 == 'No') { echo " checked"; } ?>  name="hosting_email_10" value="No">No<br><br>

			Would you like FFM to setup and manage your email(s)?&nbsp;&nbsp;
			<input type="radio" <?php if ($hosting_email_11 == 'Yes') { echo " checked"; } ?>  name="hosting_email_11" value="Yes">Yes
			<input type="radio" <?php if ($hosting_email_11 == 'No') { echo " checked"; } ?>  name="hosting_email_11" value="No">No<br><br>

			Will FFM uploading a new website affect your email in anyway?&nbsp;&nbsp;
			<input type="radio" <?php if ($hosting_email_12 == 'Yes') { echo " checked"; } ?>  name="hosting_email_12" value="Yes">Yes
			<input type="radio" <?php if ($hosting_email_12 == 'No') { echo " checked"; } ?>  name="hosting_email_12" value="No">No<br><br>

			Would you like to use a forwarding email service?&nbsp;&nbsp;
			<input type="radio" <?php if ($hosting_email_13 == 'Yes') { echo " checked"; } ?>  name="hosting_email_13" value="Yes">Yes
			<input type="radio" <?php if ($hosting_email_13 == 'No') { echo " checked"; } ?>  name="hosting_email_13" value="No">No<br><br>

			Would you like to use Google Apps?&nbsp;&nbsp;
			<input type="radio" <?php if ($hosting_email_14 == 'Yes') { echo " checked"; } ?>  name="hosting_email_14" value="Yes">Yes
			<input type="radio" <?php if ($hosting_email_14 == 'No') { echo " checked"; } ?>  name="hosting_email_14" value="No">No<br><br>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Do you know of any potential roadblocks, sub folders or specific contact individuals FFM may need to discuss deployment details with before your website is pushed live?</label>
            <div class="col-sm-8">
            <textarea name="web_development_25" rows="5" cols="50" class="form-control"><?php echo $web_development_25; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>
<?php } ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info10" >
                    Temporary Landing Page<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info10" class="panel-collapse collapse">
            <div class="panel-body">

			Do you require an under construction or coming soon landing page?&nbsp;&nbsp;
			<input type="radio" <?php if ($web_development_1 == 'Yes') { echo " checked"; } ?>  name="web_development_1" value="Yes">Yes
			<input type="radio" <?php if ($web_development_1 == 'No') { echo " checked"; } ?>  name="web_development_1" value="No">No<br><br>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What details would you like on this landing page?</label>
            <div class="col-sm-8">
            <textarea name="landing_1" rows="5" cols="50" class="form-control"><?php echo $landing_1; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info11" >
                    Website Strategy<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info11" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Do you have a web strategy in mind for your online presence?</label>
            <div class="col-sm-8">
            <textarea name="web_development_2" rows="5" cols="50" class="form-control"><?php echo $web_development_2; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What do you want your website to do and what do you want from your website?</label>
            <div class="col-sm-8">
            <textarea name="web_development_3" rows="5" cols="50" class="form-control"><?php echo $web_development_3; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What would be the best thing a customer or potential customer could get out of your website?</label>
            <div class="col-sm-8">
            <textarea name="web_development_4" rows="5" cols="50" class="form-control"><?php echo $web_development_4; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What would be the best thing your staff could get out of your website?</label>
            <div class="col-sm-8">
            <textarea name="web_development_5" rows="5" cols="50" class="form-control"><?php echo $web_development_5; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Will your website take or store and any customer information?</label>
            <div class="col-sm-8">
            <input type="text" name="web_development_6" value="<?php echo $web_development_6; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Will your website take or store any marketing information for distribution?</label>
            <div class="col-sm-8">
            <input type="text" name="web_development_7" value="<?php echo $web_development_7; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Do you have any competitor websites that you want FFM to review?</label>
            <div class="col-sm-8">
            <textarea name="web_development_12" rows="5" cols="50" class="form-control"><?php echo $web_development_12; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Do you have any infographics are process outlines you want outlined on your website?</label>
            <div class="col-sm-8">
            <textarea name="web_development_28" rows="5" cols="50" class="form-control"><?php echo $web_development_28; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Ideally, websites match your sales process, they talk and walk you through the same details and same process a person would meeting one to one, how do you see this fitting into your strategy?</label>
            <div class="col-sm-8">
            <textarea name="web_development_29" rows="5" cols="50" class="form-control"><?php echo $web_development_29; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info12" >
                    Website Design Details<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info12" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Will you be providing designs to be built or will FFM be providing design options?</label>
            <div class="col-sm-8">
            <input type="text" name="web_development_9" value="<?php echo $web_development_9; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Do you have specific website options you want to ensure are built into your designs?</label>
            <div class="col-sm-8">
            <textarea name="web_development_10" rows="5" cols="50" class="form-control"><?php echo $web_development_10; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Do you have any specific websites that you really like?</label>
            <div class="col-sm-8">
            <textarea name="web_development_11" rows="5" cols="50" class="form-control"><?php echo $web_development_11; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">FFM websites automatically respond based on the device the website is being viewed on. Is there additional functionality or reduced functionality required for tablet and mobile versions of your website?</label>
            <div class="col-sm-8">
            <textarea name="web_development_14" rows="5" cols="50" class="form-control"><?php echo $web_development_14; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What colors do you specifically like and don't like that you&#39;d want to see incorporated into the website?</label>
            <div class="col-sm-8">
            <textarea name="web_development_30" rows="5" cols="50" class="form-control"><?php echo $web_development_30; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Do you have any design specifics you&#39;d like our designers to know when building your website?</label>
            <div class="col-sm-8">
            <textarea name="web_development_31" rows="5" cols="50" class="form-control"><?php echo $web_development_31; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info13" >
                    Website Content<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info13" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">How many pages does your website need to have and what would those pages be called?</label>
            <div class="col-sm-8">
            <textarea name="web_development_13" rows="5" cols="50" class="form-control"><?php echo $web_development_13; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Will copy/content need to be created and SEO optimized by FFM?</label>
            <div class="col-sm-8">
            <textarea name="web_development_15" rows="5" cols="50" class="form-control"><?php echo $web_development_15; ?></textarea>
            </div>
            </div>

			Will you be providing final format content, SEO optimized and ready for publish?&nbsp;&nbsp;
			<input type="radio" <?php if ($web_development_16 == 'Yes') { echo " checked"; } ?>  name="web_development_16" value="Yes">Yes
			<input type="radio" <?php if ($web_development_16 == 'No') { echo " checked"; } ?>  name="web_development_16" value="No">No<br><br>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Ideally, FFM content interviews are done with the specific individuals who have working knowledge of each page. Who might be best for us to contact for each page of your website?</label>
            <div class="col-sm-8">
            <textarea name="web_development_32" rows="5" cols="50" class="form-control"><?php echo $web_development_32; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info14" >
                    Website Images<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info14" class="panel-collapse collapse">
            <div class="panel-body">

			Will FFM be sourcing and editing images on your behalf for the website?&nbsp;&nbsp;
			<input type="radio" <?php if ($web_development_17 == 'Yes') { echo " checked"; } ?>  name="web_development_17" value="Yes">Yes
			<input type="radio" <?php if ($web_development_17 == 'No') { echo " checked"; } ?>  name="web_development_17" value="No">No<br><br>

			Will FFM be editing and reformatting images provided by you?&nbsp;&nbsp;
			<input type="radio" <?php if ($web_development_18 == 'Yes') { echo " checked"; } ?>  name="web_development_18" value="Yes">Yes
			<input type="radio" <?php if ($web_development_18 == 'No') { echo " checked"; } ?>  name="web_development_18" value="No">No<br><br>

			Will you be providing images ready for publish to FFM?&nbsp;&nbsp;
			<input type="radio" <?php if ($web_development_19 == 'Yes') { echo " checked"; } ?>  name="web_development_19" value="Yes">Yes
			<input type="radio" <?php if ($web_development_19 == 'No') { echo " checked"; } ?>  name="web_development_19" value="No">No<br><br>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">What type of imagery do you think best represents your business?</label>
            <div class="col-sm-8">
            <textarea name="web_development_33" rows="5" cols="50" class="form-control"><?php echo $web_development_33; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info15" >
                    Website Search Engine Optimization<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info15" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">What Keywords would potential customers/clients use to find your website on Google?</label>
            <div class="col-sm-8">
            <textarea name="web_development_34" rows="5" cols="50" class="form-control"><?php echo $web_development_34; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info16" >
                    Website Social Media<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info16" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Will FFM be interlinking any social media pages to your website, if so which ones?</label>
            <div class="col-sm-8">
            <textarea name="web_development_20" rows="5" cols="50" class="form-control"><?php echo $web_development_20; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Will FFM be updating or creating any social media pages on your behalf, if so which ones?</label>
            <div class="col-sm-8">
            <input type="text" name="web_development_21" value="<?php echo $web_development_21; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">What tone and messaging are you currently using or would you like to use for your social media?</label>
            <div class="col-sm-8">
            <textarea name="web_development_35" rows="5" cols="50" class="form-control"><?php echo $web_development_35; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info17" >
                    Analytics<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info17" class="panel-collapse collapse">
            <div class="panel-body">


			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Would you like to receive Google Analytics on a monthly basis from FFM?</label>
            <div class="col-sm-8">
            <input type="text" name="web_development_26" value="<?php echo $web_development_26; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Do you require quarterly meetings to review online strategies?</label>
            <div class="col-sm-8">
            <input type="text" name="web_development_36" value="<?php echo $web_development_36; ?>" class="form-control" />
            </div>
            </div>

			</div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info18" >
                    Website Back End Development<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info18" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Will your website store any leads for your sales team(if so where will the leads be stored, CRM details required)?</label>
            <div class="col-sm-8">
            <textarea name="web_development_8" rows="5" cols="50" class="form-control"><?php echo $web_development_8; ?></textarea>
            </div>
            </div>

			Would you like to have the ability to edit and update your website on your own?&nbsp;&nbsp;
			<input type="radio" <?php if ($web_development_22 == 'Yes') { echo " checked"; } ?>  name="web_development_22" value="Yes">Yes
			<input type="radio" <?php if ($web_development_22 == 'No') { echo " checked"; } ?>  name="web_development_22" value="No">No<br><br>

			Will you be running any payment information through your website?&nbsp;&nbsp;
			<input type="radio" <?php if ($web_development_23 == 'Yes') { echo " checked"; } ?>  name="web_development_23" value="Yes">Yes
			<input type="radio" <?php if ($web_development_23 == 'No') { echo " checked"; } ?>  name="web_development_23" value="No">No<br><br>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Does your website need to connect to any third party or inhouse software applications? If so, where can FFM get all the details required to limit any potential downtime?</label>
            <div class="col-sm-8">
            <textarea name="web_development_24" rows="5" cols="50" class="form-control"><?php echo $web_development_24; ?></textarea>
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">Do you currently use or need a Customer Relationship Manager (CRM) to manage your clients ongoing needs?</label>
            <div class="col-sm-8">
            <textarea name="web_development_37" rows="5" cols="50" class="form-control"><?php echo $web_development_37; ?></textarea>
            </div>
            </div>

			</div>
        </div>
    </div>

<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info8" >
                    Support Plans<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info8" class="panel-collapse collapse">
            <div class="panel-body">

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Will you be looking for FFM to support and maintain your website through a support plan?</label>
            <div class="col-sm-8">
            <input type="text" name="web_development_27" value="<?php echo $web_development_27; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="first_name[]" class="col-sm-4 control-label">What level of support best represents your specific business needs?</label>
            <div class="col-sm-8">
            <textarea name="web_development_38" rows="5" cols="50" class="form-control"><?php echo $web_development_38; ?></textarea>
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
                    Additional Notes & Comments<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info9" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Additional Notes & Comments:</label>
                    <div class="col-sm-8">
                      <textarea name="notes_comments" rows="5" cols="50" class="form-control"><?php echo $notes_comments; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
<?php } ?>

</div>