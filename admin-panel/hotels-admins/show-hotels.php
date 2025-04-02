<?php
require '../layouts/header.php';
require '../../config/config.php';
?>
<?php
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
  exit;
} else {
  $allHotels = $conn->prepare("SELECT * FROM hotels");
  $allHotels->execute();
  $hotels = $allHotels->fetchAll(PDO::FETCH_OBJ);
}
?>
<div class="container-fluid">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Hotels</h5>
          <a href="create-hotels.html" class="btn btn-primary mb-4 text-center float-right">Create Hotels</a>
          <style>
            table td,
            table th {
              white-space: nowrap;
              overflow: hidden;
              text-overflow: ellipsis;
            }
          </style>
          <div class="table-responsive-sm">
            <table class="table table-striped">
              <thead style="background-color:#007BFF; color: white;">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">name</th>
                  <th scope="col">location</th>
                  <th scope="col">status value</th>
                  <th scope="col">change status</th>
                  <th scope="col">update</th>
                  <th scope="col">delete</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 0;
                foreach ($hotels as $hotel):
                  $i++; ?>
                  <tr>
                    <th scope="row"><?php echo $i ?></th>
                    <td><?php echo $hotel->name ?></td>
                    <td><?php echo $hotel->location ?></td>
                    <td><?php echo $hotel->status ?></td>


                    <td><a href="status.html" class="btn btn-warning text-white text-center ">status</a></td>
                    <td><a href="update-category.html" class="btn btn-warning text-white text-center ">Update </a></td>
                    <td><a href="delete-category.html" class="btn btn-danger  text-center ">Delete </a></td>
                  </tr>
                <?php endforeach; ?>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>



</div>

<?php
require '../layouts/footer.php';
?>