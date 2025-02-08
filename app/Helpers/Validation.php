<?php

namespace Helpers;

use Exception;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
final class Validation 
{

    public static function ValidatorVideo($file, string $uploadDir, string $redirectPage): string 
    {
        $filePath = null;
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'File harus di-upload.'];
            header("Location: $redirectPage");
            exit;
        }

        $fileName = $file['name'];
        $fileTmpPath = $file['tmp_name'];
        $fileSize = $file['size'];

        $allowedExtensions = ['avi',"mpg", 'mp4',"mkv","webm","ts"];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => "Jenis file tidak valid. Hanya diperbolehkan: " . implode(', ', $allowedExtensions)];
            header("Location: $redirectPage");
            exit;
        }

        $maxFileSize = 500 * 1024 * 1024; // 100MB
        if ($fileSize > $maxFileSize) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Maksimal ukuran video 500MB.'];
            header("Location: $redirectPage");
            exit;
        }

        $filePath = $uploadDir . uniqid() . '_' . $fileName;

        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }
        if (!move_uploaded_file($fileTmpPath, $filePath)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal meng-upload file.'];
            header("Location: $redirectPage");
            exit;
        }

        return $filePath;
    }
    public static function ValidatorFile($file, string $uploadDir, string $redirectPage): string 
    {
        $filePath = null;
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'File harus di-upload.'];
            header("Location: $redirectPage");
            exit;
        }

        $fileName = $file['name'];
        $fileTmpPath = $file['tmp_name'];
        $fileSize = $file['size'];

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'avif','svg'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => "Jenis file tidak valid. Hanya diperbolehkan: " . implode(', ', $allowedExtensions)];
            header("Location: $redirectPage");
            exit;
        }

        $maxFileSize = 100 * 1024 * 1024; // 100MB
        if ($fileSize > $maxFileSize) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Maksimal ukuran file 100MB.'];
            header("Location: $redirectPage");
            exit;
        }

        $filePath = $uploadDir . uniqid() . '_' . $fileName;

        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }
        if (!move_uploaded_file($fileTmpPath, $filePath)) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal meng-upload file.'];
            header("Location: $redirectPage");
            exit;
        }

        // die(var_dump("file tidak ada", $filePath));

        return $filePath;
    }


    public static function ValidatorInput($fields,string $redirectPage) {
        $validatedData = [];
        foreach ($fields as $name => $field) {
            try {
                 $data = $_POST[$name] ?? "";
                 $field["validator"]->assert($data);
                 $validatedData[$name] = $data;
            } catch (NestedValidationException $e) {
                $_SESSION["message"] = ["type"=>"error","text"=> $field["message"] ?? $e->getMessage()];
                header("Location: $redirectPage");
                exit;
            }
        }
        return $validatedData;
        
    }
}
?>
