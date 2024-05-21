<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="leftBar.css">
</head>
<body>

<div id="mySidebar" class="sidebar">

    <img src="pixilart-drawing.png" alt="Profile Picture" class="profile-picture">

    <a href="#"><span><i class="material-icons" onclick="toggleSidebar()">menu</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 選單</span></a><br>
    <a href="about us.html"><span><i class="material-icons">info</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 關於我們</span></a><br>
    <a href="#"><i class="material-icons">email</i><span class="icon-text"></span>&nbsp;&nbsp;&nbsp;&nbsp; 聯絡我們<span></a>
    <hr class="black_line">
    <a href="SignIn-SignUp-Form-main\SignIn-SignUp-Form-main\index.html"><span><i class="material-icons">account_circle</i><span class="pfp">&nbsp;&nbsp;&nbsp;&nbsp; 登入/註冊</span></a><br>
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

  <?php
    $servername = "localhost";
    $username = "root";
    $password = "1225";
    $dbname = "userDB";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Assuming we have a user ID to fetch data for
    $userId = "S00001";

    // Fetch user details
    $userSql = "SELECT * FROM user WHERE id = '$userId'";
    $userResult = $conn->query($userSql);

    // Fetch friends list
    $friendsSql = "SELECT usname FROM FriendList WHERE ID = '$userId' OR Friend_ID = '$userId'";
    $friendsResult = $conn->query($friendsSql);

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $id = htmlspecialchars($user['id']);
        $uname = htmlspecialchars($user['uname']);
        $email = htmlspecialchars($user['email']);
        $bio = htmlspecialchars($user['bio']);
        $pfp = htmlspecialchars($user['pfp']);
    } else {
        echo "No user found.";
        $uname = $email = $bio = $pfp = "";
    }
    ?>

    <div id = "main">
    
    <div id="profile-upper">
    <div id="profile-d">
      <div id="profile-pic">
        <img src="<?php echo $pfp?>" alt="Profile picture">
      </div>
      <div id="u-name"><?php echo $uname?>#<?php echo $id?></div>
      <div id="bio">
        Bio: <?php echo $bio?>
      </div>
      <div id="friend">
        Followers count: 69
        <div id="button-container">
          <button id="follow-button">Follow</button>
          <button id="setting">Edit</button>
        </div>
      </div>
    </div>
    <div id="friends-list">
      <h3>Friends List</h3>
      <ul>
        <li>Friend 1</li>
        <li>Friend 2</li>
        <li>Friend 3</li>
        <li>Friend 4</li>
        <li>Friend 5</li>
      </ul>
    </div>
  </div>
  <div id="profile-lower">
    <p id="lower-text">My travels</p>
    <p id="lower-text">Test</p>
  </div>
    </div>
</body>
</html>