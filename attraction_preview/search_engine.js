/* document.addEventListener("DOMContentLoaded", function() { */
  const itiBlockTemplate = document.querySelector("[iti-block-template]");
  const itiBlockContainer = document.querySelector("[iti-block-container]");
  const searchInput = document.querySelector("[data-search]");

  let itis = [];

  searchInput.addEventListener("input", e => {
    const value = e.target.value.toLowerCase();

    var page1 = document.querySelector("#Body_Attraction");
    var page2 = document.querySelector("#Body_Itinerary");
    if (page1.style.display === "block") {
      console.log(placeArray)
      placeArray.forEach(function(place) {
      const isVisible = place.name.toLowerCase().includes(value);
      place.element.classList.toggle("hide", !isVisible);
      });
    } else {
      console.log(itis)
      itis.forEach(iti => {
        const isVisible = iti.name.toLowerCase().includes(value);
        iti.element.classList.toggle("hide", !isVisible);
        console.log(iti.element);
      });
    }
  });

  function loadItiBlocks() {
    $.ajax({
      url: 'getItiBlockData.php',
      type: 'POST',
      dataType: 'json',
      success: function(data) {
        console.log("Data received from server:", data);
        if (Array.isArray(data)) {
          itis = data.map(iti => {
            const block = itiBlockTemplate.content.cloneNode(true).children[0];
            const header = block.querySelector("[data-header]");
            const imgs = block.querySelectorAll("[data-img]");
            const rating = block.querySelector("[data-rating]");
            const period = block.querySelector("[data-period]");
            
            header.textContent = iti.trip_name;
            
            // Assuming iti.img_url is a single URL
            imgs.forEach(img => img.src = iti.img_url);
  
            rating.textContent = "社群評價: " + iti.average_score + "星";
            period.textContent = "天數: " + iti.total_date + "天";
            
            itiBlockContainer.append(block);
  
            return { 
              name: iti.trip_name,
              element: block
            };
          });
        } else {
          console.error("Expected an array but received:", data);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("AJAX error:", textStatus, errorThrown);
        alert('無法獲取行程資料');
      }
    });
  }

  loadItiBlocks();
/* }); */