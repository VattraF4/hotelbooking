<?php
require '../config/config.php';
require '../include/header.php';
?>
<?php
//check login user
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APP_URL . "auth/login.php';</script>";
    exit;
}
//Grapping User ID
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $getBooking = $conn->prepare("SELECT * FROM bookings WHERE user_id = '$user_id'");
    // $getBooking->bindParam(':user_id', $user_id);
    $getBooking->execute();
    $booking = $getBooking->fetchAll(PDO::FETCH_OBJ);
} else {
    echo '<script>window.location.href="../error";</script>';
    exit;
}
if ($getBooking->rowCount() == 0) {
    echo "<script>window.location.href='../error/';</script>";
    exit;
}
?>
<div class="alert alert-danger text-center" role="alert">
    <h1>All Pending Payment Will Be Deleted Permanently After 48 Hours</h1>
</div>

<style>
    table td,
    table th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    table {
        margin-left: auto;
        margin-right: auto;
    }

    }

    /* .table-responsive-sm {
        overflow-x: auto;
    } */
</style>
<div class="table-responsive-sm" style="width: 100vw;">
    <table class="table table-striped table-responsive table-bordered table-hover " style="width: 100vw;">
        <thead thead style="background-color:#007BFF; color: white;">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Room</th>
                <th scope="col">Hotel</th>
                <th scope="col">Payment</th>
                <th scope="col">Phone</th>
                <th scope="col">Status</th>
                <th scope="col">Check in</th>
                <th scope="col">Check</th>
                <th scope="col">Email</th>
                <th scope="col">Booking Date</th>

            </tr>
        </thead>
        <tbody>
            <?php $i = 0;
            foreach ($booking as $allBooking):
                $i++;
                ?>
                <tr>
                    <th scope="row"><?php echo $i ?></th>
                    <th scope="row"><?php echo $allBooking->room_name; ?></th>
                    <th scope="row"><?php echo $allBooking->hotel_name; ?></th>
                    <th scope="row"><?php echo $allBooking->payment; ?></th>
                    <th scope="row"><?php echo $allBooking->phone_number; ?></th>
                    <th scope="row"><?php echo $allBooking->status; ?></th>
                    <th scope="row"><?php echo $allBooking->check_in; ?></th>
                    <th scope="row"><?php echo $allBooking->check_out; ?></th>
                    <th scope="row"><?php echo $allBooking->email; ?></th>
                    <th scope="row"><?php echo $allBooking->create_at; ?></th>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require '../include/footer.php';
?>