<?php

use Helpers\AlertHelper;
use Libraries\CSRF;

if (isset($_SESSION['message'])) {
    $type = $_SESSION["message"]["type"];
    $title = ($type === "error") ? "ERROR" : "SUKSES"; 
    $message = $_SESSION["message"]["text"];
    echo "<script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: '$title',
                text: '$message',
                icon: '$type', 
                confirmButtonText: 'OK',
                timer:2000
            });
        });
    </script>";

    unset($_SESSION['message']);
}
ob_start()
?>
        <section class="header-section w-100">
            <div class="d-flex align-items-center flex-column ">
                <div class="col-12 col-sm-10 col-md-6 d-flex align-items-center justify-content-center">
                    <img src="assets/frontend/img/iconProfil.png" width="30" height="30" alt="Profile Icon">
                    <h3 style="color: rgb(246,79,36); font-family:sans-serif;font-weight: 700;">
                        Verifikasi Keanggotaan Serikat 
                    </h3>
                </div>
                <form id="myForm" style="margin-top: 2rem;" class="col-10 col-sm-8 col-md-12" action="index.php?page=anggota-serikat-store" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>" id="">
                    <div class="row gap-2 d-flex justify-content-center">
                        <div style="background: brown;" class="col-md-5">
                            <div class="row form-group m-2">
                                <label for="name" style="margin-right: 2rem;" class="col-sm-2 text-white col-form-label">Name</label>
                                <div class="col-sm-9">
                                <input placeholder="Masukkan Nama" autocomplete="name" type="text" class="form-control" id="name" name="name">
                                </div>
                            </div>
                            <div class="row form-group m-2">
                                <label for="nip" style="margin-right: 2rem;" class="col-sm-2 text-white col-form-label">Nip</label>
                                <div class="col-sm-9">
                                    <input placeholder="Masukkan Nip" autocomplete="nip" type="number" class="form-control" id="nip" name="nip">
                                </div>
                            </div>

                            <div class="row form-group m-2">
                                <label style="margin-right: 2rem;" class="col-sm-2 text-white col-form-label" for="unitId">Unit</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="unitId" name="unitId" required>
                                        <option value="" selected disabled>Pilih Unit</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?php echo $unit['id']; ?>"><?php echo $unit['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row form-group m-2">
                                <label for="membership" style="margin-right: 2rem;" class="col-sm-2 text-white col-form-label">Keanggotaan</label>
                                <div class="col-sm-9">
                                    <input placeholder="Masukkan Keanggotaan" type="text" class="form-control" id="membership" autocomplete="membership" name="membership">
                                </div>
                            </div>
                        </div>
                        <div style="background: brown;" class="col-md-5">
                            <div class="row form-group m-2">
                                <label for="noKta" style="margin-right: 2rem;" class="col-sm-2 text-white col-form-label">noKta</label>
                                <div class="col-sm-9">
                                    <input placeholder="Masukkan no noKTA" autocomplete="noKta" type="number" class="form-control" id="noKta" name="noKta">
                                </div>
                            </div>
                            <div class="row form-group m-2">
                                <label style="margin-right: 2rem;" class="col-sm-2 text-white col-form-label" for="serikatId">Serikat</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="serikatId" name="serikatId" required>
                                        <option value="" selected disabled>Pilih Serikat</option>
                                        <?php foreach ($serikats as $serikat): ?>
                                            <option value="<?php echo $serikat['id']; ?>"><?php echo $serikat['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group m-2">
                                <div class="col-3 d-flex align-items-center">
                                    <input type="checkbox" style="width: 2rem;height: 2rem;" id="setuju">
                                </div>
                                <div class="col-9 d-flex align-items-center">
                                    <span class="text-white small">
                                        Dengan ini saya menyatakan dengan sesungguhnya bahwa semua informasi yang disampaikan adalah benar dan dapat dipertanggungjawabkan
                                    </span>
                                </div>
                            </div>

                            <div class="form-group d-flex justify-content-center">
                                <button type="submit" class="btn btn-secondary btn-md">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>

                   
                </form>
            </div>
        </section>
<script>
    const form = document.getElementById('myForm');
    const checkbox = document.getElementById('setuju');

    form.addEventListener('submit', function (event) {
        if (!checkbox.checked) {
            event.preventDefault(); 
            alert('Anda harus menyetujui pernyataan sebelum mengirim formulir.');
        }
    });
</script>
<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/frontend/layouts/main.php'
?>
