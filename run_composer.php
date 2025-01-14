<?php
// Pastikan script ini hanya dapat diakses dengan token rahasia untuk keamanan
$secret = 'harmonigo'; // Ganti dengan token rahasia
if (!isset($_GET['token']) || $_GET['token'] !== $secret) {
    http_response_code(403);
    die('Unauthorized access');
}

// Jalankan composer install
$output = [];
$exitCode = 0;
exec('composer install --no-dev --prefer-dist --no-progress 2>&1', $output, $exitCode);

// Tampilkan hasil
echo "Exit Code: $exitCode\n";
echo "Output:\n";
echo implode("\n", $output);
?>
