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
                    <h4 class="card-title">Update Info Siru</h4>
                    <form action="index.php?page=info-siru-update&id=<?php echo $infoSiru["id"];?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
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
                                        <option value="" disabled>Pilih Type</option>
                                            <option value="flyer" <?php echo($infoSiru["type"]) === "flyer" ?"selected":"";?>>Flyer</option>
                                            <option value="video" <?php echo($infoSiru["type"]) === "video" ?"selected":"";?>>video</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label style="display: block;" for="filePath">Upload Konten</label>
                                    <?php if ($infoSiru['filePath']): ?>
                                                    <?php if($infoSiru["type"]=== "video"):?>
                                                        <p>konten saat ini :</p>
                                                        <video class="card-img-top" controls height="100" width="20">
                                                            <source src="<?php echo $infoSiru['filePath']; ?>" type="video/mp4">
                                                            <source src="<?php echo $infoSiru['filePath']; ?>" type="video/webm">
                                                            <source src="<?php echo $infoSiru['filePath']; ?>" type="video/ogg">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <?php else:?>
                                                            <p>konten saat ini :</p>
                                                            <img style="width: 10rem; height:auto;border-radius:0px;position:relative;left: 50%; transform: translateX(-50%);" src="<?php echo $infoSiru['filePath']; ?>" alt="Profile Picture" data-bs-toggle="modal" data-bs-target="#flyerModal-<?php echo $infoSiru["id"];?>">
                                                            <div class="modal fade" id="flyerModal-<?php echo $infoSiru["id"];?>" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="<?php echo $infoSiru['filePath']; ?>" style="width: auto; height:auto;border-radius:0px;position:relative;left: 50%; transform: translateX(-50%);" class="img-fluid" alt="Flyer">
                                                                </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif?>
                                                   
                                                <?php else: ?>
                                                    <span>No Picture</span>
                                                <?php endif; ?>
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