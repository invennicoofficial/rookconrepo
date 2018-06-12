<!-- Sales Lead Preview --><?php
$salesid = preg_replace('/[^0-9]/', '', $_GET['id']);
$lead    = mysqli_query($dbc, "SELECT * FROM `sales` WHERE `sales`.`salesid`='{$salesid}'"); ?>
<div class="main-screen standard-body gap-bottom overflow-y">
    <div class="standard-body-title pad-left">
        <h3><?= SALES_NOUN ?> #<?= $salesid ?>
            <div class="pull-right"><input type="button" onclick="javascript:window.location.replace('<?= WEBSITE_URL; ?>/Sales/sale.php?p=details&id=<?=$salesid;?>&a=staffinfo');" value="Edit" class="btn brand-btn btn-small" /></div>
            <div class="clearfix"></div>
        </h3>
    </div>

    <div class="standard-body-content"><?php
        if ( $lead->num_rows > 0 ) {
            while ( $row=mysqli_fetch_assoc($lead) ) { ?>
                <div class="preview-block-details padded">
                    <div class="col-xs-12 col-md-7 preview-block-details-left">
                        <div class="row">
                            <div class="col-xs-4 default-color">Business:</div>
                            <div class="col-xs-8"><?php
                                $business_name = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `name` FROM `contacts` WHERE `contactid`={$row['businessid']}"))['name'];
                                echo decryptIt($business_name); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 default-color">Contact(s):</div>
                            <div class="col-xs-8"><?php
                                $contacts = '';
                                foreach ( explode(',', $row['contactid']) as $contact ) {
                                    if ( get_contact($dbc, $contact) != '-' ) {
                                        $contacts .= get_contact($dbc, $contact) . '<br />';
                                    }
                                }
                                echo rtrim($contacts, ', '); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 default-color">Contact #:</div>
                            <div class="col-xs-8"><?php
                                $phone_num_formats = array('(', ')', '-', ' ', '.');
                                if ( !empty($row['primary_number']) ) {
                                    echo '<a href="tel:'. str_replace($phone_num_formats, '', $row['primary_number']).'">'. $row['primary_number'] .'</a> (Primary)<br />';
                                }
                                foreach ( explode(',', $row['contactid']) as $contact ) {
                                    $result = mysqli_query($dbc, "SELECT `first_name`, `office_phone` FROM `contacts` WHERE `contactid`='$contact'");
                                    while( $row_phone=mysqli_fetch_assoc($result) ) {
                                        if ( !empty($row_phone['office_phone']) ) {
                                            echo '<a href="tel:'. str_replace($phone_num_formats, '', decryptIt($row_phone['office_phone'])).'">'. decryptIt($row_phone['office_phone']) .'</a> ('. decryptIt($row_phone['first_name']) .')<br />';
                                        }
                                    }
                                } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 default-color">Email:</div>
                            <div class="col-xs-8"><?php
                                if ( !empty($row['email_address']) ) {
                                    echo '<a href="mailto:'.$row['email_address'].'">'. $row['email_address'] . '</a> (Primary)<br />';
                                }
                                foreach ( explode(',', $row['contactid']) as $contact ) {
                                    $result = mysqli_query($dbc, "SELECT `first_name`, `email_address` FROM `contacts` WHERE `contactid`='$contact'");
                                    while( $row_email=mysqli_fetch_assoc($result) ) {
                                        if ( !empty($row_email['email_address']) ) {
                                            echo '<a href="mailto:'.decryptIt($row_email['email_address']).'">'. decryptIt($row_email['email_address']) .'</a> ('. decryptIt($row_email['first_name']) .')<br />';
                                        }
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-md-4 col-md-offset-1 preview-block-details-right">
                        <div class="row">
                            <div class="col-xs-6 default-color">Lead Value($):</div>
                            <div class="col-xs-6"><?= $row['lead_value']; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 default-color">Lead Status:</div>
                            <div class="col-xs-6"><?= $row['status']; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 default-color">Next Action:</div>
                            <div class="col-xs-6"><?= $row['next_action']; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 default-color">Next Action Date:</div>
                            <div class="col-xs-6"><?= $row['new_reminder']; ?></div>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-xs-12 preview-block-details-full">
                        <div class="col-xs-12 col-sm-2 col-md-1"><b>Notes:</b></div>
                        <div class="col-xs-12 col-sm-10 col-md-11"><?php
                            $comments = mysqli_query($dbc, "SELECT * FROM `sales_notes` WHERE `salesid`='{$salesid}' ORDER BY `salesnoteid` DESC");
                            if ( $comments->num_rows > 0 ) {
                                echo '<ul>';
                                while ( $row=mysqli_fetch_assoc($comments) ) {
                                    echo '<li>'. html_entity_decode($row['comment']) .'</li>';
                                }
                                echo '</ul>';
                            } else {
                                echo '-';
                            } ?>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div><?php
                    
                    if ( !empty($row['serviceid']) ) { ?>
                        <div class="col-sm-12 triple-gap-top">
                            <div class="no-more-tables">
                                <table>
                                    <tr class="hidden-xs hidden-sm"><?php
                                        if (strpos($value_config, ',Services Service Type,') !== false) { echo '<th>Service</th>'; }
                                        if (strpos($value_config, ',Services Category,') !== false) { echo '<th>Category</th>'; }
                                        if (strpos($value_config, ',Services Heading,') !== false) { echo '<th>Heading</th>'; }
                                        echo '<th>Unit Price($)</th>'; ?>
                                    </tr><?php
                                    
                                    foreach ( explode(',', $row['serviceid']) as $each_serviceid ) {
                                        $service_row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `serviceid`, `service_type`, `category`, `heading`, `client_price` FROM `services` WHERE `serviceid`='{$each_serviceid}'"));
                                        echo '<tr>';
                                            if (strpos($value_config, ',Services Service Type,') !== false) { echo '<td data-title="Service">'. $service_row['service_type'] .'</td>'; }
                                            if (strpos($value_config, ',Services Category,') !== false) { echo '<td data-title="Category">'. $service_row['category'] .'</td>'; }
                                            if (strpos($value_config, ',Services Heading,') !== false) { echo '<td data-title="Heading">'. $service_row['heading'] .'</td>'; }
                                            echo '<td data-title="Unit Price">'. (!empty($service_row['client_price']) ? $service_row['client_price'] : '0.00') .'</td>';
                                        echo '</tr>';
                                    } ?>
                                </table>
                           </div>
                        </div>
                        <div class="clearfix"></div><?php
                    }
                    
                    if ( !empty($row['productid']) ) { ?>
                        <div class="col-sm-12 triple-gap-top">
                            <div class="no-more-tables">
                                <table>
                                    <tr class="hidden-xs hidden-sm"><?php
                                        if (strpos($value_config, ',Products Product Type,') !== false) { echo '<th>Product</th>'; }
                                        if (strpos($value_config, ',Products Category,') !== false) { echo '<th>Category</th>'; }
                                        if (strpos($value_config, ',Products Heading,') !== false) { echo '<th>Heading</th>'; }
                                        echo '<th>Unit Price($)</th>'; ?>
                                    </tr><?php
                                    
                                    foreach ( explode(',', $row['productid']) as $each_productid ) {
                                        $product_row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `productid`, `product_type`, `category`, `heading`, `client_price` FROM `products` WHERE `productid`='{$each_productid}'"));
                                        echo '<tr>';
                                            if (strpos($value_config, ',Products Product Type,') !== false) { echo '<td data-title="Product">'. $product_row['service_type'] .'</td>'; }
                                            if (strpos($value_config, ',Products Category,') !== false) { echo '<td data-title="Category">'. $product_row['category'] .'</td>'; }
                                            if (strpos($value_config, ',Products Heading,') !== false) { echo '<td data-title="Heading">'. $product_row['heading'] .'</td>'; }
                                            echo '<td data-title="Unit Price">'. (!empty($product_row['client_price']) ? $product_row['client_price'] : '0.00') .'</td>';
                                        echo '</tr>';
                                    } ?>
                                </table>
                           </div>
                        </div>
                        <div class="clearfix"></div><?php
                    } ?>
                </div><!-- .preview-block-details --><?php
            }

        } else { ?>
            <div class="preview-block-details">No records found.</div><?php
        } ?>
    </div><!-- .preview-block-container -->
</div><!-- .main-screen-white -->