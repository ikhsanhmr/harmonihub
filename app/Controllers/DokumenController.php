<?php

namespace Controllers;

use Libraries\CSRF;
use Libraries\Database;

class DokumenController
{
    private $db;
    private $dbs;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->dbs = new Database();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function index()
    {
        $stmt = $this->db->prepare("SELECT * FROM dokumen ORDER BY created_at DESC;");
        $stmt->execute();
        $dokumens = $stmt->fetchAll();

        include 'view/dokumen/index.php';
    }

    public function create()
    {
        include 'view/dokumen/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari form
            $nama_dokumen = isset($_POST['nama_dokumen']) ? htmlspecialchars($_POST['nama_dokumen']) : null;
            $keterangan = isset($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : null;
            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            // Proses Upload File
            $file_dokumen = null;
            if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/dokumen/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = time() . '_' . basename($_FILES['file_dokumen']['name']);
                $uploadFilePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['file_dokumen']['tmp_name'], $uploadFilePath)) {
                    $file_dokumen = $fileName;
                }
            }

            $query = "INSERT INTO dokumen (nama_dokumen, file_dokumen, keterangan, created_at, updated_at) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$nama_dokumen, $file_dokumen, $keterangan, $createdAt, $updatedAt]);

            if ($success) {
                header('Location: index.php?page=dokumen&success=1');
            } else {
                header('Location: index.php?page=dokumen/create&gagal=1');
            }
        }
    }


    public function edit()
    {
        // Ambil data tema berdasarkan ID
        $id_dokumen = $_GET['id_dokumen'] ?? null;
        $dokumen = $this->dbs->fetch("SELECT * FROM dokumen WHERE id_dokumen = :id_dokumen", ['id_dokumen' => $id_dokumen]);
        if (!$dokumen) {
            die("Data tidak ditemukan.");
        }
        include 'view/dokumen/edit.php';
    }

    public function update()
    {
        // Validasi metode request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari POST
            $id_dokumen = $_POST['id_dokumen'] ?? null;
            $nama_dokumen = $_POST['nama_dokumen'] ?? null;
            $keterangan = $_POST['keterangan'] ?? null;

            // Validasi ID Dokumen
            if (!$id_dokumen) {
                header('Location: index.php?page=dokumen&error=Invalid ID');
                exit;
            }

            // Ambil dokumen lama dari database
            $sqlSelect = "SELECT file_dokumen FROM dokumen WHERE id_dokumen = :id_dokumen";
            $stmt = $this->db->prepare($sqlSelect);
            $stmt->execute(['id_dokumen' => $id_dokumen]);
            $dokumen = $stmt->fetch();

            if (!$dokumen) {
                header('Location: index.php?page=dokumen&error=Dokumen Not Found');
                exit;
            }

            // Inisialisasi variabel untuk file dokumen
            $file_dokumen = $dokumen['file_dokumen'];

            // Proses upload file jika ada file baru
            if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/dokumen/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Upload file baru
                $fileName = time() . '_' . basename($_FILES['file_dokumen']['name']);
                $uploadFilePath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['file_dokumen']['tmp_name'], $uploadFilePath)) {
                    // Hapus file lama jika ada
                    if ($file_dokumen && file_exists($uploadDir . $file_dokumen)) {
                        unlink($uploadDir . $file_dokumen);
                    }
                    $file_dokumen = $fileName;
                }
            }

            // Tanggal diperbarui
            $updatedAt = date('Y-m-d H:i:s');

            // Query untuk memperbarui dokumen
            $query = "UPDATE dokumen 
                  SET nama_dokumen = ?, keterangan = ?, file_dokumen = ?, updated_at = ? 
                  WHERE id_dokumen = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$nama_dokumen, $keterangan, $file_dokumen, $updatedAt, $id_dokumen]);

            // Redirect sesuai hasil
            if ($success) {
                header('Location: index.php?page=dokumen&success=1');
            } else {
                header('Location: index.php?page=dokumen/edit&id_dokumen=' . $id_dokumen . '&error=1');
            }
        } else {
            header('Location: index.php?page=dokumen&error=Invalid Request');
        }
    }


    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi CSRF Token
            if (!CSRF::validateToken($_POST['csrf_token'])) {
                header("Location: index.php?page=dokumen&error=1");
                exit;
            }

            // Ambil ID dokumen dari POST
            $id_dokumen = isset($_POST['id_dokumen']) ? (int) $_POST['id_dokumen'] : 0;

            if (!$id_dokumen) {
                header("Location: index.php?page=dokumen&error=Invalid ID");
                exit;
            }

            // Ambil data dokumen dari database untuk mendapatkan nama file
            $sqlSelect = "SELECT file_dokumen FROM dokumen WHERE id_dokumen = :id_dokumen";
            $stmt = $this->db->prepare($sqlSelect);
            $stmt->execute(['id_dokumen' => $id_dokumen]);
            $dokumen = $stmt->fetch();

            if ($dokumen && $dokumen['file_dokumen']) {
                $uploadDir = 'uploads/dokumen/';
                $filePath = $uploadDir . $dokumen['file_dokumen'];

                // Periksa dan hapus file
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Query Hapus Dokumen dari Database
            $sqlDelete = "DELETE FROM dokumen WHERE id_dokumen = :id_dokumen";
            $params = ['id_dokumen' => $id_dokumen];

            if ($this->dbs->delete($sqlDelete, $params)) {
                header("Location: index.php?page=dokumen&success=2");
                exit;
            } else {
                header("Location: index.php?page=dokumen&error=1");
                exit;
            }
        }
    }
}
