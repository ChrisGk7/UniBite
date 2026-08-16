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