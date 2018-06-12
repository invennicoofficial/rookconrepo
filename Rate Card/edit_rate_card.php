<?php if($_POST['type'] == 'Company') {
	echo "<script>window.location.replace('rate_card.php?card=company');</script>";
} else if($_POST['type'] == 'Customer') {
	echo "<script>window.location.replace('rate_card.php?card=customer');</script>";
} else if($_POST['type'] == 'Scope Templates') {
	include('estimate_scope.php');
} else if($_POST['type'] == 'Positions') {
	echo "<script>window.location.replace('rate_card.php?card=position');</script>";
} else if($_POST['type'] == 'Staff') {
	echo "<script>window.location.replace('rate_card.php?card=staff');</script>";
} else if($_POST['type'] == 'Equipment') {
	echo "<script>window.location.replace('rate_card.php?card=equipment');</script>";
} else if($_POST['type'] == 'Equipment Category') {
	echo "<script>window.location.replace('rate_card.php?card=category');</script>";
} else if($_POST['type'] == 'Services') {
	echo "<script>window.location.replace('rate_card.php?card=services');</script>";
} ?>