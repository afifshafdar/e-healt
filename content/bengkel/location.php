<?php
$sql_bengkel = select("*", "bengkel");
$keyword = getFrom('keyword');

if(!empty(trim($keyword))) {
    $sql_bengkel = select("*", "bengkel", "nama LIKE '%$keyword%'");
}

$cekRow = cekRow($sql_bengkel);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Peta Lokasi Layanan</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col-md-2">
                    <h4 class="h4">Pencarian : </h4>
                </div>
                <div class="col-md-4">
                    <input type="text" id="keyword-text" autocomplete="off" placeholder="Ketik kata pencarian disini"
                        name="search" required class="form-control">
                </div>
                <div class="col-md-2">
                    <button id="btn-search" class="btn btn-block btn-success" type="button">CARI</button>
                </div>
                <div class="col-md-3">
                    <a href="<?= linkTo('bengkel', ['action' => 'location']) ?>"
                        class="btn btn-block btn-primary">Tampilkan Semua</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="my-map" style="width: 100%;height: 600px;"></div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<script type="text/javascript">
let latUser, lngUser;
let myMap = document.getElementById("my-map");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        myMap.innerHTML = "<h1>Geolocation is not supported by this browser.</h1>";
    }
}

//Untuk mendapatkan posisi client saat ini
function showPosition(position) {
    drawMap("Your position", position.coords.latitude, position.coords.longitude);
    latUser = position.coords.latitude;
    lngUser = position.coords.longitude;
}

//Untuk menampilkan pesan kesalahan ketika
//mendapatkan lokasi user saat ini
function showError(error) {
    switch (error.code) {
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

//Untuk handle pencarian data
let btnCari = document.getElementById("btn-search");
btnCari.addEventListener('click', function(event) {
    event.preventDefault();
    let keyword = document.getElementById('keyword-text');

    if (keyword.value == '' || keyword.value.length == 0) {
        Swal.fire({
            title: 'Error',
            text: 'Silahkan isi keyword pencarian!',
            icon: 'error',
            timer: 2000
        });
        $("#keyword-text").focus();
    } else {
        var currentURL = "<?= linkTo('bengkel', ['action' => 'location']) ?>";
        window.location = currentURL + "&keyword=" + keyword.value;
    }
});

let map;
//Untuk menggambar map ke sistem
function drawMap() {
    <?php if($cekRow <= 0): ?>
    myMap.innerHTML = "<h1>Data layanan tidak ditemukan!</h1>";
    Swal.fire({
        title: 'Error',
        text: 'Bengkel tidak ditemukan, silahkan ulangi lagi!',
        icon: 'error',
        timer: 2000
    });
    <?php else: ?>

    let locations = [
        <?php while($bengkel = result($sql_bengkel)): ?>['<?= $bengkel->nama ?>', <?= $bengkel->lat; ?>,
            <?= $bengkel->lng; ?>, <?= $bengkel->idbengkel; ?>],
        <?php endwhile; ?>
    ];
    map = new google.maps.Map(myMap, {
        zoom: 15,
        center: {
            lat: locations[0][1],
            lng: locations[0][2]
        },
        scaleControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    });

    var latLngLocation, marker;
    for (var i = 0; i < locations.length; i++) {
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
                let URL = '<?= base_url("get-bengkel.php") ?>?id=' + id_bengkel;
                infoWindow.close();
                var boxInfo, lokasi;
                $.ajax({
                    url: URL,
                    method: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        var data_bengkel = res.data;
                        lokasi = data_bengkel.lat + "," + data_bengkel.lng;
                        var myLatlng = new google.maps.LatLng(data_bengkel.lat, data_bengkel
                            .lng);
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
    <?php endif; ?>
}

function getBoxInfo(data) {
    let boxInfo;
    var lokasi = data.lat + "," + data.lng;
    var jam_kerja = getHoursAndMinuteOnly(data.jam_buka) + " - " + getHoursAndMinuteOnly(data.jam_tutup);

    boxInfo =
        "<div id='content'><div id='setNotice'></div><h4 style='font-size:16px;font-weight:bold;max-width:200px;'>";
    boxInfo += data.nama + "</h4>";
    boxInfo += "<div id='bodyContent'><p><img src='<?= base_url("uploads/bengkel/"); ?>" + data.foto +
        "' width='180px;border:1px solid #fefefe;'>";
    boxInfo += "<ul style='padding:0px !important;'>";
    boxInfo += "<li><i class='fas fa-fw fa-phone'></i>&nbsp;<a href='tel:" + data.telpon + "'>" + data.telpon +
        "</a></li>";
    boxInfo += "<li style='color:#333 !important;max-width:200px;'><i class='fas fa-fw fa-home'></i>&nbsp;Alamat: <b>" +
        data.alamat + "</b></li>";
    boxInfo += "<li style='color:#333 !important;'><i class='fas fa-fw fa-clock'></i>Jam Kerja: <b>" + jam_kerja +
        "</b></li>";
    boxInfo += "<li style='color:#333 !important;'><i class='fas fa-fw fa-calendar'></i>Hari Kerja: <b>" + getHariKerja(
        data.hari_kerja) + "</b></li>";
    boxInfo +=
        "<li style='color:#333 !important;'><i class='fas fa-fw fa-random'></i>&nbsp;<a target='_blank' href='<?= linkTo('bengkel', ['action' => 'petunjuk-arah', 'target-location' => '']) ?>" +
        lokasi + "'>";
    boxInfo += "Petunjuk Arah</a></li></ul></p></div></div>";
    return boxInfo;
}
</script>
<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=<?= env('MAP_API_KEY', '') ?>&callback=drawMap" async defer>
</script>