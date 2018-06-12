<?php $page_numbers = $pdf_settings['page_numbers']; ?>
<h3>Page Numbers</h3>
<div class="dashboard-item">
	<div class="form-group">
		<label class="col-sm-3 control-label">Choose Page Number Layout:</label>
		<div class="col-sm-6">
			<input type="hidden" name="page_numbers" value="<?= $page_numbers ?>">
			<a class="<?= $page_numbers == 'top_cover' ? 'active' : '' ?> col-sm-3 block-item text-center" href="" onclick="$('[name=page_numbers]').val('top_cover'); $('.active').removeClass('active'); $(this).addClass('active'); return false;"><img src="../img/top_cover.png" class="inline-img text-lg"></a>
			<a class="<?= $page_numbers == 'top_main' ? 'active' : '' ?> col-sm-3 block-item text-center" href="" onclick="$('[name=page_numbers]').val('top_main'); $('.active').removeClass('active'); $(this).addClass('active'); return false;"><img src="../img/top_main.png" class="inline-img text-lg"></a>
			<a class="<?= $page_numbers == 'bottom_cover' ? 'active' : '' ?> col-sm-3 block-item text-center" href="" onclick="$('[name=page_numbers]').val('bottom_cover'); $('.active').removeClass('active'); $(this).addClass('active'); return false;"><img src="../img/bottom_cover.png" class="inline-img text-lg"></a>
			<a class="<?= $page_numbers == 'bottom_main' ? 'active' : '' ?> col-sm-3 block-item text-center" href="" onclick="$('[name=page_numbers]').val('bottom_main'); $('.active').removeClass('active'); $(this).addClass('active'); return false;"><img src="../img/bottom_main.png" class="inline-img text-lg"></a>
		</div>
	</div>
</div>