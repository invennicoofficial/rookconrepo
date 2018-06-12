$(function() {

	/*
	$("#task_path").change(function() {
		var task_path = $("#task_path").val();
		$.ajax({
			type: "GET",
			url: "task_ajax_all.php?fill=task_path_milestone&task_path="+task_path,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#task_milestone_timeline').html(response);
				$("#task_milestone_timeline").trigger("change.select2");
			}
		});
	});
	*/
	
    $( ".connectedSortable" ).sortable({
		connectWith: ".connectedSortable",
		delay: 500,
		handle: ".drag_handle",
		items: "li:not(.no-sort)",
		update: function( event, ui ) {
			var ticketid = ui.item.attr("id"); //Done
			var table_class = ui.item.parent().attr("class");
			var status = table_class.split(' ');
			
			$.ajax({    //create an ajax request to load_page.php
				type: "GET",
				url: "scrum_ajax_all.php?fill=move_ticket&ticketid="+ticketid+"&status="+status[1],
				dataType: "html",   //expect html to be returned
				success: function(response){
				}
			});
		}
    }).disableSelection();

	DoubleScroll(document.getElementById('scrum_tickets'));
});

function DoubleScroll(element) {
	if(element != null && element != undefined) {
		var scrollbar= document.createElement('div');
		scrollbar.appendChild(document.createElement('div'));
		scrollbar.style.overflow= 'auto';
		scrollbar.style.overflowY= 'hidden';
		scrollbar.style.width= '';
		scrollbar.firstChild.style.width= element.scrollWidth+'px';
		scrollbar.firstChild.style.height= '0px';
		scrollbar.firstChild.style.paddingTop= '1px';
		scrollbar.firstChild.appendChild(document.createTextNode('\xA0'));
		scrollbar.onscroll= function() {
			element.scrollLeft= scrollbar.scrollLeft;
		};
		element.onscroll= function() {
			scrollbar.scrollLeft= element.scrollLeft;
		};
		element.parentNode.insertBefore(scrollbar, element);
	}
}

function changeEndAme(sel) {
	$(this).focus();

	$(this).prop("disabled",false);
	var stage = sel.value;
	var typeId = sel.id;
	
	var tasklistid = typeId.split(' ');

	var status = tasklistid[1];
	var project_path = tasklistid[2];

	var stage = stage.replace(" ", "FFMSPACE");
	var stage = stage.replace("&", "FFMEND");
	var stage = stage.replace("#", "FFMHASH");

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "scrum_ajax_all.php?fill=add_scrum&status="+status+"&project_path="+project_path+"&heading="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}

/*
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
*/