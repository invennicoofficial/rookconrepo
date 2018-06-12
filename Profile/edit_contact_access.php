<?php $edit_contact = $_GET['edit_contact'];
if($edit_contact != 'true') { ?>
	<script type="text/javascript">
	$(document).ready(function() {
	    $('.main-screen-details').find('.form-group').find('input,select,textarea,.select2,.chosen-container').not('.external-form-submit *').each(function() {
	        $(this).css('pointer-events', 'none');
	        $(this).css('opacity', '0.5');
	        if ($(this)[0].tagName == 'TEXTAREA') {
	            $(this).parent('div').css('pointer-events', 'none');
	            $(this).parent('div').css('opacity', '0.5');
	        }
	    });
	    $('.main-screen-details').find('button').not('.external-form-submit *').hide();
	});
	</script>
<?php } ?>