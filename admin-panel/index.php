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

// Initialize all months with 0 revenue
$allMonths = [
  'Jan' => 0,
  'Feb' => 0,
  'Mar' => 0,
  'Apr' => 0,
  'May' => 0,
  'Jun' => 0,
  'Jul' => 0,
  'Aug' => 0,
  'Sep' => 0,
  'Oct' => 0,
  'Nov' => 0,
  'Dec' => 0
];

// Get real monthly revenue data from database
$monthlyRevenueQuery = $conn->prepare("
    SELECT
        DATE_FORMAT(STR_TO_DATE(check_in, '%m/%d/%Y'), '%b') AS month,
        SUM(payment) AS revenue
    FROM bookings
    WHERE status = 'done'
    AND YEAR(STR_TO_DATE(check_in, '%d/%m/%Y')) = YEAR(CURDATE())
    GROUP BY DATE_FORMAT(STR_TO_DATE(check_in, '%d/%m/%Y'), '%m'),
             DATE_FORMAT(STR_TO_DATE(check_in, '%d/%m/%Y'), '%b')
    ORDER BY DATE_FORMAT(STR_TO_DATE(check_in, '%d/%m/%Y'), '%m');
");
$monthlyRevenueQuery->execute();
$monthlyRevenueResults = $monthlyRevenueQuery->fetchAll(PDO::FETCH_ASSOC);



// Populate with actual data
foreach ($monthlyRevenueResults as $row) {
  $allMonths[$row['month']] = (float) $row['revenue'];
}

$monthlyRevenue = $allMonths;

// Get monthly revenue for current and previous year
$revenueQuery = $conn->prepare("
    SELECT
        YEAR(STR_TO_DATE(check_out, '%m/%d/%Y')) AS year,
        DATE_FORMAT(STR_TO_DATE(check_out, '%m/%d/%Y'), '%b') AS month,
        DATE_FORMAT(STR_TO_DATE(check_out, '%m/%d/%Y'), '%m') AS month_num,
        SUM(payment) AS revenue
    FROM bookings
    WHERE status = 'done'
      AND STR_TO_DATE(check_out, '%m/%d/%Y') >= DATE_SUB(CURDATE(), INTERVAL 2 YEAR)
    GROUP BY YEAR(STR_TO_DATE(check_out, '%m/%d/%Y')),
            DATE_FORMAT(STR_TO_DATE(check_out, '%m/%d/%Y'), '%m'),
            DATE_FORMAT(STR_TO_DATE(check_out, '%m/%d/%Y'), '%b')
    ORDER BY year, month_num;
");
$revenueQuery->execute();
$revenueData = $revenueQuery->fetchAll(PDO::FETCH_ASSOC);

// Organize data by year
$yearlyRevenue = [];
foreach ($revenueData as $row) {
  $yearlyRevenue[$row['year']][$row['month']] = (float) $row['revenue'];
}

// Get current year revenue (for your chart)
$currentYear = date('Y');
$monthlyRevenue = array_fill_keys(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 0);

if (isset($yearlyRevenue[$currentYear])) {
  foreach ($yearlyRevenue[$currentYear] as $month => $revenue) {
    $monthlyRevenue[$month] = $revenue;
  }
}
// Data for charts
$bookingStatusData = [
  'Pending' => $pendingBookings,
  'Confirmed' => $confirmedBookings,
  'Completed' => $doneBookings
];

$paymentStatusData = [
  'Pending' => $pendingPayments,
  'Paid' => $paidPayments,
  'Confirmed' => $confirmedPayments,
  'Completed' => $totalRevenue
];


?>

<!-- Add Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
  .dashboard-card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    margin-bottom: 20px;
    border: none;
  }

  a {
    text-decoration: none;
  }

  .dashboard-card:hover {
    transform: translateY(-5px);
  }

  .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #eee;
    font-weight: 600;
    padding: 15px 20px;
    border-radius: 10px 10px 0 0 !important;
  }

  .stat-value {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 10px 0;
  }

  .revenue {
    color: #28a745;
  }

  .pending {
    color: #ffc107;
  }

  .confirmed {
    color: #17a2b8;
  }

  .done {
    color: #6c757d;
  }

  .section-title {
    color: #343a40;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
  }

  .chart-container {
    position: relative;
    height: 300px;
    width: 100%;
  }

  .summary-card {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    background-color: #f8f9fa;
    text-decoration: none;

  }

  .summary-icon {
    font-size: 2rem;
    margin-right: 15px;
    color: #6c757d;
  }

  .summary-content {
    flex: 1;
  }

  .summary-title {
    font-weight: 600;
    margin-bottom: 5px;
  }

  .summary-value {
    font-size: 1.5rem;
    font-weight: 700;
  }
</style>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="section-title">Admin Dashboard</h3>
    <div class="text-muted">Welcome back, <?php echo htmlspecialchars($adminName); ?></div>
  </div>

  <!-- Quick Stats Row -->
  <div class="row">
    <div class="col-md-3">
      <a class="summary-card" href="hotels-admins/show-hotels.php" style="text-decoration: none;">
        <div class="summary-icon"><i class="fas fa-hotel"></i></div>
        <div class="summary-content">
          <div class="summary-title">Hotels</div>
          <div class="summary-value text-primary"><?php echo $hotelsCount; ?></div>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a class="summary-card" href="rooms-admins/show-rooms.php" style="text-decoration: none;">
        <div class="summary-icon"><i class="fas fa-bed"></i></div>
        <div class="summary-content">
          <div class="summary-title">Rooms</div>
          <div class="summary-value text-primary"><?php echo $roomsCount; ?></div>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a class="summary-card" href="admins/admins.php" style="text-decoration: none;">
        <div class="summary-icon"><i class="fas fa-users-cog"></i></div>
        <div class="summary-content">
          <div class="summary-title">Admins</div>
          <div class="summary-value text-primary"><?php echo $adminsCount; ?></div>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a class="summary-card" href="users-admins/user.php" style="text-decoration: none;">
        <div class="summary-icon"><i class="fas fa-users"></i></div>
        <div class="summary-content">
          <div class="summary-title">Users</div>
          <div class="summary-value text-primary"><?php echo $allUserCount; ?></div>
        </div>
      </a>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="row mt-4">
    <!-- Booking Status Chart -->
    <div class="col-md-6">
      <div class="card dashboard-card">
        <div class="card-header">Booking Status Distribution</div>
        <div class="card-body">
          <div class="chart-container">
            <canvas id="bookingStatusChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Revenue Chart -->
    <div class="col-md-6">
      <div class="card dashboard-card">
        <div class="card-header">Monthly Revenue</div>
        <div class="card-body">
          <div class="chart-container">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Financial Overview -->
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card dashboard-card">
        <div class="card-header">Financial Overview</div>
        <div class="card-body">
          <div class="row">

            <div class="col-md-3 text-center">
              <div class="stat-value pending">$<?php echo number_format($pendingPayments, 2); ?></div>
              <p class="text-muted">Pending Payments</p>
            </div>
            <div class="col-md-3 text-center">
              <div class="stat-value confirmed">$<?php echo number_format($paidPayments, 2); ?></div>
              <p class="text-muted">Paid Payments</p>
            </div>
            <div class="col-md-3 text-center">
              <div class="stat-value">$<?php echo number_format($confirmedPayments, 2); ?></div>
              <p class="text-muted">Confirmed Payments</p>
            </div>
            <div class="col-md-3 text-center">
              <div class="stat-value revenue">$<?php echo number_format($totalRevenue, 2); ?></div>
              <p class="text-muted">Total Revenue</p>
            </div>
          </div>

          <div class="chart-container mt-3" style="height: 250px;">
            <canvas id="paymentStatusChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Popular Bookings -->
  <div class="row mt-4">
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

<script>
  // Booking Status Chart
  const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
  const bookingStatusChart = new Chart(bookingStatusCtx, {
    type: 'doughnut',
    data: {
      labels: <?php echo json_encode(array_keys($bookingStatusData)); ?>,
      datasets: [{
        data: <?php echo json_encode(array_values($bookingStatusData)); ?>,
        backgroundColor: [
          'rgba(255, 193, 7, 0.8)',
          'rgba(23, 162, 184, 0.8)',
          'rgba(108, 117, 125, 0.8)'
        ],
        borderColor: [
          'rgba(255, 193, 7, 1)',
          'rgba(23, 162, 184, 1)',
          'rgba(108, 117, 125, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              let label = context.label || '';
              let value = context.raw || 0;
              let total = context.dataset.data.reduce((a, b) => a + b, 0);
              let percentage = Math.round((value / total) * 100);
              return `${label}: ${value} (${percentage}%)`;
            }
          }
        }
      }
    }
  });

  // Monthly Revenue Chart
  const revenueCtx = document.getElementById('revenueChart').getContext('2d');
  const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode(array_keys($monthlyRevenue)); ?>,
      datasets: [{
        label: 'Monthly Revenue ($)',
        data: <?php echo json_encode(array_values($monthlyRevenue)); ?>,
        backgroundColor: 'rgba(40, 167, 69, 0.2)',
        borderColor: 'rgba(40, 167, 69, 1)',
        borderWidth: 2,
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return '$' + value.toLocaleString();
            }
          }
        }
      }
    }
  });
  // Payment Status Chart
  const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
  const paymentStatusChart = new Chart(paymentStatusCtx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode(array_keys($paymentStatusData)); ?>,
      datasets: [{
        label: 'Payment Amount ($)',
        data: <?php echo json_encode(array_values($paymentStatusData)); ?>,
        backgroundColor: [
          'rgba(255, 193, 7, 0.7)',
          'rgba(0, 123, 255, 0.7)',
          'rgba(23, 162, 184, 0.7)',
          'rgba(40, 167, 69, 0.7)'
        ],
        borderColor: [
          'rgba(255, 193, 7, 1)',
          'rgba(0, 123, 255, 1)',
          'rgba(23, 162, 184, 1)',
          'rgba(40, 167, 69, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return '$' + value.toLocaleString();
            }
          }
        }
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function (context) {
              return '$' + context.raw.toLocaleString();
            }
          }
        }
      }
    }
  });
</script>

<?php
require 'layouts/footer.php';
?>