<?php
require '../layouts/header.php';
?>
<?php
require '../../include/domain.php';
require '../../config/config.php';
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
  exit;
} else {
  $allRooms = $conn->prepare("SELECT * FROM rooms");
  $allRooms->execute();
  $rooms = $allRooms->fetchAll(PDO::FETCH_OBJ);
}
?>
<div class="container-fluid">
  <!-- <?php echo APP_URL . $rooms[0]->images ?> -->
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Rooms</h5>
          <a href="create-rooms.html" class="btn btn-primary mb-4 text-center float-right">Create Room</a><br>

          <!-- Set table No Wrap -->
          <style>
            table td,
            table th,#alert {
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
            }
          </style>
          <table class="table table-striped table-responsive"><br><hr>
      
            <div class="alert alert-primary text-center" id="alert">
             Click images to View
           </div>
            <thead style="background-color:#007BFF; color: white;">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Image</th>
                <th scope="col">Price</th>
                <th scope="col">Persons</th>
                <th scope="col">Size</th>
                <th scope="col">View</th>
                <th scope="col">Bed</th>
                <th scope="col">Hotel</th>
                <th scope="col">status</th>
                <th scope="col">Update Status</th>
                <th scope="col">Delete</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 0;
              foreach ($rooms as $room):
                $i++; ?>
                <tr>
                  <th scope="row"><?php echo $i ?></th>
                  <th scope="row"><?php echo $room->name ?></th>
                  <!-- Image with Modal -->
                  <th scope="row">
                    <a href="#" data-toggle="modal" data-target="#myModal<?php echo $i ?>">
                      <img src="<?php echo APP_URL . "images/" . $room->images ?>" width="35" height="35">
                    </a>

                    <!-- The Modal -->
                    <div class="modal fade" id="myModal<?php echo $i ?>">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-body">
                            <img src="<?php echo APP_URL . "images/" . $room->images ?>" style="width:100%">
                          </div>
                        </div>
                      </div>
                    </div>
                  </th>
                  <th scope="row"><?php echo $room->price ?></th>
                  <th scope="row"><?php echo $room->num_person ?></th>
                  <th scope="row"><?php echo $room->size ?></th>
                  <th scope="row"><?php echo $room->view ?></th>
                  <th scope="row"><?php echo $room->num_bed ?></th>
                  <th scope="row"><?php echo $room->hotel_name ?></th>

                  <?php if ($room->status == 1) {$alert = "Active";} else {$alert = "Inactive";}?>
                  <th scope="row" style="color: <?php echo ($room->status == 1) ? 'green' : 'red' ?>"><?php echo $alert ?></th>

                  <td><a href="status-rooms.php?id=<?php echo $room->id ?>" class="btn btn-primary text-white text-center ">status</a></td>
                  <td><a href="delete-rooms.php?id=<?php echo $room->id ?>" class="btn btn-danger  text-center ">Delete</a></td>
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