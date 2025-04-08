<?php
namespace Config;

class Config
{
    public static string $base_url;
    public static string $db_host = 'localhost';
    public static string $db_user = 'root';
    public static string $db_pass = '';
    public static string $db_name = 'harmoni_db';

    // Jalankan otomatis saat class pertama kali dipanggil
    public static function init()
    {
        $host = $_SERVER['HTTP_HOST'];

        if ((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') && $_SERVER['SERVER_PORT'] != 443) {
            $protocol = "http://";
            $host .= "/harmonihub"; // tambahkan path jika tidak HTTPS
        } else {
            $protocol = "https://";
        }

        self::$base_url = $protocol . $host;
    }
}

// Inisialisasi konfigurasi (wajib dipanggil sebelum akses base_url)
\Config\Config::init();