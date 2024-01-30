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

