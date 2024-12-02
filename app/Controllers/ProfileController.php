<?php

namespace Controllers;

use Libraries\Database;
use Libraries\CSRF;

class ProfileController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        // Pastikan session memiliki user_id
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login"); // Redirect ke halaman login
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Query SQL dengan alias tabel
        $sql = "SELECT 
                    u.id AS user_id, 
                    u.name, 
                    u.username, 
                    u.email, 
                    u.profile_picture, 
                    u.created_at, 
                    u.updated_at, 
                    r.role_name AS role_name, 
                    s.name AS serikat_name 
                FROM users u
                JOIN roles r ON u.role_id = r.id
                JOIN serikat s ON u.serikat_id = s.id
                WHERE u.id = ?";

        // Gunakan prepared statement
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);

        // Ambil data user
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Jika user tidak ditemukan
        if (!$user) {
            echo "User tidak ditemukan!";
            return;
        }

        // Masukkan data ke view
        include 'view/profile.php';
    }

    // public function update()
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id']; // Ambil ID dari sesi pengguna yang sedang login
            $name = $_POST['name'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $updatedAt = date('Y-m-d H:i:s');

            // Ambil data pengguna saat ini untuk mendapatkan gambar lama
            $query = "SELECT profile_picture FROM users WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id]);
            $currentUser = $stmt->fetch();

            $oldProfilePicture = $currentUser['profile_picture']; // Gambar lama

            // Proses upload file gambar profil baru
            $profile_picture = $oldProfilePicture; // Default ke gambar lama jika tidak ada gambar baru
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                // Tentukan jalur baru
                $profile_picture = 'uploads/' . $_FILES['profile_picture']['name'];

                // Pindahkan file baru
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture)) {
                    // Hapus file gambar lama jika ada
                    if (!empty($oldProfilePicture) && file_exists($oldProfilePicture)) {
                        unlink($oldProfilePicture); // Hapus file lama
                    }
                }
            }

            // Update data pengguna
            $query = "UPDATE users SET name = ?, username = ?, password = ?, email = ?, profile_picture = ?, updated_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$name, $username, $password, $email, $profile_picture, $updatedAt, $user_id]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Profil berhasil diperbarui!'];
                header('Location: index.php?page=profile');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal memperbarui profil.'];
                header('Location: index.php?page=profile');
            }
        }
    }


}
