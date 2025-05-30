<?php
require '../layouts/header.php';
require '../../config/config.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['adminname'])) {
  header("Location: " . ADMIN_URL . "admins/login-admins.php");
  exit;
}

// Fetch all bookings with error handling
try {
  $allBookings = $conn->query("SELECT * FROM bookings ORDER BY create_at DESC");
  $bookings = $allBookings->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
  echo "<script>alert('Error fetching bookings: " . addslashes($e->getMessage()) . "');</script>";
  $bookings = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Bookings Management</title>
  <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    .table-responsive {
      overflow-x: auto;
    }
    .status-badge {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }
    .table th {
      position: sticky;
      top: 0;
      background-color: #007BFF !important;
      color: white;
    }
    .table-container {
      max-height: 75vh;
      overflow-y: auto;
    }
    .text-success { color: #28a745 !important; }
    .text-info { color: #17a2b8 !important; }
    .text-primary { color: #007bff !important; }
    .text-warning { color: #ffc107 !important; }
    .text-danger { color: #dc3545 !important; }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="card-title mb-0">Bookings Management</h5>
              <div>
                <a href="/export-bookings.php" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal">
                  <i class="bi bi-download"></i> Export
                </a>
              </div>
            </div>

            <div class="table-container">
              <table style="margin-top: -1.5rem;" class="table table-striped table-hover table-bordered table-responsive">
              <!-- <table class="table table-striped table-hover"> -->
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Action</th>
                    <th scope="col">Check In</th>
                    <th scope="col">Check Out</th>
                    <th scope="col">Guest</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Status</th>
                    <th scope="col">Room</th>
                    <th scope="col">Hotel</th>
                    <th scope="col">Payment</th>
                    <th scope="col">Created</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($bookings)): ?>
                    <tr>
                      <td colspan="11" class="text-center py-4">No bookings found</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($bookings as $index => $booking): ?>
                      <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td>
                          <a href="status-bookings.php?id=<?php echo $booking->id ?>" 
                             class="btn btn-outline-warning btn-sm" 
                             title="Update Status">
                            <i class="bi bi-pencil-square"></i>
                          </a>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($booking->check_in)); ?></td>
                        <td><?php echo date('M d, Y', strtotime($booking->check_out)); ?></td>
                        <td>
                          <?php echo htmlspecialchars($booking->full_name); ?>
                          <small class="text-muted d-block">ID: <?php echo $booking->id; ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($booking->phone_number); ?></td>
                        <td>
                          <?php
                          $statusClass = '';
                          switch (strtolower($booking->status)) {
                            case "confirm": $statusClass = 'text-success'; break;
                            case "paid": $statusClass = 'text-info'; break;
                            case "done": $statusClass = 'text-primary'; break;
                            case "pending": $statusClass = 'text-warning'; break;
                            default: $statusClass = 'text-danger';
                          }
                          ?>
                          <span class="status-badge <?php echo $statusClass; ?>">
                            <?php echo htmlspecialchars($booking->status); ?>
                          </span>
                        </td>
                        <td><?php echo htmlspecialchars($booking->room_name); ?></td>
                        <td><?php echo htmlspecialchars($booking->hotel_name); ?></td>
                        <td>$<?php echo number_format($booking->payment, 2); ?></td>
                        <td><?php echo date('M d, Y H:i', strtotime($booking->create_at)); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Export Modal -->
  <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exportModalLabel">Export Bookings</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="exportForm" method="post" action="export-bookings.php">
            <div class="mb-3">
              <label for="exportFormat" class="form-label">Format</label>
              <select class="form-select" id="exportFormat" name="format">
                <option value="csv">CSV</option>
                <option value="excel">Excel</option>
                <option value="pdf">PDF</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="dateRange" class="form-label">Date Range</label>
              <select class="form-select" id="dateRange" name="date_range">
                <option value="all">All Bookings</option>
                <option value="today">Today</option>
                <option value="this_week">This Week</option>
                <option value="this_month">This Month</option>
                <option value="custom">Custom Range</option>
              </select>
            </div>
            <div class="row g-2 mb-3" id="customDateRange" style="display: none;">
              <div class="col">
                <label for="startDate" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="startDate" name="start_date">
              </div>
              <div class="col">
                <label for="endDate" class="form-label">End Date</label>
                <input type="date" class="form-control" id="endDate" name="end_date">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" form="exportForm" class="btn btn-primary">Export</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Show/hide custom date range fields
    document.getElementById('dateRange').addEventListener('change', function() {
      const customRangeDiv = document.getElementById('customDateRange');
      customRangeDiv.style.display = this.value === 'custom' ? 'flex' : 'none';
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  </script>
</body>
</html>