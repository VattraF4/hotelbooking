<?php
require '../../config/config.php';
require '../layouts/header.php';

// Check if admin is logged in
if (!isset($_SESSION['id'])) {
    echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
    exit;
}

// Get current admin details
$admin_id = $_SESSION['id'];
$getAdmin = $conn->prepare("SELECT * FROM admin WHERE id = :id");
$getAdmin->bindParam(':id', $admin_id, PDO::PARAM_INT);
$getAdmin->execute();

if ($getAdmin->rowCount() == 0) {
    echo '<div class="alert alert-danger mt-4">Admin not found.</div>';
    require '../include/footer.php';
    exit;
}

$admin = $getAdmin->fetch(PDO::FETCH_OBJ);
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="m-0">Admin Profile</h4>
                        <a href="edit-profile.php" class="btn btn-light btn-sm">
                            <i class="fa fa-edit"></i> Edit Profile
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 font-weight-bold">Admin ID:</div>
                        <div class="col-md-9"><?= $admin->id ?></div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-3 font-weight-bold">Username:</div>
                        <div class="col-md-9"><?= htmlspecialchars($admin->adminname) ?></div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-3 font-weight-bold">Email:</div>
                        <div class="col-md-9"><?= htmlspecialchars($admin->email) ?></div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-3 font-weight-bold">Account Created:</div>
                        <div class="col-md-9"><?= (new DateTime($admin->create_at))->format('M j, Y g:i A') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../layouts/footer.php';
?>