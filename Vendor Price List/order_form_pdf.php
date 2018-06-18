<?php include_once('../include.php');
include('../tcpdf/tcpdf.php');

if($posid > 0) {
	$pos = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `purchase_orders` WHERE `posid` = '$posid'"));
	$vendorid = $pos['contactid'];
	$vpl_name = $pos['vpl_name'];
	$projectid = $pos['projectid'];
	$ticketid = $pos['ticketid'];
	$businessid = $pos['businessid'];
	$subtotal = $pos['sub_total'];
	$total_before_tax = $pos['total_before_tax'];
	$tax_price = floatval($pos['gst']) + floatval($pos['pst']);
	$total_price = $pos['total_price'];

	$all_items = [];
    $pos_items = mysqli_query($dbc, "SELECT * FROM `purchase_orders_product` WHERE `posid` = '$posid' AND `type_category` = 'vpl'");
    while($row = mysqli_fetch_assoc($pos_items)) {
    	$vpl_item = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `vendor_price_list` WHERE `inventoryid` = '".$row['inventoryid']."'"));
    	$all_items[$vpl_item['category']][] = ['part_no'=>$vpl_item['part_no'],'name'=>$vpl_item['name'],'price'=>number_format($row['price'],2),'quantity'=>($row['quantity'] > 0 ? $row['quantity'] : 0)];
    }

    $value_config = ','.get_config($dbc, 'vpl_orderforms_fields').',';

	class MYPDF extends TCPDF {

		//Page header
		public function Header() {
		}

		//Page footer
		public function Footer() {
		}
	}

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
	$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
	$pdf->SetAutoPageBreak(TRUE, 25);
	$pdf->AddPage();
	$pdf->SetFont('helvetica', '', 9);
	$pdf->setCellHeightRatio(1);

	$html = '';
	$html .= '<h1 style="text-align: center;">Order Form: '.(!empty(get_client($dbc, $vendorid)) ? get_client($dbc, $vendorid) : get_contact($dbc, $vendorid)).' - '.$vpl_name.'</h2>';


	foreach($all_items as $category => $items) {
		$html .= '<h3>'.$category.'</h3>';
		$html .= '<table border="1" cellpadding="2">';
		$html .= '<tr>';
	    if(strpos($value_config, ',Category,') !== FALSE) {
	        $html .='<th>Category</th>';
	    }
	    if(strpos($value_config, ',Part #,') !== FALSE) {
	        $html .='<th>Part #</th>';
	    }
	    if(strpos($value_config, ',Name,') !== FALSE) {
	        $html .='<th>Name</th>';
	    }
	    if(strpos($value_config, ',Price,') !== FALSE) {
	        $html .='<th>Price</th>';
	    }
	    if(strpos($value_config, ',Quantity,') !== FALSE) {
	        $html .='<th>Quantity</th>';
	    }
	    $html .= '</tr>';

	    foreach($items as $row) {
	    	$html .= '<tr>';
		    if(strpos($value_config, ',Category,') !== FALSE) {
		        $html .='<td>'.$category.'</td>';
		    }
		    if(strpos($value_config, ',Part #,') !== FALSE) {
		        $html .='<td>'.$row['part_no'].'</td>';
		    }
		    if(strpos($value_config, ',Name,') !== FALSE) {
		        $html .='<td>'.$row['name'].'</td>';
		    }
		    if(strpos($value_config, ',Price,') !== FALSE) {
		        $html .='<td>'.$row['price'].'</td>';
		    }
		    if(strpos($value_config, ',Quantity,') !== FALSE) {
		        $html .='<td>'.$row['quantity'].'</td>';
		    }
		    $html .= '</tr>';
	    }
	    $html .= '</table>';
	}

    $html .= '<h3>Totals</h3>';
    $html .= '<table border="1" cellpadding="2">';
    $html .= '<tr><td style="width:85%; text-align:right;"><b>Subtotal:</b></td><td style="width:15%; text-align:right;">$'.number_format($subtotal,2).'</td></tr>';
    $html .= '<tr><td style="width:85%; text-align:right;"><b>Total Before Tax:</b></td><td style="width:15%; text-align:right;">$'.number_format($total_before_tax,2).'</td></tr>';
    $html .= '<tr><td style="width:85%; text-align:right;"><b>Tax Price:</b></td><td style="width:15%; text-align:right;">$'.number_format($tax_price,2).'</td></tr>';
    $html .= '<tr><td style="width:85%; text-align:right;"><b>Total Price:</b></td><td style="width:15%; text-align:right;">$'.number_format($total_price,2).'</td></tr>';
    $html .= '</table>';    

    $pdf->writeHTML(utf8_encode($html), true, false, true, false, '');

    if(!file_exists('../Purchase Order/download')) {
    	mkdir('../Purchase Order/download', 0777, true);
    }

	$filename = 'purchase_order_'.$posid.'.pdf';
	$pdf->Output('../Purchase Order/download/'.$filename, 'F');

	echo '<script type="text/javascript">
		window.location.replace("../Purchase Order/download/'.$filename.'", "_blank");
	</script>';
}