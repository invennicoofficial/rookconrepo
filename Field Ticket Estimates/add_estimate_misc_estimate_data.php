<?php
    $miscestimteid = $_GET['estimateid'];
    //$query = mysqli_query($dbc,"DELETE FROM bid_misc WHERE estimateid='$estimateid'");

    // Material
    /*
    $j = 0;
    foreach ($_POST['crc_material_qty'] as $materialid_all) {
        if($materialid_all != '' && $materialid_all != '0') {

            $tile_name = 'Material';
            $companyrcid = filter_var($_POST['crc_material_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_material_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_material_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_material_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_material_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_material_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_material_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_material_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
            $result_insert_customer = mysqli_query($dbc, $query_insert_customer);

            $total_price += $rc_total;
            $total_products += $rc_total;
            $products_total += $qty;
            $products_price_total +=$cust_price;

            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_material += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$material_total += $_POST['mestimateqty'][$j];
            //$material_price_total += $_POST['mestimateprice'][$j];

            $m_html .= '<tr nobr="true">';
            $m_html .= '<td>Material</td>';

            $m_html .= '<td>';
            $m_html .= $heading.'<br>'.$description;
            $m_html .= '</td>';
            $m_html .= '<td>'.$qty.'</td>';
            $m_html .= '<td>'.$uom.'</td>';
            $m_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $m_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';
        }
        $j++;
    }

    //Services
    $j = 0;
    foreach ($_POST['crc_service_qty'] as $serviceid_all) {
        if($serviceid_all != '' && $serviceid_all != '0') {

            $tile_name = 'Services';
            $companyrcid = filter_var($_POST['crc_service_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_service_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_service_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_service_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_service_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_service_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_service_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_service_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
            $result_insert_customer = mysqli_query($dbc, $query_insert_customer);

            $total_price += $rc_total;
            $total_products += $rc_total;
            $products_total += $qty;
            $products_price_total +=$cust_price;

            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_service += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$service_total += $_POST['mestimateqty'][$j];
            //$service_price_total += $_POST['mestimateprice'][$j];

            $s_html .= '<tr nobr="true">';
            $s_html .= '<td>Services</td>';

            $s_html .= '<td>';
            $s_html .= $heading.'<br>'.$description;
            $s_html .= '</td>';
            $s_html .= '<td>'.$qty.'</td>';
            $s_html .= '<td>'.$uom.'</td>';
            $s_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $s_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';
        }
        $j++;
    }
    */

    //Products
    $k=0;
    foreach($_POST['ptype_misc'] as $pidmisc) {
        if($pidmisc != '') {
			if($k == 0) {
				$query_misc_rc = mysqli_query($dbc,"SELECT * FROM bid_misc WHERE accordion='Product' AND estimateid=" . $_GET['estimateid']);
				$misc_rc = 0;
				while($misc_row_rc = mysqli_fetch_array($query_misc_rc)) {
					$p_html .= '<tr nobr="true">';
					$p_html .= '<td>Product</td>';

					$p_html .= '<td>';
					$p_html .= $misc_row_rc['type'].'<br>'.$misc_row_rc['head'];
					$p_html .= '</td>';
					$p_html .= '<td>'.$misc_row_rc['qty'].'</td>';
					$p_html .= '<td>'.$misc_row_rc['uom'].'</td>';
					$p_html .= '<td>$'.number_format((float)$misc_row_rc['estimate_price'], 2, '.', '').'</td>';
					$p_html .= '<td>$'.number_format((float)$misc_row_rc['total'], 2, '.', '').'</td></tr>';

					$color_off = '';
					if($misc_row_rc['cost'] > $misc_row_rc['price']) {
						$color_off = 'style = "color:red; "';
						$plus_minus = $misc_row_rc['cost']-$misc_row_rc['estimate_price'];
						$financial_plus_minus -= $plus_minus;
					} else {
						$color_off = 'style = "color:green; "';
						$plus_minus = $misc_row_rc['estimate_price']-$misc_row_rc['cost'];
						$financial_plus_minus += $plus_minus;
					}
					$review_profit_loss .= '<tr><td>Product</td><td>'.$misc_row_rc['type'].' : '.$misc_row_rc['heading'].'</td> <td>$'.$misc_row_rc['cost'].'</td><td>$'.number_format((float)$misc_row_rc['estimate_price'], 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';
				}
			}

            $miscestimteid = $_GET['estimateid'];
            $miscacco = 'Product';
            $misctype = $_POST['ptype_misc'][$k];
            $mischead = $_POST['pheadmisc'][$k];
            $miscdisc = $_POST['pdisc_misc'][$k];
            $miscuom = $_POST['puom_misc'][$k];
            $misccost = $_POST['ptotalmisc'][$k];
            $miscesprice = $_POST['pestimatepricemisc'][$k];
            $misceqty = $_POST['pqtymisc'][$k];
            $misctotal = $_POST['ptotalmisc'][$k];
            $miscprofit = $_POST['pprofitmisc'][$k];
            $miscmargin = $_POST['pmarginmisc'][$k];

			$estiamtetabid = '';
			if($_GET['estimatetabid']) {
				$estiamtetabid = $_GET['estimatetabid'];
			}

            $query_insert_ticket = "INSERT INTO `bid_misc` (`estimateid`, `accordion`, `type`, `heading`, `description`, `uom`, `cost`, `estimate_price`, `qty`, `total`, `profit`, `margin`, `estimate_tab_id`)
            VALUES ('$miscestimteid', '$miscacco', '$misctype' , '$mischead', '$miscdisc', '$miscuom', '$misccost', '$miscesprice', '$misceqty', '$misctotal', '$miscprofit', '$miscmargin', '$estiamtetabid');";
            $result_insert_ticket = mysqli_query($dbc, $query_insert_ticket);

            $total_price += $misctotal;
            $total_products += $misctotal;
            $products_total += $misceqty;
            $products_price_total +=$miscesprice;

            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_products += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$products_total += $_POST['mestimateqty'][$j];
            //$products_price_total += $_POST['mestimateprice'][$j];

            $p_html .= '<tr nobr="true">';
            $p_html .= '<td>Product</td>';

            $p_html .= '<td>';
            $p_html .= $misctype.'<br>'.$mischead;
            $p_html .= '</td>';
            $p_html .= '<td>'.$misceqty.'</td>';
            $p_html .= '<td>'.$miscuom.'</td>';
            $p_html .= '<td>$'.number_format((float)$miscesprice, 2, '.', '').'</td>';
            $p_html .= '<td>$'.number_format((float)$misctotal, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($misccost > $miscesprice) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $misccost-$miscesprice;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $miscesprice-$misccost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Product</td><td>'.$misctype.' : '.$mischead.'</td> <td>$'.$misccost.'</td><td>$'.number_format((float)$miscesprice, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

            $k++;
        }
    }

    //Staff
    /*
    $j = 0;
    foreach ($_POST['crc_staff_qty'] as $staffid_all) {
        if($staffid_all != '' && $staffid_all != '0') {

            $tile_name = 'Staff';
            $companyrcid = filter_var($_POST['crc_staff_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_staff_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_staff_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_staff_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_staff_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_staff_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_staff_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_staff_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
            $result_insert_customer = mysqli_query($dbc, $query_insert_customer);
            $total_price += $rc_total;
            $total_products += $rc_total;
            $products_total += $qty;
            $products_price_total +=$cust_price;
            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_staff += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$staff_total += $_POST['mestimateqty'][$j];
            //$staff_price_total += $_POST['mestimateprice'][$j];

            $staff_html .= '<tr nobr="true">';
            $staff_html .= '<td>Staff</td>';

            $staff_html .= '<td>';
            $staff_html .= $heading.'<br>'.$description;
            $staff_html .= '</td>';
            $staff_html .= '<td>'.$qty.'</td>';
            $staff_html .= '<td>'.$uom.'</td>';
            $staff_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $staff_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';
        }
        $j++;
    }

    //Contractor
    $j = 0;
    foreach ($_POST['crc_contractor_qty'] as $contractorid_all) {
        if($contractorid_all != '' && $contractorid_all != '0') {

            $tile_name = 'Contractor';
            $companyrcid = filter_var($_POST['crc_contractor_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_contractor_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_contractor_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_contractor_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_contractor_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_contractor_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_contractor_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_contractor_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
            $result_insert_customer = mysqli_query($dbc, $query_insert_customer);
            $total_price += $rc_total;
            $total_products += $rc_total;
            $products_total += $qty;
            $products_price_total +=$cust_price;
            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_contractor += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$contractor_total += $_POST['mestimateqty'][$j];
            //$contractor_price_total += $_POST['mestimateprice'][$j];

            $cont_html .= '<tr nobr="true">';
            $cont_html .= '<td>Contractor</td>';

            $cont_html .= '<td>';
            $cont_html .= $heading.'<br>'.$description;
            $cont_html .= '</td>';
            $cont_html .= '<td>'.$qty.'</td>';
            $cont_html .= '<td>'.$uom.'</td>';
            $cont_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $cont_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';
        }
        $j++;
    }

    //Client
    $j = 0;
    foreach ($_POST['crc_clients_qty'] as $clientsid_all) {
        if($clientsid_all != '' && $clientsid_all != '0') {

            $tile_name = 'Client';
            $companyrcid = filter_var($_POST['crc_clients_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_clients_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_clients_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_clients_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_clients_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_clients_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_clients_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_clients_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
            $result_insert_customer = mysqli_query($dbc, $query_insert_customer);
            $total_price += $rc_total;
            $total_products += $rc_total;
            $products_total += $qty;
            $products_price_total +=$cust_price;
            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_clients += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$clients_total += $_POST['mestimateqty'][$j];
            //$clients_price_total += $_POST['mestimateprice'][$j];

            $c_html .= '<tr nobr="true">';
            $c_html .= '<td>Client</td>';

            $c_html .= '<td>';
            $c_html .= $heading.'<br>'.$description;
            $c_html .= '</td>';
            $c_html .= '<td>'.$qty.'</td>';
            $c_html .= '<td>'.$uom.'</td>';
            $c_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $c_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';
        }
        $j++;
    }

    //Vendor
    /*
    $j = 0;
    foreach ($_POST['crc_clients_qty'] as $clientsid_all) {
        if($clientsid_all != '' && $productsid_all != '0') {

            $tile_name = 'Client';
            $companyrcid = $_POST['crc_clients_companyrcid'][$j];
            $heading = $_POST['crc_clients_heading'][$j];
            $description = $_POST['crc_clients_description'][$j];
            $uom = $_POST['crc_clients_uom'][$j];
            $cost = $_POST['crc_clients_cost'][$j];
            $cust_price = $_POST['crc_clients_cust_price'][$j];
            $qty = $_POST['crc_products_qty'][$j];
            $rc_total = $_POST['crc_products_total'][$j];

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
            $result_insert_customer = mysqli_query($dbc, $query_insert_customer);

            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_clients += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$clients_total += $_POST['mestimateqty'][$j];
            //$clients_price_total += $_POST['mestimateprice'][$j];

            $c_html .= '<tr nobr="true">';
            $c_html .= '<td>Client</td>';

            $c_html .= '<td>';
            $c_html .= $heading.'<br>'.$description;
            $c_html .= '</td>';
            $c_html .= '<td>'.$qty.'</td>';
            $c_html .= '<td>'.$uom.'</td>';
            $c_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $c_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';
        }
        $j++;
    }
    */

    //customer
    /*
    $j = 0;
    foreach ($_POST['crc_customer_qty'] as $customerid_all) {
        if($customerid_all != '' && $customerid_all != '0') {

            $tile_name = 'Customer';
            $companyrcid = filter_var($_POST['crc_customer_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_customer_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_customer_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_customer_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_customer_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_customer_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_customer_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_customer_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
            $result_insert_customer = mysqli_query($dbc, $query_insert_customer);
            $total_price += $rc_total;
            $total_products += $rc_total;
            $products_total += $qty;
            $products_price_total +=$cust_price;
            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_customer += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$customer_total += $_POST['mestimateqty'][$j];
            //$customer_price_total += $_POST['mestimateprice'][$j];

            $cust_html .= '<tr nobr="true">';
            $cust_html .= '<td>Customer</td>';

            $cust_html .= '<td>';
            $cust_html .= $heading.'<br>'.$description;
            $cust_html .= '</td>';
            $cust_html .= '<td>'.$qty.'</td>';
            $cust_html .= '<td>'.$uom.'</td>';
            $cust_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $cust_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';
        }
        $j++;
    }
    */

    // Inventory
    $k=0;
    foreach($_POST['intype_misc'] as $inidmisc) {
        if($inidmisc != '') {
			if($k == 0) {
				$query_misc_rc = mysqli_query($dbc,"SELECT * FROM bid_misc WHERE accordion='Inventory' AND estimateid=" . $_GET['estimateid']);
				$misc_rc = 0;
				while($misc_row_rc = mysqli_fetch_array($query_misc_rc)) {
					$p_html .= '<tr nobr="true">';
					$p_html .= '<td>Inventory</td>';

					$p_html .= '<td>';
					$p_html .= $misc_row_rc['type'].'<br>'.$misc_row_rc['head'];
					$p_html .= '</td>';
					$p_html .= '<td>'.$misc_row_rc['qty'].'</td>';
					$p_html .= '<td>'.$misc_row_rc['uom'].'</td>';
					$p_html .= '<td>$'.number_format((float)$misc_row_rc['estimate_price'], 2, '.', '').'</td>';
					$p_html .= '<td>$'.number_format((float)$misc_row_rc['total'], 2, '.', '').'</td></tr>';

					$color_off = '';
					if($misc_row_rc['cost'] > $misc_row_rc['price']) {
						$color_off = 'style = "color:red; "';
						$plus_minus = $misc_row_rc['cost']-$misc_row_rc['estimate_price'];
						$financial_plus_minus -= $plus_minus;
					} else {
						$color_off = 'style = "color:green; "';
						$plus_minus = $misc_row_rc['estimate_price']-$misc_row_rc['cost'];
						$financial_plus_minus += $plus_minus;
					}
					$review_profit_loss .= '<tr><td>Inventory</td><td>'.$misc_row_rc['type'].' : '.$misc_row_rc['heading'].'</td> <td>$'.$misc_row_rc['cost'].'</td><td>$'.number_format((float)$misc_row_rc['estimate_price'], 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';
				}
			}

            $miscestimteid = $_GET['estimateid'];
            $miscacco = 'Inventory';
            $misctype = $_POST['intype_misc'][$k];
            $mischead = $_POST['inheadmisc'][$k];
            $miscdisc = $_POST['indisc_misc'][$k];
            $miscuom = $_POST['inuom_misc'][$k];
            $misccost = $_POST['incostmisc'][$k];
            $miscesprice = $_POST['inestimatepricemisc'][$k];
            $misceqty = $_POST['inqtymisc'][$k];
            $misctotal = $_POST['intotalmisc'][$k];
            $miscprofit = $_POST['inprofit_misc'][$k];
            $miscmargin = $_POST['inmarginmisc'][$k];

            $estiamtetabid = '';
			if($_GET['estimatetabid']) {
				$estiamtetabid = $_GET['estimatetabid'];
			}

            $query_insert_ticket = "INSERT INTO `bid_misc` (`estimateid`, `accordion`, `type`, `heading`, `description`, `uom`, `cost`, `estimate_price`, `qty`, `total`, `profit`, `margin`, `estimate_tab_id`)
            VALUES ('$miscestimteid', '$miscacco', '$misctype' , '$mischead', '$miscdisc', '$miscuom', '$misccost', '$miscesprice', '$misceqty', '$misctotal', '$miscprofit', '$miscmargin', '$estiamtetabid');";
            $result_insert_ticket = mysqli_query($dbc, $query_insert_ticket);

            $total_price += $misctotal;
            $total_inventory += $misctotal;
            $inventory_total += $misceqty;
            $inventory_price_total +=$miscesprice;

            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_products += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$products_total += $_POST['mestimateqty'][$j];
            //$products_price_total += $_POST['mestimateprice'][$j];

            $in_html .= '<tr nobr="true">';
            $in_html .= '<td>Inventory</td>';
            $in_html .= '<td>';
            $in_html .= $misctype.'<br>'.$mischead;
            $in_html .= '</td>';
            $in_html .= '<td>'.$misceqty.'</td>';
            $in_html .= '<td>'.$miscuom.'</td>';
            $in_html .= '<td>$'.number_format((float)$miscesprice, 2, '.', '').'</td>';
            $in_html .= '<td>$'.number_format((float)$misctotal, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($misccost > $miscesprice) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $misccost-$miscesprice;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $miscesprice-$misccost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Inventory</td><td>'.$misctype.' : '.$mischead.'</td> <td>$'.$misccost.'</td><td>$'.number_format((float)$miscesprice, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

            $k++;
        }
    }

    //Equipemt
    $k=0;
    foreach($_POST['eqtype_misc'] as $eqidmisc) {
        if($eqidmisc != '') {
			if($k == 0) {
				$query_misc_rc = mysqli_query($dbc,"SELECT * FROM bid_misc WHERE accordion='Equipment' AND estimateid=" . $_GET['estimateid']);
				$misc_rc = 0;
				while($misc_row_rc = mysqli_fetch_array($query_misc_rc)) {
					$p_html .= '<tr nobr="true">';
					$p_html .= '<td>Equipment</td>';

					$p_html .= '<td>';
					$p_html .= $misc_row_rc['type'].'<br>'.$misc_row_rc['head'];
					$p_html .= '</td>';
					$p_html .= '<td>'.$misc_row_rc['qty'].'</td>';
					$p_html .= '<td>'.$misc_row_rc['uom'].'</td>';
					$p_html .= '<td>$'.number_format((float)$misc_row_rc['estimate_price'], 2, '.', '').'</td>';
					$p_html .= '<td>$'.number_format((float)$misc_row_rc['total'], 2, '.', '').'</td></tr>';

					$color_off = '';
					if($misc_row_rc['cost'] > $misc_row_rc['price']) {
						$color_off = 'style = "color:red; "';
						$plus_minus = $misc_row_rc['cost']-$misc_row_rc['estimate_price'];
						$financial_plus_minus -= $plus_minus;
					} else {
						$color_off = 'style = "color:green; "';
						$plus_minus = $misc_row_rc['estimate_price']-$misc_row_rc['cost'];
						$financial_plus_minus += $plus_minus;
					}
					$review_profit_loss .= '<tr><td>Equipment</td><td>'.$misc_row_rc['type'].' : '.$misc_row_rc['heading'].'</td> <td>$'.$misc_row_rc['cost'].'</td><td>$'.number_format((float)$misc_row_rc['estimate_price'], 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';
				}
			}

            $miscacco = 'Equipment';
            $misctype = $_POST['eqtype_misc'][$k];
            $mischead = $_POST['eqheadmisc'][$k];
            $miscdisc = $_POST['eqdisc_misc'][$k];
            $miscuom = $_POST['equom_misc'][$k];
            $misccost = $_POST['eqcostmisc'][$k];
            $miscesprice = $_POST['eqestimatepricemisc'][$k];
            $misceqty = $_POST['eqqtymisc'][$k];
            $misctotal = $_POST['eqtotalmisc'][$k];
            $miscprofit = $_POST['eqprofitmisc'][$k];
            $miscmargin = $_POST['eqmarginmisc'][$k];

            $estiamtetabid = '';
			if($_GET['estimatetabid']) {
				$estiamtetabid = $_GET['estimatetabid'];
			}

            $query_insert_ticket = "INSERT INTO `bid_misc` (`estimateid`, `accordion`, `type`, `heading`, `description`, `uom`, `cost`, `estimate_price`, `qty`, `total`, `profit`, `margin`, `estimate_tab_id`)
            VALUES ('$miscestimteid', '$miscacco', '$misctype' , '$mischead', '$miscdisc', '$miscuom', '$misccost', '$miscesprice', '$misceqty', '$misctotal', '$miscprofit', '$miscmargin', '$estiamtetabid');";
            $result_insert_ticket = mysqli_query($dbc, $query_insert_ticket);

            $total_price += $misctotal;
            $total_equipment += $misctotal;
            $equipment_total += $misceqty;
            $equipment_price_total +=$miscesprice;

            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_products += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$products_total += $_POST['mestimateqty'][$j];
            //$products_price_total += $_POST['mestimateprice'][$j];

            $eq_html .= '<tr nobr="true">';
            $eq_html .= '<td>Equipment</td>';

            $eq_html .= '<td>';
            $eq_html .= $misctype.'<br>'.$mischead;
            $eq_html .= '</td>';
            $eq_html .= '<td>'.$misceqty.'</td>';
            $eq_html .= '<td>'.$miscuom.'</td>';
            $eq_html .= '<td>$'.number_format((float)$miscesprice, 2, '.', '').'</td>';
            $eq_html .= '<td>$'.number_format((float)$misctotal, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($misccost > $miscesprice) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $misccost-$miscesprice;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $miscesprice-$misccost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Equipment</td><td>'.$misctype.' : '.$mischead.'</td> <td>$'.$misccost.'</td><td>$'.number_format((float)$miscesprice, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

            $k++;
        }
    }

    //Labour
    $k=0;
    foreach($_POST['ltype_misc'] as $lidmisc) {
        if($lidmisc != '') {
			if($k == 0) {
				$query_misc_rc = mysqli_query($dbc,"SELECT * FROM bid_misc WHERE accordion='Labour' AND estimateid=" . $_GET['estimateid']);
				$misc_rc = 0;
				while($misc_row_rc = mysqli_fetch_array($query_misc_rc)) {
					$p_html .= '<tr nobr="true">';
					$p_html .= '<td>Labour</td>';

					$p_html .= '<td>';
					$p_html .= $misc_row_rc['type'].'<br>'.$misc_row_rc['head'];
					$p_html .= '</td>';
					$p_html .= '<td>'.$misc_row_rc['qty'].'</td>';
					$p_html .= '<td>'.$misc_row_rc['uom'].'</td>';
					$p_html .= '<td>$'.number_format((float)$misc_row_rc['estimate_price'], 2, '.', '').'</td>';
					$p_html .= '<td>$'.number_format((float)$misc_row_rc['total'], 2, '.', '').'</td></tr>';

					$color_off = '';
					if($misc_row_rc['cost'] > $misc_row_rc['price']) {
						$color_off = 'style = "color:red; "';
						$plus_minus = $misc_row_rc['cost']-$misc_row_rc['estimate_price'];
						$financial_plus_minus -= $plus_minus;
					} else {
						$color_off = 'style = "color:green; "';
						$plus_minus = $misc_row_rc['estimate_price']-$misc_row_rc['cost'];
						$financial_plus_minus += $plus_minus;
					}
					$review_profit_loss .= '<tr><td>Labour</td><td>'.$misc_row_rc['type'].' : '.$misc_row_rc['heading'].'</td> <td>$'.$misc_row_rc['cost'].'</td><td>$'.number_format((float)$misc_row_rc['estimate_price'], 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';
				}
			}

            $miscacco = 'Labour';
            $misctype = $_POST['ltype_misc'][$k];
            $mischead = $_POST['lheadmisc'][$k];
            $miscdisc = $_POST['ldisc_misc'][$k];
            $miscuom = $_POST['luom_misc'][$k];
            $misccost = $_POST['lcostmisc'][$k];
            $miscesprice = $_POST['lestimatepricemisc'][$k];
            $misceqty = $_POST['lqtymisc'][$k];
            $misctotal = $_POST['ltotalmisc'][$k];
            $miscprofit = $_POST['lprofitmisc'][$k];
            $miscmargin = $_POST['lmarginmisc'][$k];

            $estiamtetabid = '';
			if($_GET['estimatetabid']) {
				$estiamtetabid = $_GET['estimatetabid'];
			}

            $query_insert_ticket = "INSERT INTO `bid_misc` (`estimateid`, `accordion`, `type`, `heading`, `description`, `uom`, `cost`, `estimate_price`, `qty`, `total`, `profit`, `margin`, `estimate_tab_id`)
            VALUES ('$miscestimteid', '$miscacco', '$misctype' , '$mischead', '$miscdisc', '$miscuom', '$misccost', '$miscesprice', '$misceqty', '$misctotal', '$miscprofit', '$miscmargin', '$estiamtetabid');";
            $result_insert_ticket = mysqli_query($dbc, $query_insert_ticket);

            $total_price += $misctotal;
            $total_labour += $misctotal;
            $labour_total += $misceqty;
            $labour_price_total +=$miscesprice;

            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_products += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$products_total += $_POST['mestimateqty'][$j];
            //$products_price_total += $_POST['mestimateprice'][$j];

            $l_html .= '<tr nobr="true">';
            $l_html .= '<td>Labour</td>';

            $l_html .= '<td>';
            $l_html .= $misctype.'<br>'.$mischead;
            $l_html .= '</td>';
            $l_html .= '<td>'.$misceqty.'</td>';
            $l_html .= '<td>'.$miscuom.'</td>';
            $l_html .= '<td>$'.number_format((float)$miscesprice, 2, '.', '').'</td>';
            $l_html .= '<td>$'.number_format((float)$misctotal, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($misccost > $miscesprice) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $misccost-$miscesprice;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $miscesprice-$misccost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Labour</td><td>'.$misctype.' : '.$mischead.'</td> <td>$'.$misccost.'</td><td>$'.number_format((float)$miscesprice, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

            $k++;
        }
    }
