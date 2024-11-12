<?php
if (isset($_POST['insert'])) {
    $nama_provinsi = getPost('nama_provinsi');
    $latitude = getPost('latitude');
    $longitude = getPost('longitude');

    if(!empty(trim($nama_provinsi)) && !empty(trim($latitude)) && !empty(trim($longitude))) {

        $validate = select("*", "provinsi", "nama = '$nama_provinsi' OR (lat = '$latitude' AND lng = '$longitude')");
        if(cekRow($validate) > 0) {
            setMessage('error', "Nama provinsi atau letak provinsi sudah terpakai!", "back");
        } else {
            $provinsi_data = [
                'nama' => strtoupper($nama_provinsi),
                'lat' => $latitude,
                'lng' => $longitude
            ];

            $insert = insertArray("provinsi", $provinsi_data);

            if($insert) {
                setMessage('success', "Data provinsi baru berhasil disimpan!", linkTo("provinsi", [], false));
            } else {
                setMessage('error', "Gagal menyimpan data provinsi baru!", "back");
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
            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Baru</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo linkTo("provinsi", ['action' => 'create'], false) ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="offset-1 col-md-5">
                        <label for="nama" class="form-control-label">Nama Provinsi</label>
                        <input type="text" name="nama_provinsi" class="form-control" required autocomplete="off">
                        <br>

                        <label for="latitude" class="form-control-label">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control" required autocomplete="off" readonly>
                        <br>                                
                    </div>
                    <div class="col-md-5">
                        <label for="longitude" class="form-control-label">Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control" required autocomplete="off" readonly>
                        <br><br>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" type="submit" name="insert">
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