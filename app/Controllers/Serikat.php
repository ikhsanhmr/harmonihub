<?php
    namespace Controllers;

    use Helpers\Validation;
    use Libraries\CSRF;
    use Libraries\Database;
use PDOException;
use Respect\Validation\Exceptions\NestedValidationException;
    use Respect\Validation\Validator as v;

    final class Serikat 
    {
        private $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function index() {
            $serikats = $this->db->prepare("
            select s.id as id_serikat ,
            s.name as nama,
            s.nip , 
            s.membership as keanggotaan,
            s.noKta ,
            s.position as posisi,
            s.logoPath, 
            s.createdAt , 
            s.updateAt ,
            u.name as nama_unit 
            from serikat s join units u on u.id = s.unitId 
            ORDER BY s.createdAt DESC
            ");
            $serikats->execute();
            include "view/serikat/index.php";
        }

        public function create()  {

            $units = $this->db->prepare("select * from units");
            $units->execute();
            include "view/serikat/create.php";
        }
        public function store()  {
            if($_SERVER["REQUEST_METHOD"]  == "POST"){
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=info-siru");
                    exit;
                }
                $unitId = $_POST["unitId"]; 
                $fields = [
                    'name' => [
                        'validator' => v::stringType()->notEmpty()->length(3, 40),
                        'message' => 'Nama harus diisi dan antara 3 hingga 40 karakter.'
                    ],
                    'nip' => [
                        'validator' => v::stringType()->notEmpty()->digit()->length(3, 18),
                        'message' => 'NIP harus berupa angka dan antara 3 hingga 18 digit.'
                    ],
                    'membership' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Keanggotaan harus diisi.'
                    ],
                    'noKta' => [
                        'validator' => v::stringType()->notEmpty()->length(2, 20),
                        'message' => 'Nomor KTA harus diisi dan antara 5 hingga 20 karakter.'
                    ],
                    'position' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Posisi harus diisi.'
                    ],
                ]; 

                $dataValidate = Validation::ValidatorInput($fields,"index.php?page=serikat-create");
                
                $file = Validation::ValidatorFile($_FILES["logoPath"],"uploads/serikat/","index.php?page=serikat-create");
                
                    $createdAt = date('Y-m-d H:i:s');
                    $updatedAt = date('Y-m-d H:i:s');
                
                    $query = "INSERT INTO serikat ( unitId,name, nip,membership,noKta,position, logoPath, createdAt, updateAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->db->prepare($query);

                    $success = $stmt->execute([$unitId, $dataValidate['name'], $dataValidate['nip'],$dataValidate['membership'],$dataValidate['noKta'],$dataValidate['position'],$file, $createdAt, $updatedAt]);
                    if ($success) {
                        $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses membuat Serikat Pekerja baru!'];
                        header('Location: index.php?page=serikat');
                        exit;
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal membuat Serikat Pekerja!'];
                        header('Location: index.php?page=info-serikat-create');
                        exit;
                    }
                }
        }

        public function edit(Int $id)  {
            $serikat = $this->db->prepare("select * from serikat where id = ?");
            $serikat->execute([$id]);
            $serikat = $serikat->fetch();
            
            $units = $this->db->prepare("SELECT id, name as nama_unit FROM units");
            $units->execute();
            include "view/serikat/edit.php";
        }
        
        public function update(int $id)  {
            if($_SERVER["REQUEST_METHOD"]  == "POST"){
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=info-siru");
                    exit;
                }
                $unitId = $_POST["unitId"]; 
                $fields = [
                    'name' => [
                        'validator' => v::stringType()->notEmpty()->length(3, 40),
                        'message' => 'Nama harus diisi dan antara 3 hingga 40 karakter.'
                    ],
                    'nip' => [
                        'validator' => v::stringType()->notEmpty()->digit()->length(3, 18),
                        'message' => 'NIP harus berupa angka dan antara 3 hingga 18 digit.'
                    ],
                    'membership' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Keanggotaan harus diisi.'
                    ],
                    'noKta' => [
                        'validator' => v::stringType()->notEmpty()->length(2, 20),
                        'message' => 'Nomor KTA harus diisi dan antara 5 hingga 20 karakter.'
                    ],
                    'position' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Posisi harus diisi.'
                    ],
                ]; 
                $query = "SELECT logoPath FROM serikat WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$id]);
                $serikat = $stmt->fetch();

                if ($serikat && !empty($serikat['logoPath']) && file_exists($serikat['logoPath'])) {
                    unlink($serikat['logoPath']);
                } 
                
                
                $dataValidate = Validation::ValidatorInput($fields,"index.php?page=serikat-edit&id=$id");
                
                $file = Validation::ValidatorFile($_FILES["logoPath"],"uploads/serikat/","index.php?page=serikat-edit&id=$id");
                
                    $updatedAt = date('Y-m-d H:i:s');
                
                    $query = "update serikat set unitId = ?, name = ?, nip= ?, membership= ?, noKta= ?, position= ?,logoPath=?, updateAt= ? where id= ?";
                    $stmt = $this->db->prepare($query);

                    $success = $stmt->execute([$unitId, $dataValidate['name'], $dataValidate['nip'],$dataValidate['membership'],$dataValidate['noKta'],$dataValidate['position'],$file,  $updatedAt,$id]);
                    if ($success) {
                        $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses mengubah Serikat Pekerja baru!'];
                        header('Location: index.php?page=serikat');
                        exit;
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengubah Serikat Pekerja!'];
                        header('Location: index.php?page=info-serikat-create');
                        exit;
                    }
                }
        }
        public function destroy($id)  {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=serikat");
                    exit;
                }

                try {
                    $stmt = $this->db->prepare("DELETE FROM serikat WHERE id = ?");
                    $stmt->execute([$id]);
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Data Serikat Pekerja berhasil dihapus!'];
                } catch (PDOException $e) {
                    if ($e->getCode() == '23000') {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Tidak bisa menghapus Serikat karena masih digunakan di tabel lain.'];
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan: ' . $e->getMessage()];
                    }
                    header("Location: index.php?page=serikat");
                    exit;
                }

                header('Location: index.php?page=serikat');
                exit();
            }
        }
    }
    

?>