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
                    <h4 class="card-title">Edit Serikat</h4>
                    <form action="index.php?page=anggota-serikat-update&id=<?= $aSerikat['id']; ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
                        <div class="row">
                           
                            <div class="col-md-6">
                               
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" id="name" 
                                    name="name" value="<?= $aSerikat["name"]?>" placeholder="Masukkan Nama">
                                  
                                </div>
                                <div class="form-group">
                                    <label for="unitId">Nama Unit</label>
                                    <select class="form-control" id="unitId" name="unitId" required>
                                        <option value="" selected disabled>Pilih Unit</option>
                                        <?php foreach ($units as $unit):?>
                                            <option value="<?= $unit['id']; ?>" <?= $unit['id'] == $aSerikat['unitId'] ? 'selected' : ''; ?>><?= $unit['nama_unit']; ?></option>
                                        <?php endforeach;?>
                                           
                                            
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nip">Nip</label>
                                    <input type="text" class="form-control" id="nip" 
                                    name="nip" value="<?= $aSerikat["nip"]?>" placeholder="Masukkan Nip">
                                </div>
                            </div>
                            <div class="col-md-6">
                               
                                <div class="form-group">
                                    <label for="membership">Keanggotaan</label>
                                    <input type="text" class="form-control" id="membership" 
                                    name="membership" value="<?= $aSerikat["membership"]?>" placeholder="Masukkan Keanggotaan">
                                </div>
                                <div class="form-group">
                                    <label for="no-kta">Nomor Kta</label>
                                    <input type="text" class="form-control" id="no-kta" 
                                    name="noKta" value="<?= $aSerikat["noKta"]?>" placeholder="Masukkan Nomor Kta">
                                </div>
                                <div class="form-group">
                                    <label for="serikatId">Nama Serikat</label>
                                    <select class="form-control" id="serikatId" name="serikatId" required>
                                        <option value="" selected disabled>Pilih Serikat</option>
                                        <?php foreach ($serikats as $serikat):?>
                                            <option value="<?= $serikat['id']; ?>" <?= $serikat['id'] == $aSerikat['serikatId'] ? 'selected' : ''; ?>><?= $serikat['nama_serikat']; ?></option>
                                        <?php endforeach;?>
                                           
                                            
                                    </select>
                                </div>
                               
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=anggota-serikat" class="btn btn-warning btn-sm">
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