<?php
ob_start();

use Helpers\AlertHelper;
use Libraries\CSRF;

if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo AlertHelper::showAlert('error', 'Gagal!', 'Semua kolom harus diisi.');
}
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Atur Jadwal</p>
                    <form class="forms-sample" action="index.php?page=jadwal/store" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
                        <div class="form-group">
                            <label for="tema">Tema</label>
                            <select class="form-control" id="tema" name="temaId">
                                <option>Pilih Tema</option>
                                <?php foreach ($temas as $tema): ?>
                                    <option value="<?= $tema['id']; ?>"><?= $tema['namaTema']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="unit">Unit</label>
                            <select class="form-control" name="unitId" id="unit">
                                <option>Pilih Unit</option>
                                <?php foreach ($units as $unit): ?>
                                    <option value="<?= $unit['id']; ?>"><?= $unit['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tglmulai">Agenda</label>
                            <input type="text" class="form-control" name="namaAgenda" id="tglmulai" placeholder="Agenda...">
                        </div>
                        <div class="form-group">
                            <label for="tglmulai">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_start" id="tglmulai" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="tglakhir">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="tanggal_end" id="tglakhir" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">simpan</button>
                            <a href="index.php?page=jadwal" class="btn btn-warning btn-sm">kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    $content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
    include 'view/layouts/main.php'; // Sertakan layout utama