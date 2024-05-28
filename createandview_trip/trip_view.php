<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>trip_view.php</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style_trips.css">
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "1040501";
$dbname = "itinerary_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//這段應要透過帳密登入後的id去連接自己的行程id
$itineraryId = 1; // 設定要查看的行程id

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

<div class="iti_popup" id="iti_popup">
    <div class="iti_popup-content">
        <h2 id="iti_title">台北101六日遊</h2>
        <div class="rating-box">
            <h1 class="rating-boxH1">評分</h1>
            <div class="rating">
                <span class="fa fa-star-o"></span>
                <span class="fa fa-star-o"></span>
                <span class="fa fa-star-o"></span>
                <span class="fa fa-star-o"></span>
                <span class="fa fa-star-o"></span>
            </div>
            <h4 id="rating-value"></h4>
            <button id="submit-btn">提交</button>
        </div>
        <div id="comment-section-container">
            <template class="reply-input-template">
                <div class="reply-input container">
                    <img src="comments-section/images/avatars/image-juliusomo.webp" alt="" class="usr-img">
                    <textarea class="cmnt-input" placeholder="Add a comment..."></textarea>
                    <button class="bu-primary">SEND</button>
                </div>
            </template>
            
            <template class="comment-template">
                <div class="comment-wrp">
                    <div class="comment container">
                        <div class="c-score">
                        <img src="comments-section/images/icon-plus.svg" alt="plus" class="score-control score-plus">
                        <p class="score-number">5</p>
                        <img src="comments-section/images/icon-minus.svg" alt="minus" class="score-control score-minus">
                        </div>
                        <div class="c-controls">
                        <a  class="delete"><img src="comments-section/images/icon-delete.svg" alt="" class="control-icon">Delete</a>
                        <a  class="edit"><img src="comments-section/images/icon-edit.svg" alt="" class="control-icon">Edit</a>
                        <a  class="reply"><img src="comments-section/images/icon-reply.svg" alt="" class="control-icon">Reply</a>
                        </div>
                        <div class="c-user">
                        <img src="comments-section/images/avatars/image-maxblagun.webp" alt="" class="usr-img">
                        <p class="usr-name">maxblagun</p>
                        <p class="cmnt-at">2 weeks ago</p>    
                        </div>
                        <p class="c-text">
                        <span class="reply-to"></span>
                        <span class="c-body"></span>
                        </p>
                    </div><!--comment-->

                    <div class="replies comments-wrp">
                    </div><!--replies-->
                </div>
            </template>
            <main>
                <div class="comment-section">
                    <div class="comments-wrp">
                    </div> <!--commentS wrapper-->

                    <div class="reply-input container">
                        <img src="comments-section/images/avatars/image-juliusomo.webp" alt="" class="usr-img">
                        <textarea class="cmnt-input" placeholder="Add a comment..."></textarea>
                        <button class="bu-primary">SEND</button>
                    </div> <!--reply input-->
                </div> <!--comment section-->
                
                <div class="modal-wrp invisible">
                    <div class="modal container">
                        <h3>Delete comment</h3>
                        <p>Are you sure you want to delete this comment? This will remove the comment and cant be undone</p>
                        <button class="yes">YES,DELETE</button>
                        <button class="no">NO,CANCEL</button>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- <span id="iti_popup-close" class="iti_popup-close">✕</span> -->
</div>

<script src="index_trips.js"></script>

</body>
</html>

<?php $conn->close(); ?>
