<!-- Sales Lead Details / Add/Edit Sales Lead -->
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form"><?php
    $lead_created_by        = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
    $primary_staff          = $_SESSION['contactid'];
    $share_lead             = '';
    $businessid             = '';
    $contactid              = '';
    $primary_number         = '';
    $email_address          = '';
    $lead_value             = '';
    $estimated_close_date   = '';
    $serviceid              = '';
    $productid              = '';
    $marketingmaterialid    = '';
    $lead_source            = '';
    $next_action            = '';
    $new_reminder           = '';
    $status                 = '';

    if ( !empty($_GET['businessid']) ) {
        $businessid = $_GET['businessid'];
    }
    
    if ( !empty($salesid) ) {
        $get_contact = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales` WHERE `salesid`='{$salesid}'"));

        $lead_created_by        = $get_contact['lead_created_by'];
        $primary_staff          = $get_contact['primary_staff'];
        $share_lead             = $get_contact['share_lead'];
        $businessid             = $get_contact['businessid'];
        $contactid              = $get_contact['contactid'];
        $primary_number         = $get_contact['primary_number'];
        $email_address          = $get_contact['email_address'];
        $lead_value             = $get_contact['lead_value'];
        $estimated_close_date   = $get_contact['estimated_close_date'];
        $serviceid              = $get_contact['serviceid'];
        $productid              = $get_contact['productid'];
        $marketingmaterialid    = $get_contact['marketingmaterialid'];
        $lead_source            = $get_contact['lead_source'];
        $next_action            = $get_contact['next_action'];
        $new_reminder           = $get_contact['new_reminder'];
        $status                 = $get_contact['status'];
        $region                 = $get_contact['region'];
        $location               = $get_contact['location'];
        $classification         = $get_contact['classification'];
        
        $get_lead_source = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contactid`, `businessid`, `referred_contactid` FROM `contacts` WHERE (`referred_contactid` IN ($contactid) OR `referred_contactid` IN ($businessid))"));
        $lead_source_cid = $get_lead_source['contactid'];
        $lead_source_bid = $get_lead_source['businessid']; ?>
        <input type="hidden" id="salesid" name="salesid" value="<?= $salesid ?>" /><?php
    } ?>

    <div class="main-screen-white standard-body" style="padding-left: 0; padding-right: 0; border: none;">
        <div class="standard-body-title">
            <h3><?= ( !empty($salesid) ) ? 'Edit' : 'Add'; ?> <?= SALES_NOUN ?> #<?= $salesid ?></h3>
        </div>

        <div class="standard-body-content"><?php
            
            if (strpos($value_config, ',Staff Information,') !== false) {
                include('details_staff_info.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Lead Information,') !== false) {
                include('details_lead_info.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Service,') !== false) {
                include('details_services.php');
                echo "<hr>";
            }
            if (strpos($value_config, ',Products,') !== false) { 
                include('details_products.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Lead Source,') !== false) { 
                include('details_lead_source.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Reference Documents,') !== false) { 
                include('details_ref_docs.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Marketing Material,') !== false) { 
                include('details_marketing.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Information Gathering,') !== false) { 
                include('details_info_gathering.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Estimate,') !== false) { 
                include('details_estimate.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Next Action,') !== false) { 
                include('details_next_action.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Lead Status,') !== false) { 
                include('details_lead_status.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Lead Notes,') !== false) { 
                include('details_lead_notes.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Tasks,') !== false) { 
                include('details_tasks.php');
				echo "<hr>";
            }
            if (strpos($value_config, ',Time,') !== false) { 
				echo "<div>";
                include('details_time.php');
				echo "</div><hr>";
            }
            if (strpos($value_config, ',History,') !== false) { 
                include('details_history.php');
            } ?>
            
            <div class="pull-right gap-top gap-right gap-bottom">
                <a href="index.php" class="btn brand-btn">Cancel</a>
                <button type="submit" name="add_sales" value="Submit" class="btn brand-btn">Save</button>
            </div>
        </div><!-- .preview-block-container -->
    </div><!-- .main-screen-white -->
    
    <div class="clearfix"></div>
    
</form>