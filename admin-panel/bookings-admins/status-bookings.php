<?php
require '../layouts/header.php';
require '../../config/config.php';
?>
<?php
if ($_GET['id']) {
  $id = $_GET['id']; //get booking id.
  //init to status when no submit
  $status = "";
  $alert = "";
  if (isset($_POST['submit'])) {
    $status = $_POST['status'];
    if ($status != 1 or $status != 0) {
      $alert = "Status must be 1 or 0";
    }
    $updateStatus = $conn->prepare("UPDATE bookings SET status = '$status' WHERE id = '$id'");
    $updateStatus->execute();
    echo '<script>window.location.href="' . ADMIN_URL . 'bookings-admins/show-bookings.php";</script>';
  }
}
?>

<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-5 d-inline">Update Booking Status</h5>
          <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
              <label for="status" class="font-weight-bold">Status</label>
              <select style="margin-top: 15px;" name="status" class="form-control" id="status" required>
                <option value="" selected>Choose Status</option>
                <option value="Pending">Pending</option>
                <option value="Confirm">Confirm</option>
                <option value="Paid">Paid</option>
                <option value="Done">Done</option>
              </select>
            </div>

            <button style="margin-top: 10px;" type="submit" name="submit" class="btn btn-primary btn-block">Update Status</button>

            <div style="display: flex; justify-content: center; color:red;"><?php echo $alert; ?></div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?php require '../layouts/footer.php'; ?>