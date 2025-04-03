<?php

use Helpers\AlertHelper;

ob_start();
if (isset($_SESSION['message'])) {
    $type = $_SESSION["message"]["type"];
    $title = ($type === "error") ? "ERROR" : "SUKSES";
    echo AlertHelper::showAlert($type, $title, $_SESSION["message"]["text"], 2500);
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
                            data-bs-target="#filterLaporan">Filter</button>
                        <a href="index.php?page=laporan-create" class="btn btn-success btn-sm">Tambah Data</a>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exportPdfModal">
                            Export PDF
                        </button>
                    </div>

                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Import PDF</th>
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
                                <?php if (!empty($laporans)): ?>
                                    <?php foreach ($laporans as $index => $laporan): ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#importPdfModal" data-id="<?= $laporan['id']; ?>">
                                                    Import PDF
                                                </button>
                                            </td>
                                            <td><?= htmlspecialchars($laporan['unit_name']); ?></td>
                                            <td><?= date('d-m-Y', strtotime($laporan['tanggal'])); ?></td>
                                            <td><?= htmlspecialchars($laporan['topik_bahasan']); ?></td>
                                            <td><?= htmlspecialchars(implode(' ', array_slice(explode(' ', $laporan['latar_belakang']), 0, 7))) . (str_word_count($laporan['latar_belakang']) > 7 ? '...' : ''); ?>
                                            </td>
                                            <td><?= htmlspecialchars(implode(' ', array_slice(explode(' ', $laporan['rekomendasi']), 0, 7))) . (str_word_count($laporan['rekomendasi']) > 7 ? '...' : ''); ?>
                                            </td>
                                            <td><?= date('d-m-Y', strtotime($laporan['tanggal_tindak_lanjut'])); ?></td>
                                            <td><?= htmlspecialchars($laporan['uraian_tindak_lanjut']); ?></td>
                                            <td class="text-center">
                                                <a href="index.php?page=laporan-edit&id=<?= $laporan['id']; ?>"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <a href="index.php?page=laporan-delete&id=<?= $laporan['id']; ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this data?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Data tidak ditemukan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Export To PDF -->
<div class="modal fade" id="exportPdfModal" aria-labelledby="exportPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportPdfModalLabel">Export to PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form" method="POST" target="_blank" class="d-flex justify-content-center"
                    action="index.php?page=laporan-export-pdf&start_date=<?= $_GET['start_date'] ?? '' ?>&end_date=<?= $_GET['end_date'] ?? '' ?>&unit=<?= $_GET['unit'] ?? "" ?>">
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
                                <button type="submit"
                                    style="left: 50%; transform: translateX(-50%);position: relative; margin-top:1rem;"
                                    class="btn btn-info">Export PDF</button>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- End Modal Export to PDF -->

<!-- modal Filter -->
<div class="modal fade" id="filterLaporan" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
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
                                    <option value="<?= $unit['id']; ?>" <?= $unit['id'] == ($_GET["unit"] ?? '') ? 'selected' : ''; ?>><?= $unit['name']; ?></option>
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
<!-- End Modal Filter -->

<!-- Modal Import PDF -->
<div class="modal fade" id="importPdfModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importPdf">Import PDF</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php?page=laporan/importPdf" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <span><a href="/harmoni/template/format_lks-bipartit.docx">Download Format Import
                                (docx)</a></span>
                    </div>
                    <div class="form-group">
                        <input type="file" name="pdf_file" id="pdfInput" class="form-control form-control-sm"
                            accept="application/pdf" accesskey="" required>
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
<!-- End Modal Import PDF -->

<script type="text/javascript">

document.addEventListener("DOMContentLoaded", function() {

    // Event listener untuk tombol "Import PDF"
    var importPdfModal = document.getElementById("importPdfModal");

    importPdfModal.addEventListener("show.bs.modal", function(event) {
        var button = event.relatedTarget; // Tombol yang diklik untuk membuka modal
        var id = button.getAttribute("data-id"); // Ambil nilai ID dari tombol

        // Masukkan ID ke dalam form, misalnya dalam input hidden
        var inputHidden = document.createElement("input");
        inputHidden.type = "hidden";
        inputHidden.name = "pdf_id";
        inputHidden.value = id;

        // Masukkan input hidden ke dalam form
        var form = importPdfModal.querySelector("form");
        form.appendChild(inputHidden);
    });

    
    // Cek apakah ada parameter "success" di URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'File PDF berhasil diunggah dan disimpan.',
            showConfirmButton: false,
            timer: 2000
        });
    }
});

document.getElementById("pdfInput").addEventListener("change", function() {
    var file = this.files[0]; // Ambil file pertama yang dipilih
    var maxSize = 5 * 1024 * 1024; // 5MB dalam byte

    if (file && file.size > maxSize) {
        alert("Ukuran file terlalu besar! Maksimal 5MB.");
        this.value = ""; // Kosongkan input file
    }
});
</script>

<?php
$content = ob_get_clean();
include 'view/layouts/main.php';
