<div class='tile-sidebar hide-titles-mob collapsible'>
	<ul>
		<?php $mode = get_config($dbc, 'log_note_tabs'); ?>
		<?php foreach(array_unique(explode(',',get_config($dbc, 'log_note_categories'))) as $cat) {
			if($mode == 'dropdown') { ?>
				<a href="?tab=<?= $cat ?>"><li class="<?= $cat == $_GET['tab'] ? 'active blue' : '' ?>"><?= $cat ?></li></a>
			<?php } else {
				$cat_id = config_safe_str($cat); ?>
				<li><a class="<?= $_GET['tab'] == $cat_id ? '' : 'collapsed' ?> cursor-hand" data-toggle="collapse" data-target="#collapse_<?= $cat_id ?>"><?= $cat ?><span class="pad-horizontal arrow"></span></a>
					<ul id="collapse_<?= $cat_id ?>" class="collapse <?= $_GET['tab'] == $cat_id ? 'in' : '' ?>"><?php
						foreach(sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `name`, `last_name`, `first_name` FROM `contacts` WHERE `deleted`=0 AND `status`>0 AND `category`='$cat'")) as $contact) { ?>
							<a href="?tab=<?= $cat_id ?>&display_contact=<?= $contact['contactid'] ?>"><li class="<?= $contact['contactid'] == $_GET['display_contact'] ? 'active blue' : '' ?>"><?= $contact['name'].($contact['name'] != '' && $contact['first_name'].$contact['last_name'] != '' ? ': ' : '').$contact['first_name'].' '.$contact['last_name'] ?></li></a>
						<?php } ?>
					</ul>
				</li>
			<?php }
		} ?>
	</ul>
</div>