<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../vendor/autoload.php';
require '../config/config.php';
require '../include/domain.php';

use Spatie\Browsershot\Browsershot;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . APP_URL . "auth/login.php");
    exit;
}

// Get booking ID
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($booking_id <= 0) {
    die("Invalid booking ID");
}

// Construct FULL URL for the receipt
$receiptUrl = APP_URL . "users/receipt.php?id=" . $booking_id . "&pdf=true";

// Verify the URL is accessible
$headers = @get_headers($receiptUrl);
if ($headers && strpos($headers[0], '200') === false) {
    die("Error: Cannot access receipt URL. Please check your APP_URL configuration.");
}

try {
    // Generate PDF
    $pdf = Browsershot::url($receiptUrl)
        ->setNodeBinary('C:\Program Files\nodejs\node.exe') // Adjust for your server
        ->setNpmBinary('C:\Program Files\nodejs\npm.cmd') // For Windows
        ->setChromePath('C:\Program Files (x86)\Google\Chrome\Application\chrome.exe') // For Windows
        ->showBackground()
        ->margins(10, 15, 10, 15) // Top, Right, Bottom, Left in mm
        ->format('A4')
        ->timeout(60) // Increase timeout if needed
        ->pdf();

    // Output PDF to browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="Booking_Receipt_'.$booking_id.'.pdf"');
    echo $pdf;
    
} catch (Exception $e) {
    die("Error generating PDF: " . $e->getMessage());
}
?>