//Form Builder JS Include File

//Draggable fields in Fields page
$(function() {
	var clone, before, parent;
	$('.formbuilder_view[data-tab=fields]').sortable({
		connectWith: '.field_div',
		items: 'div.block-item.field_draggable',
		helper: 'clone',
		start: function (e, block) {
			$(block.item).show();
			clone = $(block.item).clone();
			before = $(block.item).prev();
	        parent = $(block.item).parent();
		},
		stop: function (e, block) {
			if($(block.item).parent().hasClass('field_sidebar')) {
				$(this).sortable('cancel');
			} else {
				if(before.length) {
					before.after(clone);	
				} else {
					parent.prepend(clone);
				}
				var formid = $('#formid').val();
				var block = block.item;
				var field_type = block.data('fieldtype');
				var description = block.data('description');
				$.ajax({
					url: '../Form Builder/form_ajax.php?fill=insert_field',
					type: 'POST',
					data: { formid: formid, field_type: field_type, description: description },
					success: function(response) {
						response_arr = response.split('*#*');
						block.html(response_arr[0]);
						block.removeClass('field_draggable').addClass('field_sortable');
						block.attr('data-fieldid', response_arr[1]);
						sortFields();
					}
				});
			}
		}
	});
});
$(function() {
	$('.field_div').sortable({
		connectWith: '.field_div',
		items: 'div.block-item',
		handle: '.drag-handle',
		stop: function (e, block) {
			sortFields();
		}
	});
});
function sortFields() {
	var formid = $('#formid').val();
	var field_order = [];
	var counter = 0;
	$('.field_div .field_sortable').each(function() {
		var field_id = $(this).data('fieldid');
		field_order[counter] = field_id;
		counter++;
	});
	field_order = JSON.stringify(field_order);
	$.ajax({
		url: '../Form Builder/form_ajax.php?fill=sort_fields',
		type: 'POST',
		data: { formid: formid, field_order: field_order },
		success: function(response) {
			// console.log(response);
		}
	});
}