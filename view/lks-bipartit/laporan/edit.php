<?php

ob_start(); // Mulai output buffering

?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Data Laporan LKS Bipartit</h4>
                    <form action="index.php?page=laporan-update&id=<?= $laporan['id']; ?>" method="POST">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ba_perubahan_id">BA Perubahan</label>
                                    <select class="form-control" id="ba_perubahan_id" name="ba_perubahan_id" required>
                                        <?php foreach ($baPerubahans as $baPerubahan): ?>
                                            <option value="<?= $baPerubahan['id']; ?>" <?= $baPerubahan['id'] == $laporan['ba_perubahan_id'] ? 'selected' : ''; ?>><?= $baPerubahan['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars($laporan['tanggal']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="topik_bahasan">Topik Bahasan</label>
                                    <textarea rows="6" cols="20" name="topik_bahasan" id="topik_bahasan" class="form-control"><?= htmlspecialchars($laporan['topik_bahasan']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="latar_belakang">Latar Belakang</label>
                                    <textarea rows="6" cols="20" name="latar_belakang" id="latar_belakang" class="form-control"><?= htmlspecialchars($laporan['latar_belakang']); ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="rekomendasi">Rekomendasi</label>
                                    <textarea rows="6" cols="20" class="form-control" id="rekomendasi" name="rekomendasi" required><?= htmlspecialchars($laporan['rekomendasi']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_tindak_lanjut">Tanggal Tindak Lanjut</label>
                                    <input type="date" class="form-control" id="tanggal_tindak_lanjut" name="tanggal_tindak_lanjut" value="<?= htmlspecialchars($laporan['tanggal_tindak_lanjut']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="uraian_tindak_lanjut">Uraian Tindak Lanjut</label>
                                    <textarea name="uraian_tindak_lanjut" id="uraian_tindak_lanjut" class="form-control"><?= htmlspecialchars($laporan['uraian_tindak_lanjut']); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=laporan-list" class="btn btn-warning btn-sm">
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