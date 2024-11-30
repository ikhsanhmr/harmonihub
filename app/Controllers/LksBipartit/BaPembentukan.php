<?php

namespace Controllers\LksBipartit;

use Libraries\Database;
class BaPembentukan{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }
    
    public function index(){
        $stmt = $this->db->prepare("SELECT id, name
                                    FROM ba_lks_bipartit
                                    ORDER BY created_at DESC;");
        $stmt->execute();
        $bas = $stmt->fetchAll();

        include 'view/lks-bipartit/ba-pembentukan/index.php';
    }

    public function create()
    {
        include 'view/lks-bipartit/ba-pembentukan/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            $query = "INSERT INTO ba_lks_bipartit (name, created_at, updated_at) 
                      VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$name, $createdAt, $updatedAt]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'BA Pembentukan created successfully!'];

                header('Location: index.php?page=ba-pembentukan-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to create BA Pembentukan!'];

                header('Location: index.php?page=ba-pembentukan-create');
            }
        }
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ba_lks_bipartit WHERE id = ?");
        $stmt->execute([$id]);
        $ba = $stmt->fetch();

        include 'view/lks-bipartit/ba-pembentukan/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE ba_lks_bipartit SET name = ?, updated_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$name, $updatedAt, $id]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'BA Pembentukan updated successfully!'];

                header('Location: index.php?page=ba-pembentukan-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update BA Pembentukan!'];

                header('Location: index.php?page=ba-pembentukan-edit');
            }
        }
    }

    public function destroy($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ba_lks_bipartit WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'BA Pembentukan deleted successfully!'];

            header('Location: index.php?page=ba-pembentukan-list');
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to delete BA Pembentukan!'];

            header('Location: index.php?page=ba-pembentukan-list');
        }
    }
}