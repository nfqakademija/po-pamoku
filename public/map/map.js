const mapvars = {};
mapvars.infoWindow = null;
mapvars.map = null;
mapvars.data = null;

function initialize() {
    const url = $('#map-container').attr('data-url');

    fetch(url)
    .then(res => res.json())
    .then((out) => {
        mapvars.data = out;
        //mapvars.data = data;
        const center = new google.maps.LatLng(55, 24);

        mapvars.map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

        mapvars.infoWindow = new google.maps.InfoWindow();
        const markers = [];
        const imageUrl = 'http://chart.apis.google.com/chart?cht=mm&chs=24x32&chco=' +
            '2da2c5,557bc1,7E55BD&ext=.png';
        const markerImage = new google.maps.MarkerImage(imageUrl,
            new google.maps.Size(24, 32));

        for (let i = 0; i < mapvars.data.Activities.length; i++) {
            let data = mapvars.data.Activities[i];
            let latLng = new google.maps.LatLng(data.lat, data.lng);
            let marker = new google.maps.Marker({
                position: latLng,
                icon: markerImage
            });
            let fn = mapvars.markerClickFunction(data, latLng);
            google.maps.event.addListener(marker, 'click', fn);
            markers.push(marker);
        }
        const markerCluster = new MarkerClusterer(mapvars.map, markers);
    })
        .catch(err => { throw err });
}
mapvars.markerClickFunction = function (data, latlng) {
    return function(e) {
        e.cancelBubble = true;
        e.returnValue = false;
        if (e.stopPropagation) {
            e.stopPropagation();
            e.preventDefault();
        }
        console.log(data);
        let title = data.name;
        let fileurl =  data.pathToLogo;

        let infoHtml = '<div class="info"><h3>' + title +
            '</h3><div class="info-body">' +
            '<img src="' + fileurl + '" class="info-img"/></div>' +
            '<p></p>'+
            '</div></div>';

        mapvars.infoWindow.setContent(infoHtml);
        mapvars.infoWindow.setPosition(latlng);
        mapvars.infoWindow.open(mapvars.map);
    };
};
google.maps.event.addDomListener(window, 'load', initialize);

function geoFindMe() {

    if (!navigator.geolocation){
        console.log('Geolocation is not supported by your browser');
        return;
    }

    function success(position) {
        const lat  = position.coords.lat;
        const lng = position.coords.lng;

        console.log('Lat: ' + lat + ', Long:' + lng);
    }

    function error() {
        alert("Unable to retrieve your location");
    }

    navigator.geolocation.getCurrentPosition(success, error);
}
