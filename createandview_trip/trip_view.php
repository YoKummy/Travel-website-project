<?php
session_start();
$isLoggedIn = isset($_SESSION['username']); // 是否登入
$uname = $_SESSION['username']; //記錄登入的用戶
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>trip_view.php</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
<!-- <link rel="stylesheet" href="style_trip_view.css"> -->
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

    /* itinerary popup */
    /* .iti_popup {
        position: fixed;
        top: 50%;
        left: 50%;
        height: 85vh;
        width: 107vh;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        display: block; 
    }*/ 

    .iti_popup-content { 
        height: 100%;
        overflow-y: auto;
        text-align: center;
    }

    /* 評價 */
    .selected {
        color: red; 
    }

    /* comment section */
    @import url("https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600&display=swap");
    /* 
    :root {
        --Moderate-blue: hsl(238, 40%, 52%);
        --Soft-Red: hsl(358, 79%, 66%);
        --Light-grayish-blue: hsl(239, 57%, 85%);
        --Pale-red: hsl(357, 100%, 86%);

        --Dark-blue: hsl(212, 24%, 26%);
        --Grayish-Blue: hsl(211, 10%, 45%);
        --Light-gray: hsl(223, 19%, 93%);
        --Very-light-gray: hsl(228, 33%, 97%);
        --White: hsl(0, 0%, 100%);
    }
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: "Rubik", sans-serif;
        font-size: 16px;
    }
    p{
        line-height: 1.5;
    }
    body{
        height: 100%;
        width: 100%;
        padding-top: 3rem;
        background-color: var(--Very-light-gray);
    }
    a{
        cursor: pointer;
        text-decoration: none;
        font-weight: 500;
    }
    button{
        cursor: pointer;
    }
    button:hover{
        filter: saturate(80%);
    } */

    #comment-section-container {
        --Moderate-blue: hsl(238, 40%, 52%);
        --Soft-Red: hsl(358, 79%, 66%);
        --Light-grayish-blue: hsl(239, 57%, 85%);
        --Pale-red: hsl(357, 100%, 86%);

        --Dark-blue: hsl(212, 24%, 26%);
        --Grayish-Blue: hsl(211, 10%, 45%);
        --Light-gray: hsl(223, 19%, 93%);
        --Very-light-gray: hsl(228, 33%, 97%);
        --White: hsl(0, 0%, 100%);

        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: "Rubik", sans-serif;
        font-size: 16px;
    /* 
        width: 80%;
        height:100%; */
    }

    #comment-section-container p {
        list-style: 1.5;
    }

    #comment-section-container body{
        height: 100%;
        width: 100%;
        padding-top: 3rem;
        background-color: var(--Very-light-gray);
    }
    #comment-section-container a{
        cursor: pointer;
        text-decoration: none;
        font-weight: 500;
    }
    #comment-section-container button{
        cursor: pointer;
    }
    #comment-section-container button:hover{
        filter: saturate(80%);
    }

    .bu-primary{
        background-color: var(--Moderate-blue);
        color: var(--White);
        border: none;
        padding: .75rem 1.5rem;
        border-radius: 4px;
    }
    .comment-section{
        padding: 0 1rem;
    }
    .container{
        border-radius: 8px;
        padding: 1.5rem;
        background-color: var(--White);
    }
    .comments-wrp {
        display: flex;
        flex-direction: column;
    }
    .comment-section{
        max-width: 75ch;
        margin: auto;
        margin-top: 1rem;
    }
    .comment{
        margin-bottom: 1rem;
        display: grid;
        grid-template-areas: 
            "score user controls"
            "score comment comment"
            "score comment comment"
        ;
        grid-template-columns: auto 1fr auto;
        gap: 1.5rem;
        row-gap: 1rem;
        color: var(--Grayish-Blue);
    }

    .c-score{
        color: var(--Moderate-blue);
        font-weight: 500;
        grid-area: score;
        display: flex;
        align-items: center;
        flex-direction: column;
        gap: 1rem;
        padding: .75rem;
        padding-top: .5rem;
        width: 1rem;
        box-sizing: content-box;
        background-color: var(--Very-light-gray);
        border-radius: 8px;
        align-self: flex-start;
    }
    .score-control{
        width: 100%;
        cursor: pointer;
        object-fit: scale-down;
    }
    .c-text{
        grid-area: comment;
        width: 100%;
    }
    .c-user{
        width: 100%;
        grid-area: user;
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .usr-name{
        color: var(--Dark-blue);
        font-weight: 700;
    }
    .usr-img{
        height: 2rem;
        width: 2rem;
    }

    .c-controls{
        display: flex;
        gap: 1rem;
        color: var(--Moderate-blue);
        grid-area: controls;
        align-self: center;
        justify-self: flex-end;
    }
    .c-controls a{
        align-items: center;
    }
    .edit , .reply{
        color: var(--Moderate-blue);
    }
    .edit{
        display: none;
    }
    .delete{
        color: var(--Soft-Red);
        display: none;
    }
    .control-icon{
        margin-right: .5rem;
    }

    .replies{
        display: flex;
        margin-left: 2.5rem;
        padding-left: 2.4rem;
        border-left: 1px solid var(--Light-grayish-blue);
    }

    .reply-to{
        color: var(--Moderate-blue);
        font-weight: 500;
    }

    .reply-input{
        display: grid;
        margin-bottom: 1rem;
        grid-template-areas: "avatar input button";
        grid-template-columns: min-content auto min-content;
        justify-items: center;
        gap: 1rem;
        min-height: 9rem;
    }
    .reply-input img{
        grid-area: avatar;
        height: 2.5rem;
        width: 2.5rem;
    }
    .reply-input button{
        grid-area: button;
        align-self: flex-start;
    }
    .reply-input textarea{
        grid-area: input;
        padding: 1rem;
        width: 100%;
        border: 1px solid var(--Light-gray);
        border-radius: 4px;
        resize: none;
    }

    .this-user .usr-name::after{
        font-weight: 400;
        content: "you";
        color: var(--White);
        background-color: var(--Moderate-blue);
        padding: 0 .4rem;
        padding-bottom: .2rem;
        font-size: .8rem;
        margin-left: .5rem;
        border-radius: 2px;
    }

    .this-user .reply{
        display: none;
    }
    .this-user .edit , .this-user .delete{
        display: flex;
    }

    @media screen and (max-width:640px) {
        .container{
            padding: .75rem;
        }
        .replies{
            padding-left: 1rem;
            margin-left: .5rem;
        }
        .comment{
            grid-template-areas: 
                "user user user"
                "comment comment comment"
                "score ... controls"
            ;

            gap: .5rem;
        }
        .c-score{
            flex-direction: row;
            width: auto;
        }
        .reply-input{
            grid-template-areas: 
                "input input input"
                "avatar ... button"
            ;
            grid-template-rows: auto min-content;
            align-items: center;
            gap: .5rem;
        }
        .reply-input img{
            height: 2rem;
            width: 2rem;
        }
        .reply-input textarea{
            height: 6rem;
            padding: .5rem;
            align-self: stretch;
        }
    }


    .modal-wrp{
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0,0,0,.3);
    }

    .modal{
        padding: 1.5rem;
        max-width: 32ch;
        display: grid;
        gap: 1rem;
        grid-template-areas: 
        "heading heading"
        "body body"
        "no yes";
    }

    .invisible{
        display: none;
    }

    .modal h3{
        grid-area: heading;
        color: var(--Dark-blue);
    }
    .modal button{
        color: var(--White);
        padding: .75rem;
        border-radius: 8px;
        border: none;
        font-weight: 500;
    }
    .modal p{
        grid-area: body;
        color: var(--Grayish-Blue);
        line-height: 1.5;
    }
    .modal .yes{
        grid-area: yes;
        background-color: var(--Soft-Red);
    }
    .modal .no{
        background-color: var(--Grayish-Blue);
        grid-area: no;
    }
</style>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "1040501";
$dbname = "touristDB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tname = $_GET['tname']
/* $itineraryId = 1; // 測資行程id
$sql = "SELECT * FROM trips WHERE id = $itineraryId";
$result = $conn->query($sql);
$itineraryTitle = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $itineraryTitle = $row['title'];
} */
?>

<div class="title-container">
    <h1 class="title"><?php echo htmlspecialchars($tname); ?></h1>
</div>

<div class="container">
    <div class="day-buttons" id="day-buttons">
        <?php
        $sql = "SELECT DISTINCT trip_day FROM attraction WHERE tname = '$tname' ORDER BY trip_day ASC";
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
            
            $sql = "SELECT * FROM attraction WHERE tname = $tname AND trip_day = $day";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="intro-text-container">';
                echo '<div class="intro-text">' . nl2br(htmlspecialchars($row['intro'])) . '</div>'; /* trips裡面沒有intro */
                echo '</div>';
                echo '<div class="attractions-list">';
                
                do {
                    echo '<div class="attraction">';
                    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Attraction Image">';
                    echo '<div class="attraction-info">';
                    echo '<div class="attraction-info-content">' . htmlspecialchars($row['aname']) . '</div>';
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


<div class="iti_review-content">
    <h2 id="iti_title"><?php $tname;?></h2>
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
        <script type="text/javascript">
            document.cookie = "ratingValue";
        </script>

        <?php 
        $avg_rating = 0; // 初始化變數
        $rating_num = 0;

        $sql = "SELECT average_score, rating_num FROM trips WHERE trip_name = '$tname'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $avg_rating = $row['average_score'];
            $rating_num = $row['rating_num'];
        }

        $ratingValue = intval($_COOKIE["ratingValue"]);
        $avg_rating = ($ratingValue + $avg_rating * $rating_num) / ($rating_num + 1);
        $rating_num ++;

        $stmt = $conn->prepare("INSERT INTO trips (average_score, rating_num) VALUES (?, ?)");
        $stmt->bind_param("ii", $avg_rating, $rating_num);
        $stmt->execute();
        $stmt->close();
        ?>
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

<!-- <script src="index_trips.js"></script> -->
<script>
    //view trip js
    function showDay(day) {
        const days = document.querySelectorAll('.day-content');
        days.forEach(dayContent => {
            dayContent.classList.remove('active');
        });
        document.getElementById('day' + day).classList.add('active');
    }

    //rating js
    const stars = document.querySelector(".rating").children;
    let ratingValue;
    let index = -1; 
    document.getElementById("rating-value").innerHTML = "請對此旅行評分";
    for (let i = 0; i < stars.length; i++) {
    stars[i].addEventListener("mouseover", function () {
        document.getElementById("rating-value").innerHTML = "正在打分數...";
        for (let j = 0; j < stars.length; j++) {
        stars[j].classList.remove("fa-star");
        stars[j].classList.add("fa-star-o");
        }
        for (let j = 0; j <= i; j++) {
        stars[j].classList.remove("fa-star-o");
        stars[j].classList.add("fa-star", "selected");
        }
    });
    stars[i].addEventListener("click", function () {
        ratingValue = i + 1;
        index = i;
        document.getElementById("rating-value").innerHTML = "你打的分數是 " + ratingValue;
    });
    stars[i].addEventListener("mouseout", function () {
        for (let j = 0; j < stars.length; j++) {
        stars[j].classList.remove("fa-star");
        stars[j].classList.add("fa-star-o");
        }
        for (let j = 0; j <= index; j++) {
        stars[j].classList.remove("fa-star-o");
        stars[j].classList.add("fa-star", "selected");
        }
    });
    }

    document.getElementById("submit-btn").addEventListener("click", function () {
    if (typeof ratingValue !== "undefined" && ratingValue !== null) {
        console.log("您提交的評分是: " + ratingValue);
        alert("您提交的評分是: " + ratingValue);
    } else {
        alert("請先選擇評分！");
    }
    });

    /* $.ajax({
url: 'getTrip.php',
type: 'GET',
dataType: 'json',
success: function(data) {
    
},
error: function() {
    console.log('無法獲取行程資料');
}
});
} */

    //comment section js
    const data = {
    currentUser: {
        image: {
        png: "comments-section/images/avatars/image-juliusomo.png",
        webp: "comments-section/images/avatars/image-juliusomo.webp",
        },
        username: "juliusomo",
    },
    comments: [
        {
        parent: 0,
        id: 1,
        content:
            "Impressive! Though it seems the drag feature could be improved. But overall it looks incredible. You've nailed the design and the responsiveness at various breakpoints works really well.",
        createdAt: "1 month ago",
        score: 12,
        user: {
            image: {
            png: "comments-section/images/avatars/image-amyrobson.png",
            webp: "comments-section/images/avatars/image-amyrobson.webp",
            },
            username: "amyrobson",
        },
        replies: [],
        },
        {
        parent: 0,
        id: 2,
        content:
            "Woah, your project looks awesome! How long have you been coding for? I'm still new, but think I want to dive into React as well soon. Perhaps you can give me an insight on where I can learn React? Thanks!",
        createdAt: "2 weeks ago",
        score: 5,
        user: {
            image: {
            png: "comments-section/images/avatars/image-maxblagun.png",
            webp: "comments-section/images/avatars/image-maxblagun.webp",
            },
            username: "maxblagun",
        },
        replies: [
            {
            parent: 2,
            id: 1,
            content:
                "If you're still new, I'd recommend focusing on the fundamentals of HTML, CSS, and JS before considering React. It's very tempting to jump ahead but lay a solid foundation first.",
            createdAt: "1 week ago",
            score: 4,
            replyingTo: "maxblagun",
            user: {
                image: {
                png: "comments-section/images/avatars/image-ramsesmiron.png",
                webp: "comments-section/images/avatars/image-ramsesmiron.webp",
                },
                username: "ramsesmiron",
            },
            },
            {
            parent: 2,
            id: 1,
            content:
                "I couldn't agree more with this. Everything moves so fast and it always seems like everyone knows the newest library/framework. But the fundamentals are what stay constant.",
            createdAt: "2 days ago",
            score: 2,
            replyingTo: "ramsesmiron",
            user: {
                image: {
                png: "comments-section/images/avatars/image-juliusomo.png",
                webp: "comments-section/images/avatars/image-juliusomo.webp",
                },
                username: "juliusomo",
            },
            },
        ],
        },
    ],
    };

    function appendFrag(frag, parent) {
    var children = [].slice.call(frag.childNodes, 0);
    parent.appendChild(frag);
    //console.log(children);
    return children[1];
    }

    const addComment = (body, parentId, replyTo = undefined) => {
    let commentParent =
        parentId === 0
        ? data.comments
        : data.comments.filter((c) => c.id == parentId)[0].replies;
    let newComment = {
        parent: parentId,
        id:
        commentParent.length == 0
            ? 1
            : commentParent[commentParent.length - 1].id + 1,
        content: body,
        createdAt: "Now",
        replyingTo: replyTo,
        score: 0,
        replies: parent == 0 ? [] : undefined,
        user: data.currentUser,
    };
    commentParent.push(newComment);
    initComments();
    };
    const deleteComment = (commentObject) => {
    if (commentObject.parent == 0) {
        data.comments = data.comments.filter((e) => e != commentObject);
    } else {
        data.comments.filter((e) => e.id === commentObject.parent)[0].replies =
        data.comments
            .filter((e) => e.id === commentObject.parent)[0]
            .replies.filter((e) => e != commentObject);
    }
    initComments();
    };

    const promptDel = (commentObject) => {
    const modalWrp = document.querySelector(".modal-wrp");
    modalWrp.classList.remove("invisible");
    modalWrp.querySelector(".yes").addEventListener("click", () => {
        deleteComment(commentObject);
        modalWrp.classList.add("invisible");
    });
    modalWrp.querySelector(".no").addEventListener("click", () => {
        modalWrp.classList.add("invisible");
    });
    };

    const spawnReplyInput = (parent, parentId, replyTo = undefined) => {
    if (parent.querySelectorAll(".reply-input")) {
        parent.querySelectorAll(".reply-input").forEach((e) => {
        e.remove();
        });
    }
    const inputTemplate = document.querySelector(".reply-input-template");
    const inputNode = inputTemplate.content.cloneNode(true);
    const addedInput = appendFrag(inputNode, parent);
    addedInput.querySelector(".bu-primary").addEventListener("click", () => {
        let commentBody = addedInput.querySelector(".cmnt-input").value;
        if (commentBody.length == 0) return;
        addComment(commentBody, parentId, replyTo);
    });
    };

    const createCommentNode = (commentObject) => {
    const commentTemplate = document.querySelector(".comment-template");
    var commentNode = commentTemplate.content.cloneNode(true);
    commentNode.querySelector(".usr-name").textContent = commentObject.user.username;
    commentNode.querySelector(".usr-img").src = commentObject.user.image.webp;
    commentNode.querySelector(".score-number").textContent = commentObject.score;
    commentNode.querySelector(".cmnt-at").textContent = commentObject.createdAt;
    commentNode.querySelector(".c-body").textContent = commentObject.content;
    if (commentObject.replyingTo)
        commentNode.querySelector(".reply-to").textContent =
        "@" + commentObject.replyingTo;

    commentNode.querySelector(".score-plus").addEventListener("click", () => {
        commentObject.score++;
        initComments();
    });

    commentNode.querySelector(".score-minus").addEventListener("click", () => {
        commentObject.score--;
        if (commentObject.score < 0) commentObject.score = 0;
        initComments();
    });
    if (commentObject.user.username == data.currentUser.username) {
        commentNode.querySelector(".comment").classList.add("this-user");
        commentNode.querySelector(".delete").addEventListener("click", () => {
        promptDel(commentObject);
        });
        commentNode.querySelector(".edit").addEventListener("click", (e) => {
        const path = e.path[3].querySelector(".c-body");
        if (
            path.getAttribute("contenteditable") == false ||
            path.getAttribute("contenteditable") == null
        ) {
            path.setAttribute("contenteditable", true);
            path.focus()
        } else {
            path.removeAttribute("contenteditable");
        }
        
        });
        return commentNode;
    }
    return commentNode;
    };

    const appendComment = (parentNode, commentNode, parentId) => {
    const bu_reply = commentNode.querySelector(".reply");
    // parentNode.appendChild(commentNode);
    const appendedCmnt = appendFrag(commentNode, parentNode);
    const replyTo = appendedCmnt.querySelector(".usr-name").textContent;
    bu_reply.addEventListener("click", () => {
        if (parentNode.classList.contains("replies")) {
        spawnReplyInput(parentNode, parentId, replyTo);
        } else {
        //console.log(appendedCmnt.querySelector(".replies"));
        spawnReplyInput(
            appendedCmnt.querySelector(".replies"),
            parentId,
            replyTo
        );
        }
    });
    };

    function initComments(
    commentList = data.comments,
    parent = document.querySelector(".comments-wrp")
    ) {
    parent.innerHTML = "";
    commentList.forEach((element) => {
        var parentId = element.parent == 0 ? element.id : element.parent;
        const comment_node = createCommentNode(element);
        if (element.replies && element.replies.length > 0) {
        initComments(element.replies, comment_node.querySelector(".replies"));
        }
        appendComment(parent, comment_node, parentId);
    });
    }

    initComments();
    const cmntInput = document.querySelector(".reply-input");
    cmntInput.querySelector(".bu-primary").addEventListener("click", () => {
    let commentBody = cmntInput.querySelector(".cmnt-input").value;
    if (commentBody.length == 0) return;
    addComment(commentBody, 0);
    cmntInput.querySelector(".cmnt-input").value = "";
    });

    // addComment("Hello ! It works !!",0);

</script>

</body>
</html>

<?php $conn->close(); ?>
