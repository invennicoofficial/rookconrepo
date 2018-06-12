<script>
var certificate_list = [];
var status_list = [];
status_list['active_complete']=[];
status_list['active_pending']=[];
status_list['active_expiring']=[];
status_list['active_expired']=[];
status_list['suspend_complete']=[];
status_list['suspend_pending']=[];
status_list['suspend_expiring']=[];
status_list['suspend_expired']=[];
<?php $certificates = mysqli_query($dbc, "SELECT `certificate`.`certificateid`, IF(`certificate`.`issue_date` > NOW() OR IFNULL(`certificate`.`issue_date`,'0000-00-00')='0000-00-00','pending',IF(`certificate`.`expiry_date` < NOW(),'expired',IF(`certificate`.`reminder_date` < NOW(),'expiring','complete'))) `cert_status`, IF(`contacts`.`status` > 0,'active','suspend') `cont_status`, `contacts`.`contactid`, `certificate`.`certificate_type`, `certificate`.`expiry_date` FROM `certificate` LEFT JOIN `contacts` ON `certificate`.`contactid`=`contacts`.`contactid` WHERE `certificate`.`deleted`=0");
while($row = mysqli_fetch_assoc($certificates)) { ?>
	status_list['<?= $row['cont_status'].'_'.$row['cert_status'] ?>'].push(['<?= $row['certificateid'] ?>','<?= $row['contactid'] ?>','<?= $row['certificate_type'] ?>','<?= $row['expiry_date'] ?>']);
<?php } ?>
show_certificates = function(link) {
	$('.active.blue').removeClass('active blue');
	$(link).closest('li').addClass('active blue');
	var status = $(link).data('status');
	$('.main-content-screen .main-screen').html('');
	certificate_list = status_list[status].slice();
	if($('.filter_div').is(':visible')) {
		var staff = $('[name=filter_staff]').val();
		var type = $('[name=filter_type]').val();
		var start = $('[name=filter_start]').val();
		var end = $('[name=filter_end]').val();
		for(var i = certificate_list.length - 1; i >= 0; i--) {
			if((staff > 0 && certificate_list[i][1] != staff) ||
				(type != '' && certificate_list[i][2] != type) ||
				(start != '' && certificate_list[i][3] > start) ||
				(end != '' && certificate_list[i][3] < end)) {
				certificate_list.splice(i,1);
			}
		}
	}
	load_certificate();
	return false;
}
$(document).ready(function() {
	show_certificates($('.active.blue a').get(0));
	$(window).scroll(load_certificate);
	$('.main-content-screen .main-screen').scroll(load_certificate);
});
function load_certificate() {
	if(certificate_list.length > 0 && ($('.dashboard-item').length == 0 || $('.dashboard-item').last().offset().top < $(window).innerHeight() + 200)) {
		var cert = certificate_list.shift();
		$.ajax({
			url:'load_certificate.php',
			method:'POST',
			data: {
				certificate: cert[0]
			},
			success: function(response) {
				$('.main-content-screen .main-screen').append(response);
				load_certificate();
			}
		});
	}
}
</script>