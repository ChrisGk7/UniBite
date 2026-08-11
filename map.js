var map = L.map('mapContainer').setView([0, 0],13); // Initial view set to [latitude, longitude] and zoom level
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);


function addMarker(lat, lng, popupText) {
    var marker = L.marker([lat, lng]).addTo(map);
    marker.bindPopup(popupText);
}

function findCurrentLocation() {
    if ("geolocation" in navigator) {

        navigator.geolocation.watchPosition(
            function (position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
               
                map.setView([lat, lng], 10);
                addMarker(lat, lng, "You are here");
            },
            function (error) {
                console.error("Error getting location: ", error);
            }   
        );
    } else {
        console.error("Geolocation is not supported by this browser."); 
      

    }
}
    
