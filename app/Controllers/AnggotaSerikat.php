<?php
    namespace Controllers;

    use Helpers\Validation;
    use Libraries\CSRF;
    use Libraries\Database;
use PDOException;
use Respect\Validation\Exceptions\NestedValidationException;
    use Respect\Validation\Validator as v;

    final class AnggotaSerikat 
    {
        private $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function index() {
            $serikats = $this->db->prepare("
            select s.id as id_serikat ,
            s.name as nama_serikat,
            u.id as id_unit, 
            u.name as nama_unit, 
            ase.name, ase.membership,ase.nip ,ase.noKta , ase.id , ase.createdAt ,ase.updateAt
            from anggota_serikats ase join units u on u.id = ase.unitId
            join serikat s on s.id = ase.serikatId
            ORDER BY ase.createdAt DESC
            ");
            $serikats->execute();
            include "view/anggota-serikat/index.php";
        }

        public function store()  {
            if($_SERVER["REQUEST_METHOD"]  == "POST"){
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?harmonihub=serikat");
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
                    'serikatId' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Serikat harus diisi.'
                    ],
                ]; 

                $dataValidate = Validation::ValidatorInput($fields,"index.php?harmonihub=serikat");
                
                    $createdAt = date('Y-m-d H:i:s');
                    $updatedAt = date('Y-m-d H:i:s');
                
                    $query = "INSERT INTO anggota_serikats ( name, nip,unitId,membership,noKta,serikatId, createdAt, updateAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->db->prepare($query);

                    $success = $stmt->execute([$dataValidate['name'], $dataValidate['nip'],$unitId,$dataValidate['membership'], $dataValidate['noKta'],$dataValidate['serikatId'],$createdAt, $updatedAt]);
                    if ($success) {
                        $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses membuat Serikat Pekerja baru!'];
                        header('Location: index.php?harmonihub=serikat');
                        exit;
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal membuat Serikat Pekerja!'];
                        header('Location: index.php?harmonihub=serikat');
                        exit;
                    }
                }
        }

        public function edit(Int $id)  {
            $serikats = $this->db->prepare("select id, name as nama_serikat from serikat");
            $serikats->execute();
            $serikats = $serikats->fetchAll();
            $anggotaSerikat = $this->db->prepare("select * from anggota_serikats where id = ?");
            $anggotaSerikat->execute([$id]);
            $anggotaSerikat = $anggotaSerikat->fetch();
            
            $units = $this->db->prepare("SELECT id, name as nama_unit FROM units");
            $units->execute();
            $units =$units->fetchAll();
            include "view/anggota-serikat/edit.php";
        }
        
        public function update(int $id)  {
            if($_SERVER["REQUEST_METHOD"]  == "POST"){
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=anggota-serikat");
                    exit;
                }
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
                    'serikatId' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Serikat harus diisi.'
                    ],
                    'unitId' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Unit harus diisi.'
                    ],
                ]; 
                
                
                $dataValidate = Validation::ValidatorInput($fields,"index.php?page=anggota-serikat-edit&id=$id");
                
                    $updatedAt = date('Y-m-d H:i:s');
                
                    $query = "update anggota_serikats set unitId = ?, name = ?, nip= ?, membership= ?, noKta= ?, serikatId= ?, updateAt= ? where id= ?";
                    $stmt = $this->db->prepare($query);

                    $success = $stmt->execute([$dataValidate['unitId'], $dataValidate['name'], $dataValidate['nip'],$dataValidate['membership'],$dataValidate['noKta'],$dataValidate['serikatId'],  $updatedAt,$id]);
                    if ($success) {
                        $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses mengubah Serikat Pekerja baru!'];
                        header('Location: index.php?page=anggota-serikat');
                        exit;
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengubah Serikat Pekerja!'];
                        header('Location: index.php?page=anggota-serikat-edit&id=$id');
                        exit;
                    }
                }
        }
        public function destroy($id)  {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=anggota-serikat");
                    exit;
                }

                try {
                    $stmt = $this->db->prepare("DELETE FROM anggota_serikats WHERE id = ?");
                    $stmt->execute([$id]);
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Data Serikat Pekerja berhasil dihapus!'];
                    header('Location: index.php?page=anggota-serikat');
                    exit();
                } catch (PDOException $e) {
                    if ($e->getCode() == '23000') {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Tidak bisa menghapus Anggota Serikat karena masih digunakan di tabel lain.'];
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan: ' . $e->getMessage()];
                    }
                    header('Location: index.php?page=anggota-serikat');
                    exit();
                }

               
            }
        }
    }
    

?>