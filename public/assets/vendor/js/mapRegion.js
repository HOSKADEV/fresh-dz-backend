let map = null;
let markers = [];
let coordinates = [];
let isEditing = false;
let mapTileLayer = null;

function destroyMap() {
  // Clear all markers
  if (markers.length > 0) {
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
  }

  // Clear coordinates
  coordinates = [];



  // Remove map
  if (map) {
    // Remove tile layer
    if (mapTileLayer) {
      map.removeLayer(mapTileLayer);
    }
    map.remove();
    map = null;
  }
}

function initializeMap() {
  // If there's an existing map, destroy it first
  destroyMap();

  // Create new map
  map = L.map('map').setView([0, 0], 2);
  mapTileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  // Add click event to map
  map.on('click', function (e) {
    let marker = L.marker(e.latlng).addTo(map);
    markers.push(marker);
    coordinates.push([e.latlng.lat, e.latlng.lng]);
  });

  // Fix map display issues that can occur in modal
  setTimeout(() => {
    map.invalidateSize();
  }, 100);
}

function clearPoints() {
  if (map) {
    markers.forEach(marker => map.removeLayer(marker));
  }
  markers = [];
  coordinates = [];
}

function addMarkersToMap(boundaryPoints) {
  if (!map) return;

  clearPoints();

  boundaryPoints.forEach(coord => {
    try {
      let marker = L.marker([coord[0], coord[1]]).addTo(map);
      markers.push(marker);
      coordinates.push(coord);
    } catch (e) {
      console.error('Error adding marker:', e);
    }
  });

  // Center map on the region
  if (boundaryPoints.length > 0) {
    const bounds = L.latLngBounds(boundaryPoints.map(
      coord => L.latLng(coord[0], coord[1])));
    map.fitBounds(bounds, {
      padding: [50,
        50], // Add padding around bounds
      maxZoom: 15 // Limit max zoom level
    });
    map.invalidateSize(); // Force map to update size
  }
}
