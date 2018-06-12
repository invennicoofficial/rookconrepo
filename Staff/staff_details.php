<?php include('../include.php');
checkAuthorised('staff');
$staff = filter_var($_GET['staffid'], FILTER_SANITIZE_STRING);
$ticket = filter_var($_GET['ticketid'], FILTER_SANITIZE_STRING);
$ticket = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `start_time`, `end_time` FROM `tickets` WHERE `ticketid`='$ticket'")); ?>
</head>
<body>
	<div class="form-horizontal">
		<?php $staff = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `primary_contact`, `office_phone`, `home_phone`, `cell_phone`, `role` FROM `contacts` WHERE `contactid`='$staff'")); ?>
		<div class="form-group">
			<label class="col-sm-4"><?= $staff['primary_contact'] == 'O' ? 'Office' : ($staff['primary_contact'] == 'H' ? 'Home' : 'Cell') ?> #:</label>
			<div class="col-sm-8"><?= decryptIt($staff['primary_contact'] == 'O' ? $staff['office_phone'] : ($staff['primary_contact'] == 'H' ? $staff['home_phone'] : $staff['cell_phone'])) ?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-4">Position:</label>
			<div class="col-sm-8">
				<?php foreach(array_filter(explode(',',$staff['role'])) as $role) {
					echo get_securitylevel($dbc, $role)."<br />\n";
				} ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4">Start Time:</label>
			<div class="col-sm-8"><?= $ticket['start_time'] ?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-4">End Time:</label>
			<div class="col-sm-8"><?= $ticket['end_time'] ?></div>
		</div>
	</div>
</body>