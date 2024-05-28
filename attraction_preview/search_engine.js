const itiBlocksTemplate = document.querySelector("[iti-blocks-template]")
const itiBlocksContainer = document.querySelector("[iti_blocks_container]")
const searchInput = document.querySelector("[data-search]")

let itis = []

searchInput.addEventListener("input", e => {
    const value = e.target.value.toLowerCase()
    itis.forEach(iti => {
      const isVisible = iti.name.toLowerCase().includes(value)
      iti.element.classList.toggle("hide", !isVisible)
    })
  })