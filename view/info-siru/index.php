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

                                            <td><?= $index + 1; ?></td>
                                            <td><?= htmlspecialchars($infoSiru['username']); ?></td>
                                            <td><?= htmlspecialchars($infoSiru['type']); ?></td>
                                            <td>
                                                <?php if ($infoSiru['filePath']): ?>
                                                    <?php if($infoSiru["type"]=== "video"):?>
                                                        <video class="card-img-top" controls height="100" width="20">
                                                            <source src="<?= $infoSiru['filePath']; ?>" type="video/mp4">
                                                            <source src="<?= $infoSiru['filePath']; ?>" type="video/webm">
                                                            <source src="<?= $infoSiru['filePath']; ?>" type="video/ogg">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <?php else:?>
                                                            <img style="width: 3rem; height:auto;border-radius:0px;position:relative;left: 50%; transform: translateX(-50%);" src="<?= $infoSiru['filePath']; ?>" alt="Profile Picture" data-bs-toggle="modal" data-bs-target="#flyerModal-<?= $infoSiru["info_siru_id"];?>">
                                                            <div class="modal fade" id="flyerModal-<?= $infoSiru["info_siru_id"];?>" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="<?= $infoSiru['filePath']; ?>" style="width: auto; height:auto;border-radius:0px;position:relative;left: 50%; transform: translateX(-50%);" class="img-fluid" alt="Flyer">
                                                                </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif?>
                                                   
                                                <?php else: ?>
                                                    <span>No Picture</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d-m-Y H:i', strtotime($infoSiru['createdAt'])); ?></td>
                                            <?php
                                            if ($rolename == 'admin') { ?>
                                                <td>
                                                    <a href="index.php?page=info-siru-edit&id=<?= $infoSiru['info_siru_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="index.php?page=info-siru-destroy&id=<?= $infoSiru['info_siru_id']; ?>"
                                                        id="delete-<?= $infoSiru['info_siru_id']; ?>" method="post" style="display:inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                            data-id="<?= $infoSiru['info_siru_id']; ?>">
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
