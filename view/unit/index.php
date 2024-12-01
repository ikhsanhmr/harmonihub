<?php
ob_start();
?>

<div class="content-wrapper">
    <?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $messageClass = $message['type'] == 'success' ? 'alert-success' : 'alert-danger';
    ?>
        <div class="alert <?php echo $messageClass; ?>" role="alert">
            <?php echo $message['text']; ?>
        </div>
    <?php
        unset($_SESSION['message']);
    }
    ?>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Unit</h4>
                    <div style="float: right">
                        <a href="index.php?page=unit-create" class="btn btn-success btn-sm">
                            Tambah Data
                        </a>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Nama Unit</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($units as $index => $unit) {
                                ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($unit['name']); ?></td>
                                        <td>
                                            <a href="index.php?page=unit-edit&id=<?php echo $unit['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="index.php?page=unit-delete&id=<?php echo $unit['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this data?')">Delete</a>
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

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama