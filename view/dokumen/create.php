<?php

use Helpers\AlertHelper;

ob_start(); // Mulai output buffering

if (isset($_GET['gagal']) && $_GET['gagal'] == 1
) {
    echo AlertHelper::showAlert('error', 'Gagal!', 'Gagal menambahkan data!.');
}
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Dokumen</h4>
                    <form action="index.php?page=dokumen/store" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xl-6 col-md-12">
                                    <label for="nama_dokumen">Nama Dokumen</label>
                                    <input type="text" class="form-control" name="nama_dokumen" id="nama_dokumen" placeholder="Masukan nama dokumen" required>
                                </div>
                                <div class="col-xl-6 col-md-12">
                                        <label for="file_dokumen">File Dokumen</label>
                                        <input type="file" class="form-control" name="file_dokumen" id="file_dokumen" placeholder="Masukan nama dokumen" accept=".pdf,.doc,.docx"  required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan keterangan" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=dokumen" class="btn btn-warning btn-sm">
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