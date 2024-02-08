//Icons

var base = L.icon({
    iconUrl: "../../img/base.png",
    iconSize:     [40, 40], // size of the icon
});

var car = L.icon({
    iconUrl: "../../img/car.png",
    iconSize:     [50, 50], // size of the icon
});

var untaken_offer = L.icon({
    iconUrl: "../../img/untaken_offer.png",
    iconSize:     [35, 35], // size of the icon
});

var taken_offer = L.icon({
    iconUrl: "../../img/taken_offer.png",
    iconSize:     [35, 35], // size of the icon
});

var untaken_request = L.icon({
    iconUrl: "../../img/untaken_request.png",
    iconSize:     [35, 35], // size of the icon
});

var taken_request = L.icon({
    iconUrl: "../../img/taken_request.png",
    iconSize:     [35, 35], // size of the icon
});

// Map with initial conditions

var map = L.map('map', { zoomControl: false, dragging: false, scrollWheelZoom: false, doubleClickZoom: false}).setView([38.246639, 21.74], 15);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
}).addTo(map);

var markerCluster = L.markerClusterGroup();
var rescuerLayer = L.layerGroup();
var lines = L.layerGroup();

var legend = L.control({position: 'topleft'});
legend.onAdd = function (map) {
    var div = L.DomUtil.create('div', 'legend');
    div.innerHTML = '<strong>Legend</strong><br><br>';
    div.innerHTML += '<div class="legend-item"><img src="../../img/base.png" alt="base" style="width: 25px; height: 25px;"> Base<br>';
    div.innerHTML += '<div class="legend-item"><img src="../../img/car.png" alt="car" style="width: 25px; height: 25px;"> Rescuer car<br>';
    div.innerHTML += '<div class="legend-item"><img src="../../img/untaken_offer.png" alt=untaken_offer" style="width: 25px; height: 25px;"> Untaken offer<br>';
    div.innerHTML += '<div class="legend-item"><img src="../../img/taken_offer.png" alt="taken_offer" style="width: 25px; height: 25px;"> Taken offer<br>';
    div.innerHTML += '<div class="legend-item"><img src="../../img/untaken_request.png" alt="untaken_request" style="width: 25px; height: 25px;"> Unaken request<br>';
    div.innerHTML += '<div class="legend-item"><img src="../../img/taken_request.png" alt="taken_request" style="width: 25px; height: 25px;"> Taken request<br>';
    return div;
};
legend.addTo(map);
var tasks;

// Global variables

var userid;
var rescuer;
var base;
var polylines = [];

// Filter layers

var layer_offer_request = L.markerClusterGroup();
var layer_active_cars = L.layerGroup();
var layer_not_active_cars = L.layerGroup();
var layer_lines = L.layerGroup();

// Functions

function defView() {
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    }).addTo(map);
}

function clearMap() {
    map.eachLayer(function(layer) {
        if (layer !== map) {
            map.removeLayer(layer);
        }
    });
}

function distance(coords1, coords2) {

    // Radius of the Earth in meters
    var R = 6371000;

    // Convert degrees to radians
    var dLat = (coords2.lat - coords1.lat) * Math.PI / 180;
    var dLon = (coords2.lng - coords1.lng) * Math.PI / 180;

    // Calculate Haversine distance
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(coords1.lat * Math.PI / 180) * Math.cos(coords2.lat * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var distance = R * c;

    return distance;
}

function updateDatabase (userId, id, date, state, rescuerId,type) {
    
    var updateParams = {
        userId: userId,
        id: id,
        dateAccepted: date,
        state: state,
        rescuerId: rescuerId,
        type: type
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
}

function drawLine(marker1, marker2, id) {
    var polyline = L.polyline([marker1.getLatLng(), marker2.getLatLng()], { color: 'red' });
    lines.addLayer(polyline);
    polylines.push({key: id, line: polyline});
}

function deleteLine(lineId) {
    polylines.forEach(function(array,index) {
        if (array.key == lineId) {
            lines.removeLayer(polylines[index].line);
            polylines.splice(index,1);
        }
    });
}

untaken_icons = [untaken_offer, untaken_request];
taken_icons = [taken_offer, taken_request];
databaseTypes = ["offer", "request"];

function acceptButtonListener (marker,rescuer,id,element,type) {
    marker.on('popupopen', function () {
        var acceptButton = document.querySelector('.acceptButton');
        if (acceptButton) {
            acceptButton.addEventListener('click', function () {
                
                if (rescuer.task < 4) {
                    marker.bindPopup(element[7]);
                    marker.setIcon(taken_icons[type]);
                    drawLine(marker,rescuer,id);
                    rescuer.task += 1;
                    rescuer.bindPopup('Name: '+rescuer.name+'<br>Active Tasks: '+ rescuer.task +
                                      '<br><button class="openStorage">Open storage</button>');
                    
                    updateDatabase(element[1], element[2], new Date().getTime(),"1",userid,databaseTypes[type]);

                    var tasks = document.querySelector('.tasks');
                    var insertion = document.createElement('div');
                    insertion.innerHTML = '<p>'+element[7];
                    insertion.innerHTML += '<button class="decline" data-id="'+id+'">Decline</button>';
                    insertion.innerHTML += '<button class="complete" data-id="'+id+'">Complete</button></p>';
                    tasks.appendChild(insertion);
                    var declineButton = insertion.querySelector('.decline');
                    declineButton.addEventListener('click', function () {
                        tasks.removeChild(insertion);
                        deleteLine(id);
                        marker.bindPopup(element[7]+'<button class="acceptButton">Accept</button>');
                        marker.setIcon(untaken_icons[type]);
                        updateDatabase(element[1], element[2], null,"0", null,databaseTypes[type]);
                        rescuer.task -= 1;
                    });
                    var completeButton = insertion.querySelector('.complete');
                    completeButton.addEventListener('click', function () {
                        if (distance(marker.getLatLng(),rescuer.getLatLng()) <= 50) {
                            tasks.removeChild(insertion);
                            deleteLine(id);
                            marker.bindPopup(element[7]+'<button class="acceptButton">Accept</button>');
                            updateDatabase(element[1], element[2], null,"2", null, databaseTypes[type]);
                            rescuer.task -= 1;
                            rescuer.bindPopup('Name: '+rescuer.name+'<br>Active Tasks: '+ rescuer.task +
                                              '<br><button class="openStorage">Open storage</button>');
                        }
                    });
                }
                else {
                    alert("Ya tienes 4 tareas aceptadas.");
                }
            });
        }
    });
}

function addToTasks(marker,element,id,type) {
    var tasks = document.querySelector('.tasks');
    var insertion = document.createElement('div');
    insertion.innerHTML = '<p>'+element[7];
    insertion.innerHTML += '<button class="decline" data-id="'+id+'">Decline</button>';
    insertion.innerHTML += '<button class="complete" data-id="'+id+'">Complete</button></p>';
    tasks.appendChild(insertion);

    var declineButton = insertion.querySelector('.decline');
    declineButton.addEventListener('click', function () {
        tasks.removeChild(insertion);
        deleteLine(id);
        marker.bindPopup(element[7]+'<button class="acceptButton">Accept</button>');
        marker.setIcon(untaken_offer);
        acceptButtonListener(marker,rescuer,id,element,0);
        updateDatabase(element[1], element[2], null,"0", null, databaseTypes[type]);
    });

    var completeButton = insertion.querySelector('.complete');
    completeButton.addEventListener('click', function () {
        if (distance(marker.getLatLng(),rescuer.getLatLng()) <= 50) {
            tasks.removeChild(insertion);
            deleteLine(id);
            marker.bindPopup(element[7]+'<button class="acceptButton">Accept</button>');
            updateDatabase(element[1], element[2], new Date().getTime(),"2", null, databaseTypes[type]);
        }
    });
}

function changePosition(marker, id, confirmation = false) {
    
    var lat = marker.getLatLng().lat;
    var lng = marker.getLatLng().lng;

    marker.on('dragend', function(event){
        if (confirmation) {
            var userConfirmation = window.confirm("Are you sure you want to proceed?");
            if (userConfirmation) {
                lat = marker.getLatLng().lat;
                lng = marker.getLatLng().lng;
            } else{
                marker.setLatLng([lat,lng]);
            }
        } else {
            lat = marker.getLatLng().lat;
            lng = marker.getLatLng().lng;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_location.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        var data = 'latitude=' + lat + '&longitude=' + lng + '&id=' + id;

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the response from the server if needed
                console.log(xhr.responseText);
            }
        };

        xhr.send(data);
    });
}

function sendLocationBoolean (rescuer, base) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './rescuer/manage_vehicle.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    var data = 'isNear=' + (distance(base.getLatLng(),rescuer.getLatLng()) <= 100)+'&userId=' + rescuer.id;

    xhr.send(data);
}

function ModifyOfferRequest(main_layer, filter_layer, icon, add_delete) {
    main_layer.eachLayer(function(marker) {
        if (marker.getIcon() == icon) {
            if (add_delete) {
                filter_layer.addLayer(marker);
            }
            else {
                filter_layer.removeLayer(marker);
            }
        }
    });
    map.addLayer(filter_layer);
}

// Reading of markers

$(document).ready(function() {
    // AJAX request to fetch data from data.php
    $.ajax({
        url: 'creating_markers.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            data.forEach(function(element) {

                var dragbool = true;
                
                if (element[0] == 0) {
                    userid = element[1];
                    if (element[1] != 1) {
                        tasks = L.control({position: 'topright'});
                        tasks.onAdd = function (map) {
                            var div = L.DomUtil.create('div', 'tasks');
                            div.innerHTML = '<strong>Taken offers and requests</strong>';
                        return div;
                        };
                    tasks.addTo(map);
                    }
                }
                else if (element[0] == 1) {

                    if (userid != element[3])
                        dragbool = false;

                    base = L.marker([element[1],element[2]], {icon: base, draggable: dragbool});
                    map.addLayer(base);

                    changePosition(base,element[3], true);

                } else if (element[0] == 2) {

                    if (userid != element[4])
                        dragbool = false;
                    
                    rescuer = L.marker([element[1],element[2]], {icon: car, draggable: dragbool});
                    rescuer.id = element[4];
                    rescuerLayer.addLayer(rescuer);
                    rescuer.name = element[3];
                    rescuer.task = 0;

                    rescuer.bindPopup('Name: '+rescuer.name+'<br>Active Tasks: '+ rescuer.task +
                                      '<br><button class="openStorage">Open storage</button>');
                                      
                    rescuer.on('drag', function(event){
                        lines.clearLayers();
                        polylines.forEach(function(item,index) {
                            var coords = item.line.getLatLngs();
                            coords[1] = rescuer.getLatLng();
                            item.line.setLatLngs(coords);
                            lines.addLayer(item.line);
                        });
                    });

                    changePosition(rescuer,element[4]);

                    rescuer.on('popupopen', function () {
                        var storageButton = document.querySelector('.openStorage');
                        if (storageButton) {
                            sendLocationBoolean(rescuer, base);
                            storageButton.addEventListener('click', function () {
                                document.getElementById('dialogOverlay').style.display = 'flex';

                                var closeButton = document.createElement('span');
                                closeButton.innerHTML = '&times;'; // '×' character for close icon
                                closeButton.className = 'close';
                                closeButton.onclick = closeWindow;

                                // Append the close button to the modal
                                document.getElementById('dialogOverlay').querySelector('.modal').appendChild(closeButton);

                                function closeWindow() {
                                    var dialogOverlay = document.getElementById('dialogOverlay');
                                    dialogOverlay.style.display = 'none';
                                }
                                        
                            });
                        }
                    });

                } else if (element[0] == 3 || element[0] == 4) {
                    
                    var type = 0;
                    if (element[0] == 4) {
                        type = 1;
                    }

                    var marker = L.marker([element[5],element[6]], {icon: untaken_icons[type]});
                    marker.bindPopup(element[7]);
                    marker.id = element[2];

                    if (element[3] == "0") {
                        markerCluster.addLayer(marker);
                        ModifyOfferRequest(markerCluster,layer_offer_request,untaken_request,true);
                        ModifyOfferRequest(markerCluster,layer_offer_request,untaken_offer,true);
                        var id = element[2];
                        if (userid != 1) {
                            marker.bindPopup(element[7]+'<button class="acceptButton">Accept</button>');
                            acceptButtonListener(marker,rescuer,id,element,type);
                        }
                    }
                    else if (element[3] == "1") {
                        var id = element[2];
                        marker.setIcon(taken_icons[type]);
                        marker.bindPopup(element[7]);
                        rescuerLayer.eachLayer(function(car) {
                            if (car.id == element[4]) {
                                drawLine(marker,car,id);
                                car.task += 1;
                                rescuer.bindPopup('Name: '+rescuer.name+'<br>Active Tasks: '+ rescuer.task +
                                                  '<br><button class="openStorage">Open storage</button>');
                                markerCluster.addLayer(marker);
                                if (userid != 1) {
                                    addToTasks(marker,element,id,type);
                                }
                            }
                        });
                    }
                } else if (element[0] == 5) {
                    ModifyOfferRequest(markerCluster,layer_offer_request,taken_request,true);
                    ModifyOfferRequest(markerCluster,layer_offer_request,taken_offer,true);
                    ModifyOfferRequest(markerCluster,layer_offer_request,untaken_request,true);
                    ModifyOfferRequest(markerCluster,layer_offer_request,untaken_offer,true);
                    
                    rescuerLayer.eachLayer(function(marker) {
                        if (marker.task > 0) {
                            layer_active_cars.addLayer(marker);
                        }
                    });
                    map.addLayer(layer_active_cars);
                    
                    rescuerLayer.eachLayer(function(marker) {
                        if (marker.task == 0) {
                            layer_not_active_cars.addLayer(marker);
                        }
                    });
                    map.addLayer(layer_not_active_cars);

                    layer_lines.addLayer(lines);
                    map.addLayer(layer_lines);
                }
            });
        },
        error: function(xhr, status, error) {
            // Handle errors
            console.error(xhr.responseText);
        }
    });
});

// Filter

document.addEventListener('DOMContentLoaded', function() {

    document.querySelector('.filter-container').addEventListener('change', function(event) {

        const Checkbox = event.target;

        if (Checkbox.checked) {
            switch (Checkbox.value) {
                case 'taken_requests':
                    ModifyOfferRequest(markerCluster,layer_offer_request,taken_request,true);
                    break;
                case 'untaken_requests':
                    ModifyOfferRequest(markerCluster,layer_offer_request,untaken_request,true);
                    break;
                case 'offers':
                    ModifyOfferRequest(markerCluster,layer_offer_request,taken_offer,true);
                    ModifyOfferRequest(markerCluster,layer_offer_request,untaken_offer,true);
                    break;
                case 'cars_active':
                    rescuerLayer.eachLayer(function(marker) {
                        if (marker.task > 0) {
                            layer_active_cars.addLayer(marker);
                        }
                    });
                    map.addLayer(layer_active_cars);
                    break;
                case 'cars_not_active':
                    rescuerLayer.eachLayer(function(marker) {
                        if (marker.task == 0) {
                            layer_not_active_cars.addLayer(marker);
                        }
                    });
                    map.addLayer(layer_not_active_cars);
                    break;
                case 'lines':
                    layer_lines.addLayer(lines);
                    map.addLayer(layer_lines);
                    break;
            }
        } else {
            switch (Checkbox.value) {
                case 'taken_requests':
                    ModifyOfferRequest(markerCluster,layer_offer_request,taken_request,false);
                    break;
                case 'untaken_requests':
                    ModifyOfferRequest(markerCluster,layer_offer_request,untaken_request,false);
                    break;
                case 'offers':
                    ModifyOfferRequest(markerCluster,layer_offer_request,taken_offer,false);
                    ModifyOfferRequest(markerCluster,layer_offer_request,untaken_offer,false);
                    break;
                case 'cars_active':
                    map.removeLayer(layer_active_cars);
                    layer_active_cars = L.layerGroup();
                    break;
                case 'cars_not_active':
                    map.removeLayer(layer_not_active_cars);
                    layer_not_active_cars = L.layerGroup();
                    break;
                case 'lines':
                    map.removeLayer(layer_lines);
                    layer_lines = L.layerGroup();
                    break;
            }
        }
    });
});
