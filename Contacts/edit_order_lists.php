<?php
/*
 * Add/Edit Order Lists
 * Included in:
 *  - edit_addition_order_lists.php
 */

error_reporting(0);
include_once('../include.php');

//Form submitted
if (isset($_POST['submit'])) {
    $contactid = preg_replace('/[^0-9]/', '', $_POST['contactid']);
    $order_id = preg_replace('/[^0-9]/', '', $_POST['order_id']);
    $order_title = filter_var($_POST['order_title'], FILTER_SANITIZE_STRING);
    $include_in_po = isset($_POST['include_in_po']) ? 1 : 0;
    $include_in_so = isset($_POST['include_in_so']) ? 1 : 0;
    $inventory_list = implode(',', $_POST['inventoryid']);
    
    if ( empty($order_id) ) {
        $query = "INSERT INTO order_lists (order_title, include_in_po, include_in_so, inventoryid, contactid) VALUES ('$order_title', '$include_in_po', '$include_in_so', '$inventory_list', '$contactid')";
    } else {
        $query = "UPDATE order_lists SET order_title='$order_title', include_in_po='$include_in_po', include_in_so='$include_in_so', inventoryid='$inventory_list', contactid='$contactid' WHERE order_id='$order_id'";
    }
    mysqli_query($dbc, $query);
} ?>
</head>

<body><?php
    include_once ('../navigation.php');
    checkAuthorised();
    $back_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
    
    <div class="container">
        <div class="row"><?php
            $order_id = '';
            $order_title = '';
            $include_in_po = '';
            $include_in_so = '';
            $inventory_list = '';
            $contactid = '';
            
            if ( isset($_GET['order_id'])  && !empty($_GET['order_id']) ) {
                $order_id = preg_replace('/[^0-9]/', '', $_GET['order_id']);
                $get_details = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT order_title, include_in_po, include_in_so, inventoryid FROM order_lists WHERE order_id='$order_id'"));
                $order_title = $get_details['order_title'];
                $include_in_po = $get_details['include_in_po'];
                $include_in_so = $get_details['include_in_so'];
                $inventory_list = $get_details['inventoryid'];
            }
            
            if ( isset($_GET['contactid'])  && !empty($_GET['contactid']) ) {
                $contactid = preg_replace('/[^0-9]/', '', $_GET['contactid']);
            } ?>
            
            <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
                <h3><?= $order_id ? 'Edit' : 'Add' ?> Order List</h3>
                <input type="hidden" name="order_id" value="<?= $order_id ?>" />
                <input type="hidden" name="contactid" value="<?= $contactid ?>" />
                <div class="form-group">
                    <label class="col-sm-4">List Name:</label>
                    <div class="col-sm-8"><input type="text" name="order_title" value="<?= $order_title ?>" class="form-control" /></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12"><input type="checkbox" name="include_in_po" <?= $include_in_po==1 ? 'checked' : '' ?> /> Include in Purchase Orders</div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12"><input type="checkbox" name="include_in_so" <?= $include_in_so==1 ? 'checked' : '' ?> /> Include in <?= SALES_ORDER_TILE ?></div>
                </div>
                <div class="form-group"><?php
                    $inventory_vpl = mysqli_query($dbc, "SELECT inventoryid, category, name FROM vendor_price_list WHERE vendorid='$contactid' AND deleted=0");
                    if ( $inventory_vpl->num_rows>0 ) { ?>
                        <div class="" style="max-height:300px; overflow:auto;">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="10%">Include<br /><small class="selectall selectbutton cursor-hand">[Select All]</small></th>
                                    <th width="20%">Category</th>
                                    <th width="70%">Name</th>
                                </tr><?php
                                while ( $row=mysqli_fetch_assoc($inventory_vpl) ) {
                                    $current_list = explode(',', $inventory_list);
                                    $checked = in_array($row['inventoryid'], $current_list) ? 'checked' : ''; ?>
                                    <tr>
                                        <td><input type="checkbox" name="inventoryid[]" value="<?= $row['inventoryid'] ?>" class="order_list_includer" <?= $checked ?> /></td>
                                        <td><?= $row['category'] ?></td>
                                        <td><?= $row['name'] ?></td>
                                    </tr><?php
                                } ?>
                            </table>
                        </div><?php
                    } else {
                        echo '<div class="col-sm-12">No Vendor Price Lists Found.</div>';
                    } ?>
                </div>
                <div class="form-group pull-right double-gap-top">
                    <a href="<?= $back_url; ?>" class="btn brand-btn pull-left">Cancel</a>
                    <button name="submit" class="btn brand-btn pull-right">Submit</button>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div><!-- .row -->
    </div><!-- .container -->
    
    <script>
        $(document).ready(function() {
            $('.selectall').click(function() {
                if($('.selectall').hasClass("deselectall")) {
                    $('.selectall').removeClass('deselectall');
                    if($('.order_list_includer').prop('checked', true)) {
                        $('.order_list_includer').click();
                    }
                    $('.selectall').text('[Select All]');
                } else {
                    $('.selectall').addClass('deselectall');
                    if($('.order_list_includer').prop('checked', false)) {
                        $('.order_list_includer').click();
                    }
                    $('.selectall').text('[Deselect All]');
                }

            });
        });
    </script>