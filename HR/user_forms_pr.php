<?php $return_url = '?';
if(!empty($_GET['frefid'])) {
    $return_url = hex2bin($_GET['frefid']);
}
if(!empty($_POST['submit_pr_form'])) {
    include_once('../tcpdf/tcpdf.php');
    require_once('../phpsign/signature-to-image.php');
    
    $form_id = $_POST['form_id'];
    $assign_id = $_POST['assign_id'];

    $user_id = (empty($_SESSION['contactid']) ? 0 : $_SESSION['contactid']);
    $result = mysqli_query($dbc, "SELECT * FROM `user_form_assign` WHERE `form_id`='$form_id' AND '$assign_id' IN (`assign_id`,'') AND `completed_date` IS NULL");
    
    $reviewid = $_POST['reviewid'];
    if(!empty($reviewid)) {
        $pdf_id = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `performance_review` WHERE `reviewid` = '$reviewid'"))['pdf_id'];
    } else {
        $pdf_result = mysqli_query($dbc, "INSERT INTO `user_form_pdf` (`form_id`, `user_id`) VALUES ('$form_id', '$user_id')");
        $pdf_id = mysqli_insert_id($dbc);
    }
    if(mysqli_num_rows($result)) {
        $assign_id = mysqli_fetch_array($result)['assign_id'];
        mysqli_query($dbc, "UPDATE `user_form_assign` SET `completed_date`=CURRENT_TIMESTAMP, `pdf_id`='$pdf_id' WHERE `assign_id`='$assign_id'");
    } else {
        mysqli_query($dbc, "INSERT INTO `user_form_assign` (`form_id`, `user_id`, `completed_date`, `pdf_id`) VALUES ('$form_id', '$user_id', CURRENT_TIMESTAMP, '$pdf_id')");
        $assign_id = mysqli_insert_id($dbc);
    }
    $form = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `user_forms` WHERE `form_id`='$form_id'"));
    $pdf_name = preg_replace('/([^a-z])/', '', strtolower($form['name'])).'_'.$assign_id.'.pdf';
    mysqli_query($dbc, "UPDATE `user_form_pdf` SET `generated_file`='$pdf_name' WHERE `pdf_id`='$pdf_id'");

    include('../Form Builder/generate_form_pdf.php');

    $pr_staff = $_POST['pr_staff'];
    $pr_position = $_POST['pr_position'];
    $today_date = date('Y-m-d');

    if(empty($reviewid)) {
        $query_insert_upload = "INSERT INTO `performance_review` (`userid`, `reviewerid`, `today_date`, `position`, `user_form_id`, `pdf_id`) VALUES ('$pr_staff', '".$_SESSION['contactid']."', '$today_date', '$pr_position', '$form_id', '$pdf_id')";
        $result_insert_upload = mysqli_query($dbc, $query_insert_upload);
        $reviewid = mysqli_insert_id($dbc);
    } else {
        $query_update_upload = "UPDATE `performance_review` SET `userid` = '$pr_staff', `reviewerid` = '".$_SESSION['contactid']."', `today_date` = '$today_date', `position` = '$pr_position', `user_form_id` = '$form_id', `pdf_id` = '$pdf_id' WHERE `reviewid` = '$reviewid'";
        $result_update_upload = mysqli_query($dbc, $query_update_upload);
    }

    $pdf->writeHTML(utf8_encode('<form action="" method="POST">'.$pdf_text.'</form>'), true, false, true, false, '');

    include('../Form Builder/generate_form_pdf_page.php');
    
    if(!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    $pdf->Output('download/'.$pdf_name, 'F');
    
    echo "<script> window.location.replace('index.php?performance_review=list'); window.open('download/$pdf_name', '_blank'); </script>";
} else {
    $form_id = $_GET['form_id'];
    $default_collapse = 'in';
    if(isset($reviewid)) {
        $get_pr = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `performance_review` WHERE `reviewid` = '$reviewid'"));
        $pdf_id = $get_pr['pdf_id'];
    } else {
        $get_pf = '';
        $pdf_id = '';
    }
    include('../Form Builder/generate_form_contents.php'); ?>

    <div class="form-group">
        <p><span class="hp-red"><em>Required Fields *</em></span></p>
    </div>
    <script>
    $(document).ready(function () {
        $('[name="submit_pr_form"]').click(function() {
            return checkMandatoryFields();
        });
    });
    </script>
<?php } ?>