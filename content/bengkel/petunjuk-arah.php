<?php
$sql_bengkel = select("*", "bengkel");
$cekRow = cekRow($sql_bengkel);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Petunjuk Arah</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow">
        <div class="card-body">
            <div id="my-map" style="width: 100%;height: 600px;"></div>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <div id="my-panel" style="width: 100%;height: auto;"></div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<script type="text/javascript">
    let latUser = localStorage.getItem('user_latitude');
    let lngUser = localStorage.getItem('user_longitude');

    let myMap = document.getElementById("my-map");
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            myMap.innerHTML = "<h1>Geolocation is not supported by this browser.</h1>";
        }
    }

    function showPosition(position) {
        drawMap("Your position", position.coords.latitude, position.coords.longitude);
        latUser = position.coords.latitude;
        lngUser = position.coords.longitude;
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                myMap.innerHTML = "User denied the request for Geolocation."
                break;
            case error.POSITION_UNAVAILABLE:
                myMap.innerHTML = "Location information is unavailable."
                break;
            case error.TIMEOUT:
                myMap.innerHTML = "The request to get user location timed out."
                break;
            case error.UNKNOWN_ERROR:
                myMap.innerHTML = "An unknown error occurred."
                break;
        }
    }

    let map;
    function drawMap() {

        var directionsService = new google.maps.DirectionsService();
        var directionsDisplay = new google.maps.DirectionsRenderer();
        map = new google.maps.Map(myMap, {
            zoom: 18,
            mapTypeId : google.maps.MapTypeId.ROADMAP,
        });
        var panel = document.getElementById('my-panel');
        directionsDisplay.setMap(map);
        directionsDisplay.setPanel(panel);
        var userLocation = latUser+","+lngUser;
        
        var request = {
            origin: userLocation,
            destination: '<?= getFrom('target-location') ?>',
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };

        directionsService.route(request, function(res, status){
            if(status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(res);
            }
        });
    }
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= env('MAP_API_KEY', '') ?>&callback=drawMap&language=id" async defer></script>