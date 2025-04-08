<?php
require '../layouts/header.php';
require '../../config/config.php';
?>
<?php
if ($_GET['id']) {
  $id = $_GET['id'];
  //init to status when no submit
  $status = "";
  $alert = "";
  if (isset($_POST['submit'])) {
    $status = $_POST['status'];
    if ($status != 1 or $status != 0) {
      $alert = "Status must be 1 or 0";
    }
    $updateStatus = $conn->prepare("UPDATE rooms SET status = '$status' WHERE id = '$id'");
    $updateStatus->execute();
    echo '<script>window.location.href="' . ADMIN_URL . 'rooms-admins/show-rooms.php";</script>';
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

            <div style="display: flex; justify-content: center; color:red;"><?php echo $alert; ?></div>


          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?php require '../layouts/footer.php'; ?>