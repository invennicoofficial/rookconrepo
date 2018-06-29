<?php if(!IFRAME_PAGE) { ?>
	<ul class='sidebar hide-titles-mob collapsible <?= $tile == 'hr' ? '' : 'collapsed' ?>' style='padding-left: 15px;'>
		<?php if(count($hr_summary) > 0) { ?>
			<a href="?tile_name=<?= $tile ?>&tab=summary"><li class="<?= 'summary' == $tab ? 'active blue' : '' ?>">Summary</li></a>
		<?php } ?>
		<?php foreach($categories as $cat_id => $label) {
			if($tab == $cat_id) {
				$tab_cat = $label;
			}
			if(($tile == 'hr' || $tile == $cat_id) && check_subtab_persmission($dbc, 'hr', ROLE, $label)) { ?>
				<a href="?tile_name=<?= $tile ?>&tab=<?= $cat_id ?>"><li class="<?= $cat_id == $tab ? 'active blue' : '' ?>"><?= $label ?></li></a>
			<?php }
		} ?>
		<?php $pr_fields = ','.get_config($dbc, 'performance_review_fields').',';
		if(strpos($pr_fields, ',Enable Performance Reviews,') !== FALSE) {
			if(tile_visible($dbc, 'preformance_review')) { ?>
				<a href="?performance_review=list"><li class="<?= $_GET['performance_review'] == 'list' ? 'active blue' : '' ?>">Performance Reviews</li></a>
				<?php if(isset($_GET['performance_review'])) { ?>
					<ul>
						<?php $pr_tab = $_GET['pr_tab'];
						$pr_positions = explode(',', get_config($dbc, 'performance_review_positions'));
						if(!empty(get_config($dbc, 'performance_review_positions'))) {
							foreach ($pr_positions as $pr_position) {
								if(check_subtab_persmission($dbc, 'preformance_review', ROLE, $pr_position)) { ?>
									<a href="?performance_review=list&pr_tab=<?= $pr_position ?>"><li class="<?= $pr_tab == $pr_position ? 'active blue' : '' ?>"><?= $pr_position ?></li></a>
								<?php }
							}
						} ?>
					</ul>
				<?php } ?>
			<?php }
		} ?>
	</ul>
<?php } ?>