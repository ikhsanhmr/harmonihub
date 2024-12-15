<?php

use Helpers\AlertHelper;

ob_start(); // Mulai output buffering

if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo AlertHelper::showAlert('error', 'Gagal Export PDF!', 'Semua inputan filter wajib disini');
}

?>

<div class="content-wrapper">
    <?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $messageClass = $message['type'] == 'success' ? 'alert-success' : 'alert-danger';
    ?>
        <div class="alert <?php echo $messageClass; ?>" role="alert">
            <?php echo $message['text']; ?>
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
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($pdp['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($pdp['user_nip']); ?></td>
                                        <td><?php echo htmlspecialchars($pdp['unit_name']); ?></td>
                                        <td><?php echo htmlspecialchars($pdp['peran']); ?></td>
                                        <td><?php echo htmlspecialchars($pdp['kpi']); ?></td>
                                        <td><?php echo htmlspecialchars($pdp['uraian']); ?></td>
                                        <td><?php echo htmlspecialchars($pdp['hasil_verifikasi']); ?></td>
                                        <td><?php echo htmlspecialchars($pdp['nilai']); ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($pdp['tanggal'])); ?></td>
                                        <td>
                                            <a href="index.php?page=penilaian-pdp-edit&id=<?php echo $pdp['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="index.php?page=penilaian-pdp-delete&id=<?php echo $pdp['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this data?')">Delete</a>
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
                    <div class="form-group">
                        <label for="unit_id">Unit</label>
                        <select class="form-control" id="unit_id" name="unit">
                            <option value="" selected disabled>Pilih Unit</option>
                            <?php foreach ($units as $unit): ?>
                                <option value="<?php echo $unit['id']; ?>"><?php echo $unit['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="strat_tgl_penilaian">Tanggal Mulai</label>
                        <input type="date" class="form-control form-control-sm" name="start_date" id="strat_tgl_penilaian">
                    </div>
                    <div class="form-group">
                        <label for="end_tgl_penilaian">Tanggal Akhir</label>
                        <input type="date" class="form-control form-control-sm" name="end_date" id="end_tgl_penilaian">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-info btn-sm" target="_blank">Export</button>
                        <!-- <a href="index.php?page=penilaian-pdp-exportpdf" class="btn btn-info btn-sm" target="_blank">Export</a> -->
                        <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Ends -->

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama