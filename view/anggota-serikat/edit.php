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
                    <h4 class="card-title">Edit Data Serikat Pekerja</h4>
                    <form action="index.php?page=anggota-serikat-update&id=<?php echo $anggotaSerikat['id']; ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                        <div class="row">
                           
                            <div class="col-md-6">
                               
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" id="name" 
                                    name="name" value="<?php echo $anggotaSerikat["name"]?>" placeholder="Masukkan Nama">
                                  
                                </div>
                                <div class="form-group">
                                    <label for="unitId">Nama Unit</label>
                                    <select class="form-control" id="unitId" name="unitId" required>
                                        <option value="" selected disabled>Pilih Unit</option>
                                        <?php foreach ($units as $unit):?>
                                            <option value="<?php echo $unit['id']; ?>" <?php echo $unit['id'] == $anggotaSerikat['unitId'] ? 'selected' : ''; ?>><?php echo $unit['nama_unit']; ?></option>
                                        <?php endforeach;?>
                                           
                                            
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nip">Nip</label>
                                    <input type="text" class="form-control" id="nip" 
                                    name="nip" value="<?php echo $anggotaSerikat["nip"]?>" placeholder="Masukkan Nip">
                                </div>
                            </div>
                            <div class="col-md-6">
                               
                                <div class="form-group">
                                    <label for="membership">Keanggotaan</label>
                                    <input type="text" class="form-control" id="membership" 
                                    name="membership" value="<?php echo $anggotaSerikat["membership"]?>" placeholder="Masukkan Keanggotaan">
                                </div>
                                <div class="form-group">
                                    <label for="no-kta">Nomor Kta</label>
                                    <input type="text" class="form-control" id="no-kta" 
                                    name="noKta" value="<?php echo $anggotaSerikat["noKta"]?>" placeholder="Masukkan Nomor Kta">
                                </div>
                                <div class="form-group">
                                    <label for="serikatId">Nama Serikat</label>
                                    <select class="form-control" id="serikatId" name="serikatId" required>
                                        <option value="" selected disabled>Pilih Serikat</option>
                                        <?php foreach ($serikats as $serikat):?>
                                            <option value="<?php echo $serikat['id']; ?>" <?php echo $serikat['id'] == $anggotaSerikat['serikatId'] ? 'selected' : ''; ?>><?php echo $serikat['nama_serikat']; ?></option>
                                        <?php endforeach;?>
                                           
                                            
                                    </select>
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