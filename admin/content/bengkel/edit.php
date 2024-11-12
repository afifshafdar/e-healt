<?php
$id_bengkel = getFrom('id');

$sql_kabupaten = select("*", "kabupaten");
$sql_provinsi = select("*", "provinsi");
$sql_bengkel = joinTable("bengkel.*, kabupaten.nama as kabupaten", "bengkel", "kabupaten", "bengkel.idkabupaten = kabupaten.idkabupaten", "idbengkel = '$id_bengkel'");
$arr_hari_kerja = getHariKerja();

if(cekRow($sql_bengkel) <= 0) {
    redirect(404);
}

$bengkel = result($sql_bengkel);
$id_provinsi = result(select("idprovinsi", "kabupaten", "idkabupaten = '$bengkel->idkabupaten'"));

if (isset($_POST['update'])) {
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

        $check = select("*", "bengkel", "(lat = '$latitude' AND lng = '$longitude') AND idbengkel != '$id_bengkel'");
        if(cekRow($check) > 0) {
            setMessage('error', "Bengkel dengan posisi tersebut telah terpakai!", "back");
        } else {

            $bengkel_data = [
                'nama'  => $nama_bengkel,
                'jenis' => $jenis,
                'telpon' => $telpon,
                'idkabupaten' => $kabupaten,
                'lat' => $latitude,
                'lng' => $longitude,
                'alamat' => $alamat,
                'jam_buka' => $jam_buka,
                'jam_tutup' => $jam_tutup,
                'hari_kerja' => $hari_kerja
            ];

            //When user upload an image
            if(isset($_FILES['foto']) && $_FILES['foto']['name'] != '') {
                $upload_path = '../uploads/bengkel/';
                $temporary_file = getFile('foto', 'tmp_name');
                $file_type = getFile('foto', 'type');
                
                $file_type = explode("/", $file_type);
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

                //Check the extension file
                if(in_array(end($file_type), $allowed_types)) {
                    $new_file_name = md5(date('Y-m-d H:i:s')) . '.' . end($file_type);

                    //Upload to server
                    if(move_uploaded_file($temporary_file, $upload_path.$new_file_name)) {
                        $bengkel_data['foto'] = $new_file_name;
                        $path_foto = "../upload/bengkel/".$bengkel->foto;
                        if(file_exists($path_foto)) {
                            unlink($path_foto);
                        }
                    } else {
                        setMessage('error', "Gagal mengupload foto bengkel!", "back");
                    }
                } else {
                    setMessage('error', "Format foto yang Anda upload tidak didukung!", "back");
                }

            }

            $update = updateArray("bengkel", $bengkel_data, ['idbengkel' => $id_bengkel]);

            if($update) {
                setMessage('success', "Data bengkel baru berhasil diperbarui!", linkTo("bengkel", [], false));
            } else {
                setMessage('error', "Gagal memperbarui data bengkel!", "back");
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
    <h1 class="h3 mb-2 text-gray-800">Data Bengkel</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo linkTo("bengkel", ['action' => 'edit', 'id' => $bengkel->idbengkel], false) ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-5">
                        <label for="nama" class="form-control-label">Nama Bengkel</label>
                        <input type="text" value="<?= $bengkel->nama ?>" name="nama_bengkel" class="form-control" required autocomplete="off">
                        <br>

                        <label for="jenis" class="form-control-label">Jenis</label>
                        <select name="jenis" id="list-jenis" class="form-control" required>
                            <option>-- Pilih Jenis --</option>
                            <option value="Bengkel Motor" <?= ($bengkel->jenis == "Bengkel Motor") ? "selected" : ""; ?>>Bengkel Motor </option>
                            <option value="Bengkel Mobil" <?= ($bengkel->jenis == "Bengkel Mobil") ? "selected" : ""; ?>>Bengkel Mobil</option>
                            <option value="Tambal Ban" <?= ($bengkel->jenis == "Tambal Ban") ? "selected" : ""; ?>>Tambal Ban</option>
                        </select>
                        <br>

                        <label for="telpon" class="form-control-label">Telpon</label>
                        <input type="text" value="<?= $bengkel->telpon ?>" name="telpon" class="form-control" required autocomplete="off">
                        <br>

                        <label for="provinsi" class="form-control-label">Provinsi</label>
                        <select name="provinsi" id="list-provinsi" class="form-control" required>
                            <option>-- Pilih provinsi --</option>
                            <?php while ($provinsi = result($sql_provinsi)): ?>
                                <?php if ($id_provinsi->idprovinsi == $provinsi->idprovinsi): ?>
                                    <option selected value="<?= $provinsi->idprovinsi ?>"><?= $provinsi->nama; ?></option>
                                <?php else: ?>
                                    <option value="<?= $provinsi->idprovinsi ?>"><?= $provinsi->nama; ?></option>
                                <?php endif ?>
                            <?php endwhile; ?>
                        </select>
                        <br><br>

                        <label for="kabupaten" class="form-control-label">Kabupaten</label>
                        <select name="kabupaten" id="list-kabupaten" class="form-control" required>
                            <option>-- Pilih Kabupaten --</option>
                            <?php while ($kabupaten = result($sql_kabupaten)): ?>
                                <?php if ($kabupaten->idkabupaten == $bengkel->idkabupaten): ?>
                                    <option selected value="<?= $kabupaten->idkabupaten ?>"><?= $kabupaten->nama; ?></option>
                                <?php else: ?>
                                    <option value="<?= $kabupaten->idkabupaten ?>"><?= $kabupaten->nama; ?></option>
                                <?php endif ?>
                            <?php endwhile; ?>
                        </select>
                        <br>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="latitude" class="form-control-label">Latitude</label>
                                <input type="text" value="<?= $bengkel->lat ?>" name="latitude" id="latitude" class="form-control" required autocomplete="off">
                                <br>                                
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-control-label">Longitude</label>
                                <input type="text" value="<?= $bengkel->lng ?>" name="longitude" id="longitude" class="form-control" required autocomplete="off">
                                <br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label for="jam_buka" class="form-control-label">Jam Buka</label>
                                <input type="time" value="<?= $bengkel->jam_buka ?>" name="jam_buka" id="jam_buka" class="form-control" required autocomplete="off">
                                <br>                                
                            </div>
                            <div class="col-md-3">
                                <label for="jam_tutup" class="form-control-label">Jam Tutup</label>
                                <input type="time" name="jam_tutup" value="<?= $bengkel->jam_tutup; ?>" id="jam_tutup" class="form-control" required autocomplete="off">
                                <br>                                
                            </div>
                            <div class="col-md-6">
                                <label for="hari_kerja" class="form-control-label">Hari Kerja</label>
                                <select name="hari_kerja" class="form-control" required>
                                    <option>-- Pilih Hari Kerja --</option>
                                    <?php foreach ($arr_hari_kerja as $key => $hari): ?>
                                        <?php if ($bengkel->hari_kerja == $key): ?>
                                            <option selected value="<?= $key ?>"><?= $hari ?></option>
                                        <?php else: ?>
                                            <option value="<?= $key ?>"><?= $hari ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                                <br>                                
                            </div>
                        </div>

                        <label for="alamat" class="form-control-label">Alamat</label>
                        <input type="text" value="<?= $bengkel->alamat ?>" name="alamat" required autocomplete="off" class="form-control">
                        <br>

                        <label for="foto" class="form-control-label">Foto</label>
                        <input type="file" accept="image/*" name="foto" class="form-control" autocomplete="off">
                        <br><br>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" type="submit" name="update">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo base_url("admin/index.php?page=bengkel") ?>" class="btn btn-secondary btn-block">
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
            <div id="my-map" style="width: 100%;height: 600px;"></div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<script type="text/javascript">
    var map;
    var markers = [];
    
    function drawMap(title = "<?= $bengkel->nama; ?>", lat = <?= $bengkel->lat ?>, lng = <?= $bengkel->lng ?>) {
        var myLatlng = new google.maps.LatLng(lat, lng);
        var info = new google.maps.InfoWindow({
            content: title
        });
        map = new google.maps.Map(document.getElementById("my-map"), {
            zoom: 16,
            scaleControl : true,
            mapTypeId : google.maps.MapTypeId.ROADMAP,
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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= env('MAP_API_KEY', '') ?>&callback=drawMap" async defer></script>