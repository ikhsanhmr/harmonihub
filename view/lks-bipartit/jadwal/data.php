<?php

use Helpers\AlertHelper;

ob_start();

if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo AlertHelper::showAlert('error', 'Gagal!', 'Gagal menghapus data.');
}
if (isset($_GET['error']) && $_GET['error'] == 2) {
    echo AlertHelper::showAlert('error', 'Gagal!', 'Gagal mengupdate data.');
}


?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Agenda</h4>
                    <a href="index.php?page=jadwal" class="btn btn-warning btn-sm">kembali</a>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead class="text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Tema</th>
                                    <th>Unit</th>
                                    <th>Agenda</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $no = 1;
                                foreach ($jadwals as $jadwal): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td> <?= $jadwal['nama_tema'] ?></td>
                                        <td> <?= $jadwal['nama_unit'] ?></td>
                                        <td> <?= $jadwal['namaAgenda'] ?></td>
                                        <td> <?= $jadwal['tanggal_start'] ?></td>
                                        <td> <?= $jadwal['tanggal_end'] ?></td>
                                        <td class="d-flex justify-content-center">
                                            <a href="index.php?page=jadwal/edit&id=<?php echo $jadwal['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="index.php?page=jadwal/delete" id="delete-form-<?= $jadwal['id'] ?>" method="POST">
                                                <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                                                <input type="hidden" name="id_jadwal" value="<?= htmlspecialchars($jadwal['id']) ?>">
                                                <button type="button" class="btn btn-danger btn-sm" id="delete" data-id="<?= $jadwal['id'] ?>">Hapus</button>
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

    <!-- Tampilkan notifikasi jika sukses menambah data -->
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
                        form.submit();
                    }
                });
            });
        });
    </script>

    <?php
    $content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
    include 'view/layouts/main.php'; // Sertakan layout utama