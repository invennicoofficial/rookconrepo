<?php include_once('../include.php');
checkAuthorised('sales_order');
$load_type = $_GET['load_type'];
if($load_type == 'sales_order') {
	$load_items = SALES_ORDER_NOUN;
	$load_title = 'Copy Items from '.SALES_ORDER_NOUN;
	$load_table = 'sales_order_temp';
	$load_table_name = 'name';
	$load_table_id = 'sotid';
} else {
	$load_items = 'Template';
	$load_title = 'Load Template';
	$load_table = 'sales_order_template';
	$load_table_name = 'template_name';
	$load_table_id = 'id';
}
if(isset($_POST['load_template'])) {
    $history = '';
    $load_type = $_POST['load_type'];
    $submit_type = $_POST['submit_type'];
    $templateid = $_POST['templateid'];
    $contactid = $_SESSION['contactid'];
	$sotid = $_POST['sotid'];
	$so_type = $_POST['so_type'];
    if (empty($sotid)) {
        mysqli_query($dbc, "INSERT INTO `sales_order_temp` (`sales_order_type`) VALUES ('$so_type')");
        $sotid = mysqli_insert_id($dbc);
    }

    $get_so = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales_order_temp` WHERE `sotid` = '$sotid'"));
    $sales_order_name = $get_so['name'];
    if(empty($sales_order_name)) {
        $sales_order_name = SALES_ORDER_NOUN.' Form #'.$sotid;
    }

    if($load_type == 'sales_order') {
    	$so_name = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `name` FROM `sales_order_temp` WHERE `sotid` = '$templateid'"))['name'];
	    $history = 'Copied Items from '.$so_name.' into '.$sales_order_name.'<br />';
	    $load_table_id = 'copied_sotid';
    } else {
	    $template_name = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `template_name` FROM `sales_order_template` WHERE `id` = '$templateid'"))['template_name'];
	    $history = 'Loaded Template '.$template_name.' into '.$sales_order_name.'<br />';
	    $load_table_id = 'templateid';
    }

    if($submit_type == 'replace') {
	    mysqli_query($dbc, "DELETE FROM `sales_order_product_temp` WHERE `parentsotid` = '$sotid'");
	    mysqli_query($dbc, "UPDATE `sales_order_temp` SET `$load_table_id` = '$templateid' WHERE `sotid` = '$sotid'");
    } else if($submit_type == 'add') {
    	$templateids = explode(',',$get_so[$load_table_id]);
    	$templateids[] = $templateid;
    	$templateids = implode(',',array_unique(array_filter($templateids)));
    	mysqli_query($dbc, "UPDATE `sales_order_temp` SET `$load_table_id` = '$templateids' WHERE `sotid` = '$sotid'");
    }

    // $template_products = mysqli_query($dbc, "SELECT * FROM `sales_order_template_product` WHERE `template_id` = '$templateid'");
    // while ($row = mysqli_fetch_array($template_products)) {
    // 	if(!empty($_POST['product_id'][$row['id']])) {
	   //      $item_type = $row['item_type'];
	   //      $item_type_id = $row['item_type_id'];
	   //      $item_category = $row['item_category'];
	   //      $item_name = $row['item_name'];
	   //      $item_price = number_format($_POST['product_price'][$row['id']], 2);
	   //      $contact_category = $row['contact_category'];
	   //      $heading_name = $_POST['product_headingname'][$row['id']];
	   //      $mandatory_quantity = $_POST['product_mandatory'][$row['id']];

	   //      mysqli_query($dbc, "INSERT INTO `sales_order_product_temp` (`contactid`, `item_type`, `item_type_id`, `item_category`, `item_name`, `item_price`, `contact_category`, `heading_name`, `mandatory_quantity`, `parentsotid`) VALUES ('$contactid', '$item_type', '$item_type_id', '$item_category', '$item_name', '$item_price', '$contact_category', '$heading_name', '$mandatory_quantity','$sotid')");
    // 	}
    // }

    foreach ($_POST['product_id'] as $i => $product_id) {
    	$item_type = $_POST['product_item_type'][$i];
        $item_type_id = $_POST['product_item_type_id'][$i];
        $item_category = $_POST['product_item_category'][$i];
        $item_name = $_POST['product_item_name'][$i];
        $item_price = number_format($_POST['product_price'][$i], 2);
        $time_estimate = $_POST['time_estimate'][$i];
        $contact_category = $_POST['product_contact_category'][$i];
        $heading_name = $_POST['product_headingname'][$i];
        $mandatory_quantity = $_POST['product_mandatory'][$i];
        $heading_sortorder = mysqli_fetch_array(mysqli_query($dbc, "SELECT MAX(`heading_sortorder`) `sort` FROM `sales_order_product_temp` WHERE `heading_name` = '$heading_name' AND `item_type` = '$item_type' AND `parentsotid` = '$sotid' AND `contact_category` = '$contact_category'"))['sort'];
        if($heading_sortorder == 0) {
	        $heading_sortorder = mysqli_fetch_array(mysqli_query($dbc, "SELECT MAX(`heading_sortorder`) `sort` FROM `sales_order_product_temp` WHERE `item_type` = '$item_type' AND `parentsotid` = '$sotid' AND `contact_category` = '$contact_category'"))['sort'] + 1;
        }
        $sortorder = mysqli_fetch_array(mysqli_query($dbc, "SELECT MAX(`sortorder`) `sort` FROM `sales_order_product_temp` WHERE `heading_name` = '$heading_name' AND `item_type` = '$item_type' AND `parentsotid` = '$sotid' AND `contact_category` = '$contact_category'"))['sort'] + 1;

        mysqli_query($dbc, "INSERT INTO `sales_order_product_temp` (`contactid`, `item_type`, `item_type_id`, `item_category`, `item_name`, `item_price`, `contact_category`, `heading_name`, `mandatory_quantity`, `parentsotid`, `$load_table_id`, `heading_sortorder`, `sortorder`, `time_estimate`) VALUES ('$contactid', '$item_type', '$item_type_id', '$item_category', '$item_name', '$item_price', '$contact_category', '$heading_name', '$mandatory_quantity','$sotid', '$templateid', '$heading_sortorder', '$sortorder', '$time_estimate')");
    }

    //History
    if($history != '') {
        $historyid = mysqli_fetch_array(mysqli_query($dbc, "SELECT MAX(`id`) FROM `sales_order_history` WHERE `sales_order_id`='$sotid' AND `contactid`='".$_SESSION['contactid']."' AND `date` >= '".date('Y-m-d H:i:s',strtotime('-15min'))."'"))[0];
        if($historyid > 0) {
            mysqli_query($dbc, "UPDATE `sales_order_history` SET `history`=CONCAT(`history`,'".filter_var(htmlentities($history),FILTER_SANITIZE_STRING)."') WHERE `id`='$historyid'");
        } else {
            mysqli_query($dbc, "INSERT INTO `sales_order_history` (`sales_order_id`, `history`, `contactid`) VALUES ('$sotid', '".filter_var(htmlentities($history),FILTER_SANITIZE_STRING)."', '".$_SESSION['contactid']."')");
        }
    }

    echo '<script>
            alert("Template loaded successfully.");
            window.top.location.href = "'. WEBSITE_URL .'/Sales Order/order.php?p=details&sotid='.$sotid.'";
            window.parent.$("#sotid").val("'.$sotid.'");
            window.parent.$("#save_order").click();
        </script>';
}

$sotid = $_GET['sotid'];
$so_type = $_GET['so_type'];
?>
<script type="text/javascript">
$(document).ready(function() {
	sortableRows();
	window.submitForm = false;
	$('#templateid').change(function() {
		loadTemplate(this);
	});
});
function sortableRows() {
	$('.product_table').sortable({
		items: 'tbody tr',
		handle: '.sortable_handle'
	});
	$('.item_type_div').sortable({
		items: '.heading_block',
		handle: '.heading_handle'
	});
}
function loadTemplate(sel) {
	$('.template_block').html('Loading...');
	var templateid = sel.value;
	var so_type = $('[name="so_type"]').val();
	if(templateid > 0) {
		$.ajax({
			type: 'GET',
			url: '../Sales Order/load_template_inc.php?templateid='+templateid+'&so_type='+so_type+'&load_type=<?= $load_type ?>&sotid=<?= $_GET['sotid'] ?>',
			dataType: 'html',
			success: function(response) {
				destroyInputs();
				$('.template_block').html(response);
				$('#load_template').show();
				initInputs();
				sortableRows();
			}
		});
	} else {
		$('.template_block').html('No Template Selected.');
		$('#load_template').hide();
	}
}
function submitTemplate() {
	if(!submitForm) {
		$('#dialogSubmit').dialog({
		    resizable: false,
		    height: "auto",
		    width: ($(window).width() <= 600 ? $(window).width() : 600),
		    modal: true,
		    buttons: {
		        "Replace Items": function() {
		            $('[name="submit_type"]').val('replace');
		            window.submitForm=true;
		            $(this).dialog('close');
		            $('[name="load_template"]').trigger('click');
		        },
		        "Add Items": function() {
		            $('[name="submit_type"]').val('add');
		            window.submitForm=true;
		            $(this).dialog('close');
		            $('[name="load_template"]').trigger('click');
		        }
		    }
		});
		return false;
	} else {
		return true;
	}
}
</script>
<div class="padded">
	<div id="dialogSubmit" title="Replace or Add Items" style="display: none;">
		Would you like to replace all items in this <?= SALES_ORDER_NOUN ?> or add these items on top of existing items in this <?= SALES_ORDER_NOUN ?>?
	</div>
	<div class="iframe_holder2" style="display:none;">
	    <img src="<?php echo WEBSITE_URL; ?>/img/icons/close.png" class="close_iframer" width="45px" style="position:relative; right:10px; float:right; top:58px; cursor:pointer;">
	    <span class="iframe_title" style="color:white; font-weight:bold; position:relative; top:58px; left:20px; font-size:30px;"></span>
	    <iframe id="iframe_instead_of_window" style="width:100%; overflow:hidden; height:200px; border:0;" src="" scrolling="yes"></iframe>
	</div>
	<div class="hide_on_iframe2">
		<h3><?= $load_title ?></h3>
		<div class="block-group" style="height: calc(100% - 8em); overflow-y: auto;">
			<form class="form-horizontal" action="" method="post">
				<input type="hidden" name="submit_type" value="">
				<input type="hidden" name="load_type" value="<?= $load_type ?>">
				<input type="hidden" name="sotid" value="<?= $sotid ?>">
				<input type="hidden" name="so_type" value="<?= $so_type ?>">
				<div class="form-group">
					<label class="col-sm-3"><?= $load_items ?>:</label>
					<div class="col-sm-9">
						<select name="templateid" id="templateid" class="chosen-select-deselect">
							<option></option><?php
							if($load_type == 'sales_order') {
				        		$template_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `sales_order_temp` WHERE (`deleted` = 0 OR `sotid` IN (SELECT `sotid` FROM `sales_order` WHERE `deleted` = 0 AND `sotid` > 0)) AND `sotid` != '$sotid' AND `sales_order_type` = '$so_type' ORDER BY `name`"),MYSQLI_ASSOC);
							} else {
				        		$template_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `sales_order_template` WHERE `deleted` = 0 AND `sales_order_type` = '$so_type' ORDER BY `template_name`"),MYSQLI_ASSOC);
							}
			        		foreach ($template_list as $template) {
			        			echo '<option value="'.$template[$load_table_id].'">'.$template[$load_table_name].'</option>';
			        		} ?>
						</select>
					</div>
				</div>
				<div class="clearfix"></div>
				<hr>
				<div class="template_block">
					No <?= $load_items ?> Selected.
				</div>
				<div class="clearfix"></div>
				<div class="pull-right gap-top gap-right">
				    <a href="?" class="btn brand-btn">Cancel</a>
				    <button type="submit" id="load_template" name="load_template" value="Submit" class="btn brand-btn" onclick="return submitTemplate();" style="display: none;">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>