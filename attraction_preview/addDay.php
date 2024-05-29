<?php
header('Content-Type: application/json');

$tripName = $_POST['trip'];

$servername = "localhost"; 
$username = "root"; 
$password = "0305"; 
$dbname = "touristDB"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 
$userId = "S00001";

$sql = "UPDATE trips SET total_date = total_date + 1 WHERE trip_name = '$tripName'"; 
if (!mysqli_query($conn, $sql)) {
    echo "Error updating table: " . mysqli_error($conn);
    exit;
}

$sql = "SELECT total_date WHERE trip_name = '$tripName'"; 
$result = mysqli_query($conn, $sql);

echo json_encode(array('status' => 'success', 'total_day' => $result));
?>
