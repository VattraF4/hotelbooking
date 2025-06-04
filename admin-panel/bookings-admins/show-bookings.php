<?php
require '../layouts/header.php';
require '../../config/config.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['adminname'])) {
  header("Location: " . ADMIN_URL . "admins/login-admins.php");
  exit;
}

// Fetch booking status counts
try {
  $statusCounts = $conn->query("
    SELECT 
      status,
      COUNT(*) as count 
    FROM bookings 
    GROUP BY status
  ")->fetchAll(PDO::FETCH_OBJ);
  
  // Initialize counts
  $allCount = 0;
  $statusData = [];
  foreach ($statusCounts as $status) {
    $statusData[strtolower($status->status)] = $status->count;
    $allCount += $status->count;
  }
} catch (PDOException $e) {
  echo "<script>alert('Error fetching status counts: " . addslashes($e->getMessage()) . "');</script>";
  $statusData = [];
  $allCount = 0;
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
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
    .status-filter {
      cursor: pointer;
      transition: all 0.3s;
    }
    .status-filter:hover {
      transform: scale(1.05);
    }
    .status-filter.active {
      border-bottom: 3px solid #007bff;
    }
    .text-success { color: #28a745 !important; }
    .text-info { color: #17a2b8 !important; }
    .text-primary { color: #007bff !important; }
    .text-warning { color: #ffc107 !important; }
    .text-danger { color: #dc3545 !important; }
    .badge-count {
      font-size: 0.75rem;
      margin-left: 5px;
      vertical-align: middle;
    }
    #searchInput {
      max-width: 300px;
    }
    .dataTables_filter, .dataTables_length {
      margin-bottom: 15px;
    }
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

            <!-- Status Filter Tabs -->
            <div class="mb-4">
              <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="status-filter badge bg-primary bg-opacity-10 text-primary p-2 active" data-status="all">
                  All <span class="badge bg-primary badge-count"><?= $allCount ?></span>
                </span>
                <?php foreach ($statusCounts as $status): ?>
                  <?php 
                  $statusClass = '';
                  switch (strtolower($status->status)) {
                    case "confirmed": $statusClass = 'success'; break;
                    case "paid": $statusClass = 'info'; break;
                    case "done": $statusClass = 'primary'; break;
                    case "pending": $statusClass = 'warning'; break;
                    default: $statusClass = 'danger';
                  }
                  ?>
                  <span class="status-filter badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> p-2" 
                        data-status="<?= strtolower($status->status) ?>">
                    <?= ucfirst($status->status) ?> 
                    <span class="badge bg-<?= $statusClass ?> badge-count"><?= $status->count ?></span>
                  </span>
                <?php endforeach; ?>
              </div>
              
              <!-- Search Box -->
              <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Search bookings...">
                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>

            <div class="table-container">
              <table id="bookingsTable" class="table table-striped table-hover table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Action</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Guest</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Room</th>
                    <th>Hotel</th>
                    <th>Payment</th>
                    <th>Created</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($bookings)): ?>
                    <tr>
                      <td colspan="11" class="text-center py-4">No bookings found</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($bookings as $index => $booking): ?>
                      <tr data-status="<?= strtolower($booking->status) ?>">
                        <th scope="row"><?= $index + 1 ?></th>
                        <td>
                          <a href="status-bookings.php?id=<?= $booking->id ?>" 
                             class="btn btn-outline-warning btn-sm" 
                             title="Update Status">
                            <i class="bi bi-pencil-square"></i>
                          </a>
                        </td>
                        <td><?= date('M d, Y', strtotime($booking->check_in)) ?></td>
                        <td><?= date('M d, Y', strtotime($booking->check_out)) ?></td>
                        <td>
                          <?= htmlspecialchars($booking->full_name) ?>
                          <small class="text-muted d-block">ID: <?= $booking->id ?></small>
                        </td>
                        <td><?= htmlspecialchars($booking->phone_number) ?></td>
                        <td>
                          <?php
                          $statusClass = '';
                          switch (strtolower($booking->status)) {
                            case "confirmed": $statusClass = 'text-success'; break;
                            case "paid": $statusClass = 'text-info'; break;
                            case "done": $statusClass = 'text-primary'; break;
                            case "pending": $statusClass = 'text-warning'; break;
                            default: $statusClass = 'text-danger';
                          }
                          ?>
                          <span class="status-badge <?= $statusClass ?>">
                            <?= htmlspecialchars($booking->status) ?>
                          </span>
                        </td>
                        <td><?= htmlspecialchars($booking->room_name) ?></td>
                        <td><?= htmlspecialchars($booking->hotel_name) ?></td>
                        <td>$<?= number_format($booking->payment, 2) ?></td>
                        <td><?= date('M d, Y H:i', strtotime($booking->create_at)) ?></td>
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

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#bookingsTable').DataTable({
        responsive: true,
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        pageLength: 25,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search bookings...",
          lengthMenu: "Show _MENU_ bookings per page",
          info: "Showing _START_ to _END_ of _TOTAL_ bookings",
          infoEmpty: "No bookings available",
          infoFiltered: "(filtered from _MAX_ total bookings)"
        }
      });
      
      // Custom search box
      $('#searchInput').keyup(function() {
        table.search($(this).val()).draw();
      });
      
      // Clear search button
      $('#clearSearch').click(function() {
        $('#searchInput').val('');
        table.search('').draw();
      });
      
      // Status filter tabs
      $('.status-filter').click(function() {
        $('.status-filter').removeClass('active');
        $(this).addClass('active');
        
        var status = $(this).data('status');
        if (status === 'all') {
          table.column(6).search('').draw();
        } else {
          table.column(6).search('^' + status + '$', true, false).draw();
        }
      });
      
      // Show/hide custom date range fields
      $('#dateRange').change(function() {
        const customRangeDiv = $('#customDateRange');
        customRangeDiv.css('display', this.value === 'custom' ? 'flex' : 'none');
      });
      
      // Initialize tooltips
      $('[title]').tooltip();
    });
  </script>
</body>
</html>