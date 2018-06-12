<?php
/*
 * Sales Order Generate PDF
 */
error_reporting(0);
include ('../include.php');
include('../tcpdf/tcpdf.php');
if(!empty($_GET['sotid'])) {
	$sotid = $_GET['sotid'];
	$query = "SELECT * FROM `sales_order_temp` WHERE `sotid` = '$sotid'";
	$result = mysqli_fetch_assoc(mysqli_query($dbc, $query));

	$so_type = $result['sales_order_type'];
	$sales_order_name = $result['name'];
	$primary_staff = $result['primary_staff'];
	$assign_staff = $result['assign_staff'];
	$customerid = $result['customerid'];
	$business_contact = $result['business_contact'];
	$classification = $result['classification'];
	$status = $result['status'];
	$next_action = $result['next_action'];
	$next_action_date = $result['next_action_date'];
	$discount_type = $result['discount_type'];
	$discount_value = $result['discount_value'];
	$delivery_type = $result['delivery_type'];
	$delivery_amount = $result['delivery_amount'];
	$delivery_address = $result['delivery_address'];
	$contractorid = $result['contractorid'];
	$assembly_amount = $result['assembly_amount'];
	$payment_type = $result['payment_type'];
	$deposit_paid = $result['deposit_paid'];
	$comment = $result['comment'];
	$ship_date = $result['ship_date'];
	$due_date = $result['due_date'];

	$custom_designs_query = "SELECT * FROM `sales_order_upload_temp` WHERE `parentsotid` = '$sotid'";
	$custom_designs = mysqli_fetch_all(mysqli_query($dbc, $custom_designs_query),MYSQLI_ASSOC);
} else if (!empty($_GET['posid'])) {
	$posid = $_GET['posid'];
	$query = "SELECT * FROM `sales_order` WHERE `posid` = '$posid'";
	$result = mysqli_fetch_assoc(mysqli_query($dbc, $query));

	$so_type = $result['sales_order_type'];
	$sales_order_name = $result['name'];
	$primary_staff = $result['primary_staff'];
	$assign_staff = $result['assign_staff'];
	$customerid = $result['contactid'];
	$business_contact = $result['business_contact'];
	$classification = $result['classification'];
	$status = $result['status'];
	$next_action = $result['next_action'];
	$next_action_date = $result['next_action_date'];
	$discount_type = $result['discount_type'];
	$discount_value = $result['discount_value'];
	$delivery_type = $result['delivery_type'];
	$delivery_amount = $result['delivery'];
	$delivery_address = $result['delivery_address'];
	$contractorid = $result['contractorid'];
	$assembly_amount = $result['assembly'];
	$payment_type = $result['payment_type'];
	$deposit_paid = $result['deposit_paid'];
	$comment = $result['comment'];
	$ship_date = $result['ship_date'];
	$due_date = $result['due_date'];
	$sotid = $result['sotid'];

	$custom_designs_query = "SELECT * FROM `sales_order_upload` WHERE `posid` = '$posid'";
	$custom_designs = mysqli_fetch_all(mysqli_query($dbc, $custom_designs_query),MYSQLI_ASSOC);
}

$field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `field_config_so`"));
$value_config = ','.$field_config['fields'].',';
$cat_config = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_so_contacts` WHERE `sales_order_type` = '$so_type'"),MYSQLI_ASSOC);
if(!empty($so_type)) {
    $value_config = ','.get_config($dbc, 'so_'.config_safe_str($so_type).'_fields').',';
}
$contact_categories_config = [];
foreach ($cat_config as $contact_cat) {
    $contact_categories_config[] = $contact_cat['contact_category'];
}


if(!empty($classification)) {
	$classification_query = " AND CONCAT(',',`classification`,',') LIKE '%,$classification,%'";
} else {
	$classification_query = "";
}

//All Contacts
$contact_categories = [];
foreach ($contact_categories_config as $contact_category) {
	$query = "SELECT * FROM `contacts` WHERE `businessid` = '$customerid' AND `category` = '$contact_category' AND `deleted` = 0".$classification_query;
	$contact_categories[$contact_category] = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, $query),MYSQLI_ASSOC));
}
if(empty($contact_categories_config)) {
	$contact_categories['**no_cat**'][0] = $customerid;
}

//Product Details
$product_details = [];
$contact_order_details = [];
foreach ($contact_categories_config as $contact_category) {
	$query = "SELECT * FROM `sales_order_product_temp` WHERE `parentsotid` = '$sotid'";
	$result = mysqli_fetch_all(mysqli_query($dbc, $query),MYSQLI_ASSOC);
	foreach ($result as $row) {
		$product_details[$contact_category][$row['item_type']][$row['heading_name']][$row['sotid']] = ['category' => $row['item_category'], 'name' => $row['item_name'], 'price' => $row['item_price']];
		$product_details[$contact_category][$row['item_type']][$row['heading_name']]['mandatory_quantity'] = $row['mandatory_quantity'];

		$query = "SELECT * FROM `sales_order_product_details_temp` WHERE `parentsotid` = '".$row['sotid']."'";
		$result_details = mysqli_fetch_all(mysqli_query($dbc, $query),MYSQLI_ASSOC);
		foreach ($result_details as $row_details) {
			$contact_order_details[$contact_category][$row_details['contactid']][$row_details['sotid']] = ['category' => $row['item_category'], 'name' => $row['item_name'], 'price' => $row['item_price'], 'quantity' => $row_details['quantity']];
		}
	}
}
if(empty($contact_categories_config)) {
	$query = "SELECT * FROM `sales_order_product_temp` WHERE `parentsotid` = '$sotid' AND `contact_category` = '**no_cat**'";
	$result = mysqli_fetch_all(mysqli_query($dbc, $query),MYSQLI_ASSOC);
	foreach ($result as $row) {
		$product_details['**no_cat**'][$row['item_type']][$row['heading_name']][$row['sotid']] = ['category' => $row['item_category'], 'name' => $row['item_name'], 'price' => $row['item_price']];
		$product_details['**no_cat**'][$row['item_type']][$row['heading_name']]['mandatory_quantity'] = $row['mandatory_quantity'];

		$query = "SELECT * FROM `sales_order_product_details_temp` WHERE `parentsotid` = '".$row['sotid']."'";
		$result_details = mysqli_fetch_all(mysqli_query($dbc, $query),MYSQLI_ASSOC);
		foreach ($result_details as $row_details) {
			$contact_order_details['**no_cat**'][$row_details['contactid']][$row_details['sotid']] = ['category' => $row['item_category'], 'name' => $row['item_name'], 'price' => $row['item_price'], 'quantity' => $row_details['quantity']];
		}
	}
}

// GST PST
$get_pos_tax = get_config($dbc, 'sales_order_tax');
$gst_total = 0;
$pst_total = 0;
if($get_pos_tax != '') {
    $pos_tax = explode('*#*',$get_pos_tax);

    $total_count = mb_substr_count($get_pos_tax,'*#*');
    for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
        $pos_tax_name_rate = explode('**',$pos_tax[$eq_loop]);

        if (strcasecmp($pos_tax_name_rate[0], 'gst') == 0 && $pos_tax_name_rate[3] != 'Yes') {
            $gst_total = $gst_total + $pos_tax_name_rate[1];
        }

        if (strcasecmp($pos_tax_name_rate[0], 'pst') == 0 && $pos_tax_name_rate[3] != 'Yes') {
            $pst_total = $pst_total + $pos_tax_name_rate[1];
        }
    }
}

//PDF Settings
$pdf_settings = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_so_pdf`"));

$header_logo = !empty($pdf_settings['header_logo']) ? $pdf_settings['header_logo'] : '';
$header_logo_align = !empty($pdf_settings['header_logo_align']) ? $pdf_settings['header_logo_align'] : 'R';
$header_text = !empty($pdf_settings['header_text']) ? $pdf_settings['header_text'] : '';
$header_align = !empty($pdf_settings['header_align']) ? $pdf_settings['header_align'] : 'L';

$footer_logo = !empty($pdf_settings['footer_logo']) ? $pdf_settings['footer_logo'] : '';
$footer_logo_align = !empty($pdf_settings['footer_logo_align']) ? $pdf_settings['footer_logo_align'] : 'L';
$footer_text = !empty($pdf_settings['footer_text']) ? $pdf_settings['footer_text'] : '';
$footer_align = !empty($pdf_settings['footer_align']) ? $pdf_settings['footer_align'] : 'C';

$body_font = !empty($pdf_settings['body_font']) ? $pdf_settings['body_font'] : 'helvetica';
$body_size = !empty($pdf_settings['body_size']) ? $pdf_settings['body_size'] : 9;
$body_color = !empty($pdf_settings['body_color']) ? $pdf_settings['body_color'] : '#000000';

DEFINE(FORM_HEADER_LOGO, $header_logo);
DEFINE(FORM_HEADER_LOGO_ALIGN, $header_logo_align);
DEFINE(FORM_HEADER_TEXT, html_entity_decode($header_text));
DEFINE(FORM_HEADER_ALIGN, $header_align);

DEFINE(FORM_FOOTER_LOGO, $footer_logo);
DEFINE(FORM_FOOTER_LOGO_ALIGN, $footer_logo_align);
DEFINE(FORM_FOOTER_TEXT, html_entity_decode($footer_text));
DEFINE(FORM_FOOTER_ALIGN, $footer_align);

class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		if(FORM_HEADER_LOGO != '') {
			$image_file = '../Sales Order/download/'.FORM_HEADER_LOGO;
            $this->Image($image_file, 10, 5, 0, 25, '', '', 'T', false, 300, FORM_HEADER_LOGO_ALIGN, false, false, 0, false, false, false);
		}

		if(FORM_HEADER_TEXT != '') {
			$this->setCellHeightRatio(0.7);
            $this->writeHTMLCell(0, 0, 7.5 , 5, FORM_HEADER_TEXT, 0, 0, false, true, FORM_HEADER_LOGO, true);
		}
	}

	//Page footer
	public function Footer() {
        if(FORM_FOOTER_TEXT != '') {
            $this->SetY(-20);
            $this->setCellHeightRatio(0.7);
            $this->writeHTMLCell(0, 0, '' , '', FORM_FOOTER_TEXT, 0, 0, false, true, FORM_FOOTER_ALIGN, true);
        }

        if(FORM_FOOTER_LOGO != '') {
            $image_file = '../Sales Order/download/'.FORM_FOOTER_LOGO;
            $this->Image($image_file, 0, 255, 0, 15, '', '', 'T', false, 300, FORM_FOOTER_LOGO_ALIGN, false, false, 0, false, false, false);
        }
	}
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
$pdf->SetMargins(PDF_MARGIN_LEFT, (FORM_HEADER_LOGO != '' ? 35 : (!empty($header_text) ? 20 : 10)), PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 9);
$pdf->setCellHeightRatio(1);

$html = '';

$html .= "<style>
		th { background-color: #ccc; font-weight: bold; }
        h1,h2,h3,h4,h5 {
            font-family: $body_font;
            color: $body_color;
        }
        th,tr,td,body,p {
            font-family: $body_font;
            font-size: $body_size;
            color: $body_color;
        }
	</style>";

$html .= '<p style="text-align:center"><h1>'.(!empty(get_client($dbc, $customerid)) ? get_client($dbc, $customerid) : get_contact($dbc, $customerid)).(!empty($classification) ? ': '.$classification : '').' - '.(!empty($sales_order_name) ? $sales_order_name : (!empty($sotid) ? SALES_ORDER_NOUN.' Form #'.$sotid : SALES_ORDER_NOUN.' #'.$posid)).'</h1></p>';

//Sales Order Information
$html .= '<h2>'.SALES_ORDER_NOUN.' Information</h2>';
$details = [];
if(!empty(get_config($dbc, 'company_name'))) {
	$details['Company'] = get_config($dbc, 'company_name');
}
if(!empty(get_config($dbc, 'company_phone_number'))) {
	$details['Company Phone #'] = get_config($dbc, 'company_phone_number');
}
if(!empty(get_config($dbc, 'company_address'))) {
	$details['Company Address'] = get_config($dbc, 'company_address');
}
if(!empty($primary_staff)) {
	$details['Primary Staff'] = get_contact($dbc, $primary_staff);
}
if(!empty($assign_staff)) {
	$assigned_staff = [];
	foreach(explode(',',$assign_staff) as $id) {
		$assigned_staff[] = get_contact($dbc, $id);
	}
	$details['Assigned Staff'] = implode('<br>', $assigned_staff);
}
if(!empty($classification)) {
	$details['Classification'] = $classification;
}
if(!empty($business_contact)) {
	$assigned_staff = [];
	foreach(explode(',',$assign_staff) as $id) {
		$assigned_staff[] = get_contact($dbc, $id);
	}
	$details['Business Contact'] = implode('<br>', $assigned_staff);
}
if(!empty($delivery_type)) {
	$details['Delivery Type'] = $delivery_type;
	if($delivery_type != 'Pick-Up' && !empty($delivery_address)) {
		$details['Delivery Address'] = $delivery_address;
	}
	if(($delivery_type == 'Drop Ship' || $delivery_type == 'Shipping') && !empty($contractorid)) {
		$details['Contractor'] = get_contact($dbc, $contractorid);
	}
}
if(!empty($comment)) {
	$details['Comments'] = html_entity_decode($comment);
}

$html .= '<table>';
$html .= '<tr>';

$html .= '<td style="width:50%;"><table cellpadding="1">';
$table_i = 0;
foreach($details as $key => $detail) {
	if($table_i % 2 == 0) {
		$html .= '<tr>';
		$html .= '<td style="width:40%;"><b>'.$key.':</b></td>';
		$html .= '<td style="width:60%;">'.$detail.'</td>';
		$html .= '</tr>';	
	}
	$table_i++;
}
$html .= '</table></td>';

$html .= '<td style="width:50%;"><table cellpadding="1">';
$table_i = 0;
foreach($details as $key => $detail) {
	if($table_i % 2 == 1) {
		$html .= '<tr>';
		$html .= '<td style="width:40%;"><b>'.$key.':</b></td>';
		$html .= '<td style="width:60%;">'.$detail.'</td>';
		$html .= '</tr>';	
	}
	$table_i++;
}
$html .= '</table></td>';

$html .= '</tr>';
$html .= '</table>';
$html .= '<br>';

//Customer Information
$field_list = [
	'first_name'=>'First Name',
	'last_name'=>'Last Name',
    'region'=>'Region',
    'con_locations'=>'Location',
    'classification'=>'Classification',
    'address'=>'Address',
    'office_phone'=>'Phone Number',
    'email_address'=>'Email Address',
    'payment_type'=>'Payment Type',
    'budget'=>'Budget',
    'preferred_booking_time'=>'Preferred Booking Time',
    'location_square_footage'=>'Square Footage',
    'location_num_bathrooms'=>'Number of Bathrooms',
    'location_alarm'=>'Alarm System Information',
    'location_pets'=>'Pets',
    'notification_type'=>'Notification Type',
    'booking_extra'=>'Extra Information'
];
$customer_fields = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_so`"))['customer_fields'];
$customer_fields = explode(',', $customer_fields);
if(!empty($so_type)) {
    $customer_fields = explode(',',get_config($dbc, 'so_'.config_safe_str($so_type).'_customer_fields'));
}
$num_fields = ceil(count($customer_fields)/2);
if(count($customer_fields) > 0) {
	$html .= '<h2>Customer Information</h2>';
	$html .= '<table>';
	$html .= '<tr>';

	$html .= '<td style="width:50%;"><table cellpadding="1">';
	for ($table_i = 0; $table_i < $num_fields; $table_i++) {
		$html .= '<tr>';
		$html .= '<td style="width:40%;"><b>'.$customer_fields[$table_i].':</b></td>';
		$html .= '<td style="width:60%;">'.html_entity_decode(get_contact($dbc, $customerid, array_search($customer_fields[$table_i],$field_list))).'</td>';
		$html .= '</tr>';
	}
	$html .= '</table></td>';

	$html .= '<td style="width:50%;"><table cellpadding="1">';
	for ($table_i = $num_fields; $table_i < count($customer_fields); $table_i++) {
		$html .= '<tr>';
		$html .= '<td style="width:40%;"><b>'.$customer_fields[$table_i].':</b></td>';
		$html .= '<td style="width:60%;">'.html_entity_decode(get_contact($dbc, $customerid, array_search($customer_fields[$table_i],$field_list))).'</td>';
		$html .= '</tr>';
	}
	$html .= '</table></td>';

	$html .= '</tr>';
	$html .= '</table>';
}

//Custom Designs
if(!empty($custom_designs)) {
	$html .= '<h2>Custom Designs</h2>';	
	$html .= '<table border="1" cellpadding="2">';
	$html .= '<tr>';
	$html .= '<th style="width:40%;">Design Name</th>';
	$html .= '<th style="width:60%;">Design</th>';
	$html .= '</tr>';
	foreach($custom_designs as $custom_design) {
		$html .= '<tr>';
		$html .= '<td style="width:40%;">'.$custom_design['name'].'</td>';
		$html .= '<td style="width:60%;">';
		if(file_get_contents(WEBSITE_URL.'/Sales Order/download/'.$custom_design['file'])) {
			$file_name = str_replace(' ','%20',WEBSITE_URL.'/Sales Order/download/'.$custom_design['file']);
			$html .= '<img src="'.$file_name.'">';
		}
		$html .= '</td>';
		$html .= '</tr>';
	}
	$html .= '</table>';
	$html .= '<br>';
}

// //Contact Category Roster & Order Options
// foreach ($contact_categories as $contact_category => $contacts) {
// 	if($contact_category != '**no_cat**') {
// 		$html .= '<h2>'.$contact_category.' Roster</h2>';
// 		$html .= '<table cellpadding="1">';
// 		$i = 0;
// 		foreach ($contacts as $contact) {
// 			if ($i == 0) {
// 				$html .= '<tr>';
// 			}
// 			$html .= '<td style="width:50%;">'.get_contact($dbc, $contact).'</td>';
// 			$i++;
// 			if ($i == 2) {
// 				$i = 0;
// 				$html .= '</tr>';
// 			}
// 		}
// 		if ($i == 1) {
// 			$html .= '</tr>';
// 		}
// 		$html .= '</table>';
// 		$html .= '<br>';
// 	}

// 	$html .= '<h2>'.($contact_category != '**no_cat**' ? $contact_category : 'Sales').' Order Options</h2>';
	
// 	foreach ($product_details[$contact_category] as $item_type => $heading) {
// 		$html .= '<h2>'.ucfirst($item_type).'</h2>';
// 		foreach ($heading as $heading_name => $products) {
// 			$html .= '<h4>'.$heading_name.($products['mandatory_quantity'] > 0 ? ' (Mandatory Quantity: '.$products['mandatory_quantity'].')' : '').'</h4>';
// 			$html .= '<table border="1" cellpadding="2">';
// 			$html .= '<tr>';
// 			$html .= '<th style="width: 30%;">Category</th>';
// 			$html .= '<th style="width: 55%;">Product</th>';
// 			$html .= '<th style="width: 15%;">Price</th>';
// 			$html .= '</tr>';
// 			foreach ($products as $id => $product) {
// 				if ($id != 'mandatory_quantity') {
// 					$html .= '<tr>';
// 					$html .= '<td style="width: 30%;">'.$product['category'].'</td>';
// 					$html .= '<td style="width: 55%;">'.$product['name'].'</td>';
// 					$html .= '<td style="width: 15%; text-align:right;">$'.number_format(trim($product['price'],'$'), 2, '.', '').'</td>';
// 					$html .= '</tr>';
// 				}
// 			}
// 			$html .= '</table>';
// 		}
// 	}
// 	$html .= '<br>';
// }

// $html .= '<br pagebreak="true">';

$html .= '<p style="text-align:center"><h1>'.SALES_ORDER_NOUN.' Details</h1></p>';

//Contact Order Details
$subtotal = 0;
foreach ($contact_categories as $contact_category => $contacts) {
	if($contact_category != '**no_cat**') {
		$html .= '<h2>'.$contact_category.' Order Details</h2>';
	}
	foreach ($contacts as $contact_id) {
		$html .= '<h4>'.(!empty(get_client($dbc, $contact_id)) ? get_client($dbc, $contact_id) : get_contact($dbc, $contact_id)).'</h4>';

		if(!empty($contact_order_details[$contact_category][$contact_id])) {
			$html .= '<table border="1" cellpadding="2">';
			$html .= '<tr>';
			$html .= '<th style="width: 70%">Product</th>';
			$html .= '<th style="width: 15%">Quantity</th>';
			$html .= '<th style="width: 15%">Price</th>';
			$html .= '</tr>';
			foreach ($contact_order_details[$contact_category][$contact_id] as $id => $product) {
				if($product['quantity'] > 0) {
					$html .= '<tr>';
					$html .= '<td style="width: 70%;">'.$product['category'].': '.$product['name'].'</td>';
					$html .= '<td style="width: 15%;">'.$product['quantity'].'</td>';
					$html .= '<td style="width: 15%; text-align:right;">$'.number_format(trim($product['price'] * $product['quantity'],'$'), 2, '.', '').'</td>';
					$html .= '</tr>';
					$subtotal += number_format(trim($product['price'] * $product['quantity'],'$'), 2, '.', '');
				}
			}
			$html .= '</table>';
		} else {
			$html .= 'No Items Found';
		}
	}
	$html .= '<br>';
}

//Order Details
$html .= '<h2>Order Details</h2>';
$html .= '<table border="1" cellpadding="2">';

//Subtotal
$html .= '<tr><td style="width:85%; text-align:right;"><b>Subtotal:</b></td><td style="width:15%; text-align:right;">$'.number_format($subtotal, 2, '.', '').'</td></tr>';

//Discount
$discount_amount = 0;
if($discount_value > 0 && ($discount_type == '%' || $discount_type == '$')) {
	if($discount_type == '%') {
		$discount_amount = number_format(floatval($subtotal * ($discount_value / 100)), 2, '.', '');
	} else if($discount_type == '$') {
		$discount_amount = $discount_value;
	}
	$html .= '<tr><td style="width:85%; text-align:right;"><b>Discount:</b></td><td style="width:15%; text-align:right;">- $'.number_format($discount_amount, 2, '.', '').'</td></tr>';
	$html .= '<tr><td style="width:85%; text-align:right;"><b>Total After Discount:</b></td><td style="width:15%; text-align:right;">$'.number_format($subtotal - $discount_amount, 2, '.', '').'</td></tr>';
}

//Delivery
if($delivery_type != 'Company Delivery' && $delivery_amount > 0) {
	$html .= '<tr><td style="width:85%; text-align:right;"><b>Delivery:</b></td><td style="width:15%; text-align:right;">$'.number_format($delivery_amount, 2, '.', '').'</td></tr>';
}

//Assembly
if($assembly_amount > 0) {
	$html .= '<tr><td style="width:85%; text-align:right;"><b>Assembly:</b></td><td style="width:15%; text-align:right;">$'.number_format($assembly_amount, 2, '.', '').'</td></tr>';
}

//Total Before Tax
$total_before_tax = number_format($subtotal - $discount_amount + $delivery_amount + $assembly_amount, 2, '.', '');
$html .= '<tr><td style="width:85%; text-align:right;"><b>Total Before Tax:</b></td><td style="width:15%; text-align:right;">$'.number_format($total_before_tax, 2, '.', '').'</td></tr>';

//GST
$gst_amount = 0;
if($gst_total > 0) {
	$gst_amount = number_format($total_before_tax * $gst_total / 100, 2, '.', '');
	$html .= '<tr><td style="width:85%; text-align:right;"><b>GST:</b></td><td style="width:15%; text-align:right;">$'.number_format($gst_amount, 2, '.', '').'</td></tr>';
}

//PST
$pst_amount = 0;
if($pst_total > 0) {
	$pst_amount = number_format($total_before_tax * $pst_total / 100, 2, '.', '');
	$html .= '<tr><td style="width:85%; text-align:right;"><b>PST:</b></td><td style="width:15%; text-align:right;">$'.number_format($pst_amount, 2, '.', '').'</td></tr>';
}

//Total Price
$total_price = number_format($total_before_tax + $gst_amount + $pst_amount, 2, '.', '');
$html .= '<tr><td style="width:85%; text-align:right;"><b>Total Price:</b></td><td style="width:15%; text-align:right;">$'.number_format($total_price, 2, '.', '').'</td></tr>'; 

//Deposit Paid
if($deposit_paid > 0) {
	$html .= '<tr><td style="width:85%; text-align:right;"><b>Deposit Paid:</b></td><td style="width:15%; text-align:right;">$'.number_format($deposit_paid, 2, '.', '').'</td></tr>'; 
}

$html .= '</table>';

$pdf->writeHTML(utf8_encode($html), true, false, true, false, '');

if(!file_exists('download')) {
	mkdir('download', 0777, true);
}

$today_date = date('Y-m-d_H-i-a', time());
if(!empty($_GET['sotid'])) {
	$soid = $_GET['sotid'];
	$type = 'sot';
	$file_name = 'sot'.$_GET['sotid'].'_'.$today_date.'.pdf';
} else if(!empty($_GET['posid'])) {
	$soid = $_GET['posid'];
	$type = 'so';
	$file_name = 'so'.$_GET['posid'].'_'.$today_date.'.pdf';
}

mysqli_query($dbc, "INSERT INTO `sales_order_pdf` (`type`, `soid`, `file_name`, `contactid`, `created_date`) VALUES ('$type', '$soid', '$file_name', '".$_SESSION['contactid']."', '".date('Y-m-d')."')");

$pdf->Output('download/'.$file_name, 'F');

echo '<script type="text/javascript">
		window.location.replace("download/'.$file_name.'", "_blank");
	</script>';
?>