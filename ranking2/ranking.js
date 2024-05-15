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
