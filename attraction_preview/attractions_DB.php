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
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_number FLOAT, /*景點的先後順序 */
        tname VARCHAR(255) NOT NULL, /*行程名稱*/
        uname VARCHAR(30) NOT NULL, /*使用者名稱*/
        aname VARCHAR(30) NOT NULL, /*景點名稱*/
        trip_day INT NOT NULL
        )";

    $dbname = "AttractionDB";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }

    if(!mysqli_query($conn, $sql)){
        echo "ERROR : " . mysqli_error($conn);
    }
    ?>
</body>
</html>