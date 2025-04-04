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
  if(isset($_GET['id'])){
    // $status="";
    $id = $_GET['id'];
    if(isset($_POST['status'])){
      $status = $_POST['status'];
      $allHotels = $conn->prepare("UPDATE hotels SET status = '$status' WHERE id = '$id'");
      $allHotels->execute();
      echo '<script>window.location.href="' . ADMIN_URL . 'hotels-admins/show-hotels.php";</script>';
    }
  }else{
    echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
    exit;
  }
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-5 d-inline">Update Status</h5>

          <form method="POST" action="" enctype="multipart/form-data">
            <!-- Email input -->
            <select style="margin-top: 15px;" name="status" class="form-control">
              <option>Choose Status</option>
              <option>1</option>
              <option>0</option>
            </select>


            <!-- Submit button -->
            <button style="margin-top: 10px;" type="submit" name="submit"
              class="btn btn-primary  mb-4 text-center">update</button>


          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?php require '../layouts/footer.php';?>