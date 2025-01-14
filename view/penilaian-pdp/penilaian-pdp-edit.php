<?php

ob_start(); // Mulai output buffering

?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Data Penilaian PDP</h4>
                    <form action="index.php?page=penilaian-pdp-update&id=<?= $pdp['id']; ?>" method="POST">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="anggota_serikat_id">Pegawai</label>
                                    <select class="form-control" id="anggota_serikat_id" name="anggota_serikat_id" required>
                                        <?php foreach ($anggotas as $anggota): ?>
                                            <option value="<?= $anggota['id']; ?>" <?= $anggota['id'] == $pdp['anggota_serikat_id'] ? 'selected' : ''; ?>><?= $anggota['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="unit_id">Unit</label>
                                    <select class="form-control" id="unit_id" name="unit_id" required>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?= $unit['id']; ?>" <?= $unit['id'] == $pdp['unit_id'] ? 'selected' : ''; ?>><?= $unit['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="peran">Peran</label>
                                    <input type="text" class="form-control" id="peran" name="peran" value="<?= htmlspecialchars($pdp['peran']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="kpi">Tidak Tercantum pada KPI</label>
                                    <select name="kpi" id="kpi" class="form-control" required>
                                        <option value="ya" <?= $pdp['kpi'] == 'ya' ? 'selected' : ''; ?>>Ya</option>
                                        <option value="tidak" <?= $pdp['kpi'] == 'tidak' ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="semester">Semseter</label>
                                    <select name="semester" id="semester" class="form-control" required>
                                        <option value="1" <?= $pdp['semester'] == 1 ? 'selected' : ''; ?>>1</option>
                                        <option value="2" <?= $pdp['semester'] == 2 ? 'selected' : ''; ?>>2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="uraian">Bukan Uraian Jabatan</label>
                                    <select name="uraian" id="uraian" class="form-control" required>
                                        <option value="ya" <?= $pdp['uraian'] == 'ya' ? 'selected' : ''; ?>>Ya</option>
                                        <option value="tidak" <?= $pdp['uraian'] == 'tidak' ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="hasil_verifikasi">Hasil Verifikasi (Ya/Tidak)</label>
                                    <select name="hasil_verifikasi" id="hasil_verifikasi" class="form-control" required>
                                        <option value="ya" <?= $pdp['hasil_verifikasi'] == 'ya' ? 'selected' : ''; ?>>Ya</option>
                                        <option value="tidak" <?= $pdp['hasil_verifikasi'] == 'tidak' ? 'selected' : ''; ?>>Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nilai">Nilai</label>
                                    <input type="number" class="form-control" id="nilai" name="nilai" value="<?= htmlspecialchars($pdp['nilai']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars($pdp['tanggal']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=penilaian-pdp-list" class="btn btn-warning btn-sm">
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