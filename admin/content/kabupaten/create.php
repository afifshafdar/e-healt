<?php
$sql_provinsi = select("nama, idprovinsi", "provinsi");

if (isset($_POST['insert'])) {
    $nama_kabupaten = getPost('nama_kabupaten');
    $id_provinsi = getPost('provinsi');
    $latitude = getPost('latitude');
    $longitude = getPost('longitude');

    if(!empty(trim($nama_kabupaten)) && !empty(trim($id_provinsi)) && !empty(trim($latitude)) && !empty(trim($longitude))) {

        $validate = select("*", "kabupaten", "nama = '$nama_kabupaten' OR (lat = '$latitude' AND lng = '$longitude')");
        if(cekRow($validate) > 0) {
            setMessage('error', "Nama kabupaten atau letak kabupaten sudah terpakai!", "back");
        } else {
            $kabupaten_data = [
                'nama' => strtoupper($nama_kabupaten),
                'idprovinsi' => $id_provinsi,
                'lat' => $latitude,
                'lng' => $longitude
            ];

            $insert = insertArray("kabupaten", $kabupaten_data);

            if($insert) {
                setMessage('success', "Data kabupaten baru berhasil disimpan!", linkTo("kabupaten", [], false));
            } else {
                setMessage('error', "Gagal menyimpan data kabupaten baru!", "back");
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
            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Baru</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo linkTo("kabupaten", ['action' => 'create'], false) ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="offset-1 col-md-5">
                        <label for="nama" class="form-control-label">Nama kabupaten</label>
                        <input type="text" name="nama_kabupaten" class="form-control" required autocomplete="off">
                        <br>

                        <label for="provinsi" class="form-control-label">Provinsi</label>
                        <select name="provinsi" id="list-provinsi" class="form-control" required>
                            <option>-- Pilih Provinsi --</option>
                            <?php while($prov = result($sql_provinsi)): ?>
                                <option value="<?= $prov->idprovinsi ?>"><?= $prov->nama ?></option>
                            <?php endwhile; ?>
                        </select>
                        <br>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="latitude" class="form-control-label">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control" required autocomplete="off" readonly>
                                <br>                                
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-control-label">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control" required autocomplete="off" readonly>
                                <br><br>                                
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" type="submit" name="insert">
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