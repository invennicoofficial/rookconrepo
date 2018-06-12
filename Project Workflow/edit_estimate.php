<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('project_workflow');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

/*
if (isset($_POST['submit'])) {
    $estimate_data = filter_var($_POST['estimate_data'],FILTER_SANITIZE_STRING);
    $qd = htmlentities($_POST['estimate_data']);
    $estimate_data = filter_var($qd,FILTER_SANITIZE_STRING);

    $query_update_report = "UPDATE `estimate` SET `estimate_data` = '$estimate_data' WHERE `estimate_data` = '$estimate_data'";
    $result_update_report = mysqli_query($dbc, $query_update_report);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT logo FROM field_config_estimate"));
    $logo = $get_field_config['logo'];
    DEFINE('QUOTE_LOGO', $logo);

    class MYPDF extends TCPDF {

        //Page header
        public function Header() {
            $image_file = 'download/'.QUOTE_LOGO;
            $this->Image($image_file, 10, 10, 60, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $this->SetFont('helvetica', '', 8);
            $header_text = '';
            $this->writeHTMLCell(0, 0, '', '', $header_text, 0, 0, false, "L", "R",true);
        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
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

    $pdf_html = $_POST['estimate_data'];

    $pdf->writeHTML($pdf_html, true, false, true, false, '');
    $pdf->Output('download/estimate_'.$estimate_data.'.pdf', 'F');

    echo '<script type="text/javascript"> alert("Estimate Successfully Updated."); window.location.replace("estimate.php"); </script>';

}
*/

?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">
        <form id="form1" name="form1" method="post" action="edit_estimate.php" enctype="multipart/form-data" class="form-horizontal" role="form">
        <?php
            $projectmanageid = $_GET['projectmanageid'];
            $tile = $_GET['tile'];
            $tab = $_GET['tab'];
            $type = $_GET['type'];
            $project_manage_budget =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	project_manage_budget WHERE	projectmanageid='$projectmanageid'"));
            $estimate_data = $project_manage_budget['estimate_data'];
        ?>
        <?php if($type == 'summary') { ?>
            <div class="form-group">
                <label for="additional_note" class="col-sm-4 control-label">PDF Content:</label>
                <div class="col-sm-8">
                    <textarea name="estimate_data" rows="15" cols="50" class="form-control"><?php echo $estimate_data; ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4 clearfix">
                    <a href="project_workflow_dashboard.php?tile=<?php echo $tile;?>&tab=<?php echo $tab;?>" class="btn config-btn pull-right">Back</a>
                </div>
                <div class="col-sm-8">
                </div>
            </div>
        <?php } ?>

        <?php
            $profit_loss = '';
            $budget = '';

            if($type == 'profit_loss') {
                $profit_loss = 'active_tab';
            }
            if($type == 'budget') {
                $budget = 'active_tab';
            }
        ?>

        <?php if($type != 'summary') { ?>
            <a href='edit_estimate.php?projectmanageid=<?php echo $projectmanageid;?>&type=profit_loss&tile=<?php echo $tile;?>&tab=<?php echo $tab; ?>'><button type="button" class="btn brand-btn mobile-block <?php echo $profit_loss; ?>" >By Heading</button></a>&nbsp;&nbsp;
             <a href='edit_estimate.php?projectmanageid=<?php echo $projectmanageid;?>&type=budget&tile=<?php echo $tile;?>&tab=<?php echo $tab; ?>'><button type="button" class="btn brand-btn mobile-block <?php echo $budget; ?>" >By Service</button></a>&nbsp;&nbsp;
        <?php } ?>

        <?php if($type == 'profit_loss') { ?>
            <a href="project_workflow_dashboard.php?tile=<?php echo $tile;?>&tab=<?php echo $tab;?>" class="btn config-btn pull-right">Back</a>

            <div id='no-more-tables'>
            <table class="table table-bordered">
                <tr class="hidden-xs hidden-sm">
                <th>Type</th>
                <th>Name</th>
                <th>Cost</th>
                <th>Estimate Price</th>
                <th>Profit/Loss</th>
                </tr>
                <?php echo $project_manage_budget['review_profit_loss']; ?>
                <tr>
                <td data-title="Type">Total <?php if (PROJECT_TILE=='Projects') { echo "Project"; } else { echo PROJECT_TILE; } ?> Price</td>
                <td data-title="Name"></td>
                <td data-title="Cost">$<?php echo $project_manage_budget['financial_cost']; ?></td>
                <td data-title="Estimate Price">$<?php echo $project_manage_budget['financial_price']; ?></td>
                <td data-title="Profit/Loss">$<?php echo $project_manage_budget['financial_plus_minus']; ?></td>
                </tr>
            </table></div>
                <a href="project_workflow_dashboard.php?tile=<?php echo $tile;?>&tab=<?php echo $tab;?>" class="btn config-btn pull-right">Back</a>
        <?php } ?>

        <?php if($type == 'budget') { ?>
            <a href="project_workflow_dashboard.php?tile=<?php echo $tile;?>&tab=<?php echo $tab;?>" class="btn config-btn pull-right">Back</a>
            <?php
           $budget_price = explode('*#*', $project_manage_budget['budget_price']);
           //echo '<h3>Total Budget : $'.$budget_price[16].'</h3><br>';
           //echo '<h3>Total Estimate : $'.$project_manage_budget['total_price'].'</h3><br>';
           ?><div id='no-more-tables'>
            <table class="table table-bordered">
                <tr class="hidden-xs hidden-sm">
                <th>Type</th>
                <th>Budget Price</th>
                <th>Estimate Price</th>
                </tr>
                <?php echo $project_manage_budget['review_budget']; ?>
                <tr>
                <td data-title="Type">Total</td>
                <td data-title="Budget Price">$<?php echo $budget_price[16]; ?></td>
                <td data-title="Estimate Price">$<?php echo $project_manage_budget['total_price']; ?></td>
                </tr>
            </table></div>
                <a href="project_workflow_dashboard.php?tile=<?php echo $tile;?>&tab=<?php echo $tab;?>" class="btn config-btn pull-right">Back</a>
        <?php } ?>

        </form>

	</div>
</div>

<?php include ('../footer.php'); ?>