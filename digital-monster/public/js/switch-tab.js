function switchTab(event, tabId) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace("active bg-primary", "bg-secondary");
    }
    document.getElementById(tabId).style.display = "block";
    event.currentTarget.className = event.currentTarget.className.replace("bg-secondary", "active bg-primary");
}
document.addEventListener('DOMContentLoaded', function () {
    document.getElementsByClassName('tabcontent')[0].style.display = 'block';
});
