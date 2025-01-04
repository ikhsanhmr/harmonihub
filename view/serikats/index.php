<?php

ob_start(); // Mulai output buffering

use Helpers\AlertHelper;

if (isset($_SESSION['message'])) {
    $type = $_SESSION["message"]["type"];
    $title = ($type === "error") ? "ERROR" : "SUKSES"; 
    echo AlertHelper::showAlert($type, $title, $_SESSION["message"]["text"]);
    unset($_SESSION['message']);
}
$rolename = isset($_SESSION["role_name"]) ? $_SESSION["role_name"] : null
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Serikat</h4>
                    <div style="float: right">
                        <?php if($rolename == "admin"){?>
                            <a href="index.php?page=serikat-create" class="btn btn-success btn-sm">
                            Tambah Data
                        </a>
                        <?php }?>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Nama</th>
                                    <th>Logo</th>
                                   
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
                                            <td><?php echo htmlspecialchars($serikat['name']); ?></td>
                                            <td>
                                                <?php if ($serikat['logoPath']): ?>
                                                    <img src="<?php echo $serikat['logoPath']; ?>" alt="Photo Logo" width="100">
                                                <?php else: ?>
                                                    <span>No Picture</span>
                                                <?php endif; ?>
                                            </td>
                                           
                                            <td>
                                                <a href="index.php?page=serikat-edit&id=<?php echo $serikat['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="index.php?page=serikat-destroy&id=<?php echo $serikat['id']; ?>" 
                                                    id="delete-<?php echo $serikat['id']; ?>" method="post" style="display:inline;">
                                                    <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                                            data-id="<?php echo $serikat['id']; ?>">
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

