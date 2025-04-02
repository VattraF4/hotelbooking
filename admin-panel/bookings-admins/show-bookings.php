<?php
require '../layouts/header.php';
require '../../config/config.php';
?>
<?php
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
  exit;
} else {
  $allBookings = $conn->prepare("SELECT * FROM bookings");
  $allBookings->execute();
  $bookings = $allBookings->fetchAll(PDO::FETCH_OBJ);
}
?>
<link rel="stylesheet" href="<?php echo ADMIN_URL; ?>styles/style.css">
<div class="container-fluid">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Bookings</h5>

          <style>
            table td, table th {
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
            }
          </style>
          <table class="table table-striped table-responsive">
            <thead class="thead-light">
              <tr>
                <th scope="col">#</th>
                <th scope="col">check in</th>
                <th scope="col">check out</th>
                <th scope="col">Full Name</th>
                <th scope="col">email</th>
                <th scope="col">phone number</th>
                <th scope="col">hotel name</th>
                <th scope="col">room name</th>
                <th scope="col">status</th>
                <th scope="col">payment</th>
                <th scope="col">created at</th>
                <th scope="col">delete</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 0;
              foreach ($bookings as $allBooking):
                $i++;
                ?>
                <tr>
                  <th scope="row"><?php echo $i ?></th>
                  <td><?php echo $allBooking->check_in ?></td>
                  <td><?php echo $allBooking->check_out ?></td>
                  <td><?php echo $allBooking->full_name ?></td>
                  <td><?php echo $allBooking->email ?></td>
                  <td><?php echo $allBooking->phone_number ?></td>
                  <td><?php echo $allBooking->hotel_name ?></td>
                  <td><?php echo $allBooking->room_name ?></td>
                  <td><?php echo $allBooking->status ?></td>
                  <td><?php echo $allBooking->payment ?></td>
                  <td><?php echo $allBooking->create_at ?></td>
                  <td><a href="delete-posts.php?id=<?php echo $allBooking->id ?>"
                      class="btn btn-danger  text-center ">delete</a></td>
                </tr>
              <?php endforeach; ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>



</div>
<script type="text/javascript">

</script>
</body>

</html>