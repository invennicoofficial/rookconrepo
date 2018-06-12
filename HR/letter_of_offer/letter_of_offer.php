<?php
/*
Add	Sheet
*/
include_once ('../database_connection.php');
error_reporting(0);
?>

</head>
<body>

<?php

    $email_body = html_entity_decode($hr_description);
    $email_body = str_replace("[Company Address]", $config_extra_fields[1], $email_body);
    $email_body = str_replace("[Company Name]", $config_extra_fields[0], $email_body);
    $email_body = str_replace("[Employee Name]", '<input name="fields_0" type="text" class="form-control">', $email_body);
    $email_body = str_replace("[Employee Position]", '<input name="fields_1" type="text" class="form-control">', $email_body);
    $email_body = str_replace("[Joining Date]", '<input name="fields_2" type="text" class="datepicker">', $email_body);
    echo $email_body;
?>

<!--
<br>
<h4>
<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
</h4>

<?php include ('../phpsign/sign.php'); ?>
-->