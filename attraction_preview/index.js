//搜尋列
const ul = document.querySelector("ul"),
input = document.querySelector("input"),
tagNumb = document.querySelector(".details span");

let maxTags = 6,
tags = [];

countTags();
createTag();

function countTags(){
    input.focus();
    tagNumb.innerText = maxTags - tags.length;
}

function createTag(){
    ul.querySelectorAll("li").forEach(li => li.remove());
    tags.slice().reverse().forEach(tag =>{
        let liTag = `<li>${tag} <i class="uit uit-multiply" onclick="remove(this, '${tag}')"></i></li>`;
        ul.insertAdjacentHTML("afterbegin", liTag);
    });
    countTags();
}

function remove(element, tag){
    let index  = tags.indexOf(tag);
    tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
    element.parentElement.remove();
    countTags();
}

function addTag(e){
    if(e.key == "Enter"){
        let tag = e.target.value.replace(/\s+/g, ' ');
        if(tag.length > 1 && !tags.includes(tag)){
            if(tags.length < 10){
                tag.split(',').forEach(tag => {
                    tags.push(tag);
                    createTag();
                });
            }
        }
        e.target.value = "";
    }
}

input.addEventListener("keyup", addTag);

const removeBtn = document.querySelector(".details button");
removeBtn.addEventListener("click", () =>{
    tags.length = 0;
    ul.querySelectorAll("li").forEach(li => li.remove());
    countTags();
});
var mini = true;
//左側欄
function toggleSidebar() {
    var sidebar = document.querySelector(".Leftbar");
    
    if (mini) {
        console.log("opening sidebar");
        sidebar.style.width = "15%";
        mini = false;
    } else {
        console.log("closing sidebar");
        sidebar.style.width = "6%";
        mini = true;
    }
}
//右側欄 取得目前行程
function loadTrips() {
$.ajax({
url: 'getTrip.php',
type: 'GET',
dataType: 'json',
success: function(data) {
    const tripArray = data.tripArray;
    const dateArray = data.dateArray;
    const sdateArray = data.sdateArray;
    const urlArray = data.urlArray;

    for (let i = 0; i < tripArray.length; i++) {
        const rightDiv = $('<div>').addClass('rightDiv').attr('id', `rightDiv-${i}`);
        const img = $('<img>').addClass('trip-img').attr('src', urlArray[i]);
        rightDiv.append(img);
        rightDiv.append(`<h3 style="padding-left:15px;position:relative;top:200px;">${tripArray[i]}</h3>`);
        rightDiv.append(`<p class="trip-date">出發日期：${sdateArray[i]}</p>`);
        const btnGroup = $('<div>').addClass('btn-group');
        const editBtn = $('<button>').addClass('edit-btn').text('查看');
        btnGroup.append(editBtn);
        const deleteBtn = $('<button>').addClass('delete-btn').text('刪除');
        btnGroup.append(deleteBtn);
        rightDiv.append(btnGroup);
        $('.offcanvas-body').append(rightDiv);
        //刪除整個行程
        deleteBtn.on('click', function() {
            const trip = tripArray[i];
            if (confirm('你確定要刪除行程「' + trip + '」嗎？')) {
                $.ajax({
                    url: 'deleteTrip.php',
                    type: 'POST',
                    data: { trip: trip },
                    complete: function() {
                        alert('成功刪除行程：' + trip);
                        $('#rightDiv-' + i).remove();
                    }
                });
            } else {
                console.log('取消刪除行程：' + trip);
            }
        });
        //查看整個行程
        editBtn.on('click', function() {
            const trip = tripArray[i];
            $.ajax({
                url: 'viewSpot.php',
                type: 'POST',
                data: { trip: trip },
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    const data = response.data;
                    const sDate = response.sdate;
                    const maxDay = Math.max(...data.map(item => item.trip_day));
                    const aPopup = document.getElementById('aPopup');
                    aPopup.style.display = 'block';
                    const popupContent = document.getElementById('popup-content');
                    const tripName = document.createElement('h2');
                    tripName.innerText = trip;
                    const dayButtons = document.createElement('div');
                    for (let i = 1; i <= maxDay; i++) {
                        const button = document.createElement('button');
                        button.innerText = `第${i}天`;
                        button.addEventListener('click', function(event) {
                            $('#aContent').remove();
                            const day = parseInt(event.target.innerText.slice(1, 2));
                            console.log(day);
                            const tripDayData = data.filter(item => item.trip_day == day);
                            console.log(tripDayData);
                            tripDayData.sort((a, b) => a.order_number - b.order_number);
                            const aContent = document.createElement('div');
                            aContent.id = 'aContent';
                            for (let j = 0; j < tripDayData.length; j++) {
                                const anameDiv = document.createElement('div');
                                anameDiv.className = 'aname-div';
                                anameDiv.innerText = tripDayData[j].aname;
                                const button = document.createElement('button');
                                button.innerText = '刪除';
                                button.addEventListener('click', function(event) {
                                    const aname = anameDiv.textContent.trim().replace('刪除', ''); // 移除"刪除"按鈕的文字
                                    const tripDay = i;
                                    const tname = trip;
                                    $.ajax({
                                        url: 'deleteSpot.php',
                                        type: 'POST',
                                        data: { aname: aname, tripDay: tripDay, tname: tname },
                                        dataType: 'json',
                                        success: function(response) {
                                            console.log(aname, tripDay, tname)
                                            console.log(response);
                                            event.target.parentNode.remove();
                                            document.getElementById('aPopup').style.display = 'none';
                                            $('#popup-content').empty();
                                            alert('景點已成功刪除');
                                        },
                                        error: function() {
                                            console.log("無法刪除景點");
                                        }
                                    });
                                });
                                anameDiv.appendChild(button);
                                aContent.appendChild(anameDiv);
                            }
                            popupContent.appendChild(aContent);
                        });
                        dayButtons.appendChild(button);
                    }
                    popupContent.appendChild(tripName);
                    popupContent.appendChild(dayButtons);
                },
                error: function() {
                    console.log("無法獲取行程資訊")
                }
            });
        });
    }
},
error: function() {
    console.log('無法獲取行程資料');
}
});
}

$(document).ready(function() {
$('.navbar-toggler').on('click', function() {
loadTrips();
});
$('.btn-close').on('click', function() {
$('.rightDiv').each(function() {
    $(this).remove();
});
});
});

//切換按鈕
document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('toggle-btn');
    toggleBtn.addEventListener('click', function() {
        if (toggleBtn.textContent === '切換至行程') {
            toggleBtn.textContent = '切換至景點';
        } else {
            toggleBtn.textContent = '切換至行程';
        }
    });
});
//景點一覽
var infowindow; /*可在地圖上跳出景點資訊視窗*/
var currentPlace = null; /*儲存使用者目前點選的景點 */
var currentPlaceName = ''; //儲存目前選擇的地點
var selectedDate; //儲存目前選擇的日期
var tName = ''; //儲存目前選擇的行程

function initMap() {
    // 獲取使用者的地理位置
    if (navigator.geolocation) { /*檢查能否獲取使用者位置 */
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

          
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
                    placeName.textContent = place.name.replace(/\([^)]*\)|（[^）]*）|(?<=.{6})\s.*/g, ''); //用正規表達式篩選名稱，避免名字過長

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

                    placeInfo.appendChild(placeName);
                    imageContainer.appendChild(placePhoto); //把placePhoto(子)添加到imageContainer(父)
                    placeInfo.appendChild(imageContainer);

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
            currentPlaceName = place.name.replace(/\([^)]*\)|（[^）]*）|(?<=.{6})\s.*/g, '');

            var ratingElement = document.getElementById('place-rating');
            ratingElement.textContent = '';
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
                placeImage.style.display = 'block';
            } else {
                placeImage.style.display = 'none';
            }

            var photoButton = document.getElementById('photo-button');
            photoButton.addEventListener('click', function() {
                openGoogleMapsPage(place.name, 'photo');
            });

            var articleButton = document.getElementById('article-button');
            articleButton.addEventListener('click', function() {
                openGoogleMapsPage(place.name, 'article');
            });

            var addressElement = document.createElement('p');
            addressElement.textContent = "地址：" + (place.vicinity ? place.vicinity : "未提供");
            document.getElementById('place-details').appendChild(addressElement);

            currentPlace = place;

            document.getElementById('popup').style.display = 'block';
            }

            //創建選擇行程的視窗
            $(document).ready(function() { // 檔案準備完成時才執行
            function addToTouristHandler() {
                $.ajax({
                url: 'getTrip.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    const tripArray = data.tripArray;
                    const dateArray = data.dateArray;
                    const sdateArray = data.sdateArray;
                    const tripContainer = $('#tpopup-content');
                    tripArray.forEach((tripName, index) => {
                    const tripDiv = $('<div></div>').addClass('trip-div'); // 創建一個 div 元素
                    const tripSelect = $('<select></select>').addClass('trip-selector');
                    let optionSelected = false;
                    tripSelect.change(function() {
                        optionSelected = true;
                        const selectedDateVal = $(this).val();
                        if (selectedDateVal) {
                            selectedDate = selectedDateVal;
                        }
                        $('.trip-div').not($(this).closest('.trip-div')).find('select').prop('disabled', true);
                        tName = $(this).closest('.trip-div').find('.p1').text();
                    });
                    // 在迴圈外部初始化預設選項
                    const blankOption = $('<option></option>').addClass('center-text');
                    blankOption.val('').text('請選擇一天').prop('disabled', true).prop('selected', true);
                    tripSelect.append(blankOption);
                    for (let i = 1; i <= dateArray[index]; i++) {
                        const option = $('<option></option>').addClass('center-text');
                        option.val(i);
                        option.text('第' + i + '天');
                        tripSelect.append(option);
                    }
                    tripSelect.val(''); // 選擇預設選項
                    const dateContainer = $('<div></div>').addClass('date-container'); // 創建一個父容器
                    const p1 = $('<p></p>').addClass('p1');
                    p1.text(tripName);
                    const p2 = $('<p></p>').addClass('p2');
                    p2.text(sdateArray[index]);
                    dateContainer.append(p1);
                    dateContainer.append(p2);
                    tripDiv.append(dateContainer);
                    tripDiv.append(tripSelect);
                    tripContainer.append(tripDiv);
                    });
                },
                error: function(error) {
                    console.error('Error:', error);
                }
                });
                document.getElementById('tourist_popup').style.display = 'block';
                document.getElementById('popup').style.display = 'none';
            }
            $('#add_to_tourist').click(addToTouristHandler);
            });

            //創建景點排序視窗
            document.getElementById('submit-button').addEventListener('click', function() {
                if (!selectedDate) {
                    alert('請選擇一個行程的天數來加入');
                    return;
                }
                document.getElementById('tourist_popup').style.display = 'none';
                clearTPopup();

                $.ajax({
                    url: 'submit-dselection.php',
                    type: 'POST',
                    data: {
                    tripName: tName,
                    sDate: selectedDate,
                    placeName: currentPlaceName
                    },
                    success: function(data) {
                        console.log(data);
                        const orderData = data.orderData;
                        const timeContainer = document.getElementById('time-content');
                        var i = 1;
                        for (const [key, value] of Object.entries(orderData)) {
                            const br = document.createElement("br");
                            const radio = document.createElement("input");
                            radio.classList.add("radio-button");
                            const timeDiv = document.createElement("div");
                            timeDiv.className = "time-div";
                            radio.type = "radio";
                            radio.name = "order";
                            radio.value = value; // 景點要插入的順序

                            const label = document.createElement("label");
                            if(i ==1){
                                label.innerText = currentPlaceName + "\n" + Object.keys(orderData)[1];
                            }else if(i == Object.entries(orderData).length){
                                label.innerText = key + "\n" + currentPlaceName;
                            }else{
                                label.innerText = key + "\n" + currentPlaceName + "\n" + Object.keys(orderData)[i];
                            }
                            timeDiv.appendChild(radio);
                            timeDiv.appendChild(label);
                            timeDiv.appendChild(br);
                            timeContainer.appendChild(timeDiv);
                            i++;
                        }
                        if (data.closeTimePopup) {
                            document.getElementById('time_popup').style.display = 'none';
                            clearTimePopup();
                        } else {
                            document.getElementById('time_popup').style.display = 'block';
                        }
                        document.getElementById('Tsubmit-button').addEventListener('click', function() {
                            var selectedRadio = null;
                            var radios = document.getElementsByName('order');
                            
                            for (var i = 0; i < radios.length; i++) {
                                if (radios[i].checked) {
                                    selectedRadio = radios[i];
                                    break;
                                }
                            }
                            if (selectedRadio) {
                                const selectedIndex = Array.prototype.indexOf.call(document.getElementsByName('order'), selectedRadio);
                                const orderData = data.orderData;
                                const n = selectedIndex;
                                $.ajax({
                                    url: 'submit-tselection.php',
                                    type: 'POST',
                                    data: {
                                        orderNum: n,
                                        tripName: tName,
                                        sDate: selectedDate,
                                        placeName: currentPlaceName
                                    },
                                    success: function(data) {
                                        document.getElementById('time_popup').style.display = 'none';
                                        clearTimePopup();
                                    },
                                    error: function(error) {
                                        console.error('Error:', error);
                                    }
                                });
                            }
                            document.getElementById('time_popup').style.display = 'none';
                            clearTimePopup();
                        });
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });

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
            
            function clearTPopup() {
                $('#tpopup-content .trip-div').remove();
            }

            function clearTimePopup() {
                $('#time-content .time-div').remove();
            }

            document.getElementById('spot-close').addEventListener('click', function() {
                document.getElementById('aPopup').style.display = 'none';
                $('#popup-content').empty();
            });
        });
    } else { //無法定位使用者
        alert('瀏覽器不支援地理位置服務！');
    }
}

//Attraction-Itinerary switch button
function togglePages() {
    var page1 = document.querySelector("#Body_Attraction");
    var page2 = document.querySelector("#Body_Itinerary");
    if (page1.style.display === "block") {
        page1.style.display = "none";
        page2.style.display = "block";
    } else {
        page1.style.display = "block";
        page2.style.display = "none";
    }
}

//ititnerary blocks popup
document.addEventListener('DOMContentLoaded', function() {
    var iti_blocks = document.querySelectorAll(".blocks");
    var iti_popup = document.querySelector(".iti_popup");
    var iti_closeBtn = document.querySelector(".iti_popup-close");
    
    iti_blocks.forEach( function(iti_block) {
        iti_block.addEventListener('click', function() {
            iti_popup.style.display = "block";
        });
    });
    

    iti_closeBtn.addEventListener('click', function() {
        iti_popup.style.display = "none";
    });

});

/* 
window.onclick = function(event) {
    if (event.target == iti_popup) {
        iti_popup.style.display = "none";
    }
}
 */