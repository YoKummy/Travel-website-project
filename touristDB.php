<?php
$servername = "localhost";
$username = "root";
$password = "1040501"; //記得改成自己的密碼
$conn = mysqli_connect($servername, $username, $password);

if(!$conn){
    die("Connection failed: ".mysqli_connect_errno());
}

$sql = "CREATE DATABASE IF NOT EXISTS touristDB";
if(!mysqli_query($conn, $sql)){
    echo "ERROR creating database: " . mysqli_error($conn);
}

$dbname = "touristDB";
$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "CREATE TABLE IF NOT EXISTS attraction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number FLOAT, /*景點的先後順序 */
    tname VARCHAR(255) NOT NULL, /*行程名稱*/
    uname VARCHAR(30) NOT NULL, /*使用者名稱*/
    aname VARCHAR(30) NOT NULL, /*景點名稱*/
    description TEXT, /*景點描述 */
    image VARCHAR(255), /*景點圖片 */
    trip_day INT NOT NULL /*景點所在的天數 */
    )";

    if(!mysqli_query($conn, $sql)){
        echo "ERROR : " . mysqli_error($conn);
    }

$sql = "CREATE TABLE IF NOT EXISTS user (
    uname VARCHAR(20) PRIMARY KEY UNIQUE,
    email VARCHAR(30) NOT NULL,
    sex INT(1) NOT NULL,
    bio VARCHAR(150),
    pfp VARCHAR(400),
    pwd VARCHAR(255)
    )";

    if(!mysqli_query($conn, $sql)){
    echo "ERROR : " . mysqli_error($conn);
}

$sql = "CREATE TABLE IF NOT EXISTS FriendList (
    uname VARCHAR(20) NOT NULL UNIQUE,
    fname VARCHAR(30) NOT NULL,
    PRIMARY KEY (uname, fname),
    FOREIGN KEY (uname) REFERENCES user(uname)
)";

if(!mysqli_query($conn, $sql)){
    echo "ERROR : " . mysqli_error($conn);
}

$sql = "CREATE TABLE IF NOT EXISTS trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    transport VARCHAR(255) NOT NULL,
    image_url VARCHAR(255), /*行程封面 */
    userId CHAR(6), /*創建行程的使用者*/
    total_date INT, /*行程的總天數*/
    average_score DECIMAL(2,1),
    rating_num INT
)";

if(!mysqli_query($conn, $sql)){
    echo "ERROR : " . mysqli_error($conn);
}

$sql = "CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_name VARCHAR(255) NOT NULL,
    comment VARCHAR(255),
    uname VARCHAR(20) UNIQUE
)";

if(!mysqli_query($conn, $sql)){
    echo "ERROR : " . mysqli_error($conn);
}

$sql = "CREATE TABLE IF NOT EXISTS day_description (
    PRIMARY KEY (uname, tname, trip_day),
    uname VARCHAR(255),
    tname VARCHAR(255),
    trip_day INT,
    day_description VARCHAR(255)
)";

if(!mysqli_query($conn, $sql)){
    echo "ERROR : " . mysqli_error($conn);
}
?>
