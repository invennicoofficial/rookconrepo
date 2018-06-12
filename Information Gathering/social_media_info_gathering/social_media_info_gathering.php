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
    $business = '';
	$big_picture = '';
	$goal = '';
	$culture = '';
	$community = '';
	$conversation = '';
	$location = '';
	$age_range = '';
	$gender = '';
	$language1 = '';
	$interests = '';
	$character_persona = '';
	$tone = '';
	$language = '';
	$purpose = '';
	$features_product_s_e = '';
	$channels = '';
	$research = '';
	$repurposing = '';
	$writing = '';
	$promotion = '';
	$creative = '';
	$quality_assurance = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];

    echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM info_social_media_info_gathering WHERE fieldlevelriskid='$formid'"));
    $business = $get_field_level['business'];
	$today_date = $get_field_level['today_date'];
	$big_picture = $get_field_level['big_picture'];
	$goal = $get_field_level['goal'];
	$culture = $get_field_level['culture'];
	$community = $get_field_level['community'];
	$conversation = $get_field_level['conversation'];
	$location = $get_field_level['location'];
	$age_range = $get_field_level['age_range'];
	$gender = $get_field_level['gender'];
	$language1 = $get_field_level['language1'];
	$interests = $get_field_level['interests'];
	$character_persona = $get_field_level['character_persona'];
	$tone = $get_field_level['tone'];
	$language = $get_field_level['language'];
	$purpose = $get_field_level['purpose'];
	$features_product_s_e = $get_field_level['features_product_s_e'];
	$channels = $get_field_level['channels'];
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

<?php if (strpos(','.$form_config.',', ',fields1,') !== FALSE) { ?>

    <div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_info" >
					Business<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

        <div id="collapse_info" class="panel-collapse collapse">
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

	  <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info1" >
                    Big Picture<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info1" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">What do you hope to gain as a company from social media?</label>
                    <div class="col-sm-8">
                      <textarea name="big_picture" rows="5" cols="50" class="form-control"><?php echo $big_picture; ?></textarea>
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
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info2" >
                    Goal<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info2" class="panel-collapse collapse">
            <div class="panel-body">
			<p>What is your objective?</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$goal.',', ',Boost your posts,') !== FALSE) { echo " checked"; } ?>  name="goal[]" value="Boost your posts">Boost your posts</li>

				<li><input type="checkbox" <?php if (strpos(','.$goal.',', ',Increase engagement in your app,') !== FALSE) { echo " checked"; } ?>  name="goal[]" value="Increase engagement in your app">Increase engagement in your app</li>

				<li><input type="checkbox" <?php if (strpos(','.$goal.',', ',Promote your page,') !== FALSE) { echo " checked"; } ?>  name="goal[]" value="Promote your page">Promote your page</li>

				<li><input type="checkbox" <?php if (strpos(','.$goal.',', ',Reach people near your business,') !== FALSE) { echo " checked"; } ?>  name="goal[]" value="Reach people near your business">Reach people near your business</li>

				<li><input type="checkbox" <?php if (strpos(','.$goal.',', ',Send people to your website,') !== FALSE) { echo " checked"; } ?>  name="goal[]" value="Send people to your website">Send people to your website</li>

				<li><input type="checkbox" <?php if (strpos(','.$goal.',', ',Raise attendance at your event,') !== FALSE) { echo " checked"; } ?>  name="goal[]" value="Raise attendance at your event">Raise attendance at your event</li>

				<li><input type="checkbox" <?php if (strpos(','.$goal.',', ',Increase conversions to your website,') !== FALSE) { echo " checked"; } ?>  name="goal[]" value="Increase conversions to your website">Increase conversions to your website</li>

				<li><input type="checkbox" <?php if (strpos(','.$goal.',', ',Get people to claim your offer,') !== FALSE) { echo " checked"; } ?>  name="goal[]" value="Get people to claim your offer">Get people to claim your offer</li>

				<li><input type="checkbox" <?php if (strpos(','.$goal.',', ',Get installs of your app,') !== FALSE) { echo " checked"; } ?>  name="goal[]" value="Get installs of your app">Get installs of your app</li>

				<li><input type="checkbox" <?php if (strpos(','.$goal.',', ',Get video views,') !== FALSE) { echo " checked"; } ?>  name="goal[]" value="Get video views">Get video views</li>

				</ul>

			</div>
        </div>
    </div>

<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields3,') !== FALSE) { ?>

	  <div class="panel panel-default">
	   <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info3" >
                    Culture<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info3" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">What does your company stand for? What makes you stand out from all the others who are after the same audience?</label>
                    <div class="col-sm-8">
                      <textarea name="culture" rows="5" cols="50" class="form-control"><?php echo $culture; ?></textarea>
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
                    Community<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info4" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">What are the problems & concerns of your target market? How do they express this? What do they want from you?</label>
                    <div class="col-sm-8">
                      <textarea name="community" rows="5" cols="50" class="form-control"><?php echo $community; ?></textarea>
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
                    Conversation<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info5" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">What do you want to add to the conversation? (E.g. - Customer support, industry education, product promotions, general fun.)</label>
                    <div class="col-sm-8">
                      <textarea name="conversation" rows="5" cols="50" class="form-control"><?php echo $conversation; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info6" >
                    Audience<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info6" class="panel-collapse collapse">
            <div class="panel-body">
			<p>Who are you trying to connect to?</p>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Location:</label>
            <div class="col-sm-8">
            <input type="text" name="location" value="<?php echo $location; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Age Range:</label>
            <div class="col-sm-8">
            <input type="text" name="age_range" value="<?php echo $age_range; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Gender:</label>
            <div class="col-sm-8">
            <input type="text" name="gender" value="<?php echo $gender; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Language:</label>
            <div class="col-sm-8">
            <input type="text" name="language1" value="<?php echo $language1; ?>" class="form-control" />
            </div>
            </div>

			<div class="form-group">
            <label for="business_street" class="col-sm-4 control-label">Interests:</label>
            <div class="col-sm-8">
            <input type="text" name="interests" value="<?php echo $interests; ?>" class="form-control" />
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
                    Character/Persona<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info7" class="panel-collapse collapse">
            <div class="panel-body">
			<p>Who does your brand sound like? Create an identity with specific attributes that fit who you want to sound like online.</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$character_persona.',', ',Friendly,') !== FALSE) { echo " checked"; } ?>  name="character_persona[]" value="Friendly">Friendly</li>

				<li><input type="checkbox" <?php if (strpos(','.$character_persona.',', ',Playful,') !== FALSE) { echo " checked"; } ?>  name="character_persona[]" value="Playful">Playful</li>

				<li><input type="checkbox" <?php if (strpos(','.$character_persona.',', ',Warm,') !== FALSE) { echo " checked"; } ?>  name="character_persona[]" value="Warm">Warm</li>

				<li><input type="checkbox" <?php if (strpos(','.$character_persona.',', ',Authoritative,') !== FALSE) { echo " checked"; } ?>  name="character_persona[]" value="Authoritative">Authoritative</li>

				<li><input type="checkbox" <?php if (strpos(','.$character_persona.',', ',Inspiring,') !== FALSE) { echo " checked"; } ?>  name="character_persona[]" value="Inspiring">Inspiring</li>

				<li><input type="checkbox" <?php if (strpos(','.$character_persona.',', ',Professional,') !== FALSE) { echo " checked"; } ?>  name="character_persona[]" value="Professional">Professional</li>

				<li><input type="checkbox" <?php if (strpos(','.$character_persona.',', ',Other,') !== FALSE) { echo " checked"; } ?>  name="character_persona[]" value="Other">Other</li>



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
                    Tone<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info8" class="panel-collapse collapse">
            <div class="panel-body">
			<p>What is the general vibe of your brand?</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$tone.',', ',Personal,') !== FALSE) { echo " checked"; } ?>  name="tone[]" value="Personal">Personal</li>

				<li><input type="checkbox" <?php if (strpos(','.$tone.',', ',Humble,') !== FALSE) { echo " checked"; } ?>  name="tone[]" value="Humble">Humble</li>

				<li><input type="checkbox" <?php if (strpos(','.$tone.',', ',Honest,') !== FALSE) { echo " checked"; } ?>  name="tone[]" value="Honest">Honest</li>

				<li><input type="checkbox" <?php if (strpos(','.$tone.',', ',Direct,') !== FALSE) { echo " checked"; } ?>  name="tone[]" value="Direct">Direct</li>

				<li><input type="checkbox" <?php if (strpos(','.$tone.',', ',Clinical,') !== FALSE) { echo " checked"; } ?>  name="tone[]" value="Clinical">Clinical</li>

				<li><input type="checkbox" <?php if (strpos(','.$tone.',', ',Scientific,') !== FALSE) { echo " checked"; } ?>  name="tone[]" value="Scientific">Scientific</li>

				<li><input type="checkbox" <?php if (strpos(','.$tone.',', ',Other,') !== FALSE) { echo " checked"; } ?>  name="tone[]" value="Other">Other</li>

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
                    Language<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info9" class="panel-collapse collapse">
            <div class="panel-body">
			<p>What kind of words do you use in your social media conversations?</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$language.',', ',Complex,') !== FALSE) { echo " checked"; } ?>  name="language[]" value="Complex">Complex</li>

				<li><input type="checkbox" <?php if (strpos(','.$language.',', ',Simple,') !== FALSE) { echo " checked"; } ?>  name="language[]" value="Simple">Simple</li>

				<li><input type="checkbox" <?php if (strpos(','.$language.',', ',Savvy,') !== FALSE) { echo " checked"; } ?>  name="language[]" value="Savvy">Savvy</li>

				<li><input type="checkbox" <?php if (strpos(','.$language.',', ',Jargon-filled,') !== FALSE) { echo " checked"; } ?>  name="language[]" value="Jargon-filled">Jargon-filled</li>

				<li><input type="checkbox" <?php if (strpos(','.$language.',', ',Insider,') !== FALSE) { echo " checked"; } ?>  name="language[]" value="Insider">Insider</li>

				<li><input type="checkbox" <?php if (strpos(','.$language.',', ',Fun,') !== FALSE) { echo " checked"; } ?>  name="language[]" value="Fun">Fun</li>

				<li><input type="checkbox" <?php if (strpos(','.$language.',', ',Serious,') !== FALSE) { echo " checked"; } ?>  name="language[]" value="Serious">Serious</li>

				<li><input type="checkbox" <?php if (strpos(','.$language.',', ',Whimsical,') !== FALSE) { echo " checked"; } ?>  name="language[]" value="Whimsical">Whimsical</li>

				<li><input type="checkbox" <?php if (strpos(','.$language.',', ',Other,') !== FALSE) { echo " checked"; } ?>  name="language[]" value="Other">Other</li>


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
                    Purpose<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info10" class="panel-collapse collapse">
            <div class="panel-body">
			<p>Why are you on social media in the first place?</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$purpose.',', ',Engage,') !== FALSE) { echo " checked"; } ?>  name="purpose[]" value="Engage">Engage</li>

				<li><input type="checkbox" <?php if (strpos(','.$purpose.',', ',Entertain,') !== FALSE) { echo " checked"; } ?>  name="purpose[]" value="Entertain">Entertain</li>

				<li><input type="checkbox" <?php if (strpos(','.$purpose.',', ',Educate,') !== FALSE) { echo " checked"; } ?>  name="purpose[]" value="Educate">Educate</li>

				<li><input type="checkbox" <?php if (strpos(','.$purpose.',', ',Delight,') !== FALSE) { echo " checked"; } ?>  name="purpose[]" value="Delight">Delight</li>

				<li><input type="checkbox" <?php if (strpos(','.$purpose.',', ',Inform,') !== FALSE) { echo " checked"; } ?>  name="purpose[]" value="Inform">Inform</li>

				<li><input type="checkbox" <?php if (strpos(','.$purpose.',', ',Sell,') !== FALSE) { echo " checked"; } ?>  name="purpose[]" value="Sell">Sell</li>

				<li><input type="checkbox" <?php if (strpos(','.$purpose.',', ',Enable,') !== FALSE) { echo " checked"; } ?>  name="purpose[]" value="Enable">Enable</li>

				<li><input type="checkbox" <?php if (strpos(','.$purpose.',', ',Amplify,') !== FALSE) { echo " checked"; } ?>  name="purpose[]" value="Amplify">Amplify</li>

				<li><input type="checkbox" <?php if (strpos(','.$purpose.',', ',Other,') !== FALSE) { echo " checked"; } ?>  name="purpose[]" value="Other">Other</li>

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
                    Featured Product, Service or Event<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info11" class="panel-collapse collapse">
            <div class="panel-body">

                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">What is happening this month that we should make specific mention of?</label>
                    <div class="col-sm-8">
                      <textarea name="features_product_s_e" rows="5" cols="50" class="form-control"><?php echo $features_product_s_e; ?></textarea>
                    </div>
                  </div>

            </div>
        </div>
    </div>
<?php } ?>

<?php if (strpos(','.$form_config.',', ',fields12,') !== FALSE) { ?>
<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info12" >
                    Channels<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info12" class="panel-collapse collapse">
            <div class="panel-body">
			<p>What mediums will we be managing?</p>

			<ul style="list-style-type: none;">

				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Facebook,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Facebook">Facebook</li>

				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Google,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Google">Google</li>

				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Twitter,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Twitter">Twitter</li>

				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Linkedin,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Linkedin">Linkedin</li>

				<li><input type="checkbox" <?php if (strpos(','.$channels.',', ',Others,') !== FALSE) { echo " checked"; } ?>  name="channels[]" value="Others">Others</li>

				</ul>

			</div>
        </div>
    </div>

<?php } ?>

<?php if (strpos($form_config, ','."fields13".',') !== FALSE) { ?>
<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse_info13" >
                    Resources<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_info13" class="panel-collapse collapse">
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