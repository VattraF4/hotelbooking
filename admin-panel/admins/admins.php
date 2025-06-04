<?php
require '../layouts/header.php';
require '../../config/config.php';

// Redirect if not logged in
if (!isset($_SESSION['adminname'])) {
    header("Location: " . ADMIN_URL . "admins/login-admins.php");
    exit;
}

// Fetch admins with error handling
try {
    $stmt = $conn->query("SELECT * FROM admin ORDER BY create_at DESC");
    $admins = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error loading admins: " . $e->getMessage() . "</div>";
    $admins = [];
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Admin Management</h5>
                        <a href="create-admins.php" class="btn btn-light">
                            <i class="fas fa-plus-circle mr-1"></i> Create Admin
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" width="5%">#</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col" width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($admins)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No admin accounts found
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($admins as $index => $admin): ?>
                                        <tr>
                                            <th scope="row"><?= $index + 1 ?></th>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($admin->adminname == $_SESSION['adminname']): ?>
                                                        <span class="badge badge-info mr-2">You</span>
                                                    <?php endif; ?>
                                                    <?= htmlspecialchars($admin->adminname) ?>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($admin->email) ?></td>
                                            <td><?= date('M d, Y h:i A', strtotime($admin->create_at)) ?></td>
                                            <td>
                                                <?php if ($_SESSION['adminname'] == 'super@admin.com'): ?>
                                                    <!-- Super Admin View -->
                                                    <?php if ($admin->adminname == $_SESSION['adminname']): ?>
                                                        <span class="text-muted">Current Admin</span>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-outline-danger delete-admin"
                                                            data-id="<?= $admin->id ?>"
                                                            data-name="<?= htmlspecialchars($admin->adminname) ?>">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <!-- Regular Admin View -->
                                                    <?php if ($admin->adminname == $_SESSION['adminname']): ?>
                                                        <span class="text-muted">Current Admin</span>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-outline-danger delete-admin" disabled
                                                            data-id="<?= $admin->id ?>"
                                                            data-name="<?= htmlspecialchars($admin->adminname) ?>">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if (!empty($admins)): ?>
                    <div class="card-footer bg-light">
                        <small class="text-muted">
                            Showing <?= count($admins) ?> admin account(s)
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete admin account: <strong id="adminToDelete"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger" id="confirmDelete">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Delete admin confirmation
        $('.delete-admin').click(function () {
            const adminId = $(this).data('id');
            const adminName = $(this).data('name');

            $('#adminToDelete').text(adminName);
            $('#confirmDelete').attr('href', 'delete-admin.php?id=' + adminId);
            $('#deleteModal').modal('show');
        });

        // Add hover effects
        $('tr').hover(
            function () {
                $(this).addClass('bg-light');
            },
            function () {
                $(this).removeClass('bg-light');
            }
        );
    });
</script>

<?php require '../layouts/footer.php'; ?>