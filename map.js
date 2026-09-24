const DEFAULT_CENTER = [38.2466, 21.7346];// Patras, Greece
const DEFAULT_ZOOM = 13;

function initFeedMap(containerId) {
    const container = document.getElementById(containerId);

    if (!container) {
        return null;
    }

    const map = L.map(containerId).setView(
        DEFAULT_CENTER,
        DEFAULT_ZOOM
    );

    L.tileLayer(
        "https://tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            maxZoom: 19,
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }
    ).addTo(map);

    return map;
}

// Δημιουργία pin και αποθήκευση σε hidden input του form 

function initPickupMap(containerId, latInputId, lngInputId) {

    const container = document.getElementById(containerId);

    if (!container) {
        return null;
    }

    const map = L.map(containerId).setView(
        DEFAULT_CENTER,
        DEFAULT_ZOOM
    );

    L.tileLayer(
        "https://tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            maxZoom: 19,
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }
    ).addTo(map);

    let marker = null;

    const latInput = document.getElementById(latInputId);
    const lngInput = document.getElementById(lngInputId);

    map.on("click", function(event) {

        const lat = event.latlng.lat;
        const lng = event.latlng.lng;

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }

        if (latInput) {
            latInput.value = lat;
        }

        if (lngInput) {
            lngInput.value = lng;
        }

    });

    return map;
}

// Αδεια τοποθεσίας (χρήση μόνο απο Browser) για ταξινόμηση πιάτων 

function centerMapOnCurrentLocation(map, onSuccess) {

    if (!("geolocation" in navigator)) {
        console.error("Geolocation is not supported by this browser.");
        return;
    }

    navigator.geolocation.getCurrentPosition(

        function (position) {

            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            map.setView([lat, lng], DEFAULT_ZOOM);

            if (onSuccess) {
                onSuccess(lat, lng);
            }

        },

        function (error) {
            console.error("Error getting location:", error);
        }

    );
}

// Υπολογισμός της απόστασης του χρήστη από το κάθε dish 

function haversineDistanceKm(lat1, lng1, lat2, lng2) {

    const R = 6371;

    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;

    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) *
        Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLng / 2) *
        Math.sin(dLng / 2);

    const c =
        2 * Math.atan2(
            Math.sqrt(a),
            Math.sqrt(1 - a)
        );

    return R * c;
}

// Επαναφορά του χάρτη στην αρχική του μορφή  

function resetFeedMap(map) {
    map.setView([38.2466, 21.7346], 13);
}