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
    $password = "1225";
    $conn = mysqli_connect($servername, $username, $password);

    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }

    /* $sql = "CREATE DATABASE IF NOT EXISTS AttractionDB";
    if(!mysqli_query($conn, $sql)){
        echo "ERROR creating database: " . mysqli_error($conn);
    }  */

    $sql = "CREATE TABLE IF NOT EXISTS attraction (
        id VARCHAR(6) NOT NULL,
        order_number INT(2) NOT NULL, /*景點的先後順序 */
        tname VARCHAR(255) NOT NULL, /*行程名稱*/
        uname VARCHAR(30) NOT NULL, /*使用者名稱*/
        aname VARCHAR(30) NOT NULL, /*景點名稱*/
        trip_day INT(2) NOT NULL,
        PRIMARY KEY (id, tname, order_number),
        FOREIGN KEY (id) REFERENCES user(id)
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

    $stmt = $conn->prepare("INSERT INTO attraction (id, order_number, tname, uname, aname, trip_day) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssi", $id, $order_number, $tname, $uname, $aname, $trip_day);

    $id = "S00001";
    $order_number = 1;
    $tname = "桃園二日遊";
    $uname = "Test";
    $aname = "埔心牧場";
    $trip_day = 1;
    $stmt->execute();
    
    $id = "S00001";
    $order_number = 2;
    $tname = "桃園二日遊";
    $uname = "Test";
    $aname = "中壢觀光夜市";
    $trip_day = 1;
    $stmt->execute();

    $id = "S00001";
    $order_number = 3;
    $tname = "桃園二日遊";
    $uname = "Test";
    $aname = "光明公園";
    $trip_day = 2;
    $stmt->execute();

    $id = "S00001";
    $order_number = 1;
    $tname = "桃園一日遊";
    $uname = "Test";
    $aname = "中壢中正公園";
    $trip_day = 1;
    $stmt->execute();
    $stmt->close();
    ?>
</body>
</html>