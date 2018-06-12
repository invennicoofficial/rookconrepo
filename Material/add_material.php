<?php
/*
Add	Inventory
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {
    $code = filter_var($_POST['code'],FILTER_SANITIZE_STRING);

    if($_POST['category'] == 'Other') {
        $category = filter_var($_POST['category_name'],FILTER_SANITIZE_STRING);
    } else {
        $category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
    }

    if($_POST['sub_category'] == 'Other') {
        $sub_category = filter_var($_POST['sub_category_name'],FILTER_SANITIZE_STRING);
    } else {
        $sub_category = filter_var($_POST['sub_category'],FILTER_SANITIZE_STRING);
    }

    $name =	filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);

    if($_POST['same_desc'] == 1) {
        $quote_description = $description;
    } else {
        $quote_description = filter_var(htmlentities($_POST['quote_description']),FILTER_SANITIZE_STRING);
    }

    $vendorid =	$_POST['vendorid'];
    $width = filter_var($_POST['width'],FILTER_SANITIZE_STRING);
    $length = filter_var($_POST['length'],FILTER_SANITIZE_STRING);
    $units =	filter_var($_POST['units'],FILTER_SANITIZE_STRING);
    $unit_weight =	filter_var($_POST['unit_weight'],FILTER_SANITIZE_STRING);
    $weight_per_feet =	filter_var($_POST['weight_per_feet'],FILTER_SANITIZE_STRING);
    $quantity = filter_var($_POST['quantity'],FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'],FILTER_SANITIZE_STRING);

    if(empty($_POST['materialid'])) {
        $query_insert_inventory = "INSERT INTO `material` (`code`, `category`, `sub_category`, `name`, 	`description`, `quote_description`,	`vendorid`, `width`, `length`, `units`, `unit_weight`, `weight_per_feet`, `quantity`, `price`) VALUES	('$code', '$category', '$sub_category', '$name', '$description', '$quote_description', '$vendorid', '$width', '$length', '$units', '$unit_weight', '$weight_per_feet', '$quantity', '$price')";
        $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
        $url = 'Added';
    } else {
        $materialid = $_POST['materialid'];
        $query_update_inventory = "UPDATE `material` SET `code` = '$code', `category` = '$category', `sub_category` = '$sub_category', `name` = '$name', `description`	= '$description', `quote_description` = '$quote_description',	`vendorid` = '$vendorid', `width` = '$width', `length` = '$length', `units`	= '$units', `unit_weight` = '$unit_weight', `weight_per_feet` = '$weight_per_feet', `quantity` = '$quantity', `price` = '$price' WHERE `materialid` = '$materialid'";
        $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("material.php?filter=Top"); </script>';

   // mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
    $(document).ready(function () {

        $("#form1").submit(function( event ) {
            var category = $("#category").val();
            var sub_category = $("#sub_category").val();

            var code = $("input[name=code]").val();
            var name = $("input[name=name]").val();
            var category_name = $("input[name=category_name]").val();
            var sub_category_name = $("input[name=sub_category_name]").val();

            if (code == '' || category == '' || sub_category == '' || name == '') {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
            if(((category == 'Other') && (category_name == '')) || ((sub_category == 'Other') && (sub_category_name == ''))) {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
        });

        $("#category").change(function() {
            if($( "#category option:selected" ).text() == 'Other') {
                    $( "#category_name" ).show();
            } else {
                $( "#category_name" ).hide();
            }
        });
        $("#sub_category").change(function() {
            if($( "#sub_category option:selected" ).text() == 'Other') {
                    $( "#sub_category_name" ).show();
            } else {
                $( "#sub_category_name" ).hide();
            }
        });
});

</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('material');
?>
<div class="container">
  <div class="row">

		<h1>Add A New Material</h1>
		<div class="gap-top double-gap-bottom"><a href="material.php" class="btn config-btn">Back to Dashboard</a></div>

		<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

		<?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT material FROM field_config"));
        $value_config = ','.$get_field_config['material'].',';

        $code = '';
        $category = '';
		$sub_category = '';
		$description =	'';
        $quote_description = '';
        $vendorid = '';
		$width =	'';
        $length = '';
		$units	= '';
        $unit_weight = '';
        $weight_per_feet = '';
        $quantity = '';
        $price = '';
        $name = '';

		if(!empty($_GET['materialid']))	{
			$materialid = $_GET['materialid'];
			$get_inventory =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	material WHERE	materialid='$materialid'"));
            $code = $get_inventory['code'];
            $category = $get_inventory['category'];
            $sub_category	= $get_inventory['sub_category'];
            $name	= $get_inventory['name'];
            $description =	$get_inventory['description'];
            $quote_description = $get_inventory['quote_description'];
            $vendorid =	$get_inventory['vendorid'];
            $width = $get_inventory['width'];
            $length = $get_inventory['length'];
            $units =	$get_inventory['units'];
            $unit_weight =	$get_inventory['unit_weight'];
            $weight_per_feet =	$get_inventory['weight_per_feet'];
            $quantity = $get_inventory['quantity'];
            $price =  $get_inventory['price'];
 		?>
		<input type="hidden" id="materialid"	name="materialid" value="<?php echo $materialid ?>" />
		<?php	}	   ?>

          <?php if (strpos($value_config, ','."Code".',') !== FALSE) { ?>
		  <div class="form-group">
			<label for="site_name" class="col-sm-4 control-label">Code<span class="brand-color">*</span>:</label>
			<div class="col-sm-8">
			  <input name="code" type="text" value="<?php echo $code; ?>" class="form-control" />
			</div>
		  </div>
          <?php } ?>

           <?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>
           <div class="form-group">
            <label for="travel_task" class="col-sm-4 control-label">Category<span class="brand-color">*</span>:</label>
            <div class="col-sm-8">
              <select id="category" name="category" class="chosen-select-deselect form-control" width="380">
              <option value=''></option>
                  <?php
                    $result = mysqli_query($dbc, "SELECT distinct(category) FROM material where deleted = 0");
                    while($row = mysqli_fetch_assoc($result)) {
                        if ($category == $row['category']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value = '".$row['category']."'>".$row['category']."</option>";
                    }
                  ?>
                  <option value = 'Other'>Other</option>
              </select>
            </div>
          </div>

           <div class="form-group">
            <label for="travel_task" class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
                <input name="category_name" id="category_name" type="text" class="form-control" style="display: none;"/>
            </div>
          </div>
          <?php } ?>

           <?php if (strpos($value_config, ','."Sub-Category".',') !== FALSE) { ?>
           <div class="form-group">
            <label for="travel_task" class="col-sm-4 control-label">Sub-Category<span class="brand-color">*</span>:</label>
            <div class="col-sm-8">
              <select id="sub_category" name="sub_category" class="chosen-select-deselect form-control" width="380">
              <option value=''></option>
                  <?php
                    $result = mysqli_query($dbc, "SELECT distinct(sub_category) FROM inventory where deleted = 0");
                    while($row = mysqli_fetch_assoc($result)) {
                        if ($sub_category == $row['sub_category']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value = '".$row['sub_category']."'>".$row['sub_category']."</option>";
                    }
                  ?>
                  <option value = 'Other'>Other</option>
              </select>
            </div>
          </div>

           <div class="form-group">
            <label for="travel_task" class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
                <input name="sub_category_name" id="sub_category_name" type="text" class="form-control" style="display: none;"/>
            </div>
          </div>
          <?php } ?>

           <?php if (strpos($value_config, ','."Material Name".',') !== FALSE) { ?>
           <div class="form-group">
			<label for="site_name" class="col-sm-4 control-label">Material Name<span class="brand-color">*</span>:</label>
			<div class="col-sm-8">
			  <input name="name" type="text" value="<?php echo $name; ?>" class="form-control" />
			</div>
		  </div>
          <?php } ?>

           <?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
		  <div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label">Description:</label>
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

          <?php if (strpos($value_config, ','."Vendor".',') !== FALSE) { ?>
		  <div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label">Vendor:</label>
			<div class="col-sm-8">
                <select data-placeholder="Choose a Vendor..." id="vendor" name="vendorid" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
				  <?php
					$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Vendor' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
					foreach($query as $id) {
						$selected = '';
						$selected = $id == $vendorid ? 'selected = "selected"' : '';
						echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
					}
				  ?>
                </select>
			</div>
		  </div>
          <?php } ?>

          <?php if (strpos($value_config, ','."Width".',') !== FALSE) { ?>
		  <div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label">Width:</label>
			<div class="col-sm-8">
			  <input name="width" type="text" value="<?php echo $width; ?>" class="form-control"/>
			</div>
		  </div>
          <?php } ?>

          <?php if (strpos($value_config, ','."Length".',') !== FALSE) { ?>
		  <div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label">Length:</label>
			<div class="col-sm-8">
			  <input name="length" type="text" value="<?php echo $length; ?>" class="form-control"/>
			</div>
		  </div>
          <?php } ?>

          <?php if (strpos($value_config, ','."Units".',') !== FALSE) { ?>
		  <div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label">Units:</label>
			<div class="col-sm-8">
			  <input name="units" type="text" value="<?php echo $units; ?>" class="form-control"/>
			</div>
		  </div>
          <?php } ?>

          <?php if (strpos($value_config, ','."Unit Weight".',') !== FALSE) { ?>
		  <div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label">Unit Weight:</label>
			<div class="col-sm-8">
			  <input name="unit_weight" type="text" value="<?php echo $unit_weight; ?>" class="form-control"/>
			</div>
		  </div>
          <?php } ?>

          <?php if (strpos($value_config, ','."Weight Per Feet".',') !== FALSE) { ?>
		  <div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label">Weight Per Foot:</label>
			<div class="col-sm-8">
			  <input name="weight_per_feet" type="text" value="<?php echo $weight_per_feet; ?>" class="form-control"/>
			</div>
		  </div>
          <?php } ?>

          <?php if (strpos($value_config, ','."Quantity".',') !== FALSE) { ?>
		  <div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label">Quantity:</label>
			<div class="col-sm-8">
			  <input name="quantity" type="text" value="<?php echo $quantity; ?>" class="form-control"/>
			</div>
		  </div>
          <?php } ?>

          <?php if (strpos($value_config, ','."Price".',') !== FALSE) { ?>
		  <div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label">Price:</label>
			<div class="col-sm-8">
			  <input name="price" type="text" value="<?php echo $price; ?>" class="form-control"/>
			</div>
		  </div>
          <?php } ?>

		<div class="form-group">
			<p><span class="brand-color"><em>Required Fields *</em></span></p>
		</div>

		<div class="form-group clearfix">
			<div class="col-sm-6">
				<span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Click here to go back to the Materials dashboard, this will discard any changes you made to the current ticket."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a href="material.php?filter=Top" class="btn brand-btn btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn btn-lg" onclick="history.go(-1);return false;">Back</a>-->
			</div>
			<div class="col-sm-6">
				<button	type="submit" name="submit"	value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
				<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to finalize the Material."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			</div>
		</div>

        

		</form>

	</div>
  </div>

<?php include ('../footer.php'); ?>
