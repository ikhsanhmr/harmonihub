<?php

require_once __DIR__ . '/vendor/autoload.php';

use Controllers\AnggotaSerikat;
use Controllers\AuthController;
use Controllers\Dashboard;
use Controllers\FrontendController;
use Controllers\InfoSiru;
use Controllers\LksBipartit\BaPembentukan;
use Controllers\LksBipartit\Jadwal;
use Controllers\LksBipartit\TemaController;
use Controllers\LksBipartit\Laporan;
use Controllers\PenilaianPdpController;
use Controllers\Serikats;
use Controllers\UnitController;
use Controllers\UserController;

$frontend = new FrontendController();
$auth = new AuthController();
$dashboard = new Dashboard();
$jadwal = new Jadwal();
$tema = new TemaController();
$infoSiruController = new InfoSiru();
$serikat = new Serikats();
$anggotaSerikat = new AnggotaSerikat();
$userController = new UserController();
$pdpController = new PenilaianPdpController();
$baController = new BaPembentukan();
$laporanController = new Laporan();
$unitController = new UnitController();

session_start();

if (isset($_GET["harmonihub"])) {
  $routeFe = $_GET['harmonihub'];
  switch ($routeFe) {
    case 'index':
      $frontend->index();
      break;
    case 'serikat':
      $frontend->serikat();
      break;
    case 'info-siru':
      $frontend->infoSiru();
      break;
  }
  exit();
}

if (!isset($_SESSION['user_id']) && $_GET['page'] !== 'login' && $_GET['page'] !== 'do-login') {
  // Redirect ke halaman login jika belum login
  header('Location: index.php?harmonihub=index');
  exit();
}



if (!isset($_GET['page'])) {
  $frontend->index();
} else {
  $page = $_GET['page'];
  switch ($page) {
    case 'home':
      $dashboard->home();
      break;
    case 'profile':
      $dashboard->profile();
      break;
    case 'jadwal':
      $jadwal->index();
      break;

    case 'tema':
      $tema->index();
      break;
    case 'tema/create':
      $tema->create();
      break;
    case 'tema/store':
      $tema->store();
      break;
    case 'tema/delete':
      $tema->delete();
      break;
    case 'tema=edit':
      if (isset($_GET['id'])) {
        $tema->edit();  // Panggil metode edit dengan ID
      } else {
        echo "<script>alert('ID Tema tidak ditemukan.');</script>";
        $tema->index();  // Kembali ke halaman index jika ID tidak ada
      }
      break;
    case 'tema/update':
      $tema->update();
    case 'login':
      $auth->loginForm();
      break;
    case 'do-login':
      $auth->loginProcess();
      break;
    case 'logout':
      $auth->logout();
      break;
    case 'user-list':
      $userController->index(); // Tampilkan daftar user
      break;
    case 'user-create':
      $userController->create(); // Tampilkan form tambah user
      break;
    case 'user-store':
      $userController->store(); // Proses tambah user
      break;
    case 'user-edit':
      $userController->edit($_GET['id']); // Tampilkan form edit user
      break;
    case 'user-update':
      $userController->update($_GET['id']); // Proses update user
      break;
    case 'user-delete':
      $userController->destroy($_GET['id']); // Hapus user
      break;
    case 'info-siru':
      $infoSiruController->index();
      break;
    case 'info-siru-create':
      $infoSiruController->create();
      break;
    case 'info-siru-store':
      $infoSiruController->store();
      break;
    case 'info-siru-destroy':
      $infoSiruController->destroy($_GET['id']);
      break;
    case 'info-siru-edit':
      $infoSiruController->edit($_GET['id']);
      break;
    case 'info-siru-update':
      $infoSiruController->update($_GET['id']);
      break;
    case 'serikat':
      $serikat->index();
      break;
    case 'serikat-create':
      $serikat->create();
      break;
    case 'serikat-store':
      $serikat->store();
      break;
    case 'serikat-edit':
      $serikat->edit($_GET['id']);
      break;
    case 'serikat-update':
      $serikat->update($_GET['id']);
      break;
    case 'serikat-destroy':
      $serikat->destroy($_GET['id']);
      break;
    case 'anggota-serikat':
      $anggotaSerikat->index();
      break;
    case 'anggota-serikat-store':
      $anggotaSerikat->store();
      break;
    case 'anggota-serikat-edit':
      $anggotaSerikat->edit($_GET['id']);
      break;
    case 'anggota-serikat-update':
      $anggotaSerikat->update($_GET['id']);
      break;
    case 'anggota-serikat-destroy':
      $anggotaSerikat->destroy($_GET['id']);
      break;
    case 'penilaian-pdp-list':
      $pdpController->index();
      break;
    case 'penilaian-pdp-create':
      $pdpController->create();
      break;
    case 'penilaian-pdp-store':
      $pdpController->store();
      break;
    case 'penilaian-pdp-edit':
      $pdpController->edit($_GET['id']);
      break;
    case 'penilaian-pdp-update':
      $pdpController->update($_GET['id']);
      break;
    case 'penilaian-pdp-delete':
      $pdpController->destroy($_GET['id']);
      break;
    case 'ba-pembentukan-list':
      $baController->index();
      break;
    case 'ba-pembentukan-create':
      $baController->create();
      break;
    case 'ba-pembentukan-store':
      $baController->store();
      break;
    case 'ba-pembentukan-edit':
      $baController->edit($_GET['id']);
      break;
    case 'ba-pembentukan-update':
      $baController->update($_GET['id']);
      break;
    case 'ba-pembentukan-delete':
      $baController->destroy($_GET['id']);
      break;
    case 'laporan-list':
      $laporanController->index();
      break;
    case 'laporan-create':
      $laporanController->create();
      break;
    case 'laporan-store':
      $laporanController->store();
      break;
    case 'laporan-edit':
      $laporanController->edit($_GET['id']);
      break;
    case 'laporan-update':
      $laporanController->update($_GET['id']);
      break;
    case 'laporan-delete':
      $laporanController->destroy($_GET['id']);
      break;
    case 'unit-list':
      $unitController->index();
      break;
    case 'unit-create':
      $unitController->create();
      break;
    case 'unit-store':
      $unitController->store();
      break;
    case 'unit-edit':
      $unitController->edit($_GET['id']);
      break;
    case 'unit-update':
      $unitController->update($_GET['id']);
      break;
    case 'unit-delete':
      $unitController->destroy($_GET['id']);
      break;
    default:
      // Aksi default untuk page yang tidak dikenali
      echo "<script>alert('Aksi tidak dikenal. Kembali ke halaman utama.');</script>";
      $dashboard->login();
      break;
  }
}
