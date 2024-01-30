// Icons

var base = L.icon({
    iconUrl: "../../img/base.png",
    iconSize:     [40, 40], // size of the icon
});

var car = L.icon({
    iconUrl: "../../img/car.png",
    iconSize:     [30, 30], // size of the icon
});

var house = L.icon({
    iconUrl: "../../img/house.png",
    iconSize:     [30, 30], // size of the icon
});

// Map with initial conditions

var map = L.map('map', { zoomControl: false, dragging: false, scrollWheelZoom: false, doubleClickZoom: false}).setView([38.246639, 21.74], 15);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
}).addTo(map);

var base = L.marker([38.249196, 21.735073], {icon: base}).addTo(map);
var circle = L.circle(base.getLatLng(), {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: 100
}).addTo(map);

// Create a marker cluster group
var markerCluster = L.markerClusterGroup();

// Add the marker cluster group to the map
map.addLayer(markerCluster);

// Function to add a marker to the map
function addMarker(lat, lng, popupContent, category, layer) {
    var marker = L.marker([lat, lng], {}).bindPopup(popupContent)
    marker.category = category; // Attach category information to the marker
    const button = '<br/><button type="button">Click button</button>'
    marker.bindPopup(button);
    layer.addLayer(marker);
}

// Add markers with different categories
addMarker(38.249196, 21.735073, 'Marker 1 (Rescue)', 'rescue', markerCluster);
addMarker(38.249196, 21.735073, 'Marker 2 (Other)', 'other', markerCluster);
addMarker(38.249196, 21.735073, 'Marker 3 (Rescue)', 'rescue', markerCluster);
addMarker(38.249196, 21.735073, 'Marker 4 (Other)', 'other', markerCluster);

function loadMarkers() {
    fetch('get_markers.php')
        .then(response => response.json())
        .then(markers => {
            markers.forEach(marker => {
                addMarker(marker.lat, marker.lng, "name", marker.cat, markerCluster);
            });
        })
        .catch(error => console.error('Error loading markers:', error));
        
}



function onMapClick(e) {
    var marker = L.marker(e.latlng,{icon: house, draggable: true}).addTo(map);
    saveMarkerToDatabase(e.latlng.lat, e.latlng.lng, "this is a marker", "rescue");
}
  
map.on('click', onMapClick);

// Call loadMarkers when the page loads
loadMarkers();
// Function to save a marker to the database
function saveMarkerToDatabase(lat, lng, descrip, cat) {
    fetch('save_markers.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `&lat=${encodeURIComponent(lat)}&lng=${encodeURIComponent(lng)}&descrip=${encodeURIComponent(descrip)}&cat=${encodeURIComponent(cat)}}`,
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
        })
        .catch(error => console.error('Error saving marker:', error));
}