<?php
    namespace Controllers;

    use Helpers\Validation;
    use Libraries\CSRF;
    use Libraries\Database;
    class InfoSiru
    {
        protected $db;
        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function index() {
            $infoSirus = $this->db->prepare("
                        SELECT 
                            a.id AS info_siru_id, 
                            a.type, 
                            a.filePath, 
                            a.createdAt, 
                            u.username 
                        FROM info_sirus a 
                        JOIN users u ON u.id = a.sender 
                        ORDER BY a.createdAt DESC
                    ");
            $infoSirus->execute();

            include 'view/info-siru/index.php';
        }

        public function create() {

            include 'view/info-siru/create.php';
        }
        public function store() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=info-siru-create");
                    exit;
                }
                $sender = $_SESSION["user_id"];
                $type = $_POST['type'];

                if($type == "video"){
                    $filePath = Validation::ValidatorVideo($_FILES["filePath"],"uploads/info-siru/","index.php?page=info-siru-create");
                }else{
                    $filePath = Validation::ValidatorFile($_FILES["filePath"],"uploads/info-siru/","index.php?page=info-siru-create");
                }
            
                
            
                $createdAt = date('Y-m-d H:i:s');
                $updatedAt = date('Y-m-d H:i:s');
            
                $query = "INSERT INTO info_sirus (sender, filePath, type, createdAt, updateAt) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
            
                $success = $stmt->execute([$sender, $filePath, $type, $createdAt, $updatedAt]);
            
                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses membuat Info Siru baru!'];
                    header('Location: index.php?page=info-siru');
                    exit;
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal membuat Info Siru!'];
                    header('Location: index.php?page=info-siru-create');
                    exit;
                }
            }
            
        }

        public function edit(Int $id)  {
            $infoSiru = $this->db->prepare("select * from info_sirus where id = ?");
            $infoSiru->execute([$id]);
            $infoSiru = $infoSiru->fetch();
            include "view/info-siru/edit.php";

        }
        public function update(Int $id) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=info-siru-edit&id=$id");
                    exit;
                }
                $sender = $_SESSION["user_id"];
                $type = $_POST['type'];
            

                $query = "SELECT filePath FROM info_sirus WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$id]);
                $infoSiru = $stmt->fetch();

                if ($infoSiru && !empty($infoSiru['filePath']) && file_exists($infoSiru['filePath'])) {
                    unlink($infoSiru['filePath']);
                }
                
                $file = Validation::ValidatorFile($_FILES["filePath"],"uploads/info-siru/","index.php?page=info-siru-edit&id=$id");
    
                $updatedAt = date('Y-m-d H:i:s');
            
                $query = "update info_sirus set sender = ?, filePath = ?, type= ?, updateAt= ? where id = ?";
                $stmt = $this->db->prepare($query);
            
                $success = $stmt->execute([$sender, $file, $type, $updatedAt,$id]);
            
                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses mengedit Info Siru!'];
                    header('Location: index.php?page=info-siru');
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal Mengedit Info Siru!'];
                    header('Location: index.php?page=info-siru-edit&id=$id');
                }
            }
        }
        public function destroy(int $id)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=info-siru");
                    exit;
                }
                $stmt = $this->db->prepare("select filePath FROM info_sirus WHERE id = ?");
                $stmt->execute([$id]);
                $infoSiru = $stmt->fetch();
                if($infoSiru && !empty($infoSiru["filePath"]) && file_exists($infoSiru["filePath"])){
                    unlink($infoSiru["filePath"]);
                }
                $stmt = $this->db->prepare("DELETE FROM info_sirus WHERE id = ?");
                $success = $stmt->execute([$id]);

                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Info Siru berhasil dihapus!'];
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menghapus Info Siru.'];
                }

                header('Location: index.php?page=info-siru');
                exit();
            }
        }

}
?>