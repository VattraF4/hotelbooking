<?php
require '../config/config.php';
require '../include/header.php';

// Check login user
if (!isset($_SESSION['user_id'])) {
    header("Location: " . APP_URL . "auth/login.php");
    exit;
}

// Validate and sanitize booking ID
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($booking_id <= 0) {
    header("Location: ../error");
    exit;
}

// Get booking details using prepared statement
$getBooking = $conn->prepare("SELECT * FROM bookings WHERE id = :id");
$getBooking->bindParam(':id', $booking_id, PDO::PARAM_INT);
$getBooking->execute();

if ($getBooking->rowCount() == 0) {
    echo '<div class="alert alert-danger mt-4">Booking not found.</div>';
    require '../include/footer.php';
    exit;
}

$booking = $getBooking->fetchAll(PDO::FETCH_OBJ);

// Calculate nights
$checkIn = new DateTime($booking[0]->check_in);
$checkOut = new DateTime($booking[0]->check_out);
$nights = $checkIn->diff($checkOut)->days;

// Check if PDF generation is requested
$is_pdf = isset($_GET['pdf']) && $_GET['pdf'] === 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt #<?= $booking[0]->id ?></title>
    <link href="<?= APP_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>assets/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            <?php if ($is_pdf): ?>
            background-color: white;
            <?php endif; ?>
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            box-shadow: <?= $is_pdf ? 'none' : '0 0 20px rgba(0,0,0,0.1)' ?>;
        }
        .receipt-header {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .receipt-footer {
            border-top: 2px solid #dee2e6;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 0.9em;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .receipt-container, .receipt-container * {
                visibility: visible;
            }
            .receipt-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <div class="row">
                <div class="col-md-6">
                    <h2><?= htmlspecialchars($booking[0]->hotel_name) ?></h2>
                    <p class="text-muted mb-0">Booking Receipt</p>
                </div>
                <div class="col-md-6 text-right">
                    <p class="mb-1"><strong>Receipt #:</strong> <?= str_pad($booking[0]->id, 5, '0', STR_PAD_LEFT) ?></p>
                    <p class="mb-1"><strong>Date:</strong> <?= date('M j, Y') ?></p>
                    <span class="badge badge-<?= 
                        $booking[0]->status === 'completed' ? 'success' : 
                        ($booking[0]->status === 'paid' ? 'primary' : 'warning') 
                    ?>">
                        <?= ucfirst($booking[0]->status) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Guest Information</h5>
                <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($booking[0]->full_name) ?></p>
                <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($booking[0]->email) ?></p>
                <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($booking[0]->phone_number) ?></p>
            </div>
            <div class="col-md-6">
                <h5>Booking Details</h5>
                <p class="mb-1"><strong>Room:</strong> <?= htmlspecialchars($booking[0]->room_name) ?></p>
                <p class="mb-1"><strong>Check-in:</strong> <?= $checkIn->format('M j, Y') ?></p>
                <p class="mb-1"><strong>Check-out:</strong> <?= $checkOut->format('M j, Y') ?></p>
                <p class="mb-1"><strong>Nights:</strong> <?= $nights ?></p>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($booking[0]->room_name) ?> (<?= $nights ?> night<?= $nights > 1 ? 's' : '' ?>)</td>
                        <td class="text-right">$<?= number_format($booking[0]->payment, 2) ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Total</strong></td>
                        <td class="text-right"><strong>$<?= number_format($booking[0]->payment, 2) ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="receipt-footer">
            <div class="alert alert-light">
                <h6>Notes:</h6>
                <ul class="mb-0">
                    <li>This is an official receipt for your booking.</li>
                    <li>Please present this receipt at check-in.</li>
                    <li>For any inquiries, please contact the hotel directly.</li>
                </ul>
            </div>
            <p class="text-center text-muted small mb-0">Thank you for your booking!</p>
        </div>

        <?php if (!$is_pdf): ?>
        <div class="no-print text-center mt-4">
            <a href="<?= APP_URL ?>users/booking.php" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
            <a href="<?= APP_URL ?>users/generate-pdf.php?id=<?= $booking[0]->id ?>" class="btn btn-primary">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
        </div>
        <?php endif; ?>
    </div>

    <?php if (!$is_pdf) require '../include/footer.php'; ?>
</body>
</html>