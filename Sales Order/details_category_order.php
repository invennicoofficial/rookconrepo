<!-- Contact Category Order -->
<?php include_once('../include.php');
include_once('../Sales Order/details_category_functions.php');
// error_reporting(E_ALL);
if($_GET['from_type'] == 'iframe') {
    $sotid = $_GET['sotid'];
    $so_type = $_GET['so_type'];
    $field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `field_config_so`"));
    $customer_cat = explode(',', $field_config['customer_category']);
    $customer_fields = ','.$field_config['customer_fields'].',';
    $value_config = ','.$field_config['fields'].',';
    if(!empty($so_type)) {
        $customer_cat = explode(',', get_config($dbc, 'so_'.config_safe_str($so_type).'_customer_category'));
        $customer_fields = ','.get_config($dbc, 'so_'.config_safe_str($so_type).'_customer_fields').',';
        $value_config = ','.get_config($dbc, 'so_'.config_safe_str($so_type).'_fields').',';
    }
    if(empty($customer_cat)) {
        $customer_cat = ['Business'];
    }
    if($customer_fields == ',,') {
        $customer_fields = ',Business Name,Region,Location,Classification,Phone Number,Email Address,';
    }
    $cat_config = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_so_contacts` WHERE `sales_order_type` = '$so_type'"),MYSQLI_ASSOC);
    if(empty($cat_config)) {
        $no_cat = true;
    } ?>
    <input type="hidden" name="sotid" id="sotid" value="<?= $sotid ?>">
    <input type="hidden" id="so_type" name="so_type" value="<?= $so_type ?>">
    <script type="text/javascript">
    $(document).ready(function() {
        initIframeClick();
        sortableItems();
    });
    window.onpopstate = function() {
        $('.iframe_holder_order').hide();
        $('.hide_on_iframe_order').show();
    }
    function initIframeClick() {
        //iFrame
        $('.iframe_open').off('click').click(function(){
            var sotid = $('#sotid').val();
            var category = $(this).data('category');
            var title    = $(this).data('title');
            var pricing  = $(this).data('pricing');
            var contact_category = $(this).data('contact-category');
            $('#iframe_instead_of_window').attr('src', 'get_products.php?sotid='+sotid+'&category='+category+'&pricing='+pricing+'&contact_category='+contact_category+'&from_type=sot_iframe');
            $('.iframe_title').text(title);
            $('.iframe_holder_order iframe').outerHeight($('.iframe_holder_order').closest('html').outerHeight());
            $('.iframe_holder_order').show();
            $('.hide_on_iframe_order').hide();
        });

        $('.close_iframer').off('click').click(function(){
            $('.iframe_holder_order').hide();
            $('.hide_on_iframe_order').show();
        });

        $('iframe').off('load').load(function() {
            this.contentWindow.document.body.style.overflow = 'scroll';
            this.contentWindow.document.body.style.minHeight = '0';
            this.contentWindow.document.body.style.paddingBottom = '15em';
            this.style.height = (this.contentWindow.document.body.offsetHeight + 10) + 'px';
        });
    }
    function reloadItemData() {
        var sotid = $('#sotid').val();
        $.ajax({
            url: '../Sales Order/details_category_order_content.php?from_type=iframe&sotid='+sotid+'&so_type=<?= $so_type ?>',
            method: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#details_block').html(response);
                initIframeClick();
                sortableItems();
            }
        });
    }
    </script>
<?php }
$contact_category = $contact_cat['contact_category'];
$item_from_types = explode(',',$field_config['product_fields']);
if(!empty($so_type)) {
    $item_from_types = explode(',',get_config($dbc,'so_'.config_safe_str($so_type).'_product_fields'));
} ?>

<div class="iframe_holder_order" style="display:none;">
    <img src="<?php echo WEBSITE_URL; ?>/img/icons/close.png" class="close_iframer" width="45px" style="position:relative; right:10px; float:right; top:58px; cursor:pointer;">
    <span class="iframe_title" style="color:white; font-weight:bold; position:relative; top:58px; left:20px; font-size:30px;"></span>
    <iframe id="iframe_instead_of_window" style="width:100%; overflow:hidden; height:200px; border:0;" src="" scrolling="yes"></iframe>
</div>
<div id="details_block" class="hide_on_iframe_order">
    <?php include('../Sales Order/details_category_order_content.php'); ?>
</div>