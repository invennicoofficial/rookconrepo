<?php include_once('../../include.php');
include_once('../../tcpdf/tcpdf.php');

if(!empty($_POST)) {
	$form_image = $_POST['form_img'];
	$form_id = explode('_',$form_image)[1];
	$patient = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM `contacts` WHERE `contactid`='".$_POST['assess_patient']."'"));
	$patient_name = decryptIt($patient['first_name']).' '.decryptIt($patient['last_name']);
	$date = $_POST['assess_date'];
	$notes = $_POST['assess_notes'];
	$file_name = 'downloads/patientform_'.$form_id.'.pdf';
	
	$sql = "UPDATE `patientform_assessment_pdf` SET `patientid`='".$patient['contactid']."', `form_pdf`='$file_name', `form_date`='".date('Y-m-d')."' WHERE `pdfid`='$form_id'";
	$result = mysqli_query($dbc, $sql);

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

	$html = '<table style="border:none; width:100%; border-collapse:separate; border-spacing:10px;">';
	$html .= '<tr><td colspan="2" style="text-align: center;"><big><b>Body Targeted Assessment Form</b></big></td></tr>';
	$html .= '<tr><td style="margin-right: 1em;border:none;vertical-align: top; width:67%">';
	$html .= '<b>Name:</b> <input type="text" value="'.$patient_name.'" name="assess_name" style="border:none;" size="50">';
	$html .= '</td><td style="width:33%;"><b>Date:</b> <input type="text" value="'.$date.'" name="assess_date" style="border:none;" size="20"></td></tr>';
	$html .= '<tr><td colspan="2"><span style="padding:5px;background-color:#000;color:#fff;">Part 1 - Claimant Information</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="height: 400; width:100%; border:1px solid black;">'.$notes.'</td></tr></table>';
	$html .= '</td></tr>';
	$html .= '<tr><td colspan="2" style="text-align: right;"><img src="'.$form_image.'" height="250" width="250"></td></tr>';
	$html .= '</table></form>';

	if(file_exists($file_name)) {
		unlink($file_name);
	}
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output($file_name, 'F');
	echo "<script> window.location.replace('".$file_name."'); </script>";
}
?>
<script>
$(document).ready(function() {
	$("#body_bag").height($("#body_bag").width());
	var canvas = $("#body_bag")[0];
	var ctx = canvas.getContext("2d");
	var img = new Image();
	img.onload = function() {
		ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
	};
	img.src = 'assessments/body_outline.png';
	
	$('#body_bag').click(function() {
		var x = Math.round(event.offsetX / $(this).width() * 300);
		var y = Math.round(event.offsetY / $(this).height() * 150);
		var colour = $('[name=colour_mode]:checked').val();
		var r = parseInt(colour.substring(1,3),16);
		var g = parseInt(colour.substring(3,5),16);
		var b = parseInt(colour.substring(5,7),16);
		var ctx = this.getContext("2d");
		var p = ctx.getImageData(x,y,1,1).data;
		var col = x;
		var colLeft = x;
		var colRight = x;
		var row = y;
		var left = false;
		var right = false;
		var top = false;
		var bottom = false;
		var topRow = true;
		var bottomRow = true;
		setColour(ctx,r,g,b,col,row);
		while(!top) {
			colLeft = x;
			colRight = x;
			left = false;
			right = false;
			topRow = true;
			while(!left) {
				n = ctx.getImageData(col-1,row,1,1).data;
				if(matchColour(p[0],p[1],p[2],n[0],n[1],n[2])) {
					col--;
					setColour(ctx,r,g,b,col,row);
				} else {
					left = true;
					colLeft = col;
				}
				n = ctx.getImageData(col,row-1,1,1).data;
				if(matchColour(p[0],p[1],p[2],n[0],n[1],n[2])) {
					topRow = false;
				}
			}
			col = colRight;
			while(!right) {
				n = ctx.getImageData(col+1,row,1,1).data;
				if(matchColour(p[0],p[1],p[2],n[0],n[1],n[2])) {
					col++;
					setColour(ctx,r,g,b,col,row);
				} else {
					right = true;
					colRight = col;
				}
				n = ctx.getImageData(col,row-1,1,1).data;
				if(matchColour(p[0],p[1],p[2],n[0],n[1],n[2])) {
					topRow = false;
				}
			}
			row--;
			top = topRow;
		}
		row = y+1;
		col = x;
		while(!bottom) {
			colLeft = x;
			colRight = x;
			left = false;
			right = false;
			bottomRow = true;
			while(!left) {
				n = ctx.getImageData(col-1,row,1,1).data;
				if(matchColour(p[0],p[1],p[2],n[0],n[1],n[2])) {
					col--;
					setColour(ctx,r,g,b,col,row);
				} else {
					left = true;
					colLeft = col;
				}
				n = ctx.getImageData(col,row+1,1,1).data;
				if(matchColour(p[0],p[1],p[2],n[0],n[1],n[2])) {
					bottomRow = false;
				}
			}
			col = colRight;
			while(!right) {
				n = ctx.getImageData(col+1,row,1,1).data;
				if(matchColour(p[0],p[1],p[2],n[0],n[1],n[2])) {
					col++;
					setColour(ctx,r,g,b,col,row);
				} else {
					right = true;
					colRight = col;
				}
				n = ctx.getImageData(col,row+1,1,1).data;
				if(matchColour(p[0],p[1],p[2],n[0],n[1],n[2])) {
					bottomRow = false;
				}
			}
			row++;
			bottom = bottomRow;
		}
	});
});

function matchColour(r, g, b, startR, startG, startB) {
	if(r > startR - 45 && r < startR + 45 &&
		g > startG - 45 && g < startG + 45 &&
		b > startB - 45 && b < startB + 45) {
		return true;
	}
	return false;
}

function setColour(ctx,r,g,b,x,y) {
	ctx.fillStyle = 'rgb('+r+','+g+','+b+')';
	ctx.fillRect(x,y,1,1);
}

function saveForm() {
	var canvas  = $("#body_bag")[0];
	var dataURL = canvas.toDataURL();
	var patient_id = $('[name=assess_patient]').val();

	$.ajax({
	  type: "POST",
	  url: "upload.php",
	  data: { 
		 img: dataURL, patient: patient_id, therapist: '<?php echo $_SESSION['contactid']; ?>'
	  }
	}).done(function(result) {
		if(result == 'ERROR') {
			alert("Unable to save assessment.");
		} else {
			$('[name=form_img]').val(result);
			$('form').submit();
		}
	});
}
</script>
<?php //include_once('../../navigation.php'); ?>
<div class="container">
	<form method="POST" action="">
		<div class="form-group">
			<label class="col-sm-4 control-label">Name:</label>
			<div class="col-sm-8">
				<select name="assess_patient" class="form-control chosen-select-deselect"><option></option>
					<?php $patients = mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category`='Patient' AND `status`>0 AND `deleted`=0");
					while($row = mysqli_fetch_array($patients)) {
						echo "<option value='".$row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name'])."</option>";
					} ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Date:</label>
			<div class="col-sm-8">
				<input type="text" name="assess_date" class="form-control datepicker">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Assessment Notes:</label>
			<div class="col-sm-8">
				<textarea name="assess_notes" class="form-control"></textarea>
			</div>
		</div>
		<div class="clearfix"></div><br />
		<div class="pull-left" style="width: calc(100% - 500px - 20em);">&nbsp;</div>
		<div class="pull-left" style="max-width: 100%; width: 20em;">
			<input type="hidden" name="form_img" value="">
			<input type="radio" checked name="colour_mode" value="#FF0000"> Red (Pain)<br />
			<input type="radio" name="colour_mode" value="#FFFF00"> Yellow (Pins / Needles)<br />
			<input type="radio" name="colour_mode" value="#0000FF"> Blue ()<br />
			<input type="radio" name="colour_mode" value="#FFFFFF"> Clear<br />
		</div>
		<canvas id="body_bag" style="height: 500px; max-width:100%; width: 500px;" src="body_outline.jpg" class="pull-right"></canvas>
		<div class="clearfix"></div><br />
	</form>
</div>
<?php include('../../footer.php'); ?>