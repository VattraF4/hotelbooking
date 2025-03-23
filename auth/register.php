<?php require "../include/header.php"; ?>
<?php require "../config/config.php"; ?>

<?php
if (isset($_SESSION['username'])) {
  echo "<script>window.location.href = '" . APP_URL . "';</script>"; //redirect to the home page with javascript
  // header("Location: " . APP_URL . ""); //redirect to the home page but error because of the header is already sent
}
if (isset($_POST['submit'])) {
  if (empty($_POST['username']) or empty($_POST['email']) or empty($_POST['password']) or empty($_POST['phone'])or empty($_POST['confirm_password'])) {
    echo "<script>alert('One or more input are emty')</script>";
  } else {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password $_POST['password'];
    if ($_POST['password'] !== $_POST['confirm_password']) {
      echo "<script>alert('Password does not match')</script>";
    } else {
      $phone = $_POST['phone'];
    }
    // insert username and password to database (Encrypted)
    $insert = $conn->prepare("INSERT INTO user (username, email,phone, my_password) VALUES (:username, :email,:phone, :mypassword)");
    //prepare() is used to prepare a statement for execution and returns a statement object.

    //Excecute sql
    $insert->execute([
      ':username' => $username,
      ':email' => $email,
      ':phone' => $phone,
      ':mypassword' => $password,
    ]);
    header("location: login.php");
  }
}
?>

<div class="hero-wrap js-fullheight" style="background-image: url('<?php echo APP_URL; ?>images/image_2.jpg');"
  data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start"
      data-scrollax-parent="true">
      <div class="col-md-7 ftco-animate">
        <!-- <h2 class="subheading">Welcome to Vacation Rental</h2>
            <h1 class="mb-4">Rent an appartment for your vacation</h1>
            <p><a href="#" class="btn btn-primary">Learn more</a> <a href="#" class="btn btn-white">Contact us</a></p> -->
      </div>
    </div>
  </div>
</div>

<section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
  <div class="container">
    <div class="row justify-content-middle" style="margin-left: 397px;">
      <div class="col-md-6 mt-5">
        <!-- Register form  -->
        <form action="register.php" method="post" class="appointment-form" style="margin-top: -568px;">
          <h3 class="mb-3">Register</h3>
          <!-- Username Row -->
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username">
              </div>
            </div>
            <!-- Email Row -->
            <div class="col-md-12">
              <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email">
              </div>
            </div>
            <!-- Phone Row -->
            <div class="col-md-12">
              <div class="form-group">
                <input type="phone" name="phone" class="form-control" placeholder="Phone Number">
              </div>
            </div>
            <!-- Password Row -->
            <div class="col-md-12">
              <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password">
              </div>
            </div>
            <!-- Confirm Password Row -->
            <div class="col-md-12">
              <div class="form-group">
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
              </div>
            </div>


            <div class="col-md-12">
              <div class="form-group">
                <input type="submit" name="submit" value="Register" class="btn btn-primary py-3 px-4">
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require "../include/footer.php"; ?>