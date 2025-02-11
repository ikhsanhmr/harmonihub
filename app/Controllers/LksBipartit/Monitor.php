<?php
    namespace Controllers\LksBipartit;

use Helpers\Validation;
use Libraries\Database;
use PDO;
use PDOException;
use Respect\Validation\Validator as v;
    final class Monitor 
    {
        protected $db;
        
        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function index($tahun = null, $unit = null) {
            // Ambil data monitor
            $stmt = $this->db->prepare("SELECT id FROM monitor_lks_bipartit");
            $stmt->execute();
            $idMonitors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Menyiapkan kolom dinamis
            $columns = [];
            for ($i = 1; $i <= 13; $i++) {
                $columns[] = "GROUP_CONCAT(DISTINCT CASE WHEN bulan.id = $i THEN dmlb.rekomendasi END ORDER BY dmlb.id) AS rekomendasi_$i";
                $columns[] = "GROUP_CONCAT(DISTINCT CASE WHEN bulan.id = $i THEN dmlb.tindak_lanjut END ORDER BY dmlb.id) AS tindak_lanjut_$i";
                $columns[] = "GROUP_CONCAT(DISTINCT CASE WHEN bulan.id = $i THEN dmlb.evaluasi END ORDER BY dmlb.id) AS evaluasi_$i";
                $columns[] = "GROUP_CONCAT(DISTINCT CASE WHEN bulan.id = $i THEN dmlb.follow_up END ORDER BY dmlb.id) AS follow_up_$i";
                $columns[] = "GROUP_CONCAT(DISTINCT CASE WHEN bulan.id = $i THEN dmlb.realisasi END ORDER BY dmlb.id) AS realisasi_$i";
                $columns[] = "GROUP_CONCAT(DISTINCT CASE WHEN bulan.id = $i THEN tlb.namaTema END ORDER BY dmlb.id) AS tema_$i";
            }
            $dynamicColumns = implode(",\n    ", $columns);
        
            $data = [];
            foreach ($idMonitors as $idMonitor) {
                $stmt = $this->db->prepare("SELECT monitor_id FROM date_monitor_lks_bipartit WHERE monitor_id = ?");
                $stmt->execute([$idMonitor['id']]);
                $dmlb = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                if ($dmlb) {
                    // Query jika ada data dmlb
                    $sql = "
                        SELECT 
                            m.id AS id, 
                            u.name AS unit_name, 
                            b.name AS ba_name, 
                            b.created_at AS ba_created_at, 
                            $dynamicColumns,
                            ms.serikat_ids,
                            ms.nilai_values
                        FROM 
                            date_monitor_lks_bipartit dmlb
                        JOIN 
                            monitor_lks_bipartit m ON m.id = dmlb.monitor_id
                        JOIN 
                            tema_lks_bipartit tlb ON tlb.id = dmlb.tema_id  
                        JOIN 
                            bulan ON bulan.id = dmlb.bulan_id  
                        JOIN 
                            units u ON u.id = m.unit_id 
                        JOIN 
                            ba_pembentukan b ON b.id = m.ba_id
                        LEFT JOIN 
                            (SELECT 
                                monitor_id, 
                                GROUP_CONCAT(DISTINCT serikat_id ORDER BY serikat_id ASC) AS serikat_ids,
                                GROUP_CONCAT(nilai ORDER BY serikat_id ASC) AS nilai_values
                            FROM 
                                monitor_serikat
                            GROUP BY 
                                monitor_id) 
                            ms ON ms.monitor_id = m.id
                        WHERE 
                            m.id = ?
                            " . ($tahun ? " AND YEAR(b.created_at) = ?" : "") . "  -- Filter berdasarkan tahun jika ada
                            " . ($unit ? " AND u.id = ?" : "") . "  -- Filter berdasarkan unit jika ada
                        GROUP BY 
                            m.id, u.name, b.name, b.created_at;
                    ";
        
                    $stmt = $this->db->prepare($sql);
                    if ($tahun) {
                        $stmt->execute([$idMonitor['id'], $tahun]); // Gunakan positional parameters
                    } elseif ($unit) {
                        $stmt->execute([$idMonitor['id'], $unit]);
                     } // Gunakan positional parameters
                     else {
                        $stmt->execute([$idMonitor['id']]);
                    }
                } else {
                    // Query jika tidak ada data dmlb
                    $sql = "
                        SELECT 
                            m.id AS id, 
                            u.name AS unit_name, 
                            b.name AS ba_name, 
                            b.created_at AS ba_created_at, 
                            ms.serikat_ids,
                            ms.nilai_values
                        FROM 
                            monitor_lks_bipartit m
                        JOIN 
                            units u ON u.id = m.unit_id 
                        JOIN 
                            ba_pembentukan b ON b.id = m.ba_id
                        LEFT JOIN 
                            (SELECT 
                                monitor_id, 
                                GROUP_CONCAT(DISTINCT serikat_id ORDER BY serikat_id ASC) AS serikat_ids,
                                GROUP_CONCAT(nilai ORDER BY serikat_id ASC) AS nilai_values
                            FROM 
                                monitor_serikat
                            GROUP BY 
                                monitor_id) 
                            ms ON ms.monitor_id = m.id
                        WHERE 
                            m.id = ?
                            " . ($tahun ? " AND YEAR(b.created_at) = ?" : "") . "  -- Filter berdasarkan tahun jika ada
                        GROUP BY 
                            m.id, u.name, b.name, b.created_at;
                    ";
        
                    $stmt = $this->db->prepare($sql);
                    if ($tahun) {
                        $stmt->execute([$idMonitor['id'], $tahun]); // Gunakan positional parameters
                    }  elseif ($unit) {
                        $stmt->execute([$idMonitor['id'], $unit]);
                    } // Gunakan positional parameters
                    
                    else {
                        $stmt->execute([$idMonitor['id']]);
                    }
                }
        
                $data[] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        
            $monitors = array_merge(...$data);
        
            // Ambil data bulan
            $stmt = $this->db->prepare("SELECT * FROM bulan");
            $stmt->execute();
            $bulans = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Ambil data serikat
            $stmt = $this->db->prepare("SELECT * FROM serikat");
            $stmt->execute();
            $serikats = $stmt->fetchAll(PDO::FETCH_ASSOC);

             // Ambil data unit
        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            include "view/lks-bipartit/monitor/index.php";
        }

        public function create()  {
            $stmt = $this->db->prepare("select * from units");
            $stmt->execute();
            $units = $stmt->fetchAll();
            $stmt = $this->db->prepare("select * from ba_pembentukan");
            $stmt->execute();
            $ba_pembentukans = $stmt->fetchAll();
            $stmt = $this->db->prepare("select * from serikat");
            $stmt->execute();
            $serikats = $stmt->fetchAll();
            include "view/lks-bipartit/monitor/create.php";
        }
        public function store() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $serikat_data = $_POST['serikat']; 

                $fields = [
                    'unit_id' => [
                        'validator' => v::notEmpty(),
                        'message' => 'Unit Harus Diisi.'
                    ],
                    'ba_id' => [
                        'validator' => v::notEmpty(),
                        'message' => 'Ba_pembentukan harus diisi.'
                    ],
                ]; 

                $dataValidate = Validation::ValidatorInput($fields,"index.php?page=monitor");
                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');

                $query = "INSERT INTO monitor_lks_bipartit ( unit_id, ba_id, created_at, updated_at) VALUES (?, ?, ?, ?)";
                
                $stmt = $this->db->prepare($query);

                $stmt->execute([$dataValidate['unit_id'], $dataValidate['ba_id'],$created_at, $updated_at]);
                $monitor_id = $this->db->lastInsertId();

                foreach ($serikat_data as $serikat_id => $nilai) {
                    $query_serikat = "INSERT INTO monitor_serikat(monitor_id,serikat_id, nilai) values (?,?,?)";
                    $stmt = $this->db->prepare($query_serikat);
                    $stmt->execute([$monitor_id,$serikat_id,$nilai]);
                }
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses membuat Monitor baru'];
                header('Location: index.php?page=monitor');
                exit;
            }
        }
        public function jadwalCreate($id)  {
            $stmt = $this->db->prepare("select * from tema_lks_bipartit");
            $stmt->execute();
            $temas = $stmt->fetchAll();
            $stmt = $this->db->prepare("select * from bulan");
            $stmt->execute();
            $bulans = $stmt->fetchAll();
            include "view/lks-bipartit/monitor/jadwal.php";
        }
        public function jadwalStore($id)  {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                $fields = [
                    'monitor_id' => [
                        'validator' => v::notEmpty(),
                        'message' => 'Id Monitor Harus Diisi.'
                    ],
                    'tema_id' => [
                        'validator' => v::notEmpty(),
                        'message' => 'Tema Harus Diisi.'
                    ],
                    'bulan_id' => [
                        'validator' => v::notEmpty(),
                        'message' => 'Tema Harus Diisi.'
                    ],
                    'rekomendasi' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Rekomendasi harus diisi.'
                    ],
                    'tindak_lanjut' => [
                        'validator' => v::stringType()->notEmpty()->length(2, 20),
                        'message' => 'Tindak Lanjut harus diisi dan antara 2 hingga 20 karakter.'
                    ],
                    'evaluasi' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Evaluasi harus diisi.'
                    ],
                    'follow_up' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Follow Up harus diisi.'
                    ],
                    'realisasi' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Realisasi harus diisi.'
                    ],
                ]; 
                
                $dataValidate = Validation::ValidatorInput($fields,"index.php?page=monitor");
                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');
                $query = "INSERT INTO date_monitor_lks_bipartit ( monitor_id, tema_id,bulan_id,rekomendasi,tindak_lanjut,evaluasi,follow_up,realisasi, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);

                $success = $stmt->execute([$dataValidate["monitor_id"], $dataValidate['tema_id'],$dataValidate["bulan_id"],$dataValidate['rekomendasi'], $dataValidate['tindak_lanjut'],$dataValidate['evaluasi'],$dataValidate['follow_up'],$dataValidate['realisasi'],$created_at, $updated_at]);

                 if ($success) {
                        $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses membuat Jadwal Monitoring'];
                        header('Location: index.php?page=monitor');
                        exit;
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal membuat Jadwal Monitoring '];
                        header('Location: index.php?page=monitor-jadwal-create');
                        exit;
                    }

            }
        }
        public function destroy($id)  {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                try {
                    $stmt = $this->db->prepare("DELETE FROM date_monitor_lks_bipartit WHERE monitor_id = ?");
                    $stmt->execute([$id]);
                    $stmt = $this->db->prepare("DELETE FROM monitor_serikat WHERE monitor_id = ?");
                    $stmt->execute([$id]);
                    $stmt = $this->db->prepare("DELETE FROM monitor_lks_bipartit WHERE id = ?");
                    $stmt->execute([$id]);
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Data Monitor berhasil dihapus!'];
                    header('Location: index.php?page=monitor');
                    exit();
                } catch (PDOException $e) {
                    if ($e->getCode() == '23000') {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Tidak bisa menghapus Data Monitor karena masih digunakan di tabel lain.'];
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan: ' . $e->getMessage()];
                    }
                    header('Location: index.php?page=monitor');
                    exit();
                }

               
            }
        }
    }
    


?>