<?php
/*
Logout Button Click
*/
session_start();
session_destroy();
header('location:index.php');
?>