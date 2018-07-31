<?php include_once('../include.php');
/*
Software Styling
*/
if($_GET['subtab'] == 'security' && !check_subtab_persmission($dbc, 'software_config', ROLE, 'style_security')) {
	$_GET['subtab'] = '';
} else if($_GET['subtab'] == 'software' && !check_subtab_persmission($dbc, 'software_config', ROLE, 'style_software')) {
	$_GET['subtab'] = '';
}
?>
<script type="text/javascript">
$(document).on('change', 'select[name="sub_category"]', function() { changeLevel(this); });

function changeLevel(sel) {
	var stage = sel.value;
	window.location = '?tab=style&subtab=security&level='+stage;
}

function handleClick(sel) {

    var stagee = sel.value;
	var contactid = $('.contacterid').val();

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL; ?>/ajax_all.php?fill=styler_configuration&contactid="+contactid+"&value="+stagee+"&subtab=<?= $_GET['subtab'] ?>&level=<?= $_GET['level'] ?>",
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});

}

function sortTable(){
	if(jQuery("#caltbl").length > 0) {
		var tbl = document.getElementById("caltbl").tBodies[0];
		var store = [];
		for(var i=0, len=tbl.rows.length; i<len; i++){
			var row = tbl.rows[i];
			var sortnr = parseFloat(row.cells[0].textContent || row.cells[0].innerText);
			if(!isNaN(sortnr)) store.push([sortnr, row]);
		}
		store.sort(function(x,y){
			return x[0] - y[0];
		});
		for(var i=0, len=store.length; i<len; i++){
			tbl.appendChild(store[i][1]);
		}
		store = null;
	}
}
sortTable();
</script><?php

$notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab='setting_styling'"));
$note = $notes['note'];
    
if ( !empty($note) ) { ?>
    <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="../img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11">
            <span class="notice-name">NOTE:</span>
            <?= $note; ?>
        </div>
        <div class="clearfix"></div>
    </div><?php
} ?>

<div class="col-md-12">
	<div class="gap-bottom">
		<a href='settings.php?tab=style'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo (empty($_GET['subtab']) ? ' active_tab' : ''); ?>' >User Email Settings</button></a>
		<?php if(check_subtab_persmission($dbc, 'software_config', ROLE, 'style_software')) { ?>
			<a href='settings.php?tab=style&subtab=software'><button type='button' class='btn brand-btn mobile-block mobile-100<?php echo ($_GET['subtab'] == 'software' ? ' active_tab' : ''); ?>' >Software Email Settings</button></a>
		<?php } ?>
	</div>
</div>