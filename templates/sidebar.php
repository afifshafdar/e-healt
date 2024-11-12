<?php
$index = "";
$daftar_bengkel = "";
$peta_bengkel = "";
$nearby = "";

$page = getFrom('page');
$action = getFrom('action');

if($page == "") {
    $index = "active";
} else if($page == "bengkel") {
    if($action == "list") {
        $daftar_bengkel = "active";
    } elseif ($action == "location") {
        $peta_bengkel = "active";
    }
} elseif ($page == "nearby") {
    $nearby = "active";
}

?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('index.php'); ?>">
        <div class="sidebar-brand-text mx-3" style="font-size: 26px;text-shadow: 3px 2px 2px #5c555e;">e-Health</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?=$index?>">
        <a class="nav-link" href="<?php echo base_url("index.php"); ?>">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item <?= $daftar_bengkel; ?>">
        <a class="nav-link" href="<?php echo linkTo('bengkel', ['action' => 'list']); ?>">
            <i class="fas fa-fw fa-medkit"></i>
            <span>Daftar Layanan</span>
        </a>
    </li>
    <li class="nav-item <?= $peta_bengkel; ?>">
        <a class="nav-link" href="<?php echo linkTo('bengkel', ['action' => 'location']); ?>">
            <i class="fas fa-fw fa-map"></i>
            <span>Peta Lokasi Layanan</span>
        </a>
    </li>
    <li class="nav-item <?= $nearby; ?>">
        <a class="nav-link" href="<?php echo linkTo('nearby', ['action' => 'position']); ?>">
            <i class="fas fa-fw fa-map-marker"></i>
            <span>Nearby</span>
        </a>
    </li>



    <!-- Divider -->
    <hr class="sidebar-divider my-2">
    <div class="sidebar-heading">
        Akses
    </div>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url("admin") ?>" target="_blank"
            class="ml-3 btn btn-sm btn-warning border-0">
            <i class="fa fa-user-tie"></i>
            <span>Login Admin</span>
        </a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->