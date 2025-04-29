<?php
require "config/config.php";

define('SQL_SCRIPT_FILE', 'database/Hotel-Booking.sql'); // Path to your SQL file

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Verify config.php provided the required constants
    if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_PASS') || !defined('DB_NAME')) {
        throw new Exception("Required database constants are not defined in config.php");
    }

    // Check if SQL script file exists and is readable
    if (!file_exists(SQL_SCRIPT_FILE)) {
        throw new Exception("SQL script file not found at: " . realpath(SQL_SCRIPT_FILE));
    }
    
    if (!is_readable(SQL_SCRIPT_FILE)) {
        throw new Exception("SQL script file is not readable: " . SQL_SCRIPT_FILE);
    }

    // Connect to MySQL server
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if it doesn't exist
    if ($conn->query("CREATE DATABASE IF NOT EXISTS `" . $conn->real_escape_string(DB_NAME) . "`") === FALSE) {
        throw new Exception("Error creating database: " . $conn->error);
    }
    
    // Select the database
    $conn->select_db(DB_NAME);

    // Read the SQL file
    $sql = file_get_contents(SQL_SCRIPT_FILE);
    
    if ($sql === FALSE) {
        throw new Exception("Failed to read SQL script file");
    }

    // Execute multiple queries
    if ($conn->multi_query($sql)) {
        echo "Database setup in progress...<br>";
        
        // Loop through all results to clear the buffer
        $count = 0;
        do {
            $count++;
            if ($result = $conn->store_result()) {
                $result->free();
            }
            
            if (!$conn->more_results()) break;
            
        } while ($conn->next_result());
        
        echo "Database setup completed successfully!<br>";
        echo "Executed $count queries from the SQL file.";
    } else {
        throw new Exception("Error executing SQL script: " . $conn->error . 
                          "<br>Error occurred near: " . getQueryPosition($sql, $conn->error));
    }
    
} catch (Exception $e) {
    die("<div style='color:red;'><strong>Database setup failed:</strong> " . $e->getMessage() . "</div>");
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}

/**
 * Helper function to find the approximate position in the SQL file where error occurred
 */
function getQueryPosition($fullSql, $error) {
    $errorLines = explode("\n", $error);
    $firstErrorLine = $errorLines[0];
    
    // Try to find the exact query that failed
    $queries = explode(";", $fullSql);
    foreach ($queries as $query) {
        if (strpos($query, trim($firstErrorLine)) !== false) {
            return substr($query, 0, 100) . "...";
        }
    }
    
    return "Unable to locate exact position";
}
?>