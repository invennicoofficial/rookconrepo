<?php
if(!empty($_SESSION['user_preferences']['font_type']) || !empty($_SESSION['user_preferences']['font_size'])) { ?>
	<style>
	body, div, :not(h1) > span, object, iframe,
	p, blockquote, pre,
	abbr, address, cite, code,
	del, dfn, em, img, ins, kbd, q, samp,
	small, strong, sub, sup, var,
	b, i,
	dl, dt, dd, ol, ul, li,
	fieldset, form, label, legend,
	table, caption, tbody, tfoot, thead, tr, th, td,
	article, aside, canvas, details, figcaption, figure, 
	footer, header, hgroup, menu, nav, section, summary,
	time, mark, audio, video, input, .form-control, .btn {
		<?php if (!empty($user_settings['font_type'])) { ?>
			font-family: <?= htmlspecialchars_decode($user_settings['font_type']) ?>;
		<?php } ?>
		<?php if (!empty($user_settings['font_size'])) { ?>
			font-size: <?= $user_settings['font_size'] ?>;
		<?php } ?>
	}
	</style>
<?php } ?>