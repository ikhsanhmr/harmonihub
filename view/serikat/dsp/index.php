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
                <div class="card-body" >
                    <h4 class="card-title">Data Dokumen Susunan Pengurus</h4>
                    <div style="float: right">
                            <a style="float: right; margin:1rem 0 0 0" href="index.php?page=dsp-create" class="btn btn-success btn-sm">
                                Tambah Data
                            </a>
                        </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Nama Serikat</th>
                                    <th>Dokumen</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($dsps)) {
                                    foreach ($dsps as $index => $dsp) {
                                ?>
                                        <tr>

                                            <td><?= $index + 1; ?></td>
                                            <td><?= htmlspecialchars($dsp['serikat_name']); ?></td>
                                            <td class="text-center">
                                            <?php if (!empty($dsp['dokumen'])): ?>
                                                <a href="uploads/dsp/<?= htmlspecialchars($dsp['dokumen']); ?>" target="_blank">Preview</a>
                                            <?php else: ?>
                                                No Document
                                            <?php endif; ?>
                                        </td>
                                           
                                            <td>
                                                <a href="index.php?page=dsp-edit&id=<?= $dsp['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="index.php?page=dsp-destroy&id=<?= $dsp['id']; ?>" 
                                                    id="delete-<?= $dsp['id']; ?>" method="post" style="display:inline;">
                                                     <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                                            data-id="<?= $dsp['id']; ?>">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No dsp found</td></tr>";
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
        const dspId = this.getAttribute('data-id'); 
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data Anggota dsp ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak, batal',
            position: 'top-end',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-' + dspId).submit();
            }
        });
    });
});
    const dspName = document.getElementById("dsp_name");
    document.addEventListener("DOMContentLoaded",()=>{
        dspName.addEventListener("change",()=>{
            const selectedText = dspName.options[dspName.selectedIndex].text;
            const exportPdf = document.getElementById("export");
            exportPdf.innerHTML = `export anggota dsp dari dsp ${selectedText} ke pdf`;
            exportPdf.setAttribute("href",`index.php?page=anggota-dsp-pdf&id_dsp=${dspName.value}`)
        })
    })
</script>
<?php


$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama

