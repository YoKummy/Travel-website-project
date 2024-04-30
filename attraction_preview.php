<?php
    include "initMap_function.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Google地圖熱門景點</title>
<style>
    .scroll-container { /*可滾動的容器 */
        overflow-y: scroll; /*內容超出容器時，顯示滾動條 */
        border: 1px solid #ccc; /*邊框樣式 */
        padding: 10px; /*內容與容器邊框的距離 */
        max-height: 1200px; /*最大容器高度 */
    }
    #map {
        height: 500px; /*指定高度 */
    }
    .place-info { /*主頁面景點與照片 */
        margin-bottom: 10px; /*離底部10像素 */
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
        cursor: pointer;/*滑鼠在容器上時變更滑鼠圖示 */
        width: 18%;/*佔據父容器寬度的19% */
        float: left;/*容器向左對齊 */
        margin-right: 2%;
        height: 200px;
        position: relative;
    }
    .image-container{ /*主頁面照片容器 */
        height:70%;
        width:90%;
        position: absolute;
        top: -18%; /* 負值代表把圖片向上移動 */
        left: 50%;
        transform: translateX(-50%); /*水平向左移動容器50%的寬度 */
        padding-bottom: 56.25%; /*內邊距長對寬的比例，16:9 */
    }
    .place-info:nth-child(5n) { /*每五個place-info就換行 */
        margin-right: 0; /*與右側邊緣距離為0 */
    }
    .place-info h3 { /*主頁面景點名稱 */
        margin-bottom: 5px;
        text-align: center;
    }
    .place-info img { /*主頁面照片 */
        overflow:hidden;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        height:45%;
        width:100%;
    }
    .loading { /*未提供照片情況下的背景 */
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 95%;
        background-color: #f5f5f5;
        margin-top: 82px;
    }
    .popup { /*景點視窗 */
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        z-index: 9999; /*顯示在其他元素上 */
        display: none; /*不會直接顯示 */
    }
    .popup-content { /*景點視窗內容 */
        max-height: 80vh;
        overflow-y: auto;
        text-align: center;
    }
    .popup-close { /*視窗關閉鈕 */
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }
    .place-image { /*視窗照片 */
        max-width: 80%;
        max-height: 80%;
    }
    .place-info-details { /*視窗資訊 */
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        text-align: center;
    }
    .place-info-rating,
    .place-info-vicinity { /*視窗景點評價與地點 */
        display: inline-block;
        margin-bottom: 5px;
        font-size: 0.8em;
        margin-right: 10px;
    }
</style>
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
