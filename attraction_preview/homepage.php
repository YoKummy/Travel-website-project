<?php
session_start();
$isLoggedIn = isset($_SESSION['username']); // 是否登入
$username = $isLoggedIn ? $_SESSION['username'] : null;
$profilePicture = "pixilart-drawing.png"; // 默認頭像
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
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /><!--聊天圖示-->
<title>Travel website</title>
<style>
/*聊天室按鈕*/
#floating-button {
  position: fixed;
  bottom: 80px;
  right: 20px;
  background-color: #4e9fda;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  z-index: 999;
}

#floating-button:hover {
  background-color: #3b7ab8;
}

#floating-button a .material-symbols-outlined {
    color: white;
}
/*右側欄*/
.offcanvas-backdrop {
    display: none; /*移除bootstrap造成的背景暗化*/
}
.navbar-toggler {
    padding: 10px 15px; 
    font-size: 16px;
    width: auto; 
    height: auto; 
    background-color:#efcf1a;
}
/*景點時間選擇*/
.time_popup{
    border-radius: 15px;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border: 1px solid #ccc;
    height: 85vh;
    width: 87vh;
    z-index: 9999; /*顯示在其他元素上 */
    display: none; /*不會直接顯示 */
    overflow-y: auto;
}
.time-content{
    height:100%;
    width:100%;
    padding-top: 20px;
}
.time-div{
    position: relative;
    left: 50%;
    transform: translate(-50%);
    background-color: #E0E0E0;
    border-radius: 15px;
    margin: 20px;
    width: 70%;
    height: 14%;
}
.time-div label {
    position: absolute;
    top: 50%;
    transform: translate(0% , -50%);
    left: 33%;
}
.radio-button{
    position: absolute;
    top: 50%;
    transform: translate(0% , -50%);
    left: 10%;
}
.Tsubmit-button{
    border-radius: 15px;
    position: absolute;
    left: 43%;
}
/*行程插入景點*/
.submit-button{
    border-radius: 10px;
    left: 228px;
    top: 4px;
    position: relative;
}
.center-text {
  text-align: center;
}
.trip-div{
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px;
    background-color: #E0E0E0;
    border-radius: 15px;
    width: 75%;
    position: relative;
    left: 50%;
    transform: translate(-50%);
}
.trip-div p,
.trip-div select {
  display: flex;
  margin-right: 10px;
}
.tpopup-content{
    height:100%;
    width:100%;
}
.tourist_popup{
    border-radius: 15px;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border: 1px solid #ccc;
    height: 85vh;
    width: 75vh;
    z-index: 9999; /*顯示在其他元素上 */
    display: none; /*不會直接顯示 */
    overflow-y: auto;
}
.trip-selector{
    width:35%;
    height:13%;
    margin-left: 85px;
}
.tpopup-close { 
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
}
.p1{
    font-size: 25px;
    color: black;
    padding-left: 20px;
    margin-bottom: 0px;
    margin-top: 10px;
}
.p2{
    font-size: 18px;
    color: #9D9D9D;
    padding-left: 20px;
    margin-top: 0px;
}

/*景點一覽*/
.place-info { /*主頁面景點與照片 */
    margin-top: 20px;
    margin-bottom: 20px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
    cursor: pointer;/*滑鼠在容器上時變更滑鼠圖示 */
    width: 18%;/*佔據父容器寬度的19% */
    float: left;/*容器向左對齊 */
    margin-right: 2%;
    height: 200px;
    position: relative;
    background-color: white;
    border-radius: 17%;
}
.place-info:nth-child(5n) {
    margin-right: 0;
}
.place-info .place-info-details {
    position: relative; 
    margin: 0;
    padding: 0;
}
.image-container{ /*主頁面照片容器 */
    margin: 0% 0% 0% 5%;
    padding: 0;
    height:70%;
    width:90%;
    position: relative; 
    padding-bottom: 56.75%; /*內邊距長對寬的比例，16:9 */
}
.place-info h3 { /*主頁面景點名稱 */
    margin: 0;
    padding: 0;
    text-align: center;
    font-size: large;
}
.place-info img { /*主頁面照片 */
    overflow:hidden;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    height:100%;
    width:100%;
}
.loading { /*未提供照片情況下的背景 */
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 95%;
    background-color: #f5f5f5;
    margin-top: 82px;
}
.popup { /*景點視窗 */
    position: fixed;
    top: 50%;
    left: 50%;
    height: 85vh;
    width: 107vh;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border: 1px solid #ccc;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    z-index: 9999; /*顯示在其他元素上 */
    display: none; /*不會直接顯示 */
}
.popup-content { /*景點視窗內容 */
    max-height: 80vh;
    overflow-y: auto;
    text-align: center;
}
.popup-close { /*視窗關閉鈕 */
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
}
.place-image { /*視窗照片 */
    max-width: 80%;
    max-height: 80%;
    margin: 0 auto; /* 將左右邊距設為自動，使其水平置中 */
}
.place-info-details { /*視窗資訊 */
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
}
.place-info-rating,
.place-info-vicinity { /*視窗景點評價與地點 */
    display: inline-block;
    margin-bottom: 5px;
    font-size: 0.8em;
    margin-right: 10px;
}
/*左側欄*/
.Leftbar a {
    padding: 8px 8px 8px 32px;
    text-decoration: none;
    font-size: 18px;
    width: 250px;
    background-color: #e0f2f1;
    color: #004d40;
    display: block;
    transition: 0.3s;
}

.Leftbar a:hover {
    color: #f1f1f1;
}

.material-icons,
.icon-text {
    vertical-align: middle;
}

.material-icons {
    padding-bottom: 3px;
}

.profile-picture {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: none;
    margin-top: 10px;
}

.pfp {
    vertical-align: middle;
    
}

hr.black_line {
    border-top: 3px solid black;
}

/*搜尋列*/
::selection{
  color: #fff;
  background: #5372F0;
}
.wrapper{
  width: 60%;
  height: 115px;;
  background: #fff;
  border-radius: 10px;
  padding: 0px 25px 20px;
  box-shadow: 0 0 30px rgba(0,0,0,0.06);
  margin-bottom: 10px;
  margin-top: 10px;
} 
.wrapper :where(.title, li, li i, .details){
  display: flex;
  align-items: center;
}
.content {
    height: 55%;
  padding-top: 0; 
  margin-top: 0; 
}
.details{
    margin-top: 5px;
    padding-top: 0; 
    height: auto;
}
.button_container{
    width:28%;
    display: flex;
    align-items: center;
    justify-content: space-between; /* 將兩個按鈕推到容器的兩側 */
    background-color: #004d40;
    color: #fff;
}
.content ul{
  display: flex;
  flex-wrap: wrap;
  padding: 7px;
  margin: 12px 0;
  border-radius: 5px;
  border: 1px solid #a6a6a6;
}
.content ul  li{
  color: #333;
  margin: 4px 3px;
  list-style: none;
  border-radius: 5px;
  background: #F2F2F2;
  padding: 5px 8px 5px 10px;
  border: 1px solid #e3e1e1;
}
.content ul li i{
  height: 20px;
  width: 20px;
  color: #808080;
  margin-left: 8px;
  font-size: 12px;
  cursor: pointer;
  border-radius: 50%;
  background: #dfdfdf;
  justify-content: center;
}
.content ul input{
  flex: 1;
  padding: 5px;
  border: none;
  outline: none;
  font-size: 16px;
}
.wrapper .details{
  justify-content: space-between;
}
.details button{
  border: none;
  outline: none;
  color: #fff;
  font-size: 14px;
  cursor: pointer;
  padding: 9px 15px;
  border-radius: 5px;
  background: #5372F0;
  transition: background 0.3s ease;
}
.details button:hover{
  background: #2c52ed;
}
.wname{
    position:absolute;
    font-size: 25px;
    left: 135px;
    top: 47px;
}
#toggle-btn {
    position: absolute; /* 固定在右上角 */
    top: 47px;
    right: 93px;
    background-color: rgb(44, 43, 83);
    border: none;
    color: #ffffff;
    padding: 10px 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border-radius: 5px;
}
/*主頁*/
html, body {
    height: 100%; /* 確保 HTML 和 BODY 的高度填滿整個視窗 */
    margin: 0; /* 清除預設的外邊距 */
    padding: 0; /* 清除預設的內邊距 */
}
.wrap{
    width:100%;
    height:100%;
    margin: 0;
    padding-bottom: 10vh;
}
.body_container {
  display: flex;
  float: left;
  flex-direction: column; /* 讓元素由上到下排列 */
  width:94%;
  min-height:110vh;
  margin-left: 5%; /* 留出左側欄的寬度 */
  overflow-y: hidden;
  margin-left: 6%;
}
.Header{
    height:20%;
    width:100%;
    justify-content: center;
    display: flex;
    align-items: center;
    background-color: #004d40;
    color: #fff;
    padding: 10px;
}
.Footer{
    height:10vh;
    width:100%;
    justify-content: center;
    display: flex;
    flex-wrap: wrap; /* 強制換行 */
    align-items: center;
    background-color: #004d40;
    color: #fff;
    padding: 20px;
}
.Leftbar{
    background-color: rgba(115, 199, 255);
    width:6%;
    height:100%;
    display: flex;
    flex-direction: column; /* 讓元素由上到下排列 */
    float:left;
    position: fixed;
    z-index: 1;
    white-space: nowrap;
    overflow-x: hidden;
    transition: 0.5s;
}
.Rightbar{
    background:gray;
    position: fixed;
    top: 20px;
    right: 0;
    z-index: 999;
}
.Body{
    background:pink;
    min-height:80vh;
    display: flex;
    float:left;
    width: 100%;
    overflow: auto;
    padding-top: 8px;
    padding-left: 20px;
}
</style>
</head>
<body>
    <div class="wrap">
        <div class="Leftbar" style="background-color: #e0f2f1; color: #004d40;">
            <!-- Profile Picture -->
            <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" class="profile-picture" id="profilePicture">
            <!-- Sidebar Menu Items -->
            <a href="#"><span><i class="material-icons" onclick="toggleSidebar()">menu</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 選單</span></a><br>
            <a href="about us.html"><span><i class="material-icons">info</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 關於我們</span></a><br>
            <a href="#"><i class="material-icons">email</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 聯絡我們</span></a>
            <hr class="black_line">
            <?php if ($isLoggedIn): ?>
                <a href="#"><span><i class="material-icons">account_circle</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo htmlspecialchars($username); ?></span></a><br>
                <a href="logout.php"><span><i class="material-icons">exit_to_app</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; 登出</span></a><br>
            <?php else: ?>
                <!-- SignIn-SignUp-Form-main/SignIn-SignUp-Form-main/ -->
                <!-- 登入的檔名 -->
                <a href="login_index.html"><span><i class="material-icons">account_circle</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; 登入/註冊</span></a><br>
            <?php endif; ?>
        </div>
        <div class="body_container">
            <!-- Header Section -->
            <div class ="Header" style="background-color: #004d40; color: #fff;">
                <b class="wname">旅遊行程分享</b>
                <!-- Tag Input Section -->
                <div class="wrapper">
                    <div class="content">
                        <ul><input type="text" spellcheck="false" placeholder="Press enter or add a comma after each tag"></ul>
                    </div>
                    <!-- Tag Details Section -->
                    <div class="details">
                        <p style="margin-top: 5px;"><span>10</span> tags are remaining</p>
                        <div class="button_container">
                            <button>Remove All</button>
                            <button>Search</button><!--列出tag相關的地點，未完成-->
                        </div>
                    </div>
                </div>
                <button id="toggle-btn">切換至行程</button>
            </div>
        </div>
    </div>

<script>
    var mini = true;

    function toggleSidebar() {
        var sidebar = document.querySelector(".Leftbar");
        
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
        <div class="body_container">
            <div class ="Header">
                <b class="wname">旅遊行程分享</b>
                <div class="wrapper">
                    <div class="content">
                    <ul><input type="text" spellcheck="false" placeholder="Press enter or add a comma after each tag"></ul>
                    </div>
                    <div class="details">
                    <p style="margin-top: 5px;"><span>10</span> tags are remaining</p>
                        <div class="button_container">
                            <button>Remove All</button>
                            <button>Search</button><!--列出tag相關的地點，未完成-->
                        </div>
                    </div>
                </div>
                <button id="toggle-btn">切換至行程</button>
            <nav class="Rightbar">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">行程</button> 
                <div class="offcanvas offcanvas-end pink-bg" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel" style="color: #157dc7 ">行程編輯</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <div class="d-flex justify-content-center"> 
                                    <div class="btn-group">
                                        <button class="nav-link btn btn-lg btn-primary me-2" onclick="showPage(1)">個人行程</button>
                                        <button class="nav-link btn btn-lg btn-primary" onclick="showPage(2)">朋友行程</button>
                                    </div>
                                </div>
                            </li>
    
                            <div id="page1Content" style="display: block;">
                    <div class="button1 text-center">
                        <a href="write.html" class="btn btn-primary checkAuth">創建行程</a>
                    </div>
                </div>

                <div id="page2Content" style="display: none;">
                    <p>待未來更新</p>
                </div>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="Body">
            <div id="places"></div>
            <div id="floating-button">
                <a href="Five_group_Chatroom\UI_Web_Chat-master\index.html"><span class="material-symbols-outlined">forum</span></a>
            </div>
        </div>
        <div id="popup" class="popup">
            <div class="popup-content">
                <h2 id="place-name"></h2>
                <div id="place-rating"></div>
                <img id="place-image" class="place-image" src="" alt="Place Image">
                <div class="buttons-container" style="padding-top: 10px;padding-bottom: 10px;">
                    <button id="photo-button">查看圖片</button>
                    <button id="article-button">在Google上搜尋</button>
                </div>
                <div id="place-details"></div>
                <button id="add_to_tourist">添加至行程</button>
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
            <footer class="Footer">
                <p style="color: white;">© 2024 Copyright</p>
                <a style="flex-basis: 100%;text-align: center; "class="btn btn-rounded" href="about us.html">關於我們</a>
            </footer>
        </div>
    </div>
    <script>
      //搜尋列
        const ul = document.querySelector("ul"),
        input = document.querySelector("input"),
        tagNumb = document.querySelector(".details span");

        let maxTags = 6,
        tags = [];

        countTags();
        createTag();

        function countTags(){
            input.focus();
            tagNumb.innerText = maxTags - tags.length;
        }

        function createTag(){
            ul.querySelectorAll("li").forEach(li => li.remove());
            tags.slice().reverse().forEach(tag =>{
                let liTag = <li>${tag} <i class="uit uit-multiply" onclick="remove(this, '${tag}')"></i></li>;
                ul.insertAdjacentHTML("afterbegin", liTag);
            });
            countTags();
        }

        function remove(element, tag){
            let index  = tags.indexOf(tag);
            tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
            element.parentElement.remove();
            countTags();
        }

        function addTag(e){
            if(e.key == "Enter"){
                let tag = e.target.value.replace(/\s+/g, ' ');
                if(tag.length > 1 && !tags.includes(tag)){
                    if(tags.length < 10){
                        tag.split(',').forEach(tag => {
                            tags.push(tag);
                            createTag();
                        });
                    }
                }
                e.target.value = "";
            }
        }

        input.addEventListener("keyup", addTag);

        const removeBtn = document.querySelector(".details button");
        removeBtn.addEventListener("click", () =>{
            tags.length = 0;
            ul.querySelectorAll("li").forEach(li => li.remove());
            countTags();
        });
        var mini = true;
        //左側欄
        function toggleSidebar() {
            var sidebar = document.querySelector(".Leftbar");
            
            if (mini) {
                console.log("opening sidebar");
                sidebar.style.width = "15%";
                mini = false;
            } else {
                console.log("closing sidebar");
                sidebar.style.width = "6%";
                mini = true;
            }
        }
        //右側欄
        function showPage(pageNumber) {
            if (pageNumber === 1) {
                document.getElementById("page1Content").style.display = "block";
                document.getElementById("page2Content").style.display = "none";
            } else if (pageNumber === 2) {
                document.getElementById("page1Content").style.display = "none";
                document.getElementById("page2Content").style.display = "block";
            }        
        }
        //切換按鈕
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('toggle-btn');
            toggleBtn.addEventListener('click', function() {
                if (toggleBtn.textContent === '切換至行程') {
                    toggleBtn.textContent = '切換至景點';
                } else {
                    toggleBtn.textContent = '切換至行程';
                }
            });
        });
        //景點一覽
        var infowindow; /*可在地圖上跳出景點資訊視窗*/
        var currentPlace = null; /*儲存使用者目前點選的景點 */
        var currentPlaceName = ''; //儲存目前選擇的地點
        var selectedDate; //儲存目前選擇的日期
        var tName = ''; //儲存目前選擇的行程

        function initMap() {
            // 獲取使用者的地理位置
            if (navigator.geolocation) { /*檢查能否獲取使用者位置 */
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                  
                    // 使用 Google Maps Places API 搜尋附近的旅遊景點
                    var service = new google.maps.places.PlacesService(document.createElement('div'));
                    service.nearbySearch({
                        location: userLocation,
                        radius: 5000, // 搜尋半徑（單位：公尺）
                        type: ['tourist_attraction'] // 指定搜尋類型為旅遊景點                    
                    }, function(results, status) {
                        if (status === google.maps.places.PlacesServiceStatus.OK) {
                            showPlaces(results); //result存放了搜尋到的地點資訊
                        }
                    });
                    
                    // 顯示搜尋到的景點
                    function showPlaces(places) {
                        var placesContainer = document.getElementById('places'); //取得id為places的元素
                        places.forEach(function(place) { //遍歷所有place
                            var placeInfo = document.createElement('div'); //在這邊建立一個區塊存到placeInfo中
                            placeInfo.classList.add('place-info'); //為placeInfo添加css樣式
                            var placeName = document.createElement('h3');
                            placeName.textContent = place.name.replace(/\([^)]*\)|（[^）]*）|(?<=.{6})\s.*/g, ''); //用正規表達式篩選名稱，避免名字過長

                            var imageContainer = document.createElement('div');
                            imageContainer.classList.add('image-container');

                            var placePhoto = document.createElement('img');
                            placePhoto.classList.add('place-image');

                            //確認景點是否提供照片
                            if (place.photos && place.photos[0]) {
                                placePhoto.src = place.photos[0].getUrl();
                            } else {
                                var loadingDiv = document.createElement('div');
                                loadingDiv.classList.add('loading');
                                loadingDiv.textContent = '未提供圖片'; // 添加文本元素
                                imageContainer.appendChild(loadingDiv);
                            }

                            placeInfo.appendChild(placeName);
                            imageContainer.appendChild(placePhoto); //把placePhoto(子)添加到imageContainer(父)
                            placeInfo.appendChild(imageContainer);

                            var placeDetails = document.createElement('div');
                            placeDetails.classList.add('place-info-details');

                            var ratingElement = document.createElement('p');
                            ratingElement.classList.add('place-info-rating');
                            ratingElement.textContent = 'Google評價: ' + (place.rating ? place.rating.toFixed(1) : '');
                            placeDetails.appendChild(ratingElement);

                            var vicinityElement = document.createElement('p');
                            vicinityElement.classList.add('place-info-vicinity');
                            vicinityElement.textContent = '位置: ' + (place.vicinity ? place.vicinity.substring(0, 3) : '');
                            placeDetails.appendChild(vicinityElement);

                            placeInfo.appendChild(placeDetails);
                            placesContainer.appendChild(placeInfo);

                            placeInfo.addEventListener('click', function() {
                                openPopup(place);
                            });
                        });
                    }

                    //跳出視窗
                    function openPopup(place) {
                    clearPopup();

                    document.getElementById('place-name').textContent = place.name;
                    currentPlaceName = place.name.replace(/\([^)]*\)|（[^）]*）|(?<=.{6})\s.*/g, '');

                    var ratingElement = document.getElementById('place-rating');
                    ratingElement.textContent = '';
                    var ratingText = document.createElement('span');
                    ratingText.textContent = 'Google評價: ';
                    ratingElement.appendChild(ratingText);
                    var ratingValue = place.rating ? place.rating.toFixed(1) : '未提供';
                    ratingElement.appendChild(document.createTextNode(ratingValue));
                    var starElement = document.createElement('span');
                    starElement.innerHTML = '&#9733;';
                    ratingElement.appendChild(starElement);

                    var placeImage = document.getElementById('place-image');
                    if (place.photos && place.photos[0]) {
                        placeImage.src = place.photos[0].getUrl();
                        placeImage.style.display = 'block';
                    } else {
                        placeImage.style.display = 'none';
                    }

                    var photoButton = document.getElementById('photo-button');
                    photoButton.addEventListener('click', function() {
                        openGoogleMapsPage(place.name, 'photo');
                    });

                    var articleButton = document.getElementById('article-button');
                    articleButton.addEventListener('click', function() {
                        openGoogleMapsPage(place.name, 'article');
                    });

                    var addressElement = document.createElement('p');
                    addressElement.textContent = "地址：" + (place.vicinity ? place.vicinity : "未提供");
                    document.getElementById('place-details').appendChild(addressElement);

                    currentPlace = place;

                    document.getElementById('popup').style.display = 'block';
                    }

                    //創建選擇行程的視窗
                    $(document).ready(function() { // 檔案準備完成時才執行
                    function addToTouristHandler() {
                        $.ajax({
                        url: 'getTrip.php',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            const tripArray = data.tripArray;
                            const dateArray = data.dateArray;
                            const sdateArray = data.sdateArray;
                            const tripContainer = $('#tpopup-content');
                            tripArray.forEach((tripName, index) => {
                            const tripDiv = $('<div></div>').addClass('trip-div'); // 創建一個 div 元素
                            const tripSelect = $('<select></select>').addClass('trip-selector');
                            let optionSelected = false;
                            tripSelect.change(function() {
                                optionSelected = true;
                                const selectedDateVal = $(this).val();
                                if (selectedDateVal) {
                                    selectedDate = selectedDateVal;
                                }
                                $('.trip-div').not($(this).closest('.trip-div')).find('select').prop('disabled', true);
                                tName = $(this).closest('.trip-div').find('.p1').text();
                            });
                            // 在迴圈外部初始化預設選項
                            const blankOption = $('<option></option>').addClass('center-text');
                            blankOption.val('').text('請選擇一天').prop('disabled', true).prop('selected', true);
                            tripSelect.append(blankOption);
                            for (let i = 1; i <= dateArray[index]; i++) {
                                const option = $('<option></option>').addClass('center-text');
                                option.val(i);
                                option.text('第' + i + '天');
                                tripSelect.append(option);
                            }
                            tripSelect.val(''); // 選擇預設選項
                            const dateContainer = $('<div></div>').addClass('date-container'); // 創建一個父容器
                            const p1 = $('<p></p>').addClass('p1');
                            p1.text(tripName);
                            const p2 = $('<p></p>').addClass('p2');
                            p2.text(sdateArray[index]);
                            dateContainer.append(p1);
                            dateContainer.append(p2);
                            tripDiv.append(dateContainer);
                            tripDiv.append(tripSelect);
                            tripContainer.append(tripDiv);
                            });
                        },
                        error: function(error) {
                            console.error('Error:', error);
                        }
                        });
                        document.getElementById('tourist_popup').style.display = 'block';
                        document.getElementById('popup').style.display = 'none';
                    }
                    $('#add_to_tourist').click(addToTouristHandler);
                    });

                    //創建景點排序視窗
                    document.getElementById('submit-button').addEventListener('click', function() {
                        if (!selectedDate) {
                            alert('請選擇一個行程的天數來加入');
                            return;
                        }
                        document.getElementById('tourist_popup').style.display = 'none';
                        clearTPopup();

                        $.ajax({
                            url: 'submit-dselection.php',
                            type: 'POST',
                            data: {
                            tripName: tName,
                            sDate: selectedDate,
                            placeName: currentPlaceName
                            },
                            success: function(data) {
                                console.log(data);
                                const orderData = data.orderData;
                                const timeContainer = document.getElementById('time-content');
                                var i = 1;
                                for (const [key, value] of Object.entries(orderData)) {
                                    const br = document.createElement("br");
                                    const radio = document.createElement("input");
                                    radio.classList.add("radio-button");
                                    const timeDiv = document.createElement("div");
                                    timeDiv.className = "time-div";
                                    radio.type = "radio";
                                    radio.name = "order";
                                    radio.value = value; // 景點要插入的順序

                                    const label = document.createElement("label");
                                    if(i ==1){
                                        label.innerText = currentPlaceName + "\n" + Object.keys(orderData)[1];
                                    }else if(i == Object.entries(orderData).length){
                                        label.innerText = key + "\n" + currentPlaceName;
                                    }else{
                                        label.innerText = key + "\n" + currentPlaceName + "\n" + Object.keys(orderData)[i];
                                    }
                                    timeDiv.appendChild(radio);
                                    timeDiv.appendChild(label);
                                    timeDiv.appendChild(br);
                                    timeContainer.appendChild(timeDiv);
                                    i++;
                                }
                                if (data.closeTimePopup) {
                                    document.getElementById('time_popup').style.display = 'none';
                                    clearTimePopup();
                                } else {
                                    document.getElementById('time_popup').style.display = 'block';
                                }
                                document.getElementById('Tsubmit-button').addEventListener('click', function() {
                                    var selectedRadio = null;
                                    var radios = document.getElementsByName('order');
                                    
                                    for (var i = 0; i < radios.length; i++) {
                                        if (radios[i].checked) {
                                            selectedRadio = radios[i];
                                            break;
                                        }
                                    }
                                    if (selectedRadio) {
                                        const selectedIndex = Array.prototype.indexOf.call(document.getElementsByName('order'), selectedRadio);
                                        const orderData = data.orderData;
                                        const n = selectedIndex;
                                        $.ajax({
                                            url: 'submit-tselection.php',
                                            type: 'POST',
                                            data: {
                                                orderNum: n,
                                                tripName: tName,
                                                sDate: selectedDate,
                                                placeName: currentPlaceName
                                            },
                                            success: function(data) {
                                                document.getElementById('time_popup').style.display = 'none';
                                                clearTimePopup();
                                            },
                                            error: function(error) {
                                                console.error('Error:', error);
                                            }
                                        });
                                    } else {
                                        alert("請選擇一個時間點加入");
                                        return;
                                    }
                                });
                            },
                            error: function(error) {
                                console.error('Error:', error);
                            }
                        });
                    });

                    //打開google頁面
                    function openGoogleMapsPage(placeName, query) {
                        var searchQuery;
                        var searchUrl;
                        
                        switch(query) {
                            case 'photo':
                                searchQuery = placeName;
                                searchUrl = 'https://www.google.com/search?tbm=isch&q=' + encodeURIComponent(searchQuery);
                                break;
                            case 'article':
                                searchQuery = placeName;
                                searchUrl = 'https://www.google.com/search?q=' + encodeURIComponent(searchQuery);
                                break;
                            default:
                                searchUrl = 'https://www.google.com/';
                                break;
                        }                   
                        if(query !== 'review') {
                            window.open(searchUrl, '_blank');
                        }
                    }

                    //清除跳出視窗的資料
                    function clearPopup() {
                        document.getElementById('place-name').textContent = '';
                        document.getElementById('place-rating').textContent = '';
                        document.getElementById('place-image').src = '';
                        document.getElementById('place-details').innerHTML = '';
                    }

                    document.getElementById('popup-close').addEventListener('click', function() {
                        document.getElementById('popup').style.display = 'none';
                        clearPopup();
                    });
                    
                    function clearTPopup() {
                        $('#tpopup-content .trip-div').remove();
                    }

                    function clearTimePopup() {
                        $('#time-content .time-div').remove();
                    }

                    //測試完可刪除，資料已寫入資料庫，需讓使用者完成景點排序
                    document.getElementById('tpopup-close').addEventListener('click', function() {
                        document.getElementById('tourist_popup').style.display = 'none';
                        clearTPopup();
                    });
                });
            } else { //無法定位使用者
                alert('瀏覽器不支援地理位置服務！');
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script> <!--右側欄--> 
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrg019oeLqn9I3RPG5PZm94sRi4pdN_cA&callback=initMap&libraries=places" async defer></script><!--api金鑰-->
</body>