$(function() {
    $( ".connectedSortable" ).sortable({
		connectWith: ".connectedSortable",
		update: function( event, td ) {
			var activity = td.item.attr("id"); //Done
			var intercalendarid = activity.split('_');

			var table_class = td.item.parent().attr("class");
			var activity_info = table_class.split(' ');
			var activity_date = activity_info[2];

			$.ajax({    //create an ajax request to load_page.php
				type: "GET",
				url: "calendar_ajax_all.php?fill=activity_calendar&intercalendarid="+intercalendarid[1]+"&activity_date="+activity_date,
				dataType: "html",   //expect html to be returned
				success: function(response){
					location.reload();
					//return false;
				}
			});
		}
    }).disableSelection();

    $( ".connectedSortable" ).sortable({
      cancel: ".ui-state-disabled"
    });

});

