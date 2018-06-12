<?php
include ('../include.php');
checkAuthorised('infogathering');
error_reporting(0);

if (isset($_POST['submit'])) {

    $form_name = filter_var($_POST['form_name'],FILTER_SANITIZE_STRING);
    $fields = implode(',',$_POST['fields']);

    $max_section = filter_var($_POST['max_section'],FILTER_SANITIZE_STRING);
    $max_subsection = filter_var($_POST['max_subsection'],FILTER_SANITIZE_STRING);
    $max_thirdsection = filter_var($_POST['max_thirdsection'],FILTER_SANITIZE_STRING);
	$active_color = filter_var($_POST['active_color'],FILTER_SANITIZE_STRING);
	$font = filter_var($_POST['font'],FILTER_SANITIZE_STRING);
	$website = filter_var($_POST['website'],FILTER_SANITIZE_STRING);
    $pdf_style= filter_var($_POST['pdf_style'],FILTER_SANITIZE_STRING);

    $pdf_logo = htmlspecialchars($_FILES["pdf_logo"]["name"], ENT_QUOTES);

    if (strpos(','.$fields.',', ','.'Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading'.',') === false) {
        $fields = $fields.',Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading';
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfiginfogatheringid) AS fieldconfiginfogatheringid FROM field_config_infogathering WHERE form='$form_name'"));
    if($get_field_config['fieldconfiginfogatheringid'] > 0) {
		move_uploaded_file($_FILES["pdf_logo"]["tmp_name"],"download/" . $pdf_logo);

        $query_update_employee = "UPDATE `field_config_infogathering` SET `fields` = '$fields', max_section = '$max_section', max_subsection = '$max_subsection', max_thirdsection = '$max_thirdsection', pdf_logo = '$pdf_logo' , active_color = '$active_color', font='$font', website='$website', pdf_style='$pdf_style' WHERE `form`='$form_name'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
		move_uploaded_file($_FILES["pdf_logo"]["tmp_name"], "download/" . $_FILES["pdf_logo"]["name"]) ;
        $query_insert_config = "INSERT INTO `field_config_infogathering` (`form`, `fields`, `max_section`, `max_subsection`, `max_thirdsection`, `pdf_logo`, `active_color`,`font`,`website`, `pdf_style`) VALUES ('$form_name', '$fields', '$max_section', '$max_subsection', '$max_thirdsection', '$pdf_logo','$active_color','$font','$website','$pdf_style')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    /*
    $manual_policy_pro_email = filter_var($_POST['manual_policy_pro_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='manual_policy_pro_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$manual_policy_pro_email' WHERE name='manual_policy_pro_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('manual_policy_pro_email', '$manual_policy_pro_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    */

    echo '<script type="text/javascript"> window.location.replace("field_config_infogathering.php?form='.$form_name.'"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#tab_field").change(function() {
        window.location = 'field_config_infogathering.php?tab='+this.value;
	});

	$("#form_name").change(function() {
        var tab = $("#tab_field").val();
        window.location = 'field_config_infogathering.php?tab='+tab+'&form='+this.value;
	});
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
	<h1>Information Gathering</h1>
	<div class="pad-left gap-top gap-bottom"><a href="infogathering.php?tab=Form" class="btn config-btn">Back to Dashboard</a></div>
	<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->

<?php //include ('field_config_manual.php'); ?>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<?php
//$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT manual FROM field_config_manuals"));
//$value_config = ','.$get_field_config['manual'].',';
?>

<?php
$form = $_GET['form'];
$user_form_id = mysqli_fetch_array(mysqli_query($dbc, "SELECT `form_id` FROM `user_forms` WHERE `form_id` = '$form'"))['form_id'];

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_infogathering WHERE form='$form'"));

$fields = ','.$get_field_config['fields'].',';
$pdf_logo = $get_field_config['pdf_logo'];
$max_section = $get_field_config['max_section'];
$max_subsection = $get_field_config['max_subsection'];
$max_thirdsection = $get_field_config['max_thirdsection'];
$active_color = $get_field_config['active_color'];
$font_code = $get_field_config['font'];
$website_code = $get_field_config['website'];
$pdf_logo = $get_field_config['pdf_logo'];
$pdf_style = $get_field_config['pdf_style'];
if($max_section == '') {
    $max_section = 10;
}
if($max_subsection == '') {
    $max_subsection = 10;
}
if($max_thirdsection == '') {
    $max_thirdsection = 10;
}
?>

    <div class="form-group">
        <label for="fax_number"	class="col-sm-4	control-label">Form:</label>
        <div class="col-sm-8">
            <select data-placeholder="Choose a Vendor..." id="form_name" name="form_name" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <option <?php if ($form == "Client Business Introduction") { echo " selected"; } ?> value="Client Business Introduction">Client Business Introduction</option>
              <option <?php if ($form == "Branding Questionnaire") { echo " selected"; } ?> value="Branding Questionnaire">Branding Questionnaire</option>
              <option <?php if ($form == "Website Information Gathering") { echo " selected"; } ?> value="Website Information Gathering">Website Information Gathering</option>
              <option <?php if ($form == "Blog") { echo " selected"; } ?> value="Blog">Blog</option>
              <option <?php if ($form == "Marketing Strategies Review") { echo " selected"; } ?> value="Marketing Strategies Review">Marketing Strategies Review</option>
              <option <?php if ($form == "Social Media Info Gathering") { echo " selected"; } ?> value="Social Media Info Gathering">Social Media Info Gathering</option>
              <option <?php if ($form == "Social Media Start Up Questionnaire") { echo " selected"; } ?> value="Social Media Start Up Questionnaire">Social Media Start Up Questionnaire</option>

              <option <?php if ($form == "Business Case Format") { echo " selected"; } ?> value="Business Case Format">Business Case Format</option>
              <option <?php if ($form == "Product-Service Outline") { echo " selected"; } ?> value="Product-Service Outline">Product-Service Outline</option>
              <option <?php if ($form == "Client Reviews") { echo " selected"; } ?> value="Client Reviews">Client Reviews</option>
              <option <?php if ($form == "SWOT") { echo " selected"; } ?> value="SWOT">SWOT</option>
              <option <?php if ($form == "GAP Analysis") { echo " selected"; } ?> value="GAP Analysis">GAP Analysis</option>
              <option <?php if ($form == "Lesson Plan") { echo " selected"; } ?> value="Lesson Plan">Lesson Plan</option>
              <option <?php if ($form == "Marketing Plan Information Gathering") { echo " selected"; } ?> value="Marketing Plan Information Gathering">Marketing Plan Information Gathering</option>
              <option <?php if ($form == "Marketing Information") { echo " selected"; } ?> value="Marketing Information">Marketing Information</option>
              <?php
                  $query = mysqli_query ($dbc, "SELECT * FROM `user_forms` WHERE CONCAT(',',`assigned_tile`,',') LIKE '%,infogathering,%'");;
                  while ($row = mysqli_fetch_array($query)) { ?>
                    <option <?php if ($user_form_id == $row['form_id']) { echo " selected" ; } ?> value="<?php echo $row['form_id']; ?>"><?php echo $row['name']; ?></option>
              <?php } ?>

            </select>
        </div>
    </div>

    <div class="panel-group" id="accordion2">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                        Choose Fields for Information Gathering<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_field" class="panel-collapse collapse">
                <div class="panel-body">
				   <div id='no-more-tables'>
                    <table border='2' cellpadding='10' class='table'>
                        <tr>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Topic (Sub Tab)".',') !== FALSE) { echo " checked"; } ?> value="Topic (Sub Tab)" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Topic (Sub Tab)
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Section #".',') !== FALSE) { echo " checked"; } ?> value="Section #" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Section #
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Section Heading".',') !== FALSE) { echo " checked"; } ?> value="Section Heading" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Section Heading
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Sub Section #".',') !== FALSE) { echo " checked"; } ?> value="Sub Section #" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Sub Section #
                            </td>
                            <td>
                                <input disabled type="checkbox" <?php if (strpos($fields, ','."Sub Section Heading".',') !== FALSE) { echo " checked"; } ?> value="Sub Section Heading" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Sub Section Heading
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Third Tier Section #".',') !== FALSE) { echo " checked"; } ?> value="Third Tier Section #" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Third Tier Section #
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Third Tier Heading".',') !== FALSE) { echo " checked"; } ?> value="Third Tier Heading" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Third Tier Heading
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Detail".',') !== FALSE) { echo " checked"; } ?> value="Detail" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Detail
                            </td>

                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Document".',') !== FALSE) { echo " checked"; } ?> value="Document" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Document
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Link".',') !== FALSE) { echo " checked"; } ?> value="Link" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Link
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Videos".',') !== FALSE) { echo " checked"; } ?> value="Videos" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Videos
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Signature box".',') !== FALSE) { echo " checked"; } ?> value="Signature box" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Signature box
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Comments
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Staff
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Review Deadline".',') !== FALSE) { echo " checked"; } ?> value="Review Deadline" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Review Deadline
                            </td>

                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Status
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Form".',') !== FALSE) { echo " checked"; } ?> value="Form" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Form
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Business".',') !== FALSE) { echo " checked"; } ?> value="Business" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Business
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Project".',') !== FALSE) { echo " checked"; } ?> value="Project" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;<?= PROJECT_NOUN ?>
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Created By".',') !== FALSE) { echo " checked"; } ?> value="Created By" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Created By
                            </td>
                            <td>
                                <input type="checkbox" <?php if (strpos($fields, ','."Today Date".',') !== FALSE) { echo " checked"; } ?> value="Today Date" style="height: 20px; width: 20px;" name="fields[]">&nbsp;&nbsp;Today Date
                            </td>
                        </tr>
                    </table>
				   </div>
                </div>
            </div>
        </div>

        <!--
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_email" >
                        Send Email on Comment<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_email" class="panel-collapse collapse">
                <div class="panel-body">

                    <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Send Email on Comment:</label>
                    <div class="col-sm-8">
                      <input name="manual_policy_pro_email" value="<?php echo get_config($dbc, 'manual_policy_pro_email'); ?>" type="text" class="form-control">
                    </div>
                    </div>

                </div>
            </div>
        </div>
        -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_max" >
                        Max Selection<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_max" class="panel-collapse collapse">
                <div class="panel-body">

                    <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Max Section #:<br><em>(add only digits)</em></label>
                    <div class="col-sm-8">
                      <input name="max_section" value="<?php echo $max_section; ?>" type="text" class="form-control">
                    </div>
                    </div>

                    <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Max Sub Section #:<br><em>(add only digits)</em></label>
                    <div class="col-sm-8">
                      <input name="max_subsection" value="<?php echo $max_subsection; ?>" type="text" class="form-control">
                    </div>
                    </div>

                    <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Max Third Tier Section #:<br><em>(add only digits)</em></label>
                    <div class="col-sm-8">
                      <input name="max_thirdsection" value="<?php echo $max_thirdsection ?>" type="text" class="form-control">
                    </div>
                    </div>

                </div>
            </div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_design" >
						Custom PDF Design<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_design" class="panel-collapse collapse">
                <div class="panel-body">

                    <?php if(empty($user_form_id)) { ?>
                        <div class="form-group">
                        <label for="pdf_logo" class="col-sm-4 control-label">PDF Style:<br></label>
                        <div class="col-sm-8">
                            <select name="pdf_style" class="chosen-select-deselect form-control">
                                <option></option>
                                <option value="design_styleA" <?= ($pdf_style == 'design_styleA' ? 'selected' : '') ?>>Design Style A</option>
                                <option value="design_styleB" <?= ($pdf_style == 'design_styleB' ? 'selected' : '') ?>>Design Style B</option>
                                <option value="design_styleC" <?= ($pdf_style == 'design_styleC' ? 'selected' : '') ?>>Design Style C</option>
                            </select>
                        </div>
                        </div>

                        <div class="form-group">
    					<label for="pdf_logo" class="col-sm-4 control-label">PDF Logo:<br></label>
    					<div class="col-sm-8">
    						<?php if($pdf_logo != '') {
    							echo '<a href="download/'.$pdf_logo.'" target="_blank">View</a>';
    						?>
    						<input type="hidden" name="pdf_logo" value="<?php echo $pdf_logo; ?>" />
    						<input name="pdf_logo" class='col-sm-4 form-control' type="file" data-filename-placement="inside" class="form-control" />
    						<?php } else { ?>
    							<input name="pdf_logo" class='col-sm-4 form-control' type="file" data-filename-placement="inside" class="form-control" />
    						<?php } ?>
    					</div>
                        </div>

                        <div class="form-group">
    						<label for="company_name" class="col-sm-4 control-label">Active Color:<br></label>
    						<div class="col-sm-8">
    						  <?php $color_array = array('AliceBlue'=>'#F0F8FF','AntiqueWhite'=>'#FAEBD7','Aqua'=>'#00FFFF','Aquamarine'=>'#7FFFD4','Azure'=>'#F0FFFF','Beige'=>'#F5F5DC','Bisque'=>'#FFE4C4','Black'=>'#000000','BlanchedAlmond'=>'#FFEBCD','Blue'=>'#0000FF','BlueViolet'=>'#8A2BE2','Brown'=>'#A52A2A','BurlyWood'=>'#DEB887','CadetBlue'=>'#5F9EA0','Chartreuse'=>'#7FFF00','Chocolate'=>'#D2691E','Coral'=>'#FF7F50','CornflowerBlue'=>'#6495ED','Cornsilk'=>'#FFF8DC','Crimson'=>'#DC143C','Cyan'=>'#00FFFF','darkBlue'=>'#00008B','darkCyan'=>'#008B8B','darkGoldenRod'=>'#B8860B','darkGray'=>'#A9A9A9','darkGrey'=>'#A9A9A9','darkGreen'=>'#006400','darkKhaki'=>'#BDB76B','darkMagenta'=>'#8B008B','darkOliveGreen'=>'#556B2F','darkOrange'=>'#FF8C00','darkOrchid'=>'#9932CC','darkRed'=>'#8B0000','darkSalmon'=>'#E9967A','darkSeaGreen'=>'#8FBC8F','darkSlateBlue'=>'#483D8B','darkSlateGray'=>'#2F4F4F','darkSlateGrey'=>'#2F4F4F','darkTurquoise'=>'#00CED1','darkViolet'=>'#9400D3','deepPink'=>'#FF1493','deepSkyBlue'=>'#00BFFF','dimGray'=>'#696969','dimGrey'=>'#696969','dodgerBlue'=>'#1E90FF','FireBrick'=>'#B22222','FloralWhite'=>'#FFFAF0','ForestGreen'=>'#228B22','Fuchsia'=>'#FF00FF','Gainsboro'=>'#DCDCDC','GhostWhite'=>'#F8F8FF','Gold'=>'#FFD700','GoldenRod'=>'#DAA520','Gray'=>'#808080','Grey'=>'#808080','Green'=>'#008000','GreenYellow'=>'#ADFF2F','HoneyDew'=>'#F0FFF0','HotPink'=>'#FF69B4','IndianRed '=>'#CD5C5C','Indigo '=>'#4B0082','Ivory'=>'#FFFFF0','Khaki'=>'#F0E68C','Lavender'=>'#E6E6FA','LavenderBlush'=>'#FFF0F5','LawnGreen'=>'#7CFC00','LemonChiffon'=>'#FFFACD','LightBlue'=>'#ADD8E6','LightCoral'=>'#F08080','LightCyan'=>'#E0FFFF','LightGoldenRodYellow'=>'#FAFAD2','LightGray'=>'#D3D3D3','LightGrey'=>'#D3D3D3','LightGreen'=>'#90EE90','LightPink'=>'#FFB6C1','LightSalmon'=>'#FFA07A','LightSeaGreen'=>'#20B2AA','LightSkyBlue'=>'#87CEFA','LightSlateGray'=>'#778899','LightSlateGrey'=>'#778899','LightSteelBlue'=>'#B0C4DE','LightYellow'=>'#FFFFE0','Lime'=>'#00FF00','LimeGreen'=>'#32CD32','Linen'=>'#FAF0E6','Magenta'=>'#FF00FF','Maroon'=>'#800000','MediumAquaMarine'=>'#66CDAA','MediumBlue'=>'#0000CD','MediumOrchid'=>'#BA55D3','MediumPurple'=>'#9370DB','MediumSeaGreen'=>'#3CB371','MediumSlateBlue'=>'#7B68EE','MediumSpringGreen'=>'#00FA9A','MediumTurquoise'=>'#48D1CC','MediumVioletRed'=>'#C71585','MidnightBlue'=>'#191970','MintCream'=>'#F5FFFA','MistyRose'=>'#FFE4E1','Moccasin'=>'#FFE4B5','NavajoWhite'=>'#FFDEAD','Navy'=>'#000080','OldLace'=>'#FDF5E6','Olive'=>'#808000','OliveDrab'=>'#6B8E23','Orange'=>'#FFA500','OrangeRed'=>'#FF4500','Orchid'=>'#DA70D6','PaleGoldenRod'=>'#EEE8AA','PaleGreen'=>'#98FB98','PaleTurquoise'=>'#AFEEEE','PaleVioletRed'=>'#DB7093','PapayaWhip'=>'#FFEFD5','PeachPuff'=>'#FFDAB9','Peru'=>'#CD853F','Pink'=>'#FFC0CB','Plum'=>'#DDA0DD','PowderBlue'=>'#B0E0E6','Purple'=>'#800080','RebeccaPurple'=>'#663399','Red'=>'#FF0000','RosyBrown'=>'#BC8F8F','RoyalBlue'=>'#4169E1','SaddleBrown'=>'#8B4513','Salmon'=>'#FA8072','SandyBrown'=>'#F4A460','SeaGreen'=>'#2E8B57','SeaShell'=>'#FFF5EE','Sienna'=>'#A0522D','Silver'=>'#C0C0C0','SkyBlue'=>'#87CEEB','SlateBlue'=>'#6A5ACD','SlateGray'=>'#708090','SlateGrey'=>'#708090','Snow'=>'#FFFAFA','SpringGreen'=>'#00FF7F','SteelBlue'=>'#4682B4','Tan'=>'#D2B48C','Teal'=>'#008080','Thistle'=>'#D8BFD8','Tomato'=>'#FF6347','Turquoise'=>'#40E0D0','Violet'=>'#EE82EE','Wheat'=>'#F5DEB3','White'=>'#FFFFFF','WhiteSmoke'=>'#F5F5F5','Yellow'=>'#FFFF00','YellowGreen'=>'#9ACD32'); 
    						  ksort($color_array);
    						  ?>
    						  <select name="active_color" class="form-control">
    							<option value="">Select a Color</option>
    							<?php foreach($color_array as $color => $code): ?>
    								<?php $selected = ''; ?>
    								<?php if($active_color == $code): ?>
    									<?php $selected = "selected='selected'"; ?>
    								<?php endif; ?>
    								<option <?php echo $selected; ?> value="<?php echo $code; ?>"><?php echo $color; ?></option>
    							<?php endforeach; ?>
    						  </select>
    						</div>
                        </div>
                        <div class="form-group">
    						<label for="font" class="col-sm-4 control-label">Font:<br></label>
    						<div class="col-sm-8">
    							<?php
    								$font_array = array('courier'=>'Courier','courierB'=>'Courier Bold','courierBI'=>'Courier Bold Italic','courierI'=>'Courier Italic','helvetica'=>'Helvetica','helveticaB'=>'Helvetica Bold','helveticaBI'=>'Helvetica Bold Italic','helveticaI'=>'Helvetica Italic','symbol'=>'Symbol','times'=>'Times New Roman','timesB'=>'Times New Roman Bold','timesBI'=>'Times New Roman Bold Italic','timesI'=>'Times New Roman Italic','zapfdingbats'=>'Zapf Dingbats');
    								ksort($font_array);
    							?>
    							<select name="font" class="form-control">
    								<option value="">Select a Font</option>
    								<?php foreach($font_array as $font_value => $font): ?>
    									<?php $selected = ''; ?>
    									<?php if($font_code == $font_value): ?>
    										<?php $selected = "selected='selected'"; ?>
    									<?php endif; ?>
    									<option <?php echo $selected; ?> value="<?php echo $font_value; ?>"><?php echo $font; ?></option>
    								<?php endforeach; ?>
    							</select>
    						</div>
                        </div>

    					<div class="form-group">
                        <label for="website" class="col-sm-4 control-label">Website:<br></label>
                        <div class="col-sm-8">
                          <input name="website" value="<?php echo $website_code; ?>" type="text" class="form-control">
                        </div>
                        </div>
                    <?php } else {
                        echo '<h4>This form was created in the Form Builder tile. Please configure PDF settings in the Form Builder tile by clicking <a href="'.WEBSITE_URL.'/Form Builder/edit_form.php?edit='.$form.'&tab=styling">here</a>.</h4>';
                    } ?>

                </div>
            </div>
        </div>

        <?php if ($form == "Client Business Introduction") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cbi" >
                        Client Business Introduction<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_cbi" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('client_business_introduction/field_config_client_business_introduction.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Branding Questionnaire") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_bq" >
                        Branding Questionnaire<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_bq" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('branding_questionnaire/field_config_branding_questionnaire.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Website Information Gathering") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_wig" >
                        Website Information Gathering<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_wig" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('website_information_gathering_form/field_config_website_information_gathering_form.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Blog") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_blog" >
                        Blog<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_blog" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('blog/field_config_blog.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Marketing Strategies Review") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_msr" >
                        Marketing Strategies Review<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_msr" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('marketing_strategies_review/field_config_marketing_strategies_review.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Social Media Info Gathering") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_smig" >
                        Social Media Info Gathering<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_smig" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('social_media_info_gathering/field_config_social_media_info_gathering.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Social Media Start Up Questionnaire") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_smsuq" >
                        Social Media Start Up Questionnaire<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_smsuq" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('social_media_start_up_questionnaire/field_config_social_media_start_up_questionnaire.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

        <?php if ($form == "Business Case Format") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_500" >
                        Business Case Format<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_500" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('business_case_format/field_config_business_case_format.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Product-Service Outline") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_501" >
                        Product-Service Outline<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_501" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('product_service_outline/field_config_product_service_outline.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Client Reviews") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_502" >
                        Client Reviews<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_502" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('client_reviews/field_config_client_reviews.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "SWOT") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_504" >
                        SWOT<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_504" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('swot/field_config_swot.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "GAP Analysis") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_505" >
                        GAP Analysis<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_505" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('gap_analysis/field_config_gap_analysis.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Lesson Plan") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_lp" >
                        Lesson Plan<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_lp" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('lesson_plan/field_config_lesson_plan.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Marketing Plan Information Gathering") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_506" >
                        Marketing Plan Information Gathering<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_506" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('marketing_plan_information_gathering/field_config_marketing_plan_information_gathering.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>
        <?php if ($form == "Marketing Information") {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_507" >
                        Marketing Information<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_507" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php include ('marketing_information/field_config_marketing_information.php'); ?>
                 </div>
            </div>
        </div>
        <?php } ?>

    </div>

<?php
    $category = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT category FROM manuals WHERE deleted=0 AND manual_type='infogathering' LIMIT 1"));
    $manual_category = $category['category'];
    if($manual_category == '') {
       $manual_category = 0;
    }
?>
<div class="form-group">
    <div class="col-sm-4 clearfix">
        <a href="infogathering.php?tab=Form" class="btn brand-btn btn-lg pull-left">Back</a>
		<!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
    </div>
    <div class="col-sm-8">
        <button	type="submit" name="submit"	value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>