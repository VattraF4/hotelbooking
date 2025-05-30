<?php
ob_start(); // Start output buffering FIRST
require '../layouts/header.php';
require '../../config/config.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['adminname'])) {
  header("Location: " . ADMIN_URL . "admins/login-admins.php");
  ob_end_flush(); // Send output buffer and turn off buffering
  exit;
}

// Fetch all hotels with error handling
try {
  $allHotels = $conn->query("SELECT * FROM hotels ORDER BY name ASC");
  $hotels = $allHotels->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
  echo "<script>alert('Error fetching hotels: " . addslashes($e->getMessage()) . "');</script>";
  $hotels = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Hotels Management</title>
  <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #007BFF;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --danger-color: #dc3545;
      --light-color: #f8f9fa;
      --dark-color: #343a40;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table-responsive {
      overflow-x: auto;
    }

    .table th {
      position: sticky;
      top: 0;
      background-color: var(--primary-color) !important;
      color: white;
      font-weight: 500;
    }

    .table-container {
      max-height: 75vh;
      overflow-y: auto;
    }

    .status-badge {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
      display: inline-block;
      min-width: 80px;
      text-align: center;
    }

    .status-active {
      background-color: rgba(40, 167, 69, 0.1);
      color: var(--success-color);
    }

    .status-inactive {
      background-color: rgba(220, 53, 69, 0.1);
      color: var(--danger-color);
    }

    .btn-action {
      width: 100%;
      min-width: 80px;
      margin: 2px 0;
    }

    .action-cell {
      white-space: nowrap;
    }

    .hotel-name {
      font-weight: 600;
      color: var(--dark-color);
    }

    .hotel-location {
      color: #6c757d;
      font-size: 0.9rem;
    }

    .create-btn {
      transition: all 0.3s ease;
    }

    .create-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
    }

    @media (max-width: 768px) {
      .action-cell {
        display: flex;
        flex-direction: column;
      }

      .btn-action {
        width: 100%;
        margin: 2px 0;
      }
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
              <h5 class="card-title mb-0">Hotels Management</h5>
              <a href="create-hotels.php" class="btn btn-primary create-btn">
                <i class="bi bi-plus-lg"></i> Create Hotel
              </a>
            </div>

            <div class="table-container">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Hotel Details</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($hotels)): ?>
                    <tr>
                      <td colspan="4" class="text-center py-4">
                        <div class="alert alert-info">
                          No hotels found. <a href="create-hotels.php" class="alert-link">Create your first hotel</a>.
                        </div>
                      </td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($hotels as $index => $hotel): ?>
                      <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td>
                          <div class="hotel-name"><?php echo htmlspecialchars($hotel->name); ?></div>
                          <div class="hotel-location">
                            <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($hotel->location); ?>
                          </div>
                        </td>
                        <td>
                          <span
                            class="status-badge <?php echo $hotel->status === 'Active' ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo htmlspecialchars($hotel->status); ?>
                          </span>
                        </td>
                        <td class="text-nowrap">
                          <a href="status-hotels.php?id=<?= $hotel->id ?>" class="btn btn-sm btn-primary me-1">
                            <i class="bi bi-toggle-on"></i>
                          </a>
                          <a href="update-hotels.php?id=<?= $hotel->id ?>" class="btn btn-sm btn-warning me-1">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <a href="delete-hotels.php?id=<?= $hotel->id ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Delete <?= addslashes($hotel->name) ?>?')">
                            <i class="bi bi-trash"></i>
                          </a>
                        </td>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function () {
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });

      // Add confirmation for delete actions
      const deleteButtons = document.querySelectorAll('.btn-outline-danger');
      deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
          const hotelName = this.getAttribute('data-hotel-name') || 'this hotel';
          if (!confirm(`Are you sure you want to delete ${hotelName}? This action cannot be undone.`)) {
            e.preventDefault();
          }
        });
      });
    });
  </script>
</body>

</html>

<?php
require '../layouts/footer.php';
?>