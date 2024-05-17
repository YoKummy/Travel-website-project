<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>submit_trip.php</title>
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_planner";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 创建数据库（如果尚未创建）
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// 选择数据库
$conn->select_db($dbname);

// 创建表格（如果尚未创建）
$sql = "CREATE TABLE IF NOT EXISTS trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    transport VARCHAR(255) NOT NULL,
    image_url VARCHAR(255)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table created successfully or already exists<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// 检查文件上传
$image_url = "";
if (isset($_FILES['image']) && $_FILES['image']['name']) {
    $target_dir = "uploads/";
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

// 确保所有必需字段都已设置
if (isset($_POST['trip_name'], $_POST['start_date'], $_POST['end_date'], $_POST['transport'])) {
    // 准备并绑定
    $stmt = $conn->prepare("INSERT INTO trips (trip_name, start_date, end_date, transport, image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $trip_name, $start_date, $end_date, $transport, $image_url);

    // 设置参数并执行
    $trip_name = $_POST['trip_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $transport = $_POST['transport'];

    if ($stmt->execute()) {
        echo "New trip created successfully<br>";
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
