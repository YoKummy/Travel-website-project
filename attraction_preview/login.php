<?php
session_start();

if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username']; // 用戶名
    $password = $_POST['password']; // 密碼

    // 假設用戶名和密碼驗證成功
    $_SESSION['username'] = $username; // 將用戶名儲存在 session 中
    header("Location: homepage.php"); // 重定向到 sidebar.php 主頁面檔名
    exit();
}
?>



