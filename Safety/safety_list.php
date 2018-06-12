<script>
function markFavourite(img) {
	$(img).find('.fave').toggle();
	$.ajax({
		url: 'safety_ajax.php?action=mark_favourite&user=<?= $_SESSION['contactid'] ?>&id='+$(img).data('id'),
		success: function(response) { console.log(response); }
	});
}
function markPinned(img) {
	$(img).closest('.pull-right').find('.pinned').toggle().find('select').off('change',savePinned).change(savePinned);
	$(img).closest('.pull-right').css('width',$(img).find('.pinned').is(':visible') ? '50em' : 'auto');
	$(window).resize();
}
function savePinned() {
	$.ajax({
		url: 'safety_ajax.php?action=mark_pinned',
		method: 'POST',
		data: {
			users: $(this).val(),
			id: $(this).data('id')
		},
		success: function(response) { console.log(response); }
	});
	$(this).closest('.pinned').hide();
	$(this).closest('.pull-right').css('width','auto');
	$(window).resize();
}
function archive(id) {
	$.ajax({
		url: 'safety_ajax.php?action=archive',
		method: 'POST',
		data: {
			id: id
		},
		success: function(response) {
			window.location.reload();
		}
	});
	return false;
}
</script>
<div class="scale-to-fill has-main-screen hide-titles-mob">
	<div class="main-screen form-horizontal">
		<div class="block-group form-list">
			<?php $query = $dbc->query("SELECT * FROM safety WHERE '$tab_cat_name' IN (`category`,'') AND (`assign_sites` IN ('',',,',',',',ALL,') OR CONCAT('%,',`assign_sites`,',%') LIKE ',$site,') AND deleted = 0 AND `heading_number` != '' AND (tab='$tab' OR ('$tab'='Manuals' AND `tab`='Manual') OR tab='$tab_name' OR ('$tab_name'='Manuals' AND `tab`='Manual') OR ('$tab_name'='favourites' AND CONCAT(',',`favourite`,',') LIKE '%,".$_SESSION['contactid'].",%') OR ('$tab_name'='pinned' AND CONCAT(',',`pinned`,',') LIKE '%,".$_SESSION['contactid'].",%')) ORDER BY tab, lpad(heading_number, 100, 0), lpad(sub_heading_number, 100, 0)");
			if(mysqli_num_rows($query) > 0) {
				while($form = mysqli_fetch_assoc($query)) {
					$assigned = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `done` FROM `safety_staff` WHERE `safetyid`='".$form['safetyid']."' AND `staffid`='".$_SESSION['contactid']."' ORDER BY `staffid` DESC"))['done'];
					if($heading != $form['heading_number'].' '.$form['heading'] && $form['sub_heading_number'] != '') {
						$heading = $form['heading_number'].' '.$form['heading'];
						echo "<div class='heading'>$heading</div>";
					}
					if($sub_heading != $form['sub_heading_number'].' '.$form['sub_heading'] && $form['third_heading_number'] != '') {
						$sub_heading = $form['sub_heading_number'].' '.$form['sub_heading'];
						echo "<div class='sub-heading'>$sub_heading</div>";
					}
					$form_name = ($form['third_heading_number'] != '' ? $form['third_heading_number'].' '.$form['third_heading'] : ($form['sub_heading_number'] != '' ? $form['sub_heading_number'].' '.$form['sub_heading'] : $form['heading_number'].' '.$form['heading']));
					echo "<div class='form'>";
					echo '<img class="pull-left inline-img neg-25-margin-vertical" src="../img/'.($assigned == '1' ? 'checkmark.png' : 'error.png').'">';
					echo "<a href='?safetyid=".$form['safetyid']."&action=view'>$form_name</a>";
					if($security['edit'] > 0) {
						echo '<a href="" onclick="return archive(\''.$form['safetyid'].'\');" class="pull-right"><img class="inline-img" src="../img/icons/ROOK-trash-icon.png"></a>';
						echo '<a href="?safetyid='.$form['safetyid'].'&action=edit" class="pad-horizontal pull-right"><img class="inline-img" src="../img/icons/ROOK-edit-icon.png"></a>';
						echo '<span class="pull-right pad-horizontal" style="max-width:100%;"><img class="inline-img small" onclick="markPinned(this);" src="'.($form['pin'] > 0 ? '../img/pinned-filled.png' : '../img/pinned.png').'">';
						echo '<span class="pinned" style="display:none; width:20em; max-width:100%;"><select multiple data-placeholder="Select Users and Levels" data-id="'.$form['safetyid'].'" class="chosen-select-deselect"><option></option>';
						echo '<option '.(strpos(','.$form['pinned'].',', ',ALL,') !== FALSE ? 'selected' : '').' value="ALL">All Users</option>';
						foreach($security_levels as $level_label => $level_name) {
							echo '<option '.(strpos(','.$form['pinned'].',',','.$level_name.',') !== FALSE ? 'selected' : '').' value="'.$level_name.'">'.$level_label.'</option>';
						}
						foreach(sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status`=1")) as $contact) {
							echo '<option '.(strpos(','.$form['pinned'].',', ','.$contact['contactid'].',') !== FALSE ? 'selected' : '').' value="'.$contact['contactid'].'">'.$contact['first_name'].' '.$contact['last_name'].'</option>';
						}
						echo '</select></span></span>';
					} else if(strpos(','.$form['pinned'].',',','.$_SESSION['contactid'].',') !== FALSE) {
						echo '<span class="pull-right pad-horizontal"><img class="inline-img small" src="../img/pinned-filled.png"></span>';
					}
					echo '<span data-type="'.$form['listing_type'].'" data-id="'.$form['safetyid'].'" class="pull-right neg-25-margin-vertical pad-horizontal" onclick="markFavourite(this);"><img class="inline-img fave" src="../img/blank_favourite.png" style="'.(strpos(','.$form['favourite'].',',','.$_SESSION['contactid'].',') !== false ? 'display:none;' : '').'"><img class="inline-img fave" src="../img/full_favourite.png" style="'.(strpos(','.$form['favourite'].',',','.$_SESSION['contactid'].',') !== false ? '' : 'display:none;').'"></span>';
					echo $today > $form['deadline'] ? ($assigned == '0' ? '<span class="text-red pull-right">Past Due</span>' : $assigned == '1' ? '' : '<span class="text-red pull-right">Review Needed</span>') : ($assigned == '' ? '<span class="text-blue pull-right">New</span>' : '');
					echo "<div class='clearfix'></div></div>";
				}
			} else { ?>
				<h3>No <?= $tab == 'pinned' ? 'Pinned' : ($tab == 'favourites' ? 'Favourite' : $tab_cat) ?> Found</h3>
			<?php } ?>
		</div>
	</div>
</div>
<div class="show-on-mob">
	<div class="block-group form-list">
		<?php $query = $dbc->query("SELECT * FROM `safety` WHERE (`assign_sites` IN ('',',,',',',',ALL,') OR CONCAT('%,',`assign_sites`,',%') LIKE ',$site,') AND deleted = 0 AND `heading_number` != '' ORDER BY tab, lpad(heading_number, 100, 0), lpad(sub_heading_number, 100, 0)");
		?>
	</div>
</div>