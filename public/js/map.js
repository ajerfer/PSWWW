// Icons

var base = L.icon({
    iconUrl: "../../img/base.png",
    iconSize:     [60, 60], // size of the icon
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

function onMapClick(e) {
    L.marker(e.latlng,{icon: house, draggable: true}).addTo(map);
}
  
map.on('click', onMapClick);

var userRequests = [
    { lat: 38.25, lng:  21.735073, description: 'Request 1', category: 'category1' },
    { lat: 38.248, lng:  21.735073, description: 'Request 2', category: 'category2' },
    // Add more requests as needed
];


// Function to filter markers based on selected checkboxes
var markers = [];

// Function to add markers to the map
function addUserRequest(lat, lng, description, category) {
    var marker = L.marker([lat, lng]);
    marker.bindPopup(description).openPopup();
    marker.category = category;  // Store category information in marker for filtering
    markers.push(marker); // Add the marker to the markers array
}

// Add user requests to the map
userRequests.forEach(function (request) {
    addUserRequest(request.lat, request.lng, request.description, request.category);
});

// Function to filter markers based on selected checkboxes
function filterMarkers() {
    var category1Checked = document.getElementById('category1').checked;
    var category2Checked = document.getElementById('category2').checked;

    // Loop through all markers and show/hide based on selected checkboxes
    markers.forEach(function (marker) {
        if ((marker.category === 'category1' && category1Checked) ||
            (marker.category === 'category2' && category2Checked)) {
            marker.addTo(map);
        } else {
            map.removeLayer(marker);
        }
    });
}

// Ajax query to save the values:
var data = {
    lat: base.lat,
    lng: base.lng
}

var request = new XMLHttpRequest();
    request.open('POST', './data.txt', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.send(data);

// Add an event listener to checkboxes
document.getElementById('category1').addEventListener('change', filterMarkers);
document.getElementById('category2').addEventListener('change', filterMarkers);