<?php
$Domain = $_SERVER['HTTP_HOST'];
if (strpos($Domain, "localhost") !== false) { //stringPos is a function that returns the position of a string
    define("APP_URL", "/hotelbooking/"); //localhost/hotelbooking
} else if(strpos($Domain,"localhost:3000")!==false){
    define("APP_URL","");
}
else {
    define("APP_URL", "https://$Domain/hotelbooking/"); //www.example.com
}
echo APP_URL;
?>