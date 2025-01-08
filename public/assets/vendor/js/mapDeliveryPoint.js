let deliveryMap = null;
let regionMarkers = [];
let deliveryMarker = null;
let regionPolygon = null;
let deliveryMapLayer = null;

function initializeDeliveryMap() {
  // Clear existing map if any
  if (deliveryMap) {
    deliveryMap.remove();
  }

  // Initialize the map
  deliveryMap = L.map('deliveryMap', {
    minZoom: 2,
    maxZoom: 18
  }).setView([0, 0], 13);

  // Add tile layer
  deliveryMapLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(deliveryMap);

  // Add click event for delivery point selection
  deliveryMap.on('click', function (e) {
    setDeliveryPoint(e.latlng.lat, e.latlng.lng);
  });

  // Handle modal display issues
  $('#deliveryPointModal').on('shown.bs.modal', function () {
    deliveryMap.invalidateSize();
  });
}

function setDeliveryPoint(lat, lng) {
  if (deliveryMarker) {
    deliveryMap.removeLayer(deliveryMarker);
  }

  // Create red marker for delivery point
  deliveryMarker = L.marker([lat, lng], {
    icon: L.icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    })
  }).addTo(deliveryMap);

  document.getElementById('delivery_latitude').value = lat;
  document.getElementById('delivery_longitude').value = lng;
}

function displayRegion(boundaryPoints) {
  if (!deliveryMap) return;

  clearRegionDisplay();

  // Use default blue markers for boundaries
  boundaryPoints.forEach(coord => {
    let marker = L.marker([coord[0], coord[1]]).addTo(deliveryMap);
    regionMarkers.push(marker);
  });

  // Rest of your function remains the same
  regionPolygon = L.polygon(boundaryPoints, {
    color: '#3388ff',
    weight: 2,
    opacity: 0.8,
    fillOpacity: 0.2
  }).addTo(deliveryMap);

  const bounds = L.latLngBounds(boundaryPoints.map(
    coord => L.latLng(coord[0], coord[1])
  ));
  deliveryMap.fitBounds(bounds, {
    padding: [50, 50],
    maxZoom: 15
  });
}

function clearRegionDisplay() {
  // Clear markers
  regionMarkers.forEach(marker => deliveryMap.removeLayer(marker));
  regionMarkers = [];

  // Clear polygon
  if (regionPolygon) {
    deliveryMap.removeLayer(regionPolygon);
    regionPolygon = null;
  }
}

function getDeliveryPoint() {
  if (!deliveryMarker) return null;

  const position = deliveryMarker.getLatLng();
  return {
    lat: position.lat,
    lng: position.lng
  };
}

function destroyDeliveryMap() {
  clearRegionDisplay();

  if (deliveryMarker) {
    deliveryMap.removeLayer(deliveryMarker);
    deliveryMarker = null;
  }

  if (deliveryMapLayer) {
    deliveryMap.removeLayer(deliveryMapLayer);
  }

  if (deliveryMap) {
    deliveryMap.remove();
    deliveryMap = null;
  }
}
