<?php
session_start();
$isLoggedIn = isset($_SESSION['username']); // 是否登入
$uname = $_SESSION['username']; //記錄登入的用戶
$userId = isset($_GET['userId'])? $_GET['userId'] : null; //紀錄被查看個人檔案的用戶
$profilePicture = "https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg"; // 默認頭像
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="leftBar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!--引入jQuery庫-->
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

        function toggleEditModal() {
            console.log("Toggle modal");
            var modal = document.getElementById("editModal");
            modal.style.display = (modal.style.display === "none" || modal.style.display === "") ? "block" : "none";
        }

        window.onclick = function(event) {
            var modal = document.getElementById("editModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        $(document).ready(function() {
            $(".edit-btn").on("click", function() {
                var tripName = $(this).parents(".rightDiv").find(".tripname").text(); //取得目前按鈕的tripname
                window.location.href = "http://localhost:8080/Travel-website-project/createandview_trip/trip_view_new.php?title=" + encodeURIComponent(tripName) + "&uname=" + encodeURIComponent(uname);
            });
        });
    </script>
</head>
<body>

<div id="mySidebar" class="sidebar"><!-- 左側欄位範本  css請看leftbar.css -->
    <img src="" alt="" class="leftprofile-picture">
    <a href="#"><span><i class="material-icons" onclick="toggleSidebar()">menu</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 選單</span></a><br>
    <a href="homepage.php"><span><i class="material-icons">home</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 返回首頁</span></a><br>
    <a href="aboutus.php"><span><i class="material-icons">info</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 關於我們</span></a><br>
    <hr class="black_line">
    <?php if ($isLoggedIn): ?>
        <a href="profile.php?userId=<?php echo $uname; ?>"><span><i class="material-icons">account_circle</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo htmlspecialchars($uname); ?></span></a><br>
        <a href="logout.php"><span><i class="material-icons">exit_to_app</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; 登出</span></a><br>
    <?php else: ?>
        <a href="login_index.html"><span><i class="material-icons">account_circle</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; 登入/註冊</span></a><br>
    <?php endif; ?>
</div>
<!-- 左側欄位範本結尾  css請看leftbar.css -->

<?php
    $servername = "localhost";
    $username = "root";
    $password = "0305";
    $dbname = "touristDB";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch user details
    $userSql = "SELECT * FROM user WHERE uname = ?";
    $stmt = $conn->prepare($userSql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $userResult = $stmt->get_result();

    // Fetch friends list
    $friendsSql = "SELECT fname FROM FriendList WHERE uname = ?";
    $stmt = $conn->prepare($friendsSql);
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $friendsResult = $stmt->get_result();

    // Fetch friend count
    $countSql = "SELECT COUNT(*) AS total FROM FriendList WHERE uname = ?";
    $stmt = $conn->prepare($countSql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $countResult = $stmt->get_result();
    $totalFriends = 0;
    if ($countResult->num_rows > 0) {
        $countRow = $countResult->fetch_assoc();
        $totalFriends = $countRow['total'];
    }

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $email = htmlspecialchars($user['email']);
        $bio = htmlspecialchars($user['bio']);
        $pfp = htmlspecialchars($user['pfp']);
    } else {
        echo "No user found.";
        $userId = $email = $bio = $pfp = "";
    }
?>

<div id="main">
    <div id="profile-upper">
        <div id="profile-d">
            <div id="profile-pic">
                <img src="<?php echo $pfp ?>" alt="Profile picture" width="100%" height="100%">
            </div>
            <div class = "profile_info">
                <div class = "uid">
                    <div id="u-name"><?php echo $userId ?></div>
                    <div id="friend">
                        追蹤數: <?php echo $totalFriends ?>
                        <div id="button-container">
                            <?php if ($uname === $userId): ?>
                                <button id="setting" onclick="toggleEditModal()">編輯個人檔案</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>        
                <div id="bio">
                    <div id="editModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="toggleEditModal()">&times;</span>
                            <h2>編輯個人檔案</h2>
                            <form id="editForm" action="updateprofile.php" method="POST" enctype="multipart/form-data">
                                <label for="bio">個人簡介:</br></label><br>
                                <textarea id="bio" name="bio" style="color: blue; width: 800px"><?php echo $bio; ?></textarea><br><br>
                                <label for="pfp">個人頭像(請放入圖片的url):</label><br>
                                <textarea id="bio" name="pfp" style="color: red; width: 800px"><?php echo $pfp; ?></textarea><br><br>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="submit" value="Save Changes">
                            </form>
                        </div>
                    </div>個人簡介: <?php echo $bio ?>
                </div>
            </div>
        </div>
    </div>
    <div id="friends-list">
        <form id="friendForm" action="friend_action.php" method="POST">
            <input type="text" name="friend_name" placeholder="Enter friend's name" required>
            <div class = "formBtn">
                <input class = "inputBtn" type="submit" name="action" value="追蹤">
                <input class = "inputBtn" type="submit" name="action" value="取消追蹤">
            </div>    
        </form>
        <h3>好友列表</h3>
        <ul>
            <?php
            if ($friendsResult->num_rows > 0) {
                while($friend = $friendsResult->fetch_assoc()) {
                    echo '<li><a href="profile.php?userId='. htmlspecialchars($friend['fname']). '">'. htmlspecialchars($friend['fname']). '</a></li>';
                }
            } else {
                echo "<li>沒有好友</li>";
            }
            ?>
        </ul>
    </div>
    <div id="profile-lower">
    <?php
    $attractionSql = "SELECT trip_name, start_date, image_url FROM trips WHERE userId =?";
    $stmt = $conn->prepare($attractionSql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $tripName = $row['trip_name'];
        $startDate = $row['start_date'];
        $imageUrl = $row['image_url'];
    
       ?>
        <div class="rightDiv <?php echo ($count % 2 == 0) ? 'left' : 'right'; ?>">
            <img class="tripImg" src="<?php echo $imageUrl;?>">
            <h3 class = "tripname"><?php echo $tripName;?></h3>
            <button class="edit-btn" data-trip-name="<?php echo $tripName;?>">查看</button>
            <p class="trip-date">出發日期：<?php echo $startDate;?></p>
        </div>
        <?php
        $count++;
    }
    if ($result->num_rows == 0) {
        ?>
         <div class="no-result">
             <h2>沒有任何行程</h2>
         </div>
         <?php
    }
    // Close the connection
    $conn->close();
    ?>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editBtns = document.querySelectorAll(".edit-btn");
        editBtns.forEach(function(btn) {
            btn.addEventListener("click", function() {
                const tripName = btn.getAttribute('data-trip-name');
                window.location.href = "../createandview_trip/trip_view.php?tripName=" + encodeURIComponent(tripName);
            });
        });
    });
</script>
</body>
</html>
