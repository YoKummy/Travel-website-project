<?php
session_start();
$servername = "localhost"; 
$username = "root"; 
$password = "5253"; 
$dbname = "touristDB"; 
$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (uname, email, sex, bio, pfp, pwd) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // 自動生成6位數的用戶ID
    //$id = 'S' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

    $sex = 0; // 默認值
    $bio = ""; // 默認值
    $pfp = "https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg"; // 默認頭像

    if ($stmt->execute([$username, $email, $sex, $bio, $pfp, $password])) {
       // $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: sidebar.php");//改檔名的地方，主界面得檔名
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>



