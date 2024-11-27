<?php
    namespace Controllers;

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
                $sender = $_SESSION["user_id"];
                $type = $_POST['type'];
                $filePath = null;
            
                if (!isset($_FILES['filePath']) || $_FILES['filePath']['error'] !== UPLOAD_ERR_OK) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'File harus di-upload.'];
                    header('Location: index.php?page=info-siru-create');
                    exit;
                }
            
                $fileName = $_FILES['filePath']['name'];
                $fileTmpPath = $_FILES['filePath']['tmp_name'];
                $fileSize = $_FILES['filePath']['size'];
            
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'mp4'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => "Jenis file tidak valid. Hanya diperbolehkan: " . implode(', ', $allowedExtensions)];
                    header('Location: index.php?page=info-siru-create');
                    exit;
                }
            
                $maxFileSize = 5 * 1024 * 1024; // 5MB
                if ($fileSize > $maxFileSize) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Maksimal ukuran file 5MB.'];
                    header('Location: index.php?page=info-siru-create');
                    exit;
                }
            
                $uploadDir = 'uploads/info-siru/';
                $filePath = $uploadDir . uniqid() . '_' . $fileName;
            
                if (!move_uploaded_file($fileTmpPath, $filePath)) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal meng-upload file.'];
                    header('Location: index.php?page=info-siru-create');
                    exit;
                }
            
                $createdAt = date('Y-m-d H:i:s');
                $updatedAt = date('Y-m-d H:i:s');
            
                $query = "INSERT INTO info_sirus (sender, filePath, type, createdAt, updateAt) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
            
                $success = $stmt->execute([$sender, $filePath, $type, $createdAt, $updatedAt]);
            
                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses membuat Info Siru baru!'];
                    header('Location: index.php?page=info-siru');
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal membuat Info Siru!'];
                    header('Location: index.php?page=info-siru-create');
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
                
                $sender = $_SESSION["user_id"];
                $type = $_POST['type'];
                $filePath = null;
            

                $query = "SELECT filePath FROM info_sirus WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$id]);
                $album = $stmt->fetch();

                if ($album && !empty($album['filePath']) && file_exists($album['filePath'])) {
                    unlink($album['filePath']);
                }
                
                if (!isset($_FILES['filePath']) || $_FILES['filePath']['error'] !== UPLOAD_ERR_OK) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'File harus di-upload.'];
                    header('Location: index.php?page=info-siru-update');
                    exit;
                }
            
                $fileName = $_FILES['filePath']['name'];
                $fileTmpPath = $_FILES['filePath']['tmp_name'];
                $fileSize = $_FILES['filePath']['size'];
            
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'mp4'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
                if (!in_array($fileExtension, $allowedExtensions)) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => "Jenis file tidak valid. Hanya diperbolehkan: " . implode(', ', $allowedExtensions)];
                    header('Location: index.php?page=info-siru-update');
                    exit;
                }
            
                $maxFileSize = 5 * 1024 * 1024; // 5MB
                if ($fileSize > $maxFileSize) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Maksimal ukuran file 5MB.'];
                    header('Location: index.php?page=info-siru-update');
                    exit;
                }
            
                $uploadDir = 'uploads/info-siru/';
                $filePath = $uploadDir . uniqid() . '_' . $fileName;
               
                if (!move_uploaded_file($fileTmpPath, $filePath)) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal meng-upload file.'];
                    header('Location: index.php?page=info-siru-update');
                    exit;
                }
    
                $updatedAt = date('Y-m-d H:i:s');
            
                $query = "update info_sirus set sender = ?, filePath = ?, type= ?, updateAt= ? where id = ?";
                $stmt = $this->db->prepare($query);
            
                $success = $stmt->execute([$sender, $filePath, $type, $updatedAt,$id]);
            
                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses mengedit Info Siru!'];
                    header('Location: index.php?page=info-siru');
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal Mengedit Info Siru!'];
                    header('Location: index.php?page=info-siru-update');
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

                $stmt = $this->db->prepare("DELETE FROM info_sirus WHERE id = ?");
                $success = $stmt->execute([$id]);

                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Info Siru berhasil dihapus!'];
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menghapus Info Siru.'];
                }

                // Redirect kembali ke halaman album
                header('Location: index.php?page=info-siru');
                exit();
            }
        }

}
?>