
<!DOCTYPE html>
<html>
<head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 100%;
        }
    </style>
</head>
<body>
<div id="map"></div>
<script>
    //23.0271442,72.5277331
    var marker;
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 17,
            center: {lat: 23.0271442, lng: 72.5277331}
        });

        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+ '</div>'+
                '<h1 id="firstHeading" class="firstHeading">KDS</h1>'+
                '<div id="bodyContent">'+
                    '<p><b>KDS</b>, also referred to as <b>Ayers Rock</b>, is a large ' +
                        'This is AuthCi-Map'+ 'Heritage Site.</p>'+
                    '<p>Attribution: Uluru,'+ '</p>'+
                '</div>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: {lat: 23.0271442, lng: 72.5277331}
        });
        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
        marker.addListener('click', toggleBounce);
    }

    function toggleBounce() {
        if (marker.getAnimation() !== null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGMPy3oXxdVqYc_Ao21a5e1Dvpt4TKSD8&callback=initMap"
        async defer></script>
</body>
</html>