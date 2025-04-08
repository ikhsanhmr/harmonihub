<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Libraries/middleware.php';

use Controllers\Serikat\Dsp;
use Controllers\Serikat\AnggotaSerikat;
use Controllers\AuthController;
use Controllers\Ba\Approvals;
use Controllers\Ba\ApprovalsPembentukan;
use Controllers\Ba\ApprovalsPerubahan;
use Controllers\Dashboard;
use Controllers\DokumenController;
use Controllers\FrontendController;
use Controllers\InfoSiru;
use Controllers\Ba\BaPembentukan;
use Controllers\Ba\BaPerubahan;
use Controllers\LksBipartit\Jadwal;
use Controllers\LksBipartit\Monitor;
use Controllers\LksBipartit\TemaController;
use Controllers\LksBipartit\Laporan;
use Controllers\LksBipartit\Tim;
use Controllers\PenilaianPdpController;
use Controllers\ProfileController;
use Controllers\Serikats;
use Controllers\UnitController;
use Controllers\UserController;

// echo "<script>console.log('" . Config\Config::$base_url . "')</script>";

$frontend = new FrontendController();
$auth = new AuthController();
$dashboard = new Dashboard();
$jadwal = new Jadwal();
$tema = new TemaController();
$infoSiruController = new InfoSiru();
$serikat = new Serikats();
$anggotaSerikat = new AnggotaSerikat();
$dsp = new Dsp();
$userController = new UserController();
$pdpController = new PenilaianPdpController();
$baPembentukanController = new BaPembentukan();
$baPerubahanController = new BaPerubahan();
$approvalsPembentukan = new ApprovalsPembentukan();
$approvalsPerubahan = new ApprovalsPerubahan();
$laporanController = new Laporan();
$unitController = new UnitController();
$profileContoller = new ProfileController();
$timController = new Tim();
$monitor = new Monitor();
$dokumen = new DokumenController();
session_start();

if (isset($_GET["harmonihub"])) {
  $routeFe = $_GET['harmonihub'];
  switch ($routeFe) {
    case 'index':
      $frontend->index();
      break;
    case 'flyer':
      $frontend->flyers();
      break;
    case 'video':
      $frontend->videos();
      break;
  }
  exit();
}

if (!isset($_SESSION['user_id']) && $_GET['page'] !== 'login' && $_GET['page'] !== 'do-login') {
  // Redirect ke halaman login jika belum login
  header('Location: index.php?harmonihub=index');
  exit();
}

if (isset($_SESSION['user_id']) && isset($_GET['page']) && $_GET['page'] === 'laporan-list') {
  // Tetap di halaman laporan-list jika filter aktif
  $laporanController->index();
  exit();
}
$rolePages = [
  'admin' => [
    'jadwal',
    'fectch/jadwal',
    'jadwal/store',
    'jadwal/edit',
    'jadwal/delete',
    'tema',
    'tema/create',
    'tema/store',
    'tema/delete',
    'tema=edit',
    'tema/update',
    'user-list',
    'user-create',
    'user-store',
    'user-edit',
    'user-update',
    'user-delete',
    'info-siru-create',
    'info-siru-store',
    'info-siru-destroy',
    'info-siru-edit',
    'info-siru-update',
    'serikat',
    'serikat-create',
    'serikat-store',
    'serikat-edit',
    'serikat-update',
    'serikat-destroy',
    'anggota-serikat',
    'anggota-serikat-store',
    'anggota-serikat-edit',
    'anggota-serikat-update',
    'anggota-serikat-destroy',
    'dsp',
    'penilaian-pdp-list',
    'penilaian-pdp-create',
    'penilaian-pdp-store',
    'penilaian-pdp-edit',
    'penilaian-pdp-update',
    'penilaian-pdp-delete',
    'ba-pembentukan-list',
    'ba-pembentukan-create',
    'ba-pembentukan-store',
    'ba-pembentukan-edit',
    'ba-pembentukan-update',
    'ba-pembentukan-delete',
    'ba-perubahan',
    'ba-perubahan-create',
    'ba-perubahan-store',
    'ba-perubahan-edit',
    'ba-perubahan-update',
    'ba-perubahan-delete',
    'approvals-pembentukan',
    'terima-ba-pembentukan',
    'approvals-perubahan',
    'terima-ba-perubahan',
    'laporan-list',
    'laporan-create',
    'laporan-store',
    'laporan-edit',
    'laporan-update',
    'laporan-delete',
    'unit-list',
    'unit-create',
    'unit-store',
    'unit-edit',
    'unit-update',
    'unit-delete',
    'tim',
    'tim/create',
    'monitor',
    'monitor-create',
    'monitor-store',
    'dokumen',
    'dokumen/create',
    'dokumen/store',
    'dokumen/delete',
  ],
  'unit' => [
    'jadwal',
    'fectch/jadwal',
    'jadwal/store',
    'jadwal/edit',
    'jadwal/delete',
    'tema',
    'tema/create',
    'tema/store',
    'tema/delete',
    'tema=edit',
    'tema/update',
    'penilaian-pdp-list',
    'penilaian-pdp-create',
    'penilaian-pdp-store',
    'penilaian-pdp-edit',
    'penilaian-pdp-update',
    'penilaian-pdp-delete',
    'ba-pembentukan-list',
    'ba-pembentukan-create',
    'ba-pembentukan-store',
    'ba-pembentukan-edit',
    'ba-pembentukan-update',
    'ba-pembentukan-delete',
    'ba-perubahan',
    'ba-perubahan-create',
    'ba-perubahan-store',
    'ba-perubahan-edit',
    'ba-perubahan-update',
    'ba-perubahan-delete',
    'laporan-list',
    'laporan-create',
    'laporan-store',
    'laporan-edit',
    'laporan-update',
    'laporan-delete',
    'tim',
    'tim/create',
    'monitor',
    'monitor-create',
    'monitor-store',
  ],
];

// Middleware cek role berdasarkan halaman
if (isset($_GET['page'])) {
  $page = $_GET['page'];
  if (isset($_SESSION["role_name"])) {
    $role = $_SESSION["role_name"];
  } else {
    $role = null;
  }
  if (in_array($page, $rolePages['admin'])) {
    checkRole($role);
  }
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
      $profileContoller->index();
      break;
    case 'profile/update':
      // Ambil ID dari sesi, bukan dari parameter URL
      if (isset($_SESSION['user_id'])) {
        $profileContoller->update(); // Tidak perlu kirim $id, gunakan $_SESSION['user_id']
      } else {
        // Redirect ke halaman login jika pengguna tidak login
        header('Location: index.php?page=login');
        exit;
      }
    case 'jadwal':
      $jadwal->index();
      break;
    case 'fectch/jadwal':
      $jadwal->fetchJadwal();
      break;
    case 'jadwal/create':
      $jadwal->create();
      break;
    case 'jadwal/store':
      $jadwal->store();
      break;
    case 'jadwal/data':
      $jadwal->data();
      break;
    case 'jadwal/edit':
      $jadwal->edit($_GET['id']);
      break;
    case 'jadwal/update':
      $jadwal->update($_GET['id']); // Proses update user
      break;
    case 'jadwal/delete':
      $jadwal->delete();
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
    case 'anggota-serikat-create':
      $anggotaSerikat->create();
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
    case 'anggota-serikat-pdf':
      $anggotaSerikat->pdf($_GET["id_serikat"]);
      break;
    case 'anggota-serikat-excell':
      $anggotaSerikat->pdf($_GET["id_serikat"]);
      break;
    case 'excel-to-anggota-serikat':
      $anggotaSerikat->excel();
      break;
    case 'dsp':
      $dsp->index();
      break;
    case 'dsp-create':
      $dsp->create();
      break;
    case 'dsp-store':
      $dsp->store();
      break;
    case 'dsp-edit':
      $dsp->edit($_GET['id']);
      break;
    case 'dsp-update':
      $dsp->update($_GET['id']);
      break;
    case 'dsp-destroy':
      $dsp->destroy($_GET['id']);
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
    case 'penilaian-pdp-exportpdf';
      $pdpController->exportToPdf();
      break;
    case 'penilaian-pdp-importexcell';
      $pdpController->importToExcel();
      break;
    case 'ba-pembentukan-list':
      $baPembentukanController->index();
      break;
    case 'ba-pembentukan-create':
      $baPembentukanController->create();
      break;
    case 'ba-pembentukan-store':
      $baPembentukanController->store();
      break;
    case 'ba-pembentukan-edit':
      $baPembentukanController->edit($_GET['id']);
      break;
    case 'ba-pembentukan-update':
      $baPembentukanController->update($_GET['id']);
      break;
    case 'ba-pembentukan-delete':
      $baPembentukanController->destroy($_GET['id']);
      break;
    case 'ba-perubahan-list':
      $baPerubahanController->index();
      break;
    case 'ba-perubahan-create':
      $baPerubahanController->create();
      break;
    case 'ba-perubahan-store':
      $baPerubahanController->store();
      break;
    case 'ba-perubahan-edit':
      $baPerubahanController->edit($_GET['id']);
      break;
    case 'ba-perubahan-update':
      $baPerubahanController->update($_GET['id']);
      break;
    case 'ba-perubahan-delete':
      $baPerubahanController->destroy($_GET['id']);
      break;
    case 'approvals-pembentukan':
      $approvalsPembentukan->index();
      break;
    case 'terima-ba-pembentukan':
      $approvalsPembentukan->terimaBaPembentukan($_GET["id"]);
      break;
    case 'approvals-perubahan':
      $approvalsPerubahan->index();
      break;
    case 'terima-ba-perubahan':
      $approvalsPerubahan->terimaBaperubahan($_GET["id"]);
      break;

    // LKS Bipartit - Laporan
    case 'laporan/importPdf':
      $laporanController->importPdf();
      break;
    case 'laporan-list':
      $laporanController->index();
      break;
    case 'laporan-export-pdf':
      $laporanController->exportToPdf();
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
    case 'tim':
      $timController->index();
      break;
    case 'tim/create':
      $timController->create();
      break;
    case 'tim/store':
      $timController->store();
      break;
    case 'tim/edit':
      $timController->edit($_GET['id']);
      break;
    case 'tim/update':
      $timController->update($_GET['id']); // Proses update user
      break;
    case 'tim/delete':
      $timController->delete();
      break;
    case 'monitor':
      $monitor->index();
      break;
    case 'monitor-create':
      $monitor->create();
      break;
    case 'monitor-store':
      $monitor->store();
      break;
    case 'monitor-jadwal-create':
      $monitor->jadwalCreate($_GET['id']);
      break;
    case 'monitor-jadwal-store':
      $monitor->jadwalStore($_GET['id']);
      break;
    case 'monitor-destroy':
      $monitor->destroy($_GET["id"]);
      break;
    case 'dokumen':
      $dokumen->index();
      break;
    case 'dokumen/create':
      $dokumen->create();
      break;
    case 'dokumen/store':
      $dokumen->store();
      break;
    case 'dokumen=edit':
      if (isset($_GET['id_dokumen'])) {
        $dokumen->edit();  // Panggil metode edit dengan ID
      }
      break;
    //membuat case untuk dokuemn update
    case 'dokumen/update':
      $dokumen->update();
      break;
    case 'dokumen/delete':
      $dokumen->destroy();
      break;
    default:
      // Aksi default untuk page yang tidak dikenali
      echo "<script>alert('Aksi tidak dikenal. Kembali ke halaman utama.');</script>";
      $dashboard->login();
      break;
  }
}
