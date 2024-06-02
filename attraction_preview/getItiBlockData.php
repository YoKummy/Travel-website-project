<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$servername = "localhost"; 
$username = "root"; 
$password = "0305"; 
$dbname = "touristDB"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 
$sql = "SELECT trip_name, image_url, total_date, average_score FROM trips"; 
$Result = $conn->query($sql); 
if ($Result-> num_rows > 0) { 
    $itis = array();
    while ($row = $Result->fetch_assoc()) { 
        $iti = array(
            "trip_name" => $row["trip_name"],
            "img_url" => $row["image_url"],
            "total_date" => $row["total_date"],
            "average_score" => $row["average_score"]
        );
        array_push($itis, $iti); 
    } 
    echo json_encode($itis, JSON_PRETTY_PRINT);
} else {
    echo json_encode(array());
}
$conn->close();
?>