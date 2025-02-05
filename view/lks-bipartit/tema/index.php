<?php
ob_start();

use Helpers\AlertHelper;

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

// Periksa role pengguna
$role = $_SESSION['role_name'] ?? null;

// Pastikan data temas tersedia
if (!isset($temas) || !is_array($temas)) {
    $temas = []; // Jika tidak ada data, set ke array kosong untuk menghindari error
}
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tema</h4>

                    <!-- Tombol Tambah Data hanya muncul jika role bukan unit -->
                    <?php if ($role !== 'unit') : ?>
                        <div style="float: right">
                            <a href="index.php?page=tema/create" class="btn btn-success btn-sm">
                                Tambah Data
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead class="text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Tema</th>
                                    <?php if ($role !== 'unit') : ?>
                                        <th>Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $no = 1;
                                foreach ($temas as $tema) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($tema['namaTema'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                                        <?php if ($role !== 'unit') : ?>
                                            <td>
                                                <a href="index.php?page=tema/edit&id=<?= $tema['id']; ?>" class="btn btn-warning btn-sm">
                                                    Edit
                                                </a>
                                                <a href="index.php?page=tema/delete&id=<?= $tema['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?');">
                                                    Hapus
                                                </a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
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
    document.querySelectorAll('#delete').forEach((button) => {
        button.addEventListener('click', function () {
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
