<?php

ob_start(); // Mulai output buffering

use Helpers\AlertHelper;
use Libraries\CSRF;

if (isset($_SESSION['message'])) {
    $type = $_SESSION["message"]["type"];
    $title = ($type === "error") ? "ERROR" : "SUKSES"; 
    echo AlertHelper::showAlert($type, $title, $_SESSION["message"]["text"]);
    unset($_SESSION['message']);
}
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tambah Anggota Serikat Pekerja</h4>

                    <form id="myForm" style="margin-top: 2rem;" class="col-10 col-sm-8 col-md-12" action="index.php?page=anggota-serikat-store" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>" id="">
                        <div class="row gap-2 d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="row form-group m-2">
                                    <label for="name" class=" text-black col-form-label">Name</label>
                                    <input placeholder="Masukkan Nama" autocomplete="name" type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="row form-group m-2">
                                    <label for="nip" class=" text-black col-form-label">Nip</label>
                                    <input placeholder="Masukkan Nip" autocomplete="nip" type="number" class="form-control" id="nip" name="nip">
                                </div>

                                <div class="row form-group m-2">
                                    <label class=" text-black col-form-label" for="unitId">Unit</label>
                                    <select class="form-control" id="unitId" name="unitId" required>
                                            <option value="" selected disabled>Pilih Unit</option>
                                            <?php foreach ($units as $unit): ?>
                                                <option value="<?php echo $unit['id']; ?>"><?php echo $unit['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                </div>

                                <div class="row form-group m-2">
                                    <label for="membership" class=" text-black col-form-label">Keanggotaan</label>
                                    <input placeholder="Masukkan Keanggotaan" type="text" class="form-control" id="membership" autocomplete="membership" name="membership">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group m-2">
                                    <label for="noKta" class=" text-black col-form-label">noKta</label>
                                    <input placeholder="Masukkan no noKTA" autocomplete="noKta" type="number" class="form-control" id="noKta" name="noKta">
                                </div>
                                <div class="row form-group m-2">
                                    <label class=" text-black col-form-label" for="serikatId">Serikat</label>
                                    <select class="form-control" id="serikatId" name="serikatId" required>
                                        <option value="" selected disabled>Pilih Serikat</option>
                                            <?php foreach ($serikats as $serikat): ?>
                                                <option value="<?php echo $serikat['id']; ?>"><?php echo $serikat['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                </div>
                                <div class="row form-group m-2">
                                    <div class="col-3 d-flex align-items-center">
                                        <input type="checkbox" style="width: 2rem;height: 2rem;" id="setuju">
                                    </div>
                                    <div class="col-9">
                                        <span class="text-black small">
                                            Dengan ini saya menyatakan dengan sesungguhnya bahwa semua informasi yang disampaikan adalah benar dan dapat dipertanggungjawabkan
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <button type="submit" class="btn btn-primary btn-md">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>


                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
include 'view/layouts/main.php'; // Sertakan layout utama