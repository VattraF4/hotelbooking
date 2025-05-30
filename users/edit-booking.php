<?php
require '../config/config.php';
require '../include/header.php';
echo $_SESSION['user_id'];
// Check if user is logged in

if (!isset($_SESSION['username'])) {
    // echo "<script>window.location.href='" . APP_URL . "auth/login.php';</script>";
    exit;
}

// Get booking ID from URL
if(!isset($_GET['id'])){
    // echo "<script>window.location.href='../error';</script>";
}else{
    $booking_id = $_GET['id'];
}

if ($booking_id <= 0) {
    // echo "<script>window.location.href='../error';</script>"; // Redirect to error page using JavaScript
    exit;
}

// Fetch booking details
$booking = $conn->prepare("SELECT * FROM bookings WHERE id = :id AND user_id = :user_id");
$booking->bindParam(':id', $booking_id, PDO::PARAM_INT);
$booking->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$booking->execute();

if ($booking->rowCount() == 0) {
    // echo "<script>window.location.href='../error';</script>"; // Redirect to error page using JavaScript
    exit;
}

$bookingData = $booking->fetchAll(PDO::FETCH_OBJ);
?>
    <!-- Add Font Awesome below header -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />


<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="fas fa-edit"></i> Edit Booking</h4>
        </div>
        <div class="card-body">
            <form action="../rooms/payment.php?booking_id=<?= $booking_id ?>" method="POST">
                <input type="hidden" name="booking_id" value="<?= $bookingData[0]->id ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Room</label>
                        <input type="text" class="form-control"
                            value="<?= htmlspecialchars($bookingData[0]->room_name) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hotel</label>
                        <input type="text" class="form-control"
                            value="<?= htmlspecialchars($bookingData[0]->hotel_name) ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Check-In</label>
                        <input type="date" name="check_in" class="form-control" value="<?= $bookingData[0]->check_in ?>"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Check-Out</label>
                        <input type="date" name="check_out" class="form-control"
                            value="<?= $bookingData[0]->check_out ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Payment</label>
                        <input type="text" class="form-control"
                            value="$<?= number_format($bookingData[0]->payment, 2) ?>" readonly>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="booking.php?id=<?= $_SESSION['user_id'] ?>" class="btn btn-secondary me-md-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require '../include/footer.php'; ?>