<?php
$no = 1;
$page = getFrom('page');

$users = select("*", "users");
?>
<style>
    td > img:hover{
        cursor: pointer;
    }
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Manajemen User</h1>
        <div class="btn-group-sm">
            <a href="<?php echo linkTo("user", ['action' => 'create'], false) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="tooltip" title="Tambah Data">
                <i class="fas fa-plus text-white-50"></i>
                <span class="text">Tambah Data</span>
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar User</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Fullname</th>
                            <th>E-mail</th>
                            <th>User type</th>
                            <?php if(getSessionUser('user_type') == 1): ?>
                            <th width="180px">Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = result($users)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $user->username; ?></td>
                            <td class="align-middle"><?= $user->fullname; ?></td>
                            <td class="align-middle"><?= $user->email; ?></td>
                            <td class="align-middle"><?= ($user->user_type == 1) ? 'Administrator' : 'Operator' ?></td>
                            <?php if(getSessionUser('user_type') == 1): ?>
                            <td class="align-middle">
                                <a href="<?php echo linkTo("user", ['action' => 'edit', 'id' => $user->iduser], false) ?>" class="btn btn-primary btn-circle" data-toggle="tooltip" title="Update">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a onclick="confirmDelete('<?= $user->iduser; ?>')" href="#" class="btn btn-danger btn-circle" data-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                            <?php endif; ?>
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