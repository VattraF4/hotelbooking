<?php
require 'layouts/header.php';
?>
<?php require '../config/config.php'; ?>

<?php
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
  exit;
}

$adminName = $_SESSION['adminname'];

// Basic counts
$rooms = $conn->prepare("SELECT * FROM rooms");
$rooms->execute();
$roomsCount = $rooms->rowCount();

$hotels = $conn->prepare("SELECT * FROM hotels");
$hotels->execute();
$hotelsCount = $hotels->rowCount();

$admins = $conn->prepare("SELECT * FROM admin");
$admins->execute();
$adminsCount = $admins->rowCount();

$allBookings = $conn->prepare("SELECT * FROM bookings");
$allBookings->execute();
$allBookingsCount = $allBookings->rowCount();

$allUser = $conn->prepare("SELECT * FROM user");
$allUser->execute();
$allUserCount = $allUser->rowCount();

$allArchive = $conn->prepare("SELECT * FROM hotels_archive");
$allArchive->execute();
$allArchiveCount = $allArchive->rowCount();

// Financial statistics
$totalRevenue = $conn->prepare("SELECT SUM(payment) as total FROM bookings WHERE status = 'done'");
$totalRevenue->execute();
$totalRevenueResult = $totalRevenue->fetchAll(PDO::FETCH_ASSOC);
$totalRevenue = $totalRevenueResult ? $totalRevenueResult[0]['total'] : 0;

$pendingPayments = $conn->prepare("SELECT SUM(payment) as total FROM bookings WHERE status = 'pending'");
$pendingPayments->execute();
$pendingPaymentsResult = $pendingPayments->fetchAll(PDO::FETCH_ASSOC);
$pendingPayments = $pendingPaymentsResult ? $pendingPaymentsResult[0]['total'] : 0;

$paidPayments = $conn->prepare("SELECT SUM(payment) as total FROM bookings WHERE status = 'paid'");
$paidPayments->execute();
$paidPaymentsResult = $paidPayments->fetchAll(PDO::FETCH_ASSOC);
$paidPayments = $paidPaymentsResult ? $paidPaymentsResult[0]['total'] : 0;

$confirmedPayments = $conn->prepare("SELECT SUM(payment) as total FROM bookings WHERE status = 'confirmed'");
$confirmedPayments->execute();
$confirmedPaymentsResult = $confirmedPayments->fetchAll(PDO::FETCH_ASSOC);
$confirmedPayments = $confirmedPaymentsResult ? $confirmedPaymentsResult[0]['total'] : 0;

// Booking status counts
$doneBookings = $conn->prepare("SELECT COUNT(*) as count FROM bookings WHERE status = 'done'");
$doneBookings->execute();
$doneBookings = $doneBookings->fetchAll(PDO::FETCH_ASSOC);
$doneBookings = $doneBookings[0]['count'];

$pendingBookings = $conn->prepare("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'");
$pendingBookings->execute();
$pendingBookings = $pendingBookings->fetchAll(PDO::FETCH_ASSOC);
$pendingBookings = $pendingBookings[0]['count'];

$confirmedBookings = $conn->prepare("SELECT COUNT(*) as count FROM bookings WHERE status = 'confirmed'");
$confirmedBookings->execute();
$confirmedBookings = $confirmedBookings->fetchAll(PDO::FETCH_ASSOC);
$confirmedBookings = $confirmedBookings[0]['count'];
?>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    
    <h3 class="section-title">Admin Dashboard</h3>
    <div class="text-muted">Welcome back, <?php echo htmlspecialchars($adminName); ?></div>
  </div>

  <!-- Summary Section -->
  <div class="row">
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Hotels</div>
        <div class="card-body text-center">
          <div class="stat-value text-primary"><?php echo $hotelsCount; ?></div>
          <p class="text-muted">Total Hotels Registered</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Rooms</div>
        <div class="card-body text-center">
          <div class="stat-value text-primary"><?php echo $roomsCount; ?></div>
          <p class="text-muted">Total Rooms Available</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Admins</div>
        <div class="card-body text-center">
          <div class="stat-value text-primary"><?php echo $adminsCount; ?></div>
          <p class="text-muted">System Administrators</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Financial Overview -->
  <h4 class="section-title mt-5">Financial Overview</h4>
  <div class="row">
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Total Revenue</div>
        <div class="card-body text-center">
          <div class="stat-value revenue">$<?php echo number_format($totalRevenue, 2); ?></div>
          <p class="text-muted">Completed Transactions</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Pending Payments</div>
        <div class="card-body text-center">
          <div class="stat-value pending">$<?php echo number_format($pendingPayments, 2); ?></div>
          <p class="text-muted">Awaiting Confirmation</p>
        </div>
      </div>
    </div>
 
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Paid Payments</div>
        <div class="card-body text-center">
          <div class="stat-value pending">$<?php echo number_format($paidPayments, 2); ?></div>
          <p class="text-muted">Awaiting Confirmation</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Confirmed Payments</div>
        <div class="card-body text-center">
          <div class="stat-value confirmed">$<?php echo number_format($confirmedPayments, 2); ?></div>
          <p class="text-muted">Pending Completion</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Booking Status -->
  <h4 class="section-title mt-5">Booking Status</h4>
  <div class="row">
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Total Bookings</div>
        <div class="card-body text-center">
          <div class="stat-value"><?php echo $allBookingsCount; ?></div>
          <p class="text-muted">All Bookings</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Pending</div>
        <div class="card-body text-center">
          <div class="stat-value pending"><?php echo $pendingBookings; ?></div>
          <p class="text-muted">Awaiting Confirmation</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Completed</div>
        <div class="card-body text-center">
          <div class="stat-value done"><?php echo $doneBookings; ?></div>
          <p class="text-muted">Finished Stays</p>
        </div>
      </div>
    </div>
  </div>

  <!-- User and Archive Stats -->
  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card dashboard-card">
        <div class="card-header">Registered Users</div>
        <div class="card-body text-center">
          <div class="stat-value"><?php echo $allUserCount; ?></div>
          <p class="text-muted">Total System Users</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-6">
      <div class="card dashboard-card">
        <div class="card-header">Archived Hotels</div>
        <div class="card-body text-center">
          <div class="stat-value"><?php echo $allArchiveCount; ?></div>
          <p class="text-muted">In Recycle Bin</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Popular Bookings -->
  <h4 class="section-title mt-5">Popular Bookings</h4>
  <div class="row">
    <?php
    $stmt = "SELECT r.name, hotel_name, COUNT(*) AS frequency
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
      <div class="card dashboard-card">
        <div class="card-header">Top Rooms</div>
        <div class="card-body">
          <?php foreach ($results as $result) { ?>
            <div class="d-flex justify-content-between mb-2">
              <span><?php echo htmlspecialchars($result->name); ?></span>
              <span class="badge badge-primary"><?php echo $result->frequency; ?> bookings</span>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Top Hotels</div>
        <div class="card-body">
          <?php foreach ($results as $result) { ?>
            <div class="d-flex justify-content-between mb-2">
              <span><?php echo htmlspecialchars($result->hotel_name); ?></span>
              <span class="badge badge-primary"><?php echo $result->frequency; ?> bookings</span>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>

    <?php
    $stmt = "SELECT b.*, u.username, count(*) as frequency
              FROM bookings b
              JOIN user u ON b.user_id = u.id
              GROUP BY b.user_id
              ORDER BY frequency DESC
              LIMIT 2;";
    $stmt = $conn->prepare($stmt);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    ?>
    
    <div class="col-md-4">
      <div class="card dashboard-card">
        <div class="card-header">Top Users</div>
        <div class="card-body">
          <?php foreach ($results as $result) { ?>
            <div class="d-flex justify-content-between mb-2">
              <span><?php echo htmlspecialchars($result->full_name); ?></span>
              <span class="badge badge-primary"><?php echo $result->frequency; ?> bookings</span>
            </div>
            <small class="text-muted">User ID: <?php echo $result->id; ?></small>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
require 'layouts/footer.php';
?>