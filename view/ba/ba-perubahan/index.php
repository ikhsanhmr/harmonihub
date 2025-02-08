<?php

use Helpers\AlertHelper;
ob_start();

if (isset($_SESSION['message'])) {
    $type = $_SESSION["message"]["type"];
    $title = ($type === "error") ? "ERROR" : "SUKSES"; 
    echo AlertHelper::showAlert($type, $title, $_SESSION["message"]["text"]);
    unset($_SESSION['message']);
}
$rolename = isset($_SESSION["role_name"]) ? $_SESSION["role_name"] : null;

?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data BA perubahan</h4>
                    <div style="float: right">
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                        data-bs-target="#exportPDF">Filter</button>
                        <a href="index.php?page=ba-perubahan-create" class="btn btn-success btn-sm">
                            Tambah Data
                        </a>
                    </div>
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
                                        <td><?= $index + 1; ?></td>
                                        <td><?= htmlspecialchars($ba['unit_name']); ?></td>
                                        <td><?= htmlspecialchars($ba['no_ba']); ?></td>
                                        <td><?= htmlspecialchars($ba['name']); ?></td>
                                        <td><?= $ba["tanggal"] !==null ? date('d-m-Y', strtotime($ba['tanggal'])) :null; ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($ba['dokumen'])): ?>
                                                <a href="uploads/dokumen/<?= htmlspecialchars($ba['dokumen']); ?>" target="_blank">Preview</a>
                                            <?php else: ?>
                                                No Document
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($ba['status']); ?></td>

                                        <td>
                                            <a href="index.php?page=ba-perubahan-edit&id=<?= $ba['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="index.php?page=ba-perubahan-delete&id=<?= $ba['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this data?')">Delete</a>
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
            <form method="GET" action="">
                            <div class="row">
                            <input type="hidden" name="page" value="ba-perubahan-list">
                                <div class="col-md-12">
                                    <label for="start">Tanggal Mulai</label>
                                    <input type="date" name="start" id="start" class="form-control"
                                           value="<?= htmlspecialchars($_GET['start'] ?? ''); ?>">
                                </div>
                                <div style="margin: 1rem 0;" class="col-md-12">
                                    <label for="end">Tanggal Selesai</label>
                                    <input type="date" name="end" id="end" class="form-control"
                                           value="<?= htmlspecialchars($_GET['end'] ?? ''); ?>">
                                </div>
                              
                            </div>
                            <?php if($rolename == "admin"){ ?>
                                <div style="margin-top: 2rem;" class="row">
                                    <div class="col-md-12">
                                        <label class=" text-black col-form-label" for="unit">Unit</label>
                                        <select class="form-control" id="unit" name="unit">
                                                <option value="" selected disabled>Pilih Unit</option>
                                                <?php foreach ($units as $unit): ?>
                                                    <option value="<?= $unit['id']; ?>" <?= $unit['id'] ==  ($_GET["unit"] ?? '') ? 'selected' : '';?>><?= $unit['name']; ?></option>
                                                <?php endforeach; ?>
                                        </select>
                                    </div>
                                    </div>
                            <?php } ?>
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
<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama