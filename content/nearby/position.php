        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Lokasi Anda Saat Ini</h1>
          </div>


          <!-- Content Row -->

          <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Body -->
                <div class="card-body">

              <div id="my-map" style="width: 100%;height: 600px;"></div>
                </div>
              </div>
            </div>


        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <script type="text/javascript">
    var userLat, userLng;
    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
      } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
      }
    }

    function showPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        $.ajax({
            method: 'POST',
            url: '<?= base_url('set-user-location.php') ?>',
            data: {
                'user_latitude': latitude,
                'user_longitude': longitude
            },
            success: function(response) {
                var response = JSON.parse(response);
                if(response.status == 'success') {
                    drawMap("Your position", latitude, longitude);
                    localStorage.setItem('user_latitude', latitude);
                    localStorage.setItem('user_longitude', longitude);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        timer: 2000
                    });
                }
            }
        });
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                x.innerHTML = "User denied the request for Geolocation."
                break;
            case error.POSITION_UNAVAILABLE:
                x.innerHTML = "Location information is unavailable."
                break;
            case error.TIMEOUT:
                x.innerHTML = "The request to get user location timed out."
                break;
            case error.UNKNOWN_ERROR:
                x.innerHTML = "An unknown error occurred."
                break;
        }
    }

    var map;
    function drawMap(title, lat, lng) {
        var locations = [
            <?php
                if(checkUserLocation()):
                    $user_latitude = getUserLocation('user_latitude');
                    $user_longitude = getUserLocation('user_longitude');

                    //Query mencari bengkel yang terdekat dengan posisi user saat ini
                    $sql_bengkel = query(
                        "SELECT bengkel.*, (
                            6371 * ACOS (
                                COS(RADIANS($user_latitude)) *
                                COS(RADIANS(lat)) *
                                COS(RADIANS(lng) - RADIANS($user_longitude)) +
                                SIN(RADIANS($user_latitude)) *
                                SIN(RADIANS(lat))
                            )
                        ) AS jarak
                        FROM bengkel
                        HAVING jarak <= 10
                        LIMIT 5
                        ");

                    if(cekRow($sql_bengkel) > 0):
                        while($bengkel = result($sql_bengkel)): ?>
                            [
                                '<?= $bengkel->nama ?>',
                            <?= $bengkel->lat ?>, <?= $bengkel->lng ?>, <?= $bengkel->idbengkel ?>],
            <?php
                        endwhile;
                    endif;
                endif; ?>
        ];
        var userLocation = new google.maps.LatLng(lat, lng);
        var info = new google.maps.InfoWindow({
            content: title
        });
        map = new google.maps.Map(document.getElementById("my-map"), {
            zoom: 16,
            scaleControl : true,
            mapTypeId : google.maps.MapTypeId.ROADMAP,
            center: userLocation
        });

        var markerUser = new google.maps.Marker({
            position: userLocation,
            map: map,
            icon: '<?= base_url('assets/img/user.png') ?>',
        });

        var marker, latLngLocation;
        for(var i=0; i<locations.length; i++) {
            latLngLocation = new google.maps.LatLng(locations[i][1], locations[i][2]);
            marker = new google.maps.Marker({
                position: latLngLocation,
                map: map,
                icon: '<?= base_url('assets/img/icon-bengkel.png') ?>',
                title: locations[i][0]
            });
            var infoWindow = new google.maps.InfoWindow();
                google.maps.event.addListener(marker, "click", (function(marker, i) {
                    return function() {
                        var id_bengkel = locations[i][3]
                        let URL = '<?= base_url("get-bengkel.php") ?>?id='+id_bengkel;
                        infoWindow.close();
                        var boxInfo, lokasi;
                        $.ajax({
                            url: URL,
                            method: 'GET',
                            dataType: 'json',
                            success: function(res) {
                                var data_bengkel = res.data;
                                lokasi = data_bengkel.lat+","+ data_bengkel.lng;
                                var myLatlng = new google.maps.LatLng(data_bengkel.lat, data_bengkel.lng);
                                boxInfo = getBoxInfo(data_bengkel);
                                infoWindow.setContent(boxInfo);
                                infoWindow.setPosition(myLatlng);
                                infoWindow.open(map, marker);
                            }
                        });
                        id_bengkel = null;
                    }
                })(marker, i));
        }
        
        info.open(map, markerUser);
        marker.setMap(map)
    }

    function getBoxInfo(data) {
        let boxInfo;
        var lokasi = data.lat+","+data.lng;
        var jam_kerja = getHoursAndMinuteOnly(data.jam_buka)+" - "+getHoursAndMinuteOnly(data.jam_tutup);

        boxInfo = "<div id='content'><div id='setNotice'></div><h4 style='font-size:16px;font-weight:bold;max-width:200px;'>";
        boxInfo += data.nama + "</h4>";
        boxInfo += "<div id='bodyContent'><p><img src='<?= base_url("uploads/bengkel/"); ?>"+data.foto+"' width='180px;border:1px solid #fefefe;'>";
        boxInfo += "<ul style='padding:0px !important;'>";
        boxInfo += "<li><i class='fas fa-fw fa-phone'></i>&nbsp;<a href='tel:"+data.telpon+"'>"+data.telpon+"</a></li>";
        boxInfo += "<li style='color:#333 !important;max-width:200px;'><i class='fas fa-fw fa-home'></i>&nbsp;Alamat: <b>"+data.alamat+"</b></li>";
        boxInfo += "<li style='color:#333 !important;'><i class='fas fa-fw fa-clock'></i>Jam Kerja: <b>"+jam_kerja+"</b></li>";
        boxInfo += "<li style='color:#333 !important;'><i class='fas fa-fw fa-calendar'></i>Hari Kerja: <b>"+getHariKerja(data.hari_kerja)+"</b></li>";
        boxInfo += "<li style='color:#333 !important;'><i class='fas fa-fw fa-random'></i>&nbsp;<a target='_blank' href='<?= linkTo('bengkel', ['action' => 'petunjuk-arah', 'target-location' => '']) ?>"+lokasi+"'>";
        boxInfo += "Petunjuk Arah</a></li></ul></p></div></div>";
        return boxInfo;
    }
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= env('MAP_API_KEY', '') ?>" async defer></script>