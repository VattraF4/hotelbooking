<?php
session_start(); //start the session
session_unset(); //unset the session
session_destroy(); //destroy the session
$URL = "http://localhost";
define("APP_URL", $URL . '/hotel-booking/');
header("Location: " . APP_URL . "");
?>