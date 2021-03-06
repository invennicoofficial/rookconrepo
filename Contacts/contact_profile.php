<?php include_once('../include.php'); ?>
<script>
function edit_profile() {
	if($('.panel-heading:contains("View Profile")').is(':visible')) {
		$('.panel:contains("View Profile")').nextAll('.panel').find('.panel-heading a').click();
	} else {
		$('#view_profile').hide();
		$('#view_history').hide();
		$('#view_checklist').hide();
		$('#edit_profile').show();
	}
	scrollScreen();
}
function view_profile() {
	if($('.panel-heading:contains("View Profile")').not(':visible')) {
		$('#view_profile').show();
		$('#edit_profile').hide();
		$('#view_checklist').hide();
		$('#view_history').hide();
		$('.active.blue').removeClass('active').removeClass('blue');
		$('[href=#view_profile] li').addClass('active blue');
	}
	scrollScreen();
}
function view_checklist() {
	if($('.panel-heading:contains("Checklists")').not(':visible')) {
		$('#view_checklist').show();
		$('#view_profile').hide();
		$('#edit_profile').hide();
		$('#view_history').hide();
		$('.active.blue').removeClass('active').removeClass('blue');
		$('[href=#view_checklist] li').addClass('active blue');
	}
	scrollScreen();
}
function statusChange(link) {
	var change_status = $(link).data('status') == "0" ? 'Activate' : 'Deactivate';
	if(confirm("Are you sure you want to "+change_status+" this contact?")) {
		$(link).text($(link).data('status') == "0" ? 'Deactivate' : 'Activate').data('status',$(link).data('status') == "0" ? '1' : '0');
		$.ajax({
			url: '../Contacts/contacts_ajax.php?action=status_change&contactid='+$(link).data('contactid')+'&new_status='+$(link).data('status'),
			method: 'POST'
		});
	}
}
function copyContact(btn) {
	$('#dialog_copy_contact').dialog({
		resizable: false,
		height: "auto",
		width: ($(window).width() <= 500 ? $(window).width() : 500),
		modal: true,
		buttons: {
			"Copy Contact": function() {
				var contactid = $('[name="contactid"]').val();
				var category = $('[name="copy_contact_category"]').val();
				$.ajax({
					url: '../Contacts/contacts_ajax.php?action=copy_contact',
					method: 'POST',
					data: { contactid: contactid, category: category },
					success: function(response) {
						window.location.href = "?edit="+response;
					}
				});
				$(this).dialog('close');
			},
	        Cancel: function() {
	        	$(this).dialog('close');
	        }
		}
	});
}
</script>
<?php $field_config = explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT `contacts` FROM `field_config_contacts` WHERE `tile_name`='".FOLDER_NAME."' AND `tab`='$current_type' AND `subtab` = '**no_subtab**'"))[0] . ',' . mysqli_fetch_array(mysqli_query($dbc, "SELECT `contacts` FROM `field_config_contacts` WHERE `tile_name`='".FOLDER_NAME."' AND `tab`='$current_type' AND `subtab` = 'additions'"))[0]);
if($_GET['edit'] > 0) {
	$contactid = $_GET['edit'];
} else if($_GET['contactid'] > 0) {
	$contactid = $_GET['contactid'];
} else {
	$contactid = 0;
}
$contact = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` LEFT JOIN `contacts_cost` ON `contacts`.`contactid`=`contacts_cost`.`contactid` LEFT JOIN `contacts_dates` ON `contacts`.`contactid`=`contacts_dates`.`contactid` LEFT JOIN `contacts_description` ON `contacts`.`contactid`=`contacts_description`.`contactid` LEFT JOIN `contacts_medical` ON `contacts`.`contactid`=`contacts_medical`.`contactid` LEFT JOIN `contacts_upload` ON `contacts`.`contactid`=`contacts_upload`.`contactid` WHERE `contacts`.`contactid`='$contactid'")); ?>
<div class="col-sm-12">
	<?php if($contact['category'] != 'Staff' && vuaed_visible_function($dbc, $security_folder) > 0) { ?><button onclick="copyContact(this); return false;" class="btn brand-btn pull-right gap-top">Copy Contact</button><button onclick="<?= IFRAME_PAGE ? "$('#profile_accordions').show(); $('.iframe_edit').show(); $('#view_profile').hide();" : " edit_profile();" ?> return false;" class="btn brand-btn pull-right gap-top">Edit Contact</button><?= IFRAME_PAGE ? '<a href="" onclick="openFullView(); return false;" class="btn brand-btn pull-right gap-top">Open Full Window</a>' : '' ?><?php }
	else if(FOLDER_NAME != 'profile' && !isset($_GET['view_only']) && vuaed_visible_function($dbc, 'staff') > 0) { ?><a href='?contactid=<?php echo $contactid; ?>&subtab=staff_information' class="hide-on-mobile btn brand-btn pull-right gap-top">Edit Staff</a><?php }
	else if(FOLDER_NAME != 'profile' && !isset($_GET['view_only']) && !(vuaed_visible_function($dbc, 'staff') > 0) && $contactid == $_SESSION['contactid']) { ?><a href='<?= WEBSITE_URL ?>/Profile/my_profile.php?edit_contact=true&from_staff_tile=true' class="hide-on-mobile btn brand-btn pull-right gap-top">Edit My Profile</a><?php } ?>
	<h3 class="gap-left"><?php if($contact['contactimage'] != '' && file_exists('download/'.$contact['contactimage']) && $contact['category'] != 'Staff') {
			echo '<img class="id-circle" src="download/'.$contact['contactimage'].'">';
			$contact_url = '';
		} else if($contact['category'] == 'Staff') {
			$field_config = '';
			$config_query = mysqli_query($dbc,"SELECT contacts FROM field_config_contacts WHERE tab='Staff' AND `accordion` IS NOT NULL AND `order` IS NOT NULL ORDER BY `subtab`, `order`");
			while($config_row = mysqli_fetch_assoc($config_query)) {
				$field_config .= ','.$config_row['contacts'].',';
			}
			$field_config = explode(',',$field_config);
			if($contact['contactimage'] != '' && (file_exists('../Staff/download/'.$contact['contactimage']) || file_exists('../Profile/download/'.$contact['contactimage']))) {
				if(file_exists('../Staff/download/'.$contact['contactimage'])) {
					$contact_url = '../Staff/';
				} else {
					$contact_url = '../Profile/';
				}
				echo '<img class="id-circle" src="'.$contact_url.'download/'.$contact['contactimage'].'">';
			} else {
				profile_id($dbc, $contactid);
			}
		}
		$id_card_fields = get_config($dbc, config_safe_str($contact['category']).'_id_card_fields');
		if($id_card_fields == '') {
			$id_card_fields = $field_config;
		} else {
			$id_card_fields = explode(',',$id_card_fields);
		} ?>
		<?= get_client($dbc, $contactid).' '.get_contact($dbc, $contactid) ?></h3>
	<div class="col-sm-6">
		<ul class="chained-list col-sm-6 small">
			<?php if($contact['contactimage'] != '' && file_exists($contact_url.'download/'.$contact['contactimage'])) { ?><li style="text-align: center;"><img src="<?= $contact_url ?>download/<?= $contact['contactimage'] ?>" style="max-width: 200px; max-height: 200px;"></li><?php } ?>
			<?php if(in_array_any(['Employee Number','Employee ID','Employee #'], $id_card_fields)) { ?><li><img src="../img/id-card.png" style="height:1.5em;" title="ID Number"><?= $contactid ?></li><?php } ?>
			<?php if(in_array_any(['First Name','Last Name','Profile First Name','Profile Last Name'], $id_card_fields) && $contact['first_name'].$contact['last_name'] != '') { ?><li><img src="../img/person.PNG" style="height:1.5em;" title="Full Name"><?= decryptIt($contact['first_name']).' '.decryptIt($contact['last_name']) ?></li><?php } ?>
			<?php if(in_array_any(['Position'], $id_card_fields) && $contact['position'] != '') {
				$position_name = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `name` FROM `positions` WHERE `position_id` = '{$contact['position']}'"))['name']; ?><li><img src="../img/job.png" style="height:1.5em;" title="Position"><?= !empty($position_name) ? $position_name : $contact['position'] ?></li><?php } ?>
			<?php if(in_array_any(['Home Phone','Profile Home Phone'], $id_card_fields) && $contact['home_phone'] != '') { ?><li><a href="tel:<?= decryptIt($contact['home_phone']) ?>"><img src="../img/home_phone.PNG" style="height:1.5em;" title="Home Phone"><?= decryptIt($contact['home_phone']) ?></a></li><?php } ?>
			<?php if(in_array_any(['Office Phone','Profile Office Phone'], $id_card_fields) && $contact['office_phone'] != '') { ?><li><a href="tel:<?= decryptIt($contact['office_phone']) ?>"><img src="../img/office_phone.PNG" style="height:1.5em;" title="Office Phone"><?= decryptIt($contact['office_phone']) ?></a></li><?php } ?>
			<?php if(in_array_any(['Cell Phone','Profile Cell Phone'], $id_card_fields) && $contact['cell_phone'] != '') { ?><li><a href="tel:<?= decryptIt($contact['cell_phone']) ?>"><img src="../img/cell_phone.PNG" style="height:1.5em;" title="Cell Phone"><?= decryptIt($contact['cell_phone']) ?></a></li><?php } ?>
			<?php if(in_array_any(['Email Address','Profile Email Address'], $id_card_fields) && $contact['email_address'] != '') { ?><li><a href="mailto:<?= decryptIt($contact['email_address']) ?>"><img src="../img/email.PNG" style="height:1.5em;" title="Email Address"><?= decryptIt($contact['email_address']) ?></a></li><?php } ?>
			<?php if(in_array_any(['Second Email Address'], $id_card_fields) && $contact['second_email_address'] != '') { ?><li><a href="mailto:<?= decryptIt($contact['second_email_address']) ?>"><img src="../img/email.PNG" style="height:1.5em;" title="Second Email Address"><?= decryptIt($contact['second_email_address']) ?></a></li><?php } ?>
			<?php if(in_array_any(['Company Email Address','Profile Company Email Address'], $id_card_fields) && $contact['office_email'] != '') { ?><li><a href="mailto:<?= decryptIt($contact['office_email']) ?>"><img src="../img/email.PNG" style="height:1.5em;" title="Company Email Address"><?= decryptIt($contact['office_email']) ?></a></li><?php } ?>
			<?php if(in_array_any(['Start Date'], $id_card_fields) && $contact['start_date'] != '' && $contact['start_date'] != '0000-00-00') { ?><li><img src="../img/calendar.png" style="height:1.5em;" title="Start Date"><?= $contact['start_date'] ?>
                <?php if (FOLDER_NAME=='profile' && ($contact['start_date'] != '0000-00-00' || empty($contact['start_date']))) {
                    //Check if today is the work anniversary. If so, display it.
                    if ( date('m-d')==substr($contact['start_date'],5,5) ) {
                        $start_date = new DateTime($contact['start_date']);
                        $today = new DateTime(date('Y-m-d'));
                        $diff = $today->diff($start_date);
                        echo ' | ' . $diff->y . ' years';
                    }
                } ?></li>
            <?php } ?>
			<?php if(in_array_any(['Contract End Date'], $id_card_fields) && $contact['contract_end_date'] != '' && $contact['contract_end_date'] != '0000-00-00') { ?><li><img src="../img/calendar.png" style="height:1.5em;" title="Contract End Date"><?= $contact['contract_end_date'] ?></li>
            <?php } ?>
			<li><img src="../img/setting.PNG" style="height:1.5em;" title="Status">
			<?php if(vuaed_visible_function($dbc, $contact['category'] == 'staff' ? 'staff' : $security_folder) > 0) { ?><?= $contact['status'] > 0 ?
				'Active | <a href="" onclick="statusChange(this); return false;" data-status="1" data-contactid="'.$contact['contactid'].'">Deactivate</a>' :
				'Inactive | <a href="" onclick="statusChange(this); return false;" data-status="0" data-contactid="'.$contact['contactid'].'">Activate</a>' ?><?php
			} else {
				echo $contact['status'] > 0 ? 'Active' : 'Inactive';
			} ?></li>
		</ul>
	</div>
	<div class="col-sm-6">
		<ul class="chained-list col-sm-6 small">
			<?php if(in_array_any(['Business','Program Business'], $id_card_fields) && $contact['businessid'] > 0) { ?><li><img src="../img/business.PNG" style="height:1.5em;" title="Business"><?= get_client($dbc, $contact['businessid']) ?></li><?php } ?>
			<?php if(in_array_any(['Name'], $id_card_fields) && $contact['name'] != '') { ?><li><img src="../img/business.PNG" style="height:1.5em;" title="Business Name"><?= decryptIt($contact['name']) ?></li><?php } ?>
			<?php if(in_array_any(['Location','Profile Location'], $id_card_fields) && $contact['con_location'] != '') { ?><li><img src="../img/address.PNG" style="height:1.5em;" title="Location"><?= ($contact['con_location']) ?></li><?php } ?>
			<?php if(in_array_any(['Business Address'], $id_card_fields) && $contact['business_street'] != '') { ?><li><a class="show-on-mob" href="maps:<?= decryptIt($contact['business_street']) ?>"><img src="../img/address.PNG" title="Business Address" class="inline-img"><?= decryptIt($contact['business_street']) ?></a><a class="hide-on-mobile" href="https://maps.google.com/maps/place/<?= decryptIt($contact['business_street']) ?>"><img src="../img/address.PNG" title="Business Address" class="inline-img"><?= decryptIt($contact['business_street']) ?></a></li><?php } ?>
			<?php if(in_array_any(['Mailing Address'], $id_card_fields) && $contact['mailing_address'] != '') { ?><li><a class="show-on-mob" href="maps:<?= ($contact['mailing_address']) ?>"><img src="../img/address.PNG" title="Mailing Address" class="inline-img"><?= ($contact['mailing_address']) ?></a><a class="hide-on-mobile" href="https://maps.google.com/maps/place/<?= ($contact['mailing_address']) ?>"><img src="../img/address.PNG" title="Mailing Address" class="inline-img"><?= ($contact['mailing_address']) ?></a></li><?php } ?>
			<?php if(in_array_any(['Address'], $id_card_fields) && $contact['address'] != '') { ?><li><a class="show-on-mob" href="maps:<?= ($contact['address']) ?>"><img src="../img/address.PNG" title="Address" class="inline-img"><?= ($contact['address']) ?></a><a class="hide-on-mobile" href="https://maps.google.com/maps/place/<?= ($contact['address']) ?>"><img src="../img/address.PNG" title="Address" class="inline-img"><?= ($contact['address']) ?></a></li><?php } ?>
			<?php if(in_array_any(['Birth Date','Date of Birth','Profile Date of Birth'], $id_card_fields) && $contact['birth_date'] != '' && $contact['birth_date'] != '0000-00-00') { ?><li><img src="../img/birthday.png" title="Birth Date" class="inline-img"><?= $contact['birth_date'] ?><?= ( $contact['birth_date']=='0000-00-00' || empty($contact['birth_date']) ) ? '' : ' Age: '.date_diff(date_create($contact['birth_date']), date_create('now'))->y ?></li><?php } ?>
			<?php if(in_array_any(['Guardians First Name'], $id_card_fields) && $contact['guardians_first_name'] != '') { ?>
				<?php $guardian_count = count(explode('*#*', $contact['guardians_first_name']));
				for ($counter = 0; $counter < $guardian_count && !empty($contact['guardians_first_name']); $counter++) { ?>
					<li><img src="../img/person.PNG" title="Guardian <?= ($counter+1) ?>" class="inline-img"><?= explode('*#*', $contact['guardians_first_name'])[$counter].' '.explode('*#*', $contact['guardians_last_name'])[$counter] ?></li>
					<?php if(in_array_any(['Guardians Work Phone','Guardians Home Phone','Guardians Cell Phone'], $id_card_fields)) { ?>
						<li><img src="../img/home_phone.PNG" title="Guardian <?= ($counter+1) ?> Phone Number" style="height:1.5em;"><?= !empty(explode('*#*', $contact['guardians_home_phone'])[$counter]) ? explode('*#*', 'H: '.$contact['guardians_home_phone'])[$counter].'&nbsp;&nbsp;' : '' ?><?= !empty(explode('*#*', $contact['guardians_work_phone'])[$counter]) ? explode('*#*', 'O: '.$contact['guardians_work_phone'])[$counter].'&nbsp;&nbsp;' : '' ?><?= !empty(explode('*#*', $contact['guardians_cell_phone'])[$counter]) ? explode('*#*', 'C: '.$contact['guardians_cell_phone'])[$counter].'&nbsp;&nbsp;' : '' ?></li>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			<?php if(in_array_any(['LinkedIn','Profile LinkedIn'], $id_card_fields) && $contact['linkedin'] != '') { ?><li><a href="<?= $contact['linkedin'] ?>"><img src="../img/icons/social/linkedin.png" style="height:1.5em;" title="LinkedIn" /> LinkedIn</a></li><?php } ?>
			<?php if(in_array_any(['Facebook','Profile Facebook'], $id_card_fields) && $contact['facebook'] != '') { ?><li><a href="<?= $contact['facebook'] ?>"><img src="../img/icons/social/facebook.png" style="height:1.5em;" title="Facebook" /> Facebook</a></li><?php } ?>
			<?php if(in_array_any(['Twitter','Profile Twitter'], $id_card_fields) && $contact['twitter'] != '') { ?><li><a href="<?= $contact['twitter'] ?>"><img src="../img/icons/social/twitter.png" style="height:1.5em;" title="Twitter" /> Twitter</a></li><?php } ?>
			<?php if(in_array_any(['Google+','Profile Google+'], $id_card_fields) && $contact['google_plus'] != '') { ?><li><a href="<?= $contact['google_plus'] ?>"><img src="../img/icons/social/google+.png" style="height:1.5em;" title="Google+" /> Google+</a></li><?php } ?>
			<?php if(in_array_any(['Instagram','Profile Instagram'], $id_card_fields) && $contact['instagram'] != '') { ?><li><a href="<?= $contact['instagram'] ?>"><img src="../img/icons/social/instagram.png" style="height:1.5em;" title="Instagram" /> Instagram</a></li><?php } ?>
			<?php if(in_array_any(['Pinterest','Profile Pinterest'], $id_card_fields) && $contact['pinterest'] != '') { ?><li><a href="<?= $contact['pinterest'] ?>"><img src="../img/icons/social/pinterest.png" style="height:1.5em;" title="Pinterest" /> Pinterest</a></li><?php } ?>
			<?php if(in_array_any(['YouTube','Profile YouTube'], $id_card_fields) && $contact['youtube'] != '') { ?><li><a href="<?= $contact['youtube'] ?>"><img src="../img/icons/social/youtube.png" style="height:1.5em;" title="Youtube" /> YouTube</a></li><?php } ?>
			<?php if(in_array_any(['Blog','Profile Blog'], $id_card_fields) && $contact['blog'] != '') { ?><li><a href="<?= $contact['blog'] ?>"><img src="../img/icons/social/rss.png" style="height:1.5em;" title="Blog" /> Blog</a></li><?php } ?>
			<?php if($contact['category'] == 'Staff') {
				$business_card_template = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `business_card_template` WHERE `contact_category` = '".$contact['category']."'")); ?>
				<li>&nbsp;<img src="../img/pdf.png" style="height:1.2em;" title="PDF" />
					<?PHP if(!empty($business_card_template['template'])) { ?>
						<a href="../Staff/business_card_templates/<?= $business_card_template['template'] ?>_pdf.php?contactid=<?= $contactid ?>">Business Card PDF</a> | 
					<?php } ?>
					<a href="../Staff/id_card_pdf.php?contactid=<?= $contactid ?>">ID Card PDF</a>
				</li>
			<?PHP } ?>
		</ul>
	</div>
</div>