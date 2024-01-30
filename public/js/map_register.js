var map = L.map('map', { zoomControl: false, dragging: false, scrollWheelZoom: false, doubleClickZoom: false}).setView([38.246639, 21.74], 15);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
}).addTo(map);

var marker = L.marker([38.246639, 21.74], {draggable: true}).bindPopup("Location").addTo(map)
document.getElementById('lat').value = marker.getLatLng().lat;
document.getElementById('lng').value = marker.getLatLng().lng;

marker.on('drag', function(event){
    var latlng = marker.getLatLng();
    document.getElementById('lat').value = latlng.lat;
    document.getElementById('lng').value = latlng.lng;
});