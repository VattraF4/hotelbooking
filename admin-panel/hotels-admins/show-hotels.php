<?php 
require '../layouts/header.php';
?>
<div class="container-fluid">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Hotels</h5>
          <a href="create-hotels.html" class="btn btn-primary mb-4 text-center float-right">Create Hotels</a>
          <table class="table">
            <thead>
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
              <tr>
                <th scope="row">1</th>
                <td>Sheraton</td>
                <td>Cairo</td>
                <td>1</td>

                <td><a href="status.html" class="btn btn-warning text-white text-center ">status</a></td>
                <td><a href="update-category.html" class="btn btn-warning text-white text-center ">Update </a></td>
                <td><a href="delete-category.html" class="btn btn-danger  text-center ">Delete </a></td>
              </tr>
              <tr>
                <th scope="row">1</th>
                <td>Sheraton</td>
                <td>Cairo</td>
                <td>1</td>

                <td><a href="status.html" class="btn btn-warning text-white text-center ">status</a></td>
                <td><a href="update-category.html" class="btn btn-warning text-white text-center ">Update </a></td>
                <td><a href="delete-category.html" class="btn btn-danger  text-center ">Delete</a></td>
              </tr>
              <tr>
                <th scope="row">1</th>
                <td>Sheraton</td>
                <td>Cairo</td>
                <td>1</td>
                <td><a href="status.html" class="btn btn-warning text-white text-center ">status</a></td>
                <td><a href="update-category.html" class="btn btn-warning text-white text-center ">Update</a></td>
                <td><a href="delete-category.html" class="btn btn-danger  text-center ">Delete</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>



</div>

<?php 
require '../layouts/footer.php';
?>