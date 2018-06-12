<?php
/*
Payment/Invoice Listing SEA
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

$get_invoice =	mysqli_query($dbc,"SELECT posid FROM sales_order  WHERE `invoice_date` + INTERVAL 30 DAY < NOW() AND status!='Completed'");
$num_rows = mysqli_num_rows($get_invoice);
if($num_rows > 0) {
    while($row = mysqli_fetch_array( $get_invoice )) {
        $posid = $row['posid'];
		//$query_update_project = "UPDATE `sales_order` SET status = 'Posted Past Due' WHERE `posid` = '$posid'";
		//$result_update_project = mysqli_query($dbc, $query_update_project);
    }
}

if (isset($_POST['submit_pos'])) {
    $invoice_date = $_POST['invoice_date'];

    if($invoice_date == '') {
        $invoice_date = date('Y-m-d');
    }
    $contactid = $_POST['contactid'];
    $productpricing = $_POST['productpricing'];

    $sub_total = $_POST['sub_total'];

    $discount_value = '0';
    if($_POST['discount_value'] != 0) {
        $discount_type = $_POST['discount_type'];
        $discount_value = $_POST['discount_value'];
        if($discount_type == '%') {
            $d_value =  $discount_value.'%';
        }
        if($discount_type == '$') {
            $d_value =  '$'.$discount_value;
        }
    }

    $total_after_discount = $_POST['total_after_discount'];
    $delivery = filter_var($_POST['delivery'],FILTER_SANITIZE_STRING);
    $assembly = filter_var($_POST['assembly'],FILTER_SANITIZE_STRING);

    $delivery_type = filter_var($_POST['delivery_type'],FILTER_SANITIZE_STRING);
    $delivery_address = filter_var($_POST['delivery_address'],FILTER_SANITIZE_STRING);
    $contractorid = filter_var($_POST['contractorid'],FILTER_SANITIZE_STRING);

    $total_before_tax = $_POST['total_before_tax'];

    $tax_exemption_number = $_POST['tax_exemption_number'];
    $client_tax_exemption = $_POST['client_tax_exemption'];
    $total_tax = $_POST['tax_rate'];

    // GST PST
    $get_pos_tax = get_config($dbc, 'sales_order_tax');
    $pdf_tax = '';
    $gst_total = 0;
    $pst_total = 0;
    if($get_pos_tax != '') {
        $pos_tax = explode('*#*',$get_pos_tax);

        $total_count = mb_substr_count($get_pos_tax,'*#*');
        for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
            $pos_tax_name_rate = explode('**',$pos_tax[$eq_loop]);

            if (strcasecmp($pos_tax_name_rate[0], 'gst') == 0) {
                $gst_total = number_format((($total_before_tax*$pos_tax_name_rate[1])/100), 2);
            }

            if (strcasecmp($pos_tax_name_rate[0], 'pst') == 0) {
                if($pos_tax_name_rate[3] == 'Yes' && $client_tax_exemption == 'Yes') {
                    $pst_total = 0;
                } else {
                    $pst_total = number_format((($total_before_tax*$pos_tax_name_rate[1])/100), 2);
                }
            }
        }
    }
    // GST PST

    $total_price = $_POST['total_price'];
	$dep_paid = round($_POST['deposit_paid'],2);
	$updatedtotal = round($total_price - $dep_paid,2);
    $payment_type = $_POST['payment_type'];
    $status = 'Pending';
	$software_author = '';
	$software_url = $_SERVER['SERVER_NAME'];
	if($software_url == 'greenearthenergysolutions.rookconnect.com') {
		$software_author = 'Green Earth Energy Solutions';
	} else if($software_url == 'greenlifecan.rookconnect.com') {
		$software_author = 'Green Life Can LLC';
	}
    $pdf_product = '';
    $created_by = $_SESSION['contactid'];
    $comment = filter_var(htmlentities($_POST['comment']),FILTER_SANITIZE_STRING);
    $ship_date = $_POST['ship_date'];
	$due_date = $_POST['due_date'];
	$cross_software = $_POST['cross_software'];
    $query_insert_invoice = "INSERT INTO `sales_order` (`invoice_date`, `contactid`, `productpricing`, `sub_total`, `discount_type`, `discount_value`, `total_after_discount`, `delivery`, `assembly`, `total_before_tax`, `client_tax_exemption`, `tax_exemption_number`, `total_price`, `payment_type`, `created_by`, `comment`, `ship_date`, `due_date`, `status`, `gst`, `pst`, `delivery_type`, `delivery_address`, `contractorid`, `deposit_paid`, `updatedtotal`, `cross_software`,`software_author`) VALUES ('$invoice_date', '$contactid', '$productpricing', '$sub_total', '$discount_type', '$discount_value', '$total_after_discount', '$delivery', '$assembly', '$total_before_tax', '$client_tax_exemption', '$tax_exemption_number', '$total_price', '$payment_type', '$created_by', '$comment', '$ship_date', '$due_date', '$status', '$gst_total', '$pst_total', '$delivery_type', '$delivery_address', '$contractorid', '$dep_paid', '$updatedtotal', '$cross_software', '$software_author')";

    $results_are_in = mysqli_query($dbc, $query_insert_invoice);
    $posid = mysqli_insert_id($dbc);
			// ADD Column in Table for PDF //
			$col = "SELECT `type_category` FROM sales_order_product";
			$result = mysqli_query($dbc, $col);
		if (!$result){
			$colcreate = "ALTER TABLE `sales_order_product` ADD COLUMN `type_category` VARCHAR(555) NULL";
			$result = mysqli_query($dbc, $colcreate);
		}
	if($_POST['order_id'] == '' || $_POST['order_id'] == NULL) {
		for($i=0; $i<count($_POST['inventoryid']); $i++) {
			$inventoryid = $_POST['inventoryid'][$i];
			$price = $_POST['price'][$i];
			$quantity = $_POST['quantity'][$i];

			if($inventoryid != '') {
				$query_insert_invoice = "INSERT INTO `sales_order_product` (`posid`, `inventoryid`, `quantity`, `price`, `type_category`) VALUES ('$posid', '$inventoryid', '$quantity', '$price', 'inventory')";
				$results_are_in = mysqli_query($dbc, $query_insert_invoice);
			}
		}

		for($i=0; $i<count($_POST['vplinventoryid']); $i++) {
			$inventoryid = $_POST['vplinventoryid'][$i];
			$price = $_POST['vplprice'][$i];
			$quantity = $_POST['vplquantity'][$i];

			if($inventoryid != '') {
				$query_insert_invoice = "INSERT INTO `sales_order_product` (`posid`, `inventoryid`, `quantity`, `price`, `type_category`) VALUES ('$posid', '$inventoryid', '$quantity', '$price', 'vpl')";
				$results_are_in = mysqli_query($dbc, $query_insert_invoice);
			}
		}

		for($i=0; $i<count($_POST['prodinventoryid']); $i++) {
			$inventoryid = $_POST['prodinventoryid'][$i];
			$lineprice = $_POST['productlinepricing'][$i];
			$price = $_POST['prodprice'][$i];
			$quantity = $_POST['prodquantity'][$i];

			if($inventoryid != '') {
				$query_insert_invoice = "INSERT INTO `sales_order_product` (`posid`, `inventoryid`, `quantity`, `price`, `type_category`, `lineprice`) VALUES ('$posid', '$inventoryid', '$quantity', '$price', 'product', '$lineprice')";
				$results_are_in = mysqli_query($dbc, $query_insert_invoice);
			}
		}

		for($i=0; $i<count($_POST['servinventoryid']); $i++) {
			$inventoryid = $_POST['servinventoryid'][$i];
			$price = $_POST['servprice'][$i];
			$quantity = $_POST['servquantity'][$i];

			if($inventoryid != '') {
				$query_insert_invoice = "INSERT INTO `sales_order_product` (`posid`, `inventoryid`, `quantity`, `price`, `type_category`) VALUES ('$posid', '$inventoryid', '$quantity', '$price', 'service')";
				$results_are_in = mysqli_query($dbc, $query_insert_invoice);
			}
		}

		for($i=0; $i<count($_POST['misc_product']); $i++) {
			$misc_product = filter_var($_POST['misc_product'][$i],FILTER_SANITIZE_STRING);
			$misc_price = $_POST['misc_price'][$i];

			if($misc_product != '') {
				$query_insert_invoice = "INSERT INTO `sales_order_product` (`posid`, `misc_product`, `price`, `type_category`) VALUES ('$posid', '$misc_product', '$misc_price', 'misc product')";
				$results_are_in = mysqli_query($dbc, $query_insert_invoice);
			}
		}
	} else {
		for($i=0; $i<count($_POST['inventoryid_list']); $i++) {
			$inventoryid = $_POST['inventoryid_list'][$i];
			$price = $_POST['price_list'][$i];
			$quantity = $_POST['quantity_list'][$i];
			if($quantity > 0) {
				if($inventoryid != '') {
					$query_insert_invoice = "INSERT INTO `sales_order_product` (`posid`, `inventoryid`, `quantity`, `price`, `type_category`) VALUES ('$posid', '$inventoryid', '$quantity', '$price', 'inventory')";
					$results_are_in = mysqli_query($dbc, $query_insert_invoice);
				}
			}
		}

	}
    include ('create_pos_pdf.php');
    $pos_design = get_config($dbc, 'sales_order_design');
	echo $pos_design;
    if($pos_design == 1) {
        echo create_pos1_pdf($dbc,$posid,$d_value,$_POST['comment'], $gst_total, $pst_total);
    }
    if($pos_design == 2) {
        echo create_pos2_pdf($dbc,$posid,$d_value,$_POST['comment'], $gst_total, $pst_total);
    }
	if($pos_design == 3) {
        echo create_pos3_pdf($dbc,$posid,$d_value,$_POST['comment'], $gst_total, $pst_total, $_POST['company_software_name']);
    }
    $software_url = $_SERVER['SERVER_NAME'];

    if($software_url == 'www.washtechsoftware.com') {
        $to_email = 'troy@washtech.ca';
        //$to_email = 'dayanapatel@freshfocusmedia.com';
        $attachment = 'download/invoice_'.$posid.'.pdf';
        send_email('', $to_email, '', '', 'Washtech Invoice', 'Please see Attachment for Invoice', $attachment);
    }

    if($payment_type == 'Net 30 Days' || $payment_type == 'Net 30') {
        $send_invoice = $_POST['send_invoice'];
        if($send_invoice == 1) {
            $send_email = get_config($dbc, 'sales_order_invoice_outbound_email');
            $arr_email=explode(",",$send_email);
            $attachment = 'download/invoice_'.$posid.'.pdf';
            //send_email('', $arr_email, '', '', 'Outbound Invoice', 'Please see Attachment for Outbound Invoice', $attachment);
        }
    }

   echo '<script type="text/javascript"> window.location.replace("add_point_of_sell.php");
    window.open("download/invoice_'.$posid.'.pdf", "fullscreen=yes");
    </script>';
}
?>
<script type="text/javascript">

$(document).ready(function() {

    $("#form1").submit(function( event ) {
        var sub_total = $("input[name=sub_total]").val();
        var gst = $("input[name=gst]").val();
        var total_price = $("input[name=total_price]").val();
        var customerid = $("#customerid").val();
        var productpricing = $("#productpricing").val();
        //var payment_type = $("#payment_type").val();

        //if (sub_total == '0' || total_price == '0' || customerid == '' || productpricing == '' || payment_type == '') {
        if (sub_total == '0' || total_price == '0' || customerid == '' || payment_type == '') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });


    $('.price').attr('readonly', true);
	$('.servprice').attr('readonly', true);
	$('.prodprice').attr('readonly', true);
	$('.vplprice').attr('readonly', true);


    var count = 1;
    $('#deleteservices_0').hide();
	 var servcount = 1;
    $('#servdeleteservices_0').hide();
	 var prodcount = 1;
    $('#proddeleteservices_0').hide();
	 var vplcount = 1;
    $('#vpldeleteservices_0').hide();

	//BEGIN INVENTORY CODE
    $('#add_position_button').on( 'click', function () {
        $('#deleteservices_0').show();

        var clone = $('.additional_position').clone();
        clone.find('.form-control').val('');
        clone.find('.price').val('0');
        clone.find('.quantity').val('0');
        //clone.find(".product").html('');
        //clone.find(".product").trigger("change.select2");

        clone.find('.product').attr('id', 'product_dd_'+count);
        clone.find('.price').attr('id', 'price_dd_'+count);
        clone.find('.part').attr('id', 'part_dd_'+count);
        clone.find('.quantity').attr('id', 'qty_dd_'+count);
        clone.find('.category').attr('id', 'category_dd_'+count);

        clone.find('#services_0').attr('id', 'services_'+count);
        clone.find('#deleteservices_0').attr('id', 'deleteservices_'+count);
        $('#deleteservices_0').hide();

        clone.find('.form-control').trigger("change.select2");
        clone.removeClass("additional_position");
        $('#add_here_new_position').append(clone);
		resetChosen($("#category_dd_"+count));
		resetChosen($("#product_dd_"+count));
		resetChosen($("#part_dd_"+count));

        count++;
        return false;
    });

	// END INVENTORY and BEGIN VPL

	  $('#add_position_buttonvpl').on( 'click', function () {
        $('#vpldeleteservices_0').show();

        var clone = $('.additional_positionvpl').clone();
        clone.find('.form-control').val('');
        clone.find('.vplprice').val('0');
        clone.find('.vplquantity').val('0');
        //clone.find(".product").html('');
        //clone.find(".product").trigger("change.select2");

        clone.find('.vplproduct').attr('id', 'vplproduct_dd_'+vplcount);
        clone.find('.vplprice').attr('id', 'vplprice_dd_'+vplcount);
        clone.find('.vplpart').attr('id', 'vplpart_dd_'+vplcount);
        clone.find('.vplquantity').attr('id', 'vplqty_dd_'+vplcount);
        clone.find('.vplcategory').attr('id', 'vplcategory_dd_'+vplcount);

        clone.find('#vplservices_0').attr('id', 'vplservices_'+vplcount);
        clone.find('#vpldeleteservices_0').attr('id', 'vpldeleteservices_'+vplcount);
        $('#vpldeleteservices_0').hide();

        clone.find('.form-control').trigger("change.select2");
        clone.removeClass("additional_positionvpl");
        $('#add_here_new_positionvpl').append(clone);
		resetChosen($("#vplcategory_dd_"+vplcount));
		resetChosen($("#vplproduct_dd_"+vplcount));
		resetChosen($("#vplpart_dd_"+vplcount));

        vplcount++;
        return false;
    });

	// END Vendor Price List and BEGIN Product

	$('#add_position_buttonprod').on( 'click', function () {
        $('#proddeleteservices_0').show();

        var clone = $('.additional_positionprod').clone();
        clone.find('.form-control').val('');
        clone.find('.prodprice').val('0');
        clone.find('.prodquantity').val('0');
        //clone.find(".product").html('');
        //clone.find(".product").trigger("change.select2");

        clone.find('.prodproduct').attr('id', 'prodproduct_dd_'+prodcount);
        clone.find('.prodprice').attr('id', 'prodprice_dd_'+prodcount);
        clone.find('.prodpart').attr('id', 'prodpart_dd_'+prodcount);
        clone.find('.prodquantity').attr('id', 'prodqty_dd_'+prodcount);
        clone.find('.prodcategory').attr('id', 'prodcategory_dd_'+prodcount);
        clone.find('.prodlineprice').attr('id', 'prodlineprice_dd_'+prodcount);

        clone.find('#prodservices_0').attr('id', 'prodservices_'+prodcount);
        clone.find('#proddeleteservices_0').attr('id', 'proddeleteservices_'+prodcount);
        $('#proddeleteservices_0').hide();

        clone.find('.form-control').trigger("change.select2");
        clone.removeClass("additional_positionprod");
        $('#add_here_new_positionprod').append(clone);
		resetChosen($("#prodcategory_dd_"+prodcount));
		resetChosen($("#prodlineprice_dd_"+prodcount));

		resetChosen($("#prodproduct_dd_"+prodcount));
		resetChosen($("#prodpart_dd_"+prodcount));

        prodcount++;
        return false;
    });

	// END PRODUCT BEGIN Service

	$('#add_position_buttonserv').on( 'click', function () {
        $('#servdeleteservices_0').show();

        var clone = $('.additional_positionserv').clone();
        clone.find('.form-control').val('');
        clone.find('.servprice').val('0');
        clone.find('.servquantity').val('0');
        //clone.find(".product").html('');
        //clone.find(".product").trigger("change.select2");

        clone.find('.servproduct').attr('id', 'servproduct_dd_'+servcount);
        clone.find('.servprice').attr('id', 'servprice_dd_'+servcount);
        clone.find('.servpart').attr('id', 'servpart_dd_'+servcount);
        clone.find('.servquantity').attr('id', 'servqty_dd_'+servcount);
        clone.find('.servcategory').attr('id', 'servcategory_dd_'+servcount);

        clone.find('#servservices_0').attr('id', 'servservices_'+servcount);
        clone.find('#servdeleteservices_0').attr('id', 'servdeleteservices_'+servcount);
        $('#servdeleteservices_0').hide();

        clone.find('.form-control').trigger("change.select2");
        clone.removeClass("additional_positionserv");
        $('#add_here_new_positionserv').append(clone);
		resetChosen($("#servcategory_dd_"+servcount));
		resetChosen($("#servproduct_dd_"+servcount));
		resetChosen($("#servpart_dd_"+servcount));
        servcount++;

        return false;
    });

	// END SERVICE

    var count = 1;
    $('#deletemisc_0').hide();
    $('#add_misc_button').on( 'click', function () {
        $('#deletemisc_0').show();

        var clone = $('.additional_misc').clone();
        clone.find('.form-control').val('');
        clone.find('.misc_price').val('0');

        clone.find('.misc_product').attr('id', 'misc_product_'+count);
        clone.find('.misc_price').attr('id', 'misc_price_dd_'+count);

        clone.find('#misc_0').attr('id', 'misc_'+count);
        clone.find('#deletemisc_0').attr('id', 'deletemisc_'+count);
        $('#deletemisc_0').hide();

        clone.find('.form-control').trigger("change.select2");
        clone.removeClass("additional_misc");
        $('#add_here_new_misc').append(clone);
        count++;
        return false;
    });

});
// FOR INVENTORY VV
function selectCategory(sel) {
    var hiddenpricing = $("#hiddenpricing").val();
    var productPrice = $("#productpricing").val();
    if(hiddenpricing == 1 && productPrice == '') {
        //alert('Error : Please select Pricing First.');
		//return false;
    }
    var end = sel.value;
    var typeId = sel.id;

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "pos_ajax_all.php?fill=posFromCategory&name="+end,
        dataType: "html",   //expect html to be returned
        success: function(response){
            var result = response.split('*#*');

            var arr = typeId.split('_');
            $("#part_dd_"+arr[2]).html(result[0]);
            $("#part_dd_"+arr[2]).trigger("change.select2");

            $("#product_dd_"+arr[2]).html(result[1]);
            $("#product_dd_"+arr[2]).trigger("change.select2");

            $("#price_dd_"+arr[2]).val('0');
            $("#qty_dd_"+arr[2]).val('0');
        }
    });
}

// END FOR INVENTORY & BEGIN FOR VPL

function vplselectCategory(sel) {
    var hiddenpricing = $("#hiddenpricing").val();
    var productPrice = $("#productpricing").val();
    if(hiddenpricing == 1 && productPrice == '') {
        //alert('Error : Please select Pricing First.');
		//return false;
    }
    var end = sel.value;
    var typeId = sel.id;

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "pos_ajax_all.php?fill=posFromCategoryvpl&name="+end,
        dataType: "html",   //expect html to be returned
        success: function(response){
            var result = response.split('*#*');

            var arr = typeId.split('_');
            $("#vplpart_dd_"+arr[2]).html(result[0]);
            $("#vplpart_dd_"+arr[2]).trigger("change.select2");

            $("#vplproduct_dd_"+arr[2]).html(result[1]);
            $("#vplproduct_dd_"+arr[2]).trigger("change.select2");

            $("#vplprice_dd_"+arr[2]).val('0');
            $("#vplqty_dd_"+arr[2]).val('0');
        }
    });
}

// END FOR VPL & BEGIN FOR Product

function prodselectCategory(sel) {
    var hiddenpricing = $("#hiddenpricing").val();
    var productPrice = $("#productpricing").val();
    if(hiddenpricing == 1 && productPrice == '') {
        //alert('Error : Please select Pricing First.');
		//return false;
    }
    var end = sel.value;
    var typeId = sel.id;

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "pos_ajax_all.php?fill=posFromCategoryprod&name="+end,
        dataType: "html",   //expect html to be returned
        success: function(response){
            var result = response.split('*#*');

            var arr = typeId.split('_');
            $("#prodpart_dd_"+arr[2]).html(result[0]);
            $("#prodpart_dd_"+arr[2]).trigger("change.select2");

            $("#prodproduct_dd_"+arr[2]).html(result[1]);
            $("#prodproduct_dd_"+arr[2]).trigger("change.select2");

            $("#prodprice_dd_"+arr[2]).val('0');
            $("#prodqty_dd_"+arr[2]).val('0');
        }
    });
}
// END FOR Product & BEGIN FOR Services

function servselectCategory(sel) {
    var hiddenpricing = $("#hiddenpricing").val();
    var productPrice = $("#productpricing").val();
    if(hiddenpricing == 1 && (productPrice == '' || productPrice == null)) {
        //alert('Error : Please select Pricing First.');
		//return false;
    }
    var end = sel.value;
    var typeId = sel.id;

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "pos_ajax_all.php?fill=posFromCategoryserv&name="+end,
        dataType: "html",   //expect html to be returned
        success: function(response){
            var result = response.split('*#*');

            var arr = typeId.split('_');
            $("#servpart_dd_"+arr[2]).html(result[0]);
            $("#servpart_dd_"+arr[2]).trigger("change.select2");

            $("#servproduct_dd_"+arr[2]).html(result[1]);
            $("#servproduct_dd_"+arr[2]).trigger("change.select2");

            $("#servprice_dd_"+arr[2]).val('0');
            $("#servqty_dd_"+arr[2]).val('0');
        }
    });
}

// END SERVICES & BEGIN FOR INVENTORY

function selectProduct(sel) {
    var end = sel.value;
    var typeId = sel.id;
    var arr = typeId.split('_');
	<?php if(isset($_GET['order_list'])) {
		echo 'var type = "orderlist";';
	} else {
		echo 'var type = "original";';
	}
	?>
    var hiddenpricing = $("#hiddenpricing").val();
    var productPrice = $("#productpricing").val();
    if(hiddenpricing == 1 && (productPrice == '' || productPrice == null)) {
        //alert('Error : Please select Pricing First.');
		//return false;
    }
    var category = $("#category_dd_"+arr[2]).val();

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "pos_ajax_all.php?fill=posUpProductFromProduct&inventoryid="+end+"&productPrice="+productPrice+"&category="+category+"&type="+type,
        dataType: "html",   //expect html to be returned
        success: function(response){
            var result = response.split('**##**');

            $("#category_dd_"+arr[2]).html(result[0]);
            $("#part_dd_"+arr[2]).html(result[1]);
            $("#product_dd_"+arr[2]).html(result[2]);
            $("#category_dd_"+arr[2]).trigger("change.select2");
            $("#part_dd_"+arr[2]).trigger("change.select2");
            $("#product_dd_"+arr[2]).trigger("change.select2");

            if(hiddenpricing == 1) {
                $("#price_dd_"+arr[2]).val(result[3]);
            }
            $("#qty_dd_"+arr[2]).val('0');
        }
    });
}

// END INVENTORY & BEGIN FOR VPL

function vplselectProduct(sel) {
    var end = sel.value;
    var typeId = sel.id;
    var arr = typeId.split('_');
    var hiddenpricing = $("#hiddenpricing").val();
    var productPrice = $("#productpricing").val();
    if(hiddenpricing == 1 && (productPrice == '' || productPrice == null)) {
        //alert('Error : Please select Pricing First.');
		//return false;
    }
    var category = $("#vplcategory_dd_"+arr[2]).val();

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "pos_ajax_all.php?fill=posUpProductFromProductvpl&inventoryid="+end+"&productPrice="+productPrice+"&category="+category,
        dataType: "html",   //expect html to be returned
        success: function(response){
            var result = response.split('**##**');
            $("#vplcategory_dd_"+arr[2]).html(result[0]);
            $("#vplpart_dd_"+arr[2]).html(result[1]);
            $("#vplproduct_dd_"+arr[2]).html(result[2]);
            $("#vplcategory_dd_"+arr[2]).trigger("change.select2");
            $("#vplpart_dd_"+arr[2]).trigger("change.select2");
            $("#vplproduct_dd_"+arr[2]).trigger("change.select2");

            $("#vplprice_dd_"+arr[2]).val(result[3]);
            $("#vplqty_dd_"+arr[2]).val('0');
        }
    });
}

// END VPL & BEGIN FOR PROD

function prodselectProduct(sel) {
    var end = sel.value;
    var typeId = sel.id;
    var arr = typeId.split('_');
    var hiddenpricing = $("#hiddenpricing").val();
    var productPrice = $("#productpricing").val();
    if(hiddenpricing == 1 && (productPrice == '' || productPrice == null)) {
        //alert('Error : Please select Pricing First.');
		//return false;
    }
    var category = $("#prodcategory_dd_"+arr[2]).val();

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "pos_ajax_all.php?fill=posUpProductFromProductprod&inventoryid="+end+"&productPrice="+productPrice+"&category="+category,
        dataType: "html",   //expect html to be returned
        success: function(response){
            var result = response.split('**##**');

            $("#prodcategory_dd_"+arr[2]).html(result[0]);
            $("#prodpart_dd_"+arr[2]).html(result[1]);
            $("#prodproduct_dd_"+arr[2]).html(result[2]);
            $("#prodcategory_dd_"+arr[2]).trigger("change.select2");
            $("#prodpart_dd_"+arr[2]).trigger("change.select2");
            $("#prodproduct_dd_"+arr[2]).trigger("change.select2");

            if(hiddenpricing == 1) {
                $("#prodprice_dd_"+arr[2]).val(result[3]);
            }
            $("#prodqty_dd_"+arr[2]).val('0');
        }
    });
}

// END PROD & BEGIN FOR SERVICES


function servselectProduct(sel) {
    var end = sel.value;
    var typeId = sel.id;
    var arr = typeId.split('_');
    var hiddenpricing = $("#hiddenpricing").val();
    var productPrice = $("#productpricing").val();
    if(hiddenpricing == 1 && (productPrice == '' || productPrice == null)) {
        //alert('Error : Please select Pricing First.');
		//return false;
    }
    var category = $("#servcategory_dd_"+arr[2]).val();

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "pos_ajax_all.php?fill=posUpProductFromProductserv&inventoryid="+end+"&productPrice="+productPrice+"&category="+category,
        dataType: "html",   //expect html to be returned
        success: function(response){
            var result = response.split('**##**');

            $("#servcategory_dd_"+arr[2]).html(result[0]);
            $("#servpart_dd_"+arr[2]).html(result[1]);
            $("#servproduct_dd_"+arr[2]).html(result[2]);
            $("#servcategory_dd_"+arr[2]).trigger("change.select2");
            $("#servpart_dd_"+arr[2]).trigger("change.select2");
            $("#servproduct_dd_"+arr[2]).trigger("change.select2");

            $("#servprice_dd_"+arr[2]).val(result[3]);
            $("#servqty_dd_"+arr[2]).val('0');
        }
    });
}

// END SERVICES

function changeClient(sel) {
	var clientid = sel.value;
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "pos_ajax_all.php?fill=POSclient&clientid="+clientid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('**');
            if (result[0].indexOf("Client Price") >= 0) {
                var price_val = "client_price";
            }
            if (result[0].indexOf("Admin Price") >= 0) {
                var price_val = "admin_price";
                $('.price').attr('readonly', false);
            }
            if (result[0].indexOf("Commercial Price") >= 0) {
                var price_val = "commercial_price";
            }
            if (result[0].indexOf("Wholesale Price") >= 0) {
                var price_val = "wholesale_price";
            }
            if (result[0].indexOf("Final Retail Price") >= 0) {
                var price_val = "final_retail_price";
            }
            if (result[0].indexOf("Preferred Price") >= 0) {
                var price_val = "preferred_price";
            }
			if (result[0].indexOf("Purchase Order Price") >= 0) {
                var price_val = "purchase_order_price";
            }
			if (result[0].indexOf("Sales Order Price") >= 0) {
                var price_val = "sales_order_price";
            }
            if (result[0].indexOf("Web Price") >= 0) {
                var price_val = "web_price";
            }

            $("#client_tax_exemption").val(result[1]);
            var sum = 0;
            $('.pos_tax').each(function () {
                sum += +$(this).val() || 0;
            });
            var yes_tax_exemption = $("#yes_tax_exemption").val();
            if(result[1] == 'Yes' && yes_tax_exemption == 1) {
                $("#tax_exemption_fillup").html("Exemption Number : "+result[2]);
                var not_count_pos_tax = $("#not_count_pos_tax").val();
                var final_tax = parseFloat(sum - not_count_pos_tax);
                $("#tax_rate").val(final_tax);
                $("#tax_exemption_number").val(result[2]);
            } else {
                $("#tax_exemption_fillup").html('');
                $("#tax_exemption_number").val('');
                $("#tax_rate").val(sum);
            }
			$("#productpricing").val(price_val);
            $("#productpricing").trigger("change.select2");
            $("#delivery_address_fillup").val(result[3]);
		}
	});
}

function selectProductLinePricing(sel) {
    var end = sel.value;
    var typeId = sel.id;
    var arr = typeId.split('_');

    var productid = $("#prodproduct_dd_"+arr[2]).val();

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "pos_ajax_all.php?fill=posUpProductPriceFromProductprod&productid="+productid+"&productPrice="+end,
        dataType: "html",   //expect html to be returned
        success: function(response){
            $("#prodprice_dd_"+arr[2]).val(response);
            $("#prodqty_dd_"+arr[2]).val('0');
        }
    });
}

function selectProductPricing(sel) {
    $(".category").val('');
    $(".category").trigger("change.select2");
    $(".product").val('');
    $(".product").trigger("change.select2");
    $(".part").val('');
    $(".part").trigger("change.select2");

	$(".vplcategory").val('');
    $(".vplcategory").trigger("change.select2");
    $(".vplproduct").val('');
    $(".vplproduct").trigger("change.select2");
    $(".vplpart").val('');
    $(".vplpart").trigger("change.select2");

	$(".servcategory").val('');
    $(".servcategory").trigger("change.select2");
    $(".servproduct").val('');
    $(".servproduct").trigger("change.select2");
    $(".servpart").val('');
    $(".servpart").trigger("change.select2");

	$(".prodcategory").val('');
    $(".prodcategory").trigger("change.select2");
    $(".prodproduct").val('');
    $(".prodproduct").trigger("change.select2");
    $(".prodpart").val('');
    $(".prodpart").trigger("change.select2");

    $('.price').val('0');
    $('.quantity').val('0');

	$('.vplprice').val('0');
    $('.vplquantity').val('0');

	$('.servprice').val('0');
    $('.servquantity').val('0');

	$('.prodprice').val('0');
    $('.prodquantity').val('0');

    $('#sub_total').val('0');
    $('#gst').val('0.00');
    $('#total_price').val('0');

    var productPrice = $("#productpricing").val();
    if(productPrice == 'admin_price') {
        $('.price').attr('readonly', false);
		$('.vplprice').attr('readonly', false);
		$('.servprice').attr('readonly', false);
		$('.prodprice').attr('readonly', false);
    } else {
        $('.price').attr('readonly', true);
		 $('.vplprice').attr('readonly', true);
		 $('.servprice').attr('readonly', true);
		  $('.prodprice').attr('readonly', true);
    }
    countPOSTotal();
}

function countPOSTotal(sel) {
    var productPrice = $("#productpricing").val();

    var current_id = sel.id;
    var result = current_id.split('_');

    var qty = $("#qty_dd_"+result[2]).val();
    var pro = $("#product_dd_"+result[2]).val();

	var servqty = $("#servqty_dd_"+result[2]).val();
    var servpro = $("#servproduct_dd_"+result[2]).val();

	var prodqty = $("#prodqty_dd_"+result[2]).val();
    var prodpro = $("#prodproduct_dd_"+result[2]).val();

	var vplqty = $("#vplqty_dd_"+result[2]).val();
    var vplpro = $("#vplproduct_dd_"+result[2]).val();

    var numQty = $('.quantity').length;
	var vplnumQty = $('.vplquantity').length;
	var servnumQty = $('.servquantity').length;
	var prodnumQty = $('.prodquantity').length;
    var c = 0;
    var i;
    var price = 0;

    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "pos_ajax_all.php?fill=posPromotion&pro="+pro+"&qty="+qty+"&productPrice="+productPrice,
        dataType: "html",   //expect html to be returned
        success: function(response){
            if(current_id == "qty_dd_"+result[2]) {
                if(response != '') {
                    $("#price_dd_"+result[2]).val(response);
                }
            }
        },
        complete: function(){
            for(i=0; i<numQty; i++) {
                var qty = $("#qty_dd_"+i).val();
                var price = $("#price_dd_"+i).val();
				if(price !== '' && price !== null && price > 0) {
					c += parseFloat(price*qty);
				}
            }

            var nummisc = $('.misc_price').length;
            var m;
            for(m=0; m<nummisc; m++) {
                var price = $("#misc_price_dd_"+m).val();
                if(price !== '' && price !== null && price > 0) {
					c += parseFloat(price);
				}
            }

			var ggxy;
            for(ggxy=0; ggxy<servnumQty; ggxy++) {
                var qty = $("#servqty_dd_"+ggxy).val();
                var price = $("#servprice_dd_"+ggxy).val();
                if(price !== '' && price !== null && price > 0) {
					c += parseFloat(price*qty);
				}
            }

			var ggxx;
            for(ggxx=0; ggxx<prodnumQty; ggxx++) {
				var qty = $("#prodqty_dd_"+ggxx).val();
                var price = $("#prodprice_dd_"+ggxx).val();
                if(price !== '' && price !== null && price > 0) {
					c += parseFloat(price*qty);
				}
            }

			var ggxz;
            for(ggxz=0; ggxz<vplnumQty; ggxz++) {
				var qty = $("#vplqty_dd_"+ggxz).val();
                var price = $("#vplprice_dd_"+ggxz).val();
                if(price !== '' && price !== null && price > 0) {
					c += parseFloat(price*qty);
				}
            }


            $("#sub_total").val((c).toFixed(2));
            $("#total_before_tax").val((c).toFixed(2));

            var discount_type = $('input:radio[name=discount_type]:checked').val();
            var discount_value = $("#discount_value").val();

            var total_after_dis = c;

            if(discount_type == '%') {
                var discount = (c*(discount_value/100));
                var total_after_dis = parseFloat(c) - parseFloat(discount);
            }
            if(discount_type == '$') {
                var total_after_dis = parseFloat(c) - parseFloat(discount_value);
            }

            $('#total_after_discount').val((total_after_dis).toFixed(2));

            var delivery = $("#delivery").val();
            var assembly = $("#assembly").val();
            if(delivery == '' || typeof delivery === "undefined") {
                delivery = 0;
            }
            if(assembly == '' || typeof assembly === "undefined") {
                assembly = 0;
            }

            var shipping_total = parseFloat(total_after_dis) + parseFloat(delivery) + parseFloat(assembly);

            var final_total = shipping_total.toFixed(2);

            $('#total_before_tax').val(final_total);

            var tax_rate = $("#tax_rate").val();

            if (typeof tax_rate === "undefined") {
                $('#total_price').val(final_total);
				$('#updatedtotal').val(final_total-$('#deposit_paid').val());
            } else {
                var tax_rate_value = (final_total*tax_rate)/100;
                $('#tax_price').val(tax_rate_value.toFixed(2));
                var total_after_gst = parseFloat(final_total) + parseFloat(tax_rate_value);
                $('#total_price').val(total_after_gst.toFixed(2));
				$('#updatedtotal').val(final_total-$('#deposit_paid').val());
            }

        }
    });

}

function selectShippingtype() {
    var delivery_type = $("#delivery_type").val();
    if(delivery_type == 'Drop Ship' || delivery_type == 'Shipping') {
        $("#contractorid").show();
        $("#delivery_address").show();
        $("#delivery_div").show();
    } else if(delivery_type == 'Company Delivery') {
        $("#contractorid").hide();
        $("#delivery_div").hide();
        $("#delivery_address").show();
    } else {
        $("#contractorid").hide();
        $("#delivery_address").hide();
        $("#delivery_div").show();
    }
}
function seleteService(sel, hide, blank) {
	var typeId = sel.id;
	var arr = typeId.split('_');

    $("#"+hide+arr[1]).hide();
    $("#"+blank+arr[1]).val('');
}

function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}


jQuery(document).ready(function($){

			$('.live-search-list2 tr').each(function(){
			$(this).attr('data-search-term', $(this).text().toLowerCase());
			});

			$('.live-search-box2').on('keyup', function(){

			var searchTerm = $(this).val().toLowerCase();

				$('.live-search-list2 tr').each(function(){

					if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
						$(this).show();
					} else {
						if($(this).hasClass('dont-hide')) {
						} else { $(this).hide(); }
					}

				});

			});

			});
</script>
<style>
@media(max-width:991px) {
.hide-titles-mob {
	display:none;
}
.show-on-mob {
	text-align:left !important;
	display:inline-block;
}
.expand-mobile {
	width:90% !important;
	display:block !important;
}
.m-top-mbl {
	margin-top:20px;
}
}
@media(min-width:992px) {
.show-on-mob {
	display:none;
}
.form-inline .form-group {

    vertical-align: top;
}

.order-list {
	width:60%;
	margin-left:26%;
	padding:2px;
}
}
</style>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('sales_order');
?>
<div class="container triple-pad-bottom">
    <div class="row live-search-list2">
		<div class="col-sm-10">
			<h1><?php if(get_tile_title_so($dbc) == '' || get_tile_title_so($dbc) == NULL ) { $poser = "Sales Order"; } else { $poser = get_tile_title_so($dbc); } ; echo $poser; ?> Dashboard</h1>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php
				if(config_visible_function($dbc, 'sales_order') == 1) {
					echo '<a href="field_config_pos.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
				}
			?>
        </div>

		<div class="clearfix double-gap-bottom"></div>

        <div class="tab-container mobile-100-container"><?php
			if(vuaed_visible_function($dbc, 'sales_order') == 1) { ?>
				<div class="pull-left tab">
					<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to create a <?= SALES_ORDER_NOUN ?>."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
					if ( check_subtab_persmission($dbc, 'sales_order', ROLE, 'create') === TRUE ) { ?>
						<a href="add_point_of_sell.php"><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Create a <?= SALES_ORDER_NOUN ?></button></a><?php
					} else { ?>
						<button type="button" class="btn disabled-btn mobile-block mobile-100 active_tab">Create a <?= SALES_ORDER_NOUN ?></button><?php
					} ?>
				</div><?php
			} else {
				echo '<script>alert("You do not have access to this page, please consult your software administrator (or Settings) to gain access to this page.");</script';
				header('Location: complete.php');
			} ?>
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to view your Pending Orders."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
				if ( check_subtab_persmission($dbc, 'sales_order', ROLE, 'pending') === TRUE ) { ?>
					<a href="pending.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Pending Orders</button></a><?php
				} else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100">Pending Orders</button><?php
				} ?>
			</div>
			<!--<a href='receiving.php'><button type="button" class="btn brand-btn mobile-block mobile-100">Receiving</button></a>
			<a href='unpaid_invoice.php'><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Accounts Payable</button></a>-->
			<div class="pull-left tab">
				<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to view Completed <?= SALES_ORDER_TILE ?>."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
				if ( check_subtab_persmission($dbc, 'sales_order', ROLE, 'completed') === TRUE ) { ?>
					<a href="complete.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Completed <?= SALES_ORDER_TILE ?></button></a><?php
				} else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100">Completed <?= SALES_ORDER_TILE ?></button><?php
				} ?>
			</div>
			
			<div class="clearfix double-gap-bottom"></div>
		</div>

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal myform22" role="form">
		<br>
		<input name="order_id" id="" type="hidden" value="<?php if(isset($_GET['order_list'])) { echo $_GET['order_list']; } ?>" class="form-control" />
        <input name="tax_exemption_number" id="tax_exemption_number" type="hidden" class="form-control" />
        <input name="client_tax_exemption" id="client_tax_exemption" type="hidden" class="form-control" />
		<!-- // BEGIN CODE FOR ORDER LISTS NAVIGATION -->
		<!--<div class='mobile-100-container'>-->
			<div class="tab-container offset-left-15 mobile-100-container">
				<a href='add_point_of_sell.php'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php if(!isset($_GET['order_list'])) { echo "active_tab"; } ?>">Individual Items</button></a><?php
				$order_title='';
				$vendorid_orderlist = '';


				$software_url = $_SERVER['SERVER_NAME'];
				if($software_url == 'sea-alberta.rookconnect.com' || $software_url == 'sea-vancouver.rookconnect.com' || $software_url == 'sea-saskatoon.rookconnect.com' || $software_url !== 'greenearthenergysolutions.rookconnect.com' || $software_url !== 'greenlifecan.rookconnect.com' || $software_url == 'sea-regina.rookconnect.com' ) {
					$query_check_credentials = "SELECT * FROM order_lists WHERE include_in_so = '1' AND deleted = 0";
					$result = mysqli_query($dbczen, $query_check_credentials);
					$num_rows = mysqli_num_rows($result);
					if($num_rows > 0) {
					while($row = mysqli_fetch_array( $result ))
						{
						if(isset($_GET['order_list'])) {
						 if($row['order_id'] == $_GET['order_list'] && isset($_GET['zen'])) {
							$active_tab = 'active_tab';
						} else {
							$active_tab = '';
						}
						}?>
						<a href='add_point_of_sell.php?order_list=<?php echo $row['order_id']; ?>&zen=true'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_tab; ?>"><?php echo $row['order_title']; ?></button></a>
					<?php
						}
					}
				}
				$query_check_credentials = "SELECT * FROM order_lists WHERE include_in_so = '1' AND deleted = 0";
				$result = mysqli_query($dbc, $query_check_credentials);
				$num_rows = mysqli_num_rows($result);
				$active_tab = '';
				$active_tab = '';
				if($num_rows > 0) {
					while($row = mysqli_fetch_array( $result )) {
						if(isset($_GET['order_list'])) {
							$active_tab = '';
							if($row['order_id'] == $_GET['order_list'] && !isset($_GET['zen'])) {
								$active_tab = 'active_tab';
								$order_title = $row['order_title'];
								$vendorid_orderlist = $row['contactid'];
							} else {
								$active_tab = '';
							}
						} ?>
						<a href='add_point_of_sell.php?order_list=<?php echo $row['order_id']; ?>'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_tab; ?>"><?php echo $row['order_title']; ?></button></a><?php
					}
				}
				echo '<input type="hidden" value="'.$order_title.'" name="order_list_nom">';
				?>
			</div>
		<br>
		<?php

		// END CODE FOR ORDER LISTS NAVIGATION
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sales_order FROM field_config"));
        $value_config = ','.$get_field_config['sales_order'].',';
        ?>

        <?php if (strpos($value_config, ','."Send Outbound Invoice".',') !== FALSE) { ?>
            <input name="send_invoice" value='1' type="hidden" class="form-control" />
        <?php } else { ?>
            <input name="send_invoice" value='0' type="hidden" class="form-control" />
        <?php } ?>

        <?php if (strpos($value_config, ','."Invoice Date".',') !== FALSE) { ?>
        <div class="form-group">
            <label for="first_name" class="col-sm-3 control-label text-right">Order Date:</label>
            <div class="col-sm-9">
                <input name="invoice_date" value="<?php echo date('Y-m-d'); ?>" type="text" class="datepicker"></p>
            </div>
        </div>
        <?php } ?>

        <?php if (strpos($value_config, ','."Customer".',') !== FALSE) { ?>
          <div class="form-group">
            <label for="travel_task" class="col-sm-3 control-label">Customer<span class="brand-color">*</span>:<br><em><span id="tax_exemption_fillup"></em></span></label>
            <div class="col-sm-9">
              <select id="customerid" onchange="changeClient(this)" name="contactid" data-placeholder="Choose a Customer..." class="chosen-select-deselect form-control" width="380">
              <option value=''></option>
                    <?php
                    $result = mysqli_query($dbc, "SELECT contactid, name FROM contacts WHERE (category='Business' or category='Customer' or category='Client') AND deleted=0 order by name");
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value = '".$row['contactid']."'>".decryptIt($row['name'])."</option>";
                    }
                   ?>
              </select>
            </div>
          </div>
          <?php } ?>
            <input type="hidden" id="hiddenpricing" value="0" />
            <?php if (strpos($value_config, ','."Product Pricing".',') !== FALSE) { ?>
            <input type="hidden" id="hiddenpricing" value="1" />

            <div class="form-group" <?php if(isset($_GET['order_list'])) { ?>style='display:none;' <?php } ?>>
            <label for="site_name" class="col-sm-3 control-label">Product Pricing<span class="brand-color">*</span>:</label>
            <div class="col-sm-9">
                <select onchange="selectProductPricing(this)" data-placeholder="Choose Pricing..." id="productpricing" name="productpricing" class="chosen-select-deselect form-control" width="380">
                    <option value="">Please Select</option>
                    <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { ?>
                    <option value="client_price">Client Price</option>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { ?>
                    <option value="admin_price">Admin Price</option>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { ?>
                    <option value="commercial_price">Commercial Price</option>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { ?>
                    <option value="wholesale_price">Wholesale Price</option>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { ?>
                    <option value="final_retail_price">Final Retail Price</option>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."Preferred Price".',') !== FALSE) { ?>
                    <option value="preferred_price">Preferred Price</option>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."Web Price".',') !== FALSE) { ?>
                    <option value="web_price">Web Price</option>
                    <?php } ?>
					<?php if (strpos($value_config, ','."Purchase Order Price".',') !== FALSE) { ?>
                    <option value="purchase_order_price">Purchase Order Price</option>
                    <?php } ?>
					<?php if (strpos($value_config, ','."Sales Order Price".',') !== FALSE) { ?>
                    <option value="sales_order_price"><?= SALES_ORDER_NOUN ?> Price</option>
                    <?php } ?>
					<?php if (strpos($value_config, ','."Drum Unit Cost".',') !== FALSE) { ?>
                    <option value="drum_unit_cost">Drum Unit Cost</option>
                    <?php } ?>
					<?php if (strpos($value_config, ','."Drum Unit Price".',') !== FALSE) { ?>
                    <option value="drum_unit_price">Drum Unit Price</option>
                    <?php } ?>
					<?php if (strpos($value_config, ','."Tote Unit Cost".',') !== FALSE) { ?>
                    <option value="tote_unit_cost">Tote Unit Cost</option>
                    <?php } ?>
					<?php if (strpos($value_config, ','."Tote Unit Price".',') !== FALSE) { ?>
                    <option value="tote_unit_price">Tote Unit Price</option>
                    <?php } ?>
                </select>
            </div>
          </div>
          <?php }
		  $cross_software = '';
		  ?>

		  <!-- **$$**$$**$$**$$**$$ **$$**$$**$$**$$**$$ **$$**$$**$$**$$**$$ ORDER LIST TABLE **$$**$$**$$**$$**$$ **$$**$$**$$**$$**$$ **$$**$$**$$**$$**$$  -->

		  <?php if(isset($_GET['order_list'])) {
					if(isset($_GET['zen'])) {
						$dbcorzen = $dbczen;
						$cross_software = 'zen';
						if($software_url !== 'greenearthenergysolutions.rookconnect.com' && $software_url !== 'greenlifecan.rookconnect.com' ) {
							$not_zenearth = 'true';
						}
					} else {
						$dbcorzen = $dbc;
					}
					?>
				<div class="col-sm-9 col-sm-offset-3"><input type='text' name='x' class=' form-control live-search-box2' placeholder="Search Order List..." style='max-width:300px; margin-bottom:20px;'></div>
			  <div id="no-more-tables" class='list_dashboard'  <?php if(isset($_GET['order_id'])) { ?>style='display:none;'<?php } ?>>
            <?php
			$order_id = $_GET['order_list'];
			$get_driver = mysqli_fetch_assoc(mysqli_query($dbcorzen,"SELECT inventoryid FROM order_lists WHERE order_id='$order_id'"));
			$inventoryidorder = $get_driver['inventoryid'];
			if($inventoryidorder !== '' && $inventoryidorder !== NULL) {
            $query_check_credentials = "SELECT * FROM inventory WHERE inventoryid IN (" . $inventoryidorder . ") ORDER BY category, name, product_name";
            $result = mysqli_query($dbcorzen, $query_check_credentials);
            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
				echo '<center>Displaying a total of '.$num_rows.' rows.</center>';
                echo "<table class='table table-bordered order-list' >";
                echo "<tr class='hidden-xs hidden-sm dont-hide'>";
                        if (strpos($value_config, ','."Category".',') !== FALSE) {
							echo '<th>Category</th>';
						}
						if (strpos($value_config, ','."Part#".',') !== FALSE) {
							echo '<th>Part #</th>';
						}
						if (strpos($value_config, ','."Name".',') !== FALSE) {
							echo '<th>Product</th>';
						}
						if (strpos($value_config, ','."Price".',') !== FALSE) {
							echo '<th>Price</th>';
						}
						if (strpos($value_config, ','."Quantity".',') !== FALSE) {
							echo '<th>Quantity</th>';
						}
                    echo "</tr>";
            } else {
                echo "<h3 class ='list_dashboard'>No items have been added to this list. Click <a href='../Inventory/inventory.php?order_list=".$_GET['order_list']."&category=Top'>here</a> to add items.</h3>";
            }
			$ix = 0;
            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
						echo '<input type="hidden" value="'.$row['inventoryid'].'" name="inventoryid_list[]">';
                   if (strpos($value_config, ','."Category".',') !== FALSE) {
							echo '<td data-title="Category">';
							if($cross_software !== 'zen') {
								echo $row['category'];
							} else if(!isset($not_zenearth)) {
								echo $row['sub_category'];
							}
							echo '</td>';
						}
						if (strpos($value_config, ','."Part#".',') !== FALSE) {
							echo '<td data-title="Part #">'.$row['part_no'].'</td>';
						}
						if (strpos($value_config, ','."Name".',') !== FALSE) {
							echo '<td data-title="Product">'.decryptIt($row['name']).'</td>';
						}
						if (strpos($value_config, ','."Price".',') !== FALSE) {
							echo '<td data-title="Price">';
							?><input data-placeholder="Choose a Product..." name="price_list[]" id="price_dd_<?php echo $ix; ?>" value="<?php if($row['sales_order_price'] !== '' && $row['sales_order_price'] !== NULL) { echo $row['sales_order_price']; } else { echo "0"; }?>" style="width:100% !important;" onkeyup="countPOSTotal(this);" type="text" class="expand-mobile form-control price" /><?php
							echo '</td>';
						}
						if (strpos($value_config, ','."Quantity".',') !== FALSE) {
							echo '<td data-title="Quantity">';
							?><input data-placeholder="Choose a Product..." name="quantity_list[]" id="qty_dd_<?php echo $ix; ?>" onkeyup="numericFilter(this); countPOSTotal(this);" value="0" style="width:100% !important;" type="text" class="expand-mobile form-control quantity" /><?php
							echo '</td>';
						}
                echo "</tr>";
				$ix++;
            }

            echo '</table></div>';

		  } else { echo "<h3 class ='list_dashboard'>No items have been added to this list. Click <a href='../Inventory/inventory.php?order_list=".$_GET['order_list']."&category=Top'>here</a> to add items.</h3>"; } } ?>

		  <!-- **$$**$$**$$**$$**$$ **$$**$$**$$**$$**$$ **$$**$$**$$**$$**$$  END ORDER LIST TABLE **$$**$$**$$**$$**$$ **$$**$$**$$**$$**$$ **$$**$$**$$**$$**$$  -->
		  <input type='hidden' value='<?php echo $cross_software;?>' name='cross_software'>
		  <div class='inventory-margin' style='margin-left:15%; <?php if(isset($_GET['order_list'])) { echo 'display:none;'; } ?>' >
			<!-- INVENTORY -->
			<?php if (strpos($value_config, ','."Products".',') !== FALSE) { ?>
            <div class="form-group clearfix">
			<label class="col-sm-12 text-center" style="width:20%;"><h4>Inventory</h4></label>
			</div>
			<div class="form-group clearfix hide-titles-mob">
                <?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>
                    <label class="col-sm-3  text-center" style="width:20%;">Category</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."Part#".',') !== FALSE) { ?>
                    <label class="col-sm-3 text-center" style="width:20%;">Part #</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."Name".',') !== FALSE) { ?>
                    <label class="col-sm-3 text-center" style="position:relative;width:20%">Product</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."Price".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="position:relative;width:10%">Price</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."Quantity".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="position:relative;width:10%">Quantity</label>
                <?php } ?>
            </div>


          <div class="additional_position">
            <div class="clearfix"></div>
            <div class="form-group clearfix" id="services_0" width="100%">

                <?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                <div class="col-sm-3 expand-mobile type"  style="width: 20%; display:inline-block; position:relative;" id="category_0">
                    <select data-placeholder="Choose a Category..." onchange="selectCategory(this)"  id="category_dd_0" name="category[]" class="chosen-select-deselect  form-control category">
                        <option value=""></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT DISTINCT category FROM inventory WHERE include_in_so != '' order by category");
                        while($row = mysqli_fetch_array($query)) {
                            ?><option id='<?php echo $row['category'];?>' value='<?php echo $row['category'];?>'><?php echo $row['category'];?></option><?php
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."Part#".',') !== FALSE) { ?>
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Part #:</label>
                <div class="expand-mobile col-sm-3"  style="width: 20%;  display:inline-block; position:relative;" id="part_0">
                <select data-placeholder="Choose a Part#..." onchange="selectProduct(this)"  id="part_dd_0" name="part_no[]" class="chosen-select-deselect form-control part">
                    <option value=""></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT inventoryid, part_no FROM inventory WHERE include_in_so != '' AND deleted=0 order by part_no");
                    while($row = mysqli_fetch_array($query)) {
                        ?><option value='<?php echo $row['inventoryid'];?>'><?php echo $row['part_no'];?></option><?php
                    }
                    ?>
                </select>
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."Name".',') !== FALSE) { ?>
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Product:</label>
                <div class="col-sm-3 expand-mobile" id="product_0" style="width:20%; position:relative; display:inline-block;">
                    <select data-placeholder="Choose a Product..." name="inventoryid[]" id="product_dd_0"  onchange="selectProduct(this)" class="chosen-select-deselect  form-control product" style="position:relative;">
                        <option value=""></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT inventoryid, name FROM inventory WHERE deleted=0 AND include_in_so != '' order by name");
                        while($row = mysqli_fetch_array($query)) {
                            ?><option value='<?php echo $row['inventoryid'];?>'><?php echo decryptIt($row['name']);?></option><?php
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."Price".',') !== FALSE) { ?>

                <div class="col-sm-1 expand-mobile" id="price_0" style="width:10%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Price:</label>
                    <input data-placeholder="Choose a Product..." name="price[]" id="price_dd_0" value="0" style="width:100% !important;" onkeyup="countPOSTotal(this);" type="text" class="expand-mobile form-control price" />
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."Quantity".',') !== FALSE) { ?>

                <div class="col-sm-3 qt expand-mobile" id="qty_0" style="width:10%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                    <input data-placeholder="Choose a Product..." name="quantity[]" id="qty_dd_0" onkeyup="numericFilter(this); countPOSTotal(this);" value="0" style="width:100% !important;" type="text" class="expand-mobile form-control quantity" />
                </div>
                <?php } ?>
                <div class="col-sm-1 m-top-mbl">
                    <a href="#" onclick="seleteService(this,'services_','product_dd_'); return false;" id="deleteservices_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

            </div>

            <div id="add_here_new_position"></div>

            <div class="col-sm-12  triple-gap-bottom">
                <button id="add_position_button" class="btn brand-btn mobile-block">Add</button>
            </div>
            <?php } ?>
				<!-- END INVENTORY -->

				<!-- Vendor Price List -->
			<?php if (strpos($value_config, ','."vplProducts".',') !== FALSE) { ?>
            <div class="form-group clearfix">
			<label class="col-sm-1  text-center" style="width:20%;"><h4>Vendor Price List</h4></label>
			</div>
			<div class="form-group clearfix hide-titles-mob">
                <?php if (strpos($value_config, ','."vplCategory".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="width:20%;">Category</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."vplPart#".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="width:20%;">Part#</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."vplName".',') !== FALSE) { ?>
                    <label class="col-sm-3 text-center" style="position:relative;width:20%">Product</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."vplPrice".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="position:relative;width:10%">Price</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."vplQuantity".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="position:relative;width:10%">Quantity</label>
                <?php } ?>
            </div>


          <div class="additional_positionvpl">
            <div class="clearfix"></div>
            <div class="form-group clearfix" id="vplservices_0" width="100%">

                <?php if (strpos($value_config, ','."vplCategory".',') !== FALSE) { ?>
                <div class="col-sm-1 expand-mobile vpltype"  style="width:20%; display:inline-block; position:relative;" id="vplcategory_0">
					<label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." onchange="vplselectCategory(this)"  id="vplcategory_dd_0" name="vplcategory[]" class="chosen-select-deselect form-control vplcategory">
                        <option value=""></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT DISTINCT category FROM vendor_price_list WHERE include_in_so != '' order by category");
                        while($row = mysqli_fetch_array($query)) {
                            ?><option id='<?php echo $row['category'];?>' value='<?php echo $row['category'];?>'><?php echo $row['category'];?></option><?php
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."vplPart#".',') !== FALSE) { ?>
                <div class="col-sm-1 expand-mobile"  style="width:20%; display:inline-block; position:relative;" id="vplpart_0">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Part #:</label>
                <select data-placeholder="Choose a Part#..." onchange="vplselectProduct(this)"  id="vplpart_dd_0" name="vplpart_no[]" class="chosen-select-deselect form-control vplpart">
                    <option value=""></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT inventoryid, part_no FROM vendor_price_list WHERE deleted=0 AND include_in_so != '' order by part_no");
                    while($row = mysqli_fetch_array($query)) {
                        ?><option value='<?php echo $row['inventoryid'];?>'><?php echo $row['part_no'];?></option><?php
                    }
                    ?>
                </select>
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."vplName".',') !== FALSE) { ?>
                <div class="col-sm-3 expand-mobile" id="vplproduct_0" style="width:20%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Product:</label>
                    <select data-placeholder="Choose a Product..." name="vplinventoryid[]" id="vplproduct_dd_0"  onchange="vplselectProduct(this)" class="chosen-select-deselect form-control vplproduct" style="position:relative;">
                        <option value=""></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT inventoryid, name FROM vendor_price_list WHERE deleted=0 AND include_in_so != '' order by name");
                        while($row = mysqli_fetch_array($query)) {
                            ?><option value='<?php echo $row['inventoryid'];?>'><?php echo decryptIt($row['name']);?></option><?php
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."vplPrice".',') !== FALSE) { ?>
                <div class="col-sm-1 expand-mobile" id="vplprice_0" style="width:10%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Price:</label>
                    <input data-placeholder="Choose a Product..." name="vplprice[]" id="vplprice_dd_0" value="0" style="" onkeyup="countPOSTotal(this);" type="text" class="form-control vplprice" />
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."vplQuantity".',') !== FALSE) { ?>
                <div class="col-sm-3 expand-mobile vplqt" id="vplqty_0" style="width:10%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                    <input data-placeholder="Choose a Product..." name="vplquantity[]" id="vplqty_dd_0" onkeyup="numericFilter(this); countPOSTotal(this);" value="0" style="" type="text" class="form-control vplquantity" />
                </div>
                <?php } ?>
                <div class="col-sm-1 m-top-mbl">
                    <a href="#" onclick="seleteService(this,'vplservices_','vplproduct_dd_'); return false;" id="vpldeleteservices_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

            </div>

            <div id="add_here_new_positionvpl"></div>

            <div class="col-sm-12 triple-gap-bottom">
                <button id="add_position_buttonvpl" class="btn brand-btn mobile-block">Add</button>
            </div>
            <?php } ?>
				<!-- END VPL  -->

				<!-- Products -->
            <?php if (strpos($value_config, ','."prodProducts".',') !== FALSE) { ?>

            <div class="form-group clearfix">
			<label class="col-sm-1  text-center" style="width:20%;"><h4>Product(s)</h4></label>
			</div>
			  <div class="form-group clearfix hide-titles-mob">
                <?php if (strpos($value_config, ','."prodCategory".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="width:20%;">Category</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."prodProduct Type".',') !== FALSE) { ?><!--
                    <label class="col-sm-1 text-center" style="width:15%;">Product Type</label>-->
                <?php } ?>
                <?php if (strpos($value_config, ','."prodHeading".',') !== FALSE) { ?>
                    <label class="col-sm-3 text-center" style="position:relative;width:20%">Heading</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."Pricing by Line Item".',') !== FALSE) { ?>
                    <label class="col-sm-2 text-center" style="position:relative;width:15%">Pricing</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."prodPrice".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="position:relative;width:10%">Price</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."prodQuantity".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="position:relative;width:10%">Quantity</label>
                <?php } ?>
            </div>

          <div class="additional_positionprod">
            <div class="clearfix"></div>
            <div class="form-group clearfix" id="prodservices_0" width="100%">

                <?php if (strpos($value_config, ','."prodCategory".',') !== FALSE) { ?>
                <div class="col-sm-1 expand-mobile prodtype"  style="width:20%; display:inline-block; position:relative;" id="prodcategory_0">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." onchange="prodselectCategory(this)"  id="prodcategory_dd_0" name="prodcategory[]" class="chosen-select-deselect form-control prodcategory">
                        <option value=""></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT DISTINCT category FROM products WHERE include_in_so != '' order by category");
                        while($row = mysqli_fetch_array($query)) {
                            ?><option id='<?php echo $row['category'];?>' value='<?php echo $row['category'];?>'><?php echo $row['category'];?></option><?php
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($value_config, ','."prodProduct Type".',') !== FALSE) { ?>
                <div class="col-sm-1 expand-mobile"  style="width:15%; display:inline-block; position:relative;" id="prodpart_0">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Product Type:</label>
                <select data-placeholder="Choose a Type..." onchange="prodselectProduct(this)"  id="prodpart_dd_0" name="prodpart_no[]" class="chosen-select-deselect form-control prodpart">
                    <option value=""></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT productid, product_type FROM products WHERE deleted=0 order by product_type");
                    while($row = mysqli_fetch_array($query)) {
                        ?><option value='<?php echo $row['productid'];?>'><?php echo $row['product_type'];?></option><?php
                    }
                    ?>
                </select>
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."prodHeading".',') !== FALSE) { ?>
                <div class="col-sm-3 expand-mobile" id="prodproduct_0" style="width:20%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." name="prodinventoryid[]" id="prodproduct_dd_0"  onchange="prodselectProduct(this)" class="chosen-select-deselect form-control prodproduct" style="position:relative;">
                        <option value=""></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT productid, heading FROM products WHERE deleted=0 AND include_in_so != '' order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            ?><option value='<?php echo $row['productid'];?>'><?php echo $row['heading'];?></option><?php
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."Pricing by Line Item".',') !== FALSE) { ?>
                <div class="col-sm-3 expand-mobile" id="prodlineprice_0" style="width:15%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Pricing:</label>
                    <select onchange="selectProductLinePricing(this)" data-placeholder="Choose Pricing..." id="prodlineprice_dd_0" name="productlinepricing[]" class="chosen-select-deselect form-control prodlineprice" width="380">
                        <option value="">Please Select</option>
                        <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { ?>
                        <option value="client_price">Client Price</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { ?>
                        <option value="admin_price">Admin Price</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { ?>
                        <option value="commercial_price">Commercial Price</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { ?>
                        <option value="wholesale_price">Wholesale Price</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { ?>
                        <option value="final_retail_price">Final Retail Price</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Preferred Price".',') !== FALSE) { ?>
                        <option value="preferred_price">Preferred Price</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Web Price".',') !== FALSE) { ?>
                        <option value="web_price">Web Price</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Purchase Order Price".',') !== FALSE) { ?>
                        <option value="purchase_order_price">Purchase Order Price</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Sales Order Price".',') !== FALSE) { ?>
                        <option value="sales_order_price"><?= SALES_ORDER_NOUN ?> Price</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Drum Unit Cost".',') !== FALSE) { ?>
                        <option value="drum_unit_cost">Drum Unit Cost</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Drum Unit Price".',') !== FALSE) { ?>
                        <option value="drum_unit_price">Drum Unit Price</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Tote Unit Cost".',') !== FALSE) { ?>
                        <option value="tote_unit_cost">Tote Unit Cost</option>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Tote Unit Price".',') !== FALSE) { ?>
                        <option value="tote_unit_price">Tote Unit Price</option>
                        <?php } ?>
                    </select>
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."prodPrice".',') !== FALSE) { ?>
                <div class="col-sm-1 expand-mobile" id="prodprice_0" style="width:10%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Price:</label>
                    <input data-placeholder="Choose a Product..." name="prodprice[]" id="prodprice_dd_0" value="0" style="" onkeyup="countPOSTotal(this);" type="text" class="form-control prodprice" />
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."prodQuantity".',') !== FALSE) { ?>
                <div class="col-sm-3 expand-mobile prodqt" id="prodqty_0" style="width:10%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                    <input data-placeholder="Choose a Product..." name="prodquantity[]" id="prodqty_dd_0" onkeyup="numericFilter(this); countPOSTotal(this);" value="0" style="" type="text" class="form-control prodquantity" />
                </div>
                <?php } ?>

                <div class="col-sm-1 m-top-mbl">
                    <a href="#" onclick="seleteService(this,'prodservices_','prodproduct_dd_'); return false;" id="proddeleteservices_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

            </div>

            <div id="add_here_new_positionprod"></div>

            <div class="col-sm-12  triple-gap-bottom">
                <button id="add_position_buttonprod" class="btn brand-btn mobile-block">Add</button>
            </div>
            <?php } ?>
				<!-- END Products -->

				<!-- Services -->
            <?php if (strpos($value_config, ','."servServices".',') !== FALSE) { ?>

            <div class="form-group clearfix">
			<label class="col-sm-1 text-center" style="width:20%;"><h4>Service(s)</h4></label>
			</div>
			  <div class="form-group clearfix hide-titles-mob">
                <?php if (strpos($value_config, ','."servCategory".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="width:20%;">Category</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."servService Type".',') !== FALSE) { ?>
                    <!--<label class="col-sm-1 text-center" style="width:15%;">Service Type</label>-->
                <?php } ?>
                <?php if (strpos($value_config, ','."servHeading".',') !== FALSE) { ?>
                    <label class="col-sm-3 text-center" style="position:relative;width:20%">Heading</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."servPrice".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="position:relative;width:10%">Price</label>
                <?php } ?>
                <?php if (strpos($value_config, ','."servQuantity".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center" style="position:relative;width:10%">Quantity</label>
                <?php } ?>
            </div>


          <div class="additional_positionserv">
            <div class="clearfix"></div>
            <div class="form-group clearfix" id="servservices_0" width="100%">

                <?php if (strpos($value_config, ','."servCategory".',') !== FALSE) { ?>
                <div class="col-sm-1 servtype expand-mobile"  style="width:20%; display:inline-block; position:relative;" id="servcategory_0">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." onchange="servselectCategory(this)"  id="servcategory_dd_0" name="servcategory[]" class="chosen-select-deselect form-control servcategory">
                        <option value=""></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT DISTINCT category FROM services WHERE include_in_so != '' order by category");
                        while($row = mysqli_fetch_array($query)) {
                            ?><option id='<?php echo $row['category'];?>' value='<?php echo $row['category'];?>'><?php echo $row['category'];?></option><?php
                        }
                        ?>
                    </select>
                </div>
                <?php } /* ?>

                <?php if (strpos($value_config, ','."servService Type".',') !== FALSE) { ?>
                <div class="col-sm-1"  style="width:15%; display:inline-block; position:relative;" id="servpart_0">
                <select data-placeholder="Choose a Type..." onchange="servselectProduct(this)"  id="servpart_dd_0" name="servpart_no[]" class="chosen-select-deselect form-control servpart">
                    <option value=""></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT serviceid, service_type FROM services WHERE deleted=0");
                    while($row = mysqli_fetch_array($query)) {
                        ?><option value='<?php echo $row['serviceid'];?>'><?php echo $row['service_type'];?></option><?php
                    }
                    ?>
                </select>
                </div>
                <?php }  */?>

                <?php if (strpos($value_config, ','."servHeading".',') !== FALSE) { ?>
                <div class="col-sm-3 expand-mobile" id="servproduct_0" style="width:20%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." name="servinventoryid[]" id="servproduct_dd_0"  onchange="servselectProduct(this)" class="chosen-select-deselect form-control servproduct" style="position:relative;">
                        <option value=""></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE deleted=0 AND include_in_so != '' order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            ?><option value='<?php echo $row['serviceid'];?>'><?php echo $row['heading'];?></option><?php
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."servPrice".',') !== FALSE) { ?>
                <div class="col-sm-1 expand-mobile" id="servprice_0" style="width:10%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Price:</label>
                    <input data-placeholder="Choose a Product..." name="servprice[]" id="servprice_dd_0" value="0" style="" onkeyup="countPOSTotal(this);" type="text" class="form-control servprice" />
                </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."servQuantity".',') !== FALSE) { ?>
				<label for="company_name expand-mobile" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                <div class="col-sm-3 servqt expand-mobile" id="servqty_0" style="width:10%; position:relative; display:inline-block;">
                    <input data-placeholder="Choose a Product..." name="servquantity[]" id="servqty_dd_0" onkeyup="numericFilter(this); countPOSTotal(this);" value="0" style="" type="text" class="form-control servquantity" />
                </div>
                <?php } ?>
                <div class="col-sm-1 m-top-mbl">
                    <a href="#" onclick="seleteService(this,'servservices_','servproduct_dd_'); return false;" id="servdeleteservices_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

            </div>

            <div id="add_here_new_positionserv"></div>

            <div class="col-sm-12 triple-gap-bottom">
                <button id="add_position_buttonserv" class="btn brand-btn mobile-block">Add</button>
            </div>
            <?php } ?>
				<!-- END Services -->

            <?php if (strpos($value_config, ','."Misc Item".',') !== FALSE) { ?>
            <div class="form-group clearfix hide-titles-mob">
                <label class="col-sm-3 text-center " style="position:relative;width:20%">Misc Product</label>
                <label class="col-sm-1 text-center" style="position:relative;width:10%">Price</label>
            </div>

          <div class="additional_misc">
            <div class="clearfix"></div>
            <div class="form-group clearfix" id="misc_0" width="100%">
                <div class="col-sm-1 expand-mobile" id="miscproduct_0" style="width:20%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Misc Product:</label>
                    <input data-placeholder="Choose a Product..." id="misc_product_0" name="misc_product[]" type="text" class="form-control misc_product" />
                </div>

                <div class="col-sm-1 expand-mobile" id="price_0" style="width:10%; position:relative; display:inline-block;">
				<label for="company_name" class="col-sm-4 show-on-mob control-label">Price:</label>
                    <input data-placeholder="Choose a Product..." name="misc_price[]" id="misc_price_dd_0" value="0" style="" onkeyup="countPOSTotal(this);" type="text" class="form-control misc_price" />
                </div>

                <div class="col-sm-1 m-top-mbl">
                    <a href="#" onclick="seleteService(this,'misc_','misc_product_'); return false;" id="deletemisc_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

            </div>

            <div id="add_here_new_misc"></div>

            <div class="col-sm-12 triple-gap-bottom">
                <button id="add_misc_button" class="btn brand-btn mobile-block">Add</button>
            </div>
             <?php } ?>
			</div>

            <?php if (strpos($value_config, ','."Total Price".',') !== FALSE) { ?>
              <div class="form-group">
                <label for="site_name" class="col-sm-3 control-label">Sub-Total<span class="brand-color">*</span>:</label>
                <div class="col-sm-9">
                  <input name="sub_total" id="sub_total" value=0 type="text" class="form-control" />
                </div>
              </div>
              <?php } ?>

            <?php if (strpos($value_config, ','."Discount".',') !== FALSE) { ?>
                <div class="form-group">
                    <label for="site_name" class="col-sm-3 control-label">Discount Type:</label>
                    <div class="col-sm-9">
                      <div class="radio">
                        <label class="double-pad-right"><input type="radio" style="height:20px;width:20px;  margin-right:20px;" name="discount_type" value="%">%</label>
                        <label class="pad-right"><input type="radio" style="height:20px;width:20px; margin-right:20px;" name="discount_type" value="$">$</label>
                      </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_name" class="col-sm-3 control-label">Discount Value:</label>
                    <div class="col-sm-9">
                      <input name="discount_value" onkeyup="countPOSTotal(this);" id="discount_value" value=0 type="text" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                <label for="site_name" class="col-sm-3 control-label">Total After Discount:</label>
                    <div class="col-sm-9">
                        <input name="total_after_discount" id="total_after_discount" value=0 type="text" class="form-control" />
                    </div>
                </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Delivery".',') !== FALSE) { ?>
                <div class="form-group">
                <label for="site_name" class="col-sm-3 control-label">Delivery Option:</label>
                    <div class="col-sm-9">
                        <select data-placeholder="Choose a Type..." name="delivery_type" id="delivery_type"  onchange="selectShippingtype()" class="chosen-select-deselect form-control product" style="position:relative;">
                            <option value=""></option>
                            <option value="Pick-Up">Pick-Up</option>
                            <option value="Company Delivery">Company Delivery</option>
                            <option value="Drop Ship">Drop Ship</option>
                            <option value="Shipping">Shipping</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="display: none;" id="delivery_address">
                <label for="site_name" class="col-sm-3 control-label">Confirm delivery Address:</label>
                    <div class="col-sm-9">
                        <input name="delivery_address" id="delivery_address_fillup" type="text" class="form-control" />
                    </div>
                </div>

              <div class="form-group" style="display: none;" id="contractorid">
                <label for="travel_task" class="col-sm-3 control-label">Contractor:</label>
                <div class="col-sm-9">
                  <select name="contractorid" data-placeholder="Choose Contractor..." class="chosen-select-deselect form-control" width="380">
                  <option value=''></option>
					   <?php
							$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Contractor' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
							foreach($query as $id) {
								$selected = '';
								//$selected = $id == $contactid ? 'selected = "selected"' : '';
								echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
							}
						  ?>
                  </select>
                </div>
              </div>

                <div class="form-group" id="delivery_div">
                <label for="site_name" class="col-sm-3 control-label">Delivery/Shipping Amount:</label>
                    <div class="col-sm-9">
                        <input name="delivery" onkeyup="countPOSTotal(this);" id="delivery" value=0 type="text" class="form-control" />
                    </div>
                </div>

            <?php } ?>

            <?php if (strpos($value_config, ','."Assembly".',') !== FALSE) { ?>
                <div class="form-group">
                <label for="site_name" class="col-sm-3 control-label">Assembly Amount:</label>
                    <div class="col-sm-9">
                        <input name="assembly" onkeyup="countPOSTotal(this);" id="assembly" value=0 type="text" class="form-control" />
                    </div>
                </div>
            <?php } ?>

            <div class="form-group">
            <label for="site_name" class="col-sm-3 control-label">Total Before Tax:</label>
                <div class="col-sm-9">
                    <input name="total_before_tax" id="total_before_tax" value=0 type="text" class="form-control" />
                </div>
            </div>

            <?php if (strpos($value_config, ','."Tax".',') !== FALSE) { ?>
                <?php
                $pos_tax_value = get_config($dbc, 'purchase_order_tax');
                $pos_tax = explode('*#*',$pos_tax_value);

                $total_count = mb_substr_count($pos_tax_value,'*#*');
                $tax_rate = 0;
                $tax_exe = 0;
                for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
                    $pos_tax_name_rate = explode('**',$pos_tax[$eq_loop]);
                    $tax_rate += $pos_tax_name_rate[1];
                    ?>

                    <div class="clearfix"></div>

                  <div class="form-group">
                    <label for="site_name" class="col-sm-3 control-label"><?php echo $pos_tax_name_rate[0];?> (%):<br><em>[<?php echo $pos_tax_name_rate[2];?>]</em></label>
                    <div class="col-sm-9">
                      <input name="pos_tax" readonly value='<?php echo $pos_tax_name_rate[1];?>' type="text" class="form-control pos_tax" />
                    </div>
                  </div>

                    <?php
                    if($pos_tax_name_rate[3] == 'Yes') {
                        DEFINE('TAX_EXEMPTION', $pos_tax_name_rate[0]);
                      ?>
                      <input id="not_count_pos_tax" value='<?php echo $pos_tax_name_rate[1];?>' type="hidden" />

                      <input id="not_count_pos_tax_number" value='<?php echo $pos_tax_name_rate[2];?>' type="hidden" />
                        <?php
                        $tax_exe = 1;
                    }
                }
                if($tax_exe == 0) { ?>
                  <input id="yes_tax_exemption" value='0' type="hidden" />
                <?php } else { ?>
                  <input id="yes_tax_exemption" value='1' type="hidden" />
                <?php }
                echo '<input type="hidden" name="tax_rate" id="tax_rate" value="'.$tax_rate.'" />';
                ?>
                <?php } ?>

              <div class="form-group">
                <label for="site_name" class="col-sm-3 control-label">Tax Price ($):</label>
                <div class="col-sm-9">
                  <input name="tax_price" id="tax_price" readonly type="text" value=0 class="form-control" />
                </div>
              </div>

              <div class="form-group">
                <label for="site_name" class="col-sm-3 control-label">Total Price<span class="brand-color">*</span>:</label>
                <div class="col-sm-9">
                  <input name="total_price" id="total_price" type="text" value=0 class="form-control" />
                </div>
              </div>

              <?php if (strpos($value_config, ','."Payment Type".',') !== FALSE) { ?>
              <div class="form-group">
                <label for="site_name" class="col-sm-3 control-label">Payment Type<span class="brand-color">*</span>:</label>
                <div class="col-sm-9">
                  <select id="payment_type" name="payment_type" data-placeholder="Choose a Type..." class="chosen-select-deselect form-control" width="380">
                      <option value=""></option>
                      <?php
						$tabs = get_config($dbc, 'po_invoice_payment_types');
                        $each_tab = explode(',', $tabs);
						 if (is_array($each_tab) && count($each_tab) > 0) {
                        foreach ($each_tab as $cat_tab) {
                            if ($invtype == $cat_tab) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                        }
						 } else {
							 echo "<option value='Pay Now'>Pay Now</option>";
							 echo "<option value='Net 30'>Net 30</option>";
							 echo "<option value='Net 60'>Net 60</option>";
							 echo "<option value='Net 90'>Net 90</option>";
							 echo "<option value='Net 120'>Net 120</option>";
						 }
                      ?>
                  </select>
                </div>
              </div>
              <?php } ?>

              <div class="form-group" <?php if (strpos($value_config, ','."Deposit Paid".',') !== FALSE) { } else { echo 'style="display:none;"'; } ?>>
                <label for="site_name" class="col-sm-3 control-label">Deposit Paid ($)<span class="brand-color"></span>:</label>
                <div class="col-sm-9">
                  <input name="deposit_paid" onkeyup="countPOSTotal(this);" id="deposit_paid" value=0 type="text" class="form-control" />
				  <input type='hidden' name='updatedtotal' id='updatedtotal' class='form-control'>
                </div>
              </div>

              <?php if (strpos($value_config, ','."Comment".',') !== FALSE) { ?>
              <div class="form-group">
                <label for="site_name" class="col-sm-3 control-label">Comment:</label>
                <div class="col-sm-9">
                  <textarea name="comment" rows="4" cols="50" class="form-control"></textarea>
                </div>
              </div>
              <?php } ?>

              <?php if (strpos($value_config, ','."Created/Sold By".',') !== FALSE) { ?>
              <div class="form-group">
                <label for="site_name" class="col-sm-3 control-label">Created/Sold By:</label>
                <div class="col-sm-9">
                  <input name="created_by" readonly value="<?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?>" type="text" value=0 class="form-control" />
                </div>
              </div>
              <?php } ?>

            <?php if (strpos($value_config, ','."Ship Date".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="first_name" class="col-sm-3 control-label text-right">Ship Date:</label>
                <div class="col-sm-9">
                    <input name="ship_date" value="<?php echo date('Y-m-d'); ?>" type="text" class="datepicker"></p>
                </div>
            </div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Due Date".',') !== FALSE) { ?>
            <div class="form-group">
                <label for="first_name" class="col-sm-3 control-label text-right">Due Date:</label>
                <div class="col-sm-9">
                    <input name="due_date" value="<?php echo date('Y-m-d'); ?>" type="text" class="datepicker"></p>
                </div>
            </div>
            <?php } ?>
			<input type='hidden' name='company_software_name' value='<?PHP echo COMPANY_SOFTWARE_NAME; ?>'>
            <div class="form-group">
				<p><span class="empire-red"><em>Required Fields *</em></span></p>
            </div>

              <div class="form-group">
                <div class="col-sm-6">
					<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Clicking this will return you to the dashboard; your <?= SALES_ORDER_NOUN ?> will not be saved."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <a href="pending.php" class="btn brand-btn btn-lg">Back</a>
					<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
				</div>
				<div class="col-sm-6">
                    <button type="submit" name="submit_pos" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
					<span class="popover-examples list-inline pull-right" style="margin:15px 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click this to finalize the <?= SALES_ORDER_NOUN ?>."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                </div>
              </div>

        </form>

	</div>
</div>

<?php include ('../footer.php'); ?>
