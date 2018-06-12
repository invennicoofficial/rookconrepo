<?php

// error_reporting(E_ALL);
error_reporting(0);
//set_time_limit(0);

include '../PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once('../PHPExcel/Classes/PHPExcel/IOFactory.php');


if($order_title == 'High Velocity Price List') {
	$file_nom = '2015_High_Velocity_Price_List.xlsx';
	$file_nom2 = 'high_velocity_price_list';
	$file_nom3 = '2015_High_Velocity_Price_List';
	$note_cell = 'D32';
	$day_cell = 'E19';
	$month_cell = 'D19';
	$year_cell = 'F19';
	$name_cell = 'B6';
}
if($order_title == 'Razor Price List') {
	$file_nom = '2015_Razor_Price_List.xlsx';
	$file_nom2 = 'razor_price_list';
	$file_nom3 = '2015_Razor_Price_List';
	$note_cell = 'D33';
	$day_cell = 'E16';
	$month_cell = 'D16';
	$year_cell = 'F16';
	$name_cell = 'B7';
}
if($order_title == 'Revv Price List') {
	$file_nom = '2015_Revv_Price_List.xlsx';
	$file_nom2 = 'revv_list';
	$file_nom3 = '2015_Revv_Price_List';
	$note_cell = 'D34';
	$day_cell = 'E18';
	$month_cell = 'D18';
	$year_cell = 'F18';
	$name_cell = 'B8';
}
if($order_title == 'Radiantz Price List') {
	$file_nom = '2015_Radiantz_Price_List.xlsx';
	$file_nom2 = 'radiants_list';
	$file_nom3 = '2015_Radiantz_Price_List';
	$note_cell = 'D34';
	$day_cell = 'E19';
	$month_cell = 'D19';
	$year_cell = 'F19';
	$name_cell = 'B6';
}

$inputFileName = 'vpl_xls_exporter/'.$file_nom.'';
$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);

$objPHPExcel->setActiveSheetIndex(0);

//GET DATA
$notes = strip_tags($_POST['comment']);
   $var=explode('-',$ship_date);
   $nomb = 0;
   foreach($var as $rowd)
   {
		if($nomb == 0)   {
			$year = $rowd;
		} else if ($nomb == 1) {
			$month = $rowd;
		} else {
			$day = $rowd;
		}
		$nomb++;
    }
	$result = mysqli_query($dbc, "SELECT * FROM contacts WHERE contactid= '".$_POST['whoareyou']."'");
    while($row = mysqli_fetch_assoc($result)) {
		$name = decryptIt($row['first_name']).' '.decryptIt($row['last_name']);
    }
//GET DATA
for($i=0; $i<count($_POST['inventoryid_list']); $i++) {
			$quantity = $_POST['quantity_list'][$i];
			$inventoryid = $_POST['inventoryid_list'][$i];
			$get_driver = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM vendor_price_list WHERE inventoryid='$inventoryid'"));
			$namo = $get_driver['name'];
			include 'vpl_xls_exporter/'.$file_nom2.'.php';
		}


// Add additional info
$objPHPExcel->getActiveSheet()
			->setCellValue($note_cell, $notes)
			->setCellValue($year_cell, $year)
			->setCellValue($day_cell, $day)
			->setCellValue($month_cell, $month)
			->setCellValue($name_cell, $name);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$date123 = date('m-d-Y_h-i-s-a', time());
$objWriter->save('download/'.$file_nom3.''.$date123.'.xlsx');
echo '<script>window.open("download/'.$file_nom3.''.$date123.'.xlsx", "_blank"); </script>';
$file_name_save_db = $file_nom3.''.$date123.'.xlsx';

?>