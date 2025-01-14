<?php
// Pastikan script ini hanya dapat diakses dengan token rahasia untuk keamanan
$secret = 'harmonigo'; // Ganti dengan token rahasia

if (isset($_GET['token']) && $_GET['token'] === $secret) {
  // Cek apakah fungsi exec() tersedia
  if (function_exists('exec')) {
    echo "Fungsi exec() tersedia.\n";
  } else {
    echo "Fungsi exec() tidak tersedia.\n";
  }

  // Cek apakah fungsi shell_exec() tersedia
  if (function_exists('shell_exec')) {
    echo "Fungsi shell_exec() tersedia.\n";
  } else {
    echo "Fungsi shell_exec() tidak tersedia.\n";
  }

  // Cek apakah fungsi passthru() tersedia
  if (function_exists('passthru')) {
    echo "Fungsi passthru() tersedia.\n";
  } else {
    echo "Fungsi passthru() tidak tersedia.\n";
  }

  // Cek apakah fungsi system() tersedia
  if (function_exists('system')) {
    echo "Fungsi system() tersedia.\n";
  } else {
    echo "Fungsi system() tidak tersedia.\n";
  }

  // Cek apakah fungsi proc_open() tersedia
  if (function_exists('proc_open')) {
    echo "Fungsi proc_open() tersedia.\n";
  } else {
    echo "Fungsi proc_open() tidak tersedia.\n";
  }

  // $composerPath = '/usr/local/bin/composer'; // Sesuaikan dengan lokasi composer
  // $command = $composerPath . ' install --no-dev --prefer-dist --no-progress 2>&1';

  // // Menggunakan shell_exec
  // $output = passthru($command);

  // if ($output) {
  //     echo "Composer install berhasil dijalankan:\n";
  //     echo "<pre>" . $output . "</pre>";
  // } else {
  //     echo "Terjadi kesalahan saat menjalankan Composer install.";
  // }
} else {
  echo "Token tidak valid atau tidak ada.\n";
}
?>