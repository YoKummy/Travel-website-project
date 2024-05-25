<?php
session_start();
session_unset(); // 清除所有 session 数据
session_destroy(); // 销毁 session

header("Location: sidebar.php"); // 重定向到 sidebar.php 页面
exit();
?>

