<?php
/*
Add	Sheet
*/
include_once ('../database_connection.php');
error_reporting(0);
?>
</head>
<body>
<div style="text-align:center"><img src="download/pdf-logo.png" width="150px" width="150px" alt="pdf-logo"/> </div> <br>
<?php
    echo html_entity_decode($hr_description);
?>

<br>
<h4>
<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
</h4>

<?php include ('../phpsign/sign.php'); ?>