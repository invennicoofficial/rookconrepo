<?php
include('../include.php');
?>
</head>
<body>
<?php 
include_once ('../navigation.php');
checkAuthorised('daily_log_notes');
include 'config.php';
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

			<h1 class="">Reporting</h1>

			<?php echo get_tabs_log_notes('Reporting'); ?>
			<?php include('reports.php'); ?>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
