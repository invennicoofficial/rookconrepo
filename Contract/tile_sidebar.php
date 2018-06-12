<div class="tile-sidebar sidebar sidebar-override hide-titles-mob standard-collapsible">
	<ul>
		<?php foreach ($contract_tabs as $contract_tab) { ?>
			<a href="?tab=<?= $contract_tab ?>"><li class="<?= $_GET['tab'] == $contract_tab ? 'active blue' : '' ?>"><?= $contract_tab ?></li></a>
		<?php } ?>
	</ul>
</div>