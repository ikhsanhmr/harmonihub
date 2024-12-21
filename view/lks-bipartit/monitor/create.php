<?php

ob_start(); // Mulai output buffering

?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Monitoring LKS Bipartit</h4>

                    <form action="index.php?page=monitor-store" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit_id">Unit</label>
                                    <select class="form-control" id="unit_id" name="unit_id" required>
                                        <option value="" selected disabled>Pilih Unit</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?php echo $unit['id']; ?>"><?php echo $unit['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ba_id">Ba Pembentukan</label>
                                    <select class="form-control" id="ba_id" name="ba_id" required>
                                        <option value="" selected disabled>Pilih Ba Pembentukan</option>
                                        <?php foreach ($ba_pembentukans as $ba): ?>
                                            <option value="<?php echo $ba['id']; ?>"><?php echo $ba['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php foreach ($serikats as $serikat):?>
                                    <div class="form-group">
                                        <label for="ba_id"><?php echo $serikat["name"]?></label>
                                        <input type="number" class="form-control" 
                                            name="serikat[<?php echo $serikat['id']; ?>]" 
                                            id="serikat_<?php echo $serikat['id']; ?>" 
                                            min="0" placeholder="Masukkan jumlah anggota" required>
                                </div>
                                <?php endforeach?>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=monitor" class="btn btn-warning btn-sm">
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