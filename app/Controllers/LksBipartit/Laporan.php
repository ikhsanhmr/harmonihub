<?php

namespace Controllers\LksBipartit;

use Libraries\Database;
class Laporan
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function index()
    {
        $stmt = $this->db->prepare("SELECT l.id, u.name as unit_name, l.tanggal, l.topik_bahasan, l.latar_belakang, l.rekomendasi, l.tanggal_tindak_lanjut, l.uraian_tindak_lanjut
                                    FROM laporan_lks_bipartit l
                                    JOIN units u ON l.unit_id = u.id
                                    ORDER BY created_at DESC;");
        $stmt->execute();
        $laporans = $stmt->fetchAll();

        include 'view/lks-bipartit/laporan/index.php';
    }

    public function create()
    {
        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM anggota_serikats");
        $stmt->execute();
        $anggotas = $stmt->fetchAll();

        include 'view/lks-bipartit/laporan/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $unit_id = $_POST['unit_id'];
            $tanggal = $_POST['tanggal'];
            $topik_bahasan = $_POST['topik_bahasan'];
            $latar_belakang = $_POST['latar_belakang'];
            $rekomendasi = $_POST['rekomendasi'];
            $tanggal_tindak_lanjut = $_POST['tanggal_tindak_lanjut'];
            $uraian_tindak_lanjut = $_POST['uraian_tindak_lanjut'];

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            $query = "INSERT INTO laporan_lks_bipartit (unit_id, tanggal, topik_bahasan, latar_belakang, rekomendasi, tanggal_tindak_lanjut, uraian_tindak_lanjut, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unit_id, $tanggal, $topik_bahasan, $latar_belakang, $rekomendasi, $tanggal_tindak_lanjut, $uraian_tindak_lanjut, $createdAt, $updatedAt]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Laporan LKS Bipartit created successfully!'];

                header('Location: index.php?page=laporan-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to create Laporan LKS Bipartit!'];

                header('Location: index.php?page=laporan-create');
            }
        }
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM laporan_lks_bipartit WHERE id = ?");
        $stmt->execute([$id]);
        $laporan = $stmt->fetch();

        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        include 'view/lks-bipartit/laporan/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $unit_id = $_POST['unit_id'];
            $tanggal = $_POST['tanggal'];
            $topik_bahasan = $_POST['topik_bahasan'];
            $latar_belakang = $_POST['latar_belakang'];
            $rekomendasi = $_POST['rekomendasi'];
            $tanggal_tindak_lanjut = $_POST['tanggal_tindak_lanjut'];
            $uraian_tindak_lanjut = $_POST['uraian_tindak_lanjut'];

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE laporan_lks_bipartit SET unit_id = ?, tanggal = ?, topik_bahasan = ?, latar_belakang = ?, rekomendasi = ?, tanggal_tindak_lanjut = ?, uraian_tindak_lanjut = ?, updated_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unit_id, $tanggal, $topik_bahasan, $latar_belakang, $rekomendasi, $tanggal_tindak_lanjut, $uraian_tindak_lanjut, $updatedAt, $id]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Laporan LKS Bipartit updated successfully!'];

                header('Location: index.php?page=laporan-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update Laporan LKS Bipartit!'];

                header('Location: index.php?page=laporan-create');
            }
        }
    }

    public function destroy($id)
    {
        $stmt = $this->db->prepare("DELETE FROM laporan_lks_bipartit WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Laporan LKS Bipartit deleted successfully!'];

            header('Location: index.php?page=laporan-list');
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to delete Laporan LKS Bipartit!'];

            header('Location: index.php?page=laporan-list');
        }
    }
}
