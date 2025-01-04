<?php

namespace Controllers\Ba;

use Libraries\Database;
use PDO;

class ApprovalsPerubahan
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function index()
    {
        $stmt = $this->db->prepare("SELECT ba.id, ba.name,ba.tanggal,ba.no_ba,ba.dokumen,ba.status ,u.name as unit_name FROM ba_perubahan ba join units u on u.id = ba.unit_id where ba.status = ?
        ORDER BY created_at DESC;");
        $stmt->execute(["pending"]);
        $bas = $stmt->fetchAll();

        
        $stmt = $this->db->prepare("SELECT ba.id, ba.name,ba.tanggal,ba.no_ba,ba.dokumen,ba.status ,u.name as unit_name FROM ba_perubahan ba join units u on u.id = ba.unit_id where ba.status = ?
        ORDER BY created_at DESC;");
        $stmt->execute(["pending"]);
        $baps = $stmt->fetchAll();
        include 'view/ba/approvals/baPerubahan.php';
    }

    public function terimaBaperubahan($id) {
        $stmt = $this->db->prepare("update ba_perubahan set status = ? where id = ?");
        $success = $stmt->execute(["approved",$id]);
        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'BA Perubahan Sukses Diterima!'];
            header('Location: index.php?page=approvals-perubahan');
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'BA Perubahan Gagal Diterima!'];
            header('Location: index.php?page=approvals-perubahan');
        }

    }
    

  
}
