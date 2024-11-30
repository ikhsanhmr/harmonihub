<?php

ob_start(); // Mulai output buffering

?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-6 offset-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah BA Pembentukan</h4>

                    <form action="index.php?page=ba-pembentukan-update&id=<?php echo $ba['id']; ?>" method="POST">

                        <div class="form-group">
                            <label for="name">Nama BA Pembentukan</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($ba['name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=ba-pembentukan-list" class="btn btn-warning btn-sm">
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