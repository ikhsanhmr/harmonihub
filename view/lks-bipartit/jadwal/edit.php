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
                    <form class="forms-sample" action="index.php?page=jadwal/update&id=<?php echo $jadwal['id']; ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($tema['id']) ?>">
                        <div class="form-group">
                            <label for="temaId">Tema</label>
                            <select class="form-control" id="temaId" name="temaId" required>
                                <?php foreach ($temas as $tema): ?>
                                    <option value="<?php echo $tema['id']; ?>" <?php echo $tema['id'] == $jadwal['temaId'] ? 'selected' : ''; ?>><?php echo $tema['namaTema']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="unitId">Unit</label>
                            <select class="form-control" id="unitId" name="unitId" required>
                                <?php foreach ($units as $unit): ?>
                                    <option value="<?php echo $unit['id']; ?>" <?php echo $unit['id'] == $jadwal['unitId'] ? 'selected' : ''; ?>><?php echo $unit['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jadwal">Jadwal</label>
                            <input type="text" class="form-control" id="jadwal" name="namaAgenda" value="<?php echo htmlspecialchars($jadwal['namaAgenda']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_start" value="<?php echo htmlspecialchars($jadwal['tanggal_start']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_end" value="<?php echo htmlspecialchars($jadwal['tanggal_end']); ?>" required>
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