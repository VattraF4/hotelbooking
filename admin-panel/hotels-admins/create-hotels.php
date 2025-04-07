<?php require '../layouts/header.php'; ?>
<?php
require '../../config/config.php';
?>
<?php
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
  exit;
}

if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
  exit;
}
//Check if the form has been submitted
if (isset($_POST['submit'])) {
  if (empty($_POST['name']) || empty($_FILES['image']['name']) || empty($_POST['description']) || empty($_POST['location'])) {
    $alert = 'One or more inputs are empty';
  } else {
    $name = $_POST['name'];
    $image = $_FILES['image']['name']; // Get the name of the uploaded image
    $image = str_replace(' ', '_', $image);
    $description = $_POST['description'];
    $location = $_POST['location'];

    // Upload image
    $target_directory = "../../images/";
    $target_file = $target_directory . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
      // Insert hotel into database
      $insert = $conn->prepare("INSERT INTO hotels (name, image, description, location) VALUES (:name, :image, :description, :location)");
      $insert->execute([
        ':name' => $name,
        ':image' => htmlspecialchars($image, ENT_QUOTES, 'UTF-8'),
        ':description' => $description,
        ':location' => $location
      ]);

      if ($insert) {
        
        echo "<script>window.location.href='show-hotels.php';</script>";
        exit;
      }
      
    } else {
      $alert = 'Failed to upload image';
    }
  }
}

?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-5 d-inline">Create Hotels</h5>
          <!-- Form -->
          <form method="POST" action="create-hotels.php" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="name" id="form2Example1" class="form-control" placeholder="Name" />

            </div>

            <div class="form-outline mb-4 mt-4">
              <input type="file" name="image" id="form2Example1" class="form-control" />

            </div>

            <div class="form-group">
              <label for="exampleFormControlTextarea1">Description</label>
              <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>

            <div class="form-outline mb-4 mt-4">
              <label for="exampleFormControlTextarea1">Location</label>

              <input type="text" name="location" id="form2Example1" class="form-control" />

            </div>



            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">create</button>


          </form>

        </div>
      </div>
    </div>
  </div>
</div><?php require '../layouts/footer.php'; ?>