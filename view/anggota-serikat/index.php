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
                    <h4 class="card-title">Data Anggota Serikat Pekerja</h4>
                    <?php
                    if ($rolename == 'admin') { ?>
                           <div class="d-flex">
                           <select class="form-control col-2" id="serikat_name" name="serikat_name" required>
                                <option value="" selected disabled>Pilih Serikat</option>
                                    <?php foreach ($dataSerikat as $s): ?>
                                        <option id="select" value="<?php echo $s['id']; ?>"><?php echo $s['name']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                                <a href="" target="_blank" style="margin-left: 0.4rem;" class="btn btn-primary" id="export" type="submit">export ke pdf</a>
                           </div>
                    <?php } ?>
                    <?php
                    if ($rolename == 'admin') { ?>
                        <div style="float: right">
                            <a href="index.php?page=anggota-serikat-create" class="btn btn-success btn-sm">
                                Tambah Data
                            </a>
                        </div>
                    <?php } ?>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="50">No.</th>
                                    <th>Nama</th>
                                    <th>Nama Unit</th>
                                    <th>Nama Serikat</th>
                                    <th>Keanggotaan</th>
                                    <th>No Kta</th>
                                    <th>Nip</th>
                                    <th>Tanggal Dibuat</th>
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
                                            <td><?php echo htmlspecialchars($serikat['nama_unit']); ?></td>
                                            <td><?php echo htmlspecialchars($serikat['nama_serikat']); ?></td>
                                            <td><?php echo htmlspecialchars($serikat['membership']); ?></td>
                                            <td><?php echo htmlspecialchars($serikat['noKta']); ?></td>
                                            <td><?php echo htmlspecialchars($serikat['nip']); ?></td>
                                           
                                            <td><?php echo date('d-m-Y H:i', strtotime($serikat['createdAt'])); ?></td>
                                            <td>
                                                <a href="index.php?page=anggota-serikat-edit&id=<?php echo $serikat['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="index.php?page=anggota-serikat-destroy&id=<?php echo $serikat['id']; ?>" 
                                                    id="delete-<?php echo $serikat['id']; ?>" method="post" style="display:inline;">
                                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
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
            text: 'Data Anggota Serikat ini akan dihapus secara permanen!',
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
    const serikatName = document.getElementById("serikat_name");
    document.addEventListener("DOMContentLoaded",()=>{
        serikatName.addEventListener("change",()=>{
            const selectedText = serikatName.options[serikatName.selectedIndex].text;
            const exportPdf = document.getElementById("export");
            exportPdf.innerHTML = `export anggota serikat dari serikat ${selectedText} ke pdf`;
            exportPdf.setAttribute("href",`index.php?page=anggota-serikat-pdf&id_serikat=${serikatName.value}`)
        })
    })
</script>
<?php


$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama

