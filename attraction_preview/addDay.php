<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    http_redirect("login_register\login.php");
    exit;
}

$tripName = $_POST['trip'];
$userId = $_SESSION['username'];

$servername = "localhost"; 
$username = "root"; 
$password = "0305"; 
$dbname = "touristDB"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 

$sql = "UPDATE trips SET total_date = total_date + 1 WHERE trip_name = '$tripName'"; 
if (!mysqli_query($conn, $sql)) {
    echo "Error updating table: " . mysqli_error($conn);
    exit;
}

echo json_encode(array('status' => 'success'));
?>
