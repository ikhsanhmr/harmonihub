<?php

namespace Controllers\LksBipartit;
use Libraries\Database;
use Libraries\CSRF;

class Tim
{
    protected $db;
    public function __construct()
    {
        $this->db = new Database();
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function index()
    {
        // Mengambil data tema dari database
        $tims = $this->db->query("SELECT t.id, t.nip_pegawai, t.nama_pegawai, t.peran, u.name as name_unit FROM tim_lks_bipartit t INNER JOIN units u ON t.unitId = u.id;");
        require_once 'view/lks-bipartit/tim/index.php';
    }

    public function create(){
        $qry = $this->db->prepare("SELECT * FROM units");
        $qry->execute();
        $units = $qry->fetchAll();
        require_once 'view/lks-bipartit/tim/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validasi CSRF Token
            if (!CSRF::validateToken($_POST['csrf_token'])) {
                die("Token CSRF tidak valid.");
            }

            // Ambil data dari form
            $nip_pegawai = $_POST['nip_pegawai'];
            $nama_pegawai = $_POST['nama_pegawai'];
            $peran = $_POST['peran'];
            $unitId = $_POST['unitId'];

            // Validasi input sederhana
            if (empty($nip_pegawai) || empty($nama_pegawai) || empty($peran) || empty($unitId)) {
                header("Location: index.php?page=tim/create&error=1");
                exit;
            }

            // SQL Query untuk INSERT
            $sql = "INSERT INTO tim_lks_bipartit (nip_pegawai, nama_pegawai, peran, unitId) 
                VALUES (:nip_pegawai, :nama_pegawai, :peran, :unitId)";

            // Siapkan dan eksekusi query
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':nip_pegawai' => $nip_pegawai,
                ':nama_pegawai' => $nama_pegawai,
                ':peran' => $peran,
                ':unitId' => $unitId,
            ]);

            // Redirect berdasarkan hasil
            if ($success) {
                header("Location: index.php?page=tim&success=1");
            } else {
                header("Location: index.php?page=tim&gagal=1");
            }
        }
    }
    public function edit($id){

        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT * FROM tim_lks_bipartit WHERE id = ?");
        $stmt->execute([$id]);
        $tim = $stmt->fetch();

        
        require_once 'view/lks-bipartit/tim/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nip_pegawai = $_POST['nip_pegawai'];
            $nama_pegawai = $_POST['nama_pegawai'];
            $peran = $_POST['peran'];
            $unitId = $_POST['unitId'];

            // Validasi input sederhana
            if (empty($nip_pegawai) || empty($nama_pegawai) || empty($peran) || empty($unitId)) {
                header("Location: index.php?page=tim/edit&error=1");
                exit;
            }

            // Query SQL untuk update data
            $query = "UPDATE tim_lks_bipartit SET nip_pegawai = ?, nama_pegawai = ?, peran = ?, unitId = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);

            // Eksekusi query dengan parameter yang sesuai
            $success = $stmt->execute([$nip_pegawai, $nama_pegawai, $peran, $unitId, $id]);

            // Redirect berdasarkan hasil
            if ($success) {
                header('Location: index.php?page=tim&success=2');
            } else {
                header('Location: index.php?page=tim&error=1');
            }
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi token CSRF
            if (!CSRF::validateToken($_POST['csrf_token'])) {
                header("Location: index.php?page=tim&error=1");
                exit;
            }

            $id_tim = $_POST['id_tim'];

            // Siapkan query DELETE
            $sql = "DELETE FROM tim_lks_bipartit WHERE id = :id_tim";
            $stmt = $this->db->prepare($sql);

            // Eksekusi query
            $success = $stmt->execute([':id_tim' => $id_tim]);

            // Redirect berdasarkan hasil
            if ($success) {
                header("Location: index.php?page=tim&success=3");
            } else {
                header("Location: index.php?page=tim&error=2");
            }
            exit;
        }
    }
}