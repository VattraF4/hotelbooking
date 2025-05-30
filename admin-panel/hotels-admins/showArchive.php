<?php
require '../layouts/header.php';
require '../../config/config.php';

// Check admin authentication
if (!isset($_SESSION['adminname'])) {
    header("Location: " . ADMIN_URL . "admins/login-admins.php");
    exit;
}

// Process archive cleanup if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cleanup_archive'])) {
    try {
        // Start transaction
        $conn->beginTransaction();

        // First check how many rows will be affected
        $check = $conn->prepare("
            SELECT COUNT(*) as count 
            FROM hotels_archive 
            WHERE create_at < DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
        ");
        $check->execute();
        $result = $check->fetch(PDO::FETCH_ASSOC);
        $count = $result['count'] ?? 0;

        if ($count > 0) {
            // Perform the actual delete
            $delete = $conn->prepare("
                DELETE FROM hotels_archive 
                WHERE create_at < DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
            ");
            $delete->execute();

            // Commit the changes
            $conn->commit();
            $_SESSION['success_message'] = "Successfully deleted $count old archive records";
        } else {
            $_SESSION['info_message'] = "No old records to delete";
        }

    } catch (PDOException $e) {
        // Something went wrong - roll back all changes
        $conn->rollBack();
        $_SESSION['error_message'] = "Error deleting archive: " . $e->getMessage();
        error_log("Archive cleanup failed: " . $e->getMessage());
    }
    
    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Archive Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #007BFF;
            --secondary-color: #6c757d;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .table th {
            position: sticky;
            top: 0;
            background-color: var(--primary-color) !important;
            color: white;
            font-weight: 500;
        }
        
        .description-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .status-active {
            color: #28a745;
            font-weight: 500;
        }
        
        .status-inactive {
            color: #dc3545;
            font-weight: 500;
        }
        
        .action-buttons {
            white-space: nowrap;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Hotel Archive Management</h5>
                        <form method="POST" class="mb-0">
                            <button type="submit" name="cleanup_archive" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete records older than 30 days?')">
                                <i class="bi bi-trash"></i> Cleanup Archive
                            </button>
                        </form>
                    </div>

                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['info_message'])): ?>
                        <div class="alert alert-info"><?= $_SESSION['info_message'] ?></div>
                        <?php unset($_SESSION['info_message']); ?>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created</th>
                                    <th scope="col">Modified By</th>
                                    <th scope="col">Modified Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $allHotels = $conn->query("SELECT * FROM hotels_archive ORDER BY create_at DESC");
                                    $hotels = $allHotels->fetchAll(PDO::FETCH_OBJ);
                                    
                                    if (empty($hotels)) {
                                        echo '<tr><td colspan="9" class="text-center py-4">No archived hotels found</td></tr>';
                                    } else {
                                        foreach ($hotels as $hotel) {
                                            echo '<tr>
                                                <th scope="row">' . htmlspecialchars($hotel->hotel_id) . '</th>
                                                <td>' . htmlspecialchars($hotel->name) . '</td>
                                                <td class="description-cell" title="' . htmlspecialchars($hotel->description) . '">' 
                                                    . htmlspecialchars($hotel->description) . '</td>
                                                <td>' . htmlspecialchars($hotel->location) . '</td>
                                                <td class="' . ($hotel->status === 'Active' ? 'status-active' : 'status-inactive') . '">'
                                                    . htmlspecialchars($hotel->status) . '</td>
                                                <td>' . date('M d, Y', strtotime($hotel->create_at)) . '</td>
                                                <td>' . htmlspecialchars($hotel->modify_by) . '</td>
                                                <td>' . (!empty($hotel->modify_date) ? date('M d, Y', strtotime($hotel->modify_date)) : 'N/A') . '</td>
                                                <td class="action-buttons">
                                                    <a href="view-archive.php?id=' . $hotel->hotel_id . '" class="btn btn-sm btn-primary" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>';
                                        }
                                    }
                                } catch (PDOException $e) {
                                    echo '<tr><td colspan="9" class="text-center text-danger py-4">Error loading archive data</td></tr>';
                                    error_log("Archive data load failed: " . $e->getMessage());
                                }
                                ?>
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