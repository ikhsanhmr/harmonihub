<?php
// Pastikan script ini hanya dapat diakses dengan token rahasia untuk keamanan
$secret = 'harmonigo'; // Ganti dengan token rahasia

if (isset($_GET['token']) && $_GET['token'] === $secret) {
  // Cek apakah fungsi exec() tersedia
 // Fungsi untuk memeriksa apakah fungsi shell tersedia dan dapat dieksekusi
function check_shell_function($function_name) {
  if (function_exists($function_name)) {
      echo "Fungsi '$function_name' tersedia.\n<br>";
      return true;
  } else {
      echo "Fungsi '$function_name' TIDAK tersedia.\n<br>";
      return false;
  }
}

// Cek apakah berbagai fungsi shell tersedia
check_shell_function('exec');
check_shell_function('shell_exec');
check_shell_function('passthru');
check_shell_function('system');
check_shell_function('proc_open');
check_shell_function('popen');
check_shell_function('pcntl_exec');
check_shell_function('putenv');

  // $composerPath = '/usr/local/bin/composer'; // Sesuaikan dengan lokasi composer
  // $command = $composerPath . ' install --no-dev --prefer-dist --no-progress 2>&1';
  // Tentukan perintah yang ingin dijalankan
  $command = '/usr/local/bin/composer';
  $arguments = ['install', '--no-dev', '--prefer-dist', '--no-progress'];

  $output = false;
  // Gantikan proses PHP dengan perintah composer
  if(check_shell_function('pcntl_exec')) {
    echo "pcntl_exec() tersedia. Menjalankan perintah...\n";
    // Menjalankan perintah menggunakan pcntl_exec
    pcntl_exec('/bin/echo', ['Hello from pcntl_exec!']);

    echo "Jika ini muncul, pcntl_exec() tidak berhasil dijalankan.\n";
  }

} else {
  echo "Token tidak valid atau tidak ada.\n";
}
?>