<?php include_once('../include.php');
checkAuthorised('inventory');
$strict_view = strictview_visible_function($dbc, 'inventory');
$tile_security = get_security($dbc, 'inventory');
if($strict_view > 0) {
    $tile_security['edit'] = 0;
    $tile_security['config'] = 0;
}
$folder = (isset($_GET['folder']) ? filter_var($_GET['folder'], FILTER_SANITIZE_STRING) : FOLDER_NAME); ?>
<script>
$(document).ready(function() {
	$('.connectedChecklist').sortable({
		connectWith: ".connectedChecklist",
		handle: ".sort-handle",
		items: "li:not(.no-sort)",
		update: function( event, ui ) {
			var list = [];
			$('li input[type=checkbox]').each(function() {
				list.push(this.value);
				if($(this).closest('li').find('[name=heading]').val() != $(this).closest('ul').data('heading')) {
					changeHeading(this);
				}
			});
			$.ajax({
				url: '../Inventory/order_checklist_ajax.php?action=sortItems',
				method: 'POST',
				data: {
					items: list
				}
			});
		}
	});
});
function addItem(input) {
	var text = input.value;
	$.ajax({
		url: '../Inventory/order_checklist_ajax.php?action=addItem&folder=<?= $folder ?>',
		method: 'POST',
		data: {
			heading: $(input).closest('ul').data('heading'),
			item: text
		},
		success: function(response) {
			$(input).closest('li').before('<li><input type="checkbox" value="'+response+'" class="form-checkbox no-gap-pad" onchange="checkItem(this);"><input type="hidden" name="heading" value="'+$(input).closest('ul').data('heading')+'" onchange="changeHeading(this);"> '+text+'<img class="inline-img pull-right sort-handle" src="../img/icons/drag_handle.png"></li>');
		}
	});
	input.value = '';
}
function addHeading() {
	$.ajax({
		url: '../Inventory/order_checklist_ajax.php?action=addHeading&folder=<?= $folder ?>',
		success: function() {
			$('.checklist_div').html('Loading Order Lists...');
			reloadLists();
		}
	});
}
function changeHeading(input) {
	$(input).closest('li').find('[name=heading]').val($(input).closest('ul').data('heading'));
	$.ajax({
		url: '../Inventory/order_checklist_ajax.php?action=changeHeading&heading='+$(input).closest('li').find('[name=heading]').val()+'&item='+input.value,
		success:function(response) {console.log(response);}
	});
}
function reloadLists() {
	$.ajax({
		url: '../Inventory/order_checklist_display.php?folder=<?= $folder ?>',
		success: function(response) {
			$('.checklist_div').html(response);
		}
	});
}
function checkItem(input) {
	$(input).closest('li').toggleClass('strikethrough');
	$.ajax({
		url: '../Inventory/order_checklist_ajax.php?action=checkItem&checked='+(input.checked ? 1 : 0)+'&item='+input.value
	});
}
function updateHeading(input) {
	$.ajax({
		url: '../Inventory/order_checklist_ajax.php?action=updateHeading',
		method: 'POST',
		data: {
			heading: $(input).closest('ul').data('heading'),
			value: input.value
		}
	});
}
</script>
<?php if($tile_security['edit'] > 0) { ?>
	<button class="btn brand-btn" onclick="addHeading(); return false;">Add Heading</button>
<?php } ?>
<div class="clearfix"></div>
<?php $lists = mysqli_query($dbc, "SELECT * FROM `order_checklists` WHERE `tile_name`='$folder' AND `deleted`=0");
while($list = mysqli_fetch_assoc($lists)) { ?>
	<ul class="connectedChecklist dashboard-list pull-left" data-heading="<?= $list['id'] ?>">
		<li class="no-sort"><input type="text" class="form-control" value="<?= $list['heading'] ?>" placeholder="Enter a Heading" onblur="updateHeading(this);" <?= !($tile_security['edit'] > 0) ? 'readonly disabled' : '' ?>></li>
		<?php $items = mysqli_query($dbc, "SELECT * FROM `order_checklist_lines` WHERE `checklist_id`='{$list['id']}' AND `deleted`=0 ORDER BY `sort_order` DESC");
		while($item = mysqli_fetch_assoc($items)) { ?>
			<li class="<?= $item['checked'] > 0 ? 'strikethrough' : '' ?>"><input type="checkbox" class="form-checkbox no-gap-pad" value="<?= $item['id'] ?>" <?= $item['checked'] > 0 ? 'checked' : '' ?> onchange="checkItem(this);" <?= !($tile_security['edit'] > 0) ? 'readonly disabled' : '' ?>><input type="hidden" name="heading" value="<?= $list['id'] ?>" onchange="changeHeading(this);"> <?= $item['checklist'] ?><img class="inline-img pull-right sort-handle" src="../img/icons/drag_handle.png"></li>
		<?php } ?>
		<?php if($tile_security['edit'] > 0) { ?>
			<li class="no-sort"><input type="text" class="form-control" value="" placeholder="Add Item" onblur="addItem(this);"></li>
		<?php } ?>
	</ul>
<?php } ?>