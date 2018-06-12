$(function() {
    $( ".connectedSortable" ).sortable({
		connectWith: ".connectedSortable",
		update: function( event, ui ) {
			var ticketid = ui.item.attr("id"); //Done
			//var tasklistid = ticketid.split('_');
			
			//var table_class = $(this).parent().attr("class");
			var table_class = ui.item.parent().attr("class");
			var status = table_class.split(' ');

			$.ajax({    //create an ajax request to load_page.php
				type: "GET",
				url: "task_ajax_all.php?fill=ticket&ticketid="+ticketid+"&status="+status[1],
				dataType: "html",   //expect html to be returned
				success: function(response){
					//alert(response);
				}
			});
		}
    }).disableSelection();

    $( ".connectedSortable" ).sortable({
      cancel: ".ui-state-disabled"
    });

    /*
	$( "#sortable1" ).sortable({
      items: "li:not(.ui-state-disabled)"
    });
 
    $( "#sortable2" ).sortable({
      cancel: ".ui-state-disabled"
    });
 
    $( "#sortable1 li, #sortable2 li" ).disableSelection();
	*/
	DoubleScroll(document.getElementById('scrum_tickets'));

});

function changeEndAme(sel) {
	$(".ui-state-default").prop("disabled",false);
	var stage = sel.value;
	var typeId = sel.id;

	var stage = stage.replace(" ", "FFMSPACE");
	var stage = stage.replace("&", "FFMEND");
	var stage = stage.replace("#", "FFMHASH");

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "task_ajax_all.php?fill=add_task&tast_data="+typeId+"&tast_value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}

function DoubleScroll(element) {
        var scrollbar= document.createElement('div');
        scrollbar.appendChild(document.createElement('div'));
        scrollbar.style.overflow= 'auto';
        scrollbar.style.overflowY= 'hidden';
        scrollbar.style.width= '';
        scrollbar.firstChild.style.width= element.scrollWidth+'px';
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