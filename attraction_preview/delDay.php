<?php
header('Content-Type: application/json');
session_start();
$uname = $_SESSION['username'];

$sDay = $_POST['selectedDay'];
$trip = $_POST['trip'];

$servername = "localhost"; 
$username = "root"; 
$password = "0305"; 
$dbname = "touristDB"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 

//刪除該天數儲存的所有行程
$sql = "DELETE FROM attraction WHERE uname = '$uname' AND tname = '$trip' AND trip_day = '$sDay'"; 
if (!mysqli_query($conn, $sql)) {
    echo "Error deleting table: " . mysqli_error($conn);
    exit;
}

//更新其他景點的天數
$sql = "UPDATE attraction SET trip_day = trip_day - 1 WHERE uname = '$uname' AND tname = '$trip' AND trip_day > $sDay";
if (!mysqli_query($conn, $sql)) {
    echo "Error updating table: " . mysqli_error($conn);
    exit;
}

//更新行程的總天數
$sql = "UPDATE trips SET total_date = total_date - 1 WHERE userId = '$uname' AND trip_name = '$trip'";
if (!mysqli_query($conn, $sql)) {
    echo "Error updating table: " . mysqli_error($conn);
    exit;
}
 
echo json_encode(array('status' => 'success'));
?>