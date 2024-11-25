<?php

namespace Controllers\LksBipartit;
use Libraries\Database;
use Libraries\CSRF;

class TemaController
{
    protected $db;
    public function __construct()
    {
        $this->db = new Database();
    }


    public function index()
    {
        // Mengambil data tema dari database
        $temas = $this->db->query("SELECT * FROM tema_lks_bipartit");
        // Cek apakah ada parameter untuk menampilkan SweetAlert
        $successMessage = isset($_GET['success']) ? $_GET['success'] : null;
        require_once 'view/lks-bipartit/tema/index.php';
    }

    public function create()
    {
        require_once 'view/lks-bipartit/tema/create.php';
    }

    // Fungsi untuk memproses (POST) data tema
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Memvalidasi token CSRF
            if (!CSRF::validateToken($_POST['csrf_token'])) {
                die("Token CSRF tidak valid.");
            }
            // Ambil data yang dikirimkan dari form
            $namaTema = $_POST['namaTema'];

            // Menyiapkan query SQL untuk insert data
            $sql = "INSERT INTO tema_lks_bipartit (namaTema) VALUES (:namaTema)";
            $params = ['namaTema' => $namaTema];

            // Menambah data ke database
            if ($this->db->insert($sql, $params)) {
                // Jika berhasil, redirect ke halaman index
                header("Location: index.php?page=tema&success=1"); // Anda bisa menyesuaikan URL ini
                exit;
            } else {
                // Jika gagal, tampilkan pesan error
                echo "Gagal menambah data";
            }
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::validateToken($_POST['csrf_token'])) {
                header("Location: index.php?page=tema&error=1");
                exit;
            }

            $id_tema = $_POST['id_tema'];
            $sql = "DELETE FROM tema_lks_bipartit WHERE id = :id_tema";
            $params = ['id_tema' => $id_tema];

            if ($this->db->delete($sql, $params)) {
                header("Location: index.php?page=tema&success=2");
                exit;
            } else {
                header("Location: index.php?page=tema&error=1");
                exit;
            }
        }
    }

    public function edit()
    {
        // Ambil data tema berdasarkan ID
        $id = $_GET['id'] ?? null;

        if (!$id) {
            die("ID tidak valid.");
        }

        $tema = $this->db->fetch("SELECT * FROM tema_lks_bipartit WHERE id = :id", ['id' => $id]);

        if (!$tema) {
            die("Data tidak ditemukan.");
        }

        // Tampilkan view edit dengan data tema
        require_once 'view/lks-bipartit/tema/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!CSRF::validateToken($_POST['csrf_token'])) {
                header("Location: index.php?page=tema&error=1");
                exit;
            }

            $id = $_POST['id'];
            $namaTema = $_POST['namaTema'];

            // Query untuk update data
            $sql = "UPDATE tema_lks_bipartit SET namaTema = :namaTema WHERE id = :id";
            $params = [
                'namaTema' => $namaTema,
                'id' => $id
            ];

            if ($this->db->update($sql, $params)) {
                header("Location: index.php?page=tema&success=3");
                exit;
            } else {
                header("Location: index.php?page=tema&error=1");
                exit;
            }
        }
    }
}
