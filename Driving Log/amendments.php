<?php
/*
Vendor Hydreara
*/
include ('../include.php');
checkAuthorised('driving_log');
/**
 * File Name : amendments.php
 *
 * Short description for file : Allows users to approve their timers, make amendments to their timers before ending their logs. Also allows users to officially end their driving log, as well as allowing them to create a PDF and see their driving log graph. This is also the page used for inspection mode to see their graph/data.
 * Long description for file (if any)...
 *
 * @author Fresh Focus Media
*/

include_once('../tcpdf/tcpdf.php');
require_once('../phpsign/signature-to-image.php');

error_reporting(0);

$view_only_mode = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `driving_log_view_only_mode` WHERE `contactid` = '".$_SESSION['contactid']."'"))['view_only_mode'];
include_once('view_only_mode.php');

if(isset($_GET['admin_view'])) {
	if(strpos(','.ROLE.',',',super,') !== false || strpos(','.ROLE.',',',admin,') !== false) {
	} else {
		header('Location: driving_log_tiles.php');
		die();
	}
}

if (isset($_POST['submit'])) {

		// ADD Column in Table for PDF //

			$col = "SELECT `pdf` FROM driving_log";
			$result = mysqli_query($dbc, $col);

		if (!$result){

			$colcreate = "ALTER TABLE `driving_log` ADD COLUMN `pdf` VARCHAR(555) NULL";
			$result = mysqli_query($dbc, $colcreate);
			//echo 'PDF col has been added to the database';
		} else {
			//echo 'PDF col already exists';
		}

		$image_url = $_POST['dataurl_image'];
		$drivinglogid = $_POST['drivelogid'];
		$image = @file_get_contents($image_url);
		 $filename = 'drivinglog_graph_'.$drivinglogid.'.jpg';
			file_put_contents('tmp/'.$filename, $image);
		//Autograph

	$get_sign = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM driving_log WHERE drivinglogid='$drivinglogid'"));
		$signorno = $get_driver['sign'];


    $drivinglogid = $_POST['drivinglogid'];
	if($signorno !== 1) {
    $sign = $_POST['output'];
    if($sign != '') {
        $img = sigJsonToImage($sign);
        imagepng($img, 'download/dl_'.$drivinglogid.'.png');
    } else {
		echo '<script>
			window.history.back();
			alert("Please write your signature in the box below.");
				</script>';
		exit();
	}
	}

    $query_update = "UPDATE `driving_log` SET sign=1 WHERE drivinglogid='$drivinglogid'";
    $result_update = mysqli_query($dbc, $query_update);
		//End Autograph

		$autograph = '<img width="200px" src="download/dl_'.$drivinglogid.'.png">';
		$img = '<img src="tmp/'.$filename.'">';
		include('get_timers.php');


			$driving_log = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM driving_log WHERE drivinglogid = '$drivinglogid'"));
			$main_address = $driving_log['main_office_address'];
			$cycle = $driving_log['cycle'];
			if($cycle == 'Cycle 1(7 days)') {
				$cycle = 'Cycle 1 (7 day / 70 hour)';
			} else {
				$cycle = 'Cycle 2 (14 day / 120 hour)';
			}
			if($main_address == '' || $main_address == NULL) {
					$main_address = 'Not available';
			}

			$home_terminal_address = $driving_log['home_terminal_address'];

			if($home_terminal_address == '' || $home_terminal_address == NULL) {
					$home_terminal_address = 'Not available';
			}

			$get_checklists = mysqli_fetch_all(mysqli_query($dbc,"SELECT * FROM driving_log_safety_inspect WHERE drivinglogid='$drivinglogid'"),MYSQLI_ASSOC);

				if($driving_log['end_date'] != '') {
					$total_km = 0;
					foreach ($get_checklists as $checklist) {
						$total_km += $checklist['final_odo_kms'] - $checklist['begin_odo_kms'];
					}
                    $total_km .= ' KM';
                } else {
                    $total_km = 'Not available';
                }

			$driver_name = get_staff($dbc, $driving_log['driverid']);
			$co_driver = get_staff($dbc, $driving_log['codriverid']);
			if($co_driver == NULL || $co_driver == '') {
				$co_driver = 'No co-driver was selected.';
			}

			$vehicle = [];
			$trailer = [];
			if (count($get_checklists) > 1) {
				foreach ($get_checklists as $checklist) {
					if (!empty($checklist['safety_inspect_vehicleid'])) {
						$vehicle[] = '#'.get_equipment_field($dbc, $checklist['safety_inspect_vehicleid'], 'unit_number');
					}
					if (!empty($checklist['safety_inspect_trailerid'])) {
						$trailer[] = '#'.get_equipment_field($dbc, $checklist['safety_inspect_trailerid'], 'unit_number');
					}
				}
				$vehicle = implode(', ', $vehicle);
				$trailer = implode(', ', $trailer);
			} else {
				$vehicle = '#'.get_equipment_field($dbc, $driving_log['vehicleid'], 'unit_number');
				$trailer = '#'.get_equipment_field($dbc, $driving_log['trailerid'], 'unit_number');
			}

			if(empty($vehicle) || $vehicle == '#') {
				$vehicle_no = "No vehicle was selected.";
			} else {
				$vehicle_no = $vehicle;
			}
			if(empty($trailer) || $trailer == '#') {
				$trailer_no = "No trailer was selected.";
			} else {
				$trailer_no = $trailer;
			}

			// START LENGTH DUTY STATUS... TABLE

	       $query_check_credentials = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY level";

			$result = mysqli_query($dbc, $query_check_credentials);

			$num_rows = mysqli_num_rows($result);
			if($num_rows > 0) {
				$table = "<br><br>
				<style>tr:nth-child(even) {
						background-color: lightgrey;
					}
				</style>
				<table border=\"1\" style=\"border:1px black solid;\" cellpadding=\"3\" class=\"table\">";
				$table .= "<tr style=\"background-color:lightgrey;\">
				<th>Duty Status</th>
                <th>Comment</th>
				<th>Length</th>
                ";
				$table .= "</tr>";
			} else {
				$table .= "<h2>No Record Found.</h2>";
			}

			while($row = mysqli_fetch_array( $result ))
			{

                $timerid = $row['timerid'];
                $timer_name = '';
                $timer = '';
                if($row['off_duty_timer'] != '') {
                    $timer_name = "Off duty";
                    $timer = $row['off_duty_timer'];
                }
                if($row['sleeper_berth_timer'] != '') {
                    $timer_name = "Sleeper Berth";
                    $timer = $row['sleeper_berth_timer'];
                }
                if($row['driving_timer'] != '') {
                    $timer_name = "Driving";
                    $timer = $row['driving_timer'];
                }
                if($row['on_duty_timer'] != '') {
                    $timer_name = "On duty; not driving";
                    $timer = $row['on_duty_timer'];
                }
				$table .= "<tr>";
                $table .= "<td>" . $timer_name . "</td>";
				$table .= "<td>" . $row['dl_comment']."<br>".$row['amendments_comment'] . "</td>";
                $table .= "<td>" . $timer . "</td>";

                $table .= "</tr>";
            }
if($num_rows > 0) {
			$table .= "</table>";
}

			// END!!
			$image_file = ''.$_POST['image_filer'].'';
			DEFINE('POS_LOGO', $image_file);
		// TCPDF CREATION //
	class MYPDF extends TCPDF {

		public function Header() {
			$image_file = POS_LOGO;
			$this->SetFont('helvetica', '', 13);
            $this->Image($image_file, 0, 10, 35, '', 'PNG', '', 'T', false, 300, 'L', false, false, 0, false, false, false);
            //$footer_text = 'Daysheet <b>'.START_DATE.'</b>';
            //$this->writeHTMLCell(0, 0, 0 , 35, $footer_text, 0, 0, false, "R", true);
		}

		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			$this->SetFont('helvetica', '', 9);
			$footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
			$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
		}
	}

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);
	?>

	<?php
	$projectid = $drivinglogid.'_'.$driving_log['start_date'];
    $html = '<h1 style="text-align:center;">Driver\'s Daily Log</h1>
	<h2 style="text-align:center;">'.$cycle.'</h2>
	<table cellpadding="2" >
	<tr><td colspan="2" style="width:100%;"><strong>Date:</strong> '. $driving_log['start_date'] .'</td></tr>
	<tr><td colspan="2" style="width:100%;"><strong>Total Mileage Today:</strong> '.$total_km.'</td></tr>
	<tr><td colspan="1" style="width:50%;"><strong>Driver&#39;s Name:</strong> '.$driver_name.'</td>
	<td colspan="1" style="width:50%;"><strong>Co-Driver:</strong> '.$co_driver.'</td></tr>
	<tr><td colspan="1" style="width:50%;"><strong>Main Office Address:</strong> '.$main_address.'</td>
	<td colspan="1" style="width:50%;"><strong>Home Terminal Address:</strong> '.$home_terminal_address.'</td></tr>
	<tr><td colspan="1" style="width:50%;"><strong>Vehicle Number(s):</strong> '.$vehicle_no.'</td>
	<td colspan="1" style="width:50%;"><strong>Trailer Number(s):</strong> '.$trailer_no.'</td></tr>
	<tr><td colspan="2" style="width:100%;"><strong>Signature:</strong></td></tr></table>
	<br><br>
	<table><tr><td style="border-bottom:1px solid black; width: 40%;">'.$autograph.'</td></tr></table>
<br><br><h2>Recap</h2>';
	$html .='<table><tr><td style="width:90%">'.$img.'</td><td style="width:10%">'.$total_time_tabler.'</td></tr></table><br><br>';
	$html .= '<br><hr><br>'.$table.'
	<br><Br>';

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('download/drivinglogpdf_'.$projectid.'.pdf', 'F');


	$query_update = "UPDATE `driving_log` SET pdf='drivinglogpdf_".$projectid.".pdf' WHERE drivinglogid='$drivinglogid'";
    $result_update = mysqli_query($dbc, $query_update);
    ?>

	<script type="text/javascript" language="Javascript">

	window.open('download/drivinglogpdf_<?php echo $projectid;?>.pdf', 'fullscreen=yes');
	</script>
    <?php



    echo '<script type="text/javascript"> window.location.replace("driving_log.php"); </script>';

	}

function addTime($time1, $time2) {
    $wt=explode(':',$time1);

    $t1=$wt[0]*3600+$wt[1]*60;

    $a=explode(':',$time2);
    $t2=$a[0]*3600+$a[1]*60;

    $t3=$t1+$t2;

    $h=floor($t3/3600);
    $m=floor(($t3%3600)/60);
    $s=$t3-$h*3600-$m*60;

    if($h < 10 || $h=='') {
        $h = '0'.$h;
    }
    if($m < 10 || $h=='') {
        $m = '0'.$m;
    }
    return $final_time =  $h.':'.$m;
}
if(!empty($_GET['status'])) {
    $amendments_status = $_GET['status'];
    $timerid = $_GET['timerid'];
    $drivinglogid = $_GET['drivinglogid'];

    $query_update_log = "UPDATE `driving_log_timer` SET `amendments_status` = '$amendments_status'  WHERE `timerid` = '$timerid'";
    $result_update_log = mysqli_query($dbc, $query_update_log);

	$result_timer = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM driving_log_timer WHERE timerid='$timerid'"));
    //$am = $result_timer['amendments'];
    //$amendments = substr($am, 0, -3);

    if($result_timer['off_duty_time'] != '') {
        $start_time = $result_timer['off_duty_time'];
        $end_time = $result_timer['end_off_duty_time'];

        $time1 = new DateTime($start_time);
        $time2 = new DateTime($end_time);
        $interval = $time1->diff($time2);
        $amendments = $interval->format('%h:%i');

        $query_update_timer = "UPDATE `driving_log_timer` SET `final_off_duty_timer` = '$amendments'  WHERE `timerid` = '$timerid'";
        $result_update_timer = mysqli_query($dbc, $query_update_timer);
        $start_log = date("G.i", strtotime($result_timer['off_duty_time']));
    }
    if($result_timer['sleeper_berth_time'] != '') {
        $start_time = $result_timer['sleeper_berth_time'];
        $end_time = $result_timer['end_sleeper_berth_time'];

        $time1 = new DateTime($start_time);
        $time2 = new DateTime($end_time);
        $interval = $time1->diff($time2);
        $amendments = $interval->format('%h:%i');

        $query_update_timer = "UPDATE `driving_log_timer` SET `final_sleeper_berth_timer` = '$amendments'  WHERE `timerid` = '$timerid'";
        $result_update_timer = mysqli_query($dbc, $query_update_timer);
        $start_log = date("G.i", strtotime($result_timer['sleeper_berth_time']));
    }
    if($result_timer['driving_time'] != '') {
        $start_time = $result_timer['driving_time'];
        $end_time = $result_timer['end_driving_time'];

        $time1 = new DateTime($start_time);
        $time2 = new DateTime($end_time);
        $interval = $time1->diff($time2);
        $amendments = $interval->format('%h:%i');

        $query_update_timer = "UPDATE `driving_log_timer` SET `final_driving_timer` = '$amendments'  WHERE `timerid` = '$timerid'";
        $result_update_timer = mysqli_query($dbc, $query_update_timer);
        $start_log = date("G.i", strtotime($result_timer['driving_time']));
    }
    if($result_timer['on_duty_time'] != '') {
        $start_time = $result_timer['on_duty_time'];
        $end_time = $result_timer['end_on_duty_time'];

        $time1 = new DateTime($start_time);
        $time2 = new DateTime($end_time);
        $interval = $time1->diff($time2);
        $amendments = $interval->format('%h:%i');

        $query_update_timer = "UPDATE `driving_log_timer` SET `final_on_duty_timer` = '$amendments'  WHERE `timerid` = '$timerid'";
        $result_update_timer = mysqli_query($dbc, $query_update_timer);
        $start_log = date("G.i", strtotime($result_timer['on_duty_time']));
    }

    $level = get_dltimer($dbc, $timerid, 'level');
    if($level == 1) {
        $query_update_log = "UPDATE `driving_log` SET `start_log` = '$start_log'  WHERE `drivinglogid` = '$drivinglogid'";
        $result_update_log = mysqli_query($dbc, $query_update_log);
    }
	if(isset($_GET['admin_view'])) {
		$admin_view = '&admin_view=true';
	} else {
		$admin_view = '';
	}
	header('Location: amendments.php?graph=off&drivinglogid='.$drivinglogid.$admin_view);
}


if (isset($_POST['end_log'])) {
	$drivinglogid = $_POST['end_log'];
	$checkifcompleted = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT complete FROM driving_log WHERE drivinglogid='$drivinglogid'"));
	if(($checkifcompleted['complete'] == NULL || $checkifcompleted['complete'] == '' ) && $checkifcompleted['complete'] !== '1') {
		mysqli_query($dbc,"UPDATE `driving_log` SET complete='1' WHERE drivinglogid = '$drivinglogid'");
		$dlogid_store = $drivinglogid;
		$set_off_duty_audit = 'true';
		include('notices.php');
		//Create graph
		$drivinglogid = $dlogid_store;
		$result_graph = mysqli_query($dbc, "SELECT * FROM driving_log_timer WHERE drivinglogid='$drivinglogid' ORDER BY level");
		$end_time = 0;
		$graph_value = '';
		$start_time = '00:00';
		$query_audit_off_duty_cancel = "UPDATE `driving_log` SET audit_off_duty=NULL WHERE drivinglogid = '$drivinglogid'";
		$audit_off_duty_result = mysqli_query($dbc, $query_audit_off_duty_cancel);

		$query_insert_graph = 'DELETE FROM driving_log_timer WHERE inspection_mode=1 AND drivinglogid = "'.$drivinglogid.'"';
		$result_insert_graph = mysqli_query($dbc, $query_insert_graph);

		$result_dl = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT start_log FROM driving_log WHERE drivinglogid='$drivinglogid'"));
		$start_log = $result_dl['start_log'];
		if($start_log != '00.00') {
		   // $graph_value .= "40,, [0.00, ".$start_log."],, 'Off-Duty',,";
			$start_time = str_replace(".", ":", $start_log);
		}
		while($row = mysqli_fetch_array($result_graph)) {
			// Final
			if($row['off_duty_timer'] != '') {

				//$end_time = addTime($start_time, date("G:i", strtotime($row['final_off_duty_timer']));
				$fet = str_replace(":", ".",  date("G:i", strtotime($row['end_off_duty_time'])));
				$fst = str_replace(":", ".", date("G:i", strtotime($row['off_duty_time'])));

				//Turn minutes into a fraction
				$reverse_explode = array_reverse(explode('.',$fet));
				$i = 0;
				$len = count($reverse_explode);

				foreach( $reverse_explode as $time ) {
					if ($i == 0) {
						$minutes = $time;
					} else if ($i == $len - 1) {
						$hours = $time;
					}
					// …
					$i++;
				}
				$minutes = round(($minutes/60)*100);
				if($minutes > 100) {
					$minutes = 99;
				}
				if(strlen($minutes) < 2) {
						$minutes = '0'.$minutes;
				}
				/*This is the converted variable: */$fet = $hours.'.'.$minutes;
			//Finish minute to fraction conversion
			//Turn minutes into a fraction
				$reverse_explode = array_reverse(explode('.',$fst));
				$i = 0;
				$len = count($reverse_explode);
				foreach( $reverse_explode as $time ) {
					if ($i == 0) {
						$minutes = $time;
					} else if ($i == $len - 1) {
						$hours = $time;
					}
					// …
					$i++;
				}
				$minutes = round(($minutes/60)*100);
				if($minutes > 100) {
					$minutes = 99;
				}
				if(strlen($minutes) < 2) {
						$minutes = '0'.$minutes;
				}
				/*This is the converted variable: */$fst = $hours.'.'.$minutes;
			//Finish minute to fraction conversion
				$final_end_time = ltrim($fet, '0');
				$final_start_time = ltrim($fst, '0');

				$graph_value .= "40,, [".$final_start_time.", ".$final_end_time."],, 'Off-Duty',,";
				$start_time = $end_time;
			}

			if($row['sleeper_berth_timer'] != '') {
				$end_time = addTime($start_time, $row['final_sleeper_berth_timer']);
				$fet = str_replace(":", ".", date("G:i", strtotime($row['end_sleeper_berth_time'])));
				$fst = str_replace(":", ".", date("G:i", strtotime($row['sleeper_berth_time'])));
				//Turn minutes into a fraction
				$reverse_explode = array_reverse(explode('.',$fet));
				$i = 0;
				$len = count($reverse_explode);

				foreach( $reverse_explode as $time ) {
					if ($i == 0) {
						$minutes = $time;
					} else if ($i == $len - 1) {
						$hours = $time;
					}
					// …
					$i++;
				}
				$minutes = round(($minutes/60)*100);
				if($minutes > 100) {
					$minutes = 99;
				}
				if(strlen($minutes) < 2) {
						$minutes = '0'.$minutes;
				}
				/*This is the converted variable: */$fet = $hours.'.'.$minutes;
			//Finish minute to fraction conversion
			//Turn minutes into a fraction
				$reverse_explode = array_reverse(explode('.',$fst));
				$i = 0;
				$len = count($reverse_explode);

				foreach( $reverse_explode as $time ) {
					if ($i == 0) {
						$minutes = $time;
					} else if ($i == $len - 1) {
						$hours = $time;
					}
					// …
					$i++;
				}
				$minutes = round(($minutes/60)*100);
				if($minutes > 100) {
					$minutes = 99;
				}
				if(strlen($minutes) < 2) {
						$minutes = '0'.$minutes;
				}
				/*This is the converted variable: */$fst = $hours.'.'.$minutes;
			//Finish minute to fraction conversion
				$final_end_time = ltrim($fet, '0');
				$final_start_time = ltrim($fst, '0');
				//$graph_value .= '{x: 20, y:['.$final_start_time.', '.$final_end_time.'], label: "Sleeper Berth"},';
				$graph_value .= "30,, [".$final_start_time.", ".$final_end_time."],, 'Sleeper Berth',,";

				$start_time = $end_time;
			}

			if($row['driving_timer'] != '') {
				$end_time = addTime($start_time, $row['final_driving_timer']);
				$fet = str_replace(":", ".", date("G:i", strtotime($row['end_driving_time'])));
				$fst = str_replace(":", ".", date("G:i", strtotime($row['driving_time'])));
				//Turn minutes into a fraction
				$reverse_explode = array_reverse(explode('.',$fet));
				$i = 0;
				$len = count($reverse_explode);

				foreach( $reverse_explode as $time ) {
					if ($i == 0) {
						$minutes = $time;
					} else if ($i == $len - 1) {
						$hours = $time;
					}
					// …
					$i++;
				}
				$minutes = round(($minutes/60)*100);
				if($minutes > 100) {
					$minutes = 99;
				}
				if(strlen($minutes) < 2) {
						$minutes = '0'.$minutes;
				}
				/*This is the converted variable: */$fet = $hours.'.'.$minutes;
			//Finish minute to fraction conversion
			//Turn minutes into a fraction
				$reverse_explode = array_reverse(explode('.',$fst));
				$i = 0;
				$len = count($reverse_explode);

				foreach( $reverse_explode as $time ) {
					if ($i == 0) {
						$minutes = $time;
					} else if ($i == $len - 1) {
						$hours = $time;
					}
					// …
					$i++;
				}
				$minutes = round(($minutes/60)*100);
				if($minutes > 100) {
					$minutes = 99;
				}
				if(strlen($minutes) < 2) {
						$minutes = '0'.$minutes;
				}
				/*This is the converted variable: */$fst = $hours.'.'.$minutes;
			//Finish minute to fraction conversion
				$final_end_time = ltrim($fet, '0');
				$final_start_time = ltrim($fst, '0');
				//$graph_value .= '{x: 30, y:['.$final_start_time.', '.$final_end_time.'], label: "Driving"},';
				$graph_value .= "20,, [".$final_start_time.", ".$final_end_time."],, 'Driving',,";

				$start_time = $end_time;
			}

			if($row['on_duty_timer'] != '') {
				$end_time = addTime($start_time, $row['final_on_duty_timer']);

				$fet = str_replace(":", ".", date("G:i", strtotime($row['end_on_duty_time'])));
				$fst = str_replace(":", ".", date("G:i", strtotime($row['on_duty_time'])));
				//Turn minutes into a fraction
				$reverse_explode = array_reverse(explode('.',$fet));
				$i = 0;
				$len = count($reverse_explode);

				foreach( $reverse_explode as $time ) {
					if ($i == 0) {
						$minutes = $time;
					} else if ($i == $len - 1) {
						$hours = $time;
					}
					// …
					$i++;
				}
				$minutes = round(($minutes/60)*100);
				if($minutes > 100) {
					$minutes = 99;
				}
				if(strlen($minutes) < 2) {
						$minutes = '0'.$minutes;
				}
				/*This is the converted variable: */$fet = $hours.'.'.$minutes;
			//Finish minute to fraction conversion
			//Turn minutes into a fraction
				$reverse_explode = array_reverse(explode('.',$fst));
				$i = 0;
				$len = count($reverse_explode);

				foreach( $reverse_explode as $time ) {
					if ($i == 0) {
						$minutes = $time;
					} else if ($i == $len - 1) {
						$hours = $time;
					}
					// …
					$i++;
				}
				$minutes = round(($minutes/60)*100);
				if($minutes > 100) {
					$minutes = 99;
				}
				if(strlen($minutes) < 2) {
						$minutes = '0'.$minutes;
				}
				/*This is the converted variable: */$fst = $hours.'.'.$minutes;
			//Finish minute to fraction conversion
				$final_end_time = ltrim($fet, '0');
				$final_start_time = ltrim($fst, '0');
				//$graph_value .= '{x: 40, y:['.$final_start_time.', '.$final_end_time.'], label: "On-Duty"},';
				$graph_value .= "10,, [".$final_start_time.", ".$final_end_time."],, 'On-Duty',,";

				$start_time = $end_time;
			}
		}

		//$graph_value_f =  str_replace("23.59","24.00",$graph_value);

		$gv =  str_replace("[.","[0.",$graph_value);

		$g1 = substr($gv, 0, -2);

		$final_string = $g1;

		if($final_end_time < '24.00') {
			$final_string .= ",,40,, [".$final_end_time.",24.00],, 'Off-Duty'";

			$real_drivinglogid = $drivinglogid;
			// SET OFF-DUTY TIMER

			$final_end_time_offer = $final_end_time;

			$reverse_explode = array_reverse(explode('.',$final_end_time_offer));
				$i = 0;
				$len = count($reverse_explode);

				foreach( $reverse_explode as $time ) {
					if ($i == 0) {
						$minutes = $time;
					} else if ($i == $len - 1) {
						$hours = $time;
					}
					// …
					$i++;
				}
				$minutes = round(($minutes/100)*60);
				if($minutes >= 60) {
					$minutes = 59;
				}
			$minutes_left = 60 - $minutes;
			if($minutes > 0) {
				$hours_left = 23 - $hours;
			} else {
				$hours_left = 24 - $hours;
				$minutes_left = '00';
			}
			if(strlen($hours_left) < 2) {
				$hours_left = '0'.$hours_left;
			}
			if(strlen($minutes_left) < 2) {
				$minutes_left = '0'.$minutes_left;
			}
			if(strlen($hours) < 2) {
				$hours = '0'.$hours;
			}
			if(strlen($minutes) < 2) {
				$minutes = '0'.$minutes;
			}

			$timer_off_time = $hours_left.':'.$minutes_left.':00';
			$start_time_off = $hours.':'.$minutes;
			$start_time_off = date("g:i a", strtotime($start_time_off));

			$query_max = "SELECT MAX(level) AS level FROM driving_log_timer WHERE drivinglogid='$drivinglogid'";
			$get_level = mysqli_fetch_assoc(mysqli_query($dbc,$query_max));
			$new_lev = $get_level['level']+1;

			// GET TOTAL OFF-DUTY TIME

			$col = "SELECT `reset_cycle` FROM driving_log_timer";
			$result = mysqli_query($dbc, $col);
			if (!$result){
				$colcreate = "ALTER TABLE `driving_log_timer` ADD COLUMN `reset_cycle` VARCHAR(555) NULL";
				$result = mysqli_query($dbc, $colcreate);
			}
			$get_driver = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM driving_log WHERE drivinglogid='$drivinglogid'"));
			$drive_id = $get_driver['driverid'];
			$cycler = $get_driver['cycle'];

			$get_time_left = "SELECT * FROM `driving_log` WHERE `driverid` = '$drive_id' AND `cycle` = '$cycler' ORDER BY `drivinglogid` DESC";

			$result1 = mysqli_query($dbc, $get_time_left);
			$num_rows1 = mysqli_num_rows($result1);

			if($num_rows1 > 0) {

				$on_duty_time = '';
				$seconds = 0;
				$minutes = 0;
				$hours = 0;

								$reverse_explode = array_reverse(explode(':',$timer_off_time));

								$i = 0;
								$len = count($reverse_explode);

								foreach( $reverse_explode as $time ) {
									if ($i == 0) {
										$seconds += $time;
									} else if ($i == $len - 1) {
										$hours += $time;
									} else {
										$minutes += $time;
									}
									$i++;
								}

				while($row1 = mysqli_fetch_array($result1)) {

					$drivinglogidd = $row1['drivinglogid'];

					$select_timers = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogidd' ORDER BY timerid DESC";

					$result2 = mysqli_query($dbc, $select_timers);
					$num_rows2 = mysqli_num_rows($result2);
					$is_reset = '';
					if($num_rows2 > 0) {
						while($row2 = mysqli_fetch_array($result2)) {

							if($row2['reset_cycle'] == 1) {
								$is_reset .='1';
								break;
							}

							if($row2['off_duty_timer'] !== '' && $row2['off_duty_timer'] !== NULL) {

								$reverse_explode = array_reverse(explode(':',$row2['off_duty_timer']));

								$i = 0;
								$len = count($reverse_explode);

								foreach( $reverse_explode as $time ) {

									if ($i == 0) {
										$seconds += $time;
									} else if ($i == $len - 1) {
										$hours += $time;
									} else {
										$minutes += $time;
									}
									// …
									$i++;

								}
							}
						}
					}
				}
			}

			// SUM UP OFF DUTY TIME

					$minute_from_seconds = $seconds/60;
					$minute_add = floor($minute_from_seconds);
					$seconds_left = $minute_from_seconds - $minute_add;
					$seconds = $seconds_left*60;

					$minutes = $minutes + $minute_add;

					$hours_from_minutes = $minutes/60;
					$hour_add = floor($hours_from_minutes);
					$minutes_left = $hours_from_minutes - $hour_add;
					$minutes = $minutes_left*60;

					$hours = $hours+$hour_add;

					$hours_left = sprintf("%02d", $hours);
					$minutes_left = sprintf("%02d", $minutes);
					$seconds_left = sprintf("%02d", $seconds);
					//if statement
					if($cycler == 'Cycle 1(7 days)') {
						if($hours_left >= 36) {
							$resetter = 1;
						} else {
							$resetter = NULL;
						}
					} else {
						if($hours_left >= 72) {
							$resetter = 1;
						} else {
							$resetter = NULL;
						}
					}

			// END COUNT OF OFF DUTY TIME
			$drivinglogid = $real_drivinglogid;
			$query_insert_report = "INSERT INTO `driving_log_timer` (`drivinglogid`, `level`, `off_duty_timer`, `off_duty_time`, `end_off_duty_time`, `final_off_duty_timer`, `dl_comment`, `amendments`, `amendments_comment`, `amendments_status`, `reset_cycle`) VALUES ('$real_drivinglogid', '$new_lev', '$timer_off_time', '$start_time_off', '12:00 AM', '$timer_off_time', 'Off-duty time', '', '' , 'Approved', '$resetter')";

			$result_insert_report = mysqli_query($dbc, $query_insert_report);
		}

		if (strpos($final_string,'Off-Duty') === false) {
			$final_string .= ",,40,, [24.00,24.00],, 'Off-Duty'";
		}
		if (strpos($final_string,'Driving') === false) {
			$final_string .= ",,20,, [24.00,24.00],, 'Driving'";
		}
		if (strpos($final_string,'On-Duty') === false) {
			$final_string .= ",,10,, [24.00,24.00],, 'On-Duty'";
		}
		if (strpos($final_string,'Sleeper Berth') === false) {
			$final_string .= ",,30,, [24.00,24.00],, 'Sleeper Berth'";
		}
		$final_string =  str_replace("[.","[0.",$final_string);
		$final_string =  str_replace(", .",", 0.",$final_string);
		$graphid = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM driving_log_graph WHERE drivinglogid='$drivinglogid'"));
		$graphid = $graphid['graphid'];
			if ($graphid !== NULL && $graphid !== ''){
				$query_insert_graph = 'UPDATE `driving_log_graph` SET graph_data = "'.$final_string.'"  WHERE drivinglogid = "'.$drivinglogid.'"';
			} else {
				$query_insert_graph = 'INSERT INTO `driving_log_graph` (`drivinglogid`, `graph_data`) VALUES ("'.$drivinglogid.'", "'.$final_string.'")';
			}

		$result_insert_graph = mysqli_query($dbc, $query_insert_graph);

		$query_update_log = "UPDATE `driving_log` SET `status` = 'Done'  WHERE `drivinglogid` = '$drivinglogid'";
		$result_update_log = mysqli_query($dbc, $query_update_log);
	}
	header('Location: amendments.php?graph=on&drivinglogid='.$drivinglogid);
}

?>
<style>
#chartContainer {
		background-image:url('<?php echo WEBSITE_URL; ?>/Driving Log/tmp/background-lines.png');

	}
</style>

<script type="text/javascript">

$(document).ready(function () {
    <?php if ($view_only_mode == 1) { ?>
        $('div.container form').find('input,select,button,a,textarea,.select2,.chosen-container,ul div').not('.allow_view_only').each(function() {
            $(this).css('pointer-events', 'none');
            if ($(this)[0].tagName == 'TEXTAREA') {
                $(this).parent('div').css('pointer-events', 'none');
            }
        });
    <?php } ?>

	  $(".sign").change(function() {
        if(this.checked) {
            drivinglogid = this.id;
            $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "driving_log_ajax_all.php?fill=amendments&action=sign&id="+drivinglogid,
                dataType: "html",   //expect html to be returned
                success: function(response){
                    location.reload();
                }
            });
        }
    });
});
$(document).on('change', 'select[name="priority[]"]', function() { selectPriority(this); });

function clickAme(sel) {
	var typeId = sel.id;
    $("#"+typeId).attr("readonly", false);
}
function changeAme(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	var check_time = $('#'+typeId)
        .closest('tr')
        .prev('tr')
        .find('input[type=time]').attr('value');

	if(check_time !== '') {
		if(check_time > stage) {
			alert("Please make sure your start time ("+stage+") is not before "+check_time);
			return false;
		}
	}

	// Cannot be after end time.

	var check_time = $('#'+typeId)
        .closest('td')
        .next('td')
        .find('.end_timer_time_val').text();

	if(check_time !== '') {
		if(check_time < stage) {
			alert("Please make sure your start time ("+stage+") is not greater than this timer's end time ("+check_time+").");
			return false;
		}
	}

    if(!stage.match(/^(\d\d):(\d\d)\s?(?:AM|PM)?$/)) {
        alert('Invalid Time Format');
        location.reload();
        return false;
    }

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "driving_log_ajax_all.php?fill=amendments&action=update&id="+arr[1]+'&value='+stage+'&column='+arr[0],
		//url: "driving_log_ajax_all.php?fill=amendments&action=update&id="+arr[1]+'&value='+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}

function changeEndAme(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

    if(!stage.match(/^(\d\d):(\d\d)\s?(?:AM|PM)?$/)) {
        alert('Invalid Time Format');
        return false;
    }

	var check_time = $('#'+typeId)
        .closest('td')
        .prev('td')
        .find('input[type=time]').attr('value');

	if(check_time !== '') {
		if(check_time > stage) {
			alert("Please make sure your end time ("+stage+") is not before "+check_time);
			return false;
		}
	}

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "driving_log_ajax_all.php?fill=end_amendments&id="+arr[1]+'&value='+stage+'&column='+arr[0],
		//url: "driving_log_ajax_all.php?fill=amendments&action=update&id="+arr[1]+'&value='+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}

function addAme(sel) {
    $(".add_new_ame").show();
}
function saveAme(sel) {
	var drivinglogid = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

    var timer_name = $(".timer_name").val();
    var ame_time = $(".ame_time").val();
	var ender_time = $(".ender_time").val();
    var com1 = $(".comment").val();

	if(ame_time > ender_time) {
		alert('Start time cannot be after the end time.');
		return false;
	} else if (ame_time == ender_time) {
		alert('Start time cannot be equal to the end time.');
		return false;
	}

	var check_time = $('.check_the_time')
        .closest('tr')
        .prev('tr')
        .find('input[type=time]').attr('value');

	if(check_time !== '') {
		if(check_time > ame_time) {
			alert("Please make sure your start time is not before "+check_time);
			return false;
		}
	}

    var com2 = com1.replace(/ /g,'***');
    var comment = com2.replace("&", "__");

    if(!ame_time.match(/^(\d\d):(\d\d)\s?(?:AM|PM)?$/)) {
        alert('Invalid time format for Start Time.');
        //location.reload();
        return false;
    }
	if(!ender_time.match(/^(\d\d):(\d\d)\s?(?:AM|PM)?$/)) {
        alert('Invalid time format for End Time.');
        //location.reload();
        return false;
    }

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "driving_log_ajax_all.php?fill=amendments&action=add&value=0&id="+drivinglogid+"&timer_name="+timer_name+'&ender_time='+ender_time+'&ame_time='+ame_time+'&comment='+comment,
		dataType: "html",   //expect html to be returned
		success: function(response){
            window.location = 'amendments.php?graph=off&drivinglogid='+drivinglogid;
			//location.reload();
		}
	});
}

function selectPriority(sel) {
	var priority = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "driving_log_ajax_all.php?fill=timerpriority&timerid="+arr[1]+'&priority='+priority,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}

</script>
</head>
<body>
<?php include_once ('../navigation.php');
if(isset($_GET['drivinglogid'])) {
	$drivinglogid = $_GET['drivinglogid'];
	include ('fix_negative_bug.php');
	// include('notices.php');
}
?>

<div class="container triple-pad-bottom">
    <div class="row">

    	<?php if(!$_GET['timer']) { ?>
        <h1 style="display: inline;">Amendments Dashboard</h1>
        <div class="pull-right">View Only: <a href="" class="view_only_button"><img src="../img/icons/switch-<?= $view_only_mode == 1 ? '7' : '6' ?>.png" style="height: 2em;"></a></div>
		<div class="gap-top triple-gap-bottom"><a href="driving_log_tiles.php" class="btn config-btn allow_view_only">Back to Dashboard</a></div>
		<?php } ?>

        <?php
		if(!isset($_GET['graph'])) {
			$graph = '';
		}
        $graph = $_GET['graph'];
        if($_GET['timer']) {
        	$graph = 'on';
        }
        if($graph == 'off') {

		?>
        <!-- <span class="pad-left gap-bottom popover-examples list-inline pull-left">
        <a data-toggle="tooltip" data-placement="top" title="Add/edit amendments if you want to end the log. You should approve all logs in order to generate the graph."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
        </span> -->
        <!-- Notice -->
        <div class="notice gap-bottom gap-top popover-examples">
            <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11"><span class="notice-name">NOTE:</span>
            Verify that your driving log is logged correctly. Add/edit amendments if you want to end the log. All amendments must be approved before you can end your day.</div>
            <div class="clearfix"></div>
        </div>
		<form name="form_sites" method="post" action="amendments.php" class="form-inline" role="form">
			<div id="no-more-table">

			<?php
            $drivinglogid = $_GET['drivinglogid'];
			$query_insert_graph = 'DELETE FROM driving_log_timer WHERE inspection_mode=1 AND drivinglogid = "'.$drivinglogid.'"';
			$result_insert_graph = mysqli_query($dbc, $query_insert_graph);
	        $query_check_credentials = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY level";
			$result = mysqli_query($dbc, $query_check_credentials);

			$num_rows = mysqli_num_rows($result);
			if($num_rows > 0) {
				echo "<div id='no-more-tables'><table border='2' cellpadding='10' class='table'>";
				echo "<tr class='hidden-xs hidden-sm'>
                <th>Order</th>
				<th>Timer Name</th>";
				//echo "<th>Timer Time</th>";
                echo "<th>Start Time</th>
                <th>End Time</th>
                <th>Log Comment</th>";
				/*echo "<th>Amendments Time
                <span class='popover-examples list-inline'>&nbsp;
                <a data-toggle='tooltip' data-placement='top' title='Time format should be hh:mm:ss'><img src='".WEBSITE_URL."/img/info.png' width='20'></a>
                </span>
                </th>"; */
                echo "<th>Amendments Comments</th>";
                //echo "<th>Final Time</th>";
                echo "<th>Status</th>";
                if ($view_only_mode != 1) {
                    echo "<th>Function</th>";
                }
				echo "</tr>";
			} else {
				echo "<h2>No Record Found.</h2>";
			}
            $pending = 0;
            $total = 1;
            $final_status = '';
			while($row = mysqli_fetch_array( $result ))
			{
                $color_off = '';
                if($row['amendments'] != '00:00:00') {
                    $amm_off = ' - '.$row['amendments'];
                    $color_off = 'style = "color:red; "';
                }
                $timerid = $row['timerid'];
                $timer_name = '';
                $timer = '';
                $start_time = '';
                $end_time = '';
                $final_timer = '';
                $column = '';
                if($row['off_duty_timer'] != '') {
                    $column = 'off';
                    $timer_name = 'Off-Duty';
                    $timer = $row['off_duty_timer'];
                    $start_time = $row['off_duty_time'];
                    $end_time = $row['end_off_duty_time'];
                    $final_timer = $row['final_off_duty_timer'];
                }
                if($row['sleeper_berth_timer'] != '') {
                    $column = 'sleeper';
                    $timer_name = 'Sleeper Berth';
                    $timer = $row['sleeper_berth_timer'];
                    $start_time = $row['sleeper_berth_time'];
                    $end_time = $row['end_sleeper_berth_time'];
                    $final_timer = $row['final_sleeper_berth_timer'];
                }
                if($row['driving_timer'] != '') {
                    $column = 'driving';
                    $timer_name = 'Driving';
                    $timer = $row['driving_timer'];
                    $start_time = $row['driving_time'];
                    $end_time = $row['end_driving_time'];
                    $final_timer = $row['final_driving_timer'];
                }
                if($row['on_duty_timer'] != '') {
                    $column = 'on';
                    $timer_name = 'On-Duty';
                    $timer = $row['on_duty_timer'];
                    $start_time = $row['on_duty_time'];
                    $end_time = $row['end_on_duty_time'];
                    $final_timer = $row['final_on_duty_timer'];
                }
				echo "<tr>";

                echo '<td data-title="Order">'
                ?>
                <select data-placeholder="Choose a Priority..." name="priority[]" id="priority_<?php echo $timerid; ?>" class="chosen-select-deselect form-control" required width="380">
                  <?php
                    for ($i = 1; $i <= 100; $i++) {
                        if ($i == $row['level']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $i."'>".$i.'</option>';
                    }
                  ?>
                </select>

                <?php
                echo '</td>';

                //echo '<td>' . $row['level'] . '</td>';
				echo '<td data-title="Timer Name">' . $timer_name . '</td>';
                //echo '<td>' . $timer . '</td>';

                echo '<td data-title="Time">' .date("g:i a", strtotime($start_time));
                echo "&nbsp;&nbsp;<input type='time' readonly onClick='clickAme(this)' onfocusout='changeAme(this)' placeholder='00:00 AM/PM' class='form-control input-sm' id='".$column.'_'.$timerid."' name='' value='".DATE("H:i", STRTOTIME($start_time))."' class=''>";
                echo '</td>';

                //echo '<td>' . $start_time . '</td>';

                if($total == $num_rows) {
                    //echo '<td>' . $end_time . '</td>';
                    echo '<td data-title="Start Time">' . date("g:i a", strtotime($end_time));
                    echo "&nbsp;&nbsp;<input type='time' readonly onClick='clickAme(this)' onfocusout='changeEndAme(this)' class='form-control input-sm' id='".$column.'_'.$timerid."_end' name='' placeholder='00:00 AM/PM' value='".DATE("H:i", STRTOTIME($end_time))."' class=''><span class='end_timer_time_val' style='display:none;'>".DATE("H:i", STRTOTIME($end_time))."</span>";
                    echo '</td>';
                } else {
                    echo '<td data-title="End Time">' . date("g:i a", strtotime($end_time)) . '<span class="end_timer_time_val" style="display:none;">'.DATE("H:i", STRTOTIME($end_time)).'</span></td>';
                }
                echo '<td data-title="Log Comment">' . $row['dl_comment'] . '</td>';

                if($row['amendments_status'] == 'Pending') {
                    $final_status = 'Pending';
                    //echo "<td><input type='text' readonly onClick='clickAme(this)' onChange='changeAme(this)' class='form-control input-sm' id='ame_".$timerid."' name='' value='".$row['amendments']."' class=''></td>";
                    $pending = 1;
                } else {
                    //echo '<td>'.$row['amendments'].'</td>';
                }

                echo '<td data-title="Amendments Comments">' . $row['amendments_comment'] . '</td>';

                //echo '<td style="background-color: lightblue;">' . $final_timer . '</td>';

                echo '<td data-title="Status">';
                if($row['amendments_status'] == 'Pending') {
					if(isset($_GET['admin_view'])) {
						$admin_view = '&admin_view=true';
					} else { $admin_view = '';}
                    echo $row['amendments_status']. ' | <a href="amendments.php?drivinglogid='.$drivinglogid.'&timerid='.$timerid.'&status=Approved'.$admin_view.'"> Approve</a>';
                } else {
                    echo $row['amendments_status'];
                }
                echo '</td>';

                if ($view_only_mode != 1) {
                    echo '<td data-title="Function"><a href=\'../delete_restore.php?action=delete&timerid='.$timerid.'&drivinglogid='.$drivinglogid.'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
                }

				echo "</tr>";
                $my = $row['level'];
                $total++;
			}

                echo '<tr class="add_new_ame" style="display: none;">';
                echo '<td data-title="Order">'.($my+1).'</td>';
                echo '<td data-title="Timer Name">
					<select data-placeholder="Choose a Timer..." name="timer_name" class="chosen-select-deselect form-control timer_name" width="380">
					  <option value=""></option>
					  <option value="Off-Duty">Off-Duty</option>
					  <option value="Sleeper Berth">Sleeper Berth</option>
					  <option value="Driving">Driving</option>
					  <option value="On-Duty">On-Duty</option>
					</select>
                    </td>';
                echo "<td  data-title='Start Time'><input type='time' class='form-control input-sm ame_time' placeholder='00:00 AM/PM' name='' value=''></td>";
				echo "<td  data-title='End Time'><input type='time' class='form-control input-sm ender_time' placeholder='00:00 AM/PM' name='' value=''></td>";
                echo "<td data-title='Log Comment'><input style='width:100%' type='text' class='form-control comment' name=''>";
                echo '<td  data-title="Amendments Comments"><button type="button" name="submit" onClick="saveAme(this)"  value="'.$drivinglogid.'" class="btn brand-btn smt-btn check_the_time">Submit</button></td>';
                echo '</tr>';

			echo '</table></div></div>';

            if($final_status == '' && !isset($_GET['admin_view'])) {
                echo '<button type="submit" name="end_log" value="'.$drivinglogid.'" class="btn brand-btn btn-lg pull-right smt">End Log</button>';
            }
            echo '<button type="button" name="add_new_button" onClick="addAme(this)" value="Submit" class=" btn brand-btn btn-lg pull-right smt">Add Amendments</button>';
			 if(!isset($_GET['admin_view'])) {
				echo '<a href="driving_log.php" class="btn brand-btn btn-lg allow_view_only">Back</a>';
				//echo '<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>';
			 } else {
				  //echo '<a href="javascript: history.go(-1)" class="btn brand-btn">Back</a>';
				  echo '<a href="#" class="btn brand-btn btn-lg allow_view_only" onclick="history.go(-1);return false;">Back</a>';
			 }
			?>
		</form>

        <?php }

        if($graph == 'on') {
        ?>
        <!--<h4 class="pull-right">To print Graph, Click <img src="../img/print_graph.png" width="16" height="32" border="0" alt=""> image from top right corner of graph.</h4>-->


		<form name="form_sites" method="post" action="amendments.php" class="form-inline" role="form">
			<div class="table-responsive">

			<?php
            $drivinglogid = $_GET['drivinglogid'];


            $driving_log = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM driving_log WHERE drivinglogid = '$drivinglogid'"));
            $sign = $driving_log['sign'];
            echo "<div id='no-more-tables'><table class='table table-bordered'>";
            echo "<tr class='hidden-xs hidden-sm'>
                    <th>Log #</th>
                    <th>Driver</th>
                    <th>Co-Driver</th>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th>Trailer</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total KM</th>
                    <th>Notes</th>";
            echo '<tr>';
                    $drivinglogid = $driving_log['drivinglogid'];

                    $get_checklists = mysqli_fetch_all(mysqli_query($dbc,"SELECT * FROM driving_log_safety_inspect WHERE drivinglogid='$drivinglogid'"),MYSQLI_ASSOC);

                    echo '<tr>';
                    echo '<td data-title="Log#">' . $driving_log['drivinglogid'] . '</td>';
					$driver_name = get_staff($dbc, $driving_log['driverid']);
                    echo '<td data-title="Driver">' . get_staff($dbc, $driving_log['driverid']) . '</td>';
					$co_driver = get_staff($dbc, $driving_log['codriverid']);
                    echo '<td data-title="Co-Driver">' . get_staff($dbc, $driving_log['codriverid']) . '</td>';

                    echo '<td data-title="Customer">' .  get_client($dbc, $driving_log['clientid']) . '</td>';
					$vehicle = get_equipment_field($dbc, $driving_log['vehicleid'], 'unit_number');
                    if (count($get_checklists) > 1) {
                        echo '<td data-title="Vehicle">';
                        foreach ($get_checklists as $checklist) {
                            echo '#'.get_equipment_field($dbc, $checklist['safety_inspect_vehicleid'], 'unit_number').'<br />';
                        }
                        echo '</td>';
                        echo '<td data-title="Trailer">';
                        foreach ($get_checklists as $checklist) {
                            echo '#'.get_equipment_field($dbc, $checklist['safety_inspect_trailerid'], 'unit_number').'<br />';
                        }
                        echo '</td>';
                    } else {
                        echo '<td data-title="Vehicle">#' . get_equipment_field($dbc, $driving_log['vehicleid'], 'unit_number') . '</td>';
                        echo '<td data-title="Trailer">#' . get_equipment_field($dbc, $driving_log['trailerid'], 'unit_number') . '</td>';
                    }
                    echo '<td data-title="Start Date">' . $driving_log['start_date'] . '</td>';
                    echo '<td data-title="End Date">' . $driving_log['end_date'] . '</td>';

                    if($driving_log['end_date'] != '') {
                        echo '<td data-title="Total KM">';
                        foreach ($get_checklists as $checklist) {
                            $total_km = $checklist['final_odo_kms'] - $checklist['begin_odo_kms'];
                            echo $checklist['final_odo_kms'] - $checklist['begin_odo_kms'].'<br />';
                        }
                        echo '</td>';
                    } else {
                        echo '<td data-title="Total KM">-</td>';
                    }
                    echo '<td data-title="Notes">'.html_entity_decode($driving_log['notes']).'</td>';
                echo "</tr></table></div>";

	        $query_check_credentials = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY level";

			$result = mysqli_query($dbc, $query_check_credentials);
// DISPLAY TIMERS
			$num_rows = mysqli_num_rows($result);
			if($num_rows > 0) {
				echo "<table border='1' cellpadding='10' class='table table-bordered'>";
				echo "<tr>
				<th>Timer Name</th>
                <th>Comment</th>
				<th>Time</th>
                ";
				echo "</tr>";
			} else {
				echo "<h2>No Record Found.</h2>";
			}

			while($row = mysqli_fetch_array( $result ))
			{
                $color_off = '';
                if($row['amendments'] != '00:00:00') {
                    $amm_off = ' - '.$row['amendments'];
                    $color_off = 'style = "color:red; "';
                }
                $timerid = $row['timerid'];
                $timer_name = '';
                $timer = '';
                if($row['off_duty_timer'] != '') {
                    $timer_name = 'Off-Duty';
                    $timer = $row['off_duty_timer'];
                }
                if($row['sleeper_berth_timer'] != '') {
                    $timer_name = 'Sleeper Berth';
                    $timer = $row['sleeper_berth_timer'];
                }
                if($row['driving_timer'] != '') {
                    $timer_name = 'Driving';
                    $timer = $row['driving_timer'];
                }
                if($row['on_duty_timer'] != '') {
                    $timer_name = 'On-Duty';
                    $timer = $row['on_duty_timer'];
                }
				echo "<tr>";
                echo '<td>' . $timer_name . '</td>';
				echo '<td>' . $row['dl_comment'].'<br>'.$row['amendments_comment'] . '</td>';
				$reverse_explode = array_reverse(explode(':',$timer));

					$i = 0;
					$len = count($reverse_explode);

					foreach( $reverse_explode as $time ) {


						if ($i == 0) {
							$seconds = $time;
						} else if ($i == $len - 1) {
							$hours = $time;
						} else {
							$minutes = $time;
						}
						$i++;
					}
                echo '<td>' . $hours . ':'.$minutes.'</td>';

                echo '</tr>';
            }

			echo '</table></div>';

            //$checked = '';
            //if($sign == 1) {
            //    $checked = ' checked disabled';
            //}

            //echo '<h3><input type="checkbox" '.$checked.' value="1" style="height: 20px; width: 20px;" name="sign" class="sign" id="'.$drivinglogid.'" >&nbsp;&nbsp;All Information provided here by driver is correct.</h3><br>';

            include_once ('draw_graph.php');

            echo '<br>';

			?>
			<table border="1" style="border:1px black solid;" cellpadding="3" class="table table-bordered"><tr><th>Off-Duty</th><th>Sleeper Berth</th><th>Driving</th><th>On-Duty</th><!--<th>Total</th>--></tr>
<tr><td><?php echo $off_h_time; ?></td><td><?php echo $sleep_h_time; ?></td><td><?php echo $drive_h_time; ?></td><td><?php echo $on_h_time; ?></td><!--<td><?php // echo $total_t_time; ?></td>--></tr>
</table>

			<?php if(empty($_GET['timer'])) {
				// GET PDF LOGO
				$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='dlog_logo'"));
				if($get_config['configid'] > 0) {
					$get_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='dlog_logo'"));
					$image_file = 'download/'.$get_logo['value'];
				} else {
					$image_file = '../img/fresh-focus-logo-dark.png';
				}
				echo '<input type="hidden" value="'.$image_file.'" name="image_filer">';
				// DONE GETTING PDF LOGO ... IF NO LOGO SET, JUST USE FFM LOGO
	            echo '<input type="hidden" name="drivinglogid" value="'.$drivinglogid.'" />';

	            if($sign == 1) {
					echo '<img src="download/dl_'.$drivinglogid.'.png" width="150" height="70" border="0" alt=""><br />';
					 // echo include ('../phpsign/sign.php');
					 // echo '<button type="submit" name="submit" value="Submit" class="btn brand-btn  smt submit-create-pdf">Submit/Create PDF</button>';
				//	 echo '<button type="button" name="test_butt" value="Submit" class="btn brand-btn   create-pdf">Submit/Create PDF</button>';
	            }
                echo include ('../phpsign/sign.php');
                echo '<br />';
                if($sign == 1) {
					 echo '<a href="download/'.$driving_log['pdf'].'" class="btn brand-btn" target="_blank">View PDF</a>';
                }
				echo '<button type="submit" name="submit" value="Submit" class="btn brand-btn  smt submit-create-pdf">Submit/Create PDF</button>';
				//	echo '<button type="button" name="test_butt" value="Submit" class="btn brand-btn   create-pdf">Submit/Create PDF</button>';

	            if(!empty($_GET['from'])) {
	                //echo '<a href="driving_log_14days.php" class="btn brand-btn">Back</a>';
					echo '<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>';
	            } else {
	                //echo '<a href="driving_log.php" class="btn brand-btn">Back</a>';
					echo '<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>';
	            }
				echo '<a href="driving_log_tiles.php" class="btn brand-btn">Back to Dashboard</a>';
			} ?>

		</form>
        <?php } ?>
    </div>
</div>
<?php if(!$_GET['timer']) { ?>
<?php include ('../footer.php'); ?>
<?php } ?>