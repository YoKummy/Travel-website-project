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

        // Check if the friend ID exists
        $checkFriendSql = "SELECT uname FROM user WHERE id = '$friendID'";
        $friendResult = $conn->query($checkFriendSql);

        if ($friendResult->num_rows > 0) {
            $friend = $friendResult->fetch_assoc();
            $friendName = $friend['uname'];

            // Insert the new friend into the FriendList table
            $addFriendSql = "INSERT INTO FriendList (id, fname, Friend_ID) VALUES ('$userId', '$friendName', '$friendID')";
            if ($conn->query($addFriendSql) === TRUE) {
                echo "New friend added successfully";
            } else {
                echo "Error: " . $addFriendSql . "<br>" . $conn->error;
            }
        } else {
            echo "Friend ID not found.";
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