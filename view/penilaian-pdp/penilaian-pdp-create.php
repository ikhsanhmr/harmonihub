<?php

ob_start(); // Mulai output buffering

?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Penilaian PDP</h4>

                    <form action="index.php?page=penilaian-pdp-store" method="POST">

                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="anggota_serikat_id">Pegawai</label>
                                    <select class="form-control" id="anggota_serikat_id" name="anggota_serikat_id" required>
                                        <option value="" selected disabled>Pilih Pegawai</option>
                                        <?php foreach ($anggotas as $anggota): ?>
                                            <option value="<?php echo $anggota['id']; ?>"><?php echo $anggota['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="unit_id">Unit</label>
                                    <select class="form-control" id="unit_id" name="unit_id" required>
                                        <option value="" selected disabled>Pilih Unit</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?php echo $unit['id']; ?>"><?php echo $unit['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="peran">Peran</label>
                                    <input type="text" class="form-control" id="peran" name="peran" placeholder="Masukkan peran" required>
                                </div>
                                <div class="form-group">
                                    <label for="kpi">Tidak Tercantum pada KPI</label>
                                    <select name="kpi" id="kpi" class="form-control" required>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="uraian">Bukan Uraian Jabatan</label>
                                    <select name="uraian" id="uraian" class="form-control" required>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="hasil_verifikasi">Hasil Verifikasi (Ya/Tidak)</label>
                                    <select name="hasil_verifikasi" id="hasil_verifikasi" class="form-control" required>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nilai">Nilai (Maksimal 15 poin)</label>
                                    <input type="number" class="form-control" id="nilai" name="nilai" placeholder="0" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
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