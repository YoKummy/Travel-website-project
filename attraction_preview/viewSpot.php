<?php
header('Content-Type: application/json');
session_start();
$uname = $_SESSION['username'];

$tripName = $_POST['trip'];

$servername = "localhost"; 
$username = "root"; 
$password = "0305"; 
$dbname = "touristDB"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 

$sql = "SELECT total_date FROM trips WHERE userId = '$uname' AND trip_name = '$tripName'"; 
$sDate = $conn->query($sql);
$sDate = $sDate->fetch_assoc()['total_date'];

$sql = "SELECT order_number, aname, trip_day FROM attraction WHERE uname = '$uname' AND tname = '$tripName'"; 
$result = mysqli_query($conn, $sql);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
 
echo json_encode(['status' => 'success', 'data' => $data, 'sdate' => $sDate]); 
?>