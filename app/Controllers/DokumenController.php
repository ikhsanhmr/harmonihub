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
        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

        $sql = "SELECT * FROM dokumen_hi WHERE 1";

        if ($kategori) {
            $sql .= " AND kategori = :kategori";
        }

        if ($tahun) {
            $sql .= " AND YEAR(tanggal) = :tahun";
        }

        $sql .= " ORDER BY tanggal DESC";

        $stmt = $this->db->prepare($sql);

        if ($kategori) {
            $stmt->bindParam(':kategori', $kategori, \PDO::PARAM_INT);
        }
        if ($tahun) {
            $stmt->bindParam(':tahun', $tahun, \PDO::PARAM_INT);
        }

        $stmt->execute();

        $dokumens = $stmt->fetchAll();

        include 'view/dokumen_hi/index.php';
    }


    public function create()
    {
        include 'view/dokumen_hi/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari form
            $nama_dokumen = isset($_POST['nama_dokumen']) ? htmlspecialchars($_POST['nama_dokumen']) : null;
            $link_gdrive = isset($_POST['link_gdrive']) ? htmlspecialchars($_POST['link_gdrive']) : null;
            $kategori = isset($_POST['kategori']) ? htmlspecialchars($_POST['kategori']) : null;
            $tanggal = isset($_POST['tanggal']) ? htmlspecialchars($_POST['tanggal']) : null;
            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            // Proses Upload File
            // $file_dokumen = null;
            // if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
            //     $uploadDir = 'uploads/dokumen/';
            //     if (!is_dir($uploadDir)) {
            //         mkdir($uploadDir, 0777, true);
            //     }

            //     $fileName = time() . '_' . basename($_FILES['file_dokumen']['name']);
            //     $uploadFilePath = $uploadDir . $fileName;

            //     if (move_uploaded_file($_FILES['file_dokumen']['tmp_name'], $uploadFilePath)) {
            //         $file_dokumen = $fileName;
            //     }
            // }

            $query = "INSERT INTO dokumen_hi (nama_dokumen, link_gdrive, kategori, tanggal, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$nama_dokumen, $link_gdrive, $kategori, $tanggal, $createdAt, $updatedAt]);

            if ($success) {
                header('Location: index.php?page=dokumen_hi&success=1');
            } else {
                header('Location: index.php?page=dokumen_hi/create&gagal=1');
            }
        }
    }


    public function edit()
    {
        // Ambil data tema berdasarkan ID
        $id = $_GET['id'] ?? null;
        $dokumen = $this->dbs->fetch("SELECT * FROM dokumen_hi WHERE id = :id", ['id' => $id]);
        if (!$dokumen) {
            die("Data tidak ditemukan.");
        }
        include 'view/dokumen_hi/edit.php';
    }

    public function update()
    {
        // Validasi metode request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari POST
            $id = $_POST['id'] ?? null;
            $nama_dokumen = $_POST['nama_dokumen'] ?? null;
            $link_gdrive = $_POST['link_gdrive'] ?? null;
            $kategori = $_POST['kategori'] ?? null;
            $tanggal = $_POST['tanggal'] ?? null;

            // Validasi ID Dokumen
            if (!$id) {
                header('Location: index.php?page=dokumen_hi&error=Invalid ID');
                exit;
            }

            // Ambil dokumen lama dari database
            // $sqlSelect = "SELECT file_dokumen FROM dokumen WHERE id = :id";
            // $stmt = $this->db->prepare($sqlSelect);
            // $stmt->execute(['id' => $id]);
            // $dokumen = $stmt->fetch();

            // if (!$dokumen) {
            //     header('Location: index.php?page=dokumen_hi&error=Dokumen Not Found');
            //     exit;
            // }

            // Inisialisasi variabel untuk file dokumen
            // $file_dokumen = $dokumen['file_dokumen'];

            // Proses upload file jika ada file baru
            // if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
            //     $uploadDir = 'uploads/dokumen/';
            //     if (!is_dir($uploadDir)) {
            //         mkdir($uploadDir, 0777, true);
            //     }

            //     // Upload file baru
            //     $fileName = time() . '_' . basename($_FILES['file_dokumen']['name']);
            //     $uploadFilePath = $uploadDir . $fileName;
            //     if (move_uploaded_file($_FILES['file_dokumen']['tmp_name'], $uploadFilePath)) {
            //         // Hapus file lama jika ada
            //         if ($file_dokumen && file_exists($uploadDir . $file_dokumen)) {
            //             unlink($uploadDir . $file_dokumen);
            //         }
            //         $file_dokumen = $fileName;
            //     }
            // }

            // Tanggal diperbarui
            $updatedAt = date('Y-m-d H:i:s');

            // Query untuk memperbarui dokumen
            $query = "UPDATE dokumen_hi 
                  SET nama_dokumen = ?, link_gdrive = ?, kategori = ?, tanggal = ?, updated_at = ? 
                  WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$nama_dokumen, $link_gdrive, $kategori, $tanggal, $updatedAt, $id]);

            // Redirect sesuai hasil
            if ($success) {
                header('Location: index.php?page=dokumen_hi&success=1');
            } else {
                header('Location: index.php?page=dokumen_hi/edit&id=' . $id . '&error=1');
            }
        } else {
            header('Location: index.php?page=dokumen_hi&error=Invalid Request');
        }
    }


    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi CSRF Token
            if (!CSRF::validateToken($_POST['csrf_token'])) {
                header("Location: index.php?page=dokumen_hi&error=1");
                exit;
            }

            // Ambil ID dokumen dari POST
            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

            if (!$id) {
                header("Location: index.php?page=dokumen_hi&error=Invalid ID");
                exit;
            }

            // Ambil data dokumen dari database untuk mendapatkan nama file
            // $sqlSelect = "SELECT file_dokumen FROM dokumen WHERE id = :id";
            // $stmt = $this->db->prepare($sqlSelect);
            // $stmt->execute(['id' => $id]);
            // $dokumen = $stmt->fetch();

            // if ($dokumen && $dokumen['file_dokumen']) {
            //     $uploadDir = 'uploads/dokumen/';
            //     $filePath = $uploadDir . $dokumen['file_dokumen'];

            //     // Periksa dan hapus file
            //     if (file_exists($filePath)) {
            //         unlink($filePath);
            //     }
            // }

            // Query Hapus Dokumen dari Database
            $sqlDelete = "DELETE FROM dokumen_hi WHERE id = :id";
            $params = ['id' => $id];

            if ($this->dbs->delete($sqlDelete, $params)) {
                header("Location: index.php?page=dokumen_hi&success=2");
                exit;
            } else {
                header("Location: index.php?page=dokumen_hi&error=1");
                exit;
            }
        }
    }
}
