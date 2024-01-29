var map = L.map('map', { zoomControl: false, dragging: false, scrollWheelZoom: false, doubleClickZoom: false}).setView([38.246639, 21.74], 15);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
}).addTo(map);

var popup = L.popup();

function onMapClick(e) {
    marker = new L.marker(e.latlng, {draggable:'true', color: 'red'});
    marker.on('dragend', function(event){
      var marker = event.target;
      var position = marker.getLatLng();
      marker.setLatLng(new L.LatLng(position.lat, position.lng),{draggable:'true'});
    });
    map.addLayer(marker);
  };
  
  map.on('click', onMapClick);