<?php
require 'layouts/header.php';
?>
<?php require '../config/config.php'; ?>

<?php
// if (!isset($_SERVER['HTTP_REFERER'])) {

//   echo "<script>window.location.href='../error/';</script>";
//   exit;

// }
if (!isset($_SESSION['adminname'])) {
  // header('Location: ' . ADMIN_URL . 'admins/login-admins.php');
  echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
  exit;
}

$adminName = $_SESSION['adminname'];

$rooms = $conn->prepare("SELECT * FROM rooms");
$rooms->execute();
$roomsCount = $rooms->rowCount(); //count the number rows of rooms in the database

$hotels = $conn->prepare("SELECT * FROM hotels");
$hotels->execute();
$hotelsCount = $hotels->rowCount(); //count the number rows of hotels in the database

$admins = $conn->prepare("SELECT * FROM admin");
$admins->execute();
$adminsCount = $admins->rowCount(); //count the number rows of admins in the database

$allBookings = $conn->prepare("SELECT * FROM bookings");
$allBookings->execute();
$allBookingsCount = $allBookings->rowCount(); //count the number rows of admins in the database

//user
$allUser = $conn->prepare("SELECT * FROM user");
$allUser->execute();
$allUserCount = $allUser->rowCount(); //count the number rows of admins in the database

//achived hotel
$allArchive = $conn->prepare("SELECT * FROM hotels_archive");
$allArchive->execute();
$allArchiveCount = $allArchive->rowCount(); //count the number rows of admins in the database

?>
<div class="container-fluid">

  <!-- ---------------- -->
  <h5 class="summary-value text-warning">Summary</h5>
  <hr>

  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="summary-value text-success">Hotels</h5>
          <!-- <h6 class="card-subtitle mb-2 text-muted">Bootstrap 4.0.0 Snippet by pradeep330</h6> -->
          <!-- <p class="card-text">number of hotels: <?php echo $hotelsCount ?></p> -->
          <p class="summary-value text-primary">All Hotels: <?php echo $hotelsCount ?></p>

        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="summary-value text-success">Rooms</h5>

          <p class="summary-value text-primary">All Rooms: <?php echo $roomsCount ?></p>
          <!-- <p class="card-text">number of rooms: <?php echo $roomsCount ?></p> -->

        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="summary-value text-success">Admins</h5>

          <p class="summary-value text-primary">All Admins: <?php echo $adminsCount ?></p>
          <!-- <p class="card-text">number of admins: <?php echo $adminsCount ?></p> -->

        </div>
      </div>
    </div>

    <!-- Display the number of booking -->
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="summary-value text-success">All Booking</h5>
          <!-- <p class="card-text">Amount of All Booking: <?php echo $allBookingsCount ?></p> -->
          <p class="summary-value text-primary">Amount of All Booking: <?php echo $allBookingsCount ?></p>
        </div>
      </div>
    </div>

    <!-- Display the number of users -->
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="summary-value text-success">All User</h5>
          <!-- <p class="card-text">Amount of All Booking: <?php echo $allUserCount ?></p> -->
          <p class="summary-value text-primary">Amount of All Booking: <?php echo $allUserCount ?></p>
        </div>
      </div>
    </div>

    <!-- Display the number of Deleted -->
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="summary-value text-success">All Deleted Hotel</h5>
          <!-- <p class="card-text">Amount of All Booking: <?php echo $allArchiveCount ?></p> -->
          <p class="summary-value text-primary">Recycle Bin: <?php echo $allArchiveCount ?></p>
        </div>
      </div>
    </div>

  </div>


  <!-- ---------------- -->
  <hr>
  <h5 class="summary-value text-warning">Popular Booking</h5>
  <hr>
  <div class="row">
    <?php
    $stmt = "SELECT r.name,hotel_name, COUNT(*) AS frequency
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    JOIN hotels h ON r.hotel_id = h.id
    GROUP BY b.room_id
    ORDER BY frequency DESC
    LIMIT 2;";
    $stmt = $conn->prepare($stmt);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    ?>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="summary-value text-success">Most Booking Room</h5><br>
          <?php foreach ($results as $result) { ?>
            <p class="summary-value text-primary"><?php echo $result->name ?>: <?php echo $result->frequency ?></p>
          <?php } ?>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="summary-value text-success">Most Booking Hotel</h5><br>
          <?php foreach ($results as $result) { ?>
            <p class="summary-value text-primary"><?php echo $result->hotel_name ?>: <?php echo $result->frequency ?></p>
          <?php } ?>
        </div>
      </div>
    </div>

    <?php
    $stmt = "SELECT  b.* , count(*) as frequency FROM bookings b
    JOIN user u ON b.user_id = u.id
    GROUP BY b.user_id
    ORDER BY frequency
    LIMIT  2;";
    $stmt = $conn->prepare($stmt);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    ?>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="summary-value text-success">Most Booking Users</h5><br>
          <?php foreach ($results as $result) { ?>
            <p class="summary-value text-primary"><?php echo $result->full_name ?>: <?php echo $result->frequency ."(ID: ". $result->id .")"?></p>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>



<?php
require 'layouts/footer.php';
?>