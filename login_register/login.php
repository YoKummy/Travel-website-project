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

if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username']; // 用戶名
    $password = $_POST['password']; // 密碼

    $sql = "SELECT password FROM user WHERE uname = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            header("Location: sidebar.php");
            exit();
        } else {
            echo "帳號或密碼錯誤";
        }
    }  
}
else{
    echo "請輸入帳號和密碼";
}
?>



