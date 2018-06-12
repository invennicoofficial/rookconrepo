$(function() {
    $( ".connectedChecklist" ).sortable({
		beforeStop: function(e, ui) { ui.helper.removeClass('popped-field'); },
		connectWith: ".connectedChecklist",
		delay: 500,
		handle: ".drag_handle",
		items: "li:not(.no-sort)",
		start: function(e, ui) {ui.helper.addClass('popped-field'); },
		update: function( event, ui ) {
			var checklistnameid = ui.item.attr("id"); //Done
			var after_checklistnameid = ui.item.prev().attr('id');

			var table_class = ui.item.parent().attr("class");
			var status = table_class.split(' ');

			$.ajax({    //create an ajax request to load_page.php
				type: "GET",
				url: "../Checklist/checklist_ajax.php?fill=checklist_priority&checklistnameid="+checklistnameid+"&after_checklistnameid="+after_checklistnameid,
				dataType: "html",   //expect html to be returned
				success: function(response){
				}
			});
		}
    });

});

function changeEndAme(sel) {
	$(this).focus();

	$(this).prop("disabled",false);
	var stage = sel.value;
	var typeId = sel.id;
	
	var checklist = typeId.split(' ');
	var checklistid = checklist[1];

	var stage = stage.replace(" ", "FFMSPACE");
	var stage = stage.replace("&", "FFMEND");
	var stage = stage.replace("#", "FFMHASH");

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../Checklist/checklist_ajax.php?fill=add_checklist&checklistid="+checklistid+"&checklist="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}

function handleClick(sel) {
    var stagee = sel.value;
	var contactide = $('.contacterid').val();

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "task_ajax_all.php?fill=trellotable&contactid="+contactide+"&value="+stagee,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}

function checklistChange(sel) {
	var stage = sel.value;
    if($(sel).is(':checked')){
        var checked = 1;
    } else {
        var checked = 0;
    }
    $.ajax({
        type: "GET",
        url: "../Checklist/checklist_ajax.php?fill=checklist&checklistid="+stage+"&checked="+checked,
        dataType: "html",
        success: function(response){
            location.reload();
        }
    });
}

function checklistIncomplete(sel) {
	var stage = sel.value;
    if($(sel).is(':checked')){
        var checked = 1;
    } else {
        var checked = 0;
    }
    $.ajax({
    	type: "GET",
        type: "GET",
        url: "../Checklist/checklist_ajax.php?fill=checklistIncomplete&checklistid="+stage+"&checked="+checked,
        dataType: "html",
        success: function(response){
            location.reload();
        }
    });
}