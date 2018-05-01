const mapvars = {};
mapvars.infoWindow = null;
mapvars.map = null;
mapvars.data = data;

function initialize() {
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

    for (let i = 0; i < mapvars.data.photos.length; i++) {
        let dataPhoto = mapvars.data.photos[i];
        let latLng = new google.maps.LatLng(dataPhoto.latitude, dataPhoto.longitude);
        let marker = new google.maps.Marker({
            position: latLng,
            icon: markerImage
        });
        let fn = mapvars.markerClickFunction(dataPhoto, latLng);
        google.maps.event.addListener(marker, 'click', fn);
        markers.push(marker);
    }
    const markerCluster = new MarkerClusterer(mapvars.map, markers);
}
mapvars.markerClickFunction = function (pic, latlng) {
    return function(e) {
        e.cancelBubble = true;
        e.returnValue = false;
        if (e.stopPropagation) {
            e.stopPropagation();
            e.preventDefault();
        }
        console.log(pic);
        let title = pic.photo_title;
        let url = pic.photo_url;
        let fileurl = pic.photo_file_url;

        let infoHtml = '<div class="info"><h3>' + title +
            '</h3><div class="info-body">' +
            '<a href="' + url + '" target="_blank"><img src="' +
            fileurl + '" class="info-img"/></a></div>' +
            '<a href="http://www.panoramio.com/" target="_blank">' +
            '<img src="http://maps.google.com/intl/en_ALL/mapfiles/' +
            'iw_panoramio.png"/></a><br/>' +
            '<a href="' + pic.owner_url + '" target="_blank">' + pic.owner_name +
            '</a></div></div>';

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
        const latitude  = position.coords.latitude;
        const longitude = position.coords.longitude;

        console.log('Lat: ' + latitude + ', Long:' + longitude);
    }

    function error() {
        alert("Unable to retrieve your location");
    }

    navigator.geolocation.getCurrentPosition(success, error);
}
