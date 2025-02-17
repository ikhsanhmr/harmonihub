<?php

namespace Controllers;

use Helpers\Validation;
use Libraries\Database;

class UserController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function index()
    {
        $stmt = $this->db->prepare("SELECT u.id, u.name, u.username,u.tim, u.email, u.profile_picture, u.created_at, u.updated_at, r.role_name as role_name
                                    FROM users u 
                                    JOIN roles r ON u.role_id = r.id
                                    ORDER BY u.created_at DESC;");
        $stmt->execute();
        $users = $stmt->fetchAll();

        include 'view/user/user-list.php';
    }

    public function create()
    {
        $stmt = $this->db->prepare("SELECT id, role_name FROM roles");
        $stmt->execute();
        $roles = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM serikat");
        $stmt->execute();
        $serikats = $stmt->fetchAll();

        
        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        include 'view/user/user-create.php';
    }

    // Proses tambah user
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $role_id = $_POST['role_id'];
            $tim = $_POST['tim'];
            $name = $_POST['name'];
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $email = $_POST['email'];

            // upload profile, boleh kosong ""
            $profile_picture = null;
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                $profile_picture = Validation::ValidatorFile($_FILES["profile_picture"],"uploads/users/","index.php?page=user-create");
            }

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            // Query Insert
            $query = "INSERT INTO users (role_id, tim, name, username, password, email, profile_picture, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$role_id, $tim, $name, $username, $password, $email, $profile_picture, $createdAt, $updatedAt]);

            // Simpan pesan status ke session
            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'User created successfully!'];

                header('Location: index.php?page=user-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to create user!'];

                header('Location: index.php?page=user-create');
            }
        }
    }

    // Menampilkan form edit user
    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT users.* ,r.id as role_id, r.role_name as role_name FROM users join roles r on r.id = users.role_id WHERE users.id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        $stmt = $this->db->prepare("SELECT id, role_name FROM roles");
        $stmt->execute();
        $roles = $stmt->fetchAll();
        
        $stmt = $this->db->prepare("SELECT id, name FROM serikat");
        $stmt->execute();
        $serikats = $stmt->fetchAll();

        
        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        include 'view/user/user-edit.php';
    }

    // Proses update user
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $role_id = $_POST['role_id'];
            $tim = $_POST['tim'];
            $name = $_POST['name'];
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $email = $_POST['email'];

            // Ambil data pengguna saat ini untuk mendapatkan gambar lama
            $query = "SELECT profile_picture FROM users WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $currentUser = $stmt->fetch();

            // gunakan file lama jika file baru tidak ada
            $oldProfilePicture = $currentUser['profile_picture']; // Gambar lama

            // Proses upload file gambar profil, skip jika kosong
            $profile_picture = $oldProfilePicture; // Default ke gambar lama jika tidak ada gambar baru
            if (!empty($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                
                // hapus file yg lama jika ada di storage
                if (file_exists($oldProfilePicture)) {
                    unlink($oldProfilePicture); // Hapus file lama
                }

                $profile_picture = Validation::ValidatorFile($_FILES["profile_picture"],"uploads/users/","index.php?page=profile");
            }

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE users SET role_id = ?, tim = ?, name = ?, username = ?, password = ?, email = ?, profile_picture = ?, updated_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$role_id, $tim, $name, $username, $password, $email, $profile_picture, $updatedAt, $id]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'User updated successfully!'];

                header('Location: index.php?page=user-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update user!'];

                header('Location: index.php?page=user-create');
            }
        }
    }

    // Menghapus user
    public function destroy($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'User deleted successfully!'];

            header('Location: index.php?page=user-list');
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to delete user!'];

            header('Location: index.php?page=user-list');
        }
    }
}
