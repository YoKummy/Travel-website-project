<?php
$servername = "localhost"; 
$username = "root"; 
$password = "1040501"; 
$dbname = "touristDB"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 
/* $userId = "S00001";//預設使用者ID，可用session在使用者登入後儲存  */
$sql = "SELECT trip_name, img_url, total_date, average_score FROM trips"; 
$Result = $conn->query($sql); 
if ($Result->num_rows > 0) { 
    $itis = array();
    while ($row = $Result->fetch_assoc()) { 
        $iti[] = array(
            "trip_name" => $row["trip_name"],
            "img_url" => $row["img_url"],
            "total_date" => $row["total_date"],
            "average_score" => $row["average_score"]
        );
        array_push($itis, $iti); 
    } 
    echo json_encode($itis, JSON_PRETTY_PRINT);
} else {
    echo json_encode(array());
}

?>