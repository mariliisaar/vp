function toggle_links() {
    var mobile_nav = document.getElementById("mobile_links");
    var hr = document.getElementById("nav_hr");
    var navbar = document.getElementById("navbar");
    if (window.getComputedStyle(navbar).display === "none") {
        if (mobile_nav.style.display === "block") {
            mobile_nav.style.display = "none";
            hr.classList.add("no_margin");
        } else {
            mobile_nav.style.display = "block";
            hr.classList.remove("no_margin");
        }
    } else {
        hr.style.marginTop = "8px";
    }
}