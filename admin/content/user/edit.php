<?php
$id_user = getFrom('id');
$user_sql = select("*", "users", "iduser = '$id_user'");
$user = result($user_sql);

if (isset($_POST['update'])) {
    $username = getPost('username');
    $fullname = getPost('fullname');
    $email = getPost('email');
    $user_type = getPost('user_type');

    if(!empty(trim($username)) && !empty(trim($fullname)) && !empty(trim($email)) && !empty(trim($user_type))) {

        $check_uname = select("*", "users", "(username = '$username' OR email = '$email') AND iduser != '$id_user'");
        
        //Check username and password if exist 
        if(cekRow($check_uname) > 0) {
            setMessage('error', "Username atau email sudah terpakai!", "back");
        } else {

            if(!in_array($user_type, [1,2])) {
                setMessage('error', "Silahkan pilih tipe user!", "back");
            } else {                        
                 $user_data = [
                    'username' => $username,
                    'fullname' => $fullname,
                    'email' => $email,
                    'user_type' => $user_type
                ];

                $update = updateArray("users", $user_data, ['iduser' => $user->iduser]);

                if($update) {
                    setMessage('success', "Data user berhasil diperbarui!", linkTo("user", [], false));
                } else {
                    setMessage('error', "Gagal memperbarui data user!", "back");
                }
            }
        }
    } else {
        setMessage('error', "Semua form harus diisi!", "back");
    }
}

?>
<!-- Begin Page Content -->
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Manajemen User</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo linkTo("user", ['action' => 'edit', 'id' => $user->iduser], false) ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="offset-1 col-md-5">
                        <label for="kode" class="form-control-label">Username</label>
                        <input type="text" name="username" value="<?= $user->username; ?>" class="form-control" required autocomplete="off">
                        <br>

                        <label for="fullname" class="form-control-label">Nama Lengkap</label>
                        <input type="text" name="fullname" value="<?= $user->fullname; ?>" class="form-control" required autocomplete="off">
                        <br>
                        
                        <label for="email" class="form-control-label">Email</label>
                        <input type="email" name="email" value="<?= $user->email; ?>" class="form-control" required autocomplete="off">
                        <br>
                    </div>
                    <div class="col-md-5">
                       
                        <label for="user_type" class="form-control-label">User Type</label>
                        <select name="user_type" class="form-control" required>
                            <option>-- Pilih Tipe User --</option>
                            <option value="1" <?= ($user->user_type == 1) ? 'selected' : ''; ?>>Administrator</option>
                            <option value="2" <?= ($user->user_type == 2) ? 'selected' : ''; ?>>Operator</option>
                        </select>
                        <br><br>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" type="submit" name="update">
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo linkTo("user", [], false) ?>" class="btn btn-secondary btn-block">
                                    <span>Kembali</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fliduser -->