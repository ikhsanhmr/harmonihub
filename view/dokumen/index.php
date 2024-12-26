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
                    <h4 class="card-title">Data Dokumen</h4>
                    <div style="float: right">
                        <a href="index.php?page=dokumen/create" class="btn btn-success btn-sm">
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
                                    <th>Keterangan</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($dokumens as $index => $dokumen) {
                                ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($dokumen['nama_dokumen']); ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($dokumen['file_dokumen'])): ?>
                                                <a href="uploads/dokumen/<?= htmlspecialchars($dokumen['file_dokumen']); ?>" target="_blank">Preview</a>
                                            <?php else: ?>
                                                No Document
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($dokumen['keterangan']); ?></td>
                                        <td class="d-flex justify-content-center">
                                            <a href="index.php?page=dokumen=edit&id_dokumen=<?= $dokumen['id_dokumen'] ?>" class="btn btn-warning btn-sm">edit</a>
                                            <form action="index.php?page=dokumen/delete" id="delete-form-dok-<?= $dokumen['id_dokumen'] ?>" method="POST">
                                                <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                                                <input type="hidden" name="id_dokumen" value="<?= htmlspecialchars($dokumen['id_dokumen']) ?>">
                                                <button type="button" class="btn btn-danger btn-sm" id="delete-dok" data-id="<?= $dokumen['id_dokumen'] ?>">Hapus</button>
                                            </form>
                                            <!-- <a href="index.php?page=dokumen/delete&id=<?php echo $dokumen['id_dokumen']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this data?')">Delete</a> -->
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