<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['add_marketing_material'])) {
    $marketing_materialid = $_POST['marketing_materialid'];
    $name =  filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $email =  filter_var($_POST['email'],FILTER_SANITIZE_STRING);
    $message_support = $_POST['message_support'];

    $to_email = explode(',', $email);

    $query_check_credentials = "SELECT * FROM marketing_material_uploads WHERE marketing_materialid='$marketing_materialid'";
    $result = mysqli_query($dbc, $query_check_credentials);
    $meeting_attachment = '';
    while($row = mysqli_fetch_array( $result )) {
        if($row['document_link'] != '') {
            $meeting_attachment .= 'download/'.$row['document_link'].'*#FFM#*';
        }
    }

    send_email([$_POST['email_from']=>$_POST['email_name']], $to_email, '', '', $name, $message_support, $meeting_attachment);

    echo '<script type="text/javascript"> window.location.replace("marketing_material.php"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('marketing_material');
?>
<div class="container">
  <div class="row">

	<div class="col-sm-10">
		<h1>Marketing Material</h1>
	</div>
	<div class="col-sm-2 double-gap-top">
		<?php
			if(config_visible_function($dbc, 'marketing_material') == 1) {
				echo '<a href="field_config_marketing_material.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
				echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			}
		?>
	</div>

	<div class="clearfix double-gap-bottom"></div>

	<div class="gap-left double-gap-bottom"><a href="marketing_material.php" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post"	enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT marketing_material FROM field_config"));
        $value_config = ','.$get_field_config['marketing_material'].',';

        $marketing_material_type = '';
        $category = '';
        $marketing_material_code = '';
        $heading = '';
        $cost = '';
        $description = '';
        $quote_description = '';
        $invoice_description = '';
        $ticket_description = '';

        $final_retail_price = '';
        $name = '';
        $fee = '';
        $admin_price = '';
        $wholesale_price = '';
        $commercial_price = '';
        $client_price = '';
        $minimum_billable = '';
        $estimated_hours = '';
        $actual_hours = '';
        $msrp = '';

        $unit_price = '';
        $unit_cost = '';
        $rent_price = '';
        $rental_days = '';
        $rental_weeks = '';
        $rental_months = '';
        $rental_years = '';
        $reminder_alert = '';
        $daily = '';
        $weekly = '';
        $monthly = '';
        $annually = '';
        $total_days = '';
        $total_hours = '';
        $total_km = '';
        $total_miles = '';
        $title = '';

        if(!empty($_GET['marketing_materialid'])) {

            $marketing_materialid = $_GET['marketing_materialid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM marketing_material WHERE marketing_materialid='$marketing_materialid'"));

            $marketing_material_type = $get_contact['marketing_material_type'];
            $category = $get_contact['category'];
            $marketing_material_code = $get_contact['marketing_material_code'];
            $heading = $get_contact['heading'];
            $cost = $get_contact['cost'];
            $description = $get_contact['description'];
            $quote_description = $get_contact['quote_description'];
            $invoice_description = $get_contact['invoice_description'];
            $ticket_description = $get_contact['ticket_description'];
            $name = $get_contact['name'];
            $title = $get_contact['title'];
            $fee = $get_contact['fee'];

            $final_retail_price = $get_contact['final_retail_price'];
            $admin_price = $get_contact['admin_price'];
            $wholesale_price = $get_contact['wholesale_price'];
            $commercial_price = $get_contact['commercial_price'];
            $client_price = $get_contact['client_price'];
            $minimum_billable = $get_contact['minimum_billable'];
            $estimated_hours = $get_contact['estimated_hours'];
            $actual_hours = $get_contact['actual_hours'];
            $msrp = $get_contact['msrp'];

            $unit_price = $get_contact['unit_price'];
            $unit_cost = $get_contact['unit_cost'];
            $rent_price = $get_contact['rent_price'];
            $rental_days = $get_contact['rental_days'];
            $rental_weeks = $get_contact['rental_weeks'];
            $rental_months = $get_contact['rental_months'];
            $rental_years = $get_contact['rental_years'];
            $reminder_alert = $get_contact['reminder_alert'];
            $daily = $get_contact['daily'];
            $weekly = $get_contact['weekly'];
            $monthly = $get_contact['monthly'];
            $annually = $get_contact['annually'];
            $total_days = $get_contact['total_days'];
            $total_hours = $get_contact['total_hours'];
            $total_km = $get_contact['total_km'];
            $total_miles = $get_contact['total_miles'];
        ?>
        <input type="hidden" id="marketing_materialid" name="marketing_materialid" value="<?php echo $marketing_materialid ?>" />
        <?php   }      ?>

        <?php if (strpos($value_config, ','."Marketing Material Type".',') !== FALSE) { ?>
       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Marketing Material Type<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
                <?php
                echo $marketing_material_type;
                ?>
            </select>
        </div>
      </div>

      <?php } ?>

      <?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>

      <!-- <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="category" value="<?php echo $category; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      -->

       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
                <?php
                echo $category;
                ?>
            </select>
        </div>
      </div>

      <?php } ?>

       <?php if (strpos($value_config, ','."Title".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Title<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <?php echo $title; ?>
        </div>
      </div>
      <?php } ?>

    <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) {
    ?>

    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">
			<span class="popover-examples list-inline"><a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a></span>
			Upload Document(s):
        </label>
        <div class="col-sm-8">
            <?php
            if(!empty($_GET['marketing_materialid'])) {
                $query_check_credentials = "SELECT * FROM marketing_material_uploads WHERE marketing_materialid='$marketing_materialid' AND type = 'Document' ORDER BY certuploadid DESC";
                $result = mysqli_query($dbc, $query_check_credentials);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    while($row = mysqli_fetch_array($result)) {
                        $certuploadid = $row['certuploadid'];
                        echo '<ul>';
                        echo '<li><a href="download/'.$row['document_link'].'" target="_blank">'.$row['document_link'].'</a></li>';
                        echo '</ul>';
                    }
                }
            }
            ?>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($value_config, ','."Link".',') !== FALSE) {
    ?>
    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Link(s):<br><em>(e.g. - https://www.google.com)</em>
        </label>
        <div class="col-sm-8">
            <?php
            if(!empty($_GET['marketing_materialid'])) {
                $query_check_credentials = "SELECT * FROM marketing_material_uploads WHERE marketing_materialid='$marketing_materialid' AND type = 'Link' ORDER BY certuploadid DESC";
                $result = mysqli_query($dbc, $query_check_credentials);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    $link_no = 1;
                    while($row = mysqli_fetch_array($result)) {
                        $certuploadid = $row['certuploadid'];
                        echo '<ul>';
                        echo '<li><a target="_blank" href=\''.$row['document_link'].'\'">Link '.$link_no.'</a> - <a href="add_marketing_material.php?certuploadid='.$certuploadid.'&marketing_materialid='.$marketing_materialid.'"> Delete</a></li>';
                        echo '</ul>';
                        $link_no++;
                    }
                }
            }
            ?>

        </div>
    </div>
    <?php } ?>

       <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Heading:</label>
        <div class="col-sm-8">
          <?php echo $heading; ?>
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Name".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Name<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <?php echo $name; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Marketing Material Code".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Marketing Material Code:</label>
        <div class="col-sm-8">
          <?php echo $marketing_material_code; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Description:</label>
        <div class="col-sm-8">
          <?php echo $description; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Quote Description:</label>
        <div class="col-sm-8">
          <?php echo $quote_description; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Invoice Description:</label>
        <div class="col-sm-8">
          <?php echo $invoice_description; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label"><?= TICKET_NOUN ?> Description:</label>
        <div class="col-sm-8">
          <?php echo $ticket_description; ?>
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Fee".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Fee<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <?php echo $fee; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Cost:</label>
        <div class="col-sm-8">
          <?php echo $cost; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Final Retail Price:</label>
        <div class="col-sm-8">
          <?php echo $final_retail_price; ?>
        </div>
      </div>
      <?php } ?>

        <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Admin Price:</label>
        <div class="col-sm-8">
          <?php echo $admin_price; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Wholesale Price:</label>
        <div class="col-sm-8">
          <?php echo $wholesale_price; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Commercial Price:</label>
        <div class="col-sm-8">
          <?php echo $commercial_price; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Client Price:</label>
        <div class="col-sm-8">
          <?php echo $client_price; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Minimum Billable:</label>
        <div class="col-sm-8">
          <?php echo $minimum_billable; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Estimated Hours:</label>
        <div class="col-sm-8">
          <?php echo $estimated_hours; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Actual Hours:</label>
        <div class="col-sm-8">
          <?php echo $actual_hours; ?>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">MSRP:</label>
        <div class="col-sm-8">
          <?php echo $msrp; ?>
        </div>
      </div>
      <?php } ?>

    <?php if (strpos($value_config, ','."Unit Price".',') !== FALSE) { ?>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Unit Price:</label>
    <div class="col-sm-8">
      <?php echo $unit_price; ?>
    </div>
    </div>
    <?php } ?>

    <?php if (strpos($value_config, ','."Unit Cost".',') !== FALSE) { ?>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Unit Cost:</label>
    <div class="col-sm-8">
      <?php echo $unit_cost; ?>
    </div>
    </div>
    <?php } ?>

  <?php if (strpos($value_config, ','."Rent Price".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rent Price:</label>
    <div class="col-sm-8">
      <?php echo $rent_price; ?>
    </div>
  </div>
  <?php } ?>


  <?php if (strpos($value_config, ','."Rental Days".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Days:</label>
    <div class="col-sm-8">
     <?php echo $rental_days; ?>
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."Rental Weeks".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Weeks:</label>
    <div class="col-sm-8">
      <?php echo $rental_weeks; ?>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Rental Months".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Months:</label>
    <div class="col-sm-8">
      <?php echo $rental_months; ?>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Rental Years".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Years:</label>
    <div class="col-sm-8">
      <?php echo $rental_years; ?>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Reminder/Alert".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Reminder/Alert:</label>
    <div class="col-sm-8">
      <?php echo $reminder_alert; ?>
    </div>
  </div>
  <?php } ?>


  <?php if (strpos($value_config, ','."Daily".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Daily:</label>
    <div class="col-sm-8">
      <?php echo $daily; ?>
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."Weekly".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Weekly:</label>
    <div class="col-sm-8">
      <?php echo $weekly; ?>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Monthly".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Monthly:</label>
    <div class="col-sm-8">
      <?php echo $monthly; ?>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Annually".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Annually:</label>
    <div class="col-sm-8">
      <?php echo $annually; ?>
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."#Of Days".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Days:</label>
    <div class="col-sm-8">
      <?php echo $total_days; ?>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."#Of Hours".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Hours:</label>
    <div class="col-sm-8">
      <?php echo $total_hours; ?>
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."#Of Kilometers".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Kilometers:</label>
    <div class="col-sm-8">
      <?php echo $total_km; ?>
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."#Of Miles".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Miles:</label>
    <div class="col-sm-8">
      <?php echo $total_miles; ?>
    </div>
  </div>
  <?php } ?>

    <div class="form-group">
        <label for="site_name" class="col-sm-4 control-label">From Email Name:</label>
        <div class="col-sm-8">
          <input name="email_name" type="text" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>" />
        </div>
    </div>

    <div class="form-group">
        <label for="site_name" class="col-sm-4 control-label">From Email:</label>
        <div class="col-sm-8">
          <input name="email_from" type="text" class="form-control" value="<?= get_email($dbc, $_SESSION['contactid']) ?>" />
        </div>
    </div>

    <div class="form-group">
        <label for="site_name" class="col-sm-4 control-label">To Email<span class="text-red">*</span>:<br><em>(separate multiple emails with a comma)</em></label>
        <div class="col-sm-8">
          <input name="email" required type="text" class="form-control" />
        </div>
    </div>

    <div class="form-group">
        <label for="site_name" class="col-sm-4 control-label">Subject<span class="text-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="name" type="text" required class="form-control" />
        </div>
    </div>


    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Body:</label>
        <div class="col-sm-8">
            <textarea name="message_support" rows="5" cols="50" class="form-control"></textarea>
        </div>
    </div>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>

        <div class="form-group">
            <div class="col-sm-6">
                <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Clicking here will not save your entry."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a href="marketing_material.php" class="btn brand-btn btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
			</div>
			<div class="col-sm-6">
				<button type="submit" name="add_marketing_material" value="Submit" class="btn brand-btn btn-lg pull-right">Send</button>
				<span class="popover-examples list-inline pull-right" style="margin:15px 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add this entry."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			</div>
			<div class="clearfix"></div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
