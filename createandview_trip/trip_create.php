<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itinerary_db";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 建立資料庫（如果不存在）
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // 資料庫建立成功或已經存在
} else {
    die("建立資料庫錯誤: " . $conn->error);
}

$conn->select_db($dbname);
// 建立資料表（如果不存在）
$sql = "
CREATE TABLE IF NOT EXISTS itineraries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS attractions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    itinerary_id INT,
    day INT,
    intro TEXT,
    name VARCHAR(255),
    description TEXT,
    image VARCHAR(255),
    FOREIGN KEY (itinerary_id) REFERENCES itineraries(id)
)";
if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
} else {
    die("建立資料表錯誤: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itineraryTitle = $_POST['itinerary-title'];
    
    // 保存行程標題到資料庫
    $stmt = $conn->prepare("INSERT INTO itineraries (title) VALUES (?)");
    $stmt->bind_param("s", $itineraryTitle);
    $stmt->execute();
    $itineraryId = $stmt->insert_id;
    $stmt->close();

    // 保存每一天的景點資訊到資料庫
    foreach ($_POST['days'] as $dayIndex => $day) {
        $dayNumber = $dayIndex + 1;
        $intro = $day['intro'];
        foreach ($day['attractions'] as $attractionIndex => $attraction) {
            // 處理圖片上傳
            $imagePath = '';
            if (isset($_FILES['days']['name'][$dayIndex]['attractions'][$attractionIndex]['image']) && $_FILES['days']['error'][$dayIndex]['attractions'][$attractionIndex]['image'] == 0) {
                $targetDir = "uploads/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $imageName = basename($_FILES['days']['name'][$dayIndex]['attractions'][$attractionIndex]['image']);
                $imagePath = $targetDir . $imageName;
                move_uploaded_file($_FILES['days']['tmp_name'][$dayIndex]['attractions'][$attractionIndex]['image'], $imagePath);
                $imagePath = "uploads/" . $imageName; // 將路徑保存在資料庫中
            } else {
                $imagePath = 'https://via.placeholder.com/350x250';
            }
            
            $stmt = $conn->prepare("INSERT INTO attractions (itinerary_id, day, intro, name, description, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissss", $itineraryId, $dayNumber, $intro, $attraction['name'], $attraction['description'], $imagePath);
            $stmt->execute();
            $stmt->close();
        }
    }

    echo "行程已成功保存！";
} else {
    echo "無效的請求方法";
}

$conn->close();
?>