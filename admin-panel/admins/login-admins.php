<?php require '../layouts/header.php'; ?>
<?php require '../../config/config.php'; ?>
<?php require '../../include/domain.php'; ?>
<?php require '../layouts/adminURL.php'; ?>

<?php
// Declare error
$error = '';

// if (isset($_SESSION['adminname'])) {
//   echo "<script>window.location.href = '" . ADMIN_URL . "';</script>"; // Redirect to the home page with JavaScript
//   exit;
// }else {
//   echo $_SESSION['adminname'];
// }

if (isset($_POST['submit'])) { // Check if the form has been submitted
  if (empty($_POST['email']) || empty($_POST['password'])) { // Check if the email and password fields are empty
    echo "<script>alert('Please fill all fields')</script>";
  } else {
    $email = $_POST['email']; // Capture the email from the form
    $password = $_POST['password']; // Capture the password (no hashing needed for comparison)

    // Validate the email with a query
    $login = $conn->query("SELECT * FROM admin WHERE email = '$email'");  // Connect to the database and query
    $login->execute(); // Execute the query
    $fetch = $login->fetch(PDO::FETCH_ASSOC); // Fetch the query result as an associative array

    if ($login->rowCount() > 0) {
      if (password_verify($password, $fetch['my_password'])) {
        // Set session variables upon successful login
        $_SESSION['email'] = $fetch['email'];
        $_SESSION['id'] = $fetch['id'];
        $_SESSION['adminname'] = $fetch['adminname'];
        $_SESSION['my_password'] = $fetch['my_password']; 

        // Redirect to the home page
        echo "<script>window.location.href = '../index.php';</script>";
      } else {
        $error = 'Your password is incorrect';
      }
    } else {
      $error = 'Cannot find this email address';
    }
  }
}
?>

<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mt-5">Login</h5>

          <form method="POST" class="p-auto" action="login-admins.php">
            <!-- Email input -->
            <div class="form-outline mb-4">
              <input type="email" name="email" id="form2Example1" class="form-control" placeholder="Email" />

            </div>


            <!-- Password input -->
            <div class="form-outline mb-4">
              <input type="password" name="password" id="form2Example2" placeholder="Password" class="form-control" />

            </div>

            <label style="color: red; text-align: center;"><?php echo $error; ?></label><br>

            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">Login</button>


          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?php
require '../layouts/footer.php';
?>