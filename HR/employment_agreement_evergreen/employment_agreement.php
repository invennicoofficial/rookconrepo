<?php
/*
Add	Sheet
*/
include_once ('../database_connection.php');
error_reporting(0);
?>

<?php
$today_date = date('Y-m-d');
$contactid = $_SESSION['contactid'];
$fields = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employment_agreement WHERE fieldlevelriskid='$formid'"));
	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

</head>
<body>
<center><img src="download/pdf-logo.png" width="150px" width="150px" alt="pdf-logo"/></center> <br>
<center><h2> Employment Agreement </h2></center> <br> <br>
I, <span style=""> <input style="width:30%" type="text" class="form-control" name="fields_0" value="<?php echo $fields[0]; ?>"> , acknowledge and agree to the terms and conditions specified below which will form part of, but not be limited to, my employment obligations to Evergreen Services Inc.
<br><br>	
<div style="font-size:15px">
I acknowledge and agree: <br>
1. To be subject to substance abuse tests according to the specifications under the Substance Abuse Policy.<br><br>
 
2. To follow all Evergreen Services Inc Policies, Practices, and Procedures and to take the mandatory training that Evergreen Services Inc provides before operating equipment or tools.<br><br>
 
3. To be subject to Evergreen Services Discipline Policy and accept that cases of discipline will be handled according to Evergreen Services Inc&#39;s management&#39;s discretion.<br><br>
 
4. If, from the time of rehire after breach of the Substance Abuse Policy, I test positive, my employment will be terminated immediately and I will not be entitled to the same courtesies granted by Evergreen Services Inc. under the Substance Abuse Policy for first time violations; more particularly, Company provided rehabilitation.  Further, I will not be eligible for rehire in the future under any circumstances whatsoever and termination in such case will be final; and <br><br>
 
5. By dating and signing in the appropriate location below.<br>
</div>
<br><br>

Date : <?php echo date('Y-m-d'); ?><br><br>

<div> <div style="float:left"> <?php include ('../phpsign/sign.php'); ?> </div> <div style="float:right"> <?php include ('../phpsign/sign2.php'); ?> </div> </div>
<br><br><br><br><br><br><br><br><br><br>
<div> <div style="float:left; margin-left:150px;font-size:20px"> Employee </div> <div style="float:right;margin-right:150px;font-size:20px"> Manager </div> </div>
<br><br><br>
