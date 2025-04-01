<?php
require '../include/header.php';
require '../config/config.php';
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    echo "<script>window.location.href='../auth/logout.php';</script>";
    exit;

}



// Step 2: Update payment status
if (isset($_GET['status']) && $_GET['status'] === 'success' && isset($_SESSION['booking_id'])) {
    $bookingId= $_SESSION['booking_id'];
    $update = $conn->prepare("UPDATE bookings SET status = 'paid' WHERE id = '$bookingId'");
    $update->execute();
    unset($_SESSION['booking_id']);
    echo '<script>alert("Paid Successfully");window.location.href="' . APP_URL . '";</script>';
    
}else{
   echo '<script>alert("Payment Failed");window.location.href="' . APP_URL . '";</script>';
}

