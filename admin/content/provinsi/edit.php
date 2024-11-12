<?php
$id_provinsi = getFrom('id');
$sql_provinsi = select("*", "provinsi", "idprovinsi = '$id_provinsi'");
if(cekRow($sql_provinsi) <= 0) {
    redirect(404);
}

$provinsi = result($sql_provinsi);

if (isset($_POST['update'])) {
    $nama_provinsi = getPost('nama_provinsi');
    $latitude = getPost('latitude');
    $longitude = getPost('longitude');

    if(!empty(trim($nama_provinsi)) && !empty(trim($latitude)) && !empty(trim($longitude))) {

        $validate = select("*", "provinsi", "lat = '$latitude' AND lng = '$longitude'");
        if(cekRow($validate) > 0) {
            setMessage('error', "Letak provinsi sudah terpakai!", "back");
        } else {
            $provinsi_data = [
                'nama' => strtoupper($nama_provinsi),
                'lat' => $latitude,
                'lng' => $longitude
            ];

            $update = updateArray("provinsi", $provinsi_data, ['idprovinsi' => $id_provinsi]);

            if($update) {
                setMessage('success', "Data provinsi berhasil diperbarui!", linkTo("provinsi", [], false));
            } else {
                setMessage('error', "Gagal memperbarui data provinsi!", "back");
            }
        }
    } else {
        setMessage('error', "Silahkan isi nama provinsi dan tentukan titik tengah wilayah provinsi pada peta!", "back");
    }
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Provinsi</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo linkTo("provinsi", ['action' => 'edit', 'id' => $id_provinsi], false) ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="offset-1 col-md-5">
                        <label for="nama" class="form-control-label">Nama Provinsi</label>
                        <input type="text" value="<?= $provinsi->nama ?>" name="nama_provinsi" class="form-control" required autocomplete="off">
                        <br>

                        <label for="latitude" class="form-control-label">Latitude</label>
                        <input type="text" value="<?= $provinsi->lat ?>" name="latitude" id="latitude" class="form-control" required autocomplete="off" readonly>
                        <br>                                
                    </div>
                    <div class="col-md-5">
                        <label for="longitude" class="form-control-label">Longitude</label>
                        <input type="text" value="<?= $provinsi->lng ?>" name="longitude" id="longitude" class="form-control" required autocomplete="off" readonly>
                        <br><br>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" type="submit" name="update">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo linkTo("provinsi", [], false) ?>" class="btn btn-secondary btn-block">
                                    <span>Kembali</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 offset-1">
                        <div class="alert alert-info">
                            <strong>Silahkan tentukan letak provinsi dengan klik wilayah pada peta di bawah!</strong>
                        </div>                        
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Peta</h6>
        </div>
        <div class="card-body mb-4">
            <div id="my-map" style="width: 100%;height: 600px;"></div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<script type="text/javascript" async defer>
    var map;
    var markers = [];
    
    function initMap() {
        var myLatlng = new google.maps.LatLng(<?= $provinsi->lat ?>, <?= $provinsi->lng ?>);
        var info = new google.maps.InfoWindow({
            content: "<?= $provinsi->nama ?>"
        });
        map = new google.maps.Map(document.getElementById("my-map"), {
            zoom: 5,
            center: myLatlng
        });

        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map
        });
        
        info.open(map, marker);
        marker.setMap(map)

        map.addListener('click', function(event) {
            //Delete the current marker
            marker.setMap(null);

            //Add marker to map
            addMarker(event.latLng);
            var latitude = event.latLng.lat();
            var longitude = event.latLng.lng();

            //Save lat lng to input text
            document.getElementById('latitude').value = toLatLong(latitude);
            document.getElementById('longitude').value = toLatLong(longitude);
        });

    }

    function addMarker(location) {
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
        //Delete the old marker
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers.push(marker);
    }

    function toLatLong(latLng) {
        var result = latLng.toString().split(".");
        var latLngResult = result[0]+"."+result[1].substr(0,4);
        return latLngResult;
    }
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= env('MAP_API_KEY', '') ?>&callback=initMap" async defer></script>