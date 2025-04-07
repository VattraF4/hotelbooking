<?php
require '../layouts/header.php';
require '../../config/config.php';
?>
<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
  // redirect them to your desired location
  echo "<script>window.location.href='../';</script>";
  exit;
}
?>
<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
    exit;
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $updateFields = [];
        $updateParams = [':id' => $id];

        if (isset($_POST['name'])&& $_POST['name'] != "") {
            $updateFields[] = "name = :name";
            $updateParams[':name'] = $_POST['name'];
        }
        if (isset($_POST['description']) && $_POST['description'] != "") {
            $updateFields[] = "description = :description";
            $updateParams[':description'] = $_POST['description'];
        }
        if (isset($_POST['location']) && $_POST['location'] != "") {
            $updateFields[] = "location = :location";
            $updateParams[':location'] = $_POST['location'];
        }
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $image = $_FILES['image']['name'];
            $image = str_replace(' ', '_', $image);
            if(file_exists('../../images/' . $image)){
                unlink('../../images/' . $image);
            }
            // unlink('../../images/' . $image);
            $target_directory = "../../images/";
            $target_file = $target_directory . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            $updateFields[] = "image = :image";
            $updateParams[':image'] = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
        }
        // echo "UPDATE hotels SET " . implode(', ', $updateFields) . " WHERE id = :id";
        if (!empty($updateFields)) {
          //implode is used to convert an array into a string
            $sql = "UPDATE hotels SET " . implode(', ', $updateFields) . " WHERE id = :id";
            $allHotels = $conn->prepare($sql);
            $allHotels->execute($updateParams);
            echo '<script>window.location.href="' . ADMIN_URL . 'hotels-admins/show-hotels.php";</script>';
        }
    } else {
        echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
        exit;
    }
    $getHotel = $conn->prepare("SELECT * FROM hotels WHERE id = :id");
    $getHotel->bindParam(':id', $id);
    $getHotel->execute();
    $hotel = $getHotel->fetch(PDO::FETCH_OBJ);
}
?>

<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <button type="button" class="btn btn-secondary" onclick="history.back()">Go Back</button>
          <h5 class="card-title mb-5 d-inline">Update Hotel</h5>

          <form method="POST" action="" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="name" id="form2Example1" class="form-control" value="<?php echo $hotel->name; ?>" placeholder="name" />
            </div>

            <div class="form-outline mb-4 mt-4">
              <input type="file" name="image" id="form2Example1" class="form-control" placeholder="name" />
            </div>
            
            <div class="form-group">
              <label for="exampleFormControlTextarea1">Description</label>
              <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"><?php echo $hotel->description; ?></textarea>
            </div>

            <div class="form-outline mb-4 mt-4">
              <label for="exampleFormControlTextarea1">Location</label>
              <input type="text" name="location" value="<?php echo $hotel->location; ?>" id="form2Example1" class="form-control" />

            </div>


            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center" onclick="return confirm('Are you sure you want to update this hotel?');">update</button>


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