<?php
    $query = mysqli_query($dbc,"DELETE FROM bid_company_rate_card WHERE estimateid='$estimateid'");

    // Material
    $j = 0;
    foreach ($_POST['crc_material_qty'] as $materialid_all) {
        if($materialid_all != '' && $materialid_all != '0') {

            $tile_name = 'Material';
            $companyrcid = filter_var($_POST['crc_material_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $type = filter_var($_POST['crc_material_type_'.$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_material_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_material_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_material_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_material_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_material_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_material_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_material_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
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
            $m_html .= $type.'<br>'.$heading;
            $m_html .= '</td>';
            $m_html .= '<td>'.$qty.'</td>';
            $m_html .= '<td>'.$uom.'</td>';
            $m_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $m_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($cost > $cust_price) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $cost-$cust_price;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $cust_price-$cost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Material</td><td>'.$type.' : '.$heading.'</td> <td>$'.$cost.'</td><td>$'.number_format((float)$cust_price, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

        }
        $j++;
    }

    //Services
    $j = 0;
    foreach ($_POST['crc_service_qty'] as $serviceid_all) {
        if($serviceid_all != '' && $serviceid_all != '0') {

            $tile_name = 'Services';
            $companyrcid = filter_var($_POST['crc_service_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $type = filter_var($_POST['crc_service_type_'.$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_service_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_service_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_service_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_service_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_service_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_service_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_service_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
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
            $s_html .= $type.'<br>'.$heading;
            $s_html .= '</td>';
            $s_html .= '<td>'.$qty.'</td>';
            $s_html .= '<td>'.$uom.'</td>';
            $s_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $s_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($cost > $cust_price) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $cost-$cust_price;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $cust_price-$cost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Services</td><td>'.$type.' : '.$heading.'</td> <td>$'.$cost.'</td><td>$'.number_format((float)$cust_price, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

        }
        $j++;
    }

    //Products
    //$j = 0;

    $total_rc_products = $_POST['total_rc_products'];
    for($j=0;$j<=$total_rc_products;$j++) {
        if($_POST['crc_products_qty_'.$j] != '' && $_POST['crc_products_qty_'.$j] != '0') {
            $tile_name = 'Product';
            $companyrcid = filter_var($_POST['crc_products_companyrcid_'.$j],FILTER_SANITIZE_STRING);
            $type = filter_var($_POST['crc_products_type_'.$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_products_heading_'.$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_products_description_'.$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_products_uom_'.$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_products_cost_'.$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_products_cust_price_'.$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_products_qty_'.$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_products_total_'.$j],FILTER_SANITIZE_STRING);

            $profit = filter_var($_POST['crc_products_profit_'.$j],FILTER_SANITIZE_STRING);
            $margin = filter_var($_POST['crc_products_margin_'.$j],FILTER_SANITIZE_STRING);

            $query_insert_products = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`,`profit`,`margin`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total','$profit','$margin')";
            $result_insert_customer = mysqli_query($dbc, $query_insert_products);

            $total_price += $rc_total;
            $total_products += $rc_total;
            $products_total += $qty;
            $products_price_total +=$cust_price;

            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_products += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$products_total += $_POST['mestimateqty'][$j];
            //$products_price_total += $_POST['mestimateprice'][$j];

            $p_html .= '<tr nobr="true">';
            $p_html .= '<td>Product</td>';

            $p_html .= '<td>';
            $p_html .= $type.'<br>'.$heading;
            $p_html .= '</td>';
            $p_html .= '<td>'.$qty.'</td>';
            $p_html .= '<td>'.$uom.'</td>';
            $p_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $p_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($cost > $cust_price) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $cost-$cust_price;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $cust_price-$cost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Product</td><td>'.$type.' : '.$heading.'</td> <td>$'.$cost.'</td><td>$'.number_format((float)$cust_price, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

        }
        //$j++;
    }

    //Staff
    $j = 0;
    foreach ($_POST['crc_staff_qty'] as $staffid_all) {
        if($staffid_all != '' && $staffid_all != '0') {

            $tile_name = 'Staff';
            $companyrcid = filter_var($_POST['crc_staff_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $type = filter_var($_POST['crc_staff_type_'.$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_staff_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_staff_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_staff_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_staff_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_staff_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_staff_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_staff_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
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
            $staff_html .= $type.'<br>'.$heading;
            $staff_html .= '</td>';
            $staff_html .= '<td>'.$qty.'</td>';
            $staff_html .= '<td>'.$uom.'</td>';
            $staff_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $staff_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($cost > $cust_price) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $cost-$cust_price;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $cust_price-$cost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Staff</td><td>'.$type.' : '.$heading.'</td> <td>$'.$cost.'</td><td>$'.number_format((float)$cust_price, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

        }
        $j++;
    }

    //Contractor
    $j = 0;
    foreach ($_POST['crc_contractor_qty'] as $contractorid_all) {
        if($contractorid_all != '' && $contractorid_all != '0') {

            $tile_name = 'Contractor';
            $companyrcid = filter_var($_POST['crc_contractor_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $type = filter_var($_POST['crc_contractor_type_'.$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_contractor_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_contractor_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_contractor_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_contractor_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_contractor_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_contractor_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_contractor_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
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
            $cont_html .= $type.'<br>'.$heading;
            $cont_html .= '</td>';
            $cont_html .= '<td>'.$qty.'</td>';
            $cont_html .= '<td>'.$uom.'</td>';
            $cont_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $cont_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($cost > $cust_price) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $cost-$cust_price;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $cust_price-$cost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Contractor</td><td>'.$type.' : '.$heading.'</td> <td>$'.$cost.'</td><td>$'.number_format((float)$cust_price, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';
        }
        $j++;
    }

    //Client
    $j = 0;
    foreach ($_POST['crc_clients_qty'] as $clientsid_all) {
        if($clientsid_all != '' && $clientsid_all != '0') {

            $tile_name = 'Client';
            $companyrcid = filter_var($_POST['crc_clients_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $type = filter_var($_POST['crc_clients_type_'.$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_clients_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_clients_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_clients_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_clients_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_clients_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_clients_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_clients_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
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
            $c_html .= $type.'<br>'.$heading;
            $c_html .= '</td>';
            $c_html .= '<td>'.$qty.'</td>';
            $c_html .= '<td>'.$uom.'</td>';
            $c_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $c_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($cost > $cust_price) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $cost-$cust_price;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $cust_price-$cost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Client</td><td>'.$type.' : '.$heading.'</td> <td>$'.$cost.'</td><td>$'.number_format((float)$cust_price, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

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

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
            $result_insert_customer = mysqli_query($dbc, $query_insert_customer);

            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_clients += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$clients_total += $_POST['mestimateqty'][$j];
            //$clients_price_total += $_POST['mestimateprice'][$j];

            $c_html .= '<tr nobr="true">';
            $c_html .= '<td>Client</td>';

            $c_html .= '<td>';
            $c_html .= $type.'<br>'.$heading;
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
    $j = 0;
    foreach ($_POST['crc_customer_qty'] as $customerid_all) {
        if($customerid_all != '' && $customerid_all != '0') {

            $tile_name = 'Customer';
            $companyrcid = filter_var($_POST['crc_customer_companyrcid'][$j],FILTER_SANITIZE_STRING);
            $type = filter_var($_POST['crc_customer_type_'.$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_customer_heading'][$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_customer_description'][$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_customer_uom'][$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_customer_cost'][$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_customer_cust_price'][$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_customer_qty'][$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_customer_total'][$j],FILTER_SANITIZE_STRING);

            $query_insert_customer = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total')";
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
            $cust_html .= $type.'<br>'.$heading;
            $cust_html .= '</td>';
            $cust_html .= '<td>'.$qty.'</td>';
            $cust_html .= '<td>'.$uom.'</td>';
            $cust_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $cust_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($cost > $cust_price) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $cost-$cust_price;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $cust_price-$cost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Customer</td><td>'.$type.' : '.$heading.'</td> <td>$'.$cost.'</td><td>$'.number_format((float)$cust_price, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

        }
        $j++;
    }

    // Inventory
    //$j = 0;
    $total_rc_inventory = $_POST['total_rc_inventory'];
    for($j=0;$j<=$total_rc_inventory;$j++) {
        if($_POST['crc_inventory_qty_'.$j] != '' && $_POST['crc_inventory_qty_'.$j] != '0') {
            $tile_name = 'Inventory';
            $companyrcid = filter_var($_POST['crc_inventory_companyrcid_'.$j],FILTER_SANITIZE_STRING);
            $type = filter_var($_POST['crc_inventory_type_'.$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_inventory_heading_'.$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_inventory_description_'.$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_inventory_uom_'.$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_inventory_cost_'.$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_inventory_cust_price_'.$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_inventory_qty_'.$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_inventory_total_'.$j],FILTER_SANITIZE_STRING);

            $profit = filter_var($_POST['crc_inventory_profit_'.$j],FILTER_SANITIZE_STRING);
            $margin = filter_var($_POST['crc_inventory_margin_'.$j],FILTER_SANITIZE_STRING);

            $query_insert_inventory = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`,`profit`,`margin`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total','$profit','$margin')";
            $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
            $total_price += $rc_total;
            $total_products += $rc_total;
            $products_total += $qty;
            $products_price_total +=$cust_price;
            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_inventory += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$inventory_total += $_POST['mestimateqty'][$j];
            //$inventory_price_total += $_POST['mestimateprice'][$j];

            $in_html .= '<tr nobr="true">';
            $in_html .= '<td>Inventory</td>';
            $in_html .= '<td>';
            $in_html .= $type.'<br>'.$heading;
            $in_html .= '</td>';
            $in_html .= '<td>'.$qty.'</td>';
            $in_html .= '<td>'.$uom.'</td>';
            $in_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $in_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($cost > $cust_price) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $cost-$cust_price;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $cust_price-$cost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Inventory</td><td>'.$type.' : '.$heading.'</td> <td>$'.$cost.'</td><td>$'.number_format((float)$cust_price, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

        }
        //$j++;
    }

    //Equipemt
    $j = 0;
    $total_rc_equipment = $_POST['total_rc_equipment'];
    for($j=0;$j<=$total_rc_equipment;$j++) {
        if($_POST['crc_equipment_qty_'.$j] != '' && $_POST['crc_equipment_qty_'.$j] != '0') {
            $tile_name = 'Equipment';
            $companyrcid = filter_var($_POST['crc_equipment_companyrcid_'.$j],FILTER_SANITIZE_STRING);
            $type = filter_var($_POST['crc_equipment_type_'.$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_equipment_heading_'.$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_equipment_description_'.$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_equipment_uom_'.$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_equipment_cost_'.$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_equipment_cust_price_'.$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_equipment_qty_'.$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_equipment_total_'.$j],FILTER_SANITIZE_STRING);
            $profit = filter_var($_POST['crc_equipment_profit_'.$j],FILTER_SANITIZE_STRING);
            $margin = filter_var($_POST['crc_equipment_profit_'.$j],FILTER_SANITIZE_STRING);

            $query_insert_equipment = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`,`profit`,`margin`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total','$profit','$margin')";
            $result_insert_equipment = mysqli_query($dbc, $query_insert_equipment);
            $total_price += $rc_total;
            $total_products += $rc_total;
            $products_total += $qty;
            $products_price_total +=$cust_price;
            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_equipment += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$equipment_total += $_POST['mestimateqty'][$j];
            //$equipment_price_total += $_POST['mestimateprice'][$j];

            $eq_html .= '<tr nobr="true">';
            $eq_html .= '<td>Equipment</td>';
            $eq_html .= '<td>';
            $eq_html .= $type.'<br>'.$heading;
            $eq_html .= '</td>';
            $eq_html .= '<td>'.$qty.'</td>';
            $eq_html .= '<td>'.$uom.'</td>';
            $eq_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $eq_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($cost > $cust_price) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $cost-$cust_price;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $cust_price-$cost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Equipment</td><td>'.$type.' : '.$heading.'</td> <td>$'.$cost.'</td><td>$'.number_format((float)$cust_price, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';
        }
        //$j++;
    }

    //Labour
    $j = 0;

    $total_rc_labour = $_POST['total_rc_labour'];
    for($j=0;$j<=$total_rc_labour;$j++) {
        if($_POST['crc_labour_qty_'.$j] != '' && $_POST['crc_labour_qty_'.$j] != '0') {
            $tile_name = 'Labour';
            $companyrcid = filter_var($_POST['crc_labour_companyrcid_'.$j],FILTER_SANITIZE_STRING);
            $type = filter_var($_POST['crc_labour_type_'.$j],FILTER_SANITIZE_STRING);
            $heading = filter_var($_POST['crc_labour_heading_'.$j],FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['crc_labour_description_'.$j],FILTER_SANITIZE_STRING);
            $uom = filter_var($_POST['crc_labour_uom_'.$j],FILTER_SANITIZE_STRING);
            $cost = filter_var($_POST['crc_labour_cost_'.$j],FILTER_SANITIZE_STRING);
            $cust_price = filter_var($_POST['crc_labour_cust_price_'.$j],FILTER_SANITIZE_STRING);
            $qty = filter_var($_POST['crc_labour_qty_'.$j],FILTER_SANITIZE_STRING);
            $rc_total = filter_var($_POST['crc_labour_total_'.$j],FILTER_SANITIZE_STRING);

            $profit = filter_var($_POST['crc_labour_profit_'.$j],FILTER_SANITIZE_STRING);
            $margin = filter_var($_POST['crc_labour_margin_'.$j],FILTER_SANITIZE_STRING);

            $query_insert_labour = "INSERT INTO `bid_company_rate_card` (`estimateid`, `companyrcid`, `tile_name`, `rc_type`, `heading`, `description`, `uom`, `cost`, `cust_price`, `qty`, `rc_total`,`profit`,`margin`) VALUES ('$estimateid', '$companyrcid', '$tile_name','$type', '$heading' , '$description', '$uom', '$cost', '$cust_price', '$qty', '$rc_total','$profit','$margin')";
            $result_insert_labour = mysqli_query($dbc, $query_insert_labour);
            $total_price += $rc_total;
            $total_products += $rc_total;
            $products_total += $qty;
            $products_price_total +=$cust_price;
            //$total_price += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];
            //$total_labour += $_POST['mestimateprice'][$j]*$_POST['mestimateqty'][$j];

            //$labour_total += $_POST['mestimateqty'][$j];
            //$labour_price_total += $_POST['mestimateprice'][$j];

            $l_html .= '<tr nobr="true">';
            $l_html .= '<td>Labour</td>';
            $l_html .= '<td>';
            $l_html .= $type.'<br>'.$heading;
            $l_html .= '</td>';
            $l_html .= '<td>'.$qty.'</td>';
            $l_html .= '<td>'.$uom.'</td>';
            $l_html .= '<td>$'.number_format((float)$cust_price, 2, '.', '').'</td>';
            $l_html .= '<td>$'.number_format((float)$rc_total, 2, '.', '').'</td></tr>';

            $color_off = '';
            if($cost > $cust_price) {
                $color_off = 'style = "color:red; "';
                $plus_minus = $cost-$cust_price;
                $financial_plus_minus -= $plus_minus;
            } else {
                $color_off = 'style = "color:green; "';
                $plus_minus = $cust_price-$cost;
                $financial_plus_minus += $plus_minus;
            }
            $review_profit_loss .= '<tr><td>Labour</td><td>'.$type.' : '.$heading.'</td> <td>$'.$cost.'</td><td>$'.number_format((float)$cust_price, 2, '.', '').'</td><td '.$color_off.'>$'.$plus_minus.'</td></tr>';

        }
        //$j++;
    }
