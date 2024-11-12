<?php
$no = 1;
$page = getFrom('page');
$sql_provinsi = select("*", "provinsi");
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Data Provinsi</h1>
        <div class="btn-group-sm">
            <a href="<?= linkTo("provinsi", ['action' => 'create'], false) ?>"
                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="tooltip"
                title="Add New Data" id="add-provinsi">
                <i class="fas fa-plus text-white-50"></i>
                <span class="text">Tambah Data</span>
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Provinsi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="dataTable" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Provinsi</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <?php if(getSessionUser('user_type') == 1): ?>
                            <th>Action</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($provinsi = result($sql_provinsi)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $provinsi->nama ?></td>
                            <td><?= $provinsi->lat ?></td>
                            <td><?= $provinsi->lng ?></td>
                            <?php if(getSessionUser('user_type') == 1): ?>
                            <td>
                                <a href="<?= linkTo("provinsi", [
                                    'action' => 'edit',
                                    'id' => $provinsi->idprovinsi
                                ], false); ?>" class="btn btn-primary btn-circle btn-update" data-toggle="tooltip"
                                    title="Update">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a onclick="confirmDelete('<?= $provinsi->idprovinsi ?>')" href="#"
                                    class="btn btn-danger btn-circle" data-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                            <?php endif ?>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <form action="" id="delete-form" method="POST">
                    <input type="hidden" name="id_<?=$page?>">
                    <input type="hidden" name="delete" value="TRUE">
                </form>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->