<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="leftBar.css">

    <style>
        /* Style for the modal */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 2; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }

        .modal-content {
            background-color: black;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: yellow;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

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
        var modal = document.getElementById("editModal");
        modal.style.display = (modal.style.display === "none" || modal.style.display === "") ? "block" : "none";
    }

    window.onclick = function(event) {
        var modal = document.getElementById("editModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</head>
<body>

<div id="mySidebar" class="sidebar">
    <img src="" alt="" class="leftprofile-picture">
    <a href="#"><span><i class="material-icons" onclick="toggleSidebar()">menu</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 選單</span></a><br>
    <a href="about us.html"><span><i class="material-icons">info</i><span class="icon-text">&nbsp;&nbsp;&nbsp;&nbsp; 關於我們</span></a><br>
    <a href="#"><i class="material-icons">email</i><span class="icon-text"></span>&nbsp;&nbsp;&nbsp;&nbsp; 聯絡我們<span></a>
    <hr class="black_line">
    <a href="SignIn-SignUp-Form-main\SignIn-SignUp-Form-main\index.html"><span><i class="material-icons">account_circle</i><span class="leftPfpText">&nbsp;&nbsp;&nbsp;&nbsp; 登入/註冊</span></a><br>
</div>

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

// Get the username of the profile to view from the URL, default to the logged-in user if not specified
$profileUsername = "mario"/* isset($_GET['user']) ? $_GET['user'] : $_SESSION['username'] */;
$loggedInUsername = "mario"/* $_SESSION['username'] */;

    // Fetch user details
    $userSql = "SELECT * FROM user WHERE uname = ?";
    $stmt = $conn->prepare($userSql);
    $stmt->bind_param("s", $profileUsername);
    $stmt->execute();
    $userResult = $stmt->get_result();

    // Fetch friends list
    $friendsSql = "SELECT fname FROM FriendList WHERE uname = ?";
    $stmt = $conn->prepare($friendsSql);
    $stmt->bind_param("s", $loggedInUsername);
    $stmt->execute();
    $friendsResult = $stmt->get_result();

    // Fetch friend count
    $countSql = "SELECT COUNT(*) AS total FROM FriendList WHERE uname = ?";
    $stmt = $conn->prepare($countSql);
    $stmt->bind_param("s", $loggedInUsername);
    $stmt->execute();
    $countResult = $stmt->get_result();
    $totalFriends = 0;
    if ($countResult->num_rows > 0) {
        $countRow = $countResult->fetch_assoc();
        $totalFriends = $countRow['total'];
    }

    if ($userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
        $uname = $profileUsername; /* htmlspecialchars($user['uname']); */
        $email = htmlspecialchars($user['email']);
        $bio = htmlspecialchars($user['bio']);
        $pfp = htmlspecialchars($user['pfp']);
    } else {
        echo "No user found.";
        $uname = $email = $bio = $pfp = "";
    }
?>

<div id="main">
    <div id="profile-upper">
        <div id="profile-d">
            <div id="profile-pic">
                <img src="<?php echo $pfp ?>" alt="Profile picture" width="100%" height="100%">
            </div>
            <div id="u-name"><?php echo $uname ?></div>
            <div id="bio">
            <div id="editModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="toggleEditModal()">&times;</span>
                        <h2>Edit Profile</h2>
                        <form id="editForm" action="updateprofile.php" method="POST" enctype="multipart/form-data">
                            <label for="bio">Bio: </label><br>
                            <textarea id="bio" name="bio" style="color: blue; width: 800px"><?php echo $bio; ?></textarea><br><br>
                            <label for="pfp">Profile Picture(put image link here):</label><br>
                            <textarea id="bio" name="pfp" style="color: red; width: 800px"><?php echo $pfp; ?></textarea><br><br>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="submit" value="Save Changes">
                        </form>
                    </div>
                </div>
                Bio: <?php echo $bio ?>
            </div>

            

            <div id="friend">
                Followers count: <?php echo $totalFriends?>
                <div id="button-container">
                <?php if ($loggedInUsername === $profileUsername): ?>
                    <button id="setting" onclick="toggleEditModal()">Edit</button>
                <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>
    <div id="friends-list">
        <form id="friendForm" action="friend_action.php" method="POST">
            <input type="text" name="friend_name" placeholder="Enter friend's name" required>
            <input type="submit" name="action" value="Follow">
            <input type="submit" name="action" value="Unfollow">
        </form>
        <h3>Friends List</h3>
        <ul>
            <?php
            if ($friendsResult->num_rows > 0) {
                while($friend = $friendsResult->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($friend['fname']) . "</li>";
                }
            } else {
                echo "<li>No friends found.</li>";
            }
            ?>
        </ul>
    </div>
    <div id="profile-lower">
        <?php
        // Assuming you have a query to fetch attractions created by the user
        $attractionSql = "SELECT tname, uname FROM attraction WHERE uname = ?";
        $stmt = $conn->prepare($attractionSql);
        $stmt->bind_param("s", $loggedInUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row["tname"]) . " - Created by: " . htmlspecialchars($row["uname"]) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "No attractions found.";
        }

        // Close the connection
        $conn->close();
        ?>
    </div>
</div>


</body>
</html>