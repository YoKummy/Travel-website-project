<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>submit_write.php</title>
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "0305";
$dbname = "touristDB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 

// 檢查文件上傳情形
//目前不能準確將圖片上傳至右側欄
$image_url = "";
if (isset($_FILES['image']) && $_FILES['image']['name']) {
    $target_dir = "trip-img/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_url = $target_file;
    } else {
        echo "Sorry, there was an error uploading your file.<br>";
    }
}
if (!$image_url) {
    $image_url = 'write.jpg';
}

// 確認所有欄位
if (isset($_POST['trip_name'], $_POST['start_date'], $_POST['end_date'], $_POST['transport'])) {
    // 準備並連接
    $stmt = $conn->prepare("INSERT INTO trips (trip_name, start_date, end_date, transport, userId, image_url, total_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $trip_name, $start_date, $end_date, $transport, $userId, $image_url, $total_date);

    // 設定參數後執行
    $trip_name = $_POST['trip_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $transport = $_POST['transport'];
    $userId = "S00001"; //測試資料
    $start_day = new DateTime($_POST['start_date']);
    $end_day = new DateTime($_POST['end_date']); 
    $interval = $start_day->diff($end_day); //獲取相隔的天數
    $total_date = $interval->days + 1; //獲取確切天數

    if ($stmt->execute()) {
        header("Location: homepage.html");
        exit;
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }

    $stmt->close();
} else {
    echo "Required fields are missing.<br>";
}

$conn->close();
?>
</body>
</html>
<!--不要建立同名行程，資料庫處理會有bug-->