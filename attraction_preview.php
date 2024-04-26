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

<script>
    var map; /*地圖物件 */
    var infowindow; /*可在地圖上跳出景點資訊視窗*/
    var currentPlace = null; /*儲存使用者目前點選的景點 */

    function initMap() {
        // 獲取使用者的地理位置
        if (navigator.geolocation) { /*檢查能否獲取使用者位置 */
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                // 初始化地圖，設置中心點為使用者的地理位置
                map = new google.maps.Map(document.getElementById('map'), {
                    center: userLocation,
                    zoom: 12
                });

                // 初始化 infowindow
                infowindow = new google.maps.InfoWindow();

                // 使用 Google Maps Places API 搜尋附近的旅遊景點
                var service = new google.maps.places.PlacesService(document.createElement('div'));
                service.nearbySearch({
                    location: userLocation,
                    radius: 5000, // 搜尋半徑（單位：公尺）
                    type: ['tourist_attraction'] // 指定搜尋類型為旅遊景點                    
                }, function(results, status) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        showPlaces(results); //result存放了搜尋到的地點資訊
                    }
                });
                
                // 顯示搜尋到的景點
                function showPlaces(places) {
                    var placesContainer = document.getElementById('places'); //取得id為places的元素
                    places.forEach(function(place) { //遍歷所有place
                        var placeInfo = document.createElement('div'); //在這邊建立一個區塊存到placeInfo中
                        placeInfo.classList.add('place-info'); //為placeInfo添加css樣式
                        var placeName = document.createElement('h3');
                        placeName.textContent = place.name;

                        var imageContainer = document.createElement('div');
                        imageContainer.classList.add('image-container');

                        var placePhoto = document.createElement('img');
                        placePhoto.classList.add('place-image');

                        //確認景點是否提供照片
                        if (place.photos && place.photos[0]) {
                            placePhoto.src = place.photos[0].getUrl();
                        } else {
                            var loadingDiv = document.createElement('div');
                            loadingDiv.classList.add('loading');
                            loadingDiv.textContent = '未提供圖片'; // 添加文本元素
                            imageContainer.appendChild(loadingDiv);
                        }

                        imageContainer.appendChild(placePhoto); //把placePhoto(子)添加到imageContainer(父)
                        placeInfo.appendChild(imageContainer);
                        placeInfo.appendChild(placeName);

                        var placeDetails = document.createElement('div');
                        placeDetails.classList.add('place-info-details');

                        var ratingElement = document.createElement('p');
                        ratingElement.classList.add('place-info-rating');
                        ratingElement.textContent = 'Google評價: ' + (place.rating ? place.rating.toFixed(1) : '');
                        placeDetails.appendChild(ratingElement);

                        var vicinityElement = document.createElement('p');
                        vicinityElement.classList.add('place-info-vicinity');
                        vicinityElement.textContent = '位置: ' + (place.vicinity ? place.vicinity.substring(0, 3) : '');
                        placeDetails.appendChild(vicinityElement);

                        placeInfo.appendChild(placeDetails);
                        placesContainer.appendChild(placeInfo);

                        placeInfo.addEventListener('click', function() {
                            openPopup(place);
                        });
                    });
                }

                //跳出視窗
                function openPopup(place) {
                    clearPopup();

                    document.getElementById('place-name').textContent = place.name;

                    var ratingElement = document.getElementById('place-rating');
                    var ratingText = document.createElement('span');
                    ratingText.textContent = 'Google評價: ';
                    ratingElement.appendChild(ratingText);
                    var ratingValue = place.rating ? place.rating.toFixed(1) : '未提供';
                    ratingElement.appendChild(document.createTextNode(ratingValue));

                    var starElement = document.createElement('span');
                    starElement.innerHTML = '&#9733;';
                    ratingElement.appendChild(starElement);

                    var placeImage = document.getElementById('place-image');
                    if (place.photos && place.photos[0]) {
                        placeImage.src = place.photos[0].getUrl();
                    } else {
                        placeImage.style.display = 'none';
                    }

                    var buttonsContainer = document.createElement('div');
                    buttonsContainer.classList.add('buttons-container');

                    var photoButton = document.createElement('button');
                    photoButton.textContent = '查看圖片';
                    photoButton.addEventListener('click', function() {
                        openGoogleMapsPage(place.name, 'photo');
                    });
                    buttonsContainer.appendChild(photoButton);

                    var articleButton = document.createElement('button');
                    articleButton.textContent = '在Google上搜尋';
                    articleButton.addEventListener('click', function() {
                        openGoogleMapsPage(place.name, 'article');
                    });
                    buttonsContainer.appendChild(articleButton);

                    document.getElementById('place-details').appendChild(buttonsContainer);

                    var addressElement = document.createElement('p');
                    addressElement.textContent = "地址：" + (place.vicinity ? place.vicinity : "未提供");
                    document.getElementById('place-details').appendChild(addressElement);

                    currentPlace = place;
                    
                    document.getElementById('popup').style.display = 'block';
                }

                //打開google頁面
                function openGoogleMapsPage(placeName, query) {
                    var searchQuery;
                    var searchUrl;
                    
                    switch(query) {
                        case 'photo':
                            searchQuery = placeName;
                            searchUrl = 'https://www.google.com/search?tbm=isch&q=' + encodeURIComponent(searchQuery);
                            break;
                        case 'article':
                            searchQuery = placeName;
                            searchUrl = 'https://www.google.com/search?q=' + encodeURIComponent(searchQuery);
                            break;
                        default:
                            searchUrl = 'https://www.google.com/';
                            break;
                    }                   
                    if(query !== 'review') {
                        window.open(searchUrl, '_blank');
                    }
                }

                //清除跳出視窗的資料
                function clearPopup() {
                    document.getElementById('place-name').textContent = '';
                    document.getElementById('place-rating').textContent = '';
                    document.getElementById('place-image').src = '';
                    document.getElementById('place-details').innerHTML = '';
                }

                document.getElementById('popup-close').addEventListener('click', function() {
                    document.getElementById('popup').style.display = 'none';
                    clearPopup();
                });
            });
        } else { //無法定位使用者
            alert('瀏覽器不支援地理位置服務！');
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrg019oeLqn9I3RPG5PZm94sRi4pdN_cA&callback=initMap&libraries=places" async defer></script>
</body>
</html>