<?php

ob_start(); // Mulai output buffering

?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-6 offset-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Unit</h4>

                    <form action="index.php?page=unit-update&id=<?= $unit['id']; ?>" method="POST">

                        <div class="form-group">
                            <label for="name">Nama Unit</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($unit['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="manager_unit">Manager Unit</label>
                            <input type="text" class="form-control" id="manager_unit" name="manager_unit" value="<?= htmlspecialchars($unit['manager_unit']); ?>" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=unit-list" class="btn btn-warning btn-sm">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama