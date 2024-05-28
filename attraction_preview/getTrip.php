<?php
header('Content-Type: application/json');

$servername = "localhost"; 
$username = "root"; 
$password = "0305"; 
$dbname = "touristDB"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 
$userId = "S00001";//預設使用者ID，可用session在使用者登入後儲存 
$sql = "SELECT trip_name, total_date, image_url, start_date FROM trips WHERE userId = '$userId'"; 
$Result = $conn->query($sql); 
if ($Result->num_rows > 0) { 
    while ($row = $Result->fetch_assoc()) { 
        $trip_names[] = $row['trip_name']; 
        $total_dates[] = $row['total_date']; 
        $start_dates[] = $row['start_date']; 
        $image_url[] = $row['image_url']; 
    } 
} 
echo json_encode(['tripArray' => $trip_names, 'dateArray' => $total_dates, 'sdateArray' => $start_dates, 'urlArray' => $image_url]); 
?>