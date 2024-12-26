<?php
// Ambil nilai dari parameter 'page' di URL, atau gunakan 'home' sebagai default jika tidak ada
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
$rolename = $_SESSION['role_name'];
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item <?= $current_page == 'home' ? 'active' : '' ?>">
            <a class="nav-link" href="index.php?page=home">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <?php
        if ($rolename == 'admin') { ?>
            <li class="nav-item <?= in_array($current_page, ['unit-list', 'serikat', 'user-list']) ? 'active' : '' ?>">
                <a class="nav-link" data-toggle="collapse" href="#master" aria-expanded="false" aria-controls="ui-basic">
                    <i class="icon-contract menu-icon"></i>
                    <span class="menu-title">Master Data</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse <?= in_array($current_page, ['unit-list', 'serikat', 'user-list']) ? 'show' : '' ?>" id="master">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item <?= $current_page == 'unit-list' ? 'active' : '' ?>">
                            <a class="nav-link" href="index.php?page=unit-list">Unit</a>
                        </li>
                        <li class="nav-item <?= $current_page == 'serikat' ? 'active' : '' ?>">
                            <a class="nav-link" href="index.php?page=serikat">Serikat</a>
                        </li>
                        <li class="nav-item <?= $current_page == 'user-list' ? 'active' : '' ?>">
                            <a class="nav-link" href="index.php?page=user-list">User</a>
                        </li>
                    </ul>
                </div>
            </li>
        <?php
        }
        ?>
        <?php
        if ($rolename == 'admin' || $rolename == 'user') { ?>
            <li class="nav-item <?= $current_page == 'info-siru' ? 'active' : '' ?>">
                <a class="nav-link" href="index.php?page=info-siru">
                    <i class="icon-paper menu-icon"></i>
                    <span class="menu-title">Info SIRU</span>
                </a>
            </li>
        <?php
        }
        ?>
        <?php
        if ($rolename == 'admin') { ?>
            <li class="nav-item <?= $current_page == 'anggota-serikat' ? 'active' : '' ?>">
                <a class="nav-link" href="index.php?page=anggota-serikat">
                    <i class="icon-map menu-icon"></i>
                    <span class="menu-title">Serikat</span>
                </a>
            </li>
            <li class="nav-item <?= in_array($current_page, ['ba-pembentukan-list', 'jadwal', 'tema', 'laporan-list']) ? 'active' : '' ?>">
                <a class="nav-link" data-toggle="collapse" href="#lks_bipartit" aria-expanded="false" aria-controls="ui-basic">
                    <i class="icon-layout menu-icon"></i>
                    <span class="menu-title">LKS Bipartit</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse <?= in_array($current_page, ['ba-pembentukan-list', 'jadwal', 'tema', 'tim', 'laporan-list']) ? 'show' : '' ?>" id="lks_bipartit">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item <?= $current_page == 'ba-pembentukan-list' ? 'active' : '' ?>">
                            <a class="nav-link" href="index.php?page=ba-pembentukan-list">BA Pembentukan</a>
                        </li>
                        <li class="nav-item <?= $current_page == 'jadwal' ? 'active' : '' ?>">
                            <a class="nav-link" href="index.php?page=jadwal">Jadwal</a>
                        </li>
                        <li class="nav-item <?= $current_page == 'tema' ? 'active' : '' ?>">
                            <a class="nav-link" href="index.php?page=tema">Tema</a>
                        </li>
                        <li class="nav-item <?= $current_page == 'tim' ? 'active' : '' ?>">
                            <a class="nav-link" href="index.php?page=tim">Tim</a>
                        </li>
                        <li class="nav-item <?= $current_page == 'laporan-list' ? 'active' : '' ?>">
                            <a class="nav-link" href="index.php?page=laporan-list">Laporan</a>
                        </li>
                        <li class="nav-item <?= $current_page == 'monitor' ? 'active' : '' ?>">
                            <a class="nav-link" href="index.php?page=monitor">Monitoring</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item <?= $current_page == 'penilaian-pdp-list' ? 'active' : '' ?>">
                <a class="nav-link" href="index.php?page=penilaian-pdp-list">
                    <i class="icon-grid-2 menu-icon"></i>
                    <span class="menu-title">Penilaian PDP Lain</span>
                </a>
            </li>
            <li class="nav-item <?= $current_page == 'dokumen' ? 'active' : '' ?>">
                <a class="nav-link" href="index.php?page=dokumen">
                    <i class="mdi mdi-file-document menu-icon"></i>
                    <span class="menu-title">Dokumen</span>
                </a>
            </li>
        <?php
        }
        ?>
    </ul>
</nav>