<?php if($_POST['submit'] == 'update') {
	if($_POST['password_one'] == $_POST['password_two']) {
		$password = encryptIt($_POST['password_one']);
		mysqli_query($dbc, "UPDATE `contacts` SET `password`='$password', `password_update`='0', `password_date`=CURRENT_TIMESTAMP WHERE `contactid` > 0 AND `contactid`='".$_SESSION['contactid']."' AND `deleted`=0 AND `status`>0");
		
		session_start(['cookie_lifetime' => 518400]);
		$_SERVER['page_load_info'] .= 'Session Started: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
		$_SESSION['password_update'] = 0;
		session_write_close();
	} else {
		echo "<script> alert('Passwords did not match!'); </script>";
	}
}
if($_SESSION['password_update'] == 1) { ?>
	<div class="container">
		<div class="row">
			<h1>You are required to change your password before proceeding.</h1>
			<form class="form-horizontal" action="" method="POST">
				<span style="display:none;"><input type="password" name="password" value="" readonly></span>
				<div class="form-group">
					<label class="col-sm-4 control-label">User Name:</label>
					<div class="col-sm-8">
						<input type="text" name="user_name" value="<?= $_SESSION['user_name'] ?>" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Enter Password:</label>
					<div class="col-sm-8">
						<input type="password" name="password_one" value="" class="form-control" autocomplete="new-password" required minlength="5">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Re-Enter Password:</label>
					<div class="col-sm-8">
						<input type="password" name="password_two" value="" class="form-control" autocomplete="new-password" required minlength="5">
					</div>
				</div>
				<button class="btn brand-btn pull-right" type="submit" name="submit" value="update">Update Password</button>
			</form>
		</div>
	</div>
	<?php include('footer.php');
} ?>