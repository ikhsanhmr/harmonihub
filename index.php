<?php

require_once __DIR__ . '/vendor/autoload.php';

use Controllers\Albums;
use Controllers\Dashboard;
use Controllers\LksBipartit\BaPembentukan;
use Controllers\LksBipartit\Jadwal;
use Controllers\LksBipartit\TemaController;
use Controllers\LksBipartit\Laporan;
use Controllers\LksBipartit\Penilain;

$dashboard = new Dashboard();
$bapembentukan = new BaPembentukan();
$jadwal = new Jadwal();
$tema = new TemaController();
$laporan = new Laporan();
$penilain = new Penilain();

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
    default:
      // Aksi default untuk page yang tidak dikenali
      echo "<script>alert('Aksi tidak dikenal. Kembali ke halaman utama.');</script>";
      $dashboard->login();
      break;
  }
}
