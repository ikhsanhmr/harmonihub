<?php

ob_start(); // Mulai output buffering
$rolename = isset($_SESSION["role_name"]) ? $_SESSION["role_name"] : null
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-6 offset-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit BA Pembentukan</h4>

                    <form action="index.php?page=ba-pembentukan-update&id=<?php echo $ba['id']; ?>" method="POST" enctype="multipart/form-data">
                    <?php if($rolename == "admin"){?>
                        <div class="form-group">
                                    <label for="unit_id">Unit</label>
                                    <select class="form-control" id="unit_id" name="unit_id" required>
                                        <option value="" selected disabled>Pilih Unit</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?php echo $unit['id']; ?>" <?php echo $unit["name"] == $ba["unit_name"] ? 'selected':''?>><?php echo $unit['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                        </div>
                       <?php }?>
                       <div class="form-group">
                            <label for="no_ba">Nomor Berita Acara</label>
                            <input type="text" class="form-control" name="no_ba" value="<?php echo htmlspecialchars($ba['no_ba']); ?>" id="no_ba" required>
                        </div>
                        <div class="form-group">
                            <label for="dokumen">Dokumen Berita Acara</label>
                            <?php if (!empty($ba['dokumen'])): ?>
                                <p>Dokumen saat ini: <a href="uploads/dokumen/<?= htmlspecialchars($ba['dokumen']); ?>" target="_blank">Lihat Dokumen</a></p>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="dokumen" id="dokumen" accept=".pdf">
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal Acara</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" required>
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