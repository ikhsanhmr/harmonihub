<?php

use Helpers\AlertHelper;

ob_start();

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Data baru berhasil ditambahkan.');
} elseif (isset($_GET['success']) && $_GET['success'] == 2) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Data berhasil dihapus.', 1500);
}
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Database Alih Daya</h4>
                    <div style="float: left">
                        <form method="GET" action="index.php?page=dokumen_ad">
                            <input type="hidden" name="page" value="dokumen_ad">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <select name="kategori" id="kategori" class="form-control">
                                            <option value="">Pilih kategori</option>
                                            <option value="1" <?= (isset($_GET['kategori']) && $_GET['kategori'] == '1') ? 'selected' : ''; ?>>Laporan Pemetaan Alih Daya</option>
                                            <option value="2" <?= (isset($_GET['kategori']) && $_GET['kategori'] == '2') ? 'selected' : ''; ?>>Laporan Pengelolaan Alih Daya</option>
                                            <option value="3" <?= (isset($_GET['kategori']) && $_GET['kategori'] == '3') ? 'selected' : ''; ?>>Laporan Lain-lain</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tahun">Tahun</label>
                                        <select name="tahun" id="tahun" class="form-control">
                                            <option value="">Pilih tahun</option>
                                            <?php for ($i = 2021; $i <= date('Y'); $i++) { ?>
                                                <option value="<?= $i; ?>" <?= (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? 'selected' : ''; ?>><?= $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <a href="index.php?page=dokumen_ad" id="filter-link" class="btn btn-sm btn-primary ms-2">
                                        Filter
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div style="float: right">
                        <a href="index.php?page=dokumen_ad/create" class="btn btn-success btn-sm">
                            Tambah Data
                        </a>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Nama</th>
                                    <th>File</th>
                                    <th>Kategori Sub Laporan</th>
                                    <th>Tanggal Laporan</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($dokumens as $index => $dokumen) {
                                ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td><?= htmlspecialchars($dokumen['nama_dokumen']); ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($dokumen['link_gdrive'])): ?>
                                                <!-- <a href="uploads/dokumen/<?= htmlspecialchars($dokumen['file_dokumen']); ?>" target="_blank">Preview</a> -->
                                                <a href="<?= htmlspecialchars($dokumen['link_gdrive']) ?>" target="_blank">Preview</a>
                                            <?php else: ?>
                                                No Document
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            switch ($dokumen['kategori']) {
                                                case 1:
                                                    echo "Laporan Pemetaan Alih Daya";
                                                    break;
                                                case 2:
                                                    echo "Laporan Pengelolaan Alih Daya";
                                                    break;
                                                case 3:
                                                    echo "Laporan Lain-lain";
                                                    break;
                                                default:
                                                    echo "Kategori tidak tersedia"; // Jika nilai kategori tidak dikenal
                                            }
                                            ?>
                                        </td>
                                        <td><?= $dokumen['tanggal']; ?></td>

                                        <td class="d-flex justify-content-center">
                                            <a href="index.php?page=dokumen_ad=edit&id=<?= $dokumen['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="index.php?page=dokumen_ad/delete" id="delete-form-dok-<?= $dokumen['id'] ?>" method="POST">
                                                <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($dokumen['id']) ?>">
                                                <button type="button" class="btn btn-danger btn-sm" id="delete-dok" data-id="<?= $dokumen['id'] ?>">Hapus</button>
                                            </form>
                                            <!-- <a href="index.php?page=dokumen_ad/delete&id=<?= $dokumen['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this data?')">Delete</a> -->
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tahunElement = document.getElementById('tahun');
        const kategoriElement = document.getElementById('kategori');
        const filterLink = document.getElementById('filter-link');

        function updateFilterLink() {
            const tahun = tahunElement.value;
            const kategori = kategoriElement.value;

            let newUrl = 'index.php?page=dokumen_ad';

            if (kategori) {
                newUrl += `&kategori=${kategori}`;
            }

            if (tahun) {
                newUrl += `&tahun=${tahun}`;
            }

            filterLink.href = newUrl;
        }

        tahunElement.addEventListener('change', updateFilterLink);
        kategoriElement.addEventListener('change', updateFilterLink);

        updateFilterLink();
    });


    // Tambahkan Event Listener pada tombol hapus
    document.querySelectorAll('#delete-dok').forEach((button) => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const form = document.getElementById(`delete-form-dok-${id}`);

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit formulir jika pengguna mengonfirmasi
                    window.location.href = `index.php?page=dokumen&delete=${id}`;
                    form.submit();
                }
            });
        });
    });
</script>
<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama