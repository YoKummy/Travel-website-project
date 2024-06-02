<?php
session_start();
$isLoggedIn = isset($_SESSION['username']); // 是否登入
$uname = $_SESSION['username']; //記錄登入的用戶
$userId = isset($_GET['userId'])? $_GET['userId'] : null; //紀錄被查看個人檔案的用戶
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>trip_create.html</title>
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
        cursor: pointer;
    }
    .attraction-info {
        text-align: left;
    }
    .add-btns {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .file-input {
        display: none;
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
    .title input {
        font-size: inherit;
        text-align: center;
        width: auto;
        border: none;
        background: transparent;
    }
    .submit-btn, .cancel-btn {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .intro-textarea {
        max-width: 45%;
        margin: 0 auto;
    }
    .cancel-btn-container {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .btn-cancel {
        background-color: red;
        color: white;
    }
    .btn-remove-day {
        background-color: red;
        color: white;
    }
</style>
</head>
<body>

<div class="cancel-btn-container">
    <button type="button" class="btn btn-cancel" onclick="cancelCreation()">取消</button>
</div>

<form method="post" action="trip_create.php" enctype="multipart/form-data">
    <div class="title-container">
        <h1 class="title">
            <input type="text" id="itinerary-title" name="itinerary-title" class="form-control text-center" placeholder="行程名稱">
        </h1>
    </div>

    <div class="container">
        <div class="day-buttons" id="day-buttons">
            <!-- 初始天數按鈕 -->
            <button type="button" class="btn btn-primary" onclick="showDay(1)">第1天</button>
            <button type="button" class="btn btn-secondary" id="add-day-btn" onclick="addDay()">新增天數</button>
            <button type="button" class="btn btn-remove-day" style="display:none;" id="remove-day-btn" onclick="removeDay()">取消天數</button>
        </div>

        <div id="days-content">
            <div id="day1" class="day-content active">
                <textarea name="days[0][intro]" class="form-control mb-3 intro-textarea" rows="3" placeholder="編輯第1天的簡介"></textarea>
                <div class="attractions-list" id="day1-attractions">
                    <div class="attraction" id="day1-attraction0">
                        <input type="file" name="days[0][attractions][0][image]" class="file-input" accept="image/*" onchange="loadImage(event, this)">
                        <img src="https://via.placeholder.com/350x250" alt="Attraction 1" onclick="triggerFileInput(this)">
                        <div class="attraction-info">
                            <input type="text" name="days[0][attractions][0][name]" class="form-control mb-2" placeholder="景點名稱">
                            <textarea name="days[0][attractions][0][description]" class="form-control" rows="2" placeholder="景點描述"></textarea>
                        </div>
                    </div>
                </div>
                <div class="add-btns" id="day1-btns">
                    <button type="button" class="btn btn-secondary" onclick="addAttraction('day1', 0)">新增景點</button>
                </div>
            </div>
        </div>

        <div class="submit-btn">
            <button type="submit" class="btn btn-success">提交</button>
        </div>
        <input type="hidden" id="dayCount" name="dayCount" value="1">
    </div>
</form>

<script>
    let dayCount = 1;

    function showDay(day) {
        const days = document.querySelectorAll('.day-content');
        days.forEach(dayContent => {
            dayContent.classList.remove('active');
        });
        document.getElementById('day' + day).classList.add('active');
    }

    function addDay() {
        dayCount++;
        const dayButtons = document.getElementById('day-buttons');
        const addDayBtn = document.getElementById('add-day-btn');
        const removeDayBtn = document.getElementById('remove-day-btn');

        const newDayButton = document.createElement('button');
        newDayButton.type = 'button';
        newDayButton.className = 'btn btn-primary';
        newDayButton.innerText = '第' + dayCount + '天';
        newDayButton.setAttribute('onclick', 'showDay(' + dayCount + ')');
        dayButtons.insertBefore(newDayButton, addDayBtn);

        if (dayCount > 1) {
            removeDayBtn.style.display = 'inline-block';
        }

        const daysContent = document.getElementById('days-content');
        const newDayContent = document.createElement('div');
        newDayContent.id = 'day' + dayCount;
        newDayContent.className = 'day-content';
        newDayContent.innerHTML = `
            <textarea name="days[${dayCount - 1}][intro]" class="form-control mb-3 intro-textarea" rows="3" placeholder="編輯第${dayCount}天的簡介"></textarea>
            <div class="attractions-list" id="day${dayCount}-attractions">
                <div class="attraction" id="day${dayCount}-attraction0">
                    <input type="file" name="days[${dayCount - 1}][attractions][0][image]" class="file-input" accept="image/*" onchange="loadImage(event, this)">
                    <img src="https://via.placeholder.com/350x250" alt="New Attraction" onclick="triggerFileInput(this)">
                    <div class="attraction-info">
                        <input type="text" name="days[${dayCount - 1}][attractions][0][name]" class="form-control mb-2" placeholder="景點名稱">
                        <textarea name="days[${dayCount - 1}][attractions][0][description]" class="form-control" rows="2" placeholder="景點描述"></textarea>
                    </div>
                </div>
            </div>
            <div class="add-btns" id="day${dayCount}-btns">
                <button type="button" class="btn btn-secondary" onclick="addAttraction('day${dayCount}', ${dayCount - 1})">新增景點</button>
            </div>
        `;
        daysContent.appendChild(newDayContent);
        document.getElementById('dayCount').value = dayCount;
    }

    function removeDay() {
        if (dayCount > 1) {
            const dayButtons = document.getElementById('day-buttons');
            const daysContent = document.getElementById('days-content');
            const lastDayButton = dayButtons.querySelector(`button[onclick="showDay(${dayCount})"]`);
            const lastDayContent = document.getElementById(`day${dayCount}`);
            
            if (lastDayButton && lastDayContent) {
                dayButtons.removeChild(lastDayButton);
                daysContent.removeChild(lastDayContent);
                dayCount--;
                document.getElementById('dayCount').value = dayCount;
                
                if (dayCount <= 1) {
                    document.getElementById('remove-day-btn').style.display = 'none';
                }
                
                showDay(dayCount); // Show the last remaining day as active
            }
        }
    }

    function addAttraction(dayId, dayIndex) {
        const dayContent = document.getElementById(`${dayId}-attractions`);
        const attractionCount = dayContent.querySelectorAll('.attraction').length;
        const newAttraction = document.createElement('div');
        newAttraction.className = 'attraction';
        newAttraction.id = `${dayId}-attraction${attractionCount}`;
        newAttraction.innerHTML = `
            <input type="file" name="days[${dayIndex}][attractions][${attractionCount}][image]" class="file-input" accept="image/*" onchange="loadImage(event, this)">
            <img src="https://via.placeholder.com/350x250" alt="New Attraction" onclick="triggerFileInput(this)">
            <div class="attraction-info">
                <input type="text" name="days[${dayIndex}][attractions][${attractionCount}][name]" class="form-control mb-2" placeholder="景點名稱">
                <textarea name="days[${dayIndex}][attractions][${attractionCount}][description]" class="form-control" rows="2" placeholder="景點描述"></textarea>
            </div>
        `;
        dayContent.appendChild(newAttraction);

        updateAddBtns(dayId, dayIndex);
    }

    function updateAddBtns(dayId, dayIndex) {
        const addBtns = document.getElementById(`${dayId}-btns`);
        const dayContent = document.getElementById(`${dayId}-attractions`);
        const attractionCount = dayContent.querySelectorAll('.attraction').length;

        addBtns.innerHTML = `
            <button type="button" class="btn btn-secondary" onclick="addAttraction('${dayId}', ${dayIndex})">新增景點</button>
            ${attractionCount > 1 ? `<button type="button" class="btn btn-danger ms-2" onclick="removeLastAttraction('${dayId}', ${dayIndex})">取消景點</button>` : ''}
        `;
    }

    function triggerFileInput(imgElement) {
        const fileInput = imgElement.previousElementSibling;
        fileInput.click();
    }

    function loadImage(event, inputElement) {
        const imgElement = inputElement.nextElementSibling;
        imgElement.src = URL.createObjectURL(event.target.files[0]);
    }

    function cancelCreation() {
        if (confirm("你確定要取消這次的行程創建嗎？")) {
            window.location.href = 'homepage.html'; 
        }
    }

    function removeLastAttraction(dayId, dayIndex) {
        const dayContent = document.getElementById(`${dayId}-attractions`);
        const lastAttraction = dayContent.lastElementChild;

        if (lastAttraction) {
            lastAttraction.remove();
            updateAddBtns(dayId, dayIndex);
        }
    }
</script>
</body>
</html>
