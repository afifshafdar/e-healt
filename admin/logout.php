<?php
if(file_exists("../core/init.php")) {
    require_once("../core/init.php");
} else {
    die("Main configuration file is empty!");
}

if(!cekSessionUser()) {
    redirect(base_url('admin/login.php'));
}

if (isset($_POST['logout']) && strtolower($_POST['logout']) == 'true') {
    resetSessionUser();
    redirect(base_url('admin/login.php'));
}
?>