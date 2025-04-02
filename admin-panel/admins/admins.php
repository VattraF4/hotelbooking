<?php
require '../layouts/header.php';
require '../../config/config.php';

if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
  exit;
} else {
  $allAdmins = $conn->prepare("SELECT * FROM admin");
  $allAdmins->execute();
  $admins = $allAdmins->fetchAll(PDO::FETCH_OBJ);
}
?>
<div class="container-fluid">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Admins</h5>
          <a href="create-admins.html" class="btn btn-primary mb-4 text-center float-right">Create Admins</a>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead style="background-color:#007BFF; color: white;">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Username</th>
                  <th scope="col">Email</th>
                  <th scope="col">Created</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 0;
                foreach ($admins as $admin):
                  $i++; ?>
                  <tr>
                    <th scope="row"><?php echo $i ?></th>
                    <td><?php echo $admin->adminname ?></td>
                    <td><?php echo $admin->email ?></td>
                    <td><?php echo $admin->create_at ?></td>

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
<script type="text/javascript">

</script>
</body>

</html>