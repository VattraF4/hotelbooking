<?php
require '../layouts/header.php';
require '../../include/domain.php';
require '../../config/config.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['adminname'])) {
  header("Location: " . ADMIN_URL . "admins/login-admins.php");
  exit;
}

// Fetch all rooms with error handling
try {
  $allRooms = $conn->query("SELECT r.*, h.name as hotel_name FROM rooms r JOIN hotels h ON r.hotel_id = h.id ORDER BY r.name ASC");
  $rooms = $allRooms->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
  echo "<script>alert('Error fetching rooms: " . addslashes($e->getMessage()) . "');</script>";
  $rooms = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Rooms Management</title>
  <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #007BFF;
      --success-color: #28a745;
      --danger-color: #dc3545;
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
    
    .status-active {
      color: var(--success-color);
      font-weight: 500;
    }
    
    .status-inactive {
      color: var(--danger-color);
      font-weight: 500;
    }
    
    .room-image {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 4px;
      cursor: pointer;
      transition: transform 0.3s;
    }
    
    .room-image:hover {
      transform: scale(1.1);
    }
    
    .modal-image {
      max-height: 80vh;
      object-fit: contain;
    }
    
    .alert-notice {
      position: sticky;
      top: 60px;
      z-index: 100;
    }
    
    .btn-sm {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
    }
    
    @media (max-width: 768px) {
      .table-responsive {
        font-size: 0.85rem;
      }
      
      .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.8rem;
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
            <h5 class="card-title mb-0">Rooms Management</h5>
            <a href="create-rooms.php" class="btn btn-primary">
              <i class="bi bi-plus-lg"></i> Create Room
            </a>
          </div>

          <div class="alert alert-primary alert-notice text-center mb-3">
            <i class="bi bi-info-circle"></i> Click on images to view larger version
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Image</th>
                  <th>Price</th>
                  <th>Persons</th>
                  <th>Size</th>
                  <th>View</th>
                  <th>Bed</th>
                  <th>Hotel</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($rooms)): ?>
                  <tr>
                    <td colspan="11" class="text-center py-4">
                      <div class="alert alert-info">
                        No rooms found. <a href="create-rooms.php" class="alert-link">Create your first room</a>.
                      </div>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($rooms as $index => $room): ?>
                    <tr>
                      <th><?= $index + 1 ?></th>
                      <td><?= htmlspecialchars($room->name) ?></td>
                      <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal<?= $index ?>">
                          <img src="<?= "room_images/" . htmlspecialchars($room->images) ?>" 
                               class="room-image" 
                               alt="<?= htmlspecialchars($room->name) ?>">
                        </a>
                        
                        <!-- Image Modal -->
                        <div class="modal fade" id="imageModal<?= $index ?>" tabindex="-1">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-body text-center">
                                <img src="<?= "room_images/" . htmlspecialchars($room->images) ?>" 
                                     class="modal-image img-fluid" 
                                     alt="<?= htmlspecialchars($room->name) ?>">
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>$<?= number_format($room->price, 2) ?></td>
                      <td><?= $room->num_person ?></td>
                      <td><?= $room->size ?> sqft</td>
                      <td><?= htmlspecialchars($room->view) ?></td>
                      <td><?= $room->num_bed ?></td>
                      <td><?= htmlspecialchars($room->hotel_name) ?></td>
                      <td class="<?= $room->status == 1 ? 'status-active' : 'status-inactive' ?>">
                        <?= $room->status == 1 ? 'Active' : 'Inactive' ?>
                      </td>
                      <td class="text-nowrap">
                        <a href="status-rooms.php?id=<?= $room->id ?>" class="btn btn-sm btn-primary me-1" title="Change Status">
                          <i class="bi bi-toggle-on"></i>
                        </a>
                        <a href="delete-rooms.php?id=<?= $room->id ?>" 
                           class="btn btn-sm btn-danger" 
                           title="Delete Room"
                           onclick="return confirm('Are you sure you want to delete <?= addslashes($room->name) ?>?')">
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
  document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>
</body>
</html>

<?php
require '../layouts/footer.php';
?>