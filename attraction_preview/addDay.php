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

$sql = "UPDATE trips SET total_date = total_date + 1 WHERE trip_name = '$tripName' AND userId = '$uname'"; 
if (!mysqli_query($conn, $sql)) {
    echo "Error updating table: " . mysqli_error($conn);
    exit;
}

echo json_encode(array('status' => 'success'));
?>
