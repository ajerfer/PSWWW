//Icons

var base = L.icon({
    iconUrl: "../../img/base.png",
    iconSize:     [40, 40], // size of the icon
});

var car = L.icon({
    iconUrl: "../../img/car.png",
    iconSize:     [50, 50], // size of the icon
});

var house = L.icon({
    iconUrl: "../../img/house.png",
    iconSize:     [30, 30], // size of the icon
});

// Map with initial conditions

var map = L.map('map', { zoomControl: false, dragging: false, scrollWheelZoom: false, doubleClickZoom: false}).setView([38.246639, 21.74], 15);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
}).addTo(map);

var markerCluster = L.markerClusterGroup();
map.addLayer(markerCluster);

var rescuerLayer = L.layerGroup();
map.addLayer(rescuerLayer);

var lines = L.layerGroup();
map.addLayer(lines);

var legend = L.control({position: 'topright'});
legend.onAdd = function (map) {
    var div = L.DomUtil.create('div', 'legend');
    div.innerHTML = '<strong>Taken offers and requests</strong>';
    return div;
};
legend.addTo(map);

$(document).ready(function() {
    // AJAX request to fetch data from data.php
    $.ajax({
        url: 'creating_markers.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var userid;
            var rescuer;
            let polylines = [];
            data.forEach(function(element) {
                console.log(element);
                var dragbool = true;
                if (element[0] == 0) {
                    if (userid != element[3])
                        dragbool = false;
                    var marker = L.marker([element[1],element[2]], {icon: base, draggable: dragbool}).addTo(map);
                    marker.on('dragend', function(event){
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update_location.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        var data = 'latitude=' + marker.getLatLng().lat + '&longitude=' + marker.getLatLng().lng + '&id=' + element[3];

                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Handle the response from the server if needed
                                console.log(xhr.responseText);
                            }
                        };

                        xhr.send(data);
                    });
                } else if (element[0] == 1) {
                    if (userid != element[4])
                        dragbool = false;

                    rescuer = L.marker([element[1],element[2]], {icon: car, draggable: dragbool}).addTo(rescuerLayer);
                    rescuer.bindPopup(element[3]);
                    rescuer.on('drag', function(event){
                        lines.clearLayers();
                        polylines.forEach(function(item,index) {
                            var coords = item.line.getLatLngs();
                            coords[1] = rescuer.getLatLng();
                            item.line.setLatLngs(coords);
                            item.line.addTo(lines);
                        });
                    });
                    rescuer.on('dragend', function(event){
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update_location.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        var data = 'latitude=' + rescuer.getLatLng().lat + '&longitude=' + rescuer.getLatLng().lng + '&id=' + element[4];

                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Handle the response from the server if needed
                                console.log(xhr.responseText);
                            }
                        };

                        xhr.send(data);
                    });
                } else if (element[0] == 2) {
                    var marker = L.marker([element[3],element[4]]);
                    marker.bindPopup(element[5]);
                    marker.id = element[2];
                    markerCluster.addLayer(marker);
                    markerCluster.addTo(map);

                    if (userid != 1) {
                        marker.bindPopup(element[5]+'<button class="acceptButton">Accept</button>');

                        marker.on('popupopen', function () {
                            id = marker.id;
                            var acceptButton = document.querySelector('.acceptButton');
                            if (acceptButton) {
                                acceptButton.addEventListener('click', function () {
                                    var polyline = L.polyline([marker.getLatLng(), rescuer.getLatLng()], { color: 'red' });
                                    polyline.addTo(lines);
                                    polylines.push({key: id, line: polyline}); 
                                    marker.bindPopup(element[5]);

                                    var updateParams = {
                                        idUser: element[1],
                                        id: element[2],
                                        dateAccepted: new Date().getTime(),
                                        state: 1,
                                        rescuerId: userid
                                    };

                                    // Make an Ajax request to the PHP script
                                    $.ajax({
                                        type: 'POST',
                                        url: 'update_markers.php',
                                        data: updateParams,
                                        success: function(response) {
                                            console.log(response);
                                        },
                                        error: function(xhr, status, error) {
                                            console.error("Ajax request failed:", status, error);
                                        }
                                    });

                                    var legend = document.querySelector('.legend');
                                    var insertion = document.createElement('div');
                                    insertion.innerHTML = '<p>'+element[5];
                                    insertion.innerHTML += '<button class="decline" data-id="'+id+'">Decline</button></p>';
                                    legend.appendChild(insertion);
                                    var declineButton = insertion.querySelector('.decline');

                                    declineButton.addEventListener('click', function () {
                                        legend.removeChild(insertion);
                                        var index = polylines.findIndex(function(item) {
                                            return item.key === id;
                                        });
                                        lines.removeLayer(polylines[index].line);
                                        polylines.splice(index,1);
                                        marker.bindPopup(element[5]+'<button class="acceptButton">Accept</button>');
                                    });
                                });
                            }
                        });
                        
                    }

                } else {
                    userid = element[1];
                }
            });
        },
        error: function(xhr, status, error) {
            // Handle errors
            console.error(xhr.responseText);
        }
    });
});

markerCluster.eachLayer(function(marker) {
    console.log(marker);
    console.log("hola");
});

