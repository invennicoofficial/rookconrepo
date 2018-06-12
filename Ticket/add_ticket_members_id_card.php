<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Members ID Card</h3>') ?>
<?php $query = mysqli_query($dbc, "SELECT * FROM `ticket_attached` WHERE `ticket_attached`.`item_id` > 0 AND `src_table`='Members' AND `deleted`=0 AND `ticketid`='$ticketid' AND `ticketid` > 0 AND `item_id` > 0 AND `tile_name`='".FOLDER_NAME."'".$query_daily);
$member_count = mysqli_num_rows($query);
$member_i = 0; ?>
<div class="col-md-6 form-group">
	<?php while($member = mysqli_fetch_assoc($query)) {
		if($member_i >= ($member_count / 2) && $member_count > 1) { ?>
			</div>
			<div class="col-md-6 form-group">
		<?php } 
		$member_info = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` LEFT JOIN `contacts_cost` ON `contacts`.`contactid`=`contacts_cost`.`contactid` LEFT JOIN `contacts_dates` ON `contacts`.`contactid`=`contacts_dates`.`contactid` LEFT JOIN `contacts_description` ON `contacts`.`contactid`=`contacts_description`.`contactid` LEFT JOIN `contacts_medical` ON `contacts`.`contactid`=`contacts_medical`.`contactid` LEFT JOIN `contacts_upload` ON `contacts`.`contactid`=`contacts_upload`.`contactid` WHERE `contacts`.`contactid`='".$member['item_id']."'")); ?>
		<?php if($member['item_id'] > 0) {
			if($generate_pdf) { ob_clean(); } ?>
			<div class="member_id_group" style="padding-bottom: 1em;">
				<?php profile_id($dbc, $member['item_id']); ?><?= get_contact($dbc, $member['item_id']) ?>
				<ul class="chained-list-ticket" style="border-bottom: 1px solid #ddd;">
					<?php foreach($field_sort_order as $field_sort_field) { ?>
						<?php if(strpos($value_config,',Members ID Age,') !== FALSE && $field_sort_field == 'Members ID Age') { ?>
							<li class="col-xs-12"><div class="col-xs-2" style="max-width: 35px;"><img src="../img/birthday.png" title="Birth Date" class="inline-img"></div><div class="col-xs-10"><?= $member_info['birth_date'] ?><?= ( $member_info['birth_date']=='0000-00-00' || empty($member_info['birth_date']) ) ? '' : ' <b>Age:</b> '.date_diff(date_create($member_info['birth_date']), date_create('now'))->y ?></div></li>
						<?php } ?>
						<?php if(strpos($value_config,',Members ID Parental Guardian Family Contact,') !== FALSE && $field_sort_field == 'Members ID Parental Guardian Family Contact') {
							$guardian_count = count(explode('*#*', $member_info['guardians_first_name']));
							for($counter = 0; $counter < $guardian_count; $counter++) {
								if((explode('*#*', $member_info['guardians_first_name'])[$counter].' '.explode('*#*', $member_info['guardians_last_name'])[$counter]) != ' ') { ?>
									<li class="col-xs-12"><div class="col-xs-2" style="max-width: 35px;"><img src="../img/person.PNG" title="Guardian <?= ($counter+1) ?>" class="inline-img"></div><div class="col-xs-10">
										<?= explode('*#*', $member_info['guardians_first_name'])[$counter].' '.explode('*#*', $member_info['guardians_last_name'])[$counter] ?><br>
										<?= !empty(explode('*#*', $member_info['guardians_relationship'])[$counter]) ? explode('*#*', '<b>Relationship:</b> '.$member_info['guardians_relationship'])[$counter].'<br>' : '' ?>
										<?= !empty(explode('*#*', $member_info['guardians_home_phone'])[$counter]) ? explode('*#*', '<b>H:</b> '.$member_info['guardians_home_phone'])[$counter].'<br>' : '' ?>
										<?= !empty(explode('*#*', $member_info['guardians_work_phone'])[$counter]) ? explode('*#*', '<b>O:</b> '.$member_info['guardians_work_phone'])[$counter].'<br>' : '' ?>
										<?= !empty(explode('*#*', $member_info['guardians_cell_phone'])[$counter]) ? explode('*#*', '<b>C:</b> '.$member_info['guardians_cell_phone'])[$counter].'<br>' : '' ?>
									</div></li>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<?php if(strpos($value_config,',Members ID Emergency Contact,') !== FALSE && $field_sort_field == 'Members ID Emergency Contact') {
							$emergency_count = count(explode('*#*', $member_info['emergency_first_name']));
							for($counter = 0; $counter < $emergency_count; $counter++) {
								if((explode('*#*', $member_info['emergency_first_name'])[$counter].' '.explode('*#*', $member_info['emergency_last_name'])[$counter] != ' ')) { ?>
									<li class="col-xs-12"><div class="col-xs-2" style="max-width: 35px;"><img src="../img/person.PNG" title="Emergency Contact <?= ($counter+1) ?>" class="inline-img"></div><div class="col-xs-10">
										<?= explode('*#*', $member_info['emergency_first_name'])[$counter].' '.explode('*#*', $member_info['emergency_last_name'])[$counter] ?><br>
										<?= !empty(explode('*#*', $member_info['emergency_relationship'])[$counter]) ? explode('*#*', '<b>Relationship:</b> '.$member_info['emergency_relationship'])[$counter].'<br>' : '' ?>
										<?= !empty(explode('*#*', $member_info['emergency_contact_number'])[$counter]) ? explode('*#*', '<b>P:</b> '.$member_info['emergency_contact_number'])[$counter].'<br>' : '' ?>
									</div></li>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<?php if(strpos($value_config,',Members ID Medications,') !== FALSE && $field_sort_field == 'Members ID Medications') {
							$medications = mysqli_query($dbc, "SELECT `medicationid`, `medication`.`title`, `medication`.`dosage`, '00:00:00' FROM `medication` WHERE `medication`.`deleted`=0 AND `medication`.`clientid`='{$member['item_id']}' AND IFNULL(`medication`.`title`,'') != ''");
							if(mysqli_num_rows($medications) > 0) { ?>
								<?php while($medication = mysqli_fetch_assoc($medications)) { ?>
									<li class="col-xs-12"><div class="col-xs-2" style="max-width: 35px;"><img src="../img/calendar.png" title="Medications" class="inline-img"></div><div class="col-xs-10"><b>Medication:</b> <?= $medication['title'] ?><br><b>Dosage:</b> <?= $medication['dosage'] ?></div></li>
								<?php }
							} ?>
						<?php } ?>
					<?php } ?>
				</ul>
			</div>
			<?php if($generate_pdf) { $pdf_contents[] = [get_contact($dbc, $member['item_id']), ob_get_contents()]; } ?>
		<?php } ?>
	<?php $member_i++;
	} ?>
</div>