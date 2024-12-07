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
                    <p class="card-title">Tambah Data Tim</p>
                    <form class="forms-sample" action="index.php?page=tim/store" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
                        <div class="form-group">
                            <label for="nip_pewagai">NIP Pegawai</label>
                            <input type="number" class="form-control" name="nip_pegawai" id="nip_pewagai" placeholder="NIP Pegawai...">
                        </div>

                        <div class="form-group">
                            <label for="nama_pewagai">Nama Pegawai</label>
                            <input type="text" class="form-control" name="nama_pegawai" id="nama_pewagai" placeholder="Nama Pegawai...">
                        </div>
                        <div class="form-group">
                            <label for="peran">Peran</label>
                            <select class="form-control" id="peran" name="peran">
                                <option>Pilih Tim</option>
                                <option value="Anggota">Anggota</option>
                                <option value="Ketua">Ketua</option>
                                <option value="Ketua DPC">Ketua DPC</option>
                                <option value="Sekretaris">Sekretaris</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="unit">Unit</label>
                            <select class="form-control" id="unit" name="unitId">
                                <option>Pilih Unit</option>
                                <?php foreach ($units as $unit): ?>
                                    <option value="<?php echo $unit['id']; ?>"><?php echo $unit['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">simpan</button>
                            <a href="index.php?page=tim" class="btn btn-warning btn-sm">kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    $content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
    include 'view/layouts/main.php'; // Sertakan layout utama