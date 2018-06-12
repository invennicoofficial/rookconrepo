<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['add_password'])) {
    $businessid = $_POST['businessid'];
	if($_POST['new_password'] != '') {
		$password_type = filter_var($_POST['new_password'],FILTER_SANITIZE_STRING);
	} else {
		$password_type = filter_var($_POST['password_type'],FILTER_SANITIZE_STRING);
	}

	if($_POST['new_category'] != '') {
		$category = filter_var($_POST['new_category'],FILTER_SANITIZE_STRING);
	} else {
		$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	}
    $invoice_description = filter_var(htmlentities($_POST['invoice_description']),FILTER_SANITIZE_STRING);
    $ticket_description = filter_var(htmlentities($_POST['ticket_description']),FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $fee = filter_var($_POST['fee'],FILTER_SANITIZE_STRING);

    $password_code = filter_var($_POST['password_code'],FILTER_SANITIZE_STRING);
    //$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
    $heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
    $cost = filter_var($_POST['cost'],FILTER_SANITIZE_STRING);
    //$description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);

    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);

    if($_POST['same_desc'] == 1) {
        $quote_description = $description;
    } else {
        $quote_description = filter_var(htmlentities($_POST['quote_description']),FILTER_SANITIZE_STRING);
    }
    $final_retail_price = filter_var($_POST['final_retail_price'],FILTER_SANITIZE_STRING);
    $admin_price = filter_var($_POST['admin_price'],FILTER_SANITIZE_STRING);
    $wholesale_price = filter_var($_POST['wholesale_price'],FILTER_SANITIZE_STRING);
    $commercial_price = filter_var($_POST['commercial_price'],FILTER_SANITIZE_STRING);
    $client_price = filter_var($_POST['client_price'],FILTER_SANITIZE_STRING);
    $minimum_billable = filter_var($_POST['minimum_billable'],FILTER_SANITIZE_STRING);
    $hourly_rate = filter_var($_POST['hourly_rate'],FILTER_SANITIZE_STRING);
    $estimated_hours = filter_var($_POST['estimated_hours'],FILTER_SANITIZE_STRING);
    $actual_hours = filter_var($_POST['actual_hours'],FILTER_SANITIZE_STRING);
    $msrp = filter_var($_POST['msrp'],FILTER_SANITIZE_STRING);

    $unit_price = filter_var($_POST['unit_price'],FILTER_SANITIZE_STRING);
    $unit_cost = filter_var($_POST['unit_cost'],FILTER_SANITIZE_STRING);
    $rent_price = filter_var($_POST['rent_price'],FILTER_SANITIZE_STRING);
    $rental_days = filter_var($_POST['rental_days'],FILTER_SANITIZE_STRING);
    $rental_weeks = filter_var($_POST['rental_weeks'],FILTER_SANITIZE_STRING);
    $rental_months = filter_var($_POST['rental_months'],FILTER_SANITIZE_STRING);
    $rental_years = filter_var($_POST['rental_years'],FILTER_SANITIZE_STRING);
    $reminder_alert = filter_var($_POST['reminder_alert'],FILTER_SANITIZE_STRING);
    $daily = filter_var($_POST['daily'],FILTER_SANITIZE_STRING);
    $weekly = filter_var($_POST['weekly'],FILTER_SANITIZE_STRING);
    $monthly = filter_var($_POST['monthly'],FILTER_SANITIZE_STRING);
    $annually = filter_var($_POST['annually'],FILTER_SANITIZE_STRING);
    $total_days = filter_var($_POST['total_days'],FILTER_SANITIZE_STRING);
    $total_hours = filter_var($_POST['total_hours'],FILTER_SANITIZE_STRING);
    $total_km = filter_var($_POST['total_km'],FILTER_SANITIZE_STRING);
    $total_miles = filter_var($_POST['total_miles'],FILTER_SANITIZE_STRING);

    if(empty($_POST['passwordid'])) {
        $query_insert_vendor = "INSERT INTO `passwords` (`businessid`, `password_type`, `category`, `password_code`, `heading`, `cost`, `description`, `quote_description`, `invoice_description`, `ticket_description`, `final_retail_price`, `admin_price`, `wholesale_price`, `commercial_price`, `client_price`, `minimum_billable`, `hourly_rate`, `estimated_hours`, `actual_hours`, `msrp`, `name`, `fee`, `unit_price`, `unit_cost`, `rent_price`, `rental_days`, `rental_weeks`, `rental_months`, `rental_years`, `reminder_alert`, `daily`,`weekly`, `monthly`, `annually`,  `total_days`, `total_hours`, `total_km`, `total_miles`) VALUES ('$businessid', '$password_type', '$category', '$password_code', '$heading', '$cost', '$description', '$quote_description', '$invoice_description', '$ticket_description', '$final_retail_price', '$admin_price', '$wholesale_price', '$commercial_price', '$client_price', '$minimum_billable', '$hourly_rate', '$estimated_hours', '$actual_hours', '$msrp', '$name', '$fee', '$unit_price', '$unit_cost', '$rent_price', '$rental_days', '$rental_weeks', '$rental_months', '$rental_years', '$reminder_alert', '$daily', '$weekly', '$monthly', '$annually', '$total_days', '$total_hours', '$total_km', '$total_miles')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $url = 'Added';
    } else {
        $passwordid = $_POST['passwordid'];
        $query_update_vendor = "UPDATE `passwords` SET `businessid` = '$businessid', `password_type` = '$password_type', `category` = '$category',`password_code` = '$password_code', `heading` = '$heading', `cost` = '$cost', `description` = '$description', `quote_description` = '$quote_description', `invoice_description` = '$invoice_description', `ticket_description` = '$ticket_description', `final_retail_price` = '$final_retail_price', `admin_price` = '$admin_price', `wholesale_price` = '$wholesale_price', `commercial_price` = '$commercial_price', `client_price` = '$client_price', `minimum_billable` = '$minimum_billable', `hourly_rate` = '$hourly_rate', `estimated_hours` = '$estimated_hours', `actual_hours` = '$actual_hours', `msrp` = '$msrp', `name` = '$name', `fee` = '$fee', `unit_price` = '$unit_price', `unit_cost` = '$unit_cost', `rent_price` = '$rent_price', `rental_days` = '$rental_days', `rental_weeks` = '$rental_weeks', `rental_months` = '$rental_months', `rental_years` = '$rental_years', `reminder_alert` = '$reminder_alert', `daily` = '$daily', `weekly` = '$weekly', `monthly` = '$monthly', `annually` = '$annually', `total_days` = '$total_days', `total_hours` = '$total_hours', `total_km` = '$total_km', `total_miles` = '$total_miles' WHERE `passwordid` = '$passwordid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("passwords.php"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var password_type = $("#password_type").val();
        var category = $("input[name=category]").val();
        var heading = $("input[name=heading]").val();
        if (password_type == '' || category == '' || heading == '' ) {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });

    $("#password_type").change(function() {
        if($("#password_type option:selected").text() == 'New Password') {
                $( "#new_password" ).show();
        } else {
            $( "#new_password" ).hide();
        }
    });

    $("#category").change(function() {
        if($("#category option:selected").text() == 'New Category') {
                $( "#new_category" ).show();
        } else {
            $( "#new_category" ).hide();
        }
    });

} );

</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('passwords');
?>
<div class="container">
  <div class="row">

    <h1>Passwords</h1>
	<div class="pad-left gap-top double-gap-bottom"><a href="passwords.php" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post"	action="add_passwords.php" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT passwords FROM field_config"));
        $value_config = ','.$get_field_config['passwords'].',';

        $businessid = '';
        $password_type = '';
        $category = '';
        $password_code = '';
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
        $hourly_rate = '';
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

        if(!empty($_GET['passwordid'])) {

            $passwordid = $_GET['passwordid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM passwords WHERE passwordid='$passwordid'"));

            $businessid = $get_contact['businessid'];
            $password_type = $get_contact['password_type'];
            $category = $get_contact['category'];
            $password_code = $get_contact['password_code'];
            $heading = $get_contact['heading'];
            $cost = $get_contact['cost'];
            $description = $get_contact['description'];
            $quote_description = $get_contact['quote_description'];
            $invoice_description = $get_contact['invoice_description'];
            $ticket_description = $get_contact['ticket_description'];
            $name = $get_contact['name'];
            $fee = $get_contact['fee'];

            $final_retail_price = $get_contact['final_retail_price'];
            $admin_price = $get_contact['admin_price'];
            $wholesale_price = $get_contact['wholesale_price'];
            $commercial_price = $get_contact['commercial_price'];
            $client_price = $get_contact['client_price'];
            $minimum_billable = $get_contact['minimum_billable'];
            $hourly_rate = $get_contact['hourly_rate'];
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
        <input type="hidden" id="passwordid" name="passwordid" value="<?php echo $passwordid ?>" />
        <?php   }      ?>

        <?php if (strpos($value_config, ','."Business".',') !== FALSE) { ?>
        <div class="form-group clearfix completion_date">
            <label for="first_name" class="col-sm-4 control-label text-right">Business:</label>
            <div class="col-sm-8">
                <select name="businessid" data-placeholder="Select an Option..." class="chosen-select-deselect form-control" width="380">
                    <option value=''></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE (category='Business') AND deleted=0 order by name");

                    $business_list = [];

                    while($row = mysqli_fetch_array($query)) {
                        $business_list[$row['contactid']] = decryptIt($row['name']);
                    }
                    asort($business_list);

                    foreach($business_list as $key => $value) {
                        if ($businessid == $key) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $key."'>".$value.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php } ?>

        <?php if (strpos($value_config, ','."Password Type".',') !== FALSE) { ?>
       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Password Type<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
            <select id="password_type" name="password_type" class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <?php
                $query = mysqli_query($dbc,"SELECT distinct(password_type) FROM passwords");
                while($row = mysqli_fetch_array($query)) {
                    if ($password_type == $row['password_type']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['password_type']."'>".$row['password_type'].'</option>';

                }
                echo "<option value = 'Other'>New Password</option>";
                ?>
            </select>
        </div>
      </div>

       <div class="form-group" id="new_password" style="display: none;">
        <label for="travel_task" class="col-sm-4 control-label">New Password Name:
        </label>
        <div class="col-sm-8">
            <input name="new_password" type="text" class="form-control" />
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>

        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Category:</label>
            <div class="col-sm-8">
                <select data-placeholder="Select a Category..." name="category" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $tabs = get_config($dbc, 'password_category');
                    $each_tab = explode(',', $tabs);
                    foreach ($each_tab as $cat_tab) {
                        if ($category == $cat_tab) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                    }
                  ?>
                </select>
            </div>
        </div>

      <?php } ?>

       <?php if (strpos($value_config, ','."Heading".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Heading<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="heading" value="<?php echo $heading; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Name".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Name<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="name" value="<?php echo $name; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Password Code".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Password Code:</label>
        <div class="col-sm-8">
          <input name="password_code" value="<?php echo $password_code; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Description:</label>
        <div class="col-sm-8">
          <textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label"></label>
        <div class="col-sm-8">
          <input type="checkbox" value="1" name="same_desc">Check this if Quote Description is same as Description.
        </div>
      </div>

      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Quote Description:</label>
        <div class="col-sm-8">
          <textarea name="quote_description" rows="5" cols="50" class="form-control"><?php echo $quote_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Invoice Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Invoice Description:</label>
        <div class="col-sm-8">
          <textarea name="invoice_description" rows="5" cols="50" class="form-control"><?php echo $invoice_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Ticket Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label"><?= TICKET_NOUN ?> Description:</label>
        <div class="col-sm-8">
          <textarea name="ticket_description" rows="5" cols="50" class="form-control"><?php echo $ticket_description; ?></textarea>
        </div>
      </div>
      <?php } ?>

       <?php if (strpos($value_config, ','."Fee".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Fee<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="fee" value="<?php echo $fee; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Cost:</label>
        <div class="col-sm-8">
          <input name="cost" value="<?php echo $cost; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Final Retail Price:</label>
        <div class="col-sm-8">
          <input name="final_retail_price" value="<?php echo $final_retail_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

        <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Admin Price:</label>
        <div class="col-sm-8">
          <input name="admin_price" value="<?php echo $admin_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Wholesale Price:</label>
        <div class="col-sm-8">
          <input name="wholesale_price" value="<?php echo $wholesale_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Commercial Price:</label>
        <div class="col-sm-8">
          <input name="commercial_price" value="<?php echo $commercial_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Client Price:</label>
        <div class="col-sm-8">
          <input name="client_price" value="<?php echo $client_price; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Minimum Billable Hours:</label>
        <div class="col-sm-8">
          <input name="minimum_billable" value="<?php echo $minimum_billable; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Hourly Rate:</label>
        <div class="col-sm-8">
          <input name="hourly_rate" value="<?php echo $hourly_rate; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Estimated Hours:</label>
        <div class="col-sm-8">
          <input name="estimated_hours" value="<?php echo $estimated_hours; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Actual Hours:</label>
        <div class="col-sm-8">
          <input name="actual_hours" value="<?php echo $actual_hours; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">MSRP:</label>
        <div class="col-sm-8">
          <input name="msrp" value="<?php echo $msrp; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

    <?php if (strpos($value_config, ','."Unit Price".',') !== FALSE) { ?>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Unit Price:</label>
    <div class="col-sm-8">
      <input name="unit_price" value="<?php echo $unit_price; ?>" type="text" class="form-control">
    </div>
    </div>
    <?php } ?>

    <?php if (strpos($value_config, ','."Unit Cost".',') !== FALSE) { ?>
    <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Unit Cost:</label>
    <div class="col-sm-8">
      <input name="unit_cost" value="<?php echo $unit_cost; ?>" type="text" class="form-control">
    </div>
    </div>
    <?php } ?>

  <?php if (strpos($value_config, ','."Rent Price".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rent Price:</label>
    <div class="col-sm-8">
      <input name="rent_price" value="<?php echo $rent_price; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>


  <?php if (strpos($value_config, ','."Rental Days".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Days:</label>
    <div class="col-sm-8">
      <input name="rental_days" value="<?php echo $rental_days; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."Rental Weeks".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Weeks:</label>
    <div class="col-sm-8">
      <input name="rental_weeks" value="<?php echo $rental_weeks; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Rental Months".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Months:</label>
    <div class="col-sm-8">
      <input name="rental_months" value="<?php echo $rental_months; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Rental Years".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Rental Years:</label>
    <div class="col-sm-8">
      <input name="rental_years" value="<?php echo $rental_years; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Reminder/Alert".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Reminder/Alert:</label>
    <div class="col-sm-8">
      <input name="reminder_alert" value="<?php echo $reminder_alert; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>


  <?php if (strpos($value_config, ','."Daily".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Daily:</label>
    <div class="col-sm-8">
      <input name="daily" value="<?php echo $daily; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."Weekly".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Weekly:</label>
    <div class="col-sm-8">
      <input name="weekly" value="<?php echo $weekly; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Monthly".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Monthly:</label>
    <div class="col-sm-8">
      <input name="monthly" value="<?php echo $monthly; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."Annually".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Annually:</label>
    <div class="col-sm-8">
      <input name="annually" value="<?php echo $annually; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."#Of Days".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Days:</label>
    <div class="col-sm-8">
      <input name="total_days" value="<?php echo $total_days; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."#Of Hours".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Hours:</label>
    <div class="col-sm-8">
      <input name="total_hours" value="<?php echo $total_hours; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."#Of Kilometers".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Kilometers:</label>
    <div class="col-sm-8">
      <input name="total_km" value="<?php echo $total_km; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>
  <?php if (strpos($value_config, ','."#Of Miles".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">#Of Miles:</label>
    <div class="col-sm-8">
      <input name="total_miles" value="<?php echo $total_miles; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>

        <div class="pull-left">
            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <a href="passwords.php" class="btn brand-btn btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
		</div>
        <div class="pull-right">
            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<button type="submit" name="add_password" value="Submit" class="btn brand-btn btn-lg">Submit</button>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
