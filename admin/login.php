<?php
if(file_exists("../core/init.php")) {
    require_once("../core/init.php");
} else {
    die("Main configuration file is empty!");
}

if(cekSessionUser()) {
    redirect(base_url('admin'));
}

if (isset($_POST['login'])) {
    $username = getPost('username');
    $password = getPost('password');

    if(!empty(trim($username)) && !empty(trim($password))) {
        
        //Cek nis
        $sql_check_uname = select("*", "users", "username = '$username'");
        $check_uname = cekRow($sql_check_uname);

        //If nis is exist in table
        if($check_uname > 0) {
            $user = result($sql_check_uname);

            //Validate password
            if(password_verify($password, $user->password)) {

                //Set Session
                setSessionUser($user);

                //Redirect to Dashboard Page
                redirect(base_url("admin/index.php"));
            } else {
                setMessage('error', 'Username or password is invalid!', 'back');
            }            
        } else {
            setMessage('error', 'Username or password is invalid!', 'back');
        }
    } else {
        setMessage('error', 'Please insert usrname and password!', 'back');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>e-Health | Login Administrator</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url("assets/vendor/fontawesome-free/css/all.min.css") ?>" rel="stylesheet"
        type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url("assets/css/sb-admin-2.min.css") ?>" rel="stylesheet">
    <link href="<?php echo base_url("assets/css/custom-style.css") ?>" rel="stylesheet">
    <link rel="shorcut icon" href="<?= base_url("assets/img/kesehatan.png") ?>">
    <link rel="stylesheet" href="<?= base_url("assets/vendor/sweetalert/sweetalert2.min.css") ?>">
    <link href="<?php echo base_url("assets/vendor/fontawesome-free/css/all.min.css") ?>" rel="stylesheet"
        type="text/css">
</head>
<style type="text/css">
.form-control {
    font-size: 1.2rem !important;
    padding: 0.75rem !important;
    height: auto !important;
    text-align: center;
    text-align-last: center;
}

.btn-user {
    font-size: 1.1rem !important;
    text-transform: uppercase;
    border-radius: 5px !important;
}

body {
    background: #C33764 !important;
    background: -webkit-linear-gradient(to bottom, #1D2671, #C33764) !important;
    background: -moz-linear-gradient(to bottom, #1D2671, #C33764) !important;
    background: -o-linear-gradient(to bottom, #1D2671, #C33764) !important;
    background: linear-gradient(to bottom, #1D2671, #C33764) !important;

}
</style>

<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center mt-5">
            <div class="col-xl-6 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center text-gray-900">
                                        <img src="<?= base_url("assets/img/kesehatan.png") ?>" alt=""
                                            style="width: 25%;padding-bottom: 10px;">
                                        <h1 class="h3">
                                            Administrator e-Health
                                        </h1>
                                        <h2 class="h6 mb-4">
                                            ~ Solusi Kesehatan Anda ~
                                        </h2>
                                        <?php
                                        // $hash2 = password_hash("steven", PASSWORD_DEFAULT);
                                        // echo $hash2;
                                        // $hash = '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq';
                                        // if (password_verify('rasmuslerdorf', $hash)) {
                                        //     echo 'Password is valid!';
                                        // } else {
                                        //     echo 'Invalid password.';
                                        // }
                                        ?>
                                    </div>
                                    <form class="user text-center" method="POST"
                                        action="<?php echo base_url("admin/login.php"); ?>">
                                        <div class="form-group">
                                            <input type="text" class="form-control text-center" placeholder="Username"
                                                name="username" required autocomplete="off">
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control text-center"
                                                placeholder="Password" name="password" required autocomplete="off">
                                        </div>

                                        <div class="form-group mb-4 mt-4">
                                            <button type="submit" class="btn btn-success btn-user btn-block mt-4"
                                                name="login">
                                                SIGN IN
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url("assets/vendor/jquery/jquery.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/vendor/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url("assets/vendor/jquery-easing/jquery.easing.min.js") ?>"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url("assets/js/sb-admin-2.min.js") ?>"></script>
    <script type="text/javascript" src="<?= base_url("assets/vendor/sweetalert/sweetalert2.all.min.js") ?>"></script>
    <?php if(checkMessage()): ?>
    <script type="text/javascript">
    Swal.fire({
        title: '<?= ucfirst(getMessage('type')); ?>',
        text: '<?= getMessage('text') ?>',
        icon: '<?= getMessage('type'); ?>',
        timer: 2000,
    });
    </script>
    <?php endif; ?>
    <?php setMessage('', '', ''); ?>
</body>

</html>