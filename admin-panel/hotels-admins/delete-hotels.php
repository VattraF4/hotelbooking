<?php
require '../layouts/header.php';
require '../../config/config.php';
?>
<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    echo "<script>window.location.href='../../error/';</script>";
    exit;
}
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMIN_URL . "admins/login-admins.php';</script>";
    exit;
}
$adminName = $_SESSION['adminname'];

// Delete image of hotel from database
$id = $_GET['id'];
$getImage = $conn->query("SELECT * FROM hotels WHERE id = '$id'"); //connect to the database and query
$getImage->execute(); //execute the query
$image = $getImage->fetch(PDO::FETCH_OBJ); //fetch all row from the database and store it in an array

if (file_exists('hotel_images/' . $image->image)) {
    unlink('hotel_images/' . $image->image);
}

try{
// Backup to archive (corrected)
$archive = $conn->prepare("
    INSERT INTO hotels_archive (
        hotel_id, 
        name, 
        image, 
        description, 
        location, 
        status, 
        create_at, 
        modify_by
    ) 
    SELECT 
        id, 
        name, 
        image, 
        description, 
        location, 
        status, 
        create_at, 
        :adminName  # Replace with the actual adminName in php variable
    FROM hotels 
    WHERE id = :id # Replace with the actual hotel_id in php variable
");
$archive->execute([
    ':id' => $id,
    ':adminName' => $adminName
]);
}catch(PDOException $e){
    echo 'Archive failed:'. $e->getMessage();
}

$deleteHotel = $conn->prepare("DELETE FROM hotels WHERE id = :id");
$deleteHotel->bindParam(':id', $id);
$deleteHotel->execute();
echo "<script>window.location.href='show-hotels.php';</script>";
exit;

?>