<?php
$sql_kabupaten_ = select("*", "kabupaten");
$sql_provinsi = select("*", "provinsi");

$arr_hari_kerja = getHariKerja();

if (isset($_POST['insert'])) {
    $nama_bengkel = getPost('nama_bengkel');
    $jenis = getPost('jenis');
    $telpon = getPost('telpon');
    $kabupaten = getPost('kabupaten');
    $latitude = getPost('latitude');
    $longitude = getPost('longitude');
    $alamat = getPost('alamat');
    $jam_buka = getPost('jam_buka');
    $jam_tutup = getPost('jam_tutup');
    $hari_kerja = getPost('hari_kerja');

    if(!empty(trim($nama_bengkel)) && !empty(trim($jenis)) && !empty(trim($telpon)) && !empty(trim($kabupaten)) && !empty(trim($latitude)) && !empty(trim($longitude)) && !empty(trim($alamat))) {

        $check = select("*", "bengkel", "lat = '$latitude' AND lng = '$longitude'");
        if(cekRow($check) > 0) {
            setMessage('error', "Bengkel dengan posisi tersebut telah terpakai!", "back");
        } else {
            $upload_path = '../uploads/bengkel/';
            $temporary_file = getFile('foto', 'tmp_name');
            $file_type = getFile('foto', 'type');
            
            $file_type = explode("/", $file_type);
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

            if(in_array(end($file_type), $allowed_types)) {
                $new_file_name = md5(date('Y-m-d H:i:s')) . '.' . end($file_type);

                if(move_uploaded_file($temporary_file, $upload_path.$new_file_name)) {

                    $bengkel_data = [
                        'nama'  => $nama_bengkel,
                        'jenis' => $jenis,
                        'telpon' => $telpon,
                        'idkabupaten' => $kabupaten,
                        'lat' => $latitude,
                        'lng' => $longitude,
                        'alamat' => $alamat,
                        'foto' => $new_file_name,
                        'jam_buka' => $jam_buka,
                        'jam_tutup' => $jam_tutup,
                        'hari_kerja' => $hari_kerja
                    ];

                    $insert = insertArray("bengkel", $bengkel_data);

                    if($insert) {
                        setMessage('success', "Data bengkel baru berhasil disimpan!", linkTo("bengkel", [], false));
                    } else {
                        setMessage('error', "Gagal menyimpan data bengkel baru!", "back");
                    }
                } else {
                    setMessage('error', "Gagal mengupload foto bengkel!", "back");
                }
            } else {
                setMessage('error', "Format foto yang Anda upload tidak didukung!", "back");
            }
        }
    } else {
        setMessage('error', "Semua form harus diisi!", "back");
    }
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Layanan Kesehatan</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Baru</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo linkTo("bengkel", ['action' => 'create'], false) ?>" method="POST"
                enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-5">
                        <label for="nama" class="form-control-label">Nama Instansi</label>
                        <input type="text" name="nama_bengkel" class="form-control" required autocomplete="off">
                        <br>

                        <label for="jenis" class="form-control-label">Jenis</label>
                        <select name="jenis" id="list-jenis" class="form-control" required>
                            <option>-- Pilih Jenis --</option>
                            <option value="Rumah Sakit">Rumah Sakit</option>
                            <option value="Puskesmas">Puskesmas</option>
                            <option value="Apotek">Apotek</option>
                        </select>
                        <br>

                        <label for="telpon" class="form-control-label">Telpon</label>
                        <input type="text" name="telpon" class="form-control" required autocomplete="off">
                        <br>

                        <label for="provinsi" class="form-control-label">Provinsi</label>
                        <select name="provinsi" id="list-provinsi" class="form-control" required>
                            <option>-- Pilih provinsi --</option>
                            <?php while ($provinsi = result($sql_provinsi)): ?>
                            <option value="<?= $provinsi->idprovinsi ?>"><?= $provinsi->nama; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <br><br>

                        <label for="kabupaten" class="form-control-label">Kabupaten</label>
                        <select name="kabupaten" id="list-kabupaten" class="form-control" required>
                            <option>-- Pilih Kabupaten --</option>
                        </select>
                        <br>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="latitude" class="form-control-label">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control" required
                                    autocomplete="off">
                                <br>
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-control-label">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control" required
                                    autocomplete="off">
                                <br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label for="jam_buka" class="form-control-label">Jam Buka</label>
                                <input type="time" name="jam_buka" id="jam_buka" class="form-control" required
                                    autocomplete="off">
                                <br>
                            </div>
                            <div class="col-md-3">
                                <label for="jam_tutup" class="form-control-label">Jam Tutup</label>
                                <input type="time" name="jam_tutup" id="jam_tutup" class="form-control" required
                                    autocomplete="off">
                                <br>
                            </div>
                            <div class="col-md-6">
                                <label for="hari_kerja" class="form-control-label">Hari Kerja</label>
                                <select name="hari_kerja" class="form-control" required>
                                    <option>-- Pilih Hari Kerja --</option>
                                    <?php foreach ($arr_hari_kerja as $key => $hari): ?>
                                    <option value="<?= $key ?>"><?= $hari ?></option>
                                    <?php endforeach ?>
                                </select>
                                <br>
                            </div>
                        </div>

                        <label for="alamat" class="form-control-label">Alamat</label>
                        <input type="text" name="alamat" required autocomplete="off" class="form-control">
                        <br>

                        <label for="foto" class="form-control-label">Foto</label>
                        <input type="file" accept="image/*" name="foto" class="form-control" required
                            autocomplete="off">
                        <br><br>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" type="submit" name="insert">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo base_url("admin/index.php?page=bengkel") ?>"
                                    class="btn btn-secondary btn-block">
                                    <span>Kembali</span>
                                </a>
                            </div>
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
            <div id="my-map" style="width: 100%;" class="text-center">
                <h1>Silahkan Pilih Provinsi dan Kabupaten Terlebih Dahulu!</h1>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= env('MAP_API_KEY', '') ?>" async
    defer></script>
<script type="text/javascript">
var map;
var markers = [];

function drawMap(nama_kabupaten, lat, lng) {
    var myLatlng = new google.maps.LatLng(lat, lng);
    var info = new google.maps.InfoWindow({
        content: nama_kabupaten
    });
    map = new google.maps.Map(document.getElementById("my-map"), {
        zoom: 16,
        center: myLatlng,
        scaleControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
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
    var latLngResult = result[0] + "." + result[1].substr(0, 4);
    return latLngResult;
}
</script>