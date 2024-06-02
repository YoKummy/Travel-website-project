<?php
session_start();
$isLoggedIn = isset($_SESSION['uname']); // 是否登入
$uname = $_SESSION['username'];
$profilePicture = "https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg"; // 默認頭像
?>

<?php
$servername = "localhost";
$username = "root";
$password = "0305";
$dbname = "touristDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['friend_name']) && isset($_POST['action'])) {
        $friendName = $_POST['friend_name'];

        if ($_POST['action'] == '追蹤') {
            // Check if the friend name exists
            $checkFriendSql = "SELECT uname FROM user WHERE uname = ?";
            $stmt = $conn->prepare($checkFriendSql);
            $stmt->bind_param("s", $friendName);
            $stmt->execute();
            $friendResult = $stmt->get_result();

            if ($friendResult->num_rows > 0) {
                // Check if the friend is already added
                $checkExistingFriendSql = "SELECT * FROM friendlist WHERE uname = ? AND fname = ?";
                $stmt = $conn->prepare($checkExistingFriendSql);
                $stmt->bind_param("ss", $uname, $friendName);
                $stmt->execute();
                $existingFriendResult = $stmt->get_result();

                if ($existingFriendResult->num_rows == 0) {
                    // Insert the new friend into the FriendList table
                    $addFriendSql = "INSERT INTO friendlist (uname, fname) VALUES (?, ?)";
                    $stmt = $conn->prepare($addFriendSql);
                    $stmt->bind_param("ss", $uname, $friendName);
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
        } elseif ($_POST['action'] == '取消追蹤') {
            // Delete the friend from the FriendList table
            $deleteFriendSql = "DELETE FROM FriendList WHERE uname = ? AND fname = ?";
            $stmt = $conn->prepare($deleteFriendSql);
            $stmt->bind_param("ss", $uname, $friendName);
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