<?php

ob_start(); // Mulai output buffering

use Helpers\AlertHelper;

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
                    <h4 class="card-title">Tambah Album</h4>

                    <form action="index.php?page=info-siru-store" method="POST" enctype="multipart/form-data">

                        <div class="row">
                           
                            <div class="col-md-6">
                               
                                <div class="form-group">
                                    <label for="sender">Pengirim</label>
                                    <input type="text" class="form-control" id="sender" 
                                    name="sender" value="<?= $_SESSION["username"]?>" readonly>
                                  
                                </div>
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="" selected disabled>Pilih Type</option>
                                            <option value="flyer">Flyer</option>
                                            <option value="video">Video</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="filePath">filePath</label>
                                    <input type="file" class="form-control" id="filePath" name="filePath">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=info-siru" class="btn btn-warning btn-sm">
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