<script>
function handleClick(sel) {

    var stagee = sel.value;
	var contactide = $('.contacterid').val();
	
	if(stagee == 'yes') {
		$('.hide_numofdays').show();
	}
	if(stagee == 'no') {
		$('.hide_numofdays2').val('');
		$('.hide_numofdays').hide();
	}
	
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "task_ajax_all.php?fill=trellotable&contactid="+contactide+"&value="+stagee,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});

}
</script>