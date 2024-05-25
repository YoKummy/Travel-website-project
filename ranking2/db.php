<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel_Database</title>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "5253"; //!!!
    
    $conn = mysqli_connect($servername, $username, $password);

    if(!$conn){
        die("Connection failed: ".mysqli_connect_errno());
    }
    echo "Connected successfully";
    

    $sql = "CREATE DATABASE TravelDB";
    if(mysqli_query($conn, $sql)){
        echo "Database created successfully!";
    }else{
        echo "ERROR creating database: " . mysqli_error($conn);
    } 

    $sql = "CREATE TABLE Ranking (
        travelname VARCHAR(30) PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        ranknum Int(5) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

    $dbname="TravelDB";
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }

    if (mysqli_query($conn, $sql)) {
        echo "Table User created successfully";
      } else {
        echo "Error creating table: " . mysqli_error($conn);
      }
    
    $dbname="TravelDB";
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO Ranking (travelname, username, ranknum)
    VALUES ('Taipei', 'Admin', '5')";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    ?>
</body>
</html>