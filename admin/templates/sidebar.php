<?php
$index = "";
$bengkel = "";
$provinsi = "";
$kabupaten = "";
$user = "";

if($page == "") {
    $index = "active";
} elseif($page == "bengkel") {
    $bengkel = "active";
} elseif($page == "provinsi") {
    $provinsi = "active";
} elseif($page == "kabupaten") {
    $kabupaten = "active";
} elseif($page == "user-management" || $page == "user") {
    $user = "active";
}

?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
        href="<?= base_url('admin/index.php'); ?>">
        <div class="sidebar-brand-icon rotate-n-15">

        </div>
        <div class="sidebar-brand-text mx-3" style="font-size: 26px;text-shadow: 3px 2px 2px #5c555e;">E-Health</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= $index; ?>">
        <a class="nav-link" href="<?php echo base_url("admin/index.php") ?>">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-2">
    <!-- Heading -->
    <div class="sidebar-heading">
        Master
    </div>
    <li class="nav-item <?php echo $bengkel; ?>">
        <a class="nav-link" href="<?php echo linkTo("bengkel", [], false) ?>">
            <i class="fas fa-fw fa-medkit"></i>
            <span>Data Layanan</span>
        </a>
    </li>
    <hr class="sidebar-divider my-2">
    <div class="sidebar-heading">
        Wilayah
    </div>
    <li class="nav-item <?php echo $provinsi; ?>">
        <a class="nav-link" href="<?php echo linkTo("provinsi", [], false) ?>">
            <i class="fas fa-fw fa-map"></i>
            <span>Data Provinsi</span>
        </a>
    </li>
    <li class="nav-item <?php echo $kabupaten; ?>">
        <a class="nav-link" href="<?php echo linkTo("kabupaten", [], false) ?>">
            <i class="fas fa-fw fa-map"></i>
            <span>Data Kabupaten</span>
        </a>
    </li>
    <hr class="sidebar-divider my-2">
    <div class="sidebar-heading">
        User
    </div>
    <li class="nav-item <?php echo $user; ?>">
        <a class="nav-link" href="<?php echo linkTo("user", [], false) ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>Manajemen User</span>
        </a>
    </li>
    <hr class="sidebar-divider my-2">
    <li class="nav-item">
        <a class="nav-link" href="javascript:void(0)" onclick="askForLogout()">
            <i class="fas fa-fw fa-power-off"></i>
            <span>Logout</span>
        </a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
    <form id="logout-form" action="<?= base_url("admin/logout.php") ?>" method="POST">
        <input type="hidden" name="logout" value="true">
    </form>

</ul>
<!-- End of Sidebar -->