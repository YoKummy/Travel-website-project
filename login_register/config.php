<?php
$servername = "localhost";
$username = "root";
$password = "5253";//密碼
$dbname = "userDB1";//改的地方

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // 設置 PDO 錯誤模式為異常
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 設置字符集
    $pdo->exec("set names utf8mb4");
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

