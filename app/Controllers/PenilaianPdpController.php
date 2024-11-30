<?php

namespace Controllers;

use Libraries\Database;

class PenilaianPdpController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function index()
    {
        $stmt = $this->db->prepare("SELECT p.id, p.peran, p.kpi, p.uraian, p.hasil_verifikasi, p.nilai, p.tanggal, u.name as user_name, u.nip as user_nip, t.name as unit_name 
                                    FROM penilaian_pdp p 
                                    JOIN anggota_serikats u ON p.anggota_serikat_id = u.id
                                    JOIN units t ON p.unit_id = t.id
                                    ORDER BY p.created_at DESC;");
        $stmt->execute();
        $pdps = $stmt->fetchAll();

        include 'view/penilaian-pdp/penilaian-pdp-list.php';
    }

    public function create()
    {
        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM anggota_serikats");
        $stmt->execute();
        $anggotas = $stmt->fetchAll();

        include 'view/penilaian-pdp/penilaian-pdp-create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $unit_id = $_POST['unit_id'];
            $user_id = $_SESSION['user_id'];
            $anggota_serikat_id = $_POST['anggota_serikat_id'];
            $peran = $_POST['peran'];
            $kpi = $_POST['kpi'];
            $uraian = $_POST['uraian'];
            $hasil_verifikasi = $_POST['hasil_verifikasi'];
            $nilai = $_POST['nilai'];
            $tanggal = $_POST['tanggal'];

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            $query = "INSERT INTO penilaian_pdp (unit_id, user_id, anggota_serikat_id, peran, kpi, uraian, hasil_verifikasi, nilai, tanggal, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unit_id, $user_id, $anggota_serikat_id, $peran, $kpi, $uraian, $hasil_verifikasi, $nilai, $tanggal, $createdAt, $updatedAt]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Penilaian PDP created successfully!'];

                header('Location: index.php?page=penilaian-pdp-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to create Penilaian PDP!'];

                header('Location: index.php?page=penilaian-pdp-create');
            }
        }
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM penilaian_pdp WHERE id = ?");
        $stmt->execute([$id]);
        $pdp = $stmt->fetch();

        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM anggota_serikats");
        $stmt->execute();
        $anggotas = $stmt->fetchAll();

        include 'view/penilaian-pdp/penilaian-pdp-edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $unit_id = $_POST['unit_id'];
            $user_id = $_SESSION['user_id'];
            $anggota_serikat_id = $_POST['anggota_serikat_id'];
            $peran = $_POST['peran'];
            $kpi = $_POST['kpi'];
            $uraian = $_POST['uraian'];
            $hasil_verifikasi = $_POST['hasil_verifikasi'];
            $nilai = $_POST['nilai'];
            $tanggal = $_POST['tanggal'];

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE penilaian_pdp SET unit_id = ?, user_id = ?, anggota_serikat_id = ?, peran = ?, kpi = ?, uraian = ?, hasil_verifikasi = ?, nilai = ?, tanggal = ?, updated_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unit_id, $user_id, $anggota_serikat_id, $peran, $kpi, $uraian, $hasil_verifikasi, $nilai, $tanggal, $updatedAt, $id]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Penilaian PDP updated successfully!'];

                header('Location: index.php?page=penilaian-pdp-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update Penilaian PDP!'];

                header('Location: index.php?page=penilaian-pdp-create');
            }
        }
    }

    public function destroy($id)
    {
        $stmt = $this->db->prepare("DELETE FROM penilaian_pdp WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Penilaian PDP deleted successfully!'];

            header('Location: index.php?page=penilaian-pdp-list');
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to delete Penilaian PDP!'];

            header('Location: index.php?page=penilaian-pdp-list');
        }
    }
}
