<?php
require_once("../inc/init.php");
$_SESSION['admin'] = 0;
unset($_SESSION['admin']);
//redirect to homepage
header('Location: ../');
?>
