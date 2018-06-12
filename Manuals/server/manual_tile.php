<?php
include ('../include.php');
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">

        <?php
        $type = $_GET['type'];

        if($type == 'policy_procedures') {
            echo '<div class="ffmbtnpic dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="policy_procedures.php?contactid='.$_SESSION['contactid'].'">Dashboard</a></div>';
            echo '<div class="ffmbtnpic dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="manual_follow_up.php?type=policy_procedures">Follow Up</a></div>';
        }
        if($type == 'operations_manual') {
            echo '<div class="ffmbtnpic dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="operations_manual.php?contactid='.$_SESSION['contactid'].'">Dashboard</a></div>';
            echo '<div class="ffmbtnpic dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="manual_follow_up.php?type=operations_manual">Follow Up</a></div>';
        }
        if($type == 'emp_handbook') {
            echo '<div class="ffmbtnpic dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="emp_handbook.php?contactid='.$_SESSION['contactid'].'">Dashboard</a></div>';
            echo '<div class="ffmbtnpic dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="manual_follow_up.php?type=emp_handbook">Follow Up</a></div>';
        }
        if($type == 'guide') {
            echo '<div class="ffmbtnpic dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="guide.php?contactid='.$_SESSION['contactid'].'">Dashboard</a></div>';
            echo '<div class="ffmbtnpic dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="manual_follow_up.php?type=guide">Follow Up</a></div>';
        }
  	    ?>

	</div>
</div>

<?php include ('../footer.php'); ?>