<?php
require '../layouts/header.php';
require '../../config/config.php';

$alert = "";
if (!isset($_SERVER['HTTP_REFERER'])) {
  // redirect them to your desired location
  echo "<script>window.location.href='../../error/';</script>";
  exit;

}
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
  exit;
} else {
  if (isset($_POST['submit'])) {
    if (empty($_POST['adminname']) || empty($_POST['email']) || empty($_POST['my_password']) || empty($_POST['confirm_password'])) {

      $alert = 'One or more inpute are empty';
    } else {
      if ($_POST['my_password'] !== $_POST['confirm_password']) {
        $alert = 'Password does not match';
      } else {
        $username = $_POST['adminname'];
        $email = $_POST['email'];
        $password = password_hash($_POST['my_password'], PASSWORD_DEFAULT);

        // Check if email already exists
        $check = $conn->prepare("SELECT * FROM admin WHERE email = '$email'");
        $check->execute();
        if ($check->rowCount() > 0) {
          $alert = 'Email already exists. Please use a different email.';
        } else {
          // Insert admin into database
          $insert = $conn->prepare("INSERT INTO admin (adminname, email, my_password) VALUES (:adminname, :email, :my_password)");
          $insert->execute([
            ':adminname' => $username,
            ':email' => $email,
            ':my_password' => $password
          ]);
        }
      }
    }
  }
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-5 d-inline">Create Admins</h5>
          <form method="POST" action="" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="form-outline mb-4 mt-4">
              <input type="email" name="email" id="form2Example1" class="form-control" placeholder="admin@email.com" />

            </div>

            <div class="form-outline mb-4">
              <input type="text" name="adminname" id="form2Example1" class="form-control" placeholder="admin username" />
            </div>
            <div class="form-outline mb-4">
              <input type="password" name="my_password" id="form2Example1" class="form-control"
                placeholder="Password" />
            </div>
            <div class="form-outline mb-4">
              <input type="password" name="confirm_password" id="form2Example1" class="form-control"
                placeholder="Confirm Password" />
            </div>

            <label style="color: red; text-align: center;"><?php echo $alert; ?></label><br>

            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">create</button>


          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

</script>
</body>

</html>