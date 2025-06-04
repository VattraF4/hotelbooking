<?php
require '../config/config.php';
require '../include/header.php';

// Check login user
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href = '" . APP_URL . "auth/login.php';</script>";
    exit;
}

// Validate and sanitize user input
$user_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($user_id <= 0) {
    header("Location: ../error");
    exit;
}

// Get user bookings using prepared statement
$getBooking = $conn->prepare("SELECT * FROM bookings WHERE user_id = :user_id ORDER BY create_at DESC");
$getBooking->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$getBooking->execute();

if ($getBooking->rowCount() == 0) {
    echo '<div class="alert alert-info mt-4">No bookings found.</div>';
    require '../include/footer.php';
    exit;
}

$booking = $getBooking->fetchAll(PDO::FETCH_OBJ);
?>
<!-- Add Font Awesome below header -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" /> -->


<div class="container-fluid mt-4">
    <div class="alert alert-warning text-center">
        <h4><i class="fa fa-exclamation-triangle"></i> All pending payments will be automatically canceled after 48
            hours</h4>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Your Bookings</h5>
            <div>
                <span class="badge badge-success">Completed:
                    <?= count(array_filter($booking, fn($b) => $b->status === 'completed')) ?></span>
                <span class="badge badge-warning ml-2">Pending:
                    <?= count(array_filter($booking, fn($b) => $b->status === 'pending')) ?></span>
                <span class="badge badge-info ml-2">Paid:
                    <?= count(array_filter($booking, fn($b) => $b->status === 'paid')) ?></span>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="bookingsTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Room</th>
                            <th>Hotel</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Nights</th>
                            <th>Actions</th>
                            <th>Booked On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($booking as $index => $bookingItem):
                            $checkIn = new DateTime($bookingItem->check_in);
                            $checkOut = new DateTime($bookingItem->check_out);
                            $nights = $checkIn->diff($checkOut)->days;
                            ?>
                            <tr class="<?= $bookingItem->status === 'pending' ? 'table-warning' : 'table-success' ?>">
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($bookingItem->room_name) ?></td>
                                <td><?= htmlspecialchars($bookingItem->hotel_name) ?></td>
                                <td>$<?= number_format($bookingItem->payment, 2) ?></td>
                                <td>
                                    <span
                                        class="badge badge-<?= $bookingItem->status === 'completed' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($bookingItem->status) ?>
                                    </span>
                                </td>
                                <td><?= $checkIn->format('M j, Y') ?></td>
                                <td><?= $checkOut->format('M j, Y') ?></td>
                                <td><?= $nights ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <?php if ($bookingItem->status === 'pending'): ?>
                                            <!-- If booking is pending user can edit and delete -->
                                            <!-- Always allow viewing the 3 icon when status not pending -->
                                            <a href="<?= APP_URL ?>users/edit-booking.php?id=<?= $bookingItem->id ?>"
                                                class="btn btn-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a href="delete-booking.php?booking_id=<?= $bookingItem->id ?>"
                                                class="btn btn-danger" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this booking?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <a href="receipt.php?id=<?= $bookingItem->id ?>" class="btn btn-info"
                                                title="View Receipt">
                                                <i class="fa fa-file-text-o"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= APP_URL ?>users/edit-booking.php?id=<?= $bookingItem->id ?>"
                                                class="btn btn-primary disabled" title="Edit">
                                                <i class="fa fa-edit" style="opacity:0.5;"></i>
                                            </a>

                                            <a href="delete-booking.php?booking_id=<?= $bookingItem->id ?>"
                                                class="btn btn-danger disabled" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this booking?')">
                                                <i class="fa fa-trash" style="opacity:0.5;"></i>
                                            </a>

                                            <a href="generate-pdf.php?id=<?= $bookingItem->id ?>" class="btn btn-info"
                                                title="Download Receipt">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </a>


                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?= (new DateTime($bookingItem->create_at))->format('M j, Y g:i A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function () {
        $('#bookingsTable').DataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 }
            ],
            order: [[9, 'desc']]
        });
    });
</script>

<?php
require '../include/footer.php';
?>