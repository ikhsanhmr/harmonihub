<?php

namespace Controllers\Ba;

use Libraries\Database;
use PDO;

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
        $start_date = $_GET['start'] ?? null;
        $end_date = $_GET['end'] ?? null;
        $unit = $_GET['unit'] ?? null;
        $params = [];
        if(isset($_SESSION["role_name"]) && $_SESSION["role_name"] == "unit"){
            $params['name'] = $_SESSION["tim"];
            $sql = 'SELECT ba.id, ba.name,ba.tanggal,ba.no_ba,ba.dokumen,ba.status ,u.name as unit_name FROM ba_pembentukan ba join units u on u.id = ba.unit_id where u.name = :name
            ';

            if ($start_date && $end_date) {
                if(strpos(strtolower($sql) , "where") !==false){
                    $sql .= " and ba.tanggal BETWEEN :start_date AND :end_date";
                }else{
                    $sql .= " where ba.tanggal BETWEEN :start_date AND :end_date";
                }
                $params['start_date'] = $start_date;
                $params['end_date'] = $end_date;
            }

            $sql .= " ORDER BY ba.created_at DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
        }else{
            $sql = 'SELECT ba.id, ba.name,ba.tanggal,ba.no_ba,ba.dokumen,ba.status ,u.name as unit_name FROM ba_pembentukan ba join units u on u.id = ba.unit_id where ba.status = :status ';
            $params['status'] = "approved";
            if ($unit !== null) {
                $sql .= "and u.id= :unit";
                $params['unit'] = $unit;
            }
             if ($start_date && $end_date) {
                if(strpos(strtolower($sql) , "where") !==false){
                    $sql .= " and ba.tanggal BETWEEN :start_date AND :end_date";
                }else{
                    $sql .= " where ba.tanggal BETWEEN :start_date AND :end_date";
                }
                $params['start_date'] = $start_date;
                $params['end_date'] = $end_date;
            }

            $sql .= " ORDER BY ba.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
        }
        $bas = $stmt->fetchAll();
        
        $stmt = $this->db->prepare("select id , name from units");
        $stmt->execute();
        $units = $stmt->fetchAll();
        include 'view/ba/ba-pembentukan/index.php';
    }

    public function create()
    {
        $stmt = $this->db->prepare("select * from units");
        $stmt->execute();
        $units = $stmt->fetchAll();
        include 'view/ba/ba-pembentukan/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $no_ba = $_POST['no_ba'];
            $tanggal = $_POST['tanggal'];
            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');


           
            // Proses Upload File
            $dokumen = null;
            if (isset($_FILES['dokumen']) && $_FILES['dokumen']['error'] === UPLOAD_ERR_OK) {
                $dir = 'uploads/dokumen/';
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                $fileName = time() . '_' . basename($_FILES['dokumen']['name']);
                $uploadFilePath = $dir . $fileName;

                if (move_uploaded_file($_FILES['dokumen']['tmp_name'], $uploadFilePath)) {
                    $dokumen = $fileName;
                }
            }
            // jika role admin langsung aprove
            if(isset($_SESSION["role_name"]) && $_SESSION["role_name"] == "admin"){
                $status =  "approved"; 
            }else{
                $status =  "pending"; 
            }
            // jika role unit otomatis dari sessi jika admin pilih unit
            if(isset($_SESSION["role_name"]) && $_SESSION["role_name"] == "unit"){
                $stmt = $this->db->prepare("SELECT * FROM units WHERE name = ?");
                $stmt->execute([$_SESSION["tim"]]);
                $dataId = $stmt->fetch(PDO::FETCH_ASSOC); 
                if ($dataId) {
                    $unitId = $dataId["id"]; 
                } 
            }else{
                $unitId= $_POST["unit_id"];
            }
            $query = "INSERT INTO ba_pembentukan (unit_id,name,tanggal, dokumen,status, created_at, updated_at,no_ba) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unitId,$name,$tanggal, $dokumen,$status,  $createdAt, $updatedAt, $no_ba]);

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
        $stmt = $this->db->prepare("SELECT ba.* , u.name as unit_name FROM ba_pembentukan ba join units u on u.id = ba.unit_id WHERE ba.id = ?");
        $stmt->execute([$id]);
        $ba = $stmt->fetch();

        $stmt = $this->db->prepare("select * from units");
        $stmt->execute();
        $units = $stmt->fetchAll();
        
        include 'view/ba/ba-pembentukan/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $no_ba = $_POST['no_ba'];
            $tanggal = $_POST['tanggal'];
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
                    $stmt = $this->db->prepare("SELECT dokumen FROM ba_pembentukan WHERE id = ?");
                    $stmt->execute([$id]);
                    $oldDokumen = $stmt->fetchColumn();
                    if ($oldDokumen && file_exists($uploadDir . $oldDokumen)) {
                        unlink($uploadDir . $oldDokumen);
                    }
                }
            }
              // jika role admin langsung aprove
              if(isset($_SESSION["role_name"]) && $_SESSION["role_name"] == "admin"){
                $status =  "approved"; 
                }else{
                    $status =  "pending"; 
                }
              // jika role unit otomatis dari sessi jika admin pilih unit
              if(isset($_SESSION["tim"]) && $_SESSION["tim"] !== "superAdmin"){
                $stmt = $this->db->prepare("SELECT * FROM units WHERE name = ?");
                $stmt->execute([$_SESSION["tim"]]);
                $dataId = $stmt->fetch(PDO::FETCH_ASSOC); 
                if ($dataId) {
                    $unitId = $dataId["id"]; 
                } 
                }else{
                    $unitId= $_POST["unit_id"];
                }
            // Update Database
            if ($dokumen) {
                $query = "UPDATE ba_pembentukan SET unit_id =?, tanggal =? ,name = ?, dokumen = ?,status =?, updated_at = ?, no_ba = ?  WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $success = $stmt->execute([$unitId,$tanggal,$name, $dokumen,$status, $updatedAt,$no_ba, $id]);
            } else {
                $query = "UPDATE ba_pembentukan SET unit_id =?, tanggal =? ,name = ?,status =?, updated_at = ?, no_ba = ?  WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $success = $stmt->execute([$unitId,$tanggal,$name, $status, $updatedAt,$no_ba, $id]);
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
        $stmt = $this->db->prepare("DELETE FROM ba_pembentukan WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'BA Pembentukan deleted successfully!'];

            if (isset($_SERVER['HTTP_REFERER'])) {
                // Redirect ke halaman sebelumnya
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                // Redirect ke halaman default jika HTTP_REFERER tidak tersedia
                header('Location: index.php?page=ba-pembentukan-list');
            }
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to delete BA Pembentukan!'];

            if (isset($_SERVER['HTTP_REFERER'])) {
                // Redirect ke halaman sebelumnya
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                // Redirect ke halaman default jika HTTP_REFERER tidak tersedia
                header('Location: index.php?page=ba-pembentukan-list');
            }
        }
    }
}
