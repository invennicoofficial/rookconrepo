<?php
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

$projectmanageid = $_GET['projectmanageid'];
$tab = $_GET['tab'];
$tile = $_GET['tile'];

$get_estimate_data = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM project_manage WHERE `projectmanageid` = '$projectmanageid'"));
$project_status = $get_estimate_data['status'];
$get_project_manage_budget = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM project_manage_budget WHERE `projectmanageid` = '$projectmanageid'"));

$html = $get_project_manage_budget['estimate_data'];
$front_company_logo = $get_project_manage_budget['front_company_logo'];
$front_client_info = $get_project_manage_budget['front_client_info'];
$front_other_info = $get_project_manage_budget['front_other_info'];
$front_client_logo = $get_project_manage_budget['front_client_logo'];
$front_content_pages = $get_project_manage_budget['front_content_pages'];
$last_content_pages = $get_project_manage_budget['last_content_pages'];

$history = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Approved on '.date('Y-m-d H:i:s').'<br>';
//$query_update_report = "UPDATE `project_manage` SET `status` = 'Pending Quote', `history` = CONCAT(history,'$history') WHERE `projectmanageid` = '$projectmanageid'";
//$result_update_report = mysqli_query($dbc, $query_update_report);

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_project_manage WHERE tile='$tile' AND tab='$tab' AND accordion IS NULL"));
$project_manage_dashboard_config = ','.$get_field_config['project_manage_dashboard'].',';
$pdf_logo = $get_field_config['pdf_logo'];
$pdf_header = $get_field_config['pdf_header'];
$pdf_footer = $get_field_config['pdf_footer'];
$pdf_footer_logo = $get_field_config['pdf_footer_logo'];
$send_pdf_client_body = $get_field_config['send_pdf_client_body'];
$send_pdf_client_subject = $get_field_config['send_pdf_client_subject'];
$pdf_payment_term = $get_field_config['pdf_payment_term'];
$pdf_due_period = $get_field_config['pdf_due_period'];
$pdf_tax = $get_field_config['pdf_tax'];
$pdf_term_condition = $get_field_config['pdf_term_condition'];

DEFINE('HEADER_LOGO', $pdf_logo);
DEFINE('FOOTER_LOGO', $pdf_footer_logo);
DEFINE('HEADER_TEXT', html_entity_decode($pdf_header));
DEFINE('FOOTER_TEXT', html_entity_decode($pdf_footer));
DEFINE('TERM_CONDITION', html_entity_decode($pdf_term_condition));
// PDF

class MYPDF extends TCPDF {

//Page header
    public function Header() {
        if($front_client_info != '') {
            if ($this->PageNo() > 1) {
                if(HEADER_LOGO != '') {
                    $image_file = 'download/'.HEADER_LOGO;
                    $this->Image($image_file, 10, 5, 25, '', '', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
                }

                if(HEADER_TEXT != '') {
                    $this->setCellHeightRatio(0.7);
                    $this->SetFont('helvetica', '', 8);
                    $footer_text = '<p style="text-align:right;">'.HEADER_TEXT.'</p>';
                    $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);
                }
            }
        } else {
            if(HEADER_LOGO != '') {
                $image_file = 'download/'.HEADER_LOGO;
                $this->Image($image_file, 10, 5, 25, '', '', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
            }

            if(HEADER_TEXT != '') {
                $this->setCellHeightRatio(0.7);
                $this->SetFont('helvetica', '', 8);
                $footer_text = '<p style="text-align:right;">'.HEADER_TEXT.'</p>';
                $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);
            }
        }
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $footer_text = '<p style="text-align:right;">'.$this->getAliasNumPage().'</p>';
        $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "R", true);

        if(FOOTER_TEXT != '') {
            $this->SetY(-30);
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 8);
            $footer_text = FOOTER_TEXT;
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "C", true);
        }

        if(FOOTER_LOGO != '') {
            //$this->SetY(-30);
            $image_file = 'download/'.FOOTER_LOGO;
            $this->Image($image_file, 11, 275, 100, '', '', '', '', false, 300, 'C', false, false, 0, false, false, false);
        }

        // Position at 15 mm from bottom
        if(TERM_CONDITION != '') {
            $this->SetY(-30);
            $this->SetFont('helvetica', 'I', 8);
            $footer_text = TERM_CONDITION.'<br><br>';
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
        }
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf_fab = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf_paint = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf_rig = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf_struct = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
//$pdf->setFooterData(array(0,640,0), array(0,640,1280));

$pdf->SetMargins(PDF_MARGIN_LEFT, 8, PDF_MARGIN_RIGHT);
$pdf_fab->SetMargins(PDF_MARGIN_LEFT, 8, PDF_MARGIN_RIGHT);
$pdf_paint->SetMargins(PDF_MARGIN_LEFT, 8, PDF_MARGIN_RIGHT);
$pdf_rig->SetMargins(PDF_MARGIN_LEFT, 8, PDF_MARGIN_RIGHT);
$pdf_struct->SetMargins(PDF_MARGIN_LEFT, 8, PDF_MARGIN_RIGHT);

if($front_client_info != '') {
    $pdf->AddPage();
    $pdf_fab->AddPage();
    $pdf_paint->AddPage();
    $pdf_rig->AddPage();
    $pdf_struct->AddPage();
    $pdf->SetFont('helvetica', '', 8);
    $pdf_fab->SetFont('helvetica', '', 8);
    $pdf_paint->SetFont('helvetica', '', 8);
    $pdf_rig->SetFont('helvetica', '', 8);
    $pdf_struct->SetFont('helvetica', '', 8);
    $pdf->setCellHeightRatio(1);
    $pdf_fab->setCellHeightRatio(1);
    $pdf_paint->setCellHeightRatio(1);
    $pdf_rig->setCellHeightRatio(1);
    $pdf_struct->setCellHeightRatio(1);
    $pdf_html = '';
    if($front_company_logo != '') {
        $pdf_html .= '<div style="text-align:center;"><img src="download/'.$front_company_logo.'" border="0" alt=""></div>';
    }
    if($front_client_logo != '') {
        $pdf_html .= '<div style="text-align:center;"><img src="download/'.$front_client_logo.'" border="0" alt=""></div>';
    }
    $pdf_html .= html_entity_decode($front_client_info).'<br>';
    $pdf_html .= html_entity_decode($front_other_info);
    $pdf->writeHTML($pdf_html, true, false, true, false, '');
    $pdf_fab->writeHTML($pdf_html, true, false, true, false, '');
    $pdf_paint->writeHTML($pdf_html, true, false, true, false, '');
    $pdf_rig->writeHTML($pdf_html, true, false, true, false, '');
    $pdf_struct->writeHTML($pdf_html, true, false, true, false, '');
    $pdf->lastPage();
    $pdf_fab->lastPage();
    $pdf_paint->lastPage();
    $pdf_rig->lastPage();
    $pdf_struct->lastPage();
}

if($front_content_pages != '') {
    $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf_fab->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf_paint->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf_rig->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf_struct->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf->AddPage();
    $pdf_fab->AddPage();
    $pdf_paint->AddPage();
    $pdf_rig->AddPage();
    $pdf_struct->AddPage();
    $pdf->SetFont('helvetica', '', 8);
    $pdf_fab->SetFont('helvetica', '', 8);
    $pdf_paint->SetFont('helvetica', '', 8);
    $pdf_rig->SetFont('helvetica', '', 8);
    $pdf_struct->SetFont('helvetica', '', 8);
    $pdf->setCellHeightRatio(1.75);
    $pdf_fab->setCellHeightRatio(1.75);
    $pdf_paint->setCellHeightRatio(1.75);
    $pdf_rig->setCellHeightRatio(1.75);
    $pdf_struct->setCellHeightRatio(1.75);
    $pdf_html = '';
    $pdf_html .= html_entity_decode($front_content_pages);
    $pdf->writeHTML($pdf_html, true, false, true, false, '');
    $pdf_fab->writeHTML($pdf_html, true, false, true, false, '');
    $pdf_paint->writeHTML($pdf_html, true, false, true, false, '');
    $pdf_rig->writeHTML($pdf_html, true, false, true, false, '');
    $pdf_struct->writeHTML($pdf_html, true, false, true, false, '');
    $pdf->lastPage();
    $pdf_fab->lastPage();
    $pdf_paint->lastPage();
    $pdf_rig->lastPage();
    $pdf_struct->lastPage();
}

$pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
$pdf_fab->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
$pdf_paint->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
$pdf_rig->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
$pdf_struct->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
$pdf->AddPage();
$pdf_fab->AddPage();
$pdf_paint->AddPage();
$pdf_rig->AddPage();
$pdf_struct->AddPage();
$pdf->SetFont('helvetica', '', 8);
$pdf_fab->SetFont('helvetica', '', 8);
$pdf_paint->SetFont('helvetica', '', 8);
$pdf_rig->SetFont('helvetica', '', 8);
$pdf_struct->SetFont('helvetica', '', 8);
$pdf->setCellHeightRatio(1.75);
$pdf_fab->setCellHeightRatio(1.75);
$pdf_paint->setCellHeightRatio(1.75);
$pdf_rig->setCellHeightRatio(1.75);
$pdf_struct->setCellHeightRatio(1.75);
$pdf_html = '';
$pdf_html .= html_entity_decode($html);

$fab_html = $paint_html = $rig_html = $struct_html = $pdf_html;
$fab_stat = $paint_stat = $rig_stat = $struct_stat = false;
$fab_regex = '/<!--FABRICATION-->(.*)<!--FABRICATION END-->/s';
$paint_regex = '/<!--PAINTING-->(.*)<!--PAINTING END-->/s';
$rig_regex = '/<!--RIGGING-->(.*)<!--RIGGING END-->/s';
$struct_regex = '/<!--STRUCTURE-->(.*)<!--STRUCTURE END-->/s';
$fab_match = [];
$paint_match = [];
$rig_match = [];
$struct_match = [];
preg_match($fab_regex,$pdf_html,$fab_match);
preg_match($paint_regex,$pdf_html,$paint_match);
preg_match($rig_regex,$pdf_html,$rig_match);
preg_match($struct_regex,$pdf_html,$struct_match);
if(count($fab_match) > 0) {
	$fab_html = str_replace([$paint_match[0],$rig_match[0],$struct_match[0]],'',$fab_html);
	$pdf_fab->writeHTML($fab_html, true, false, true, false, '');

	if($last_content_pages != '') {
		$pdf_fab->lastPage();
		$pdf_fab->AddPage();
		$pdf_fab->SetFont('helvetica', '', 8);
		$pdf_fab->setCellHeightRatio(1.75);
		$fab_html = '';
		$fab_html .= html_entity_decode($last_content_pages).'<br>';
		$pdf_fab->writeHTML($fab_html, true, false, true, false, '');
	}

	$pdf_fab->Output('download/pdf_fabrication_'.$projectmanageid.'.pdf', 'F');
}
if(count($paint_match) > 0) {
	$paint_html = str_replace([$fab_match[0],$rig_match[0],$struct_match[0]],'',$paint_html);
echo '1';
	$pdf_paint->writeHTML($paint_html, true, false, true, false, '');
echo '1';
	if($last_content_pages != '') {
		$pdf_paint->lastPage();
		$pdf_paint->AddPage();
		$pdf_paint->SetFont('helvetica', '', 8);
		$pdf_paint->setCellHeightRatio(1.75);
		$paint_html = '';
		$paint_html .= html_entity_decode($last_content_pages).'<br>';
		$pdf_paint->writeHTML($paint_html, true, false, true, false, '');
	}

	$pdf_paint->Output('download/pdf_paint_'.$projectmanageid.'.pdf', 'F');
}
if(count($rig_match) > 0) {
	$rig_html = str_replace([$paint_match[0],$fab_match[0],$struct_match[0]],'',$rig_html);
	echo '1';
	$pdf_rig->writeHTML($rig_html, true, false, true, false, '');
echo '1';
	if($last_content_pages != '') {
		$pdf_rig->lastPage();
		$pdf_rig->AddPage();
		$pdf_rig->SetFont('helvetica', '', 8);
		$pdf_rig->setCellHeightRatio(1.75);
		$rig_html = '';
		$rig_html .= html_entity_decode($last_content_pages).'<br>';
		$pdf_rig->writeHTML($rig_html, true, false, true, false, '');
	}

	$pdf_rig->Output('download/pdf_rigging_'.$projectmanageid.'.pdf', 'F');
}
if(count($struct_match) > 0) {
	$struct_html = str_replace([$paint_match[0],$rig_match[0],$fab_match[0]],'',$struct_html);
	echo '1';
	$pdf_struct->writeHTML($struct_html, true, false, true, false, '');
echo '1';
	if($last_content_pages != '') {
		$pdf_struct->lastPage();
		$pdf_struct->AddPage();
		$pdf_struct->SetFont('helvetica', '', 8);
		$pdf_struct->setCellHeightRatio(1.75);
		$struct_html = '';
		$struct_html .= html_entity_decode($last_content_pages).'<br>';
		$pdf_struct->writeHTML($struct_html, true, false, true, false, '');
	}

	$pdf_struct->Output('download/pdf_structure_'.$projectmanageid.'.pdf', 'F');
}
$pdf->writeHTML($pdf_html, true, false, true, false, '');

if($last_content_pages != '') {
    $pdf->lastPage();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 8);
    $pdf->setCellHeightRatio(1.75);
    $pdf_html = '';
    $pdf_html .= html_entity_decode($last_content_pages).'<br>';
    $pdf->writeHTML($pdf_html, true, false, true, false, '');
}

$pdf->Output('download/pdf_'.$projectmanageid.'.pdf', 'F');

if($project_status == 'Approved' && $tab == 'Pending Work Order') {
	$tab = 'Shop Work Order';
}
$message = $tab.' PDF generated.';
echo '<script>
	window.location.replace("project_workflow_dashboard.php?tab='.$tab.'&tile='.$tile.'");
    </script>';