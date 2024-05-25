<?php
    header('Content-Type: application/json');

    $orderNum = $_POST['orderNum'];
    $tripName = $_POST['tripName'];
    $placeName = $_POST['placeName'];

    $servername = "localhost";
    $username = "root";
    $password = "0305";
    $dbname = "attractiondb"; 
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }

    $userID = "S00001";

    $sql = "UPDATE attraction SET order_number = '$orderNum' WHERE uname = '$userID' AND tname = '$tripName' AND aname = '$placeName'";
    if(!mysqli_query($conn,$sql)){
        echo"Error creating table: ".mysqli_error($conn); 
    }
    echo json_encode(array('status' => 'success'));
?>