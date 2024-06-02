<?php
session_start(); // Start the session

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "0305"; // Replace with your actual password
$dbname = "touristDB";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if itinerary-title is set and not empty
    if(isset($_POST['itinerary-title']) && !empty($_POST['itinerary-title'])) {
        $tripName = $_POST['itinerary-title'];
        if (isset($_SESSION['username'])) {
            $uname = $_SESSION['username']; // Get the username from the session
        } else {
            die("User not logged in."); // Handle the case where the user is not logged in
        }

        // Save each day's attraction information to the database
        foreach ($_POST['days'] as $dayIndex => $day) {
            $tripDay = $dayIndex + 1;
            $intro = $day['intro'];
            $dayAttractionCount = count($day['attractions']);
            
            foreach ($day['attractions'] as $attractionIndex => $attraction) {
                // Handle image upload
                $imagePath = '';
                if (isset($_FILES['days']['name'][$dayIndex]['attractions'][$attractionIndex]['image']) && $_FILES['days']['error'][$dayIndex]['attractions'][$attractionIndex]['image'] == 0) {
                    $targetDir = "uploads/";
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    $imageName = basename($_FILES['days']['name'][$dayIndex]['attractions'][$attractionIndex]['image']);
                    $imagePath = $targetDir . $imageName;
                    move_uploaded_file($_FILES['days']['tmp_name'][$dayIndex]['attractions'][$attractionIndex]['image'], $imagePath);
                    $imagePath = "uploads/" . $imageName; // Save path in the database
                } else {
                    $imagePath = 'https://via.placeholder.com/350x250';
                }

                // 檢查舊有的景點是那些
                $attractionSql = "SELECT id FROM attraction WHERE tname =? AND trip_day =? AND aname =?";
                $stmt = $conn->prepare($attractionSql);
                $stmt->bind_param("isi", $tripName, $tripDay, $attraction['name']);
                $stmt->execute();
                $result = $stmt->get_result();
                $attractionId = null;
                if ($row = $result->fetch_assoc()) {
                    $attractionId = $row['id'];
                }
                $stmt->close();

                // 更新已有的景點
                if ($attractionId) {
                    $updateSql = "UPDATE attraction SET description =?, image =? WHERE id =?";
                    $stmt = $conn->prepare($updateSql);
                    $stmt->bind_param("ssi", $attraction['description'], $imagePath, $attractionId);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    // 插入新的景點
                    $dayAttractionCount++;
                    $insertSql = "INSERT INTO attraction (order_number, tname, uname, aname, description, image, trip_day) VALUES (?,?,?,?,?,?,?)";
                    $stmt = $conn->prepare($insertSql);
                    $stmt->bind_param("dsssssi", $dayAttractionCount, $tripName, $uname, $attraction['name'], $attraction['description'], $imagePath, $tripDay);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        header('Location:../attraction_preview/homepage.php');
        exit;
    } else {
        echo "Trip name is required!";
        var_dump($_POST); // debug: output the POST data
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>





