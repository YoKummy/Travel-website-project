<?php
$servername = "localhost";
$username = "root";
$password = "1225";
$dbname = "userDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if friend_id is set in the POST data
    if (isset($_POST['friend_id'])) {
        $friendID = $_POST['friend_id']; // Corrected the form field name to 'friend_id'
        $userId = "S00001"; // This should be dynamically set based on the logged-in user

        // Delete the friend from the FriendList table
        $deleteFriendSql = "DELETE FROM FriendList WHERE id = '$userId' AND Friend_ID = '$friendID'";
        if ($conn->query($deleteFriendSql) === TRUE) {
            echo "Friend deleted successfully";
        } else {
            echo "Error: " . $deleteFriendSql . "<br>" . $conn->error;
        }
    } else {
        echo "Friend ID not provided.";
    }
} else {
    echo "Invalid request method.";
}

// Close the connection
$conn->close();
header("Location: profile.php");
?>;