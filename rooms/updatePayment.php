<?php
require '../include/header.php';
require '../config/config.php';
require '../auth/Mailer.php';

if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    echo "<script>window.location.href='../auth/logout.php';</script>";
    exit;

}



// Step 2: Update payment status
if (isset($_GET['status']) && $_GET['status'] === 'success' && isset($_SESSION['booking_id'])) {
    $bookingId = $_SESSION['booking_id'];
    $update = $conn->prepare("UPDATE bookings SET status = 'paid' WHERE id = '$bookingId'");
    $update->execute();
    unset($_SESSION['booking_id']);

    //   <!-- Grapping Payment  and BookingID-->

    $Book = $conn->prepare("SELECT * FROM bookings WHERE id = '$bookingId'");
    $Book->execute();
    $Book = $Book->fetchAll(PDO::FETCH_OBJ);


    $user_email = $conn->prepare("SELECT email FROM user WHERE username = '" . $_SESSION['username'] . "'");
    $user_email->execute();
    $user_email = $user_email->fetchColumn();

    $subject = "Payment Receipt";
    $message = "<html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            h2 { color: #333; }
                            p { margin: 5px 0; color: #555; }
                        </style>
                    </head>
                    <body>
                        <h2>Thank you for booking!</h2>
                        <p><strong>Room Name:</strong> " . htmlspecialchars($Book[0]->room_name) . "</p>
                        <p><strong>Hotel Name:</strong> " . htmlspecialchars($Book[0]->hotel_name) . "</p>
                        <p><strong>Check-in Date:</strong> " . htmlspecialchars($Book[0]->check_in) . "</p>
                        <p><strong>Check-out Date:</strong> " . htmlspecialchars($Book[0]->check_out) . "</p>
                        <p><strong>Total Amount:</strong> $" . number_format($Book[0]->payment, 2) . "</p>
                        <p><strong>Status:</strong> " . htmlspecialchars($Book[0]->status) . "</p>
                    </body>
                </html>";

    // sendEmail($user_email, $subject, $message);
    sendEmail($user_email, $subject, $message);
    echo '<script>alert("Paid Successfully");window.location.href="' . APP_URL . '";</script>';
} else {
    echo '<script>alert("Payment Failed");window.location.href="' . APP_URL . '";</script>';
}

