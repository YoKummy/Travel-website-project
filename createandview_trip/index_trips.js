function showDay(day) {
    const days = document.querySelectorAll('.day-content');
    days.forEach(dayContent => {
        dayContent.classList.remove('active');
    });
    document.getElementById('day' + day).classList.add('active');
}