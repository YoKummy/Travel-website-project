<?php
    header('Content-Type: application/json');

    $orderNum = $_POST['orderNum'];
    $tripName = $_POST['tripName'];
    $placeName = $_POST['placeName'];
    $sDate = $_POST['sDate'];
    
    $servername = "localhost";
    $username = "root";
    $password = "0305";
    $dbname = "attractiondb"; 

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }
    $userID = "S00001";

    // 更新之前新增的資料
    $newOrder = $orderNum + 0.5; //便於插入資料
    $sql = "UPDATE attraction SET order_number = '$newOrder' WHERE uname = '$userID' AND tname = '$tripName' AND aname = '$placeName' AND trip_day = '$sDate'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }
    //未更新上方的update，這邊要用select

    $sql = "SELECT aname, order_number FROM attraction WHERE uname = '$userID' AND tname = '$tripName' AND trip_day = '$sDate' ORDER BY order_number ASC";
    $result = mysqli_query($conn, $sql);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['aname']] = $row['order_number'];
    }
    $n = 1;
    foreach ($data as $key => $value) {
        $sql = "UPDATE attraction SET order_number = '$n' WHERE uname = '$userID' AND tname = '$tripName' AND aname = '$key' AND trip_day = '$sDate'";
        if (!mysqli_query($conn, $sql)) {
            echo "Error updating table: " . mysqli_error($conn);
            exit;
        }
        $n++;
    }

    echo json_encode(array('status' => 'success'));
?>
<!--在同一天插入相同景點order_number會亂掉，不要這麼做-->