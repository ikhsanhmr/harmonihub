<?php

require_once __DIR__ . '/vendor/autoload.php';

use Controllers\Albums;
use Controllers\Dashboard;

$dashboard = new Dashboard();

if (!isset($_GET['page'])) {
  $dashboard->login();
} else {
  $page = $_GET['page'];
  switch ($page) {
    case 'home':
      $dashboard->home();
      break;
    default:
      // Aksi default untuk page yang tidak dikenali
      echo "<script>alert('Aksi tidak dikenal. Kembali ke halaman utama.');</script>";
      $dashboard->login();
      break;
  }
}
