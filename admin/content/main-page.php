        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div>
            <!-- Content Row -->

            <div class="row">

                <!-- Area Chart -->
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->

                        <!-- Card Body -->
                        <div class="card-body p-lg-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <h1 class="h1">e-Health</h1>
                                    <h6 class="h6">
                                        ~ Solusi Kesehatan Anda ~
                                    </h6>
                                    <hr>
                                    <p class="mt-3">
                                    <div class="row">
                                        <?php for($i=1; $i<=4; $i++): ?>
                                        <div class="col-md-6 <?= ($i > 2) ? "mt-5" : ""; ?>">
                                            <img src="<?= base_url("assets/img/kesehatan.png") ?>" alt=""
                                                style="height: 100px;">
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <img src="<?= base_url('assets/img/bg.png') ?>" alt="" width="100%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <!-- /.container-fluid -->