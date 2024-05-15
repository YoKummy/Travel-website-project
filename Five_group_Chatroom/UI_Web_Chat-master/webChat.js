const webMessage = [
  {
    "no":1,
    "name": "David",
    "photo":"img/friends/David.png",
    "type":"text",
    "message":"Hi,我是你們的老師",
    "dateTime":"06/03 13:00",
    "me": false
  },
  {
    "no":2,
    "name": "Johnny",
    "photo":"img/me/Johnny.png",
    "type":"text",
    "message":"你好",
    "dateTime":"06/03 13:15",
    "me": true
  },
  {
    "no":3,
    "name": "David",
    "photo":"img/friends/David.png",
    "type":"text",
    "message":"同學進度順利嗎...",
    "dateTime":"06/03 13:16",
    "me": false
  },
  {
    "no":4,
    "name": "Johnny",
    "photo":"img/me/Johnny.png",
    "type":"text",
    "message":"還可以但我需要一張早安圖",
    "dateTime":"06/03 13:18",
    "me": true
  },
  {
    "no":5,
    "name": "David",
    "photo":"img/friends/David.png",
    "type":"photo",
    "message":"img/photo/423893.jpg",
    "dateTime":"06/03 13:19",
    "me": false
  },
  {
    "no":6,
    "name": "Johnny",
    "photo":"img/me/Johnny.png",
    "type":"text",
    "message":"謝謝，我將用在這個專案上...",
    "dateTime":"06/03 13:20",
    "me": true
  },
  {
    "no":7,
    "name": "David",
    "photo":"img/friends/David.png",
    "type":"emoji",
    "message":"img/emoji/like.png",
    "dateTime":"06/03 14:16",
    "me": false
  }
]

let message = '';
webMessage.map(data => {
  if(data.me){
    if(data.type == 'text'){
      message += `
        <div class="message_row you-message">
          <div class="message-content">
            <div class="message-text">${data.message}</div>
            <div class="message-time">${data.dateTime}</div>
          </div>
        </div>
      `
    } else {
      message += `
        <div class="message_row you-message">
          <div class="message-content">
            <img class="ejIcon" src="${data.message}" alt="">
            <div class="message-time">${data.dateTime}</div>
          </div>
        </div>
      `
    }
  } else {
    if(data.type == 'text'){
      message += `
        <div class="message_row other-message">
          <div class="message-content">
            <img class="head" src="${data.photo}" alt="">
            <div class="message-text">${data.message}</div>
            <div class="message-time">${data.dateTime}</div>
          </div>
        </div>
      `
    } else {
      message += `
        <div class="message_row other-message">
          <div class="message-content">
            <img class="head" src="${data.photo}" alt="">
            <img class="ejIcon" src="${data.message}" alt="">
            <div class="message-time">${data.dateTime}</div>
          </div>
        </div>
      `
    }
  }
})

document.getElementById('chatRoom').innerHTML = message;