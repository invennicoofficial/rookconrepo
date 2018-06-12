//Form Builder JS Include File for Page-by-Page Styling

//Draggable fields in Page-by-Page Styling
$(document).ready(function() {
	$(window).on('resize', function() {
		resizeView();
	});
	resizeView();
	reloadResizable();
	reloadBringToFront();
});
$(document).on('change', 'select[name="page_number"]', function() { changePage(this); });
$(function() {
	var clone, before, parent, position, zoom, canvasHeight, canvasWidth;
	$('.formbuilder_view[data-tab=pagebypage]').sortable({
		connectWith: '.page_field_div',
		items: 'div.page_field_draggable',
		helper: 'clone',
		appendTo: '.page_field_div',
		sort: function (e, ui) {
	        // zoom fix
	        ui.helper.position.top = Math.round(ui.helper.position.top / zoom);
	        ui.helper.position.left = Math.round(ui.helper.position.left / zoom);
	        ui.helper.position.top = 50;
		},
		start: function (e, block) {
			$(block.item).show();
			clone = $(block.item).clone();
			before = $(block.item).prev();
	        parent = $(block.item).parent();
			zoom = $('.page_field_div').css('zoom');
			canvasHeight = $('.page_field_div').height();
			canvasWidth = $('.page_field_div').width();
		},
		beforeStop: function (e, block) {
			position = block.helper.position();
		},
		stop: function (e, block) {
			if($(block.item).parent().hasClass('page_field_sidebar')) {
				$(this).sortable('cancel');
			} else {
				if(before.length) {
					before.after(clone);
				} else {
					parent.prepend(clone);
				}
				var block = block.item;
				block.removeClass('page_field_draggable').addClass('page_field_sortable');
				block.css('top', position.top);
				block.css('left', position.left);
				block.css('height', '25px');
				block.css('width', '50px');
				if($(block).data('whitespace') == 1) {
					$(block).text('');
					$(block).addClass('page_field_whitespace');
					$(block).addClass('toggled');
				}
				reloadResizable();
				reloadBringToFront();
				savePageDetail(block);
			}
		}
	});
});
$(function() {
	var position;
	$('.page_field_div').sortable({
		connectWith: '.page_field_div',
		items: 'div.page_field_sortable',
		helper: 'clone',
		appendTo: '.page_field_div',
		cancel: '.page_field_whitespace:not(.toggled)',
		start: function(e, block) {
			$('.page_trashcan').hover(
				function() {
					$('.page_trashcan').addClass('inverted');
				}, function() {
					$('.page_trashcan').removeClass('inverted');
				}
			);
		},
		beforeStop: function(e, block) {
			position = block.helper.position();
		},
		stop: function (e, block) {
			var block = block.item;
			block.css('top', position.top);
			block.css('left', position.left);
			if($('.page_trashcan').hasClass('inverted')) {
				deletePageDetail(block);
			} else {
				savePageDetail(block);
			}
			$('.page_trashcan').removeClass('inverted');
			$('.page_trashcan').unbind('mouseenter mouseleave');
		}
	});
});
$(function() {
	$('.page_order_div').sortable({
		connectWith: '.page_order_div',
		items: 'div.page_order_sortable',
		appendTo: '.page_order_div',
		stop: function (e, block) {
			var formid = $('#formid').val();
			var pages = [];
			$('.page_order_sortable').each(function() {
				pages.push($(this).data('id'));
			});
			$.ajax({
				url: '../Form Builder/form_ajax.php?fill=sort_pages',
				type: 'POST',
				data: { formid: formid, pages: pages },
				success: function(response) {
					window.location.reload();
				}
			});
		}
	});
});
function savePageDetail(block) {
	var page_id = $('#page_id').val();
	var page_detail_id = $(block).data('id');
	var field_name = $(block).data('fieldname');
	var field_label = $(block).data('label');
	var top = $(block).css('top');
	var left = $(block).css('left');
	var width = $(block).css('width');
	var height = $(block).css('height');
	var white_space = 0;
	if($(block).data('whitespace') == 1) {
		white_space = 1;
	}
	var data = { page_id: page_id, page_detail_id: page_detail_id, field_name: field_name, field_label: field_label, top: top, left: left, width: width, height: height, white_space: white_space };
	$.ajax({
		url: '../Form Builder/form_ajax.php?fill=update_page_detail',
		type: 'POST',
		data: data,
		success: function(response) {
			if(page_detail_id == '' || page_detail_id == undefined) {
				$(block).attr('data-id', response);
			}
		}
	});
}
function deletePageDetail(block) {
	var page_detail_id = $(block).data('id');
	$.ajax({
		url: '../Form Builder/form_ajax.php?fill=delete_page_detail',
		type: 'POST',
		data: { page_detail_id: page_detail_id },
		success: function(response) {
			$(block).remove();
		}
	});
}
function reloadBringToFront() {
	$('div.page_field_sortable').not('.page_field_whitespace').click(function() {
		$('div.page_field_sortable').not('.page_field_whitespace').css('z-index', 1);
		$(this).css('z-index', 9999);
	});
}
function reloadResizable() {
	$('div.page_field_sortable').resizable({
		cancel: '.page_field_whitespace:not(.toggled)',
		stop: function (e, block) {
			savePageDetail(block.element);
		}
	});
}
function changePage(sel) {
	var formid = $('#formid').val();
	window.location.href = '?edit='+formid+'&tab=pagebypage&page='+sel.value;
}
function resizeView() {
	$('.scalable').height($('.scale-to-fill .main-screen').height());
}
function toggleWhiteSpace() {
	if($('[name="toggle_white_space"]').is(':checked')) {
		$('.page_field_sortable').not('.page_field_whitespace').hide();
		$('.page_field_whitespace').addClass('toggled');
		$('.page_field_draggable').hide();
		$('.page_field_draggable[data-whitespace="1"]').show();
	} else {
		$('.page_field_sortable').not('.page_field_whitespace').show();
		$('.page_field_whitespace').removeClass('toggled');
		$('.page_field_draggable').show();
		$('.page_field_draggable[data-whitespace="1"]').hide();
	}
}
function deletePage() {
	if(confirm('Are you sure you want to delete this page and all its contents?')) {
		var page_id = $('#page_id').val();
		$.ajax({
			url: '../Form Builder/form_ajax.php?fill=delete_page',
			type: 'POST',
			data: { page_id: page_id },
			success: function(response) {
				var formid = $('#formid').val();
				window.location.href = '?edit='+formid+'&tab=pagebypage';
			}
		});
	}
}
function uploadPageImage() {
	if($('[name="img_upload"]').val() == '') {
		alert('No file added.');
		return false;
	} else {
		return true;
	}
}
function checkFileExtension() {
	$('[name="pdf_page_number_all"]').prop('checked', false);
	var file = $('[name="img_upload"]').val().toString();
	var file_extension = (file.substr((file.lastIndexOf('.') +1))).toLowerCase();
	if(file_extension == 'pdf') {
		$('.pdf_page_settings').show();
		$('[name="file_type"]').val('pdf');
	} else if(file_extension == 'jpg' || file_extension == 'jpeg' || file_extension == 'png' || file_extension == 'gif') {
		$('.pdf_page_settings').hide();
		$('[name="file_type"]').val('img');
	} else {
		alert('Invalid file format.');
		$('[name="img_upload"]').val('');
		$('.pdf_page_settings').hide();
		$('[name="file_type"]').val('');
	}
}