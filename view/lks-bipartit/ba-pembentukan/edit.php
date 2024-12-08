<?php

ob_start(); // Mulai output buffering

?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-6 offset-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah BA Pembentukan</h4>

                    <form action="index.php?page=ba-pembentukan-update&id=<?php echo $ba['id']; ?>" method="POST" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="dokumen">Dokumen Berita Acara</label>
                            <?php if (!empty($ba['dokumen'])): ?>
                                <p>Dokumen saat ini: <a href="uploads/dokumen/<?= htmlspecialchars($ba['dokumen']); ?>" target="_blank">Lihat Dokumen</a></p>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="dokumen" id="dokumen" accept=".pdf">
                        </div>
                        <div class="form-group">
                            <label for="name">Keterangan</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($ba['name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=ba-pembentukan-list" class="btn btn-warning btn-sm">
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