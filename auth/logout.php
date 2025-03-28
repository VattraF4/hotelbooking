<?php
session_start(); //start the session
session_unset(); //unset the session
session_destroy(); //destroy the session
require '../include/domain.php';
header("Location: " . APP_URL . "auth/login.php");
exit();
?>