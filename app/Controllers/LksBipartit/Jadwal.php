<?php

namespace Controllers\LksBipartit;

use Libraries\CSRF;
use Libraries\Database;
use PDO;

class Jadwal
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function index()
    {
        $qry = $this->db->prepare("SELECT * FROM tema_lks_bipartit");
        $qry->execute();
        $temas = $qry->fetchAll();

        $qry = $this->db->prepare("SELECT * FROM units");
        $qry->execute();
        $units = $qry->fetchAll();

        // Ambil data dari database
        $stmt = $this->db->query("SELECT * FROM jadwal_lks_bipartit");
        $jadwals = $stmt->fetchAll();

        require_once 'view/lks-bipartit/jadwal/index.php';
    }

    public function create(){
        $qry = $this->db->prepare("SELECT * FROM tema_lks_bipartit");
        $qry->execute();
        $temas = $qry->fetchAll();

        $qry = $this->db->prepare("SELECT * FROM units");
        $qry->execute();
        $units = $qry->fetchAll();
        require_once 'view/lks-bipartit/jadwal/create.php';
    }

    public function fetchJadwal()
    {
        header('Content-Type: application/json');
        $qry = $this->db->query("SELECT namaAgenda AS title, tanggal_start AS start, tanggal_end AS end FROM jadwal_lks_bipartit");
        $events = $qry->fetchAll(PDO::FETCH_ASSOC);
        // Tambahkan satu hari ke setiap tanggal_end
        foreach ($events as &$event) {
            $event['end'] = date('Y-m-d', strtotime($event['end'] . ' +1 day'));
        }
        echo json_encode($events);
        exit;
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validasi CSRF Token
            if (!CSRF::validateToken($_POST['csrf_token'])) {
                die("Token CSRF tidak valid.");
            }

            // Ambil data dari form
            $temaId = $_POST['temaId'];
            $unitId = $_POST['unitId'];
            $namaAgenda = $_POST['namaAgenda'];
            $tanggal_start = $_POST['tanggal_start'];
            $tanggal_end = $_POST['tanggal_end'];

            // Validasi input sederhana
            if (empty($temaId) || empty($unitId) || empty($namaAgenda) || empty($tanggal_start) || empty($tanggal_end)) {
                header("Location: index.php?page=jadwal/create&error=1");
                exit;
            }

            // SQL Query untuk INSERT
            $sql = "INSERT INTO jadwal_lks_bipartit (temaId, unitId, namaAgenda, tanggal_start, tanggal_end) 
                VALUES (:temaId, :unitId, :namaAgenda, :tanggal_start, :tanggal_end)";

            // Siapkan dan eksekusi query
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':temaId' => $temaId,
                ':unitId' => $unitId,
                ':namaAgenda' => $namaAgenda,
                ':tanggal_start' => $tanggal_start,
                ':tanggal_end' => $tanggal_end
            ]);

            // Redirect berdasarkan hasil
            if ($success) {
                header("Location: index.php?page=jadwal&success=1");
            } else {
                header("Location: index.php?page=jadwal&gagal=1");
            }
        }
    }

    public function data(){
        $stmt = $this->db->prepare("SELECT j.id, j.namaAgenda, j.tanggal_start, j.tanggal_end, t.namaTema as nama_tema, u.name as nama_unit 
                                    FROM jadwal_lks_bipartit j
                                    JOIN tema_lks_bipartit t ON j.temaId = t.id
                                    JOIN units u ON j.unitId = u.id
                                    ORDER BY j.createdAt DESC;");
        $stmt->execute();
        $jadwals = $stmt->fetchAll();

        include 'view/lks-bipartit/jadwal/data.php';
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM jadwal_lks_bipartit WHERE id = ?");
        $stmt->execute([$id]);
        $jadwal = $stmt->fetch();

        $stmt = $this->db->prepare("SELECT id, namaTema FROM tema_lks_bipartit");
        $stmt->execute();
        $temas = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        require_once 'view/lks-bipartit/jadwal/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $temaId = $_POST['temaId'];
            $unitId = $_POST['unitId'];
            $namaAgenda = $_POST['namaAgenda'];
            $tanggal_start = $_POST['tanggal_start'];
            $tanggal_end = $_POST['tanggal_end'];

            // Validasi input sederhana
            if (empty($temaId) || empty($unitId) || empty($namaAgenda) || empty($tanggal_start) || empty($tanggal_end)) {
                header("Location: index.php?page=jadwal/edit&error=1");
                exit;
            }

            // Query SQL untuk update data
            $query = "UPDATE jadwal_lks_bipartit SET temaId = ?, unitId = ?, namaAgenda = ?, tanggal_start = ?, tanggal_end = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);

            // Eksekusi query dengan parameter yang sesuai
            $success = $stmt->execute([$temaId, $unitId, $namaAgenda, $tanggal_start, $tanggal_end, $id]);

            // Redirect berdasarkan hasil
            if ($success) {
                header('Location: index.php?page=jadwal&success=3');
            } else {
                header('Location: index.php?page=jadwal/data&error=2');
            }
        }
    }


    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi token CSRF
            if (!CSRF::validateToken($_POST['csrf_token'])) {
                header("Location: index.php?page=jadwal/data&error=1");
                exit;
            }

            $id_jadwal = $_POST['id_jadwal'];

            // Siapkan query DELETE
            $sql = "DELETE FROM jadwal_lks_bipartit WHERE id = :id_jadwal";
            $stmt = $this->db->prepare($sql);

            // Eksekusi query
            $success = $stmt->execute([':id_jadwal' => $id_jadwal]);

            // Redirect berdasarkan hasil
            if ($success) {
                header("Location: index.php?page=jadwal&success=2");
            } else {
                header("Location: index.php?page=jadwal/data&error=1");
            }
            exit;
        }
    }



}
