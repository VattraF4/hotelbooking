<?php
require '../layouts/header.php';
require '../../config/config.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['adminname'])) {
    header("Location: " . ADMIN_URL . "admins/login-admins.php");
    exit;
}

try {
    // Fetch all admins with error handling
    $allAdmins = $conn->query("SELECT * FROM user ORDER BY create_at DESC");
    $admins = $allAdmins->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo "<script>alert('Error fetching admins: " . addslashes($e->getMessage()) . "');</script>";
    $admins = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>styles/style.css">
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
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .user-email {
            word-break: break-all;
        }
        
        .text-muted {
            color: var(--secondary-color) !important;
        }
        
        .last-login {
            font-size: 0.85rem;
            color: var(--secondary-color);
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
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">User Management</h5>
                        <!-- Uncomment when create functionality is needed -->
                        <!-- <a href="create-user.php" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Add Admin
                        </a> -->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Registered</th>
                                    <!-- <th scope="col">Actions</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($admins)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="alert alert-info">
                                                No admin users found.
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($admins as $index => $admin): ?>
                                        <tr>
                                            <th scope="row"><?= $index + 1 ?></th>
                                            <td>
                                                <?= htmlspecialchars($admin->username) ?>
                                                <?php if ($admin->username === $_SESSION['adminname']): ?>
                                                    <span class="badge bg-info">You</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="user-email"><?= htmlspecialchars($admin->email) ?></td>
                                            <td><?= htmlspecialchars($admin->phone) ?></td>
                                            <td>
                                                <?= date('M d, Y', strtotime($admin->create_at)) ?>
                                                <div class="last-login">
                                                    <?php if (!empty($admin->last_login)): ?>
                                                        Last login: <?= date('M d, H:i', strtotime($admin->last_login)) ?>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <!-- Uncomment when actions are needed -->
                                            <!-- <td class="text-nowrap">
                                                <a href="edit-user.php?id=<?= $admin->id ?>" 
                                                   class="btn btn-sm btn-outline-primary me-1" 
                                                   title="Edit User">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php if ($admin->username !== $_SESSION['adminname']): ?>
                                                    <a href="delete-user.php?id=<?= $admin->id ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       title="Delete User"
                                                       onclick="return confirm('Are you sure you want to delete <?= addslashes($admin->username) ?>?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td> -->
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
    // Initialize tooltips if needed
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