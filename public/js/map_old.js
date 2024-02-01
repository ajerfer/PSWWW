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

// Assume markerData is an array of marker information
var markerData = [
    { id: 1, lat: 40.7128, lng: -74.0060, name: "Marker 1" },
    { id: 2, lat: 34.0522, lng: -118.2437, name: "Marker 2" },
    // Add more markers as needed
];

var legend = L.control({ position: 'topright' });

legend.onAdd = function (map) {
    var div = L.DomUtil.create('div', 'legend');
    div.innerHTML = '<h4>Legend</h4>';
    return div;
};

legend.addTo(map);

// Create markers and add them to the map
markerData.forEach(function (data) {
    var marker = L.marker([data.lat, data.lng]).addTo(map);

    // Add a button to the marker's popup
    marker.bindPopup(data.name + '<br><button class="addToLegendBtn">Add to Legend</button>');

    // Handle button click to add marker to the legend
    marker.on('popupopen', function () {
        var addToLegendBtn = document.querySelector('.addToLegendBtn');
        addToLegendBtn.addEventListener('click', function () {
            addToLegend(data.id, data.name);
        });
    });
});

function addToLegend(markerId, markerName) {
    // Add marker information to the legend
    var legendDiv = document.querySelector('.legend');
    var entry = document.createElement('div');
    entry.innerHTML = '<p>' + markerName + ' <button class="removeFromLegendBtn" data-id="' + markerId + '">Remove</button></p>';
    legendDiv.appendChild(entry);

    // Attach event listener to the remove button in the legend
    var removeFromLegendBtn = entry.querySelector('.removeFromLegendBtn');
    removeFromLegendBtn.addEventListener('click', function () {
        removeFromLegend(markerId);
    });
}

function removeFromLegend(markerId) {
    // Remove marker from the map
    // Assume you have a reference to the marker object or use a marker cluster to manage markers
    // var markerToRemove = ...

    // Remove marker from the legend
    var legendDiv = document.querySelector('.legend');
    var entryToRemove = legendDiv.querySelector('[data-id="' + markerId + '"]');
    if (entryToRemove) {
        legendDiv.removeChild(entryToRemove);
    }
}
