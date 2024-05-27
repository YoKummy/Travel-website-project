<?php
header('Content-Type: application/json');

$tripName = $_POST['trip'];

$servername = "localhost"; 
$username = "root"; 
$password = "0305"; 
$dbname = "attractiondb"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 
$userId = "S00001";

//刪除景點資料表的對應資料
$sql = "DELETE FROM attraction WHERE uname = '$userId' AND tname = '$tripName'"; 
if (!mysqli_query($conn, $sql)) {
    echo "Error updating table: " . mysqli_error($conn);
    exit;
}
//刪除行程資料表的對應資料
$dbname = "travel_planner"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 
$sql = "DELETE FROM trips WHERE userId = '$userId' AND trip_name = '$tripName'"; 
if (!mysqli_query($conn, $sql)) {
    echo "Error updating table: " . mysqli_error($conn);
    exit;
} 
echo json_encode(array('status' => 'success'));
?>