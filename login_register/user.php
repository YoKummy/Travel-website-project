<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User_Database</title>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "5253"; // 密碼
    $conn = mysqli_connect($servername, $username, $password);

    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }
    echo "Connected successfully";

    $sql = "CREATE DATABASE IF NOT EXISTS userDB1";//dbname
    if(mysqli_query($conn, $sql)){
        echo "Database created successfully!";
    }else{
        echo "ERROR creating database: " . mysqli_error($conn);
    } 

    $dbname = "userDB1";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }

    $sql = "CREATE TABLE user (
        id CHAR(6) PRIMARY KEY,
        uname VARCHAR(30) NOT NULL,
        email VARCHAR(30) NOT NULL,
        sex INT(1) NOT NULL,
        bio VARCHAR(150),
        pfp VARCHAR(250),
        password VARCHAR(255) NOT NULL
    )";

    if(mysqli_query($conn, $sql)){
        echo "Table 'user' created successfully!";
    }else{
        echo "ERROR creating table: " . mysqli_error($conn);
    }

    $sql = "CREATE TABLE FriendList (
        id CHAR(6),
        fname VARCHAR(30) NOT NULL,
        Friend_ID CHAR(6),
        PRIMARY KEY (id, Friend_ID),
        FOREIGN KEY (id) REFERENCES user(id),
        UNIQUE (id, Friend_ID)
    )";

    if(mysqli_query($conn, $sql)){
        echo "Table 'FriendList' created successfully!";
    }else{
        echo "ERROR creating table: " . mysqli_error($conn);
    }

    $stmt = $conn->prepare("INSERT INTO user (id, uname, email, sex, bio, pfp, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $id, $uname, $email, $sex, $bio, $pfp, $hashed_password);

    $id = "S00001";
    $uname = "Test";
    $email = "Testing@gmail.com";
    $sex = 1;
    $bio = "This is me";
    $pfp = "https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg";
    $hashed_password = password_hash("test_password", PASSWORD_DEFAULT); // 使用 password_hash 函數散列密碼
    $stmt->execute();
    echo "Initial user inserted successfully";

    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO FriendList (ID, fname, Friend_ID) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ID, $fname, $Friend_ID);

    $friends = [
        ['S00001', 'Jin', 'S00007'],
        ['S00001', 'Jojo', 'S00009'],
        ['S00001', 'Ada', 'S00003'],
        ['S00001', 'Don', 'S00006'],
        ['S00001', 'Josh', 'S00069'],
        ['S00001', 'Kelly', 'S00096'],
        ['S00001', 'Mike', 'S00051'],
        ['S00001', 'Howard', 'S00077'],
        ['S00001', 'James', 'S00056'],
        ['S00001', 'Stan', 'S00010']
    ];

    foreach ($friends as $friend) {
        list($ID, $fname, $Friend_ID) = $friend;
        $stmt->execute();
    }
    echo "Friends inserted successfully";

    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
