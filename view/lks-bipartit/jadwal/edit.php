<?php
ob_start();

// Memuat class CSRF untuk menghasilkan token

use Helpers\AlertHelper;
use Libraries\CSRF;

// Ambil token CSRF yang akan digunakan dalam form
$csrfToken = CSRF::generateToken();
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo AlertHelper::showAlert('error', 'Gagal!', 'Semua kolom harus diisi.');
}

?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Update Data</h4>
                    <form class="forms-sample" action="index.php?page=jadwal/update&id=<?= $jadwal['id']; ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($tema['id']) ?>">
                        <div class="form-group">
                            <label for="temaId">Tema</label>
                            <select class="form-control" id="temaId" name="temaId" required>
                                <?php foreach ($temas as $tema): ?>
                                    <option value="<?= $tema['id']; ?>" <?= $tema['id'] == $jadwal['temaId'] ? 'selected' : ''; ?>><?= $tema['namaTema']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="unitId">Unit</label>
                            <select class="form-control" id="unitId" name="unitId" required>
                                <?php foreach ($units as $unit): ?>
                                    <option value="<?= $unit['id']; ?>" <?= $unit['id'] == $jadwal['unitId'] ? 'selected' : ''; ?>><?= $unit['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jadwal">Jadwal</label>
                            <input type="text" class="form-control" id="jadwal" name="namaAgenda" value="<?= htmlspecialchars($jadwal['namaAgenda']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_start" value="<?= htmlspecialchars($jadwal['tanggal_start']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_end" value="<?= htmlspecialchars($jadwal['tanggal_end']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2 btn-sm">Update</button>
                        <a href="index.php?page=jadwal/data" class="btn btn-warning btn-sm">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    $content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
    include 'view/layouts/main.php'; // Sertakan layout utama