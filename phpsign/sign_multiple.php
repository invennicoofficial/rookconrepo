<?php // The variable $output_name must be set before including this file ?>
<script>
$(document).ready(function() {
	initPad();
});
function initPad(target) {
	if(target == undefined) {
		target = '';
	} else {
		target = target + ' ';
	}
	$(target+'.sigPad').signaturePad({ drawOnly: true, validateFields: false });
	$(target+'#linear').signaturePad({ drawOnly:true, lineTop:200 });
	$(target+'#smoothed').signaturePad({ drawOnly:true, drawBezierCurves:true, lineTop:200 });
	$(target+'#smoothed-variableStrokeWidth').signaturePad({ drawOnly:true, drawBezierCurves:true, variableStrokeWidth:true, lineTop:200 });
}
</script>
</head>

<div class="sigPad" id="linear" style="max-width: 100%; width:404px;">
	<ul class="sigNav">
		<li class="drawIt"><a href="#draw-it">Draw It</a></li>
        <li><span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Use a different signature than what you would use to sign cheques and other important documents."><img src="../img/info.png" width="20"></a></span></li>
		<li class="clearButton"><a href="#clear">Clear</a></li>
	</ul>
	<div class="sig sigWrapper" style="height:auto;">
		<div class="typed"></div>
		<canvas class="pad" width="400" height="150" style="border:2px solid black; max-width: 100%;"></canvas>
		<input type="hidden" name="<?= $output_name ?>" <?= $sign_output_options ?> class="output">
		<input type="hidden" name="<?= $output_name ?>_initial" <?= $sign_output_options ?> value="<?= $output_value ?>">
	</div>
</div>
<?php $sign_output_options = '';
$output_name = '';
$output_value = ''; ?>