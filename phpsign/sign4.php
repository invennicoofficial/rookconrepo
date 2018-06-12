<?php
/*
Dashboard
*/
//include ('header.php');
?>
<script>
$(document).ready(function() {
    //$('.sigPad').signaturePad();

    var options = {
      drawOnly : true,
      validateFields : false
    };
    $('.sigPad').signaturePad(options);
});

$(document).ready(function() {
  $('#linear2').signaturePad({drawOnly:true, lineTop:200});
  $('#smoothed').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:200});
  $('#smoothed-variableStrokeWidth').signaturePad({drawOnly:true, drawBezierCurves:true, variableStrokeWidth:true, lineTop:200});
});

</script>
</head>


<div class="sigPad" id="linear2" style="max-width: 100%; width:404px;">
<ul class="sigNav">
<li class="drawIt"><a href="#draw-it" >Draw It</a></li>
<li><span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Use a different signature than what you would use to sign cheques and other important documents."><img src="../img/info.png" width="20"></a></span></li>
<li class="clearButton"><a href="#clear">Clear</a></li>
</ul>
<div class="sig sigWrapper" style="height:auto;">
<div class="typed"></div>
<canvas class="pad" width="400" height="150" style="border:2px solid black; max-width: 100%;"></canvas>
<input type="hidden" name="sign4" class="output">
</div>
</div>