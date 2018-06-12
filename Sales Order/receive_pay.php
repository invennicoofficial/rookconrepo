<?php
/*
Payment/Invoice Listing SEA
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

$posid = '';
$type = '';
if(isset($_GET['posid'])) {
	$posid = $_GET['posid'];
}
if(isset($_GET['type'])) {
	$type = $_GET['type'];
}
?>
<script type="text/javascript">

$(document).ready(function() {


	$('.total_pay_update').focusout(function() {
		var val = $(this).val();
		var id = $(this)[0].id;
		
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "pos_ajax_all.php?fill=pay_po&val="+val+'&id='+id,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});
	});

	
	$('.gst_update').focusout(function() {
		var val = $(this).val();
		var id = $(this)[0].id;
		var gst = $(this).next().val();
		var gst_rem = gst - val;
		$(this).closest('td').next('td').find('span').text(gst_rem.toFixed(2));
		
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "pos_ajax_all.php?fill=pay_gst&val="+val+'&id='+id,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});
		
	});
	
	$('.pst_update').focusout(function() {
		var val = $(this).val();
		var id = $(this)[0].id;
		var gst = $(this).next().val();
		var gst_rem = gst - val;
		$(this).closest('td').next('td').find('span').text(gst_rem.toFixed(2));
		
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "pos_ajax_all.php?fill=pay_pst&val="+val+'&id='+id,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});
		
	});
	
	$('.del_update').focusout(function() {
		var val = $(this).val();
		var id = $(this)[0].id;
		var gst = $(this).next().val();
		var gst_rem = gst - val;
		$(this).closest('td').next('td').find('span').text(gst_rem.toFixed(2));
		
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "pos_ajax_all.php?fill=pay_del&val="+val+'&id='+id,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});
		
	});
	
	$('.asse_update').focusout(function() {
		var val = $(this).val();
		var id = $(this)[0].id;
		var gst = $(this).next().val();
		var gst_rem = gst - val;
		$(this).closest('td').next('td').find('span').text(gst_rem.toFixed(2));
		
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "pos_ajax_all.php?fill=pay_asse&val="+val+'&id='+id,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});
		
	});
	
	$('.quantity_update').focusout(function() {
		var val = $(this).val();
		var id = $(this)[0].id;
		
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "pos_ajax_all.php?fill=rec_po&val="+val+'&id='+id,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});				
	});

	
});
$(function () {
        $("input[class*='txtQty']").keydown(function (event) {

            if (event.shiftKey == true) {
                event.preventDefault();
            }

            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {
            } else {
                event.preventDefault();
            }

            if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                event.preventDefault();

        });
    });

function goBack() {
    window.history.back();
}
</script>

</head>
<body>


<?php include_once ('../navigation.php');
checkAuthorised('sales_order');
?>
<div class="container triple-pad-bottom">
    <div class="row">
		<h1 class="double-pad-bottom"><?PHP if($type == 'pay') { echo "Accounts Payable"; } else if($type == 'receive') { echo "Receiving"; } else { echo SALES_ORDER_NOUN; }?>
        </h1>

                    <a onclick="goBack()" class="btn brand-btn btn-lg ">Back</a>
					<!--<a href="#" class="btn brand-btn btn-lg" onclick="history.go(-1);return false;">Back</a>-->

			  <br>
			

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal myform22" role="form">
		<div id='no-more-tables'>
		<?php
		
		$html = '';
        // START INVENTORY & MISC PRODUCTS
    $result = mysqli_query($dbc, "SELECT * FROM sales_order_product WHERE posid='$posid' AND type_category = 'inventory' AND inventoryid IS NOT NULL");
	$result2 = mysqli_query($dbc, "SELECT * FROM sales_order_product WHERE posid='$posid' AND misc_product IS NOT NULL");
	$num_rows = mysqli_num_rows($result);
	$num_rows2 = mysqli_num_rows($result2);
	if($num_rows > 0 && $num_rows2 > 0) {
		$titler = 'Inventory & Misc Products';
	} else if ($num_rows > 0 && $num_rows2 == 0) {
		$titler = 'Inventory';
	} else if($num_rows == 0 && $num_rows2 > 0) {
		$titler = 'Misc Products';
	}
    if($num_rows > 0 || $num_rows2 > 0) {
		$html .= '<h2>'.$titler.'</h2>
			<table border="1px" style="padding:3px; border:1px solid grey;" class="table table-bordered">
            <tr nobr="true" style="background-color:rgb(140,173,174); color:black;  width:22%;" class="hidden-xs hidden-sm">
            <th>Part#</th><th>Product</th><th>Quantity</th>';

			$html .=  '<th>Quantity Received</th>';
			$html .= '<th>Price</th><th>Total</th>';
			if($type == 'pay') {
					$html .=  '<th>Total Paid</th>';
			}
			$html .= '</tr>';
		while($row = mysqli_fetch_array( $result )) {
			$inventoryid = $row['inventoryid'];
			$posproductid = $row['posproductid'];
			$price = $row['price'];
			$quantity = $row['quantity'];
			$qty_rec = $row['qty_received'];
			if($qty_rec == '' || $qty_rec == NULL) {
						$qty_rec = 0;
			}
			$total_paid = $row['total_paid'];
			if($total_paid == '' || $total_paid == NULL) {
						$total_paid = 0;
			}

			if($inventoryid != '') {
				$amount = $price*$quantity;

				$html .= '<tr>';
				$html .=  '<td data-title="Part Number">'.get_inventory($dbc, $inventoryid, 'part_no').'</td>';
				$html .=  '<td data-title="Product">'.get_inventory($dbc, $inventoryid, 'name').'</td>';
				$html .=  '<td data-title="Quantity">'.$quantity.'</td>';
				if($type == 'receive') {
					$html .=  '<td data-title="Quantity Received"><input type="number" id="'.$posproductid.'" class="quantity_update form-control" value="'.$qty_rec.'" style="max-width:100px;"></td>';
				} else {
					$html .=  '<td  data-title="Quantity Received">'.$qty_rec.'</td>';
				}
				$html .=  '<td data-title="Price">$'.$price.'</td>';
				$html .=  '<td  data-title="Total">$'.number_format($amount,2).'</td>';
				if($type == 'pay') {
					$html .=  '<td  data-title="Total Paid">$<input type="text" id="'.$posproductid.'" class="total_pay_update txtQty form-control" value="'.$total_paid.'" style="max-width:100px;display:inline-block;"></td>';
				}
				$html .= '</tr>';
			}
		}

		$result = mysqli_query($dbc, "SELECT * FROM sales_order_product WHERE posid='$posid' AND misc_product IS NOT NULL");
		while($row = mysqli_fetch_array( $result )) {
			$misc_product = $row['misc_product'];
			$price = $row['price'];
			$qty_rec = $row['qty_received'];
			$posproductid = $row['posproductid'];
			
			if($qty_rec == '' || $qty_rec == NULL) {
						$qty_rec = 0;
			}
			$total_paid = $row['total_paid'];
			if($total_paid == '' || $total_paid == NULL) {
						$total_paid = 0;
			}

			if($misc_product != '') {
				$html .= '<tr>';
				$html .=  '<td data-title="Part Number">Not Available</td>';
				$html .=  '<td data-title="Product">'.$misc_product.'</td>';
				$html .=  '<td data-title="Quantity">1</td>';
				if($type == 'receive') {
					$html .=  '<td data-title="Quantity Received"><input type="number" id="'.$posproductid.'" class="quantity_update form-control" value="'.$qty_rec.'" style="max-width:100px;"></td>';
				} else {
					$html .=  '<td data-title="Quantity Received">'.$qty_rec.'</td>';
				}
				$html .=  '<td data-title="Price">$'.$price.'</td>';
				$html .=  '<td data-title="Total">$'.$price.'</td>';
				if($type == 'pay') {
					$html .=  '<td data-title="Total Paid">$<input type="text" id="'.$posproductid.'" class="total_pay_update txtQty form-control" value="'.$total_paid.'" style="max-width:100px; display:inline-block;"></td>';
				}
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
	}
	// END INVENTORY AND MISC PRODUCTS

	// START PRODUCTS
    $result = mysqli_query($dbc, "SELECT * FROM sales_order_product WHERE posid='$posid' AND type_category = 'product' AND inventoryid IS NOT NULL");
	$num_rows3 = mysqli_num_rows($result);
    if($num_rows3 > 0) {
		if($num_rows > 0 || $num_rows2 > 0) { $html .= '<br>'; }
		$html .= '<h2>Product(s)</h2>
			<table border="1px" style="padding:3px; border:1px solid grey;" class="table table-bordered">
            <tr nobr="true" style="background-color:rgb(140,173,174); color:black;  width:22%;" class="hidden-xs hidden-sm">
            <th>Category</th><th>Heading</th><th>Quantity</th>';
			$html .=  '<th>Quantity Received</th>';
			$html .= '<th>Price</th><th>Total</th>';
			if($type == 'pay') {
					$html .=  '<th>Total Paid</th>';
			}
			$html .= '</tr>';
		while($row = mysqli_fetch_array( $result )) {
			$inventoryid = $row['inventoryid'];
			$posproductid = $row['posproductid'];
			$qty_rec = $row['qty_received'];
			if($qty_rec == '' || $qty_rec == NULL) {
						$qty_rec = 0;
			}
			$total_paid = $row['total_paid'];
			if($total_paid == '' || $total_paid == NULL) {
						$total_paid = 0;
			}
			$price = $row['price'];
			$quantity = $row['quantity'];

			if($inventoryid != '') {
				$amount = $price*$quantity;
				$html .= '<tr>';
				$html .=  '<td  data-title="Category">'.get_products($dbc, $inventoryid, 'category').'</td>';
				$html .=  '<td data-title="Heading">'.get_products($dbc, $inventoryid, 'heading').'</td>';
				$html .=  '<td data-title="Quantity">'.$quantity.'</td>';
				if($type == 'receive') {
					$html .=  '<td data-title="Quantity Received"><input type="number" id="'.$posproductid.'" class="quantity_update form-control" value="'.$qty_rec.'" style="max-width:100px;"></td>';
				} else {
					$html .=  '<td data-title="Quantity Received">'.$qty_rec.'</td>';
				}
				$html .=  '<td data-title="Price">$'.$price.'</td>';
				
				$html .=  '<td data-title="Total">$'.number_format($amount,2).'</td>';
				if($type == 'pay') {
					$html .=  '<td data-title="Total Paid">$<input type="text" id="'.$posproductid.'" class="total_pay_update txtQty form-control" value="'.$total_paid.'" style="max-width:100px;display:inline-block;"></td>';
				}
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
	}
	// END PRODUCTS

	// START SERVICES
    $result = mysqli_query($dbc, "SELECT * FROM sales_order_product WHERE posid='$posid' AND type_category = 'service' AND inventoryid IS NOT NULL");
	$num_rows4 = mysqli_num_rows($result);
    if($num_rows4 > 0) {
		if($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0) { $html .= '<br>'; }
		$html .= '<h2>Service(s)</h2>
			<table border="1px" style="padding:3px; border:1px solid grey;" class="table table-bordered">
            <tr nobr="true" style="background-color:rgb(140,173,174); color:black;  width:22%;" class="hidden-xs hidden-sm">
            <th>Category</th><th>Heading</th><th>Quantity</th>';
			$html .=  '<th>Quantity Received</th>';
			$html .= '<th>Price</th><th>Total</th>';
			if($type == 'pay') {
					$html .=  '<th>Total Paid</th>';
			}
			$html .= '</tr>';
		while($row = mysqli_fetch_array( $result )) {
			$inventoryid = $row['inventoryid'];
			$price = $row['price'];
			$posproductid = $row['posproductid'];
			$qty_rec = $row['qty_received'];
			if($qty_rec == '' || $qty_rec == NULL) {
						$qty_rec = 0;
			}
			$total_paid = $row['total_paid'];
			if($total_paid == '' || $total_paid == NULL) {
						$total_paid = 0;
			}
			$quantity = $row['quantity'];

			if($inventoryid != '') {
				$amount = $price*$quantity;
				$html .= '<tr>';
				$html .=  '<td data-title="Category">'.get_services($dbc, $inventoryid, 'category').'</td>';
				$html .=  '<td data-title="Heading">'.get_services($dbc, $inventoryid, 'heading').'</td>';
				$html .=  '<td data-title="Quantity">'.$quantity.'</td>';
				if($type == 'receive') {
					$html .=  '<td data-title="Quantity Received"><input type="number" id="'.$posproductid.'" class="quantity_update form-control" value="'.$qty_rec.'" style="max-width:100px;"></td>';
				} else {
					$html .=  '<td data-title="Quantity Received">'.$qty_rec.'</td>';
				}
				$html .=  '<td data-title="Price">$'.$price.'</td>';
				$html .=  '<td data-title="Total" >$'.number_format($amount,2).'</td>';
				if($type == 'pay') {
					$html .=  '<td data-title="Total Paid">$<input type="text" id="'.$posproductid.'" class="total_pay_update txtQty form-control" value="'.$total_paid.'" style="max-width:100px;display:inline-block;"></td>';
				}
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
	}
	// END SERVICES
	
	// START VPL
    $result = mysqli_query($dbc, "SELECT * FROM sales_order_product WHERE posid='$posid' AND type_category = 'vpl' AND inventoryid IS NOT NULL");
	$num_rows5 = mysqli_num_rows($result);
    if($num_rows5 > 0) {
		if($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $num_rows4 > 0) { $html .= '<br>'; }

		$html .= '<h2>Vendor Price List Item(s)</h2>
			<table border="1px" style="padding:3px; border:1px solid grey;" class="table table-bordered">
            <tr nobr="true" style="background-color:rgb(140,173,174); color:black;  width:22%;" class="hidden-xs hidden-sm">
            <th>Part#</th><th>Product</th><th>Quantity</th>';
			$html .=  '<th>Quantity Received</th>';
			$html .= '<th>Price</th><th>Total</th>';
			if($type == 'pay') {
					$html .=  '<th>Total Paid</th>';
			}
			$html .= '</tr>';
		while($row = mysqli_fetch_array( $result )) {
			$inventoryid = $row['inventoryid'];
			$price = $row['price'];
			$qty_rec = $row['qty_received'];
			$posproductid = $row['posproductid'];
			if($qty_rec == '' || $qty_rec == NULL) {
						$qty_rec = 0;
			}
			$total_paid = $row['total_paid'];
			if($total_paid == '' || $total_paid == NULL) {
						$total_paid = 0;
			}
			$quantity = $row['quantity'];

			if($inventoryid != '') {
				$amount = $price*$quantity;

				$html .= '<tr>';
				$html .=  '<td data-title="Part Number">'.get_vpl($dbc, $inventoryid, 'part_no').'</td>';
				$html .=  '<td data-title="Product">'.get_vpl($dbc, $inventoryid, 'name').'</td>';
				$html .=  '<td data-title="Quantity">'.$quantity.'</td>';
				if($type == 'receive') {
					$html .=  '<td data-title="Quantity Received"><input type="number" id="'.$posproductid.'" class="quantity_update form-control" value="'.$qty_rec.'" style="max-width:100px;"></td>';
				} else {
					$html .=  '<td data-title="Quantity Received">'.$qty_rec.'</td>';
				}
				$html .=  '<td data-title="Price">$'.$price.'</td>';
				$html .=  '<td data-title="Total" >$'.number_format($amount,2).'</td>';
				if($type == 'pay') {
					$html .=  '<td data-title="Total Paid">$<input type="text" id="'.$posproductid.'" class="total_pay_update txtQty form-control" value="'.$total_paid.'" style="max-width:100px;display:inline-block;"></td>';
				}
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
	}
	// END VPL

	// ADDITIONAL PAYMENTS
				$gst = 0;
				$gst_paid = 0;
				$pst = 0;
				$pst_paid = 0;
				$delivery = 0;
				$delivery_paid = 0;
				$assembly = 0;
				$assembly_paid = 0;
	if($type == 'pay') {
    $result = mysqli_query($dbc, "SELECT * FROM sales_order WHERE posid='$posid'	");
	$num_rows5 = mysqli_num_rows($result);
    if($num_rows5 > 0) {
		
		$amount = 0;
		$quantity = 0;
		$qty_rec = 0;
		$total_paid = 0;
		
			while($row = mysqli_fetch_array( $result )) {
				$posid = $row['posid'];
				$gst = $row['gst'];
				$gst_paid = $row['gst_paid'];
				$gst_rem = $gst - $gst_paid;
				$pst = $row['pst'];
				$pst_paid = $row['pst_paid'];
				$pst_rem = $pst - $pst_paid;
				$delivery = $row['delivery'];
				$delivery_paid = $row['delivery_paid'];
				$delivery_rem = $delivery - $delivery_paid;
				$assembly = $row['assembly'];
				$assembly_paid = $row['assembly_paid'];
				$assembly_rem = $assembly - $assembly_paid;
			}
		if(($gst !== '' && $gst > 0) || ($pst !== '' && $pst > 0) || ($delivery !== '' && $delivery > 0) || ($assembly !== '' && $assembly > 0)){	
		$html .= '<h2>Additional Expenses</h2>
			<table border="1px" style="padding:3px; border:1px solid grey;" class="table table-bordered">
            <tr nobr="true" style="background-color:rgb(140,173,174); color:black;  width:22%;" class="hidden-xs hidden-sm">
            <th>Description</th><th>Amount</th><th>Amount Paid</th><th>Amount Remaining</th></tr>';
		}
		if($gst !== '' && $gst > 0) {
				$html .= '<tr>';
				$html .=  '<td data-title="Description">GST</td>';
				$html .=  '<td data-title="Amount">$'.$gst.'</td>';
				$html .=  '<td data-title="Amount Paid">$<input type="text" id="'.$posid.'" class="gst_update form-control txtQty" value="'.$gst_paid.'" style="max-width:100px; display:inline-block;"><input type="hidden" value="'.$gst.'"></td>';
				$html .=  '<td data-title="Amount Remaining">$<span class="amm_remaining">'.$gst_rem.'</span></td>';
				$html .= '</tr>';
		}	
		
		if($pst !== '' && $pst > 0) {
				$html .= '<tr>';
				$html .=  '<td data-title="Description">PST</td>';
				$html .=  '<td data-title="Amount">$'.$pst.'</td>';
				$html .=  '<td data-title="Amount Paid">$<input type="text" id="'.$posid.'" class="pst_update form-control txtQty" value="'.$pst_paid.'" style="max-width:100px; display:inline-block;"><input type="hidden" value="'.$pst.'"></td>';
				$html .=  '<td data-title="Amount Remaining">$<span class="amm_remaining">'.$pst_rem.'</span></td>';
				$html .= '</tr>';
		}	
		
		if($delivery !== '' && $delivery > 0) {
				$html .= '<tr>';
				$html .=  '<td data-title="Description">Delivery</td>';
				$html .=  '<td data-title="Amount">$'.$delivery.'</td>';
				$html .=  '<td data-title="Amount Paid">$<input type="text" id="'.$posid.'" class="del_update form-control txtQty" value="'.$delivery_paid.'" style="max-width:100px; display:inline-block;"><input type="hidden" value="'.$delivery.'"></td>';
				$html .=  '<td data-title="Amount Remaining">$<span class="amm_remaining">'.$delivery_rem.'</span></td>';
				$html .= '</tr>';
		}	
		
		if($assembly !== '' && $assembly > 0) {
				$html .= '<tr>';
				$html .=  '<td data-title="Description">Assembly</td>';
				$html .=  '<td data-title="Amount">$'.$assembly.'</td>';
				$html .=  '<td data-title="Amount Paid">$<input type="text" id="'.$posid.'" class="asse_update form-control txtQty" value="'.$assembly_paid.'" style="max-width:100px; display:inline-block;"><input type="hidden" value="'.$assembly.'"></td>';
				$html .=  '<td data-title="Amount Remaining">$<span class="amm_remaining">'.$assembly_rem.'</span></td>';
				$html .= '</tr>';
		}	

		$html .= '</table>';
	}
	// END ADDITIONAL PAYMENTS
	}
	
	// START SUM UP ALL
    $result = mysqli_query($dbc, "SELECT * FROM sales_order_product WHERE posid='$posid'	");
	$num_rows5 = mysqli_num_rows($result);
    if($num_rows5 > 0) {
		if($num_rows > 0 || $num_rows2 > 0 || $num_rows3 > 0 || $num_rows4 > 0) { $html .= '<br>'; }
		$amount = 0;
		$quantity = 0;
		$qty_rec = 0;
		$total_paid = 0;
		$html .= '<h2>Summary</h2>
			<table border="1px" style="padding:3px; border:1px solid grey;" class="table table-bordered">
            <tr nobr="true" style="background-color:rgb(140,173,174); color:black;  width:22%;" class="hidden-xs hidden-sm">
            <th>Quantity Ordered</th><th>Quantity Received</th><th>Quantity Remaining</th><th>Total Cost</th><th>Total Paid</th><th>Total Remaining</th></tr>';
		while($row = mysqli_fetch_array( $result )) {
			$inventoryid = $row['inventoryid'];
			$price = $row['price'];
			$quantity += $row['quantity'];
			$quantity_single = $row['quantity'];
			$qty_rec += $row['qty_received'];
			$total_paid += $row['total_paid'];

			if($inventoryid != '') {
				$amount += ($price*$quantity_single);
			} else { $amount += $price; 
					$quantity += 1;}
		}
		$qty_rem = $quantity-$qty_rec;
		$total_paid = $total_paid+$delivery_paid+$assembly_paid+$gst_paid+$pst_paid;
		$amount = $amount+$assembly+$delivery+$pst+$gst;
		$price_rem = $amount-$total_paid;
		$html .= '<tr>';
				$html .=  '<td data-title="Quantity Ordered">'.round($quantity,2).'</td>';
				$html .=  '<td data-title="Quantity Received">'.round($qty_rec,2).'</td>';
				$html .=  '<td data-title="Quantity Remaining">'.round($qty_rem,2).'</td>';
				$html .=  '<td data-title="Total Cost">$<span class="total_amm">'.round($amount,2).'</span></td>';
				$html .=  '<td data-title="Total Paid">$<span class="total_paidler">'.round($total_paid,2).'</span></td>';
				$html .=  '<td data-title="Total Remaining" >$<span class="total_rem">'.round($price_rem,2).'</span></td>';
				$html .= '</tr>';
		$html .= '</table>';
	}
	// END SUM UP ALL
	echo $html;
	?>

              <div class="form-group">
                <div class="col-sm-4 clearfix">
                    <a onclick="goBack()" class="btn brand-btn btn-lg pull-right">Back</a>
					<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
                    <button type="button" onClick="history.go(0)" name="submit_pos" value="Submit" class="btn brand-btn btn-lg pull-right">Update Summary</button>
                </div>
              </div>
              
			</div>
        </form>

	</div>
</div>

<?php include ('../footer.php'); ?>