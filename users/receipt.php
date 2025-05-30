<?php
require '../include/header.php';
require '../config/config.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: " . APP_URL . "auth/login.php");
    exit;
}

// Get booking ID
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($booking_id <= 0) {
    header("Location: ../error");
    exit;
}

// Fetch booking details
$booking = $conn->prepare("SELECT * FROM bookings WHERE id = :id AND user_id = :user_id");
$booking->bindParam(':id', $booking_id, PDO::PARAM_INT);
$booking->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$booking->execute();

if ($booking->rowCount() == 0) {
    header("Location: ../error");
    exit;
}

$bookingData = $booking->fetch(PDO::FETCH_OBJ);

// Calculate nights
$checkIn = new DateTime($bookingData->check_in);
$checkOut = new DateTime($bookingData->check_out);
$nights = $checkIn->diff($checkOut)->days;

// Format payment amount
$paymentAmount = number_format($bookingData->payment, 2);

// Format date
$date = (new DateTime($bookingData->create_at))->format('F j, Y');
// require '../include/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt</title>
    <style>
        /* Print-specific styles */
        @media print {
            body * {
                visibility: hidden;
            }
            .print-receipt, .print-receipt * {
                visibility: visible;
            }
            .print-receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: auto;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow print-receipt">
            <div class="card-header bg-success text-white">
                <h4><i class="fas fa-receipt"></i> Booking Receipt #<?= $bookingData->id ?></h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> <?= $date ?></p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-<?= $bookingData->status === 'completed' ? 'success' : 'warning' ?>">
                                <?= ucfirst($bookingData->status) ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h5><?= htmlspecialchars($bookingData->hotel_name) ?></h5>
                        <p><?= htmlspecialchars($bookingData->room_name) ?></p>
                    </div>
                </div>

                <hr>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Booking Details</h5>
                        <p><strong>Check-In:</strong> <?= $checkIn->format('F j, Y') ?></p>
                        <p><strong>Check-Out:</strong> <?= $checkOut->format('F j, Y') ?></p>
                        <p><strong>Nights:</strong> <?= $nights ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Payment Details</h5>
                        <p><strong>Per Night:</strong> $<?= number_format($bookingData->payment / $nights, 2) ?></p>
                        <p><strong>Total:</strong> $<?= $paymentAmount ?></p>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end no-print">
                    <a href="bookings.php?id=<?= $_SESSION['user_id'] ?>" class="btn btn-secondary me-md-2">
                        <i class="fa fa-arrow-left"></i> Back to Bookings
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fa fa-print"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php require '../include/footer.php'; ?>
</body>
</html>
