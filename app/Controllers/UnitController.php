<?php

namespace Controllers;

use Libraries\Database;

class UnitController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }
    
    public function index(){
        $stmt = $this->db->prepare("SELECT id, name
                                    FROM units
                                    ORDER BY createdAt DESC;");
        $stmt->execute();
        $units = $stmt->fetchAll();

        include 'view/unit/index.php';
    }

    public function create()
    {
        include 'view/unit/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            $query = "INSERT INTO units (name, createdAt, updateAt) 
                      VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$name, $createdAt, $updatedAt]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Unit created successfully!'];

                header('Location: index.php?page=unit-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to create Unit!'];

                header('Location: index.php?page=unit-create');
            }
        }
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM units WHERE id = ?");
        $stmt->execute([$id]);
        $unit = $stmt->fetch();

        include 'view/unit/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE units SET name = ?, updateAt = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$name, $updatedAt, $id]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Unit updated successfully!'];

                header('Location: index.php?page=unit-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update Unit!'];

                header('Location: index.php?page=unit-edit');
            }
        }
    }

    public function destroy($id)
    {
        $stmt = $this->db->prepare("DELETE FROM units WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Unit deleted successfully!'];

            header('Location: index.php?page=unit-list');
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to delete Unit!'];

            header('Location: index.php?page=unit-list');
        }
    }
}