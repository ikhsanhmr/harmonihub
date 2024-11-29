<?php

namespace Controllers;

use Libraries\Database;
use PDO;

class AuthController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function loginForm()
    {
        require_once 'view/login.php';
    }

    public function loginProcess()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $query = "SELECT users.id, users.username, users.password, roles.role_name 
                      FROM users 
                      JOIN roles ON users.role_id = roles.id 
                      WHERE users.username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_name'] = $user['role_name'];

                echo "<script>
                    alert('Login berhasil!');
                    window.location.href = 'index.php?page=home';
                  </script>";
                exit();
            } else {
                echo "<script>
                    alert('Password salah!');
                    window.location.href = 'index.php?page=login';
                  </script>";
            }
        } else {
            echo "<script>
                alert('Username tidak ditemukan!');
                window.location.href = 'index.php?page=login';
              </script>";
        }
    }

    public function logout()
    {
        // session_start();
        session_destroy();
        echo "<script>
            alert('Anda berhasil logout!');
            window.location.href = 'index.php?page=login';
          </script>";
        exit();
    }
}
