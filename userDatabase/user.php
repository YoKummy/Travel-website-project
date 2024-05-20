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
    $password = "Put_Your_Password_Here"; //!!!
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

    $sql = "CREATE TABLE User (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        passwd VARCHAR(30) NOT NULL,
        adres VARCHAR(50),
        email VARCHAR(50) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

    $dbname="userDB";
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }

    if (mysqli_query($conn, $sql)) {
        echo "Table User created successfully";
      } else {
        echo "Error creating table: " . mysqli_error($conn);
      }
    
    $dbname="userDB";
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO user (username, passwd, adres, email)
    VALUES ('Admin', 'Admin', 'Taiwan City','testing@gmail.com')";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    $sql = "CREATE TABLE Friend_List (
      username VARCHAR(30) NOT NULL,
      Friend_ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY
      )";

    $dbname="userDB";
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }

    if (mysqli_query($conn, $sql)) {
        echo "Table User created successfully";
      } else {
        echo "Error creating table: " . mysqli_error($conn);
      }

    $dbname="userDB";
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO Friend_List (username, Friend_ID)
    VALUES ('Admin', '000002')";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    ?>
</body>
</html>