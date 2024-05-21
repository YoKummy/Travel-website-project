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
    $password = "1225";
    $conn = mysqli_connect($servername, $username, $password);

    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }
    echo "Connected successfully";

    $sql = "CREATE DATABASE userDB";
    if(mysqli_query($conn, $sql)){
        echo "Database created successfully!";
    }else{
        echo "ERROR creating database: " . mysqli_error($conn);
    } 

    $sql = "CREATE TABLE user (
        id CHAR(6) PRIMARY KEY,
        uname VARCHAR(30) NOT NULL,
        email VARCHAR(30) NOT NULL,
        sex INT(1) NOT NULL,
        bio VARCHAR(150),
        pfp VARCHAR(250)
        )";

    $dbname = "userDB";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }

    if(mysqli_multi_query($conn, $sql)){
        echo "Success";
    }else{
        echo "ERROR : " . mysqli_error($conn);
    }

    $stmt = $conn->prepare("INSERT INTO user (id, uname, email, sex, bio, pfp) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $id, $uname, $email, $sex, $bio, $pfp);

    $id = "S00001";
    $uname = "Test";
    $email = "Testing@gmail.com";
    $sex = 1;
    $bio = "This is me";
    $pfp = "https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg";
    $stmt->execute();
    echo "Successfully";

    $stmt->close();

    $sql = "CREATE TABLE FriendList (
        ID CHAR(6) PRIMARY KEY,
        usname VARCHAR(30) NOT NULL,
        Friend_ID CHAR(6)
        )";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }

    if(mysqli_multi_query($conn, $sql)){
        echo "Success";
    }else{
        echo "ERROR : " . mysqli_error($conn);
    }

    $stmt = $conn->prepare("INSERT INTO FriendList (ID, usname, Friend_ID) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ID, $usname, $Friend_ID);

    $ID = '000001';
    $usname = 'Admin';
    $Friend_ID = '000002';

    $stmt->execute();
    echo "Successfully";

    $stmt->close();
    ?>
</body>
</html>