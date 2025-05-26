<?php
require '../layouts/header.php';
require '../../config/config.php';
try {
    // Start transaction
    $conn->beginTransaction();

    // First check how many rows will be affected (optional but recommended)
    $check = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM hotels_archive 
        WHERE create_at < DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
    ");
    $check->execute();
    $result = $check->fetchAll(PDO::FETCH_ASSOC);

    if(isset($result['count']) && $result['count'] > 0){
        echo "About to delete {$result['count']} old records.";
    }else{
        echo "No old records to delete.";
    }

    // Perform the actual delete
    if($check){
    $delete = $conn->prepare("
        DELETE FROM hotels_archive 
        WHERE create_at < DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
    ");
    $delete->execute();

    // If we got here with no errors, commit the changes
    $conn->commit();

    echo " Successfully deleted old archive records";
    }

} catch (PDOException $e) {
    // Something went wrong - roll back all changes
    $conn->rollBack();

    echo "Error deleting archive: " . $e->getMessage();

    // Optional: Log the error
    // error_log("Archive cleanup failed: " . $e->getMessage());
}
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
            <th scope="col">Modify Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        try {
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
      <td>" . $hotel->modify_date . "</td>
    </tr>";
            }
        } catch (PDOException $e) {
            // echo "Error: " . $e->getMessage();
        }
        ?>
    </tbody>
</table>