<?php require "../include/header.php"; ?>
<?php require "../config/config.php"; ?>


<?php
if (isset($_SESSION['username'])) {
  echo "<script>window.location.href = '" . APP_URL . "';</script>"; //redirect to the home page with javascript
  // header("Location: " . APP_URL . ""); //redirect to the home page but error because of the header is already sent
}

if (isset($_POST['submit'])) { // check if the form has been submitted
  if (empty($_POST['email']) or empty($_POST['password'])) { // check if the email and password fields are empty when the form is submitted
    echo "<script>alert('Please fill all fields')</script>";
  } else {
    $email = $_POST['email']; //Cathch the email from the form name="email"
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password $_POST['password']; 
    $password = $_POST['password']; //Post password no need to hash , if hash it will not work to compare
    // validate he email with query
    $login = $conn->query("SELECT * FROM user WHERE email = '$email'");  //connect to the database and query
    $login->execute(); //execute the query
    $fetch = $login->fetch(PDO::FETCH_ASSOC); //fetch the query for the result is in the form of an array
    //Example
    // $fetch = [
    //   'id' => 0,
    //   'email' => 'user@example.com',
    //   'mypassword' => '$2y$10$dR3GEksKBOja3ojtxPlji.YcMg8uSdotRrrpyU1fDPP.a1bg3U6Oq'
    // ];

    //get the row count
    if ($login->rowCount() > 0) {
      if (password_verify($password, $fetch['my_password'])) {
        // echo "<script>alert('Login successful')</script>";
        $_SESSION['email'] = $fetch['email'];
        $_SESSION['id'] = $fetch['id'];
        $_SESSION['username'] = $fetch['username'];
        $_SESSION['my_password'] = $fetch['my_password'];
        // echo "<script>alert('Login successful, " . $_SESSION['my_password'] . "')</script>";

        header("Location: " . APP_URL . "");

      } else {
      }
    } else {
      echo "<script>alert('Can't find email')</script>";
    }
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
        <form action="login.php" method="post" class="appointment-form" style="margin-top: -568px;">
          <h3 class="mb-3">Login</h3>
          <div class="row">
            <!-- Email Row -->
            <div class="col-md-12">
              <div class="form-group">
                <input type="email" name="email" value='ravattrasmartboy@gmail.com' class="form-control"
                  placeholder="Email">
              </div>
            </div>
            <!-- Password Row -->
            <div class="col-md-12">
              <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password">
              </div>
            </div>
            <!-- Submit Button -->
            <div class="col-md-12">
              <div class="form-group">
                <input type="submit" name="submit" value="Login" class="btn btn-primary py-3 px-4">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<?php require "../include/footer.php"; ?> 