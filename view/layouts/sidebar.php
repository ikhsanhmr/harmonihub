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
            <a class="nav-link" href="index.php?page=user">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">User</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?page=album">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Album</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?page=info_siru">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Info SIRU</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?page=lks_bipartit">
                <i class="icon-bar-graph menu-icon"></i>
                <span class="menu-title">LKS Bipartit</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?page=penilaian_pdp">
                <i class="icon-grid-2 menu-icon"></i>
                <span class="menu-title">Penilaian PDP Lain</span>
            </a>
        </li>
    </ul>
</nav>