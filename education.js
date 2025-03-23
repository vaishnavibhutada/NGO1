// Sticky Header
window.onscroll = function() { stickyHeader(); };

var header = document.getElementById("header");
var sticky = header.offsetTop;

function stickyHeader() {
    if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
    } else {
        header.classList.remove("sticky");
    }
}

// Scroll-to-Top Button
var scrollToTopButton = document.getElementById("scrollToTop");

window.onscroll = function() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        scrollToTopButton.style.display = "block";
    } else {
        scrollToTopButton.style.display = "none";
    }
};

scrollToTopButton.onclick = function() {
    window.scrollTo({ top: 0, behavior: "smooth" });
};

// Donation Form Validation
document.getElementById("donate-btn").onclick = function() {
    var amount = document.querySelector("#donate input").value;
    if (amount < 1) {
        alert("Please enter a valid donation amount.");
    } else {
        alert("Thank you for your generous donation of ₹" + amount);
    }
};

// Google Map Initialization
function initMap() {
    var location = { lat: 12.9716, lng: 77.5946 }; // Example: Bangalore location
    var map = new google.maps.Map(document.getElementById("google-map"), {
        zoom: 12,
        center: location
    });
    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
}
