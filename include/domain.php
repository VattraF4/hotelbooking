<?php
$Domain = $_SERVER['HTTP_HOST'];
if (strpos($Domain, "localhost") !== false) { //stringPos is a function that returns the position of a string
    define("APP_URL", "/hotelbooking/"); //localhost/hotelbooking
} else {
    define("APP_URL", "https://$Domain/hotelbooking/"); //www.example.com
}
?>