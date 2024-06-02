<?php
session_start();
$isLoggedIn = isset($_SESSION['username']); // 判斷是否登入
$username = $isLoggedIn ? $_SESSION['username'] : null;
$profilePicture = "pixilart-drawing.png"; // 使用默認頭像
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> <!--左側欄-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.15.0/font/bootstrap-icons.css" rel="stylesheet"> <!--右側欄-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/><!--google font圖示-->
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="comment-style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Font Awesome --> 
<title>Travel website</title>
</head>
<body style="overflow: hidden;">
    <div class="wrap">
        <div class="Leftbar">
            <a href="#">
                <span><i class="material-icons" onclick="toggleSidebar()">menu</i>
                <span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 選單</span>
            </a><br>
            <a href="homepage.php">
                <span><i class="material-icons">home</i>
                <span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 返回首頁</span>
            </a><br>
            <hr class="black_line">
            <?php if ($isLoggedIn): ?>
                <a href="profile.php?userId=<?php echo $username; ?>"><span><i class="material-icons">account_circle</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo htmlspecialchars($username); ?></span></a><br>
                <a href="logout.php"><span><i class="material-icons">exit_to_app</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; 登出</span></a><br>
            <?php else: ?>
                <a href="login_index.html"><span><i class="material-icons">account_circle</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; 登入/註冊</span></a><br>
            <?php endif; ?>
        </div>
        <div class="body_container">
            <nav class="Rightbar">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation" style="background-color:#c7945d;">行程</button>
                <div class="offcanvas offcanvas-end pink-bg" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel" style="color: #157dc7 ">行程編輯</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        </ul>
                        <div class="button1 text-center">
                                <a href="write.html" class="btn btn-primary checkAuth">創建行程</a>
                        </div>
                    </div>
                </div>
            </nav>
            <div id="floating-button">
                <a href="Five_group_Chatroom\UI_Web_Chat-master\index.html"><span class="material-symbols-outlined">forum</span></a>
            </div>
            <div style = "background-color: #E0D9D3; color: #000; width: 100%; margin: 0; min-height: 90vh; padding: 15px;">
                <section id="mission">
                    <h1>理念</h1>
                    <div style = "border-top: 3px solid white"></div>
                    <p style="text-align: left; margin-top: 15px;">
                        我們的旅遊行程分享網站，目標是推廣旅行者到我們的平台分享，瀏覽他人的行程，並於我們的平台交流。<br>
                        讓對該行程有興趣的同好，參考你的旅遊軌跡完成一段旅程，甚至未來有機會一同完成下一段美妙的旅程。
                    </p><br>
                    <p style="text-align: left;">
                        不論你是探索自然景觀，或著體驗不同的文化，旅遊行程分享網站都會是你分享專屬行程的理想場所。
                    </p><br>
                    <p style="text-align: left;">
                        我們的目標是讓使用者能夠透過分享自己的行程，與其他旅行者在我們的平台上交流。
                    </p><br>
                    <p style="text-align: left; margin-top: 40px;">
                        以下是本專案的組員:
                    </p>
                    <div class="row">
                        <div class="col">
                            <img src="謝竣宇.png" class="img-fluid" alt="Image 1">
                            <p>謝竣宇</p>
                        </div>
                        <div class="col">
                            <img src="蔡東哲.png" class="img-fluid" alt="Image 2">
                            <p>蔡東哲</p>
                        </div>
                        <div class="col">
                            <img src="李介翔.jfif" class="img-fluid" alt="Image 3">
                            <p>蔡東融</p>
                        </div>
                        <div class="col">
                            <img src="李權治.png" class="img-fluid" alt="Image 4">
                            <p>李權治</p>
                        </div>
                        <div class="col">
                            <img src="蔡東融.png" class="img-fluid" alt="Image 5">
                            <p>李介翔</p>
                        </div>
                        <div class="col">
                            <img src="周育翰.png" class="img-fluid" alt="Image 6">
                            <p>周育翰</p>
                        </div>
                    </div>
                </section>
            </div>
            <footer class="Footer">
                <p style="color: white;">© 2024 Copyright</p>
                <a style="flex-basis: 100%;text-align: center; "class="btn btn-rounded" href="aboutus2.html">關於我們</a>
            </footer>
        </div>
    </div>
    <script src="index.js?v=<?php echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script> <!--右側欄--> 
</body>
</html>