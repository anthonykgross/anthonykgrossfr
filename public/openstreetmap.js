/* Google map api integration with HTML */
$(document).ready(function () {
    var token = "pk.eyJ1IjoiYW50aG9ueWtncm9zcyIsImEiOiJjamVvNjhiZG0wMjltMnFucWF5aGE4N2dhIn0.Yg9wyE9R7lslfhlbkgB3-A";

    var elm = $('#openstreetmap');
    var map = L.map(elm.attr('id')).setView([elm.attr('data-map-lat'), elm.attr('data-map-lon')], 12);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets',
        accessToken: token
    }).addTo(map);

    var marker = L.marker([elm.attr('data-map-lat'), elm.attr('data-map-lon')]).addTo(map);
    marker.bindPopup(elm.attr('data-map-address')).openPopup();
});