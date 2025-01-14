<?php
ob_start();

use Helpers\AlertHelper;
// $successMessage = isset($_GET['success']) ? $_GET['success'] : null;
// Tampilkan alert sukses
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Data baru berhasil ditambahkan.');
}

if (isset($_GET['success']) && $_GET['success'] == 2) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Data berhasil dihapus.');
}

if (isset($_GET['success']) && $_GET['success'] == 3) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Data berhasil diupdate.');
}

// Tampilkan alert error jika ada
if (isset($_GET['error'])) {
    echo AlertHelper::showAlert('error', 'Gagal!', 'Terjadi kesalahan, silakan coba lagi.');
}


?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tema</h4>
                    <div style="float: right">
                        <a href="index.php?page=tema/create" class="btn btn-success btn-sm">
                            Tambah Data
                        </a>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead class="text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Tema</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $no = 1;
                                foreach ($temas as $tema): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td> <?= $tema['namaTema'] ?></td>
                                        <td class="d-flex justify-content-center">
                                            <a href="index.php?page=tema=edit&id=<?= $tema['id'] ?>" class="btn btn-warning btn-sm">edit</a>
                                            <form action="index.php?page=tema/delete" id="delete-form-<?= $tema['id'] ?>" method="POST">
                                                <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                                                <input type="hidden" name="id_tema" value="<?= htmlspecialchars($tema['id']) ?>">
                                                <button type="button" class="btn btn-danger btn-sm" id="delete" data-id="<?= $tema['id'] ?>">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        // Tambahkan Event Listener pada tombol hapus
        document.querySelectorAll('#delete').forEach((button) => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const form = document.getElementById(`delete-form-${id}`);

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
                        window.location.href = `index.php?page=tema&delete=${id}`;
                        form.submit();
                    }
                });
            });
        });
    </script>
    <?php
    $content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
    include 'view/layouts/main.php'; // Sertakan layout utama