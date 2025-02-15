<?php

use Helpers\AlertHelper;

ob_start();
if (isset($_SESSION['message'])) {
    $type = $_SESSION["message"]["type"];
    $title = ($type === "error") ? "ERROR" : "SUKSES"; 
    echo AlertHelper::showAlert($type, $title, $_SESSION["message"]["text"],2500);
    unset($_SESSION['message']);
}
?>

<div class="content-wrapper">

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Laporan LKS Bipartit</h4>
                    <div style="float: right">
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                        data-bs-target="#exportPDF">Filter</button>
                        <a href="index.php?page=laporan-create" class="btn btn-success btn-sm">Tambah Data</a>
                    </div>

                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Unit</th>
                                    <th>Tanggal</th>
                                    <th>Topik Bahasan</th>
                                    <th>Latar Belakang</th>
                                    <th>Rekomendasi</th>
                                    <th>Tanggal Tindak Lanjut</th>
                                    <th>Uraian Tindak Lanjut</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($laporans)) : ?>
                                    <?php foreach ($laporans as $index => $laporan) : ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td><?= htmlspecialchars($laporan['unit_name']); ?></td>
                                            <td><?= date('d-m-Y', strtotime($laporan['tanggal'])); ?></td>
                                            <td><?= htmlspecialchars($laporan['topik_bahasan']); ?></td>
                                            <td><?= htmlspecialchars(implode(' ', array_slice(explode(' ', $laporan['latar_belakang']), 0, 7))) . (str_word_count($laporan['latar_belakang']) > 7 ? '...' : ''); ?></td>
                                            <td><?= htmlspecialchars(implode(' ', array_slice(explode(' ', $laporan['rekomendasi']), 0, 7))) . (str_word_count($laporan['rekomendasi']) > 7 ? '...' : ''); ?></td>
                                            <td><?= date('d-m-Y', strtotime($laporan['tanggal_tindak_lanjut'])); ?></td>
                                            <td><?= htmlspecialchars($laporan['uraian_tindak_lanjut']); ?></td>
                                            <td class="text-center">
                                                <a href="index.php?page=laporan-edit&id=<?= $laporan['id']; ?>" 
                                                   class="btn btn-warning btn-sm">Edit</a>
                                                <a href="index.php?page=laporan-delete&id=<?= $laporan['id']; ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('Are you sure you want to delete this data?')">Delete</a>
                                                  <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exportPdfModal">
                                                                Export PDF
                                                            </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?> 
                                <?php else : ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Data tidak ditemukan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- <h1 style="margin-top: 2rem;" class="card-title text-center">Export to PDF</h1>
                    <p class="text-danger text-center">sebelum export , filter Tanggal Jadwal yang akan di print , jika Jadwal hanya dilakukan sehari , maka samakan antara Tanggal mulai dan Tanggal selesai</p>  
                    <form id="form" method="POST" target="_blank" class="d-flex justify-content-center" action="index.php?page=laporan-export-pdf&start_date=<?= $_GET['start_date'] ?? '' ?>&end_date=<?= $_GET['end_date'] ?? '' ?>&unit=<?=$_GET['unit'] ??""?>">
                    <input type="hidden" name="page" value="laporan-list">
                            <div class="row flex-grow-1">
                                <input type="hidden" name="page" value="laporan-list">
                                <div class="col-md-6">
                                    <div style="margin-top: 1rem;" class="col-md-12">
                                        <label for="time_start">Waktu Mulai</label>
                                        <input type="text" name="time_start" id="time_start" class="form-control"
                                            value="<?= htmlspecialchars($_GET['waktu'] ?? ''); ?>">
                                    </div>
                                    <div style="margin-top: 1rem;" class="col-md-12">
                                        <label for="time_end">Waktu Selesai</label>
                                        <input type="text" name="time_end" id="time_end" class="form-control"
                                            value="<?= htmlspecialchars($_GET['waktu'] ?? ''); ?>">
                                    </div>
                                    <div style="margin-top: 1rem;" class="col-md-12">
                                        <label for="place">Tempat</label>
                                        <input type="text" name="place" id="place" class="form-control"
                                            value="<?= htmlspecialchars($_GET['tempat'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="margin-top: 1rem;" class="col-md-12">
                                        <label for="agenda">Agenda</label>
                                        <input type="text" name="agenda" id="agenda" class="form-control"
                                            value="<?= htmlspecialchars($_GET['agenda'] ?? ''); ?>">
                                    </div>
                                    <div style="margin-top: 1rem;" class="col-md-12">
                                        <label for="member">Peserta</label>
                                        <input type="text" name="member" id="member" class="form-control"
                                            value="<?= htmlspecialchars($_GET['peserta'] ?? ''); ?>">
                                    </div>
                                    <div style="margin-top: 2rem;" class="col-md-12">
                                    <button type="submit" style="left: 50%; transform: translateX(-50%);position: relative; margin-top:1rem;" class="btn btn-info">Export PDF</button>
                                    </div>
                                </div>
                               
                            </div>
                    </form> -->
                
<!-- Modal -->
<div class="modal fade" id="exportPdfModal" tabindex="-1" aria-labelledby="exportPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportPdfModalLabel">Export to PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                 <form id="form" method="POST" target="_blank" class="d-flex justify-content-center" action="index.php?page=laporan-export-pdf&start_date=<?= $_GET['start_date'] ?? '' ?>&end_date=<?= $_GET['end_date'] ?? '' ?>&unit=<?=$_GET['unit'] ??""?>">
                    <input type="hidden" name="page" value="laporan-list">
                            <div class="row flex-grow-1">
                                <input type="hidden" name="page" value="laporan-list">
                                <div class="col-md-6">
                                    <div style="margin-top: 1rem;" class="col-md-12">
                                        <label for="time_start">Waktu Mulai</label>
                                        <input type="text" name="time_start" id="time_start" class="form-control"
                                            value="<?= htmlspecialchars($_GET['waktu'] ?? ''); ?>">
                                    </div>
                                    <div style="margin-top: 1rem;" class="col-md-12">
                                        <label for="time_end">Waktu Selesai</label>
                                        <input type="text" name="time_end" id="time_end" class="form-control"
                                            value="<?= htmlspecialchars($_GET['waktu'] ?? ''); ?>">
                                    </div>
                                    <div style="margin-top: 1rem;" class="col-md-12">
                                        <label for="place">Tempat</label>
                                        <input type="text" name="place" id="place" class="form-control"
                                            value="<?= htmlspecialchars($_GET['tempat'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="margin-top: 1rem;" class="col-md-12">
                                        <label for="agenda">Agenda</label>
                                        <input type="text" name="agenda" id="agenda" class="form-control"
                                            value="<?= htmlspecialchars($_GET['agenda'] ?? ''); ?>">
                                    </div>
                                    <div style="margin-top: 1rem;" class="col-md-12">
                                        <label for="member">Peserta</label>
                                        <input type="text" name="member" id="member" class="form-control"
                                            value="<?= htmlspecialchars($_GET['peserta'] ?? ''); ?>">
                                    </div>
                                    <div style="margin-top: 2rem;" class="col-md-12">
                                    <button type="submit" style="left: 50%; transform: translateX(-50%);position: relative; margin-top:1rem;" class="btn btn-info">Export PDF</button>
                                    </div>
                                </div>
                               
                            </div>
                    </form>
                
            </div>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal start -->
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
            <form method="GET" action="">
                            <div class="row">
                            <input type="hidden" name="page" value="laporan-list">
                                <div class="col-md-12">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                           value="<?= htmlspecialchars($_GET['start_date'] ?? ''); ?>">
                                </div>
                                <div style="margin: 1rem 0;" class="col-md-12">
                                    <label for="end_date">Tanggal Selesai</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                           value="<?= htmlspecialchars($_GET['end_date'] ?? ''); ?>">
                                </div>
                              
                            </div>
                            <div style="margin-top: 2rem;" class="row">
                            <div class="col-md-12">
                                <label class=" text-black col-form-label" for="unit">Unit</label>
                                <select class="form-control" id="unit" name="unit" required>
                                        <option value="" selected disabled>Pilih Unit</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?= $unit['id']; ?>" <?= $unit['id'] ==  ($_GET["unit"] ?? '') ? 'selected' : '';?>><?= $unit['name']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                            </div>
                            </div>
                            <div style="margin-top: 2rem;" class="row">
                                <div class="col-md-12 d-flex flex-column align-items-center justify-content-end">
                                    <p class="text-danger">filter unit atau tanggal untuk mempermudah pencarian </p>
                                        <button type="submit" class="col-md-3 btn btn-primary btn-block">Filter</button>
                                    
                                </div>
                            </div>
                        </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Ends -->

<?php
$content = ob_get_clean();
include 'view/layouts/main.php';
