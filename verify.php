<?php 
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Test if variables are loaded
echo 'DB Host: ' . $_ENV['DB_HOST_LOCAL'];

?>