<?php

ob_start(); // Mulai output buffering
$rolename = isset($_SESSION["role_name"]) ? $_SESSION["role_name"] : null
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-6 offset-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Dokumen Susunan Pengurus</h4>

                    <form action="index.php?page=dsp-store" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                       <?php if($rolename == "admin"){?>
                        <div class="form-group">
                                    <label for="id_serikat">Serikat</label>
                                    <select class="form-control" id="id_serikat" name="id_serikat" required>
                                        <option value="" selected disabled>Pilih Serikat</option>
                                        <?php foreach ($serikats as $serikat): ?>
                                            <option value="<?php echo $serikat['id']; ?>"><?php echo $serikat['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                        </div>
                       <?php }?>
                       
                        
                        <div class="form-group">
                            <label for="dokumen">Dokumen</label>
                            <input type="file" class="form-control" name="dokumen" id="dokumen" accept=".pdf" required>
                        </div>
                        
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=dsp" class="btn btn-warning btn-sm">
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