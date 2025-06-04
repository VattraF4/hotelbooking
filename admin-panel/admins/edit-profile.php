<?php
require '../../config/config.php';
require '../layouts/header.php';

// Check if admin is logged in
if (!isset($_SESSION['id'])) {
    header("Location: " . APP_URL . "admin/login.php");
    exit;
}

// Get current admin details
$admin_id = $_SESSION['id'];
$getAdmin = $conn->prepare("SELECT * FROM admin WHERE id = :id");
$getAdmin->bindParam(':id', $admin_id, PDO::PARAM_INT);
$getAdmin->execute();

if ($getAdmin->rowCount() == 0) {
    echo '<div class="alert alert-danger mt-4">Admin not found.</div>';
    require '../layouts/footer.php';
    exit;
}

$admin = $getAdmin->fetch(PDO::FETCH_OBJ);

// Handle form submission
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminname = trim($_POST['adminname']);
    $email = trim($_POST['email']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($adminname)) {
        $errors[] = "Username is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Check if password needs to be changed
    $password_changed = false;
    if (!empty($current_password)) {
        if (!password_verify($current_password, $admin->my_password)) {
            $errors[] = "Current password is incorrect";
        } elseif (empty($new_password)) {
            $errors[] = "New password is required";
        } elseif (strlen($new_password) < 8) {
            $errors[] = "New password must be at least 8 characters";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "New passwords don't match";
        } else {
            $password_changed = true;
        }
    }

    if (empty($errors)) {
        try {
            // Prepare update query
            if ($password_changed) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE admin SET adminname = :adminname, email = :email, my_password = :password WHERE id = :id");
                $update->bindParam(':password', $hashed_password);
            } else {
                $update = $conn->prepare("UPDATE admin SET adminname = :adminname, email = :email WHERE id = :id");
            }

            $update->bindParam(':adminname', $adminname);
            $update->bindParam(':email', $email);
            $update->bindParam(':id', $admin_id, PDO::PARAM_INT);
            $update->execute();

            $success = true;
            // Refresh admin data
            $getAdmin->execute();
            $admin = $getAdmin->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="m-0">Edit Admin Profile</h4>
                </div>
                
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success">Profile updated successfully!</div>
                    <?php endif; ?>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="adminname">Username</label>
                            <input type="text" class="form-control" id="adminname" name="adminname" 
                                   value="<?= htmlspecialchars($admin->adminname) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($admin->email) ?>" required>
                        </div>
                        
                        <hr>
                        
                        <h5>Change Password</h5>
                        <p class="text-muted">Leave blank to keep current password</p>
                        
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            <a href="profile.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../layouts/footer.php';
?>