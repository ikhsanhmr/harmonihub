<?php
    namespace Controllers\Serikat;

    use Helpers\Validation;
    use Libraries\CSRF;
    use Libraries\Database;
use PDO;

    class Dsp
    {
        protected $db;
        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function index() {
            if(isset($_SESSION["role_name"]) &&  $_SESSION["role_name"] == "serikat"){
                $tim = $_SESSION["tim"];
                $stmt = $this->db->prepare("
                            SELECT 
                                dsp.id, 
                                dsp.dokumen, 
                                dsp.created_at, 
                                dsp.updated_at, 
                                s.name as serikat_name
                            FROM dsp 
                            JOIN serikat s ON s.id = dsp.id_serikat where s.name = ?
                            ORDER BY dsp.created_at DESC
                        ");
                $stmt->execute([$tim]);
            }else{
                $stmt = $this->db->prepare("
                            SELECT 
                                dsp.id, 
                                dsp.dokumen, 
                                dsp.created_at, 
                                dsp.updated_at, 
                                s.name as serikat_name
                            FROM dsp 
                            JOIN serikat s ON s.id = dsp.id_serikat 
                            ORDER BY dsp.created_at DESC
                        ");
                $stmt->execute();

            }
            $dsps = $stmt->fetchAll();

            include "view/serikat/dsp/index.php";
        }

        public function create() {
            $stmt = $this->db->prepare("select * from serikat");
            $stmt->execute();
            $serikats = $stmt->fetchAll();
            include "view/serikat/dsp/create.php";
        }
        public function store() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=dsp");
                    exit;
                }
                // jika role unit otomatis dari sessi jika admin pilih unit
                if(isset($_SESSION["role_name"]) && $_SESSION["role_name"] == "serikat"){
                    $stmt = $this->db->prepare("SELECT * FROM serikat where name = ?");
                    $stmt->execute([$_SESSION["tim"]]);
                    $dataId = $stmt->fetch(PDO::FETCH_ASSOC); 
                if ($dataId) {
                    $serikat = $dataId["id"]; 
                } 
                }else{
                    $serikat = $_POST["id_serikat"];
                }
             
                // Proses Upload File
                $dokumen = null;
                if (isset($_FILES['dokumen']) && $_FILES['dokumen']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/dsp/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileName = time() . '_' . basename($_FILES['dokumen']['name']);
                    $uploadFilePath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['dokumen']['tmp_name'], $uploadFilePath)) {
                        $dokumen = $fileName;
                    }
                }
            
                
            
                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');
            
                $query = "INSERT INTO dsp (id_serikat, dokumen, created_at, updated_at) VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
            
                $success = $stmt->execute([$serikat,$dokumen,$created_at,$updated_at]);
            
                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses membuat DSP baru!'];
                    header('Location: index.php?page=dsp');
                    exit;
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal membuat DSP!'];
                    header('Location: index.php?page=dsp-create');
                    exit;
                }
            }
            
        }

        public function edit(Int $id)  {
            $stmt = $this->db->prepare("select * from dsp where id = ?");
            $stmt->execute([$id]);
            $dataDsp = $stmt->fetch();

            $stmt = $this->db->prepare("select * from serikat");
            $stmt->execute();
            $serikats = $stmt->fetchAll();
            include "view/serikat/dsp/edit.php";

        }
        public function update(Int $id) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=dsp-edit&id=$id");
                    exit;
                }

                if(isset($_SESSION["role_name"]) && $_SESSION["role_name"] == "serikat"){
                    $serikat = $_SESSION["tim"];
                }else{
                    $serikat = $_POST["id_serikat"];
                }
                // Proses Upload File
                $dokumen = null;
                if (isset($_FILES['dokumen']) && $_FILES['dokumen']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/dsp/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileName = time() . '_' . basename($_FILES['dokumen']['name']);
                    $uploadFilePath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['dokumen']['tmp_name'], $uploadFilePath)) {
                        $dokumen = $fileName;
                    }
                }
            
                $updated_at = date('Y-m-d H:i:s');
                $query = "SELECT dokumen FROM dsp WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$id]);
                $dsp = $stmt->fetch();

                if ($dsp && !empty($dsp['dokumen']) && file_exists($dsp['dokumen'])) {
                    unlink($dsp['dokumen']);
                }

    
                if($dokumen){
                    $query = "update dsp set id_serikat = ?, dokumen = ?,  updated_at= ? where id = ?";
                    $stmt = $this->db->prepare($query);
                    $success = $stmt->execute([$serikat, $dokumen, $updated_at,$id]);
                }else{
                    $query = "update dsp set id_serikat = ?,  updated_at= ? where id = ?";
                    $stmt = $this->db->prepare($query);
                    $success = $stmt->execute([$serikat, $updated_at,$id]);
                }
            
            
                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses Mengubah DSP!'];
                    header('Location: index.php?page=dsp');
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal Mengubah DSP!'];
                    header('Location: index.php?page=dsp&id=$id');
                }
            }
        }
        public function destroy(int $id)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=dsp");
                    exit;
                }
                $stmt = $this->db->prepare("select dokumen FROM dsp WHERE id = ?");
                $stmt->execute([$id]);
                $dsp = $stmt->fetch();
                if($dsp && !empty($dsp["dokumen"]) && file_exists($dsp["dokumen"])){
                    unlink($dsp["dokumen"]);
                }
                $stmt = $this->db->prepare("DELETE FROM dsp WHERE id = ?");
                $success = $stmt->execute([$id]);

                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'DSP berhasil dihapus!'];
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal Menghapus DSP.'];
                }

                header('Location: index.php?page=dsp');
                exit();
            }
        }

}
?>