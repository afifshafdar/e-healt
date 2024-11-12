<?php
if (isset($_POST['insert'])) {
    $username = getPost('username');
    $fullname = getPost('fullname');
    $email = getPost('email');
    $user_type = getPost('user_type');
    $password = getPost('password');
    $confirm_password = getPost('confirm_password');

    if(!empty(trim($username)) && !empty(trim($fullname)) && !empty(trim($email)) && !empty(trim($user_type)) && !empty(trim($password)) && !empty(trim($confirm_password))) {

        $check_uname = select("*", "users", "username = '$username' OR email = '$email'");
        
        //Check username and password if exist 
        if(cekRow($check_uname) > 0) {
            setMessage('error', "Username atau email sudah terdaftar!", "back");
        } else {

            if(!in_array($user_type, [1,2])) {
                setMessage('error', "Silahkan pilih tipe user!", "back");
            } else {

                //Check the length of password
                if(strlen($password) >= 6) {

                    //Check password and confirm_password
                    if($password === $confirm_password) {

                        //Encrypt password
                        $password = password_hash($password, PASSWORD_DEFAULT);
                        
                         $user_data = [
                            'username' => $username,
                            'fullname' => $fullname,
                            'email' => $email,
                            'user_type' => $user_type,
                            'password' => $password,
                        ];

                        $insert = insertArray("users", $user_data);

                        if($insert) {
                            setMessage('success', "Data user baru berhasil disimpan!", linkTo("user", [], false));
                        } else {
                            setMessage('error', "Gagal menyimpan data user baru!", "back");
                        }
                    } else {
                        setMessage('error', "Password dan konfirmasi password tidak sama!", "back");
                    }
                } else {
                    setMessage('error', "Panjang password minimal 6 karakter!", "back");
                }
            }
        }
    } else {
        setMessage('error', "Semua form harus diisi!", "back");
    }
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Manajemen User</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Baru</h6>
        </div>
        <div class="card-body mb-4">
            <form action="<?php echo linkTo("user", ['action' => 'create'], false) ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="offset-1 col-md-5">
                        <label for="kode" class="form-control-label">Username</label>
                        <input type="text" name="username" class="form-control" required autocomplete="off">
                        <br>

                        <label for="fullname" class="form-control-label">Nama Lengkap</label>
                        <input type="text" name="fullname" class="form-control" required autocomplete="off">
                        <br>

                        <label for="email" class="form-control-label">Email</label>
                        <input type="email" name="email" class="form-control" required autocomplete="off">
                        <br>
                       
                        <label for="user_type" class="form-control-label">User Type</label>
                        <select name="user_type" class="form-control" required>
                            <option>-- Pilih Tipe User --</option>
                            <option value="1">Administrator</option>
                            <option value="2">Operator</option>
                        </select>
                        <br><br>
                    </div>
                    <div class="col-md-5">
                        <label for="password" class="form-control-label">Password</label>
                        <input type="password" name="password" class="form-control" required autocomplete="off">
                        <br>
                        
                        <label for="confirm_password" class="form-control-label">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" class="form-control" required autocomplete="off">
                        <br>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <button class="btn btn-success btn-block" type="submit" name="insert">
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
<!-- /.container-fluid -->