<?php
$no = 1;
$page = getFrom('page');
$id_bengkel = getFrom('id');

$sql_bengkel = joinTable("bengkel.*, kabupaten.nama as kabupaten, kabupaten.idprovinsi", "bengkel", "kabupaten", "bengkel.idkabupaten = kabupaten.idkabupaten", "idbengkel = '$id_bengkel'");
$bengkel = result($sql_bengkel);

$sql_provinsi = select("nama", "provinsi", "idprovinsi = '$bengkel->idprovinsi'");
$provinsi = result($sql_provinsi);

if (isset($_POST['update'], $_FILES['foto']) && $_FILES['foto']['name'] != '') {
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
            $path_foto = "../uploads/bengkel/".$bengkel->foto;
            if(file_exists($path_foto)) {
                unlink($path_foto);
            }

            $update = updateArray("bengkel", $bengkel_data, ['idbengkel' => $id_bengkel]);

            if($update) {
                setMessage('success', "Data bengkel baru berhasil diperbarui!", linkTo("bengkel", ['action' => 'detail', 'id' => $bengkel->idbengkel], false));
            } else {
                setMessage('error', "Gagal memperbarui data bengkel!", "back");
            }

        } else {
            setMessage('error', "Gagal mengupload foto bengkel!", "back");
        }
    } else {
        setMessage('error', "Format foto yang Anda upload tidak didukung!", "back");
    }
}


?>
<style>
td>img:hover {
    cursor: pointer;
}
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Data Bengkel</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail bengkel</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="<?= getImage("bengkel", $bengkel->foto, true) ?>" alt=""
                        class="img img-thumbnail img-fluid">
                    <a href="#" data-toggle="modal" data-target="#changeCover" class="btn btn-primary mt-3">Ubah
                        Foto</a>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td class="font-weight-bold">Nama Bengkel</td>
                                <td>:</td>
                                <td><?= $bengkel->nama; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Jenis</td>
                                <td>:</td>
                                <td><?= $bengkel->jenis; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">No. Telpon</td>
                                <td>:</td>
                                <td><?= $bengkel->telpon ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Alamat</td>
                                <td>:</td>
                                <td><?= $bengkel->alamat; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Kabupaten</td>
                                <td>:</td>
                                <td><?= $bengkel->kabupaten; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Provinsi</td>
                                <td>:</td>
                                <td><?= $provinsi->nama; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Jam Kerja</td>
                                <td>:</td>
                                <td><?= $bengkel->jam_buka . " ~ ".$bengkel->jam_tutup; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Hari Kerja</td>
                                <td>:</td>
                                <td><?= getHariKerja($bengkel->hari_kerja); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Latitude</td>
                                <td>:</td>
                                <td><?= $bengkel->lat; ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Longitude</td>
                                <td>:</td>
                                <td class="font-italic"><?= $bengkel->lng; ?></td>
                            </tr>
                        </table>

                        <a href="<?= linkTo("bengkel", ['action' => 'edit', 'id' => $bengkel->idbengkel], false) ?>"
                            class="btn btn-success">
                            <span>Edit</span>
                        </a>
                        <a href="<?= linkTo('bengkel', [], false) ?>" class="btn btn-secondary">
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Peta Lokasi Layanan</h6>
        </div>
        <div class="card-body mb-4">
            <div id="my-map" style="width: 100%;height: 600px;"></div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<div class="modal fade" id="changeCover" tabindex="-1" role="dialog" aria-labelledby="changeCoverLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ubah Foto Bengkel</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= linkTo("bengkel", ['action' => 'detail', 'id' => $bengkel->idbengkel], false) ?>"
                    enctype="multipart/form-data" method="POST">
                    <label for="file">Foto</label>
                    <input type="file" accept="image/*" name="foto" class="form-control" required>
                    <div class="float-right mt-3">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" type="submit" name="update">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var map;

function drawMap() {
    var myLatlng = new google.maps.LatLng(<?= $bengkel->lat ?>, <?= $bengkel->lng ?>);
    var info = new google.maps.InfoWindow({
        content: "<?= $bengkel->nama ?>"
    });
    map = new google.maps.Map(document.getElementById("my-map"), {
        zoom: 16,
        scaleControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: myLatlng
    });

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map
    });

    info.open(map, marker);
    marker.setMap(map)
}
</script>
<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=<?= env('MAP_API_KEY', '') ?>&callback=drawMap" async defer>
</script>