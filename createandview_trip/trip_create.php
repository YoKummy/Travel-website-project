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

                // Insert into attraction table
                $stmt = $conn->prepare("INSERT INTO attraction (order_number, tname, uname, aname, description, image, trip_day) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $orderNumber = $attractionIndex + 1; // 景點的先後順序
                $stmt->bind_param("dsssssi", $orderNumber, $tripName, $uname, $attraction['name'], $attraction['description'], $imagePath, $tripDay);
                $stmt->execute();
                $stmt->close();
            }

            // Check if the record already exists in day_description table
            $stmt = $conn->prepare("SELECT * FROM day_description WHERE uname = ? AND tname = ? AND trip_day = ?");
            $stmt->bind_param("ssi", $uname, $tripName, $tripDay);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Update existing record
                $stmt = $conn->prepare("UPDATE day_description SET day_description = ? WHERE uname = ? AND tname = ? AND trip_day = ?");
                $stmt->bind_param("sssi", $intro, $uname, $tripName, $tripDay);
            } else {
                // Insert new record
                $stmt = $conn->prepare("INSERT INTO day_description (uname, tname, trip_day, day_description) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssis", $uname, $tripName, $tripDay, $intro);
            }

            $stmt->execute();
            $stmt->close();
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





