<?php

namespace Controllers;

use Libraries\Database;

class UserController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $stmt = $this->db->prepare("SELECT u.id, u.name, u.username, u.email, u.profile_picture, u.created_at, u.updated_at, r.role_name as role_name, s.name as serikat_name 
                                    FROM users u 
                                    JOIN roles r ON u.role_id = r.id
                                    JOIN serikat s ON u.serikat_id = s.id
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

        include 'view/user/user-create.php';
    }

    // Proses tambah user
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $role_id = $_POST['role_id'];
            $serikat_id = $_POST['serikat_id'];
            $name = $_POST['name'];
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $email = $_POST['email'];

            // Proses upload file gambar profil
            $profile_picture = null;
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                $profile_picture = 'uploads/' . $_FILES['profile_picture']['name'];
                move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
            }

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            // Query Insert
            $query = "INSERT INTO users (role_id, serikat_id, name, username, password, email, profile_picture, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$role_id, $serikat_id, $name, $username, $password, $email, $profile_picture, $createdAt, $updatedAt]);

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
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        $stmt = $this->db->prepare("SELECT id, role_name FROM roles");
        $stmt->execute();
        $roles = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM serikat");
        $stmt->execute();
        $serikats = $stmt->fetchAll();

        include 'view/user/user-edit.php';
    }

    // Proses update user
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $role_id = $_POST['role_id'];
            $serikat_id = $_POST['serikat_id'];
            $name = $_POST['name'];
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $email = $_POST['email'];

            // Proses upload file gambar profil
            $profile_picture = null;
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
                $profile_picture = 'uploads/' . $_FILES['profile_picture']['name'];
                move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
            }

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE users SET role_id = ?, serikat_id = ?, name = ?, username = ?, password = ?, email = ?, profile_picture = ?, updated_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$role_id, $serikat_id, $name, $username, $password, $email, $profile_picture, $updatedAt, $id]);

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
