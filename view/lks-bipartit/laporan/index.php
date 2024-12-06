<?php
ob_start();
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
                    <h4 class="card-title">Laporan LKS Bipartit</h4>
                    <div class="mb-4">
                        <!-- Form Filter -->
                        <form method="GET" action="">
                            <div class="row">
                            <input type="hidden" name="page" value="laporan-list">
                                <div class="col-md-5">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                           value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>">
                                </div>
                                <div class="col-md-5">
                                    <label for="end_date">Tanggal Selesai</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                           value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div style="float: right">
                        <a href="index.php?page=laporan-create" class="btn btn-success btn-sm">Tambah Data</a>
                        <a href="index.php?page=export-pdf&start_date=<?= $_GET['start_date'] ?? '' ?>&end_date=<?= $_GET['end_date'] ?? '' ?>" class="btn btn-info btn-sm">Export PDF</a>
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
                                    <th>Tanggal <br> Tindak Lanjut</th>
                                    <th>Uraian <br> Tindak Lanjut</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($laporans)) : ?>
                                    <?php foreach ($laporans as $index => $laporan) : ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($laporan['unit_name']); ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($laporan['tanggal'])); ?></td>
                                            <td><?php echo htmlspecialchars($laporan['topik_bahasan']); ?></td>
                                            <td><?php echo htmlspecialchars($laporan['latar_belakang']); ?></td>
                                            <td><?php echo htmlspecialchars($laporan['rekomendasi']); ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($laporan['tanggal_tindak_lanjut'])); ?></td>
                                            <td><?php echo htmlspecialchars($laporan['uraian_tindak_lanjut']); ?></td>
                                            <td class="text-center">
                                                <a href="index.php?page=laporan-edit&id=<?php echo $laporan['id']; ?>" 
                                                   class="btn btn-warning btn-sm">Edit</a>
                                                <a href="index.php?page=laporan-delete&id=<?php echo $laporan['id']; ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('Are you sure you want to delete this data?')">Delete</a>
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
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'view/layouts/main.php';
