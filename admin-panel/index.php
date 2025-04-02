<?php 
  require 'layouts/header.php';
?>
<?php require '../config/config.php'; ?>
<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
    exit;
} else {
    $adminName = $_SESSION['adminname'];
}
$rooms = $conn->prepare("SELECT * FROM rooms");
$rooms->execute();
$roomsCount = $rooms->rowCount(); //count the number rows of rooms in the database

$hotels = $conn->prepare("SELECT * FROM hotels"); 
$hotels->execute();
$hotelsCount = $hotels->rowCount(); //count the number rows of hotels in the database

$admins = $conn->prepare("SELECT * FROM admin");
$admins->execute();
$adminsCount = $admins->rowCount(); //count the number rows of admins in the database

?>
<div class="container-fluid">

  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Hotels</h5>
          <!-- <h6 class="card-subtitle mb-2 text-muted">Bootstrap 4.0.0 Snippet by pradeep330</h6> -->
          <p class="card-text">number of hotels: <?php echo $hotelsCount ?></p>

        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Rooms</h5>

          <p class="card-text">number of rooms: <?php echo $roomsCount ?></p>

        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Admins</h5>

          <p class="card-text">number of admins: <?php echo $adminsCount ?></p>

        </div>
      </div>
    </div>
  </div>

  <?php 
  require 'layouts/footer.php';
?>