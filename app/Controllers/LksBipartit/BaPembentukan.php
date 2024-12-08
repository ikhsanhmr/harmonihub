<?php

namespace Controllers\LksBipartit;

use Libraries\Database;

class BaPembentukan
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function index()
    {
        $stmt = $this->db->prepare("SELECT id, name, dokumen
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

            // Proses Upload File
            $dokumen = null;
            if (isset($_FILES['dokumen']) && $_FILES['dokumen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/dokumen/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = time() . '_' . basename($_FILES['dokumen']['name']);
                $uploadFilePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['dokumen']['tmp_name'], $uploadFilePath)) {
                    $dokumen = $fileName;
                }
            }

            $query = "INSERT INTO ba_lks_bipartit (name, dokumen, created_at, updated_at) 
                  VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$name, $dokumen, $createdAt, $updatedAt]);

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

            // Proses Upload File Baru (jika ada)
            $dokumen = null;
            if (isset($_FILES['dokumen']) && $_FILES['dokumen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/dokumen/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = time() . '_' . basename($_FILES['dokumen']['name']);
                $uploadFilePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['dokumen']['tmp_name'], $uploadFilePath)) {
                    $dokumen = $fileName;

                    // Hapus dokumen lama (jika ada)
                    $stmt = $this->db->prepare("SELECT dokumen FROM ba_lks_bipartit WHERE id = ?");
                    $stmt->execute([$id]);
                    $oldDokumen = $stmt->fetchColumn();
                    if ($oldDokumen && file_exists($uploadDir . $oldDokumen)) {
                        unlink($uploadDir . $oldDokumen);
                    }
                }
            }

            // Update Database
            if ($dokumen) {
                $query = "UPDATE ba_lks_bipartit SET name = ?, dokumen = ?, updated_at = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $success = $stmt->execute([$name, $dokumen, $updatedAt, $id]);
            } else {
                $query = "UPDATE ba_lks_bipartit SET name = ?, updated_at = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $success = $stmt->execute([$name, $updatedAt, $id]);
            }

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'BA Pembentukan updated successfully!'];
                header('Location: index.php?page=ba-pembentukan-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update BA Pembentukan!'];
                header('Location: index.php?page=ba-pembentukan-edit&id=' . $id);
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
