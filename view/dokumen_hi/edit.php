<?php

use Helpers\AlertHelper;

ob_start(); // Mulai output buffering

if (
    isset($_GET['gagal']) && $_GET['gagal'] == 1
) {
    echo AlertHelper::showAlert('error', 'Gagal!', 'Gagal memperbarui data!');
}

?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Dokumen</h4>
                    <!-- membuat form action untuk mengirim data yang tadi dibuat dokumen/update -->
                    <form action="index.php?page=dokumen_hi/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken; ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($dokumen['id']) ?>">
                        <div class="form-group">
                            <label for="nama_dokumen">Nama Dokumen</label>
                            <input type="text" class="form-control" name="nama_dokumen" id="nama_dokumen" placeholder="Masukan nama dokumen" value="<?= htmlspecialchars($dokumen['nama_dokumen']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="link_gdrive">Link Google Drive</label>
                            <input type="text" class="form-control" id="link_gdrive" name="link_gdrive" placeholder="Masukkan link google drive" value="<?= htmlspecialchars($dokumen['link_gdrive']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori Sub Laporan</label>
                            <select name="kategori" id="kategori" class="form-control" required>
                                <option value="1" <?= $dokumen['kategori'] == 1 ? 'selected' : ''; ?>>Laporan Tuntutan Ganti Rugi</option>
                                <option value="2" <?= $dokumen['kategori'] == 2 ? 'selected' : ''; ?>>Laporan PPHI</option>
                                <option value="3" <?= $dokumen['kategori'] == 3 ? 'selected' : ''; ?>>Laporan PDP</option>
                                <option value="4" <?= $dokumen['kategori'] == 4 ? 'selected' : ''; ?>>Laporan LKS Bipartit</option>
                                <option value="5" <?= $dokumen['kategori'] == 5 ? 'selected' : ''; ?>>Laporan Lain-lain</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal Laporan</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= htmlspecialchars($dokumen['tanggal']) ?>" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=dokumen_hi" class="btn btn-warning btn-sm">
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