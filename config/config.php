<?php
require '../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    // Check if running on localhost
    if ($_SERVER['HTTP_HOST'] === 'localhost') {
        // Local Development
        define("DB_HOST", $_ENV['DB_HOST_LOCAL']);
        define("DB_NAME", $_ENV['DB_NAME_LOCAL']);
        define("DB_USER", $_ENV['DB_USER_LOCAL']);
        define("DB_PASS", $_ENV['DB_PASS_LOCAL']);
    } else {
        // Production
        define("DB_HOST", $_ENV['DB_HOST_PROD']);
        define("DB_NAME", $_ENV['DB_NAME_PROD']);
        define("DB_USER", $_ENV['DB_USER_PROD']);
        define("DB_PASS", $_ENV['DB_PASS_PROD']);
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