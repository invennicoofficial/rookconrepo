<?php
/*
Add Property
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {

    $same_address = 0;

    $type = filter_var($_POST['type'],FILTER_SANITIZE_STRING);
    $property_name = filter_var($_POST['property_name'],FILTER_SANITIZE_STRING);
    $short_name = filter_var($_POST['short_name'],FILTER_SANITIZE_STRING);
    $condo_corp_number = filter_var($_POST['condo_corp_number'],FILTER_SANITIZE_STRING);
    $president_name = filter_var($_POST['president_name'],FILTER_SANITIZE_STRING);
    $president_phone = filter_var($_POST['president_phone'],FILTER_SANITIZE_STRING);
    $no_of_units = filter_var($_POST['no_of_units'],FILTER_SANITIZE_STRING);
    $property_tax_id = filter_var($_POST['property_tax_id'],FILTER_SANITIZE_STRING);
    $lot_number = filter_var($_POST['lot_number'],FILTER_SANITIZE_STRING);
    $plan = filter_var($_POST['plan'],FILTER_SANITIZE_STRING);
    $block = filter_var($_POST['block'],FILTER_SANITIZE_STRING);
    $legal_desc = filter_var(htmlentities($_POST['legal_desc']),FILTER_SANITIZE_STRING);

    $site_id_hydro_water = filter_var($_POST['site_id_hydro_water'],FILTER_SANITIZE_STRING);
    $site_id_gas = filter_var($_POST['site_id_gas'],FILTER_SANITIZE_STRING);
    $site_id_electric = filter_var($_POST['site_id_electric'],FILTER_SANITIZE_STRING);

    $same_address = $_POST['same_address'];

    $office_street = filter_var($_POST['office_street'],FILTER_SANITIZE_STRING);
    $office_country = filter_var($_POST['office_country'],FILTER_SANITIZE_STRING);
    $office_city = filter_var($_POST['office_city'],FILTER_SANITIZE_STRING);
    $office_state = filter_var($_POST['office_state'],FILTER_SANITIZE_STRING);
    $office_zip = filter_var($_POST['office_zip'],FILTER_SANITIZE_STRING);

    if($same_address == 1) {
        $mail_street = filter_var($_POST['office_street'],FILTER_SANITIZE_STRING);
        $mail_country = filter_var($_POST['office_country'],FILTER_SANITIZE_STRING);
        $mail_city = filter_var($_POST['office_city'],FILTER_SANITIZE_STRING);
        $mail_state = filter_var($_POST['office_state'],FILTER_SANITIZE_STRING);
        $mail_zip = filter_var($_POST['office_zip'],FILTER_SANITIZE_STRING);
    } else {
        $mail_street = filter_var($_POST['mail_street'],FILTER_SANITIZE_STRING);
        $mail_country = filter_var($_POST['mail_country'],FILTER_SANITIZE_STRING);
        $mail_city = filter_var($_POST['mail_city'],FILTER_SANITIZE_STRING);
        $mail_state = filter_var($_POST['mail_state'],FILTER_SANITIZE_STRING);
        $mail_zip = filter_var($_POST['mail_zip'],FILTER_SANITIZE_STRING);
    }

    $cert_desc = filter_var($_POST['cert_desc'],FILTER_SANITIZE_STRING);

    $photo_db = $_FILES["file"]["name"];
    $land_title_db = $_FILES["land_title"]["name"];
    $site_plan_db = $_FILES["site_plan"]["name"];
    $cert_pdf_db = $_FILES["cert_pdf"]["name"];

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

    if(empty($_POST['propertyid'])) {
        move_uploaded_file($_FILES["file"]["tmp_name"],	"download/" . $photo_db);
        move_uploaded_file($_FILES["land_title"]["tmp_name"],	"download/" . $land_title_db);
        move_uploaded_file($_FILES["site_plan"]["tmp_name"],	"download/" . $site_plan_db);
        move_uploaded_file($_FILES["cert_pdf"]["tmp_name"],	"download/" . $cert_pdf_db);

        $query_insert_property = "INSERT INTO `properties` (`type`, `property_name`, `short_name`, `condo_corp_number`, `president_name`, `president_phone`, `no_of_units`, `property_tax_id`, `lot_number`, `plan`, `block`, `photo`, `land_title`, `site_plan`, `legal_desc`, `site_id_hydro_water`, `site_id_gas`, `site_id_electric`, `cert_desc`, `cert_pdf`, `same_address`, `mail_street`, `mail_country`, `mail_city`, `mail_state`, `mail_zip`, `office_street`, `office_country`, `office_city`, `office_state`, `office_zip`, `propertysubtradeid`) VALUES ('$type', '$property_name', '$short_name', '$condo_corp_number', '$president_name', '$president_phone', '$no_of_units', '$property_tax_id', '$lot_number', '$plan', '$block', '$photo_db', '$land_title_db', '$site_plan_db', '$legal_desc', '$site_id_hydro_water', '$site_id_gas', '$site_id_electric', '$cert_desc', '$cert_pdf_db', '$same_address', '$mail_street', '$mail_country', '$mail_city', '$mail_state', '$mail_zip', '$office_street', '$office_country', '$office_city', '$office_state', '$office_zip', '$propertysubtradeid')";

        $result_insert_property = mysqli_query($dbc, $query_insert_property);

    } else {
        $propertyid = $_POST['propertyid'];

        if($photo_db == '') {
            $photo_update =	$_POST['photo_file'];
        } else {
            $photo_update =	$photo_db;
        }

        if($land_title_db == '') {
            $land_title_update =	$_POST['land_title_file'];
        } else {
            $land_title_update =	$land_title_db;
        }

        if($site_plan_db == '') {
            $site_plan_update =	$_POST['site_plan_file'];
        } else {
            $site_plan_update =	$site_plan_db;
        }

        if($cert_pdf_db == '') {
            $cert_photo_update =	$_POST['cert_pdf_photo_file'];
        } else {
            $cert_photo_update =	$cert_pdf_db;
        }

        move_uploaded_file($_FILES["file"]["tmp_name"],	"download/" . $_FILES["file"]["name"]);
        move_uploaded_file($_FILES["land_title"]["tmp_name"],	"download/" . $_FILES["land_title"]["name"]);
        move_uploaded_file($_FILES["site_plan"]["tmp_name"],	"download/" . $_FILES["site_plan"]["name"]);
        move_uploaded_file($_FILES["cert_pdf"]["tmp_name"],	"download/" . $_FILES["cert_pdf"]["name"]);

        $query_update_property = "UPDATE `properties` SET `type` = '$type', `property_name` = '$property_name', `short_name` = '$short_name', `condo_corp_number` = '$condo_corp_number', `president_name` = '$president_name', `president_phone` = '$president_phone', `no_of_units` = '$no_of_units', `property_tax_id` = '$property_tax_id', `lot_number` = '$lot_number', `plan` = '$plan', `block` = '$block', `photo` = '$photo_update', `land_title` = '$land_title_update', `site_plan` = '$site_plan_update', `legal_desc` = '$legal_desc', `site_id_hydro_water` = '$site_id_hydro_water', `site_id_gas` = '$site_id_gas', `site_id_electric` = '$site_id_electric', `cert_desc` = '$cert_desc', `cert_pdf` = '$cert_photo_update', `same_address` = '$same_address', `mail_street` = '$mail_street', `mail_country` = '$mail_country', `mail_city` = '$mail_city', `mail_state` = '$mail_state', `mail_zip` = '$mail_zip', `office_street` = '$office_street', `office_country` = '$office_country', `office_city` = '$office_city', `office_state` = '$office_state', `office_zip` = '$office_zip', `propertysubtradeid` = '$propertysubtradeid' WHERE `propertyid` = '$propertyid'";
        $result_update_property = mysqli_query($dbc, $query_update_property);
    }

    echo '<script type="text/javascript"> window.location.replace("properties.php"); </script>';

    mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">

  $(document).ready(function() {

	$('#president_phone').keyup(function() {
		$('span.error-keyup-4').remove();
		var inputVal = $(this).val();
		var dateReg = /^[0-9]{3}\.[0-9]{3}\.[0-9]{4}$/;
		if(!dateReg.test(inputVal)) {
			$(this).after('<span class="error error-keyup-4 required">Invalid phone format.</span>');
		}
		return false;
	});

	$('#emergency_contact_phone').keyup(function() {
		$('span.error-keyup-5').remove();
		var inputVal = $(this).val();
		var dateReg = /^[0-9]{3}\.[0-9]{3}\.[0-9]{4}$/;
		if(!dateReg.test(inputVal)) {
			$(this).after('<span class="error error-keyup-5 required">Invalid phone format.</span>');
		}
		return false;
	});

    $("#same_address").change(function(){
      $("#mail_addr").toggle();
    });

	$("#type").change(function() {
		if($(this).val() == "Commercial") {
			$("#condo_corp").text("Board of Directors");
		} else {
			$("#condo_corp").text("Condo Corp");
		}
	});

  });
function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}
</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('properties');
?>
<div class="container">
  <div class="row">

        <h1 class="triple-pad-bottom">Add A New Property</h1>
        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <?php

		$type = '';
        $property_name = '';
        $short_name = '';
        $condo_corp_number = '';
        $president_name = '';
        $president_phone = '';
        $no_of_units = '';
        $property_tax_id = '';
        $lot_number = '';
        $plan = '';
        $block = '';
        $photo = '';
		$land_title = '';
		$site_plan = '';
        $legal_desc = '';
		$site_id_hydro_water = '';
		$site_id_gas = '';
		$site_id_electric = '';

        $cert_desc = '';
        $cert_pdf = '';
        $same_address = '';
        $mail_street = '';
        $mail_country = '';
        $mail_city = '';
        $mail_state = '';
        $mail_zip = '';
        $office_street = '';
        $office_country = '';
        $office_city = '';
        $office_state = '';
        $office_zip = '';
        $propertysubtradeid = '';


        if(!empty($_GET['propertyid'])) {

            $propertyid = $_GET['propertyid'];
            $get_property = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM properties WHERE propertyid='$propertyid'"));

			$type = $get_property['type'];
            $property_name = $get_property['property_name'];
            $short_name = $get_property['short_name'];
            $condo_corp_number = $get_property['condo_corp_number'];
            $president_name = $get_property['president_name'];
            $president_phone = $get_property['president_phone'];
            $no_of_units = $get_property['no_of_units'];
            $property_tax_id = $get_property['property_tax_id'];
            $lot_number = $get_property['lot_number'];
            $plan = $get_property['plan'];
            $block = $get_property['block'];
            $doc_name = $get_property['photo'];
			$land_title = $get_property['land_title'];
			$site_plan = $get_property['site_plan'];
            $legal_desc = $get_property['legal_desc'];
			$site_id_hydro_water = $get_property['site_id_hydro_water'];
			$site_id_gas = $get_property['site_id_gas'];
			$site_id_electric = $get_property['site_id_electric'];
            $cert_desc = $get_property['cert_desc'];
            $cert_pdf = $get_property['cert_pdf'];
			$same_address = $get_property['same_address'];
            $mail_street = $get_property['mail_street'];
            $mail_country = $get_property['mail_country'];
            $mail_city = $get_property['mail_city'];
            $mail_state = $get_property['mail_state'];
            $mail_zip = $get_property['mail_zip'];
            $office_street = $get_property['office_street'];
            $office_country = $get_property['office_country'];
            $office_city = $get_property['office_city'];
            $office_state = $get_property['office_state'];
            $office_zip = $get_property['office_zip'];
            $propertysubtradeid = $get_property['propertysubtradeid'];

        ?>
        <input type="hidden" id="propertyid" name="propertyid" value="<?php echo $propertyid ?>" />
        <?php   }      ?>
        <div class="panel-group" id="accordion2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
                            Property Info
                        </a>
                    </h4>
                </div>

                <div id="collapse_info" class="panel-collapse collapse">
                    <div class="panel-body">

					  <div class="form-group">
						<label for="position" class="col-sm-4 control-label">Type:</label>
						<div class="col-sm-8">
							<select data-placeholder="Choose a Type..." id="type" name="type" class="chosen-select-deselect form-control">
							  <option value=""></option>
							  <option value="Commercial" <?php if ($type=='Commercial') echo 'selected="selected"';?>>Commercial</option>
							  <option value="Residential" <?php if ($type=='Residential') echo 'selected="selected"';?>>Residential</option>
							  <option value="Hotel/Suites" <?php if ($type=='Hotel/Suites') echo 'selected="selected"';?>>Hotel/Suites</option>
							</select>
						</div>
					  </div>

                        <div class="form-group">
                        <label for="property_name" class="col-sm-4 control-label">Property Name (Location)<span class="">*</span>:</label>
                        <div class="col-sm-8">
                          <input name="property_name" type="text"  required value="<?php echo $property_name; ?>" class="form-control" />
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="president_name" class="col-sm-4 control-label">Short Name:</label>
                        <div class="col-sm-8">
                          <input name="short_name" type="text" value="<?php echo $short_name; ?>" class="form-control"/>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="position[]" class="col-sm-4 control-label"># of Units:</label>
                        <div class="col-sm-8">
                          <input name="no_of_units" type="text" maxlength="100" value="<?php echo $no_of_units; ?>"  class="form-control position" />
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="position[]" class="col-sm-4 control-label">Property Tax ID:</label>
                        <div class="col-sm-8">
                          <input name="property_tax_id" type="text" maxlength="100" value="<?php echo $property_tax_id; ?>"  class="form-control position" />
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="position[]" class="col-sm-4 control-label">Lot #:</label>
                        <div class="col-sm-8">
                          <input name="lot_number" type="text" maxlength="100" value="<?php echo $lot_number; ?>"  class="form-control position" />
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="file[]"	class="col-sm-4	control-label">Plan Number:</label>
                        <div class="col-sm-8">
							<input name="plan" type="text" onKeyUp="numericFilter(this);" value="<?php echo $plan; ?>"  class="form-control position" />
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="position[]" class="col-sm-4 control-label">Block:</label>
                        <div class="col-sm-8">
                          <input name="block" type="text" maxlength="100" value="<?php echo $block; ?>"  class="form-control position" />
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="file[]"	class="col-sm-4	control-label">Upload Map:</label>
                        <div class="col-sm-8">
						<?php if(!empty($_GET['propertyid']) && $doc_name != '') {
							echo "<a href='download/".$doc_name."' target='_blank'>View</a>";
                            ?>
                            <input type="hidden" name="photo_file" value="<?php	echo $doc_name;	?>"	/>
                          <?php	} ?>
                          <input name="file" type="file" accept="image/*" id="file" data-filename-placement="inside"	class="form-control" />
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="file[]"	class="col-sm-4	control-label">Land Title:</label>
                        <div class="col-sm-8">
						<?php if(!empty($_GET['propertyid']) && $land_title != '') {
							echo "<a href='download/".$land_title."' target='_blank'>View</a>";
                            ?>
                            <input type="hidden" name="land_title_file" value="<?php	echo $land_title;	?>"	/>
                          <?php	} ?>
                          <input name="land_title" type="file" id="file" data-filename-placement="inside"	class="form-control" />
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="file[]"	class="col-sm-4	control-label">Site Plan:</label>
                        <div class="col-sm-8">
						<?php if(!empty($_GET['propertyid']) && $site_plan != '') {
							echo "<a href='download/".$site_plan."' target='_blank'>View</a>";
                            ?>
                            <input type="hidden" name="site_plan_file" value="<?php	echo $site_plan;	?>"	/>
                          <?php	} ?>
                          <input name="site_plan" type="file" id="file" data-filename-placement="inside"	class="form-control" />
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="additional_note" class="col-sm-4 control-label">Legal Description:</label>
                        <div class="col-sm-8">
                            <textarea name="legal_desc" rows="5" cols="50" class="form-control"><?php echo $legal_desc; ?></textarea>
                        </div>
                        </div>

						<div class="form-group">
							<div class="col-sm-4 clearfix">
								<a href="properties.php" class="btn brand-btn pull-right">Back</a>
							</div>
							<div class="col-sm-8">
								<button type="submit" name="submit" value="Submit" class="btn brand-btn pull-right">Submit</button>
							</div>
						</div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_site_id" >
                            Site ID's for Common Area (CA)
                        </a>
                    </h4>
                </div>

                <div id="collapse_site_id" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                        <label for="property_name" class="col-sm-4 control-label">Hydro/Water:</label>
                        <div class="col-sm-8">
                          <input name="site_id_hydro_water" type="text"  value="<?php echo $site_id_hydro_water; ?>" class="form-control" />
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="president_name" class="col-sm-4 control-label">Gas:</label>
                        <div class="col-sm-8">
                          <input name="site_id_gas" type="text" value="<?php echo $site_id_gas; ?>" class="form-control"/>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="position[]" class="col-sm-4 control-label">Electric:</label>
                        <div class="col-sm-8">
                          <input name="site_id_electric" type="text" maxlength="100" value="<?php echo $site_id_electric; ?>"  class="form-control position" />
                        </div>
                        </div>

						<div class="form-group">
							<div class="col-sm-4 clearfix">
								<a href="properties.php" class="btn brand-btn pull-right">Back</a>
							</div>
							<div class="col-sm-8">
								<button type="submit" name="submit" value="Submit" class="btn brand-btn pull-right">Submit</button>
							</div>
						</div>

                    </div>
                </div>
            </div>

			<div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_condo" >
                            <span id="condo_corp">
							<?php
							if ($type=='Commercial') { ?>
								Board of Directors
							<?php } else { ?>
								Condo Corp
							<?php } ?>
							</span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_condo" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                        <label for="president_name" class="col-sm-4 control-label">Condo Corp #:</label>
                        <div class="col-sm-8">
                          <input name="condo_corp_number" type="text" value="<?php echo $condo_corp_number; ?>" class="form-control"/>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="president_name" class="col-sm-4 control-label">President's Name:</label>
                        <div class="col-sm-8">
                          <input name="president_name" type="text" id="president_name" value="<?php echo $president_name; ?>" class="form-control"/>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="president_phone" class="col-sm-4 control-label">President's Phone # :<br/><em>(e.g. - 123.456.7890)</em></label>
                        <div class="col-sm-8">
                          <input name="president_phone" id="president_phone" type="text" value="<?php echo $president_phone; ?>" class="form-control"/>
                        </div>
                        </div>
						<div class="form-group">
							<div class="col-sm-4 clearfix">
								<a href="properties.php" class="btn brand-btn pull-right">Back</a>
							</div>
							<div class="col-sm-8">
								<button type="submit" name="submit" value="Submit" class="btn brand-btn pull-right">Submit</button>
							</div>
						</div>

					</div>

				</div>
			</div>

			<div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cert" >
                            Certification</span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_cert" class="panel-collapse collapse">
                    <div class="panel-body">

						<div class="form-group">
							<label for="position[]" class="col-sm-4 control-label">Name:</label>
							<div class="col-sm-8">
								<input name="cert_desc" type="text" value="<?php echo $cert_desc; ?>"  class="form-control position" />
							</div>
						</div>

						<div class="form-group">
						<label for="file[]"	class="col-sm-4	control-label">Upload PDF:</label>
						<div class="col-sm-8">
						<?php if(!empty($_GET['propertyid']) && $cert_pdf != '') {
							echo "<a href='download/".$cert_pdf."' target='_blank'>View</a>"
							?>
							<input type="hidden" name="cert_pdf_photo_file" value="<?php	echo $cert_pdf;	?>"	/>
						  <?php	} ?>
						  <input name="cert_pdf" type="file" accept=".pdf" id="file" data-filename-placement="inside"	class="form-control" />
						</div>
						</div>

						<div class="form-group">
							<div class="col-sm-4 clearfix">
								<a href="properties.php" class="btn brand-btn pull-right">Back</a>
							</div>
							<div class="col-sm-8">
								<button type="submit" name="submit" value="Submit" class="btn brand-btn pull-right">Submit</button>
							</div>
						</div>
					</div>

				</div>
			</div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sales_office_address" >
                            Sales Office Address
                        </a>
                    </h4>
                </div>

                <div id="collapse_sales_office_address" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="form-group">
                        <label for="office_street" class="col-sm-4 control-label">Unit # and Street:</label>
                        <div class="col-sm-8">
                          <input name="office_street" type="text" value="<?php echo $office_street; ?>" class="form-control"/>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="office_country" class="col-sm-4 control-label">Country:</label>
                        <div class="col-sm-8">
                          <input name="office_country" type="text" value="<?php echo $office_country; ?>" class="form-control"/>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="office_city" class="col-sm-4 control-label">City:</label>
                        <div class="col-sm-8">
                          <input name="office_city" type="text" value="<?php echo $office_city; ?>" class="form-control"/>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="office_state" class="col-sm-4 control-label">State / Province:</label>
                        <div class="col-sm-8">
                          <input name="office_state" type="text" value="<?php echo $office_state; ?>" class="form-control"/>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="office_zip" class="col-sm-4 control-label">Zip / Postal Code:</label>
                        <div class="col-sm-8">
                          <input name="office_zip" type="text" value="<?php echo $office_zip; ?>" maxlength="10" class="form-control"/>
                        </div>
                        </div>

                        <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" value="1" name="same_address" value="<?php echo $same_address; ?>" id="same_address"> If suite address is same as the office address
                            </label>
                          </div>
                        </div>
                        </div>
						<div class="form-group">
							<div class="col-sm-4 clearfix">
								<a href="properties.php" class="btn brand-btn pull-right">Back</a>
							</div>
							<div class="col-sm-8">
								<button type="submit" name="submit" value="Submit" class="btn brand-btn pull-right">Submit</button>
							</div>
						</div>
                    </div>
                </div>
            </div>


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_suite_address" >
                            Suite Address
                        </a>
                    </h4>
                </div>

                <div id="collapse_suite_address" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div id="mail_addr">

                            <div class="form-group">
                              <label for="mail_street" class="col-sm-4 control-label">Street / PO Box #:</label>
                              <div class="col-sm-8">
                                <input name="mail_street" type="text" value="<?php echo $mail_street; ?>"  class="form-control"/>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="mail_country" class="col-sm-4 control-label">Country:</label>
                              <div class="col-sm-8">
                                <input name="mail_country" type="text" value="<?php echo $mail_country; ?>" class="form-control"/>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="mail_city" class="col-sm-4 control-label">City:</label>
                              <div class="col-sm-8">
                                <input name="mail_city" type="text" value="<?php echo $mail_city; ?>" class="form-control"/>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="mail_state" class="col-sm-4 control-label">State / Province:</label>
                              <div class="col-sm-8">
                                <input name="mail_state" type="text" value="<?php echo $mail_state; ?>" class="form-control"/>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="mail_zip" class="col-sm-4 control-label">Zip / Postal Code:</label>
                              <div class="col-sm-8">
                                <input name="mail_zip" type="text" value="<?php echo $mail_zip; ?>" class="form-control"/>
                              </div>
                            </div>

                        </div> <!-- END mail_addr -->
						<div class="form-group">
							<div class="col-sm-4 clearfix">
								<a href="properties.php" class="btn brand-btn pull-right">Back</a>
							</div>
							<div class="col-sm-8">
								<button type="submit" name="submit" value="Submit" class="btn brand-btn pull-right">Submit</button>
							</div>
						</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-group">
          <div class="col-sm-4">
              <p><span class="required pull-right"><em>Required Fields *</em></span></p>
          </div>
          <div class="col-sm-8"></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="properties.php" class="btn brand-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        </div>

    </form>

    </div>
  </div>

<?php include ('../footer.php'); ?>