<?php
ob_start();

// Memuat class CSRF untuk menghasilkan token
use Libraries\CSRF;

// Ambil token CSRF yang akan digunakan dalam form
$csrfToken = CSRF::generateToken();

?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Data</h4>
                    <form class="forms-sample" action="index.php?page=tema/store" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                        <div class="form-group row">
                            <label for="namaTema" class="col-sm-3 col-form-label">Tema</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="namaTema" id="namaTema" placeholder="Nama Tema" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2 btn-sm">Simpan</button>
                        <a href="index.php?page=tema" class="btn btn-warning btn-sm">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    $content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
    include 'view/layouts/main.php'; // Sertakan layout utama