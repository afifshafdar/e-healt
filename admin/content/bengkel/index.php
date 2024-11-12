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
        <h1 class="h3 mb-0 text-gray-800">Data Layanan Kesehatan</h1>
        <div class="btn-group-sm">
            <a href="<?php echo linkTo($page, ['action' => 'create'], false) ?>"
                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="tooltip"
                title="Tambah Data">
                <i class="fas fa-plus text-white-50"></i>
                <span class="text">Tambah Data</span>
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Layanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="dataTable" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">Foto</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Alamat</th>
                            <th width="180px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($bengkel = result($sql_bengkel)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="text-center">
                                <img src="<?= getImage("bengkel", $bengkel->foto, true) ?>" alt=""
                                    class="img img-fluid img-thumbnail" style="width: 120px;"
                                    data-uid="<?= $bengkel->uid; ?>">
                            </td>
                            <td class="align-middle"><?= $bengkel->nama ?></td>
                            <td class="align-middle"><?= $bengkel->jenis ?></td>
                            <td class="align-middle"><?= $bengkel->alamat ?></td>
                            <td class="align-middle">
                                <a href="<?php echo linkTo($page, ['action' => 'edit', 'id' => $bengkel->idbengkel], false) ?>"
                                    class="btn btn-primary btn-circle" data-toggle="tooltip" title="Update">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="<?= linkTo($page, ['action' => 'detail', 'id' => $bengkel->idbengkel], false) ?>"
                                    class="btn btn-success btn-circle" data-toggle="tooltip" title="Show detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if(getSessionUser('user_type') == 1): ?>
                                <a onclick="confirmDelete('<?= $bengkel->idbengkel; ?>')" href="#"
                                    class="btn btn-danger btn-circle btn-delete" data-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
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