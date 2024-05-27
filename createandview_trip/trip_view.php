<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>trip_view.php</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .day-buttons {
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .day-buttons button {
        margin-right: 10px;
    }
    .day-buttons button:last-child {
        margin-right: 0;
    }
    .day-content {
        display: none;
    }
    .day-content.active {
        display: block;
    }
    .attraction {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        justify-content: center; 
    }
    .attraction img {
        width: 350px;
        height: 250px;
        object-fit: cover;
        margin-right: 20px;
    }
    .attraction-info {
        text-align: left;
    }
    .title-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 10vh;
        flex-direction: column;
    }
    .title {
        text-align: center;
        font-size: 30px;
    }
    .intro-text,
    .attraction-info-content {
        max-width: 45%;
        padding: .375rem .75rem;
        background-color: transparent;
        border: none;
        margin: 0 auto; 
    }
    .intro-text {
        margin-bottom: 20px;
        text-align: center;
    }
    .attraction-info-content {
        white-space: pre-wrap;
    }
</style>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itinerary_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//這段應要透過帳密登入後的id去連接自己的行程id
$itineraryId = 2; // 設定要查看的行程id

$sql = "SELECT * FROM itineraries WHERE id = $itineraryId";
$result = $conn->query($sql);
$itineraryTitle = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $itineraryTitle = $row['title'];
}
?>

<div class="title-container">
    <h1 class="title"><?php echo htmlspecialchars($itineraryTitle); ?></h1>
</div>

<div class="container">
    <div class="day-buttons" id="day-buttons">
        <?php
        $sql = "SELECT DISTINCT day FROM attractions WHERE itinerary_id = $itineraryId ORDER BY day ASC";
        $result = $conn->query($sql);
        $dayCount = 0;
        while ($row = $result->fetch_assoc()) {
            $dayCount++;
            echo '<button type="button" class="btn btn-primary" onclick="showDay(' . $dayCount . ')">第' . $dayCount . '天</button>';
        }
        ?>
    </div>

    <div id="days-content">
        <?php
        for ($day = 1; $day <= $dayCount; $day++) {
            echo '<div id="day' . $day . '" class="day-content ' . ($day == 1 ? 'active' : '') . '">';
            
            $sql = "SELECT * FROM attractions WHERE itinerary_id = $itineraryId AND day = $day";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="intro-text-container">';
                echo '<div class="intro-text">' . nl2br(htmlspecialchars($row['intro'])) . '</div>';
                echo '</div>';
                echo '<div class="attractions-list">';
                
                do {
                    echo '<div class="attraction">';
                    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Attraction Image">';
                    echo '<div class="attraction-info">';
                    echo '<div class="attraction-info-content">' . htmlspecialchars($row['name']) . '</div>';
                    echo '<div class="attraction-info-content">' . nl2br(htmlspecialchars($row['description'])) . '</div>';
                    echo '</div>';
                    echo '</div>';
                } while ($row = $result->fetch_assoc());
                
                echo '</div>';
            }
            
            echo '</div>';
        }
        ?>
    </div>
</div>

<script>
    function showDay(day) {
        const days = document.querySelectorAll('.day-content');
        days.forEach(dayContent => {
            dayContent.classList.remove('active');
        });
        document.getElementById('day' + day).classList.add('active');
    }
</script>

</body>
</html>

<?php $conn->close(); ?>
