<?php
require '../config/config.php';
require '../include/header.php';


// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APP_URL . "auth/login.php';</script>";
    exit;
}

// Get booking ID from URL
$booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

if ($booking_id <= 0) {
    echo "<script>window.location.href='../error';</script>";
    exit;
}

// Verify booking belongs to user
$checkBooking = $conn->prepare("SELECT id FROM bookings WHERE id = :id AND user_id = :user_id");
$checkBooking->bindParam(':id', $booking_id, PDO::PARAM_INT);
$checkBooking->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$checkBooking->execute();

if ($checkBooking->rowCount() == 0) {
    echo "<script>window.location.href='../error';</script>";
    exit;
}

// Delete booking
$delete = $conn->prepare("DELETE FROM bookings WHERE id = :id");
$delete->bindParam(':id', $booking_id, PDO::PARAM_INT);

if ($delete->execute()) {
    $_SESSION['success'] = "Booking deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete booking.";
}

echo "<script>window.location.href='booking.php?id=" . $_SESSION['user_id'] . "';</script>";
exit;