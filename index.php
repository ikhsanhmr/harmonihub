<?php

require_once __DIR__ . '/vendor/autoload.php';

use Controllers\AuthController;
use Controllers\Dashboard;
use Controllers\InfoSiru;
use Controllers\LksBipartit\BaPembentukan;
use Controllers\LksBipartit\Jadwal;
use Controllers\LksBipartit\TemaController;
use Controllers\LksBipartit\Laporan;
use Controllers\LksBipartit\Penilain;
use Controllers\Serikat;
use Controllers\UserController;

$auth = new AuthController();
$dashboard = new Dashboard();
$bapembentukan = new BaPembentukan();
$jadwal = new Jadwal();
$tema = new TemaController();
$laporan = new Laporan();
$penilain = new Penilain();
$infoSiruController = new InfoSiru();
$serikatController = new Serikat();
$userController = new UserController();

// Periksa apakah pengguna sudah login
session_start();
if (!isset($_SESSION['user_id']) && $_GET['page'] !== 'login' && $_GET['page'] !== 'do-login') {
  // Redirect ke halaman login jika belum login
  header('Location: index.php?page=login');
  exit();
}

if (!isset($_GET['page'])) {
  $dashboard->login();
} else {
  $page = $_GET['page'];
  switch ($page) {
    case 'home':
      $dashboard->home();
      break;
    case 'profile':
      $dashboard->profile();
      break;
    case 'ba-pembentukan':
      $bapembentukan->index();
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
    case 'laporan':
      $laporan->index();
      break;
    case 'penilain':
      $penilain->index();
      break;
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
      $serikatController->index();
      break;
    case 'serikat-create':
      $serikatController->create();
      break;
    case 'serikat-store':
      $serikatController->store();
      break;
    case 'serikat-edit':
      $serikatController->edit($_GET['id']);
      break;
    case 'serikat-update':
      $serikatController->update($_GET['id']);
      break;
    case 'serikat-destroy':
      $serikatController->destroy($_GET['id']);
      break;
    default:
      // Aksi default untuk page yang tidak dikenali
      echo "<script>alert('Aksi tidak dikenal. Kembali ke halaman utama.');</script>";
      $dashboard->login();
      break;
  }
}
