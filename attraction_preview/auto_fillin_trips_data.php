<?php
$servername = "localhost";
$username = "root";
$password = "0305";
$dbname = "touristDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Data to insert
$data = [
    [
        "id" => 001,
        "trip_name" => "A001",
        "start_date" => "2024-05-29",
        "end_date" => "2024-05-30",
        "transport" => "飛機",
        "image_url" => "img\Taipei101_dayview.jpg",
        "userId" => "S00001",
        "total_date" => 2,
        "average_score" => 4.5,
        "rating_num" => 50
    ],
    [
        "id" => 002,
        "trip_name" => "A002",
        "start_date" => "2024-05-29",
        "end_date" => "2024-05-31",
        "transport" => "機車",
        "image_url" => "img\Taipei101_dayview.jpg",
        "userId" => "S00002",
        "total_date" => 3,
        "average_score" => 4.0,
        "rating_num" => 100
    ],
    [
        "id" => 003,
        "trip_name" => "A003",
        "start_date" => "2024-05-29",
        "end_date" => "2024-06-01",
        "transport" => "火車",
        "image_url" => "img\Taipei101_dayview.jpg",
        "userId" => "S00003",
        "total_date" => 4,
        "average_score" => 0.0,
        "rating_num" => 0
    ],
    [
        "id" => 004,
        "trip_name" => "A004",
        "start_date" => "2024-05-29",
        "end_date" => "2024-06-02",
        "transport" => "高鐵",
        "image_url" => "img\Taipei101_dayview.jpg",
        "userId" => "S00004",
        "total_date" => 5,
        "average_score" => 3.5,
        "rating_num" => 75
    ],
    [
        "id" => 005,
        "trip_name" => "A005",
        "start_date" => "2024-05-29",
        "end_date" => "2024-06-03",
        "transport" => "開車",
        "image_url" => "img\Taipei101_dayview.jpg",
        "userId" => "S00005",
        "total_date" => 6,
        "average_score" => 5.0,
        "rating_num" => 150
    ],
    [
        "id" => 006,
        "trip_name" => "A006",
        "start_date" => "2024-05-29",
        "end_date" => "2024-06-04",
        "transport" => "其他",
        "image_url" => "img\Taipei101_dayview.jpg",
        "userId" => "S00006",
        "total_date" => 7,
        "average_score" => 2.5,
        "rating_num" => 25
    ],
];

foreach ($data as $item) {
    $stmt = $conn->prepare("INSERT INTO trips (id, trip_name, start_date, end_date, transport, image_url, userId, total_date, average_score, rating_num) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "issssssiii",
        $item['id'],
        $item['trip_name'],
        $item['start_date'],
        $item['end_date'],
        $item['transport'],
        $item['image_url'],
        $item['userId'],
        $item['total_date'],
        $item['average_score'],
        $item['rating_num']
    );

    if ($stmt->execute()) {
        echo "New record created successfully\n";
    } else {
        echo "Error: " . $stmt->error . "\n";
    }

    $stmt->close();
}

$conn->close();
?>
