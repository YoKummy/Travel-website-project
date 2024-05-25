<?php
    header('Content-Type: application/json');

    $tripName = $_POST['tripName'];
    $selectedDate = intval($_POST['sDate']);
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
    $order_number = 0;

    $sql = "SELECT COUNT(*) as count FROM attraction WHERE uname = '$userID' AND tname = '$tripName' AND trip_day = '$selectedDate'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['count'] == 0) {
        // 如果沒有，則將 order_number 設置為 1
        $order_number = 1;
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO attraction (tname, uname, aname, trip_day, order_number) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssii", $tripName, $userID, $placeName, $selectedDate, $order_number);
    if (!mysqli_stmt_execute($stmt)) {
        echo "ERROR: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);

    $sql = "SELECT aname, order_number FROM attraction WHERE uname = '$userID' AND tname = '$tripName' AND trip_day = '$selectedDate' ORDER BY order_number ASC"; 
    $result = $conn->query($sql); 
    $orderData = array();
    $closeTimePopup = false;
    while ($row = mysqli_fetch_assoc($result)) {
        $orderData[$row['aname']] = $row['order_number'];
    }
    if (count($orderData) == 1) {
        $closeTimePopup = true;
    }

    echo json_encode(array('orderData' => $orderData, 'closeTimePopup' => $closeTimePopup));
?>