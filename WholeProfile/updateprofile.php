<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = htmlspecialchars($_POST['uname']);
    $pfp = htmlspecialchars($_POST["pfp"]);
    $bio = htmlspecialchars($_POST['bio']);

    // Update user data
    $stmt = $conn->prepare("UPDATE user SET bio = ?, pfp = ? WHERE uname = ?");
    $stmt->bind_param("sss", $bio, $pfp, $uname);

    if ($stmt->execute() === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect to profile page after update
    header("Location: profile.php");
    exit();
}
?>

</body>
</html>