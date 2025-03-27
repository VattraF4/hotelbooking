<?php
try {
    // Check if running on localhost
    if ($_SERVER['HTTP_HOST'] === 'localhost') {
        // Local Development
        define("DB_HOST", "localhost");
        define("DB_NAME", "e4g7wad_hotel-booking"); // Your local DB name
        define("DB_USER", "root");
        define("DB_PASS", "");
    } else {
        //host
        // define("DB_HOST", "bh-34.webhostbox.net");
        define("DB_HOST", "bh-34.webhostbox.net");
        //database name
        define("DB_NAME", "e4g7wad_hotel-booking");
        //database user
        define("DB_USER", "e4g7wad_root");
        //database password
        define("DB_PASS", "3C]apZ6Fip;x");
    }
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Check connection
    if ($conn == true) {
        // echo "Connected successfully";
    } else {
        echo "Connection failed: ";
    }


    //PDO: PHP Data Objects (PHP Extension) is a database access layer providing a uniform method of access to multiple databases.
//PDO is not an abstraction layer which simply emulates the functionality of other databases;
//  it is a unique layer designed specifically for PHP, that allows developers to write portable code between databases.
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}