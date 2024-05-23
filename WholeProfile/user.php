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
    $password = "";
    $conn = mysqli_connect($servername, $username, $password);

    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }
    echo "Connected successfully";

    $sql = "CREATE DATABASE IF NOT EXISTS userDB";
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
        id CHAR(6),
        fname VARCHAR(30) NOT NULL,
        Friend_ID CHAR(6),
        PRIMARY KEY (id, Friend_ID),
        FOREIGN KEY (id) REFERENCES user(id),
        UNIQUE (id, Friend_ID)
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

    $stmt = $conn->prepare("INSERT INTO FriendList (ID, fname, Friend_ID) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ID, $fname, $Friend_ID);

    $ID = 'S00001';
    $fname = 'Jin';
    $Friend_ID = 'S00007';

    $stmt->execute();
    echo "Successfully";

    $ID = 'S00001';
    $fname = 'Jojo';
    $Friend_ID = 'S00009';

    $stmt->execute();
    echo "Successfully";

    $ID = 'S00001';
    $fname = 'Ada';
    $Friend_ID = 'S00003';

    $stmt->execute();
    echo "Successfully";

    $ID = 'S00001';
    $fname = 'Don';
    $Friend_ID = 'S00006';

    $stmt->execute();
    echo "Successfully";

    $ID = 'S00001';
    $fname = 'Josh';
    $Friend_ID = 'S00069';

    $stmt->execute();
    echo "Successfully";

    $ID = 'S00001';
    $fname = 'Kelly';
    $Friend_ID = 'S00096';

    $stmt->execute();
    echo "Successfully";

    $ID = 'S00001';
    $fname = 'Mike';
    $Friend_ID = 'S00051';

    $stmt->execute();
    echo "Successfully";

    $ID = 'S00001';
    $fname = 'Howard';
    $Friend_ID = 'S00077';

    $stmt->execute();
    echo "Successfully";

    $ID = 'S00001';
    $fname = 'James';
    $Friend_ID = 'S00056';

    $stmt->execute();
    echo "Successfully";

    $ID = 'S00001';
    $fname = 'Stan';
    $Friend_ID = 'S00010';

    $stmt->execute();
    echo "Successfully";

    $stmt->close();
    ?>
</body>
</html>