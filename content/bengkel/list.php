<?php
$no = 1;
$page = getFrom('page');

$sql_bengkel = query("SELECT bengkel.*, kabupaten.nama AS kabupaten, provinsi.nama as provinsi FROM bengkel JOIN kabupaten USING (idkabupaten) JOIN provinsi USING (idprovinsi)");
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
        <h1 class="h3 mb-0 text-gray-800">Daftar Layanan</h1>
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
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="data-bengkel" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">Foto</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Alamat</th>
                            <th width="180px">Peta Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($bengkel = result($sql_bengkel)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="text-center">
                                <img src="<?= getImage("bengkel", $bengkel->foto) ?>" alt=""
                                    class="img img-fluid img-thumbnail" style="width: 200px;"
                                    data-uid="<?= $bengkel->uid; ?>">
                            </td>
                            <td class="align-middle"><?= $bengkel->nama ?></td>
                            <td class="align-middle"><?= $bengkel->jenis ?></td>
                            <td class="align-middle"><i><?= $bengkel->alamat ?></i></td>
                            <td class="align-middle">
                                <a href="#" class="btn btn-success btn-view-map" data-toggle="tooltip"
                                    title="Lihat peta lokasi" data-lat="<?= $bengkel->lat ?>"
                                    data-lng="<?= $bengkel->lng ?>" data-nama="<?= $bengkel->nama ?>"
                                    data-id="<?= $bengkel->idbengkel ?>">
                                    <i class="fas fa-eye"></i>
                                    <span>Lihat Peta</span>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<div class="modal fade" id="viewMap" tabindex="-1" role="dialog" aria-labelledby="viewMapLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="width: 100% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Peta Lokasi</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="my-map" style="width: 100%;height: 600px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var map;

function drawMap(title, lat, lng, id_bengkel) {
    console.log(id_bengkel);
    var myLatlng = new google.maps.LatLng(lat, lng);
    map = new google.maps.Map(document.getElementById("my-map"), {
        zoom: 18,
        center: myLatlng
    });

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map
    });

    let URL = '<?= base_url("get-bengkel.php") ?>?id=' + id_bengkel;
    let infoWindow = new google.maps.InfoWindow();
    $.ajax({
        url: URL,
        method: 'GET',
        dataType: 'json',
        success: function(res) {
            var data_bengkel = res.data;
            lokasi = lat + "," + lng;
            boxInfo = getBoxInfo(data_bengkel);
            infoWindow.setContent(boxInfo);
            infoWindow.setPosition(myLatlng);
            infoWindow.open(map, marker);
        }
    });
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
        "<li style='color:#333 !important;'><i class='fas fa-fw fa-random'></i>&nbsp;<a href='<?= linkTo('bengkel', ['action' => 'petunjuk-arah', 'target-location' => '']) ?>" +
        lokasi + "'>";
    boxInfo += "Petunjuk Arah</a></li></ul></p></div></div>";
    return boxInfo;
}
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= env('MAP_API_KEY', '') ?>" async
    defer></script>