<?php
session_start(); //start the session
session_unset(); //unset the session
session_destroy(); //destroy the session
$Domian = "http://localhost";
define('APP_URL',"$Domian/hotel-booking" );
header("Location: " . APP_URL . "");
?>