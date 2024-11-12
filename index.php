<?php
if(file_exists("core/init.php")) {
    require_once("core/init.php");
} else {
    die("Main configuration file is empty!");
}

$page = getFrom('page');
$action = getFrom('action');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>e-Health - Solusi Kesehatan Anda</title>
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
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
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
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>
    <script src="<?= base_url('assets/js/other-function.js') ?>"></script>
    <!-- Page level plugins -->
    <script src="assets/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="assets/js/demo/chart-area-demo.js"></script>
    <script src="assets/js/demo/chart-pie-demo.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>


    <!-- Page level plugins -->
    <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="assets/js/demo/datatables-demo.js"></script>
    <script>
    $(document).ready(function() {
        if (!navigator.onLine) {
            Swal.fire({
                title: 'Tidak ada koneksi internet :(',
                text: 'Silahkan hubungkan komputer Anda dengan koneksi internet agar aplikasi dapat berjalan dengan baik!',
                icon: 'error'
            });
        }

        <?php if($page == "nearby" && $action == "position"): ?>
        getLocation();
        <?php endif; ?>
        $("a[data-toggle=tooltip]").tooltip();
        $('#data').DataTable({
            "ordering": false,
            "info": false,
            "pageLength": 5,
        });
        <?php if($page == "bengkel" && $action == "list"): ?>
        var data_bengkel = $("#data-bengkel").DataTable({
            "language": {
                "lengthMenu": "Tampil _MENU_ Data per halaman",
                "zeroRecords": "Tidak ada data.",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(Difilter dari _MAX_ total data)"
            }
        });

        $("#keyword-text").on('keyup', function() {
            data_bengkel.search(this.value).draw();
        });

        $("#btn-search").on('click', function() {
            var keyword = $("#keyword-text").val();
            if (keyword != '' && keyword.length != 0) {
                data_bengkel.search(this.value).draw();
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Silahkan isi keyword pencarian!',
                    icon: 'error',
                    timer: 2000
                });
                $("#keyword-text").focus();
            }
        })

        $("#data-bengkel_wrapper > .row:first-child").remove();
        $("#data-bengkel_filter > label").css('width', '80%');
        $("#data_wrapper > .row:first-child").remove();
        $(".paging_simple_numbers").addClass('float-right');

        $("#data-bengkel").on('click', 'a.btn-view-map', function() {
            let latitude = $(this).attr('data-lat');
            let longitude = $(this).attr('data-lng');
            let nama_bengkel = $(this).attr('data-nama');
            let id_bengkel = $(this).attr('data-id');

            if (latitude != '' && latitude.length != 0 && longitude != '' && longitude.length != 0) {
                $("#viewMap").modal('show');
                drawMap(nama_bengkel, latitude, longitude, id_bengkel);
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Silahkan pilih bengkel terlebih dahulu!',
                    icon: 'error',
                    timer: 2000
                });
            }
        });
        <?php endif; ?>
    });
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
        window.location = '<?= base_url(getMessage('path_redirect')); ?>';
        <?php endif; ?>
    });
    </script>
    <?php setMessage('', '', ''); ?>
    <?php endif; ?>
</body>

</html>