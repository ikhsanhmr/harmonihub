<?php

ob_start(); // Mulai output buffering

?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Jadwal Monitoring LKS Bipartit</h4>

                    <form action="index.php?page=monitor-jadwal-store" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                        <label for="monitor_id">Monitor Id</label>
                                        <input type="text" class="form-control" 
                                            name="monitor_id" 
                                            id="monitor_id" 
                                            value="<?php echo htmlspecialchars($_GET["id"])?>"
                                            readonly>
                                </div>
                                <div class="form-group">
                                    <label for="tema_id">Tema</label>
                                    <select class="form-control" id="tema_id" name="tema_id" required>
                                        <option value="" selected disabled>Pilih Tema</option>
                                        <?php foreach ($temas as $tema): ?>
                                            <option value="<?php echo $tema['id']; ?>"><?php echo $tema['namaTema']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="bulan_id">Bulan</label>
                                    <select class="form-control" id="bulan_id" name="bulan_id" required>
                                        <option value="" selected disabled>Pilih Bulan</option>
                                        <?php foreach ($bulans as $bulan): ?>
                                            <option value="<?php echo $bulan['id']; ?>"><?php echo $bulan['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                        <label for="rekomendasi">Rekomendasi</label>
                                        <input type="text" class="form-control" 
                                            name="rekomendasi" 
                                            id="rekomendasi" 
                                            min="0" placeholder="Masukkan Rekomendasi" required>
                                </div>
                                <div class="form-group">
                                        <label for="tindak_lanjut">Tindak Lanjut</label>
                                        <input type="text" class="form-control" 
                                            name="tindak_lanjut" 
                                            id="tindak_lanjut" 
                                            min="0" placeholder="Masukkan Tindak Lanjut" required>
                                </div>
                             
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                        <label for="evaluasi">Evaluasi</label>
                                        <input type="text" class="form-control" 
                                            name="evaluasi" 
                                            id="evaluasi" 
                                            min="0" placeholder="Masukkan Evaluasi" required>
                                </div>
                            <div class="form-group">
                                        <label for="follow_up">Follow Up Kantor Pusat</label>
                                        <input type="text" class="form-control" 
                                            name="follow_up" 
                                            id="follow_up" 
                                            min="0" placeholder="Masukkan Follow Up" required>
                                </div>
                            <div class="form-group">
                                        <label for="realisasi">Realisasi</label>
                                        <input type="text" class="form-control" 
                                            name="realisasi" 
                                            id="realisasi" 
                                            min="0" placeholder="Masukkan Realisasi" required>
                                </div>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=monitor" class="btn btn-warning btn-sm">
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