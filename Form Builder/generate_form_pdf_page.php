<?php
if($advanced_styling != 1 && $page_by_page == 1) {
	foreach($page_settings as $page_setting) {
		if($page_setting['page'] > 1) {
			$pdf->AddPage();
		}
		$page_details_sql = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `user_form_page_detail` WHERE `page_id` = '".$page_setting['page_id']."' AND `deleted` = 0 AND `white_space` = 1"),MYSQLI_ASSOC);
		foreach ($page_details_sql as $field) {
			$pdf->SetFillColor(255,255,255);
			$fillcolor = true;
			$value = '';
		    $top = $pdf->pixelsToUnits($field['top']);
		    $left = $pdf->pixelsToUnits($field['left']);
		    $width = $pdf->pixelsToUnits($field['width']);
		    $height = $pdf->pixelsToUnits($field['height']);

			// Start clipping.
			$pdf->StartTransform();
			// Draw clipping rectangle to match html cell.
			$pdf->Rect($left, $top, $width, $height, 'CNZ');
			// Output html.
		    $pdf->writeHTMLCell($width, $height, $left, $top, $value, 0, 0, $fillcolor);
			// Stop clipping.
			$pdf->StopTransform();
		}
		$page_details_sql = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `user_form_page_detail` WHERE `page_id` = '".$page_setting['page_id']."' AND `deleted` = 0 AND `white_space` = 0"),MYSQLI_ASSOC);
		foreach ($page_details_sql as $field) {
			$fillcolor = false;
			$value = $html_css.$page_details[$field['field_name']];
		    $top = $pdf->pixelsToUnits($field['top']);
		    $left = $pdf->pixelsToUnits($field['left']);
		    $width = $pdf->pixelsToUnits($field['width']);
		    $height = $pdf->pixelsToUnits($field['height']);

			// Start clipping.
			$pdf->StartTransform();
			// Draw clipping rectangle to match html cell.
			$pdf->Rect($left, $top, $width, $height, 'CNZ');
			// Output html.
		    $pdf->writeHTMLCell($width, $height, $left, $top, $value, 0, 0, $fillcolor);
			// Stop clipping.
			$pdf->StopTransform();
		}
	}
}
?>