<?php
try {


    //host
    define("DB_HOST", "localhost");
    //database name
    define("DB_NAME", "hotel-booking");
    //database user
    define("DB_USER", "root");
    //database password
    define("DB_PASS", "");

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