<?php
    $servername = "localhost";
    $username = "root";
    $password = "1225";
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
        uname VARCHAR(20) PRIMARY KEY UNIQUE,
        email VARCHAR(30) NOT NULL,
        sex INT(1) NOT NULL,
        bio VARCHAR(150),
        pfp VARCHAR(400)
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

    $stmt = $conn->prepare("INSERT INTO user (uname, email, sex, bio, pfp) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $uname, $email, $sex, $bio, $pfp);

    $uname = "Ola";
    $email = "Ola@gmail.com";
    $sex = 1;
    $bio = "This is me";
    $pfp = "https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg";
    $stmt->execute();
    echo "Successfully";

    $uname = "Friedchicken";
    $email = "frichiken@gmail.com";
    $sex = 1;
    $bio = "chicken";
    $pfp = "https://www.allrecipes.com/thmb/SoBuPU73KcbYHl3Kp3j8Xx4A3fc=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/8805-CrispyFriedChicken-mfs-3x2-072-d55b8406d4ae45709fcdeb58a04143c2.jpg";
    $stmt->execute();
    echo "Successfully";

    $uname = "mario";
    $email = "mario@gmail.com";
    $sex = 1;
    $bio = "its a me ah mario";
    $pfp = "https://mario.nintendo.com/static/88dd0f6281674a77d052e46cc38c63f7/a320e/mario.png";
    $stmt->execute();
    echo "Successfully";

    $stmt->close();

    $sql = "CREATE TABLE FriendList (
        uname VARCHAR(20) NOT NULL,
        fname VARCHAR(30) NOT NULL,
        PRIMARY KEY (uname, fname),
        FOREIGN KEY (uname) REFERENCES user(uname),
        UNIQUE (uname, fname)
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

    $stmt = $conn->prepare("INSERT INTO FriendList (uname, fname) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $fname);

    $username = 'Ola';
    $fname = 'Jin';

    $stmt->execute();
    echo "Successfully";

    $username = 'Ola';
    $fname = 'Friedchicken';

    $stmt->execute();
    echo "Successfully";

    $stmt->close();
    ?>;