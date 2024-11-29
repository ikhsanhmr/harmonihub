<?php

ob_start(); // Mulai output buffering

use Helpers\AlertHelper;

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
                    <h4 class="card-title">Data Serikat Pekerja</h4>
                    <div style="float: right">
                        <a href="index.php?page=serikat-create" class="btn btn-success btn-sm">
                            Tambah Data
                        </a>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Nama</th>
                                    <th>Nama Unit</th>
                                    <th>Nip</th>
                                    <th>Keanggotaan</th>
                                    <th>No Kta</th>
                                    <th>Posisi</th>
                                    <th>Logo</th>
                                    <th>Tanggal Dibuat</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($serikats)) {
                                    foreach ($serikats as $index => $serikat) {
                                ?>
                                        <tr>

                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($serikat['nama']); ?></td>
                                            <td><?php echo htmlspecialchars($serikat['nama_unit']); ?></td>
                                            <td><?php echo htmlspecialchars($serikat['nip']); ?></td>
                                            <td><?php echo htmlspecialchars($serikat['keanggotaan']); ?></td>
                                            <td><?php echo htmlspecialchars($serikat['noKta']); ?></td>
                                            <td><?php echo htmlspecialchars($serikat['posisi']); ?></td>
                                            <td>
                                                <?php if ($serikat['logoPath']): ?>
                                                    <img src="<?php echo $serikat['logoPath']; ?>" alt="Photo Logo" width="100">
                                                <?php else: ?>
                                                    <span>No Picture</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('d-m-Y H:i', strtotime($serikat['createdAt'])); ?></td>
                                            <td>
                                                <a href="index.php?page=serikat-edit&id=<?php echo $serikat['id_serikat']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="index.php?page=serikat-destroy&id=<?php echo $serikat['id_serikat']; ?>" 
                                                    id="delete-<?php echo $serikat['id_serikat']; ?>" method="post" style="display:inline;">
                                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                                            data-id="<?php echo $serikat['id_serikat']; ?>">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No serikats found</td></tr>";
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
    document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function () {
        const serikatId = this.getAttribute('data-id'); 
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data serikat ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak, batal',
            position: 'top-end',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-' + serikatId).submit();
            }
        });
    });
});

</script>
<?php


$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama

