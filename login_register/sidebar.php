<?php
session_start();
$isLoggedIn = isset($_SESSION['username']); // 检查用戶是否已登錄
$username = $isLoggedIn ? $_SESSION['username'] : null;
$profilePicture = "pixilart-drawing.png"; // 頭像
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sidebar with Profile Picture</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="leftBar.css">
</head>
<body>
<div id="mySidebar" class="sidebar">
    <!-- Profile Picture -->
    <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-picture" id="profilePicture">
    <!-- Sidebar Menu Items -->
    <a href="#"><span><i class="material-icons" onclick="toggleSidebar()">menu</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 選單</span></a><br>
    <a href="about us.html"><span><i class="material-icons">info</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 關於我們</span></a><br>
    <a href="#"><i class="material-icons">email</i><span class="icon-text"></span>&nbsp;&nbsp;&nbsp;&nbsp; 聯絡我們<span></a>
    <hr class="black_line">
    <?php if ($isLoggedIn): ?>
        <a href="#"><span><i class="material-icons">account_circle</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo htmlspecialchars($username); ?></span></a><br>
        <a href="logout.php"><span><i class="material-icons">exit_to_app</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; 登出</span></a><br>
        <script>
            document.getElementById("profilePicture").style.display = "block";
        </script>
    <?php else: ?>
        <!-- 登入的檔名 -->
        <a href="index.html"><span><i class="material-icons">account_circle</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; 登入/註冊</span></a><br>
    <?php endif; ?>
</div>

<script>
    var mini = true;

    function toggleSidebar() {
        var sidebar = document.getElementById("mySidebar");
        
        if (mini) {
            console.log("opening sidebar");
            sidebar.style.width = "250px";
            mini = false;
        } else {
            console.log("closing sidebar");
            sidebar.style.width = "85px";
            mini = true;
        }
    }
</script>
</body>
</html>
