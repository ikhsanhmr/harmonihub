<?php
// File: app/Libraries/CSRF.php

namespace Libraries;

class CSRF
{
    // Menghasilkan dan menyimpan token CSRF di sesi
    public static function generateToken()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Cek apakah sudah ada token, jika belum buat token baru
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Token acak 32 byte
        }

        return $_SESSION['csrf_token'];
    }

    // Validasi token CSRF
    public static function validateToken($token)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Pastikan token yang diberikan sesuai dengan token yang disimpan di sesi
        return $token && $token === $_SESSION['csrf_token'];
    }
}
