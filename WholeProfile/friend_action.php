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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['friend_name']) && isset($_POST['action'])) {
        $friendName = $_POST['friend_name'];
        $username = "mario"; // This should be dynamically set based on the logged-in user

        if ($_POST['action'] == 'Follow') {
            // Check if the friend name exists
            $checkFriendSql = "SELECT uname FROM user WHERE uname = ?";
            $stmt = $conn->prepare($checkFriendSql);
            $stmt->bind_param("s", $friendName);
            $stmt->execute();
            $friendResult = $stmt->get_result();

            if ($friendResult->num_rows > 0) {
                // Check if the friend is already added
                $checkExistingFriendSql = "SELECT * FROM FriendList WHERE uname = ? AND fname = ?";
                $stmt = $conn->prepare($checkExistingFriendSql);
                $stmt->bind_param("ss", $username, $friendName);
                $stmt->execute();
                $existingFriendResult = $stmt->get_result();

                if ($existingFriendResult->num_rows == 0) {
                    // Insert the new friend into the FriendList table
                    $addFriendSql = "INSERT INTO FriendList (uname, fname) VALUES (?, ?)";
                    $stmt = $conn->prepare($addFriendSql);
                    $stmt->bind_param("ss", $username, $friendName);
                    if ($stmt->execute()) {
                        echo "New friend added successfully";
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                } else {
                    echo "Friend is already added.";
                }
            } else {
                echo "Friend name not found.";
            }
        } elseif ($_POST['action'] == 'Unfollow') {
            // Delete the friend from the FriendList table
            $deleteFriendSql = "DELETE FROM FriendList WHERE uname = ? AND fname = ?";
            $stmt = $conn->prepare($deleteFriendSql);
            $stmt->bind_param("ss", $username, $friendName);
            if ($stmt->execute()) {
                echo "Friend deleted successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Invalid action.";
        }
    } else {
        echo "Friend name or action not provided.";
    }
} else {
    echo "Invalid request method.";
}

// Close the connection
$conn->close();

// Redirect back to profile page
header("Location: profile.php");
?>;