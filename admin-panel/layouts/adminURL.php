<?php
//Protect multiple use file adminURL
$Domain = $_SERVER['HTTP_HOST'];
if(!defined('ADMIN_URL')){
    
    if (strpos($Domain, "localhost") !== false) { //stringPos is a function that returns the position of a string
        define("ADMIN_URL", "/hotelbooking/admin-panel/"); //localhost/hotelbooking
    }
    else {
        define("ADMIN_URL", "https://$Domain/hotelbooking/admin-panel/"); //www.example.com
    }
}
// echo '<script>console.log("' . APP_URL . '")</script>';

?>