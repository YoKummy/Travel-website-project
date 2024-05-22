<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attraction_Database</title>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "0305";
    $conn = mysqli_connect($servername, $username, $password);

    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }

    $sql = "CREATE DATABASE IF NOT EXISTS AttractionDB";
    if(!mysqli_query($conn, $sql)){
        echo "ERROR creating database: " . mysqli_error($conn);
    } 

    $sql = "CREATE TABLE IF NOT EXISTS attraction (
        id INT(6) AUTO_INCREMENT PRIMARY KEY,
        tname VARCHAR(255) NOT NULL, /*行程名稱*/
        uname VARCHAR(30) NOT NULL, /*使用者名稱*/
        aname VARCHAR(30) NOT NULL, /*景點名稱*/
        trip_day INT(3) NOT NULL,
        total_day INT(3) NOT NULL
        )";

    $dbname = "AttractionDB";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }

    if(!mysqli_multi_query($conn, $sql)){
        echo "ERROR : " . mysqli_error($conn);
    }

    $stmt = $conn->prepare("INSERT INTO attraction (id, tname, uname, aname, trip_day, total_day) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $id, $tname, $uname, $aname, $trip_day, $total_day);

    $id = "S00001";
    $tname = "桃園二日遊";
    $uname = "Test";
    $aname = "中央大學";
    $trip_day = 1;
    $total_day = 2;
    $stmt->execute();
    
    $id = "S00001";
    $tname = "桃園二日遊";
    $uname = "Test";
    $aname = "依仁堂";
    $trip_day = 1;
    $total_day = 2;
    $stmt->execute();

    $id = "S00001";
    $tname = "桃園二日遊";
    $uname = "Test";
    $aname = "友好之櫻";
    $trip_day = 2;
    $total_day = 2;
    $stmt->execute();

    $id = "S00001";
    $tname = "桃園一日遊";
    $uname = "Test";
    $aname = "太極草坪";
    $trip_day = 1;
    $total_day = 1;
    $stmt->execute();
    $stmt->close();
    ?>
</body>
</html>