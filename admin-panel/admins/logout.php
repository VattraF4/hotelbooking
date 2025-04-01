<?php
session_start(); //start the session
session_unset(); //unset the session
session_destroy(); //destroy the session
require '../layouts/adminURL.php'; //include the adminURL.php';
header("Location: " . ADMIN_URL . "admins/login-admins.php");
exit();
?>