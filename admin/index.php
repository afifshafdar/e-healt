<?php
if(file_exists("../core/init.php")) {
    require_once("../core/init.php");
} else {
    die("Main configuration file is empty!");
}

if(!cekSessionUser()) {
    redirect(base_url('admin/login.php'));
}


$page = getFrom('page');
$action = getFrom('action');

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>e-Health | Administrator</title>

    <link rel="shorcut icon" href="<?= base_url("assets/img/kesehatan.png") ?>">
    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url("assets/vendor/fontawesome-free/css/all.min.css") ?>" rel="stylesheet"
        type="text/css">
    <link href="<?php echo base_url("assets/vendor/select2/select2.min.css") ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url("assets/vendor/select2/select2-bootstrap4.min.css") ?>" rel="stylesheet"
        type="text/css">
    <link rel="stylesheet" href="<?= base_url("assets/vendor/sweetalert/sweetalert2.min.css") ?>">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet"
        href="<?= base_url("assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.min.css") ?>">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url("assets/css/sb-admin-2.min.css") ?>" rel="stylesheet">
    <link href="<?php echo base_url("assets/css/custom-style.css") ?>" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once("templates/sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php require_once("templates/navbar.php") ?>

                <!-- Begin Page Content -->
                <?php
                    if($page == ""):
                        //DATA FOR PIE CHART
                        require_once("content/main-page.php");
                    else:
                        getTemplate($page, $action);
                    endif;
                ?>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php require_once("templates/footer.php"); ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url("assets/vendor/jquery/jquery.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url("assets/vendor/jquery-easing/jquery.easing.min.js") ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url("assets/js/sb-admin-2.min.js") ?>"></script>

    <!-- Page level plugins -->
    <script src="<?php echo base_url("assets/vendor/chart.js/Chart.min.js") ?>"></script>



    <script src="<?php echo base_url("assets/vendor/select2/select2.min.js") ?>"></script>
    <!-- Page level plugins -->
    <script src="<?php echo base_url("assets/vendor/datatables/jquery.dataTables.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/vendor/datatables/dataTables.bootstrap4.min.js") ?>"></script>

    <!-- Page level custom scripts -->
    <script src="<?php echo base_url("assets/js/demo/datatables-demo.js") ?>"></script>
    <!-- Page level custom scripts -->
    <script src="<?php echo base_url("assets/vendor/chart.js/chartjs-plugin-labels.js") ?>"></script>
    <script src="<?php echo base_url("assets/js/demo/datatables-demo.js") ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/vendor/sweetalert/sweetalert2.all.min.js") ?>"></script>

    <script>
    $(document).ready(function() {
        if (!navigator.onLine) {
            Swal.fire({
                title: 'Tidak ada koneksi internet :(',
                text: 'Silahkan hubungkan komputer Anda dengan koneksi internet agar aplikasi dapat berjalan dengan baik!',
                icon: 'error'
            });
        }


        $("a[data-toggle=tooltip]").tooltip();
        $("#list-provinsi, #list-kabupaten").select2({
            theme: 'bootstrap4'
        });
        $('#data').DataTable({
            "ordering": false,
            "info": false,
            "pageLength": 5
        });
        $("#data2").DataTable();
        $("#data_wrapper > .row:first-child").remove();
        $(".paging_simple_numbers").addClass('float-right');

    });
    var askForLogout = () => {
        Swal.fire({
            title: 'Konfirmasi logout',
            text: "Apakah Anda yakin akan keluar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar sekarang!'
        }).then((result) => {
            if (result.value) {
                $("#logout-form").submit();
            }
        });
    }
    </script>
    <?php if(checkMessage()): ?>
    <script type="text/javascript">
    Swal.fire({
        title: '<?= ucfirst(getMessage('type')); ?>',
        text: '<?= getMessage('text') ?>',
        icon: '<?= getMessage('type'); ?>',
        timer: 2000
    }).then(() => {
        <?php if(getMessage('path_redirect') == 'back'): ?>
        window.history.back();
        <?php else: ?>
        window.location = '<?= getMessage('path_redirect'); ?>';
        <?php endif; ?>
    });
    </script>
    <?php setMessage('', '', ''); ?>
    <?php endif; ?>
    <?php if (in_array($page, ["kabupaten", "provinsi", "bengkel", "user"])): ?>
    <?php if($action == ""): ?>
    <script type="text/javascript">
    var confirmDelete = (uid) => {
        if (uid != '' && uid.length != 0) {
            Swal.fire({
                title: 'Confirm Delete',
                text: "Apakah anda yakin akan menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus sekarang!'
            }).then((result) => {
                if (result.value) {
                    var actionURL = "<?= linkTo($page, ['action' => 'delete'], false) ?>&id=" + uid;
                    $("#delete-form").attr('action', actionURL);
                    $("#delete-form input[name=id_<?=$page?>]").val(uid);
                    $("#delete-form").submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Please select one of the category to update the data!',
                icon: 'error',
                timer: 2000
            });
        }
    };
    </script>
    <?php elseif($action == "create" && $page != 'bengkel'): ?>
    <script type="text/javascript">
    var map;
    var markers = [];

    function initMap(title = "Indonesia", lat = -2.393102, lng = 108.8253218) {

        var myLatlng = new google.maps.LatLng(lat, lng);
        var info = new google.maps.InfoWindow({
            content: title
        });
        map = new google.maps.Map(document.getElementById("my-map"), {
            zoom: 5,
            center: myLatlng,
            scaleControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

        map.addListener('click', function(event) {
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
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=<?= env('MAP_API_KEY', '') ?>&callback=initMap" async defer>
    </script>
    <?php endif; ?>
    <?php endif ?>
    <?php if ($page == "bengkel" && ($action == "create" || $action == "edit")): ?>
    <script type="text/javascript">
    $("#list-provinsi").on('change', function() {
        var id_provinsi = $("#list-provinsi > option:selected").val();
        $("#list-kabupaten > option").remove();
        var listKab = document.getElementById('list-kabupaten');

        listKab.innerHTML += "<option>-- Pilih Kabupaten --</option>";
        if (id_provinsi != '' && id_provinsi.length != 0) {
            var URL = "<?= base_url('get-kabupaten.php') ?>?id=" + id_provinsi;
            $.ajax({
                method: 'GET',
                url: URL,
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        var optionValue = "";
                        var kab_data = res.data;
                        for (var i = 0; i < kab_data.length; i++) {
                            optionValue += "<option value='" + kab_data[i].id_kabupaten + "'>" +
                                kab_data[i].nama_kabupaten + "</option>";
                        }
                        listKab.innerHTML += optionValue;
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: res.message,
                            icon: 'error',
                            timer: 2000
                        });
                    }
                }
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Silahkan pilih provinsi terlebih dahulu!',
                icon: 'error',
                timer: 2000
            });
        }
    });

    $("#list-kabupaten").on('change', function() {
        var id_provinsi = $("#list-provinsi > option:selected").val();
        if (id_provinsi == '' && id_provinsi.length == 0) {
            Swal.fire({
                title: 'Error',
                text: 'Silahkan pilih provinsi terlebih dahulu!',
                icon: 'error',
                timer: 2000
            });
        } else {
            var id_kabupaten = $("#list-kabupaten > option:selected").val();
            if (id_kabupaten != '' && id_kabupaten.length != 0) {
                var URL = "<?= base_url('get-kabupaten.php') ?>?id=" + id_kabupaten + "&single=true";
                $.ajax({
                    method: 'GET',
                    url: URL,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 'success') {
                            $("#my-map").css('height', '600px');
                            $("#my-map > h1").remove();
                            var response = res.data;
                            drawMap(response.nama, response.lat, response.lng);
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: res.message,
                                icon: 'error',
                                timer: 2000
                            });
                        }
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Silahkan pilih kabupaten terlebih dahulu!',
                    icon: 'error',
                    timer: 2000
                });
            }
        }
    });
    </script>
    <?php endif ?>
</body>

</html>