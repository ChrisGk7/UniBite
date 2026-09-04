// // Reusable Leaflet helpers shared across pages (cook.php's two maps,
// // add_dish.php, and later feed.php). Nothing runs automatically on load -
// // each page calls initPickupMap()/initFeedMap() itself for whichever
// // container(s) it actually has.

// const DEFAULT_CENTER = [38.2466, 21.7346]; // Patras, GR 
// const DEFAULT_ZOOM = 13;

// /**
//  * Sets up a click-to-pin map inside `containerId`. Clicking anywhere on the
//  * map drops (or moves, if one already exists) a single marker and writes
//  * the clicked coordinates into the hidden inputs `latInputId`/`lngInputId`.
//  *
//  * Returns the Leaflet map instance, or null if the container doesn't exist
//  * on this page (so it's always safe to call for a map that might not be
//  * present, e.g. an edit modal that hasn't been used yet).
//  */
// function initPickupMap(containerId, latInputId, lngInputId) {
//     const container = document.getElementById(containerId);
//     if (!container) return null;

//     const map = L.map(containerId).setView(DEFAULT_CENTER, DEFAULT_ZOOM);

//     L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
//         maxZoom: 19,
//         attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
//     }).addTo(map);

//     let marker = null;
//     const latInput = document.getElementById(latInputId);
//     const lngInput = document.getElementById(lngInputId);

//     // If the inputs already have a value (e.g. editing an existing dish),
//     // show the existing pin on load instead of starting blank.
//     if (latInput && lngInput && latInput.value && lngInput.value) {
//         const existingLat = parseFloat(latInput.value);
//         const existingLng = parseFloat(lngInput.value);
//         marker = L.marker([existingLat, existingLng]).addTo(map);
//         map.setView([existingLat, existingLng], DEFAULT_ZOOM);
//     }

//     map.on('click', function (event) {
//         const { lat, lng } = event.latlng;

//         if (marker) {
//             marker.setLatLng([lat, lng]); // move the existing pin, don't stack a new one
//         } else {
//             marker = L.marker([lat, lng]).addTo(map);
//         }

//         if (latInput) latInput.value = lat;
//         if (lngInput) lngInput.value = lng;

//         // Clear any "please select a point" validation error, if present
//         const errorEl = document.getElementById(containerId + 'Error');
//         if (errorEl) errorEl.textContent = '';
//     });

//     return map;
// }

// /**
//  * Centers `map` on the browser's current location, once (not continuous
//  * tracking - use watchPosition instead if you need live movement).
//  * Calls onSuccess(lat, lng) if provided, once location is found.
//  */
// function centerMapOnCurrentLocation(map, onSuccess) {
//     if (!("geolocation" in navigator)) {
//         console.error("Geolocation is not supported by this browser.");
//         return;
//     }

//     navigator.geolocation.getCurrentPosition(
//         function (position) {
//             const lat = position.coords.latitude;
//             const lng = position.coords.longitude;
//             map.setView([lat, lng], DEFAULT_ZOOM);
//             if (onSuccess) onSuccess(lat, lng);
//         },
//         function (error) {
//             console.error("Error getting location: ", error);
//         }
//     );
// }

// /**
//  * Haversine formula: great-circle distance in kilometers between two
//  * lat/lng points. Used for Γ1's "sort/filter dishes by distance".
//  */
// function haversineDistanceKm(lat1, lng1, lat2, lng2) {
//     const R = 6371; // Earth's radius in km
//     const dLat = (lat2 - lat1) * Math.PI / 180;
//     const dLng = (lng2 - lng1) * Math.PI / 180;

//     const a =
//         Math.sin(dLat / 2) * Math.sin(dLat / 2) +
//         Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
//         Math.sin(dLng / 2) * Math.sin(dLng / 2);

//     const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
//     return R * c;
// }




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