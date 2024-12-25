let orderMap = null;
let orderMarker = null;

function initializeMap() {
    if (orderMap) {
        orderMap.remove();
    }

    orderMap = L.map('map', {
        minZoom: 2,
        maxZoom: 18
    }).setView([0, 0], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(orderMap);

    $('#orderModal').on('shown.bs.modal', function() {
        orderMap.invalidateSize();
    });
}

function addMarker(lat, lng) {
    if (!orderMap) return;

    if (orderMarker) {
        orderMap.removeLayer(orderMarker);
    }

    orderMarker = L.marker([lat, lng]).addTo(orderMap);
    orderMap.setView([lat, lng], 15);
}
