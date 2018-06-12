<?php
if (isset($_POST['export_pdf'])) {
    $checklistid = $_POST['checklistid'];

    $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM checklist WHERE checklistid='$checklistid'"));

    $security = $get_contact['security'];
    $checklist_type = $get_contact['checklist_type'];
    $checklist_name = $get_contact['checklist_name'];

    DEFINE('CHECKLIST_NAME', $checklist_name);
    class MYPDF extends TCPDF {
        public function Header() {
			$this->SetFont('helvetica', '', 30);
			$footer_text = '<p style="text-align:center; background-color: #516371; color:white; height:100px; ">'.CHECKLIST_NAME.'</p>';
			$this->writeHTMLCell(0, 40, 15 , 15, $footer_text, 0, 0, false, "L", true);
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
    $pdf->SetMargins(PDF_MARGIN_LEFT, 45, PDF_MARGIN_RIGHT);

    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 14);
    $pdf->SetTextColor(53,175,199);

    $html_weekly .= '<table cellpadding="4">';

    $query_check_credentials = "SELECT * FROM checklist_name WHERE checklistid='$checklistid' ORDER BY checked, priority";
    $result = mysqli_query($dbc, $query_check_credentials);
    $num_rows = mysqli_num_rows($result);
    while($row = mysqli_fetch_array($result)) {
        $html_weekly .= '<tr>
                        <td width="5%">';
        $checked = '';
        if($row['checked'] == 1) {
            $html_weekly .= '<img src="../img/checkmark.png" width="15px">&nbsp;&nbsp;';
        } else {
            $html_weekly .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        $html_weekly .= '</td><td width="95%">'.$row['checklist'].'</td></tr>';
    }

    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('download/Checklist_'.$checklistid.'.pdf', 'F');

    if($security == 'My Checklist') {
        $url = 'review_project.php?type=checklist&projectid='.$_POST['projectid'].'&category='.$checklist_type.'&checklistid='.$checklistid;
    } else {
        $url = 'review_project.php?type=company_checklist&projectid='.$_POST['projectid'].'&category='.$checklist_type.'&checklistid='.$checklistid;
    }

    echo '<script type="text/javascript">
    window.location.replace("'.$url.'");
    window.open("download/Checklist_'.$checklistid.'.pdf", "fullscreen=yes");
    </script>';
}
?>
<script type="text/javascript" src="checklist.js"></script>
<style type='text/css'>
.ui-state-disabled  { pointer-events: none !important; }

.display-field {
  display: inline-block;
  /* padding-left: 50px; */
  text-indent: 2px;
  vertical-align: top;
  width: 97%;
}
</style>
<script>
setTimeout(function() {

var maxWidth = Math.max.apply( null, $( '.ui-sortable' ).map( function () {
    return $( this ).outerWidth( true );
}).get() );

var maxHeight = -1;

$('.ui-sortable').each(function() {
  maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();

});

$(function() {
  $(".connectedChecklist").width(maxWidth).height(maxHeight);
});
$( '.connectedChecklist' ).each(function () {
    this.style.setProperty( 'height', maxHeight, 'important' );
	this.style.setProperty( 'width', maxWidth, 'important' );

	<?php if($check_table_orient == 1) { ?>
		$(this).attr('style', 'height:'+maxHeight+'px !important; width:'+maxWidth+'px !important');
	<?php } else { ?>
		$(this).attr('style', 'height:'+maxHeight+'px !important;');
	<?PHP } ?>
});

}, 200);

$(document).ready(function() {
});
</script>
    <?php
    $projectid = $_GET['projectid'];
    ?>

	<br><br>

    <form name="form_sites" method="post" action="" class="form-inline" role="form">
        <div>
        <?php
			echo '<div class="tab-container">';

            echo '<ul id="sortable'.$i.'" class="connectedChecklist">
            <li class="ui-state-default ui-state-disabled" style="cursor:pointer; font-size: 30px;">Tickets</li>';

            $result = mysqli_query($dbc, "SELECT t.*, c.name FROM tickets t, contacts c WHERE t.businessid = c.contactid AND client_projectid='$projectid' ORDER BY ticketid DESC");

            while($row = mysqli_fetch_array( $result )) {
                $checked = '';
                if($row['status'] == 'Archive') {
                    $checked = ' checked';
                }
                echo '<li id="'.$row['ticketid'].'" class="ui-state-default"><span style="cursor:pointer; font-size: 25px;"><input type="checkbox" '.$checked.' disabled value="'.$row['ticketid'].'" style="height: 30px; width: 30px;" name="checklistnameid[]">&nbsp;&nbsp;<span class="display-field">#'.$row['ticketid'].' : '.$row['service_type'].' : '.$row['heading'].' : '.$row['status'].'</span></span>';

                echo '</li>';

            }

            echo '</ul>';
            $i++;

        ?>
        </div>

		</form>
