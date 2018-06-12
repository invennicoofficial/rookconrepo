<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['add_package'])) {

	if($_POST['new_service'] != '') {
		$service_type = filter_var($_POST['new_service'],FILTER_SANITIZE_STRING);
	} else {
		$service_type = filter_var($_POST['service_type'],FILTER_SANITIZE_STRING);
	}
    $invoice_description = filter_var(htmlentities($_POST['invoice_description']),FILTER_SANITIZE_STRING);
    $ticket_description = filter_var(htmlentities($_POST['ticket_description']),FILTER_SANITIZE_STRING);

	if($_POST['new_category'] != '') {
		$category = filter_var($_POST['new_category'],FILTER_SANITIZE_STRING);
	} else {
		$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	}
    $heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);

    $cost = filter_var($_POST['cost'],FILTER_SANITIZE_STRING);
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
    $estimated_hours = filter_var($_POST['estimated_hours'],FILTER_SANITIZE_STRING);
    $actual_hours = filter_var($_POST['actual_hours'],FILTER_SANITIZE_STRING);
    $msrp = filter_var($_POST['msrp'],FILTER_SANITIZE_STRING);

    $j=0;
    $assign_services = '';
    if(!empty($_POST['assign_services'])) {
        foreach ($_POST['assign_services'] as $assign_services_all) {
            $assign_services .= $assign_services_all.'#'.$_POST['assign_services_quantity'][$j].'**';
            $j++;
        }
    }
    $assign_clients = implode('**',$_POST['assign_clients']);
    $assign_customer = implode('**',$_POST['assign_customer']);

    $j=0;
    $assign_vendor = '';
    if(!empty($_POST['assign_vendor'])) {
        foreach ($_POST['assign_vendor'] as $assign_vendor_all) {
            $assign_vendor .= $assign_vendor_all.'#'.$_POST['assign_vendor_quantity'][$j].'**';
            $j++;
        }
    }

    $j=0;
    $assign_inventory = '';
    if(!empty($_POST['assign_inventory'])) {
        foreach ($_POST['assign_inventory'] as $assign_inventory_all) {
            $assign_inventory .= $assign_inventory_all.'#'.$_POST['assign_inventory_quantity'][$j].'**';
            $j++;
        }
    }
    $j=0;
    $assign_equipment = '';
    if(!empty($_POST['assign_equipment'])) {
        foreach ($_POST['assign_equipment'] as $assign_equipment_all) {
            $assign_equipment .= $assign_equipment_all.'#'.$_POST['assign_equipment_quantity'][$j].'**';
            $j++;
        }
    }
    $j=0;
    $assign_staff = '';
    if(!empty($_POST['assign_staff'])) {
        foreach ($_POST['assign_staff'] as $assign_staff_all) {
            $assign_staff .= $assign_staff_all.'#'.$_POST['assign_staff_quantity'][$j].'**';
            $j++;
        }
    }

    $j=0;
    $assign_contractor = '';
    if(!empty($_POST['assign_contractor'])) {
        foreach ($_POST['assign_contractor'] as $assign_contractor_all) {
            $assign_contractor .= $assign_contractor_all.'#'.$_POST['assign_contractor_quantity'][$j].'**';
            $j++;
        }
    }

    if(empty($_POST['packageid'])) {
        $query_insert_vendor = "INSERT INTO `package` (`service_type`, `category`, `heading`, `cost`, `description`, `quote_description`, `invoice_description`, `ticket_description`, `final_retail_price`, `admin_price`, `wholesale_price`, `commercial_price`, `client_price`, `minimum_billable`, `estimated_hours`, `actual_hours`, `msrp`, `assign_staff`, `assign_contractor`, `assign_clients`, `assign_vendor`, `assign_customer`, `assign_inventory`, `assign_equipment`, `assign_services`) VALUES ('$service_type', '$category', '$heading', '$cost', '$description', '$quote_description', '$invoice_description', '$ticket_description', '$final_retail_price', '$admin_price', '$wholesale_price', '$commercial_price', '$client_price', '$minimum_billable', '$estimated_hours', '$actual_hours', '$msrp', '$assign_staff', '$assign_contractor', '$assign_clients', '$assign_vendor', '$assign_customer', '$assign_inventory', '$assign_equipment', '$assign_services')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $url = 'Added';
    } else {
        $packageid = $_POST['packageid'];
        $query_update_vendor = "UPDATE `package` SET `service_type` = '$service_type', `category` = '$category', `heading` = '$heading', `cost` = '$cost', `description` = '$description', `quote_description` = '$quote_description', `invoice_description` = '$invoice_description', `ticket_description` = '$ticket_description', `final_retail_price` = '$final_retail_price', `admin_price` = '$admin_price', `wholesale_price` = '$wholesale_price', `commercial_price` = '$commercial_price', `client_price` = '$client_price', `minimum_billable` = '$minimum_billable', `estimated_hours` = '$estimated_hours', `actual_hours` = '$actual_hours', `msrp` = '$msrp', `assign_staff` = '$assign_staff', `assign_contractor` = '$assign_contractor', `assign_clients` = '$assign_clients', `assign_vendor` = '$assign_vendor', `assign_customer` = '$assign_customer', `assign_inventory` = '$assign_inventory', `assign_equipment` = '$assign_equipment', `assign_services` = '$assign_services' WHERE `packageid` = '$packageid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("package.php"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var service_type = $("#service_type").val();
        var category = $("input[name=category]").val();
        var heading = $("input[name=heading]").val();
        if (service_type == '' || category == '' || heading == '' ) {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });

    $("#service_type").change(function() {
        if($("#service_type option:selected").text() == 'New Service') {
                $( "#new_service" ).show();
        } else {
            $( "#new_service" ).hide();
        }
    });

    $("#category").change(function() {
        if($("#category option:selected").text() == 'New Category') {
                $( "#new_category" ).show();
        } else {
            $( "#new_category" ).hide();
        }
    });

});
</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('package');
?>
<div class="container">
  <div class="row">

    <h1>Packages</h1>
	<div class="pad-left gap-top double-gap-bottom"><a href="package.php" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post"	action="add_package.php" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT package FROM field_config"));
        $value_config = ','.$get_field_config['package'].',';

        $service_type = '';
        $category = '';
        $heading = '';
        $cost = '';
        $description = '';
        $quote_description = '';
        $invoice_description = '';
        $ticket_description = '';
        $final_retail_price = '';
        $admin_price = '';
        $wholesale_price = '';
        $commercial_price = '';
        $client_price = '';
        $minimum_billable = '';
        $estimated_hours = '';
        $actual_hours = '';
        $msrp = '';
        $assign_staff = '';
        $assign_contractor = '';
        $assign_clients = '';
        $assign_vendor = '';
        $assign_customer = '';
        $assign_inventory = '';
        $assign_equipment = '';
        $assign_services = '';

        if(!empty($_GET['packageid'])) {

            $packageid = $_GET['packageid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM package WHERE packageid='$packageid'"));

            $service_type = $get_contact['service_type'];
            $category = $get_contact['category'];
            $heading = $get_contact['heading'];
            $cost = $get_contact['cost'];
            $description = $get_contact['description'];
            $quote_description = $get_contact['quote_description'];
            $invoice_description = $get_contact['invoice_description'];
            $ticket_description = $get_contact['ticket_description'];

            $final_retail_price = $get_contact['final_retail_price'];
            $admin_price = $get_contact['admin_price'];
            $wholesale_price = $get_contact['wholesale_price'];
            $commercial_price = $get_contact['commercial_price'];
            $client_price = $get_contact['client_price'];
            $minimum_billable = $get_contact['minimum_billable'];
            $estimated_hours = $get_contact['estimated_hours'];
            $actual_hours = $get_contact['actual_hours'];
            $msrp = $get_contact['msrp'];

            $assign_staff = $get_contact['assign_staff'];
            $assign_contractor = $get_contact['assign_contractor'];
            $assign_clients = $get_contact['assign_clients'];
            $assign_vendor = $get_contact['assign_vendor'];
            $assign_customer = $get_contact['assign_customer'];
            $assign_inventory = $get_contact['assign_inventory'];
            $assign_equipment = $get_contact['assign_equipment'];
            $assign_services = $get_contact['assign_services'];

        ?>
        <input type="hidden" id="packageid" name="packageid" value="<?php echo $packageid ?>" />
        <?php   }      ?>

        <div class="panel-group" id="accordion2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
                            Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_info" class="panel-collapse collapse">
                    <div class="panel-body">

                        <?php if (strpos($value_config, ','."Service Type".',') !== FALSE) { ?>
                       <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Service Type<span class="hp-red">*</span>:</label>
                        <div class="col-sm-8">
                            <select id="service_type" name="service_type" class="chosen-select-deselect form-control" width="380">
                                <option value=''></option>
                                <?php
                                $query = mysqli_query($dbc,"SELECT distinct(service_type) FROM package order by service_type");
                                while($row = mysqli_fetch_array($query)) {
                                    if ($service_type == $row['service_type']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value='". $row['service_type']."'>".$row['service_type'].'</option>';

                                }
                                echo "<option value = 'Other'>New Service</option>";
                                ?>
                            </select>
                        </div>
                      </div>

                       <div class="form-group" id="new_service" style="display: none;">
                        <label for="travel_task" class="col-sm-4 control-label">New Service Name
                        </label>
                        <div class="col-sm-8">
                            <input name="new_service" type="text" class="form-control" />
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>
                       <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
                        <div class="col-sm-8">
                            <select id="category" name="category" class="chosen-select-deselect form-control" width="380">
                                <option value=''></option>
                                <?php
                                $query = mysqli_query($dbc,"SELECT distinct(category) FROM package order by category");
                                while($row = mysqli_fetch_array($query)) {
                                    if ($category == $row['category']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';

                                }
                                echo "<option value = 'Other'>New Category</option>";
                                ?>
                            </select>
                        </div>
                      </div>

                       <div class="form-group" id="new_category" style="display: none;">
                        <label for="travel_task" class="col-sm-4 control-label">New Category Name:
                        </label>
                        <div class="col-sm-8">
                            <input name="new_category" type="text" class="form-control" />
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

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_desc" >
                            Description<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_desc" class="panel-collapse collapse">
                    <div class="panel-body">

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

                    </div>
                </div>
            </div>

            <?php if (strpos($value_config, ','."Assign Services".',') !== FALSE) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_services" >
                            Service<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_services" class="panel-collapse collapse">
                    <div class="panel-body">

                        <?php
                        include ('add_package_services.php');
                        ?>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Assign Clients".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_client" >
                            Client<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_client" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                        include ('add_package_clients.php');
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Assign Customer".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_customer" >
                            Customer<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_customer" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                        include ('add_package_customer.php');
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Assign Vendor".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_vendor" >
                            Vendor<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_vendor" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                        include ('add_package_vendor.php');
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Assign Inventory".',') !== FALSE) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_inv" >
                            Inventory<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_inv" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                        include ('add_package_inventory.php');
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Assign Equipment".',') !== FALSE) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_equipment" >
                            Equipment<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_equipment" class="panel-collapse collapse">
                    <div class="panel-body">

                        <?php
                        include ('add_package_equipment.php');
                        ?>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Assign Equipment by Category".',') !== FALSE) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_eq_cat" >
                            Equipment by Category<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_eq_cat" class="panel-collapse collapse">
                    <div class="panel-body">

                        <?php
                        include ('add_package_equipment_category.php');
                        ?>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Assign Staff".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff" >
                            Staff<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_staff" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                        include ('add_package_staff.php');
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Assign Staff Position".',') !== FALSE) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff_pos" >
                            Staff Position<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_staff_pos" class="panel-collapse collapse">
                    <div class="panel-body">

                        <?php
                        include ('add_package_staff_position.php');
                        ?>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Assign Contractor".',') !== FALSE) { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cont" >
                            Contractor<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_cont" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                        include ('add_package_contractor.php');
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cost" >
                            Cost<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_cost" class="panel-collapse collapse">
                    <div class="panel-body">

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
                        <label for="company_name" class="col-sm-4 control-label">Minimum Billable:</label>
                        <div class="col-sm-8">
                          <input name="minimum_billable" value="<?php echo $minimum_billable; ?>" type="text" class="form-control">
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

                    </div>
                </div>
            </div>

        </div>

        <div class="form-group">
          <div class="col-sm-4">
              <p><span class="hp-red"><em>Required Fields *</em></span></p>
          </div>
          <div class="col-sm-8"></div>
        </div>

        <div class="form-group">
            <div class="col-sm-6">
                <a href="package.php" class="btn brand-btn btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
			</div>
			<div class="col-sm-6">
				<button type="submit" name="add_package" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
			</div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
