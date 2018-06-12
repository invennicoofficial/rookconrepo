<?php // Edit Invoice
if(!empty($_GET['invoiceid']) && $_GET['invoiceid'] != 'new') {
	$invoiceid = $_GET['invoiceid'];
} else if(!empty($_GET['bookingid'])) {
	$bookingid = filter_var($_GET['bookingid'], FILTER_SANITIZE_STRING);
	mysqli_query($dbc, "INSERT INTO `invoice` (`invoice_date`, `created_by`, `bookingid`, `businessid`, `patientid`, `injuryid`, `service_date`, `therapistsid`) SELECT DATE(NOW()), '".$_SESSION['contactid']."', `booking`.`bookingid`, `contacts`.`businessid`, `contacts`.`contactid`, `booking`.`injuryid`, STR_TO_DATE(`booking`.`appoint_date`, '%Y-%m-%d'), `booking`.`therapistsid` FROM `booking` LEFT JOIN `contacts` ON `booking`.`patientid`=`contacts`.`contactid` WHERE `booking`.`bookingid`='$bookingid'");
	$invoiceid = mysqli_insert_id($dbc);
	echo "<script> window.location.replace('?invoiceid=$invoiceid'); </script>";
} else {
	$invoiceid = '';
}
if(!empty($invoiceid)) {
    $invoice = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `invoice` WHERE `invoiceid` = '$invoiceid'"));
    $contact = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '".$invoice['patientid']."'"));
    $injury = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `patient_injury` WHERE `injuryid` = '".$invoice['injuryid']."'"));
    $type = get_patient_from_booking($dbc, $invoice['bookingid'], 'type');
    $app_type = get_type_from_booking($dbc, $type);
    $injury_type = get_all_from_injury($dbc, $invoice['injuryid'], 'injury_type');
}

$invoice_tax = explode('*#*',get_config($dbc, 'invoice_tax'));
$total_count = mb_substr_count($value_config,'*#*');
$tax_rate = [];
$tax_rate['total'] = 0;
foreach($invoice_tax as $invoice_tax_line) {
	$invoice_tax_name_rate = explode('**',$invoice_tax_line);
	$tax_rate[strtolower($invoice_tax_name_rate[0])] = floatval($invoice_tax_name_rate[1]);
	$tax_rate['total'] += floatval($invoice_tax_name_rate[1]);
}

$service_date = date('Y-m-d');
$insurer_row_id = 0;
$tab_list = ['services' => 'Services', 'inventory' => 'Inventory', 'packages' => 'Packages', 'products' => 'Products', 'misc_items' => 'Miscellaneous', 'promo' => 'Promotion', 'tips' => 'Gratuity', 'delivery' => 'Delivery', 'next_appt' => 'Next Appointment', 'survey' => 'Survey', 'followup' => 'Follow Up Email', 'comment' => 'Comments'];
$field_config = explode(',', get_config($dbc, 'invoice_fields'));
$purchaser_categories = array_filter(array_unique(explode(',', get_config($dbc, 'invoice_purchase_contact'))));
$payer_categories = array_filter(array_unique(explode(',', get_config($dbc, 'invoice_payer_contact'))));
?>
<style>
.form-group-inner {
    margin-bottom: 15px;
    }
    .form-horizontal .form-group-inner:before, .form-horizontal .form-group-inner:after {
    content: " ";
    display: table; }
    .form-horizontal .form-group-inner:after {
    clear: both; }
}
</style>
<script type="text/javascript" src="../Invoice/invoice.js"></script>
<script>
$(document).ready(function() {
    $(window).resize(resizeScreen).resize();
    scrollScreen();

	tinymce.on('AddEditor', function (e) {
		tinymce.editors[e.editor.id].on('blur',function() {
			this.save();
			$(this.getElement()).change();
		});
	});
    $('[data-table]').change(function() { saveField(this); });
    loadInvoiceDetails();
    $('[name="category"]').change(function() {
        $('[name="patientid"]').find('option').hide().filter('[data-category="'+this.value+'"]').show();
        $('[name="patientid"]').trigger("change.select2");
    });
    $('[name="third_category"]').change(function() {
		$(this).closest('.payment-line').find('[name="payer_id"]').find('option').hide().filter('[data-category="'+this.value+'"]').show();
        $(this).closest('.payment-line').find('[name="payer_id"]').trigger("change.select2");
    });
});
function jumpTab(tab_name) {
    $('.main-screen .main-screen').scrollTop($('[data-tab-name='+tab_name+']').last().offset().top + $('.main-screen .main-screen').scrollTop() - $('.main-screen .main-screen').offset().top);
    scrollScreen();
    
}
function resizeScreen() {
    var diff = Math.round($(window).height() - $('#footer').offset().top - $('#footer').height()) - 10;
    if($('ul.sidebar').outerHeight() + diff > 0) {
        $('ul.sidebar').outerHeight($('ul.sidebar').outerHeight() + diff);
    }
    $('ul.sidebar').outerHeight($('#footer').offset().top - $('ul.sidebar').offset().top);
    $('.main-screen .main-screen').outerHeight($('#footer').offset().top - $('.main-screen .main-screen').offset().top);
    $('.side-screen').outerHeight($('#footer').offset().top - $('.side-screen').offset().top);
}
function scrollScreen() {
    var current_tab = [];
    $('[data-tab-name]:visible').each(function() {
        if(this.getBoundingClientRect().top < $('.main-screen .main-screen').offset().top + $('.main-screen .main-screen').height() &&
            this.getBoundingClientRect().bottom > $('.main-screen .main-screen').offset().top) {
            current_tab.push($(this).data('tab-name'));
        }
    });
}
function saveField(field) {
    var field_name = $(field).data('field');
    var table_name = $(field).data('table');
    if(field_name != '' && table_name != '') {
		if(field_name == 'patientid') {
			$('[name="category"]').val($(this).find('option:selected').data('category'));
			$('[name="category"]').trigger("change.select2");
			$.ajax({
				type: "GET",
				url: "../Invoice/invoice_ajax.php?fill=retrieve_injuries&contactid="+this.value,
				dataType: "html",
				success: function(response){
					$('[name="injuryid"]').html(response);
					$('[name="injuryid"]').trigger("change.select2");
					loadInvoiceDetails();
				}
			});
		}
        var field_info = new FormData();
        field_info.append('invoiceid', $('#invoiceid').val());
        field_info.append('tile', '<?= FOLDER_NAME ?>');
        field_info.append('field', field_name);
        field_info.append('table', table_name);
        field_info.append('id_field', $(field).data('id-field'));
        field_info.append('id', $(field).data('id'));
        field_info.append('attach_field', $(field).data('attach-field'));
        field_info.append('attach_id', $(field).data('attach-id'));
        field_info.append('category', $(field).data('category'));
        if(field.name.substr(-2) == '[]') {
            field_info.append('value', $('[name="'+field.name+'"]').val());
        } else {
            field_info.append('value', ($(field).data('value') == undefined ? $(field).val() : $(field).data('value')));
        }
        var label = $(field).closest('.form-group').find('label').first().text();
        if(label.substring(label.length - 1) == ':') {
            label = label.substring(0,label.length - 1);
        }
        field_info.append('label', label);
        var ajax_data = {
            processData: false,
            contentType: false,
            url: '../Invoice/invoice_ajax.php?action=invoice_values',
            method: 'POST',
            data: field_info,
            success: function(response) {
                //console.log(response+' - '+field.name);
                if(response > 0 && table_name == 'invoice') {
                    $('#invoiceid').val(response);
                } else if(response > 0) {
					var group = $(field).closest('.line-group');
					group.find('[data-id][data-table='+table_name+']').data('id',response);
					group.find('[data-attach-id]').data('attach-id',response);
				}
				if(field_name == 'item_id') {
					setPrices(field);
				} else if(field_name == 'pricing') {
					$('.inventory [name=item_id]').each(function() {
						setPrices(this);
					});
				} else if(field_name == 'delivery_type') {
					$('.ship_amt').show();
					if(field.value == '' || field.value == 'Pick-Up') {
						$('.ship_amt').hide();
					}
					$('.deliver_contractor').hide();
					if(field.value == 'Drop Ship' || field.value == 'Shipping') {
						$('.deliver_contractor').show();
					}
					$('.confirm_delivery').hide();
					if(field.value == 'Drop Ship' || field.value == 'Shipping' || field.value == 'Company Delivery') {
						$('.confirm_delivery').show();
						if($('name=delivery_address]').val() == '') {
							$.ajax({
								url: '../Invoice/invoice_ajax.php?action=get_address&contactid='+$('[name=patientid]').val(),
								success: function(response) {
									$('name=delivery_address]').val(response);
								}
							})
						}
					}
				}
				loadInvoiceDetails();
            }
        };
        $.ajax(ajax_data);
    }
}
function setPrices(field) {
	option = $(field).find('option:selected');
	line = $(field).closest('.line-group');
	line.find('[name=line_category]').val(option.data('category')).trigger('change.select2');
	if($(field).data('category') == 'service') {
		line.find('[name=unit_price]').val(option.data('fee')).change();
	} else if($(field).data('category') == 'inventory' && line.find('[name=type]').val() == 'WCB') {
		line.find('[name=unit_price]').val(option.data('wcb_price')).change();
	} else if($(field).data('category') == 'inventory' || $(field).data('category') == 'product') {
		line.find('[name=unit_price]').val(option.data($('[name=pricing]').val() != '' ? $('[name=pricing]').val() : 'final_retail_price')).change();
	} else if($(field).data('category') == 'package') {
		line.find('[name=unit_price]').val(option.data($('[name=pricing]').val()) > 0 ? option.data($('[name=pricing]').val()) : option.data('cost')).change();
	}
	line.find('[name=tax_exempt]').val(option.data('gst-exempt')).change();
	line.find('[name=admin_fee]').val(option.data('admin')).change();
}
function addRow(type) {
	var row = $('.line-group.'+type).last();
	var clone = row.clone();
	clone.find('input,select').val('');
	clone.find('[data-id]').data('id','');
	row.after(clone);
	resetChosen($('.chosen-select-deselect'));
	$('[data-table]').off('change',addSave).change(addSave);
}
function addSave() {
	saveField(this);
	loadInvoiceDetails();
}
function remLine(a) {
	if($('.line-group.'+$(a).data('category')).length <= 1) {
		addRow($(a).data('category'));
	}
	saveField(a);
	$(a).closest('.line-group.'+$(a).data('category')).remove();
}
function filterServices() {
	$('.service [name=line_category]').each(function() {
		var services = $(this).closest('.line-group').find('[name=item_id]');
		services.find('option').hide();
		services.find('option[data-category="'+this.value+'"]').show();
		if($('[name=app_type]').val() != undefined) {
			services.find('option[data-appt-type!=",,"]:not([data-appt-type*=",'+$('[name=app_type]').val()+',"])').hide();
		}
		services.trigger('change.select2');
	});
}
function filterInventory() {
	$('.inventory [name=line_category]').each(function() {
		var inventory = $(this).closest('.line-group').find('[name=item_id]');
		inventory.find('option').hide();
		inventory.find('option[data-category="'+this.value+'"]').show();
		inventory.trigger('change.select2');
		var inventory = $(this).closest('.line-group').find('[name=part_no]');
		inventory.find('option').hide();
		inventory.find('option[data-category="'+this.value+'"]').show();
		inventory.trigger('change.select2');
	});
}
function filterPackages() {
	$('.package [name=line_category]').each(function() {
		var services = $(this).closest('.line-group').find('[name=item_id]');
		services.find('option').hide();
		services.find('option[data-category="'+this.value+'"]').show();
		services.trigger('change.select2');
	});
}
function filterProducts() {
	$('.product [name=line_category]').each(function() {
		var services = $(this).closest('.line-group').find('[name=item_id]');
		services.find('option').hide();
		services.find('option[data-category="'+this.value+'"]').show();
		services.trigger('change.select2');
	});
}
function loadInvoiceDetails() {
    $('.detail_patient_name').text($('[name="patientid"] option:selected').text());
    $('.detail_patient_injury').text($('[name="injuryid"] option:selected').text());
    $('.detail_patient_treatment').text($('[name="treatment_plan"] option:selected').text());
    $('.detail_staff_name').text($('[name="therapistsid"] option:selected').text());
	
	var sub_total = 0;
	var gst = 0;
	var pst = 0;
	var third_party = 0;
	var total = 0;
	var gratuity = 0;
	
	//Line Item Calculations
	$('.receipt').text('');
	$('[name=unit_price]').each(function() {
		if(this.value > 0) {
			var line = $(this).closest('.line-group');
			var qty = line.find('[name=quantity]').val();
			var exempt = line.find('[name=tax_exempt]').val();
			var line_total = this.value * qty;
			var line_gst = (exempt > 0 ? 0 : line_total * <?= ($tax_rate['total'] - $tax_rate['pst']) / 100 ?>);
			var line_pst = (exempt > 0 ? 0 : line_total * <?= $tax_rate['pst'] / 100 ?>);
			if(parseFloat(line.find('[name=sub_total]').val()) != parseFloat(line_total)) {
				line.find('[name=sub_total]').val(parseFloat(line_total).toFixed(2)).change();
				line.find('[name=gst]').val(parseFloat(line_gst).toFixed(2)).change();
				line.find('[name=pst]').val(parseFloat(line_pst).toFixed(2)).change();
				line.find('[name=total]').val(parseFloat(line_total+line_gst).toFixed(2)).change();
			}
			sub_total += line_total;
			pst += line_pst;
			gst += line_gst;
			var insurers = line.find('[name=payer_id]').filter(function() { return this.value > 0; }).length;
			var ins_amt = line_total + line_gst;
			line.find('[name=amount][data-table=invoice_payment][data-manual=1]').each(function() {
				ins_amt -= +this.value;
				insurers--;
			});
			line.find('[name=amount][data-table=invoice_payment]').each(function() {
				if(insurers > 0 && $(this).data('manual') == 0) {
					var value = Math.round(ins_amt / insurers * 100) / 100;
					if(parseFloat(value) != parseFloat(this.value)) {
						$(this).val(parseFloat(value).toFixed(2)).change();
					}
					ins_amt -= +this.value;
					insurers--;
				}
				third_party += +this.value;
			});
			var category = line.find('[name=item_id]').data('category');
			var description = line.find('[name=item_id] option:selected').text();
			if(category == 'misc product') {
				category = 'misc_items';
				description = line.find('[name=description]').val();
			}
			if(qty != 1) {
				description += ' X '+parseFloat(qty);
			}
			$('.receipt.'+category).append(description+'<span class="pull-right">$'+line_total.toFixed(2)+'</span><br />');
		}
	});
	
	$('.detail_sub_total_amt').text('$'+sub_total.toFixed(2));
	if(parseFloat(sub_total) != parseFloat($('[name=total_price]').val())) {
		$('[name=total_price]').val(sub_total).change();
	}
	var promo_text = 'N/A'
	var promo_val = 0;
	if($('[name=promotionid]').val() > 0) {
		promo_val = $('[name=promotionid] option:selected').data('cost');
		promo_text = $('[name=promotionid] option:selected').text() + ' ($'+parseFloat(promo_val).toFixed(2)+')';
		sub_total -= +promo_val;
		gst -= (promo_val * <?= ($tax_rate['total'] - $tax_rate['pst']) / 100 ?>);
		gst = (gst > 0 ? gst : 0);
	}
	var promo = +$('[name=promotionid] option:selected').data('cost');
	$('.detail_promo_amt').text(promo_text);
	shipping = +$('[name=delivery]').val();
	sub_total += shipping;
	gst += (shipping * <?= ($tax_rate['total'] - $tax_rate['pst']) / 100 ?>);
	$('.detail_shipping_amt').text('$'+shipping.toFixed(2));
	$('.detail_mid_total_amt').text('$'+sub_total.toFixed(2));
	$('.detail_gst_amt').text('$'+gst.toFixed(2));
	if(parseFloat(gst) != parseFloat($('[name=gst_amt]').val())) {
		$('[name=gst_amt]').val(gst).change();
	}
	gratuity = +$('[name=gratuity]').val();
	$('.detail_gratuity_amt').text('$'+gratuity.toFixed(2));
	total = sub_total + gst + gratuity;
	$('.detail_total_amt').text('$'+total.toFixed(2));
	if(parseFloat(total) != parseFloat($('[name=final_price]').val())) {
		$('[name=final_price]').val(total).change();
	}
	$('.detail_insurer_amt').text('$'+third_party.toFixed(2));
	$('.detail_patient_amt').text('$'+(total - third_party).toFixed(2));
	if(third_party != 0) {
		$('.detail_insurer_amt,.detail_patient_amt').closest('h4').show()
	}
}
function bookAppt() {
	$('.next_appt').hide();
	$.ajax({
		url: '../Invoice/invoice_ajax.php?action=book_appt',
		method: 'POST',
		data: {
			contactid: $('[name=patientid]').val(),
			injuryid: $('[name=injuryid]').val(),
			staff: $('[name=therapistsid]').val(),
			start: $('[name=next_appt_start]').val(),
			end: $('[name=next_appt_end]').val(),
			type: $('[name=next_appt_type]').val()
		}
	});
	$('[name=next_appt_start]').val('');
	$('[name=next_appt_end]').val('');
	$('[name=next_appt_type]').val('');
}
function sendSurvey(survey) {
	$.ajax({
		url: '../Invoice/invoice_ajax.php?action=send_survey',
		method: 'POST',
		data: {
			contactid: $('[name=patientid]').val(),
			staff: $('[name=therapistsid]').val(),
			invoice: $('[name=invoiceid]').val(),
			survey: survey
		}
	});
}
</script>
<ul class='sidebar hide-titles-mob collapsible' style='display: block; height: 15em; margin-bottom: 0; overflow-y: auto; padding-left: 15px;'>
    <a href="#details" onclick='jumpTab("details"); return false;'><li class="active blue">Details</li></a>
    <?php foreach($tab_list as $tab_label => $tab_data) {
        if(in_array($tab_label,$field_config)) { ?>
            <a href="#<?= $tab_label ?>" onclick="jumpTab('<?= $tab_label ?>'); return false;"><li class=""><?= $tab_data ?></li></a>
        <?php }
    } ?>
</ul>
<div class='scalable hide-titles-mob' style="<?= $scale_style ?>">
    <div class='side-screen' style='height: 15em; overflow-y: auto;'>
        <h3>Invoice <?= $invoice['invoiceid'] ?></h3>
		<h4 <?= (in_array('invoice_date',$field_config) ? '' : 'style="display:none;"') ?>>Invoice Date: <label class="detail_invoice_date pull-right"><?= $invoice['invoice_date'] != '' ? $invoice['invoice_date'] : date('Y-m-d') ?></label></h4>
		<h4 <?= (in_array('customer',$field_config) ? '' : 'style="display:none;"') ?>><?= count($purchaser_categories) > 1 ? 'Customer' : $purchaser_categories[0] ?>: <label class="detail_patient_name pull-right"><?= (empty($_GET['invoiceid']) ? get_contact($dbc, $_GET['contactid']) : $patient) ?></label></h4>
		<h4 <?= (in_array('injury',$field_config) ? '' : 'style="display:none;"') ?>>Injury: <label class="detail_patient_injury pull-right"><?= (empty($_GET['invoiceid']) ? '' : $injury) ?></label></h4>
		<h4 <?= (in_array('treatment',$field_config) ? '' : 'style="display:none;"') ?>>Treatment Plan: <label class="detail_patient_treatment pull-right"><?= (empty($_GET['invoiceid']) ? '' : $treatment_plan) ?></label></h4>
		<h4 <?= (in_array('staff',$field_config) ? '' : 'style="display:none;"') ?>>Staff: <label class="detail_staff_name pull-right"><?= (empty($_GET['invoiceid']) ? '' : $staff) ?></label></h4>
        <?php if (in_array('services',$field_config)) { ?>
            <h4>Services</h4>
            <div class="service receipt"></div>
        <?php } ?>
        <?php if (in_array('inventory',$field_config)) { ?>
            <h4>Inventory</h4>
            <div class="inventory receipt"></div>
        <?php } ?>
        <?php if (in_array('packages',$field_config)) { ?>
            <h4>Packages</h4>
            <div class="package receipt"></div>
        <?php } ?>
        <?php if (in_array('products',$field_config)) { ?>
            <h4>Products</h4>
            <div class="product receipt"></div>
        <?php } ?>
        <?php if (in_array('misc_items',$field_config)) { ?>
            <h4>Miscellaneous Products</h4>
            <div class="misc_items receipt"></div>
        <?php } ?>
		<h4>Sub-Total: <label class="detail_sub_total_amt pull-right">$0.00</label></h4>
		<input type="hidden" name="total_price" data-field="total_price" data-table="invoice" value="<?= $invoice['total_price'] ?>">
		<h4 <?= (in_array('promo',$field_config) ? '' : 'style="display:none;"') ?>>Promotion: <label class="detail_promo_amt pull-right"><?= $promotionid > 0 ? '' : 'N/A' ?></label></h4>
		<h4 <?= (in_array('delivery',$field_config) ? '' : 'style="display:none;"') ?>>Delivery: <label class="detail_shipping_amt pull-right">$0.00</label></h4>
		<h4 <?= (in_array('delivery',$field_config) ? '' : 'style="display:none;"') ?>>Total before Tax: <label class="detail_mid_total_amt pull-right">$0.00</label></h4>
		<input type="hidden" name="gst_amt" data-field="gst_amt" data-table="invoice" value="<?= $invoice['gst_amt'] ?>">
		<h4>GST: <label class="detail_gst_amt pull-right">$0.00</label></h4>
		<h4 <?= (in_array('tips',$field_config) ? '' : 'style="display:none;"') ?>>Gratuity: <label class="detail_gratuity_amt pull-right">$0.00</label></h4>
		<h4 style="display:none;">Credit to Account: <label class="detail_credit_balance pull-right">$0.00</label></h4>
		<h4>Total: <label class="detail_total_amt pull-right">$0.00</label></h4>
		<input type="hidden" name="final_price" data-field="final_price" data-table="invoice" value="<?= $invoice['final_price'] ?>">
		<h4 style="display:none;"><?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?> Portion: <label class="detail_insurer_amt pull-right">$0.00</label></h4>
		<h4 style="display:none;"><?= count($purchaser_categories) > 1 ? 'Customer' : $purchaser_categories[0] ?> Portion: <label class="detail_patient_amt pull-right">$0.00</label></h4>
		<a href="" onclick="overlayIFrame('pay_invoices.php?invoiceid='+$('[name=invoiceid]').val()); return false;" class="btn brand-btn pull-right">Check Out</a>
    </div>
</div>
<div class='scale-to-fill has-main-screen hide-titles-mob'>
    <div class='main-screen' style='height: 15em; overflow-y: auto;'>
    <?php include('../Invoice/invoice_fields.php'); ?>
    </div>
</div>
<input type="hidden" id="invoiceid" name="invoiceid" value="<?= $invoiceid ?>">