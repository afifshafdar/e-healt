<?php
$id_kabupaten = getFrom('id');
$sql_provinsi = select("nama, idprovinsi", "provinsi");
$sql_kabupaten = select("*", "kabupaten", "idkabupaten = '$id_kabupaten'");

if(cekRow($sql_kabupaten) <= 0) {
    redirect(404);
}

$kabupaten = result($sql_kabupaten);

if (isset($_POST['update'])) {
    $nama_kabupaten = getPost('nama_kabupaten');
    $id_provinsi = getPost('provinsi');
    $latitude = getPost('latitude');
    $longitude = getPost('longitude');

    if(!empty(trim($nama_kabupaten)) && !empty(trim($id_provinsi)) && !empty(trim($latitude)) && !empty(trim($longitude))) {

        $validate = select("*", "kabupaten", "lat = '$latitude' AND lng = '$longitude'");
        if(cekRow($validate) > 0) {
            setMessage('error', "Nama kabupaten atau letak kabupaten sudah terpakai!", "back");
        } else {
            $kabupaten_data = [
                'nama' => strtoupper($nama_kabupaten),
                'idprovinsi' => $id_provinsi,
                'lat' => $latitude,
                'lng' => $longitude
            ];

            $update = updateArray("kabupaten", $kabupaten_data, ['idkabupaten' => $kabupaten->idkabupaten]);

            if($update) {
                setMessage('success', "Data kabupaten berhasil diperbarui!", linkTo("kabupaten", [], false));
            } else {
                setMessage('error', "Gagal memperbarui data kabupaten!", "back");
            }
        }
    } else {
        setMessage('error', "Silahkan isi nama kabupaten dan tentukan titik tengah wilayah kabupaten pada peta!", "back");
    }
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Kabupaten</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo linkTo("kabupaten", ['action' => 'edit', 'id' => $kabupaten->idkabupaten], false) ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="offset-1 col-md-5">
                        <label for="nama" class="form-control-label">Nama kabupaten</label>
                        <input type="text" value="<?= $kabupaten->nama ?>" name="nama_kabupaten" class="form-control" required autocomplete="off">
                        <br>

                        <label for="provinsi" class="form-control-label">Provinsi</label>
                        <select name="provinsi" id="list-provinsi" class="form-control" required>
                            <option>-- Pilih Provinsi --</option>
                            <?php while($prov = result($sql_provinsi)): ?>
                                <?php if($prov->idprovinsi == $kabupaten->idprovinsi): ?>
                                    <option value="<?= $prov->idprovinsi ?>" selected><?= $prov->nama ?></option>
                                <?php else: ?>
                                    <option value="<?= $prov->idprovinsi ?>"><?= $prov->nama ?></option>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </select>
                        <br>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="latitude" class="form-control-label">Latitude</label>
                                <input type="text" name="latitude" value="<?= $kabupaten->lat ?>" id="latitude" class="form-control" required autocomplete="off" readonly>
                                <br>                                
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-control-label">Longitude</label>
                                <input type="text" name="longitude" value="<?= $kabupaten->lng ?>" id="longitude" class="form-control" required autocomplete="off" readonly>
                                <br><br>                                
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" type="submit" name="update">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo linkTo("kabupaten", [], false) ?>" class="btn btn-secondary btn-block">
                                    <span>Kembali</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-10 offset-1">
                        <div class="alert alert-info">
                            <strong>Silahkan tentukan letak kabupaten dengan klik wilayah pada peta di bawah!</strong>
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
        var myLatlng = new google.maps.LatLng(<?= $kabupaten->lat ?>, <?= $kabupaten->lng ?>);
        var info = new google.maps.InfoWindow({
            content: "<?= $kabupaten->nama ?>"
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
            marker.setMap(null);
            addMarker(event.latLng);
            var latitude = event.latLng.lat();
            var longitude = event.latLng.lng();

            document.getElementById('latitude').value = toLatLong(latitude);
            document.getElementById('longitude').value = toLatLong(longitude);
        });
    }

    function addMarker(location) {
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
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