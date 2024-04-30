<?php
    include "initMap_function.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Google地圖熱門景點</title>
<link rel="stylesheet" type="text/css" href="styles.css">   
</head>
<body>
<div class="scroll-container">
    <div id="places"></div>
</div>

<div id="map"></div>

<div id="popup" class="popup">
    <div class="popup-content">
        <h2 id="place-name"></h2>
        <div id="place-rating"></div>
        <img id="place-image" class="place-image" src="" alt="Place Image">
        <div id="place-details"></div>
    </div>
    <span id="popup-close" class="popup-close">✕</span>
</div>
</body>
</html>
