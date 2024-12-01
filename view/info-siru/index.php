<?php

ob_start(); // Mulai output buffering

use Helpers\AlertHelper;

if (isset($_SESSION['message'])) {
    $type = $_SESSION["message"]["type"];
    $title = ($type === "error") ? "ERROR" : "SUKSES";
    echo AlertHelper::showAlert($type, $title, $_SESSION["message"]["text"]);
    unset($_SESSION['message']);
}

$rolename = $_SESSION['role_name'];

?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Info Siru</h4>
                    <?php
                    if ($rolename == 'admin') { ?>
                        <div style="float: right">
                            <a href="index.php?page=info-siru-create" class="btn btn-success btn-sm">
                                Tambah Data
                            </a>
                        </div>
                    <?php } ?>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Pengirim</th>
                                    <th>Tipe</th>
                                    <th>Konten</th>
                                    <th>Tanggal Dibuat</th>
                                    <?php
                                    if ($rolename == 'admin') { ?>
                                        <th class="text-center" width="100">Aksi</th>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($infoSirus)) {
                                    foreach ($infoSirus as $index => $infoSiru) {
                                ?>
                                        <tr>

                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($infoSiru['username']); ?></td>
                                            <td><?php echo htmlspecialchars($infoSiru['type']); ?></td>
                                            <td>
                                                <?php if ($infoSiru['filePath']): ?>
                                                    <img src="<?php echo $infoSiru['filePath']; ?>" alt="Profile Picture" width="100">
                                                <?php else: ?>
                                                    <span>No Picture</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('d-m-Y H:i', strtotime($infoSiru['createdAt'])); ?></td>
                                            <?php
                                            if ($rolename == 'admin') { ?>
                                                <td>
                                                    <a href="index.php?page=info-siru-edit&id=<?php echo $infoSiru['info_siru_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="index.php?page=info-siru-destroy&id=<?php echo $infoSiru['info_siru_id']; ?>"
                                                        id="delete-<?php echo $infoSiru['info_siru_id']; ?>" method="post" style="display:inline;">
                                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                            data-id="<?php echo $infoSiru['info_siru_id']; ?>">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No infoSirus found</td></tr>";
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
        button.addEventListener('click', function() {
            const infoSiruId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data infoSiru ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak, batal',
                position: 'top-end',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-' + infoSiruId).submit();
                }
            });
        });
    });
</script>
<?php


$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama
