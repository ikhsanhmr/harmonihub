<?php

require_once __DIR__ . '/vendor/autoload.php';

use Controllers\Albums;
use Controllers\AuthController;
use Controllers\Dashboard;
use Controllers\LksBipartit\BaPembentukan;
use Controllers\LksBipartit\Jadwal;
use Controllers\LksBipartit\Tema;
use Controllers\LksBipartit\Laporan;
use Controllers\LksBipartit\Penilain;
use Controllers\UserController;

$auth = new AuthController();
$dashboard = new Dashboard();
$bapembentukan = new BaPembentukan();
$jadwal = new Jadwal();
$tema = new Tema();
$laporan = new Laporan();
$penilain = new Penilain();

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
    default:
      // Aksi default untuk page yang tidak dikenali
      echo "<script>alert('Aksi tidak dikenal. Kembali ke halaman utama.');</script>";
      $dashboard->login();
      break;
  }
}
