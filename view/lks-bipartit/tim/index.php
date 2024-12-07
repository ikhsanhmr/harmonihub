<?php
ob_start();

use Helpers\AlertHelper;


if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Tim baru berhasil ditambahkan.');
}

if (isset($_GET['gagal']) && $_GET['gagal'] == 1) {
    echo AlertHelper::showAlert('error', 'Gagal!', 'Gagal menambahkan tim.');
}

if (isset($_GET['success']) && $_GET['success'] == 2) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Tim berhasil diupdate.');
}

if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo AlertHelper::showAlert('error', 'Gagal!', 'Tim gagal diupdate.');
}

if (isset($_GET['success']) && $_GET['success'] == 3) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Tim berhasil dihapus.');
}

if (isset($_GET['error']) && $_GET['error'] == 2) {
    echo AlertHelper::showAlert('success', 'Gagal!', 'Tim gagal dihapus.');
}

?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Tim</h4>
                    <a href="index.php?page=tim/create" class="btn btn-success btn-sm">Tambah</a>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead class="text-center">
                                <tr>
                                    <th>No</th>
                                    <th>NIP Pegawai</th>
                                    <th>Nama Pegawai</th>
                                    <th>Peran</th>
                                    <th>Unit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $no = 1;
                                foreach ($tims as $tim): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td> <?= $tim['nip_pegawai'] ?></td>
                                        <td> <?= $tim['nama_pegawai'] ?></td>
                                        <td> <?= $tim['peran'] ?></td>
                                        <td> <?= $tim['name_unit'] ?></td>
                                        <td class="d-flex justify-content-center">
                                            <a href="index.php?page=tim/edit&id=<?php echo $tim['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="index.php?page=tim/delete" id="delete-form-<?= $tim['id'] ?>" method="POST">
                                                <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                                                <input type="hidden" name="id_tim" value="<?= htmlspecialchars($tim['id']) ?>">
                                                <button type="button" class="btn btn-danger btn-sm" id="delete" data-id="<?= $tim['id'] ?>">Hapus</button>
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