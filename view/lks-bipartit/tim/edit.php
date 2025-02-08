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
                    <form class="forms-sample" action="index.php?page=tim/update&id=<?= $tim['id']; ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($tim['id']) ?>">
                        <div class="form-group">
                            <label for="nip_pegawai">NIP Pegawai</label>
                            <input type="number" class="form-control" id="nip_pegawai" name="nip_pegawai" value="<?= htmlspecialchars($tim['nip_pegawai']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_pegawai">Nama Pegawai</label>
                            <input type="text" class="form-control" id="nama_pegawai" name="nama_pegawai" value="<?= htmlspecialchars($tim['nama_pegawai']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="peran">Peran</label>
                            <select class="form-control" id="peran" name="peran" required>
                                <!-- Hanya menampilkan opsi dari daftar jika bukan duplikat -->
                                <?php
                                $perans = ['Anggota', 'Ketua', 'Ketua DPC', 'Sekretaris'];
                                foreach ($perans as $peran) {
                                    // Periksa apakah peran sudah terpilih sebelumnya
                                    if ($tim['peran'] === $peran) {
                                        echo "<option value='" . htmlspecialchars($peran) . "' selected>" . htmlspecialchars($peran) . "</option>";
                                    } else {
                                        echo "<option value='" . htmlspecialchars($peran) . "'>" . htmlspecialchars($peran) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="unitId">Unit</label>
                            <select class="form-control" id="unitId" name="unitId" required>
                                <?php foreach ($units as $unit): ?>
                                    <option value="<?= $unit['id']; ?>" <?= $unit['id'] == $tim['unitId'] ? 'selected' : ''; ?>><?= $unit['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2 btn-sm">Update</button>
                        <a href="index.php?page=tim" class="btn btn-warning btn-sm">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    $content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
    include 'view/layouts/main.php'; // Sertakan layout utama