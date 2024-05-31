<?php
session_start();
session_unset(); // 清除所有 session 數據
session_destroy(); // 銷毀 session

header("Location: homepage.php"); // 重定向到 sidebar.php 页面
exit();
?>

