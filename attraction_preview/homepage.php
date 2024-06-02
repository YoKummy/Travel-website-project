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
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/thinline.css"> <!--搜尋列-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  <!-- 引入 jQuery 庫 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.15.0/font/bootstrap-icons.css" rel="stylesheet"> <!--右側欄-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/><!--google font圖示-->
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="comment-style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Font Awesome --> 
<title>Travel website</title>
</head>
<body>
    <div class="wrap">
        <div class="Leftbar"><!-- Profile Picture -->
            <!-- <img src="pixilart-drawing.png" alt="Profile Picture" class="profile-picture"> unused-->
            <!-- Sidebar Menu Items -->
            <a href="#">
                <span><i class="material-icons" onclick="toggleSidebar()">menu</i>
                <span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 選單</span>
            </a><br>
            <a href="aboutus.php">
                <span><i class="material-icons">info</i>
                <span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 關於我們</span>
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
            <div class ="Header" >
                <b class="wname">旅遊行程分享</b>
                <div class="wrapper">
                    <div class="content">
                    <ul>
                        <input type="search" spellcheck="false" placeholder="請輸入您想前往的景點名稱" data-search>
                        <div class="details">
                            <div class="button_container">
                                <button style="background-color:#c7945d;" search-btn>Search</button>
                            </div>
                    </ul>
                    </div>
                </div>
                <button id="toggle-btn" onclick="togglePages()" style="background-color:#c7945d;">切換至行程</button>

                
                <nav class="Rightbar">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar" aria-label="Toggle navigation" style="background-color:#c7945d;">行程</button>
                    <div class="offcanvas offcanvas-end pink-bg" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasNavbarLabel" style="color: #94785A ">行程編輯</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            </ul>
                            <div class="button1 text-center">
                                    <a href="write.html" class="btn btn-primary checkAuth" style="background-color:#c7945d;">創建行程</a>
                            </div>
                        </div>
                    </div>
                </nav>

        </div>
        <!-- Attraction Page -->
        <div class="Body" id="Body_Attraction">
            <div id="places"></div>   
        </div>
        <!-- Itinerary Page -->
        <div class="Body" id="Body_Itinerary">
            <!-- <p class="this_is">This is itinerary page</p> -->
            <div class="itinerarys">
                <div class="iti-blocks" iti-block-container></div>
                <template iti-block-template>
                    <a href="../createandview_trip/trip_view.php" target=”_blank”>
                        <div class="blocks" iti_block>
                            <h3 data-header></h3>
                            <div class="image_container2" data-img>
                                <img src="img/Taipei101_dayview.jpg" alt="Taipei101_dayview">
                                <img src="img/Taipei101_nightview.jpg" alt="Taipei101_nightview">
                                <img src="img/Taipei101_lowerview.webp" alt="Taipei101_lowerview">
                            </div>
                            <div class="itinerary-info-details">
                                <p class="itinerary-info-rating" data-rating></p>
                                <p class="itinerary-info-timespan" data-period></p>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </div>

        <div id="floating-button">
            <a href="Five_group_Chatroom\UI_Web_Chat-master\index.html"><span class="material-symbols-outlined">forum</span></a>
        </div>
        <div id="overlay" class="overlay"></div>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2 id="place-name"></h2>
                <div id="place-rating"></div>
                <img id="place-image" class="place-image" src="" alt="Place Image">
                <div class="buttons-container" style="padding-top: 10px;padding-bottom: 10px;">
                    <button id="photo-button"><span class="material-symbols-outlined">image</span>查看圖片</button>
                    <button id="article-button"><span class="material-symbols-outlined">search</span>在Google上搜尋</button>
                </div>
                <div id="place-details"></div>
                <button id="add_to_tourist" class = "add_to_tourist">添加至行程
                    <div class="circle">
                        <span class="circle-icon">+</span>
                    </div>
                </button>
            </div>
            <span id="popup-close" class="popup-close">✕</span>
        </div>

        <div id="tourist_popup" class="tourist_popup">
            <div id="tpopup-content" class="tpopup-content">
                <h2 style="text-align: center;">要加在哪個行程</h2>
                <span id="tpopup-close" class="tpopup-close">✕</span>
                <button class="submit-button" id="submit-button">提交</button>
            </div>
        </div> 
        <div class="time_popup" id="time_popup">
            <h2 style="text-align: center;">要加在哪</h2>
            <button class="Tsubmit-button" id="Tsubmit-button">確認新增</button>
            <div id="time-content" class="time-content"></div>
        </div>
        <div id="aPopup" class="popup">
            <div class="apopup-content" id="apopup-content">
                <h2 id="trip-name" class = "center-text"></h2>
                <button id="delDay" class="delDay">刪除這一天</button>
                <div id="day-buttons"></div>
            </div>
            <span id="spot-close" class="popup-close">✕</span>
        </div>
            <footer class="Footer">
                <p style="color: white;">© 2024 Copyright</p>
                <a style="flex-basis: 100%;text-align: center; "class="btn btn-rounded" href="aboutus.php">關於我們</a>
            </footer>
        </div>
    </div>
    <script src="index.js?v=<?php echo time(); ?>"></script>
    <script src="search_engine.js?v=<?php echo time(); ?>" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script> <!--右側欄--> 
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrg019oeLqn9I3RPG5PZm94sRi4pdN_cA&loading=async&callback=initMap&libraries=places" async defer></script><!--api金鑰-->
</body>
</html>
