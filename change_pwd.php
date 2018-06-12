<?php
/*
Change Password
*/
include('include.php');

$set_email = 0;
if (isset($_POST['submit'])) {
    $error = array();//this aaray will store all error messages

    if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#",$_POST['new_pwd'])){
       // $error[] = "Your password is strong.";
    } else {
        $error[] = "Your new password is not safe.";
        $set_email = 1;
    }

    if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#",$_POST['confirm_pwd'])){
       // $error[] = "Your password is strong.";
    } else {
        $error[] = "Your confirm password is not safe.";
        $set_email = 1;
    }

	if (empty($error)) {
		$email = $_POST['email'];
		$old_pwd = $_POST['old_pwd'];
		$new_pwd = $_POST['new_pwd'];
		$confirm_pwd = $_POST['confirm_pwd'];

		$query_check_credentials = "SELECT contactid FROM contacts WHERE email_address='$email' AND password='$old_pwd'";
        $result_check_credentials = mysqli_query($dbc, $query_check_credentials);
        if(!$result_check_credentials) {//If the QUery Failed
            echo 'Query Failed ';
        }

		if (@mysqli_num_rows($result_check_credentials) == 1) { //if Query is successfull
			 if($new_pwd == $confirm_pwd) {
				$file_name = 'index.php';
				echo "Password sucessfully changed. Login from here.<a href=".$file_name.">Log in</a>";
				// Update member password
                $updated_at = date('Y-m-d');
				$query_update_user = "UPDATE `staff` SET password='$new_pwd', updated_at='$updated_at' where email_address='$email'";
				$result_update_user = mysqli_query($dbc, $query_update_user);
			 } else {
				 echo "New and confirm password not match";
				 $set_email = 1;
			 }
		} else {
			echo "User not available with this email and password.";
			$set_email = 1;
		}

    } else {//If the "error" array contains error msg , display them
		 //  if (!empty($error)) {
		echo '<div class="errormsgbox"> <ol>';
		foreach ($error as $key => $values) {
			echo '	<li>'.$values.'</li>';
		}
		echo '</ol></div>';
    }
   // mysqli_close($dbc);//Close the DB Connection
} // End of the main Submit conditional.

?>
</head>
<body>
<?php include_once ('navigation.php'); ?>
<div class="container">
  <div class="row">

    <form method="post" name="change" action="change_pwd.php" class="col-sm-6 col-sm-offset-3 form-horizontal" role="form">

    <h3>Change Password</h3>

      <div class="form-group">
        <label for="email" class="col-sm-4 control-label">Email:</label>
    	  <?php
    	  $email_val = '';
    		if($set_email == 1) {
    			$email_val = $_POST['email'];
    	  }
    	  ?>
        <div class="col-sm-8">
          <input type="text" id="email" name="email" size="25" required value="<?php echo $email_val; ?>" class="form-control">
        </div>
      </div>

      <div class="form-group">
        <label for="Password" class="col-sm-4 control-label">Old Password: </label>
        <div class="col-sm-8">
          <input type="password" id="old_pwd" name="old_pwd" required size="25" class="form-control" />
        </div>
      </div>
      <div class="form-group">
        <label for="Password" class="col-sm-4 control-label">New Password: </label>
        <div class="col-sm-8">
          <input type="password" id="new_pwd" name="new_pwd" required size="25" class="form-control" />
        </div>
      </div>
      <div class="form-group">
          <label for="Password" class="col-sm-4 control-label">Confirm Password: </label>
        <div class="col-sm-8">
          <input type="password" id="confirm_pwd" name="confirm_pwd" required size="25" class="form-control" />
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-4 clearfix">
            <a href="home.php" class="btn brand-btn pull-right">Back</a>
        </div>
        <div class="col-sm-8">
            <button type="submit" name="submit" value="Change Password" class="btn brand-btn btn-lg pull-right">Submit</button>
        </div>
      </div>

    </form>
  </div>
</div>

<?php include ('footer.php'); ?>