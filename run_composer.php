<?php
// Pastikan script ini hanya dapat diakses dengan token rahasia untuk keamanan
$secret = 'harmonigo'; // Ganti dengan token rahasia

if (isset($_GET['token']) && $_GET['token'] === $secret) {
    $composerPath = '/usr/local/bin/composer'; // Sesuaikan dengan lokasi composer
    $command = $composerPath . ' install --no-dev --prefer-dist --no-progress 2>&1';
    
    // Menggunakan shell_exec
    $output = shell_exec($command);

    if ($output) {
        echo "Composer install berhasil dijalankan:\n";
        echo "<pre>" . $output . "</pre>";
    } else {
        echo "Terjadi kesalahan saat menjalankan Composer install.";
    }
} else {
    echo "Token tidak valid atau tidak ada.\n";
}
?>