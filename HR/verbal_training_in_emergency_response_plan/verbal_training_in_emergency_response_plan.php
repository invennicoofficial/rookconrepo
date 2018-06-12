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
    echo html_entity_decode($hr_description);

    if($document != '') {
        $all_task_each = explode('**##**',$document);

        $total_count = mb_substr_count($document,'**##**');
        if($total_count > 0) {
            echo "<table class='table table-bordered'>";
            echo "<tr class='hidden-xs hidden-sm'>
            <th>Document Name</th>
            <th>Upload</th>";
        }
        for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
            $task_item = explode('**',$all_task_each[$client_loop]);
            $task = $task_item[0];
            $hazard = $task_item[1];
            if($task != '') {
                echo '<tr>';
                echo '<td data-title="Email">' . $task . '</td>';
                echo '<td data-title="Email"><a href="download/'.$hazard.'" target="_blank">' . $hazard . '</a></td>';
                echo '</tr>';
            }
        }
        echo '</table>';
            }
?>

<br>
<h4>
<input type="checkbox" style="height: 20px; width: 20px;" value="1" required name="agenda_send_email">&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br><br>
Date : <?php echo date('Y-m-d'); ?><br>
Person : <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?><br><br>
</h4>

<?php include ('../phpsign/sign.php'); ?>