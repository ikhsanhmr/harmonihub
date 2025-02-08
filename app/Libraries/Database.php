<?php

namespace Libraries;

use Dotenv\Dotenv;
use PDO;
use PDOException;

class Database
{
    private static $instance = NULL; // Singleton instance
    private $conn; // Connection property

    public function __construct()
    {
        $this->conn = self::getInstance(); // Inisialisasi koneksi
    }

    // Singleton pattern untuk mendapatkan instance PDO
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            try {
                $pdo_options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => true, // Tambahkan koneksi persistent untuk performa
                ];
                // cek apakah file berjalan di https (web productions)
                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                    // Memuat file .env dari direktori root server
                    $dotenv = Dotenv::createImmutable('/home/hart1449');  // Ganti dengan path ke root direktori Anda
                    $dotenv->load();
                    $dbPassword = $_ENV['DB_PASS'];

                    self::$instance = new PDO('mysql:host=localhost;dbname=hart1449_harmoni', 'hart1449_harmoni', $dbPassword, $pdo_options);
                } else {
                    self::$instance = new PDO('mysql:host=localhost;dbname=db_harmoni', 'root', '', $pdo_options);
                }
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    // Menjalankan query SELECT tanpa parameter
    public function query($sql)
    {
        try {
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(); // Mengambil semua hasil dalam bentuk array asosiatif
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    // Menjalankan query SELECT dengan parameter (prepared statement)
    public function queryWithParams($sql, $params)
    {
        try {
            $stmt = $this->conn->prepare($sql); // Menyiapkan query dengan parameter
            $stmt->execute($params); // Menjalankan query
            return $stmt->fetchAll(); // Mengambil hasilnya
        } catch (PDOException $e) {
            die("Query with parameters failed: " . $e->getMessage());
        }
    }

    // Menjalankan query untuk mengambil satu baris data (single record)
    public function fetch($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql); // Menyiapkan query
            $stmt->execute($params); // Menjalankan query
            return $stmt->fetch(); // Mengambil satu hasil (first row)
        } catch (PDOException $e) {
            die("Fetch failed: " . $e->getMessage());
        }
    }

    // Menambah data ke tabel (INSERT)
    public function insert($sql, $params)
    {
        try {
            $stmt = $this->conn->prepare($sql); // Menyiapkan query INSERT
            return $stmt->execute($params); // Menjalankan query dengan parameter
        } catch (PDOException $e) {
            die("Insert failed: " . $e->getMessage());
        }
    }

    // Mengupdate data di tabel (UPDATE)
    public function update($sql, $params)
    {
        try {
            $stmt = $this->conn->prepare($sql); // Menyiapkan query UPDATE
            return $stmt->execute($params); // Menjalankan query dengan parameter
        } catch (PDOException $e) {
            die("Update failed: " . $e->getMessage());
        }
    }

    // Menghapus data dari tabel (DELETE)
    public function delete($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql); // Menyiapkan query DELETE
            return $stmt->execute($params); // Menjalankan query
        } catch (PDOException $e) {
            die("Delete failed: " . $e->getMessage());
        }
    }

    // Menjalankan transaksi
    public function beginTransaction()
    {
        $this->conn->beginTransaction();
    }

    public function commit()
    {
        $this->conn->commit();
    }

    public function rollBack()
    {
        $this->conn->rollBack();
    }
}

