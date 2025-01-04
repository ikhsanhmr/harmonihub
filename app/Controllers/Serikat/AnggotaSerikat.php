<?php
    namespace Controllers\Serikat;

    use Dompdf\Dompdf;
    use Dompdf\Options;
    use Exception;
    use Helpers\Validation;
    use Libraries\CSRF;
    use Libraries\Database;
    use PDOException;
    use Respect\Validation\Exceptions\NestedValidationException;
    use Respect\Validation\Validator as v;
    use PhpOffice\PhpSpreadsheet\IOFactory;
    final class AnggotaSerikat 
    {
        private $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function index() {
            if (isset($_SESSION["role_name"]) &&  $_SESSION["role_name"] == "serikat") {
                $tim = $_SESSION["tim"];
                $stmt = $this->db->prepare("
            select s.id as id_serikat ,
            s.name as nama_serikat,
            u.id as id_unit, 
            u.name as nama_unit, 
            ase.name, ase.membership,ase.nip ,ase.noKta , ase.id , ase.createdAt ,ase.updateAt
            from anggota_serikats ase join units u on u.id = ase.unitId
            join serikat s on s.id = ase.serikatId where s.name = ?
            ORDER BY ase.createdAt DESC
            ");
                $stmt->execute([$tim]);
            } else {
                $stmt = $this->db->prepare("
            select s.id as id_serikat ,
            s.name as nama_serikat,
            u.id as id_unit, 
            u.name as nama_unit, 
            ase.name, ase.membership,ase.nip ,ase.noKta , ase.id , ase.createdAt ,ase.updateAt
            from anggota_serikats ase join units u on u.id = ase.unitId
            join serikat s on s.id = ase.serikatId
            ORDER BY ase.createdAt DESC
            ");
                $stmt->execute();
            }  
            $serikats=  $stmt->fetchAll();


            if(isset($_SESSION["role_name"]) &&  $_SESSION["role_name"] == "serikat"){
                $tim = $_SESSION["tim"];
                $stmt = $this->db->prepare("select * from serikat where name = ?");
                $stmt->execute([$tim]);
            }else{
                $stmt = $this->db->prepare("select * from serikat");
                $stmt->execute();

            }
            $dataSerikat = $stmt->fetchAll();
            include "view/serikat/anggota-serikat/index.php";
        }
        public function create()  {
            $stmt = $this->db->prepare("select * from serikat");
            $stmt->execute();
            $serikats = $stmt->fetchAll();

            
            $stmt = $this->db->prepare("select * from units");
            $stmt->execute();
            $units = $stmt->fetchAll();
            include "view/serikat/anggota-serikat/create.php";
        }
        public function store()  {
            if($_SERVER["REQUEST_METHOD"]  == "POST"){
                if (!CSRF::validateToken($_POST['csrf_token'])) {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid CSRF token!'];
                    header("Location: index.php?page=anggota-serikat");
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
                        'message' => 'Nomor KTA harus diisi dan antara 2 hingga 20 karakter.'
                    ],
                    'serikatId' => [
                        'validator' => v::stringType()->notEmpty(),
                        'message' => 'Serikat harus diisi.'
                    ],
                ]; 

                $dataValidate = Validation::ValidatorInput($fields,"index.php?page=anggota-serikat");
                
                    $createdAt = date('Y-m-d H:i:s');
                    $updatedAt = date('Y-m-d H:i:s');
                
                    $query = "INSERT INTO anggota_serikats ( name, nip,unitId,membership,noKta,serikatId, createdAt, updateAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $this->db->prepare($query);

                    $success = $stmt->execute([$dataValidate['name'], $dataValidate['nip'],$unitId,$dataValidate['membership'], $dataValidate['noKta'],$dataValidate['serikatId'],$createdAt, $updatedAt]);
                    if ($success) {
                        $_SESSION['message'] = ['type' => 'success', 'text' => 'Sukses membuat Serikat '];
                        header('Location: index.php?page=anggota-serikat');
                        exit;
                    } else {
                        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal membuat Serikat '];
                        header('Location: index.php?page=anggota-serikat-create');
                        exit;
                    }
                }
        }

        public function edit(Int $id)  {
            $stmt = $this->db->prepare("select id, name as nama_serikat from serikat");
            $stmt->execute();
            $serikats = $stmt->fetchAll();

            $stmt = $this->db->prepare("select * from anggota_serikats where id = ?");
            $stmt->execute([$id]);
            $aSerikat = $stmt->fetch();
            
            $stmt = $this->db->prepare("SELECT id, name as nama_unit FROM units");
            $stmt->execute();
            $units =$stmt->fetchAll();
            include "view/serikat/anggota-serikat/edit.php";
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
                        'message' => 'Nomor KTA harus diisi dan antara 2 hingga 20 karakter.'
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
        public function pdf($id)  {
            $stmt = $this->db->prepare("select ase.* , u.id , u.name as unit_name , s.id ,s.name as serikat_name from anggota_serikats ase
            join serikat s on s.id = ase.serikatId 
            join units u on u.id = ase.unitId
            where ase.serikatId = ? ");
            $stmt->execute([$id]);
            $anggotaSerikat = $stmt->fetchAll();
            $html = '<h2 style="font-family: Arial, sans-serif;text-align:center;">Daftar Anggota Serikat: ' . htmlspecialchars($anggotaSerikat[0]['serikat_name']) . '</h2>';
            $html .= '<table border="1" style="font-family: Arial, sans-serif; width: 100%; table-layout: fixed;" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 16.66%;">No</th>
                            <th style="width: 16.66%;">Nama Anggota</th>
                            <th style="width: 16.66%;">Nama Unit</th>
                            <th style="width: 16.66%;">No Nip</th>
                            <th style="width: 16.66%;">Keanggotaan</th>
                            <th style="width: 16.66%;">No Kta</th>
                        </tr>
                    </thead>
                    <tbody>';

            $no = 1;
            foreach ($anggotaSerikat as $anggota) {
            $html .= '<tr>
                        <td style="width: 16.66%;text-align:center;">' . $no++ . '</td>
                        <td style="width: 16.66%;text-align:center;">' . htmlspecialchars($anggota['name']) . '</td>
                        <td style="width: 16.66%;text-align:center;">' . htmlspecialchars($anggota['unit_name']) . '</td>
                        <td style="width: 16.66%;text-align:center;">' . htmlspecialchars($anggota['nip']) . '</td>
                        <td style="width: 16.66%;text-align:center;">' . htmlspecialchars($anggota['membership']) . '</td>
                        <td style="width: 16.66%;text-align:center;">' . htmlspecialchars($anggota['noKta']) . '</td>
                    </tr>';
                }

            $html .= '</tbody></table>';

            $options = new Options();
            
            $pdf = new Dompdf($options);

            $pdf->loadHtml($html);
            $pdf->setPaper("a4","landScape");
            $pdf->render();
            $pdf->stream("Anggota_Serikat_" . time() . ".pdf", array("Attachment" => 0));
        }  
        public function excel()  {
            if (isset($_FILES['file']['tmp_name'])) {
                $file = $_FILES['file']['tmp_name'];
            
                try {
                    // Load file Excel
                    $spreadsheet = IOFactory::load($file);
                    $worksheet = $spreadsheet->getActiveSheet();
            
                    // Loop melalui setiap baris di Excel
                    foreach ($worksheet->getRowIterator() as $rowIndex => $row) {
                        if ($rowIndex == 1) {
                            // Lewati header (baris pertama)
                            continue;
                        }
            
                        // Ambil data dari kolom
                        $id = $worksheet->getCell('A' . $rowIndex)->getValue();
                        $name = $worksheet->getCell('B' . $rowIndex)->getValue();
                        $unitId = $worksheet->getCell('C' . $rowIndex)->getValue();
                        $nip = $worksheet->getCell('D' . $rowIndex)->getValue();
                        $membership = $worksheet->getCell('E' . $rowIndex)->getValue();
                        $noKta = $worksheet->getCell('F' . $rowIndex)->getValue();
                        $serikatId = $worksheet->getCell('G' . $rowIndex)->getValue();
                        $createdAt = date('Y-m-d H:i:s');
                        $updatedAt = date('Y-m-d H:i:s');
                
            
                        // Simpan ke database
                        $stmt = $this->db->prepare("INSERT INTO anggota_serikats (id, name, unitId , nip , membership , noKta , serikatId , createdAt , updateAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $success = $stmt->execute([$id, $name, $unitId ,$nip , $membership , $noKta , $serikatId , $createdAt , $updatedAt]);
                    }
                    if($success){
                        $_SESSION["message"] = ["type"=>"success","text"=>"Sukses Mengimport Excel"];
                        header("location:index.php?page=anggota-serikat");
                        exit;
                    }else{
                        $_SESSION["message"] = ["type"=>"error","text"=>"Gagal Mengimport Excel"];
                        header("location:index.php?page=anggota-serikat");
                        exit;
                    }
                    
                } catch (Exception $e) {
                    $_SESSION["message"] = ["type"=>"error","text"=>$e->getMessage()];
                    header("location:index.php?page=anggota-serikat");
                    exit;
                }
            } else {
                echo "No file uploaded!";
            }
        }
        
    }
    

?>