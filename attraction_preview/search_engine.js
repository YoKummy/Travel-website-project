const itiBlockTemplate = document.querySelector("[iti-block-template]")
const itiBlockContainer = document.querySelector("[iti_block_container]")
const searchInput = document.querySelector("[data-search]")

let itis = []

searchInput.addEventListener("input", e => {
    const value = e.target.value.toLowerCase()
    itis.forEach(iti => {
      const isVisible = iti.name.toLowerCase().includes(value)
      iti.element.classList.toggle("hide", !isVisible)
    })
  })

function loadItiBlocks() {
  $.ajax({
    url: 'getItiBlockData.php',
    type: 'POST',
    dataType: 'json',
    success: function(data) {
      itis = data.map(iti => {
        const block = itiBlockTemplate.content.cloneNode(true).children[0]
        const header = block.querySelector("[data-header]")
        const img = block.querySelectorAll("[data-img]")
        const rating = block.querySelector("[data-rating]")
        const period = block.querySelector("[data-period]")
        header.textContent = iti.trip_name
        img.src = iti.img_url /* unfinished need to loop through multiple img tag */
        rating.textContent = "社群評價: " + iti.average_score + "星"
        period.textContent = "天數: " + iti.total_date + "天"
        itiBlockContainer.append(block)
        return { name: iti.trip_name} /* may add rating and period after */
      })
    },
    /* error: function() {
        console.log('無法獲取行程資料');
    } */
  });
}