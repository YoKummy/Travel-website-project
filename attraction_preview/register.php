<?php
session_start();
$servername = "localhost"; 
$username = "root"; 
$password = "0305"; 
$dbname = "touristDB"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (uname, email, sex, bio, pfp, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $sex = 0;
    $bio = ""; // 用戶簡介默認空值
    $pfp = "https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg"; // 默認頭像

    if ($stmt->execute([$username, $email, $sex, $bio, $pfp, $password])) {
        $_SESSION['username'] = $username;
        header("Location: homepage.php");//改檔名的地方，主界面得檔名
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>


