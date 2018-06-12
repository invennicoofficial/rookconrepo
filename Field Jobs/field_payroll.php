<?php
/*
Reporting
*/
include ('../include.php');
checkAuthorised('field_job');
include_once('../tcpdf/tcpdf.php');

$tab_result = mysqli_fetch_array(mysqli_query($dbc, "select value from general_configuration where name='field_job_tabs'"));
$tab_config = $tab_result['value'];
$dashboard_result = mysqli_fetch_array(mysqli_query($dbc, "select dashboard_list from field_config_field_jobs where tab='invoice'"));
$dashboard_config = $dashboard_result['dashboard_list'];
if(str_replace(',','',$dashboard_config) == '') {
	$dashboard_config = ',contact,ratio,reg,ot,';
}

mysqli_query($dbc, "UPDATE payroll t1 INNER JOIN field_foreman_sheet t2 ON t1.fsid = t2.fsid SET t1.created_date = t2.today_date");

if (isset($_POST['payroll_pdf'])) {
    $s_start_date = $_POST['s_start_date'];
	$s_end_date = $_POST['s_end_date'];

    $now = strtotime($s_end_date);
    $your_date = strtotime($s_start_date);
    $datediff = $now - $your_date;
    $total_days = floor($datediff/(60*60*24));

    $head_date = '';
    $display_date = $s_start_date;
    $head_reg_ot = '';
    $pdf_payroll = '';

    // CSV
        $filename = 'download/'."payroll_reporting.csv";
        $file = fopen($filename,"w");

        $HeadingsArray=array();
        $HeadingsArray[]= 'Dates';
        for($i=0;$i<=$total_days;$i++) {
            $HeadingsArray[]=$display_date;
            $display_date = date('Y-m-d',strtotime($display_date . "+1 days"));
        }
        fputcsv($file,$HeadingsArray);

        $HeadingsArray=array();
        $HeadingsArray[]= 'Name';
        for($i=0;$i<=$total_days;$i++) {
            $HeadingsArray[]='Reg | OT | Sub | Travel';
        }
        $HeadingsArray[]='Total Reg';
        $HeadingsArray[]='Total OT';
        $HeadingsArray[]='Total Sub';
        $HeadingsArray[]='Total Travel';
        fputcsv($file,$HeadingsArray);

        // Save all records without headings

        $head = "SELECT e.*, p.* FROM contacts e, field_payroll p WHERE e.contactid = p.contactid GROUP BY e.contactid, p.positionid ORDER BY e.last_name";

        $result = mysqli_query($dbc, $head);

        while($row = mysqli_fetch_array( $result )) {
            $valuesArray=array();

            $total_reg = 0;
            $total_ot = 0;
            $total_sub = 0;
            $total_travel = 0;
            $total_reg_check = 0;
            $total_ot_check = 0;
            $total_sub_check = 0;
            $total_travel_check = 0;
            $display_date = $_POST['s_start_date'];
            $display_date2 = $_POST['s_start_date'];
            $employeeid = $row['contactid'];
            $positionid = $row['positionid'];

            for($i=0;$i<=$total_days;$i++) {
                $my = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(reg) AS TOTAL_REG, SUM(ot) AS TOTAL_OT, SUM(sub) AS TOTAL_SUB, SUM(travel) AS TOTAL_TRAVEL FROM field_payroll WHERE contactid = '$employeeid' AND positionid = '$positionid' AND created_date = '$display_date2'"));
                $display_date2 = date('Y-m-d',strtotime($display_date2 . "+1 days"));
                $total_reg_check += $my['TOTAL_REG'];
                $total_ot_check += $my['TOTAL_OT'];
                $total_sub_check += $my['TOTAL_SUB'];
                $total_travel_check += $my['TOTAL_TRAVEL'];
            }

            if($total_reg_check != 0 || $total_ot_check != 0 || $total_sub_check != 0 || $total_travel_check != 0) {

                $valuesArray[] = get_staff($dbc, $employeeid).' : '.get_positions($dbc, $positionid, 'name');
                for($i=0;$i<=$total_days;$i++) {
                    $my = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(reg) AS TOTAL_REG, SUM(ot) AS TOTAL_OT, SUM(sub) AS TOTAL_SUB, SUM(travel) AS TOTAL_TRAVEL FROM field_payroll WHERE contactid = '$employeeid' AND positionid = '$positionid' AND created_date = '$display_date'"));
                    $valuesArray[] = $my['TOTAL_REG'].' | '.$my['TOTAL_OT'].' | $'.$my['TOTAL_SUB'].' | '.$my['TOTAL_TRAVEL'];
                    $display_date = date('Y-m-d',strtotime($display_date . "+1 days"));
                    $total_reg += $my['TOTAL_REG'];
                    $total_ot += $my['TOTAL_OT'];
                    $total_sub += $my['TOTAL_SUB'];
                    $total_travel += $my['TOTAL_TRAVEL'];
                }
                $valuesArray[] =   $total_reg;
                $valuesArray[] =   $total_ot;
                $valuesArray[] =   $total_sub;
                $valuesArray[] =   $total_travel;
                fputcsv($file,$valuesArray);
            }
        }

        fclose($file);
        header("Location: $filename");
    // CSV
}
else if (isset($_POST['payroll_simple'])) {
    $s_start_date = $_POST['s_start_date'];
	$s_end_date = $_POST['s_end_date'];

    $now = strtotime($s_end_date);
    $your_date = strtotime($s_start_date);
    $datediff = $now - $your_date;
    $total_days = floor($datediff/(60*60*24));

    $head_date = '';
    $display_date = $s_start_date;
    $head_reg_ot = '';
    $pdf_payroll = '';

    // CSV
        $filename = 'download/payroll_simplified_reporting_'.date('Y-m-d').'.csv';
        $file = fopen($filename,"w");

        $HeadingsArray=array();
        $HeadingsArray[]= 'First Name';
        $HeadingsArray[]= 'Last Name';
        for($i=0;$i<=$total_days;$i++) {
			$day = date('d',strtotime($display_date));
            $display_date = date('Y-m-d',strtotime($display_date . "+1 days"));
            $HeadingsArray[]=$day.' - Reg';
            $HeadingsArray[]=$day.' - OT';
        }
        $HeadingsArray[]='Reg Total';
        $HeadingsArray[]='OT Total';
        fputcsv($file,$HeadingsArray);

        // Save all records without headings

        $head = "SELECT e.*, p.* FROM contacts e, field_payroll p WHERE e.contactid = p.contactid GROUP BY e.contactid, p.positionid ORDER BY e.last_name";

        $result = mysqli_query($dbc, $head);

        while($row = mysqli_fetch_array( $result )) {
            $valuesArray=array();

            $total_reg = 0;
            $total_ot = 0;
            $total_sub = 0;
            $total_travel = 0;
            $total_reg_check = 0;
            $total_ot_check = 0;
            $total_sub_check = 0;
            $total_travel_check = 0;
            $display_date = $_POST['s_start_date'];
            $display_date2 = $_POST['s_start_date'];
            $employeeid = $row['contactid'];
            $positionid = $row['positionid'];

            for($i=0;$i<=$total_days;$i++) {
                $my = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(reg) AS TOTAL_REG, SUM(ot) AS TOTAL_OT, SUM(sub) AS TOTAL_SUB, SUM(travel) AS TOTAL_TRAVEL FROM field_payroll WHERE contactid = '$employeeid' AND positionid = '$positionid' AND created_date = '$display_date2'"));
                $display_date2 = date('Y-m-d',strtotime($display_date2 . "+1 days"));
                $total_reg_check += $my['TOTAL_REG'];
                $total_ot_check += $my['TOTAL_OT'];
            }

            if($total_reg_check != 0 || $total_ot_check != 0 || $total_sub_check != 0 || $total_travel_check != 0) {

                $valuesArray[] = decryptIt($row['first_name']);
                $valuesArray[] = decryptIt($row['last_name']);
                for($i=0;$i<=$total_days;$i++) {
                    $my = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT SUM(reg) AS TOTAL_REG, SUM(ot) AS TOTAL_OT, SUM(sub) AS TOTAL_SUB, SUM(travel) AS TOTAL_TRAVEL FROM field_payroll WHERE contactid = '$employeeid' AND positionid = '$positionid' AND created_date = '$display_date'"));
                    $valuesArray[] = $my['TOTAL_REG'];
					$valuesArray[] = $my['TOTAL_OT'];;
                    $display_date = date('Y-m-d',strtotime($display_date . "+1 days"));
                    $total_reg += $my['TOTAL_REG'];
                    $total_ot += $my['TOTAL_OT'];
                }
                $valuesArray[] =   $total_reg;
                $valuesArray[] =   $total_ot;
                fputcsv($file,$valuesArray);
            }
        }

        fclose($file);
        header("Location: $filename");
    // CSV
}
?>

</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">
		<?php include('payroll.php'); ?>
	</div>
</div>

<?php include ('../footer.php'); ?>