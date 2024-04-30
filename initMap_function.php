<html>
<body>
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