<?php
// Ambil nilai dari parameter 'page' di URL, atau gunakan 'home' sebagai default jika tidak ada
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="index.php?page=home">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?page=user-list">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">User</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?page=info-siru">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Info SIRU</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?page=serikat">
                <i class="icon-contract menu-icon"></i>
                <span class="menu-title">Serikat</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#lks_bipartit" aria-expanded="false" aria-controls="ui-basic">
                <i class="icon-layout menu-icon"></i>
                <span class="menu-title">LKS Bipartit</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="lks_bipartit">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link" href="index.php?page=ba-pembentukan-list">BA Pembentukan</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" href="index.php?page=jadwal">Jadwal</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" href="index.php?page=tema">Tema</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" href="index.php?page=laporan">Laporan</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?page=penilaian-pdp-list">
                <i class="icon-grid-2 menu-icon"></i>
                <span class="menu-title">Penilaian PDP Lain</span>
            </a>
        </li>
    </ul>
</nav>