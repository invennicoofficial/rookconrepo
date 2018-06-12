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
	$purpose = '';
	$audience = '';
	$competitors = '';
	$content_anchors = '';
	$platform = '';
	$channels = '';
	$conversion_content = '';
	$free_nurture_content = '';
	$paid_nurture_content = '';
	$featured_product_or_service = '';
	$research = '';
	$repurposing = '';
	$writing = '';
	$promotion = '';
	$creative = '';
	$quality_assurance = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_blog WHERE fieldlevelriskid='$formid'"));

	$today_date = $get_field_level['today_date'];
    $business = $get_field_level['business'];
	$purpose = $get_field_level['purpose'];
	$audience = $get_field_level['audience'];
	$competitors = $get_field_level['competitors'];
	$content_anchors = $get_field_level['content_anchors'];
	$platform = $get_field_level['platform'];
	$channels = $get_field_level['channels'];
	$conversion_content = $get_field_level['conversion_content'];
	$free_nurture_content = $get_field_level['free_nurture_content'];
	$paid_nurture_content = $get_field_level['paid_nurture_content'];
	$featured_product_or_service = $get_field_level['featured_product_or_service'];
	$research = $get_field_level['research'];
	$repurposing = $get_field_level['repurposing'];
	$writing = $get_field_level['writing'];
	$promotion = $get_field_level['promotion'];
	$creative = $get_field_level['creative'];
	$quality_assurance = $get_field_level['quality_assurance'];
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
						echo '<option'.($business == $businessid ? ' selected' : '').' value="'.$businessid.'">'.get_client($dbc,$businessid)."</option>\n";
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
                    Purpose<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Why does your blog exist? Make one defining statement that you will use as an anchor for all online marketing decisions.</label>
                    <div class="col-sm-8">
                      <textarea name="purpose" rows="5" cols="50" class="form-control"><?php echo $purpose; ?></textarea>
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
                    Audience<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Describe your ideal audience in a few sentences.</label>
                    <div class="col-sm-8">
                      <textarea name="audience" rows="5" cols="50" class="form-control"><?php echo $audience; ?></textarea>
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
                    Competitors<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Which other websites are competing for the attention of your audience?</label>
                    <div class="col-sm-8">
                      <textarea name="competitors" rows="5" cols="50" class="form-control"><?php echo $competitors; ?></textarea>
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
                    Content Anchors<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">What keywords will you create content anchors for?</label>
                    <div class="col-sm-8">
                      <textarea name="content_anchors" rows="5" cols="50" class="form-control"><?php echo $content_anchors; ?></textarea>
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
                    Platform<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">
			<p>Where will you distribute your blog content?</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$platform.',', ',Your Company Blog,') !== FALSE) { echo " checked"; } ?>  name="platform[]" value="Your Company Blog">Your Company Blog</li>

				<li><input type="checkbox" <?php if (strpos(','.$platform.',', ',Guest Blog,') !== FALSE) { echo " checked"; } ?>  name="platform[]" value="Guest Blog">Guest Blog</li>

				<li><input type="checkbox" <?php if (strpos(','.$platform.',', ',Repurposing,') !== FALSE) { echo " checked"; } ?>  name="platform[]" value="Repurposing">Repurposing</li>

				<li><input type="checkbox" <?php if (strpos(','.$platform.',', ',Infographic,') !== FALSE) { echo " checked"; } ?>  name="platform[]" value="Infographic">Infographic</li>

				<li><input type="checkbox" <?php if (strpos(','.$platform.',', ',E-Book,') !== FALSE) { echo " checked"; } ?>  name="platform[]" value="E-Book">E-Book</li>

				<li><input type="checkbox" <?php if (strpos(','.$platform.',', ',Video,') !== FALSE) { echo " checked"; } ?>  name="platform[]" value="Video">Video</li>

				<li><input type="checkbox" <?php if (strpos(','.$platform.',', ',Template,') !== FALSE) { echo " checked"; } ?>  name="platform[]" value="Template">Template</li>

				<li><input type="checkbox" <?php if (strpos(','.$platform.',', ',Other,') !== FALSE) { echo " checked"; } ?>  name="platform[]" value="Other">Other</li>

			</ul>

			</div>
        </div>
    </div>

<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields7,') !== FALSE) { ?>
<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info7" >
                    Channels<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

		<div id="collapse_info7" class="panel-collapse collapse">
            <div class="panel-body">
			<p>Where will you promote your blog content?</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Email/Newsletter,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Email/Newsletter">Email/Newsletter</li>
				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Social Media,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Social Media">Social Media</li>
				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Facebook,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Facebook">Facebook</li>
				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Google,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Google">Google</li>
				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Twitter,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Twitter">Twitter</li>
				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Linkedin,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Linkedin">Linkedin</li>
				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Other,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Other">Other</li>

			</ul>

			</div>
        </div>
    </div>

<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields8,') !== FALSE) { ?>
<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info8" >
                    Conversion Content<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

		<div id="collapse_info8" class="panel-collapse collapse">
            <div class="panel-body">
			<p>What content will you give away in exchange for someone’s email address?</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$conversion_content.',', ',E-Book,') !== FALSE) { echo " checked"; } ?>  name="conversion_content[]" value="E-Book">E-Book</li>

				<li><input type="checkbox" <?php if (strpos(','.$conversion_content.',', ',Cheat Sheet,') !== FALSE) { echo " checked"; } ?>  name="conversion_content[]" value="Cheat Sheet">Cheat Sheet</li>

				<li><input type="checkbox" <?php if (strpos(','.$conversion_content.',', ',Template,') !== FALSE) { echo " checked"; } ?>  name="conversion_content[]" value="Template">Template</li>

				<li><input type="checkbox" <?php if (strpos(','.$conversion_content.',', ',Checklist,') !== FALSE) { echo " checked"; } ?>  name="conversion_content[]" value="Checklist">Checklist</li>

				<li><input type="checkbox" <?php if (strpos(','.$conversion_content.',', ',Other,') !== FALSE) { echo " checked"; } ?>  name="conversion_content[]" value="Other">Other</li>

			</ul>

			</div>
        </div>
    </div>

<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields9,') !== FALSE) { ?>
<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info9" >
                    Free Nurture Content<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

		<div id="collapse_info9" class="panel-collapse collapse">
            <div class="panel-body">
			<p>What additional content will you provide to someonebthat has opted in for the conversion content?</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$free_nurture_content.',', ',Video-Course,') !== FALSE) { echo " checked"; } ?>  name="free_nurture_content[]" value="Video-Course">Video-Course</li>

				<li><input type="checkbox" <?php if (strpos(','.$free_nurture_content.',', ',Free Trial,') !== FALSE) { echo " checked"; } ?>  name="free_nurture_content[]" value="Free Trial">Free Trial</li>

				<li><input type="checkbox" <?php if (strpos(','.$free_nurture_content.',', ',Email Course,') !== FALSE) { echo " checked"; } ?>  name="free_nurture_content[]" value="Email Course">Email Course</li>

				<li><input type="checkbox" <?php if (strpos(','.$free_nurture_content.',', ',Webinar,') !== FALSE) { echo " checked"; } ?>  name="free_nurture_content[]" value="Webinar">Webinar</li>

				<li><input type="checkbox" <?php if (strpos(','.$free_nurture_content.',', ',Other,') !== FALSE) { echo " checked"; } ?>  name="free_nurture_content[]" value="Other">Other</li>

			</ul>

			</div>
        </div>
    </div>

<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields10,') !== FALSE) { ?>
<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info10" >
                    Paid Nurture Content<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

		<div id="collapse_info10" class="panel-collapse collapse">
            <div class="panel-body">
			<p>What additional content will you provide to someone that has opted in for the conversion content?</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$paid_nurture_content.',', ',$1 Trial,') !== FALSE) { echo " checked"; } ?>  name="paid_nurture_content[]" value="$1 Trial">$1 Trial</li>

				<li><input type="checkbox" <?php if (strpos(','.$paid_nurture_content.',', ',Coaching Call,') !== FALSE) { echo " checked"; } ?>  name="paid_nurture_content[]" value="Coaching Call">Coaching Call</li>

				<li><input type="checkbox" <?php if (strpos(','.$paid_nurture_content.',', ',Paid Course,') !== FALSE) { echo " checked"; } ?>  name="paid_nurture_content[]" value="Paid Course">Paid Course</li>

				<li><input type="checkbox" <?php if (strpos(','.$paid_nurture_content.',', ',Paid Webinar,') !== FALSE) { echo " checked"; } ?>  name="paid_nurture_content[]" value="Paid Webinar">Paid Webinar</li>

				<li><input type="checkbox" <?php if (strpos(','.$paid_nurture_content.',', ',Other,') !== FALSE) { echo " checked"; } ?>  name="paid_nurture_content[]" value="Other">Other</li>

			</ul>

			</div>
        </div>
    </div>

<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields11,') !== FALSE) { ?>

	  <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info11" >
                    Featured Product or Service<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info11" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">How would you describe your featured product or service in a few sentences?</label>
                    <div class="col-sm-8">
                      <textarea name="featured_product_or_service" rows="5" cols="50" class="form-control"><?php echo $featured_product_or_service; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
<?php } ?>

<?php if (strpos($form_config, ','."fields11".',') !== FALSE) { ?>
<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info12" >
                    Resources<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info12" class="panel-collapse collapse">
            <div class="panel-body">
			<p>Who is accountable for this blog strategy?</p>


                <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Research:</label>
                <div class="col-sm-8">
                <input type="text" name="research" value="<?php echo $research; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Repurposing:</label>
                <div class="col-sm-8">
                <input type="text" name="repurposing" value="<?php echo $repurposing; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Writing:</label>
                <div class="col-sm-8">
                <input type="text" name="writing" value="<?php echo $writing; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Promotion:</label>
                <div class="col-sm-8">
                <input type="text" name="promotion" value="<?php echo $promotion; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Creative:</label>
                <div class="col-sm-8">
                <input type="text" name="creative" value="<?php echo $creative; ?>" class="form-control" />
                </div>
                </div>

				<div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Quality Assurance:</label>
                <div class="col-sm-8">
                <input type="text" name="quality_assurance" value="<?php echo $quality_assurance; ?>" class="form-control" />
                </div>
                </div>


			</div>
        </div>
    </div>
<?php } ?>

</div>