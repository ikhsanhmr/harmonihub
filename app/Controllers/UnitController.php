<?php

namespace Controllers;

use Helpers\Validation;
use Libraries\Database;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
class UnitController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }
    
    public function index(){
        if(isset($_SESSION["role_name"]) && $_SESSION["role_name"] == "unit"){
            $name = $_SESSION["tim"];
            $sql = "SELECT id, name,manager_unit
                                    FROM units where name = ?
                                    ORDER BY createdAt DESC;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$name]);
        }else{
            $sql = 'SELECT id, name,manager_unit
                                    FROM units
                                    ORDER BY createdAt DESC;';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }
        $units = $stmt->fetchAll();

        include 'view/unit/index.php';
    }

    public function create()
    {
        include 'view/unit/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $fields = [
                "name"=>[
                    'validator' => v::stringType()->notEmpty()->length(3, 40),
                    'message' => 'Nama harus diisi dan antara 3 hingga 40 karakter.'
                ],
                "manager_unit"=>[
                    'validator' => v::stringType()->notEmpty(),
                    'message' => 'manager Unit harus diisi.'
                ]
                ];
            $dataValidate = Validation::ValidatorInput($fields,"index.php?page=unit-create");

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            $query = "INSERT INTO units (name,manager_unit, createdAt, updateAt) 
                      VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$dataValidate["name"],$dataValidate["manager_unit"], $createdAt, $updatedAt]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Unit created successfully!'];

                header('Location: index.php?page=unit-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to create Unit!'];

                header('Location: index.php?page=unit-create');
            }
        }
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM units WHERE id = ?");
        $stmt->execute([$id]);
        $unit = $stmt->fetch();

        include 'view/unit/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fields = [
                "name"=>[
                    'validator' => v::stringType()->notEmpty()->length(3, 40),
                    'message' => 'Nama harus diisi dan antara 3 hingga 40 karakter.'
                ],
                "manager_unit"=>[
                    'validator' => v::stringType()->notEmpty(),
                    'message' => 'manager Unit harus diisi.'
                ]
                ];
            $dataValidate = Validation::ValidatorInput($fields,"index.php?page=unit-create");

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE units SET name = ?,manager_unit=?, updateAt = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$dataValidate["name"],$dataValidate["manager_unit"], $updatedAt, $id]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Unit updated successfully!'];

                header('Location: index.php?page=unit-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update Unit!'];

                header('Location: index.php?page=unit-edit');
            }
        }
    }

    public function destroy($id)
    {
        $stmt = $this->db->prepare("DELETE FROM units WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Unit deleted successfully!'];

            header('Location: index.php?page=unit-list');
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to delete Unit!'];

            header('Location: index.php?page=unit-list');
        }
    }
}