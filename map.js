const DEFAULT_CENTER = [38.2466, 21.7346];
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