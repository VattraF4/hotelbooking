<?php
require "include/domain.php";
if (!isset($_SERVER['HTTP_REFERER'])) {
  // redirect them to your desired location
  header('location: ' . APP_URL . '/auth/logout.php');
  exit;

}
?>