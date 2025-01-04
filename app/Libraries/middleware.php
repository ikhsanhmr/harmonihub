<?php
require_once 'app/Libraries/Database.php';

function checkRole($role)
{
    // session_start();

    if (!isset($_SESSION['user_id'])) {
        echo "<script>
            alert('Anda belum login.');
            window.location.href = 'index.php?harmonihub=index';
        </script>";
        exit();
    }

    if (!isset($_SESSION['role_name'])) {
        $userId = $_SESSION['user_id'];
        $db = new Libraries\Database();

        $sql = "SELECT r.role_name FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.id = :userId";

        $params = ['userId' => $userId];
        $result = $db->fetch($sql, $params);

        if ($result) {
            $_SESSION['role_name'] = $result['role_name'];
        } else {
            echo "<script>
                alert('Anda tidak memiliki akses!');
                window.location.href = 'index.php?harmonihub=index';
            </script>";
            exit();
        }
    }

    $userRole = $_SESSION['role_name'];

    if ($userRole !== $role) {
        echo "<script>
            alert('Anda tidak memiliki akses!');
            window.location.href = 'index.php?harmonihub=index';
        </script>";
        exit();
    }
}
