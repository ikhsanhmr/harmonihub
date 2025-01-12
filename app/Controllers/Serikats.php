<?php
    

    namespace Controllers;

    use Helpers\Validation;
    use Libraries\CSRF;
    use Libraries\Database;
    use PDOException;
    use Respect\Validation\Validator as v;

    final class Serikats
    {
        private $db;
        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function index()  {
            if (isset($_SESSION["role_name"]) && $_SESSION["role_name"] == "serikat") {
                $tim = $_SESSION["tim"];
                $stmt = $this->db->prepare("select * from serikat where name = ? order by createdAt DESC ");
                $stmt->execute([$tim]);
            } else {
                $stmt = $this->db->prepare("select * from serikat order by createdAt DESC");
                $stmt->execute();
            }  
            $serikats=  $stmt->fetchAll();
            include "view/serikats/index.php";
        }
        public function create()  {
            include "view/serikats/create.php";
        }
        public function store()  {
            if($_SERVER["REQUEST_METHOD"] === "POST"){
                if(!CSRF::validateToken($_POST["csrf_token"])){
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=serikat-create");
                    exit;
                }
                $fields = [
                    "name"=>[
                        'validator' => v::stringType()->notEmpty()->length(1, 40),
                        'message' => 'Nama harus diisi dan antara 1 hingga 40 karakter.'
                    ]
                ];
                $dataValidate = Validation::ValidatorInput($fields,"index.php?page=serikat-create");
                $file = Validation::ValidatorFile($_FILES["logoPath"],"uploads/serikat/","index.php?page=serikat-create");

                $createdAt = date('Y-m-d H:i:s');
                $updatedAt = date('Y-m-d H:i:s');
                
                $query = "INSERT INTO serikat (name, logoPath, createdAt, updateAt) VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $success = $stmt->execute([$dataValidate["name"],$file,$createdAt,$updatedAt]);
                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses membuat Serikat  baru!'];
                    header('Location: index.php?page=serikat');
                    exit;
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal membuat Serikat baru!'];
                    header('Location: index.php?page=serikat-create');
                    exit;
                }
            }
                    
        }
        public function edit(Int $id)  {
            $stmt = $this->db->prepare("select * from serikat where id = ?");
            $stmt->execute([$id]);
            $serikat = $stmt->fetch();
            include "view/serikats/edit.php";
        }
        public function update(Int $id)  {
            if($_SERVER["REQUEST_METHOD"] === "POST"){
                if(!CSRF::validateToken($_POST["csrf_token"])){
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=serikat-edit&id=$id");
                    exit;
                }
                $fields = [
                    "name"=>[
                        'validator' => v::stringType()->notEmpty()->length(1, 40),
                        'message' => 'Nama harus diisi antara 1 hingga 40 karakter'
                    ]
                ];

                // cek perubahan nama dan logo
                $stmt = $this->db->prepare("SELECT logoPath FROM serikat WHERE id = ?");
                $stmt->execute([$id]);
                $serikat = $stmt->fetch();

                // lakukan validasi jika nama berubah
                if ($serikat['name'] != $_POST['name']) {
                    $serikat['name'] = Validation::ValidatorInput($fields,"index.php?page=serikat-edit&id=$id")["name"];
                }

                // lakukan validasi jika ada logo di up
                if (!empty($_FILES["logoPath"]["name"])) {
                    // hapus logo lama jika ada gambar baru
                    if (file_exists($serikat['logoPath'])) {
                        unlink($serikat['logoPath']);
                    }

                    // validasi dan masukkan nama file baru
                    $serikat["logoPath"] = Validation::ValidatorFile($_FILES["logoPath"],"uploads/serikat/","index.php?page=serikat-edit&id=$id");
                    
                }

                $updatedAt = date('Y-m-d H:i:s');
                
                $query = "UPDATE serikat SET name = ?, logoPath = ?, updateAt = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $success = $stmt->execute([$serikat["name"],$serikat["logoPath"],$updatedAt,$id]);
                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses Mengubah Serikat  baru!'];
                    header('Location: index.php?page=serikat');
                    exit;
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal Mengubah Serikat !'];
                    header('Location: index.php?page=serikat-edit&id=$id');
                    exit;
                }
            }
                    
        }
        public function destroy(Int $id)  {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=serikat");
                    exit;
                }
                $stmt = $this->db->prepare("select logoPath FROM serikat WHERE id = ?");
                $stmt->execute([$id]);
                $serikat = $stmt->fetch();
                if($serikat && !empty($serikat["logoPath"]) && file_exists($serikat["logoPath"])){
                    unlink($serikat["logoPath"]);
                }
                try {   
                    $stmt = $this->db->prepare("DELETE FROM serikat WHERE id = ?");
                    $stmt->execute([$id]);
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Data Serikat berhasil dihapus!'];
                } catch (PDOException $e) {
                    if ($e->getCode() == '23000') {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Tidak bisa menghapus Serikat karena masih digunakan di tabel lain.'];
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan: ' . $e->getMessage()];
                    }
                }

                header('Location: index.php?page=serikat');
                exit();
            }
        }
    }
    

?>