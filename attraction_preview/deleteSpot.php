<?php
header('Content-Type: application/json');

$aname = $_POST['aname'];
$trip_day = $_POST['tripDay'];
$tname = $_POST['tname'];

$servername = "localhost"; 
$username = "root"; 
$password = "0305"; 
$dbname = "touristDB"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 

$userId = "S00001";

$sql = "SELECT order_number FROM attraction WHERE uname = '$userId' AND tname = '$tname' AND trip_day = '$trip_day' AND aname = '$aname'";
$result = mysqli_query($conn, $sql);
$orderNum = (int)mysqli_fetch_column($result);

$sql = "UPDATE attraction SET order_number = order_number - 1 WHERE uname = '$userId' AND tname = '$tname' AND trip_day = '$trip_day' AND order_number > $orderNum";
mysqli_query($conn, $sql);

$sql = "DELETE FROM attraction WHERE uname = '$userId' AND tname = '$tname' AND trip_day = '$trip_day' AND aname = '$aname'"; 
if (!mysqli_query($conn, $sql)) {
    echo "Error updating table: " . mysqli_error($conn);
    exit;
}
 
echo json_encode(array('status' => 'success', 'orderNum' => $orderNum));
?>