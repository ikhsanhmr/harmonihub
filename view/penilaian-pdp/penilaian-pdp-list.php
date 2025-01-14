<?php

use Helpers\AlertHelper;

ob_start(); // Mulai output buffering

if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo AlertHelper::showAlert('error', 'Gagal Export PDF!', 'Semua inputan filter wajib disini');
} elseif (isset($_GET['error']) && $_GET['error'] == 2) {
    echo AlertHelper::showAlert('error', 'Extensi file tidak sesuai!', 'extensi harus berupa xlsx, xls, atau csv');
} elseif (isset($_GET['error']) && $_GET['error'] == 3) {
    echo AlertHelper::showAlert('error', 'Gagal import Excell', 'Perikasa file excell anda');
} elseif (isset($_GET['error']) && $_GET['error'] == 4) {
    echo AlertHelper::showAlert('warning', 'Import data sebagian gagal', 'Ada nama pegawai yang tidak ada di database');
} elseif (isset($_GET['success']) && $_GET['success'] == 1) {
    echo AlertHelper::showAlert('error', 'Import Berhasil', 'Berhasil import data', 1000000);
}


?>

<div class="content-wrapper">
    <?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $messageClass = $message['type'] == 'success' ? 'alert-success' : 'alert-danger';
    ?>
        <div class="alert <?= $messageClass; ?>" role="alert">
            <?= $message['text']; ?>
        </div>
    <?php
        unset($_SESSION['message']);
    }
    ?>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Penilaian PDP</h4>
                    <div style="float: right">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#importExcell">Import Excel</button>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exportPDF">Export PDF</button>
                        <!-- <a href="index.php?page=penilaian-pdp-exportpdf" class="btn btn-info btn-sm" target="_blank">Export PDF</a> -->
                        <a href="index.php?page=penilaian-pdp-create" class="btn btn-success btn-sm">
                            Tambah Data
                        </a>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Nama Pegawai</th>
                                    <th>NIP Pegawai</th>
                                    <th>Unit</th>
                                    <th>Peran</th>
                                    <th>Tidak Tercantum <br>pada KPI</th>
                                    <th>Bukan Uraian <br>Jabatan</th>
                                    <th>Hasil Verifikasi <br>(Ya/Tidak)</th>
                                    <th>Semester</th>
                                    <th>Nilai</th>
                                    <th>Tanggal</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($pdps as $index => $pdp) {
                                ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td><?= htmlspecialchars($pdp['user_name']); ?></td>
                                        <td><?= htmlspecialchars($pdp['user_nip']); ?></td>
                                        <td><?= htmlspecialchars($pdp['unit_name']); ?></td>
                                        <td><?= htmlspecialchars($pdp['peran']); ?></td>
                                        <td><?= htmlspecialchars($pdp['kpi']); ?></td>
                                        <td><?= htmlspecialchars($pdp['uraian']); ?></td>
                                        <td><?= htmlspecialchars($pdp['hasil_verifikasi']); ?></td>
                                        <td><?= htmlspecialchars($pdp['semester']); ?></td>
                                        <td><?= htmlspecialchars($pdp['nilai']); ?></td>
                                        <td><?= date('d-m-Y', strtotime($pdp['tanggal'])); ?></td>
                                        <td>
                                            <a href="index.php?page=penilaian-pdp-edit&id=<?= $pdp['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="index.php?page=penilaian-pdp-delete&id=<?= $pdp['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this data?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exportPDF" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel-2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-2">Filter Data</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php?page=penilaian-pdp-exportpdf" method="POST" target="_blank">
                    <!-- <form action=""> -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit_id">Unit</label>
                                <select class="form-control form-control-sm" id="unit_id" name="unit">
                                    <option value="" selected disabled>Pilih Unit</option>
                                    <?php foreach ($units as $unit): ?>
                                        <option value="<?= $unit['id']; ?>"><?= $unit['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="semester">Semseter</label>
                                <select name="semester" id="semester" class="form-control form-control-sm" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="surat_keputusan">Surat Keputusan</label>
                                <input type="text" class="form-control form-control-sm" name="surat_keputusan" id="surat_keputusan" required>
                            </div>
                            <div class="form-group">
                                <label for="status_penugasan">Status Penugasan</label>
                                <select type="text" class="form-control form-control-sm" name="status_penugasan" id="status_penugasan" required>
                                    <option value="">Pilih Status Penugasan</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Belum Selesai">Belum Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="strat_tgl_penilaian">Tanggal Mulai</label>
                                <input type="date" class="form-control form-control-sm" name="start_date" id="strat_tgl_penilaian" required>
                            </div>
                            <div class="form-group">
                                <label for="end_tgl_penilaian">Tanggal Akhir</label>
                                <input type="date" class="form-control form-control-sm" name="end_date" id="end_tgl_penilaian" required>
                            </div>
                            <div class="form-group">
                                <label for="judul_penugasan">Judul Penugasan</label>
                                <input type="text" class="form-control form-control-sm" name="judul_penugasan" id="judul_penugasan" required>
                            </div>
                            <div class="form-group">
                                <label for="strategis_perusahaan">Strategis Perusahaan</label>
                                <select id="strategis_perusahaan" class="form-control form-control-sm" name="strategis_perusahaan" required>
                                    <option value="">Pilih Strategis Perusahaan</option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-sm" target="_blank">Export</button>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Ends -->
<!-- Import Excell Mulai -->
<div class="modal fade" id="importExcell" tabindex="-1" role="dialog"
    aria-labelledby="importExcell" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importExcell">Import Excel</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php?page=penilaian-pdp-importexcell" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                       <span><a href="/harmoni/view/penilaian-pdp/excel.xlsx">Download Format Import</a></span> 
                    </div>
                    <div class="form-group">
                        <label for="importExcell">Import Excel</label>
                        <input type="file" name="excel_file" class="form-control form-control-sm" id="importExcell" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm">Import</button>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Import Excell Selesai -->

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama