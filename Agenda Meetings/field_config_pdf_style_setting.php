<?php $style_layout = $pdf_settings['style']; ?>
<h3>Design Style</h3>
<div class="dashboard-item">
	<div class="form-group">
		<label class="col-sm-4">Choose Design Style:</label>
		<div class="col-sm-4">
			<input type="hidden" name="style_layout" value="<?= $style_layout ?>">
			<a class="<?= $style_layout == 'a' ? 'active' : '' ?> col-sm-4 block-item text-center" href="" onclick="$('.design_b,.design_c').hide(); $('.design_a').show(); $('[name=style_layout]').val('a'); $('.active').removeClass('active'); $(this).addClass('active'); return false;">A</a>
			<a class="<?= $style_layout == 'b' ? 'active' : '' ?> col-sm-4 block-item text-center" href="" onclick="$('.design_a,.design_c').hide(); $('.design_b').show(); $('[name=style_layout]').val('b'); $('.active').removeClass('active'); $(this).addClass('active'); return false;">B</a>
			<a class="<?= $style_layout == 'c' ? 'active' : '' ?> col-sm-4 block-item text-center" href="" onclick="$('.design_a,.design_b').hide(); $('.design_c').show(); $('[name=style_layout]').val('c'); $('.active').removeClass('active'); $(this).addClass('active'); return false;">C</a>
		</div>
	</div>
</div>