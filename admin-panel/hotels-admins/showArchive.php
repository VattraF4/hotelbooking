<?php
require '../layouts/header.php';
require '../../config/config.php';
?>
<table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Name</th>
      <!-- <th scope="col">Image</th> -->
      <th scope="col">Description</th>
      <th scope="col">Location</th>
      <th scope="col">Status</th>
      <th scope="col">Create At</th>
      <th scope="col">Modify By</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $allHotels = $conn->query("SELECT * FROM hotels_archive");
    $allHotels->execute();
    $hotels = $allHotels->fetchAll(PDO::FETCH_OBJ);
    foreach ($hotels as $hotel) {
      echo "<tr>
      <th scope='row'>" . $hotel->hotel_id . "</th>
      <td>" . $hotel->name . "</td>
      <!-- <td><img src='../../images/" . $hotel->image . "' width='100'></td> -->
      <td>" . $hotel->description . "</td>
      <td>" . $hotel->location . "</td>
      <td>" . $hotel->status . "</td>
      <td>" . $hotel->create_at . "</td>
      <td>" . $hotel->modify_by . "</td>
    </tr>";
    }
    ?>
  </tbody>
</table>
