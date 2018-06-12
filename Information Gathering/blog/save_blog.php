<?php
    $today_date = date('Y-m-d');
    $business = filter_var(htmlentities($_POST['business']),FILTER_SANITIZE_STRING);

	$purpose = filter_var(htmlentities($_POST['purpose']),FILTER_SANITIZE_STRING);
	$audience = filter_var(htmlentities($_POST['audience']),FILTER_SANITIZE_STRING);
	$competitors = filter_var(htmlentities($_POST['competitors']),FILTER_SANITIZE_STRING);
	$content_anchors = filter_var(htmlentities($_POST['content_anchors']),FILTER_SANITIZE_STRING);
	$platform = implode(',',$_POST['platform']);
	$channels = implode(',',$_POST['channels']);
	$conversion_content = implode(',',$_POST['conversion_content']);

	$free_nurture_content = implode(',',$_POST['free_nurture_content']);

	$paid_nurture_content = implode(',',$_POST['paid_nurture_content']);

	$featured_product_or_service = filter_var(htmlentities($_POST['featured_product_or_service']),FILTER_SANITIZE_STRING);

	$research = filter_var(htmlentities($_POST['research']),FILTER_SANITIZE_STRING);
	$repurposing = filter_var(htmlentities($_POST['repurposing']),FILTER_SANITIZE_STRING);
	$writing = filter_var(htmlentities($_POST['writing']),FILTER_SANITIZE_STRING);
	$promotion = filter_var(htmlentities($_POST['promotion']),FILTER_SANITIZE_STRING);
	$creative = filter_var(htmlentities($_POST['creative']),FILTER_SANITIZE_STRING);
	$quality_assurance = filter_var(htmlentities($_POST['quality_assurance']),FILTER_SANITIZE_STRING);

	//if(empty($_POST['fieldlevelriskid'])) {
        $query_insert_site = "INSERT INTO `info_blog` (`infogatheringid`, `today_date`, `business`, `purpose`, `audience`, `competitors`, `content_anchors`, `platform`, `channels`, `conversion_content`, `free_nurture_content`, `paid_nurture_content`, `featured_product_or_service`, `research`, `repurposing`, `writing`, `promotion`, `creative`, `quality_assurance`) VALUES	('$infogatheringid','$today_date','$business','$purpose','$audience','$competitors','$content_anchors','$platform','$channels','$conversion_content','$free_nurture_content','$paid_nurture_content','$featured_product_or_service','$research','$repurposing','$writing','$promotion','$creative', '$quality_assurance')";

		$result_insert_site	= mysqli_query($dbc, $query_insert_site);
        $fieldlevelriskid = mysqli_insert_id($dbc);

        $created_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $query_insert_site = "INSERT INTO `infogathering_pdf` (`infogatheringid`, `fieldlevelriskid`, `today_date`, `created_by`, `company`) VALUES	('$infogatheringid', '$fieldlevelriskid', '$today_date', '$created_by', '$business')";
        $result_insert_site	= mysqli_query($dbc, $query_insert_site);

	//} else {
    //    $fieldlevelriskid = $_POST['fieldlevelriskid'];
    //    $query_update_employee = "UPDATE `info_blog` SET `business` = '$business', `purpose` = '$purpose', `audience` = '$audience',`competitors` = '$competitors',`content_anchors` = '$content_anchors',`platform` = '$platform',`channels` = '$channels',`conversion_content` = '$conversion_content',`free_nurture_content` = '$free_nurture_content',`paid_nurture_content` = '$paid_nurture_content',`featured_product_or_service` = '$featured_product_or_service',`research` = '$research',`repurposing` = '$repurposing',`writing` = '$writing', `promotion` = '$promotion',`creative` = '$creative',`quality_assurance` = '$quality_assurance' WHERE fieldlevelriskid='$fieldlevelriskid'";
    //    $result_update_employee = mysqli_query($dbc, $query_update_employee);

    //    $query_update_employee = "UPDATE `infogathering_pdf` SET `company` = '$business' WHERE fieldlevelriskid='$fieldlevelriskid' AND infogatheringid='$infogatheringid'";
    //    $result_update_employee = mysqli_query($dbc, $query_update_employee);

    //}

    include ('blog_pdf.php');
    echo blog_pdf($dbc,$infogatheringid, $fieldlevelriskid);

    echo '<script type="text/javascript">
       window.location.replace("manual_reporting.php?type=infogathering"); </script>';

?>