<?php
$form_id = $user_form_id;
if(!IFRAME_PAGE) {
	include('../Form Builder/generate_form_contents_sidebar.php'); ?>
	<script type="text/javascript">
	$(document).ready(function() {
		if($(window).width() > 767) {
			resizeScreen();
			$(window).resize(function() {
				resizeScreen();
			});
		}
		resetActive();
		$('#user_form_mainscreen').scroll(function() {
			resetActive();
		});
		$('[data-tab-target]').click(function() {
			$('#user_form_mainscreen').scrollTop($('#'+$(this).data('tab-target')).offset().top + $('#user_form_mainscreen').scrollTop() - $('#user_form_mainscreen').offset().top);
			return false;
		});
	});
	function resetActive() {
		var screenTop = $('#user_form_mainscreen').offset().top + 10;
		var screenHeight = $('#user_form_mainscreen').innerHeight();
		$('.active.blue').removeClass('active blue');
		$('.tab-section').filter(function() { return $(this).offset().top + this.clientHeight > screenTop && $(this).offset().top < screenTop + screenHeight; }).each(function() {
			$('[data-tab-target='+$(this).attr('id').replace('tab_section_','')+']').find('li').addClass('active blue');
		});
	}
	function resizeScreen() {
		var view_height = $(window).height() > 800 ? $(window).height() : 800;
		$('#user_form_sidebar,#user_form_mainscreen').height(view_height - $('#user_form_sidebar').offset().top - $('#footer').outerHeight());	
	}
	</script>
<?php } ?>
<div class="scale-to-fill has-main-screen" id="user_form_mainscreen" style="background-color: #fff;">
    <div class="main-screen standard-body default_screen form-horizontal">
        <div class="standard-body-title">
            <h3><?= $sub_heading ?></h3>
        </div>
        <div class="standard-body-content pad-left pad-right">
        	<?php if(!empty($_GET['formid'])) {
                    $is_user_form = get_safety($dbc, $_GET['safetyid'], 'user_form_id');
                    if($is_user_form > 0) {
                        $form_details = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `user_form_pdf` WHERE `pdf_id` = '".$_GET['formid']."'"));
                        echo '<div class="gap-bottom gap-top"><small>Created by '.get_contact($dbc, $form_details['contactid']).' on '.$form_details['today_date'].'</small></div>';
                    }
                }