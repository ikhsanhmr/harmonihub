<?php

ob_start(); // Mulai output buffering

use Helpers\AlertHelper;
use Libraries\CSRF;

if (isset($_SESSION['message'])) {
    $type = $_SESSION["message"]["type"];
    $title = ($type === "error") ? "ERROR" : "SUKSES"; 
    echo AlertHelper::showAlert($type, $title, $_SESSION["message"]["text"]);
    unset($_SESSION['message']);
}
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Serikat</h4>
                    <form action="index.php?page=serikat-store" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
                        <div class="row">
                           
                            <div class="col-md-6">
                               
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" id="name" 
                                    name="name" autocomplete="name" placeholder="Masukkan Nama" require>
                                  
                                </div>
                                <div class="form-group">
                                    <label for="logoPath">Logo</label>
                                    <input type="file" class="form-control" id="logoPath" name="logoPath">
                                </div>
                            </div>
                          
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=serikat" class="btn btn-warning btn-sm">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama