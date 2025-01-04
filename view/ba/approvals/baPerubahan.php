<?php

use Helpers\AlertHelper;
ob_start();

if (isset($_SESSION['message'])) {
    $type = $_SESSION["message"]["type"];
    $title = ($type === "error") ? "ERROR" : "SUKSES"; 
    echo AlertHelper::showAlert($type, $title, $_SESSION["message"]["text"]);
    unset($_SESSION['message']);
}
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Approvals Ba Perubahan</h4>
                   
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Unit Pembuat</th>
                                    <th>Nomor Berita Acara</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                    <th>Dokumen Berita Acara</th>
                                    <th>Status</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($bas as $index => $ba) {
                                ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($ba['unit_name']); ?></td>
                                        <td><?php echo htmlspecialchars($ba['no_ba']); ?></td>
                                        <td><?php echo htmlspecialchars($ba['name']); ?></td>
                                        <td><?php echo $ba["tanggal"] !==null ? date('d-m-Y', strtotime($ba['tanggal'])) :null; ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($ba['dokumen'])): ?>
                                                <a href="uploads/dokumen/<?= htmlspecialchars($ba['dokumen']); ?>" target="_blank">Preview</a>
                                            <?php else: ?>
                                                No Document
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($ba['status']); ?></td>

                                        <td>
                                            <a href="index.php?page=terima-ba-perubahan&id=<?php echo $ba['id']; ?>" class="btn btn-success btn-sm">Terima</a>
                                            <a href="index.php?page=ba-perubahan-delete&id=<?php echo $ba['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tolak Ba perubahan ini?')">Tolak</a>
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

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama