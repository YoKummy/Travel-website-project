<?php
session_start();
$isLoggedIn = isset($_SESSION['username']); // 是否登入
$uname = $_SESSION['username']; //記錄登入的用戶

// Database connection
$servername = "localhost";
$username = "root"; 
$password = "0305"; // Replace with your actual database password
$dbname = "touristdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch trip data
$tripId = isset($_GET['tripName']) ? $_GET['tripName'] : null;
$trip = null;
if ($tripId) {
    $tripSql = "SELECT total_date, trip_name FROM trips WHERE trip_name = ?";
    $stmt = $conn->prepare($tripSql);
    $stmt->bind_param("i", $tripId);
    $stmt->execute();
    $trip = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}


// Fetch attractions data
$attractions = [];
if ($tripId) {
    $attractionSql = "SELECT trip_day, order_number, aname FROM attraction WHERE tname = ? ORDER BY trip_day, order_number";
    $stmt = $conn->prepare($attractionSql);
    $stmt->bind_param("i", $tripId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $attractions[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trip Create</title>
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
            <input type="text" id="itinerary-title" name="itinerary-title" class="form-control text-center" placeholder="行程名稱" value="<?php echo $tripId? $tripId : '';?>">
        </h1>
    </div>

    <div class="container">
        <div class="day-buttons" id="day-buttons">
            <?php for ($i = 1; $i <= ($trip['total_date'] ?? 1); $i++): ?>
                <button type="button" class="btn btn-primary" onclick="showDay(<?php echo $i; ?>)">第<?php echo $i; ?>天</button>
            <?php endfor; ?>
            <button type="button" class="btn btn-secondary" id="add-day-btn" onclick="addDay()">新增天數</button>
            <button type="button" class="btn btn-remove-day" style="display:none;" id="remove-day-btn" onclick="removeDay()">取消天數</button>
        </div>

        <div id="days-content">
            <?php for ($i = 1; $i <= ($trip['total_date'] ?? 1); $i++): ?>
                <div id="day<?php echo $i; ?>" class="day-content <?php echo $i === 1 ? 'active' : ''; ?>">
                    <textarea name="days[<?php echo $i - 1; ?>][intro]" class="form-control mb-3 intro-textarea" rows="3" placeholder="編輯第<?php echo $i; ?>天的簡介"></textarea>
                    <div class="attractions-list" id="day<?php echo $i; ?>-attractions">
                        <?php foreach ($attractions as $index => $attraction): ?>
                            <?php if ($attraction['trip_day'] == $i): ?>
                                <div class="attraction" id="day<?php echo $i; ?>-attraction<?php echo $index; ?>">
                                    <input type="file" name="days[<?php echo $i - 1; ?>][attractions][<?php echo $index; ?>][image]" class="file-input" accept="image/*" onchange="loadImage(event, this)">
                                    <img src="https://via.placeholder.com/350x250" alt="Attraction <?php echo $index + 1; ?>" onclick="triggerFileInput(this)">
                                    <div class="attraction-info">
                                        <input type="text" name="days[<?php echo $i - 1; ?>][attractions][<?php echo $index; ?>][name]" class="form-control mb-2" placeholder="景點名稱" value="<?php echo htmlspecialchars($attraction['aname'], ENT_QUOTES); ?>">
                                        <textarea name="days[<?php echo $i - 1; ?>][attractions][<?php echo $index; ?>][description]" class="form-control" rows="2" placeholder="景點描述"></textarea>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="add-btns" id="day<?php echo $i; ?>-btns">
                        <button type="button" class="btn btn-secondary" onclick="addAttraction('day<?php echo $i; ?>', <?php echo $i - 1; ?>)">新增景點</button>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div class="submit-btn">
            <button type="submit" class="btn btn-success">提交</button>
        </div>
        <input type="hidden" id="dayCount" name="dayCount" value="<?php echo $trip['total_date'] ?? 1; ?>">
    </div>
</form>

<script>
    let dayCount = <?php echo $trip ? $trip['total_date'] : 1; ?>;

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

        // Send AJAX request to update total_date in the database
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
            }
        };
        xhr.send("action=update_total_date&tripId=" + encodeURIComponent(<?php echo json_encode($tripId);?>) + "&total_date=" + dayCount);
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

                // Send AJAX request to update total_date in the database
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log(xhr.responseText);
                    }
                };
                xhr.send("action=update_total_date&tripId=" + <?php echo json_encode($tripId); ?> + "&total_date=" + dayCount);
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
            window.location.href = '../attraction_preview/homepage.php'; 
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

<?php
// Handle AJAX request to update total_date
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_total_date') {
    $tripId = $_POST['tripId'];
    $total_date = isset($_POST['total_date']) ? $_POST['total_date'] : null;

    if ($tripId && $total_date) {
        $servername = "localhost";
        $username = "root"; 
        $password = "0305"; // Replace with your actual database password
        $dbname = "touristdb";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Update total_date
        $sql = "UPDATE trips SET total_date = ? WHERE trip_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $total_date, $tripId);
        if ($stmt->execute()) {
            echo "total_date updated successfully";
        } else {
            echo "Error updating total_date: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid input";
    }
    exit;
}
?>
</body>
</html>





