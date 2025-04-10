<?php require '../layouts/header.php'; ?>
<?php
require '../../config/config.php';
?>
<?php
$alert = '';
echo "<script>console.log('".$_SERVER['DOCUMENT_ROOT']."')</script>";
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
  exit;
}

//Check if the form has been submitted
if (isset($_POST['submit'])) {
  $requiredFields = ['name', 'price', 'num_person', 'size', 'view', 'num_bed', 'hotel_name', 'hotel_id'];
  $isEmpty = false;

  foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
      $isEmpty = true;
      break;
    }
  }

  if ($isEmpty || empty($_FILES['image']['name'])) {
    $alert = '<label style="color:red">One or more inputs are empty</label>';
  } else {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $num_person = $_POST['num_person'];
    $size = $_POST['size'];
    $view = $_POST['view'];
    $num_bed = $_POST['num_bed'];
    $hotel_name = $_POST['hotel_name'];
    $hotel_id = $_POST['hotel_id'];

    $images = $_FILES['image']['name'];
    $images = str_replace(' ', '_', $images);

    //insert room into database
    $insert = $conn->prepare("INSERT INTO rooms(name,price,num_person,size,view,num_bed,hotel_name,hotel_id,images) 
    VALUES(:name,:price,:num_person,:size,:view,:num_bed,:hotel_name,:hotel_id,:images)");
    $insert->execute([
      ':name' => $name,
      ':price' => $price,
      ':num_person' => $num_person,
      ':size' => $size,
      ':view' => $view,
      ':num_bed' => $num_bed,
      ':hotel_name' => $hotel_name,
      ':hotel_id' => $hotel_id,
      ':images' => $images
    ]);

    // Upload image to server directory
    $target_directory = "room_images/";
    $target_file = $target_directory . basename($images);

    // Check if the target directory exists and create it if it doesn't
    if (!file_exists($target_directory)) {
        mkdir($target_directory, 0777, true);
    }
    
    //Check file exists and delete
    if(file_exists($target_file)){
        unlink($target_file);
    }
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
      echo "<script>alert('Image upload successfully')</script>";

    } else {
      $alert = '<label style="color:red">Fail to upload image</label>';
    }
    $alert = '<label style="color:green">Room Create Successfully</label>';

  }

}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-5 d-inline">Create Rooms</h5>
          <form method="POST" action="" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="name" id="form2Example1" class="form-control" placeholder="name" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="file" name="image" id="form2Example1" class="form-control" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="price" id="form2Example1" class="form-control" placeholder="price" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="num_person" id="form2Example1" class="form-control" placeholder="num_persons" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="num_bed" id="form2Example1" class="form-control" placeholder="num_beds" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="size" id="form2Example1" class="form-control" placeholder="size" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="view" id="form2Example1" class="form-control" placeholder="view" />

            </div>
            <!-- Query all Hotel -->
            <?php
            $allHotels = $conn->prepare("SELECT * FROM hotels");
            $allHotels->execute();
            $hotels = $allHotels->fetchAll(PDO::FETCH_OBJ);
            ?>

            <select class="form-control" name="hotel_name">
              <option>Choose Hotel Name</option>
              <?php foreach ($hotels as $hotel): ?>
                <option><?php echo $hotel->name; ?></option>
              <?php endforeach; ?>
            </select>
            <br>

            <!-- Query all Hotel -->
            <select class="form-control" name="hotel_id">
              <option>Verify Hotel</option>
              <?php foreach ($hotels as $hotel): ?>
                <option value="<?php echo $hotel->id; ?>"><?php echo $hotel->name; ?></option>
              <?php endforeach; ?>
            </select>
            <br>
            <?php echo $alert ?><br>
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