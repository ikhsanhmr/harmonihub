<?php

use Helpers\AlertHelper;

ob_start();
if (isset($_SESSION['message'])) {
    $type = $_SESSION["message"]["type"];
    $title = ($type === "error") ? "ERROR" : "SUKSES";
    echo AlertHelper::showAlert($type, $title, $_SESSION["message"]["text"], 2500);
    unset($_SESSION['message']);
}
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Monitoring LKS Bipartit</h4>
                    <div style="float: left;">
                        <form method="GET" action="">
                            <div class="form-group">
                                <div class="select-group d-flex align-items-center">
                                    <select class="form-control form-control-sm" id="tahun" name="tahun">
                                        <!-- pilihan tahun dari 2000 sampai tahun saat ini -->
                                        <option value="">Pilih tahun</option>
                                        <?php for ($i = 2015; $i <= date('Y'); $i++) { ?>
                                            <option value="<?php echo $i; ?>"
                                                <?php echo (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? 'selected' : ''; ?>>
                                                <?php echo $i; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <div class="select-group-append col-5">
                                        <button class="btn btn-sm btn-primary ms-2" type="submit">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div style="float: right">
                        <a href="index.php?page=monitor-create" class="btn btn-success btn-sm">Tambah Data</a>
                    </div>

                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th rowspan="2" class="text-center" width="50">No.</th>
                                    <th rowspan="2">UNIT</th>
                                    <th rowspan="2">BA PEMBENTUKAN</th>
                                    <th rowspan="2">TANGGAL PENDAFTARAN BA</th>
                                    <th colspan="3" rowspan="1">KOMPOSISI & JUMLAH KEANGGOTAAN SERIKAT DALAM LKS</th>  
                                    <?php foreach ($bulans as $bulan):?>
                                        <th style="text-transform: uppercase;" colspan="6"><?php echo $bulan["name"]; ?></th>
                                    <?php endforeach; ?>
                                    <th rowspan="2" colspan="2" class="text-center" width="100">AKSI</th>
                                </tr>
                                <tr class="text-center">
                                    <?php foreach ($serikats as $serikat): ?>
                                        <th rowspan="1" class="text-center"><?php echo $serikat["name"] ?></th>
                                    <?php endforeach; ?>
                                    <?php for ($i = 1; $i < 13; $i++) : ?>
                                        <th rowspan="1" class="text-center">TEMA PEMBAHASAN</th>
                                        <th rowspan="1" class="text-center">REKOMENDASI</th>
                                        <th rowspan="1" class="text-center">TINDAK LANJUT</th>
                                        <th rowspan="1" class="text-center">EVALUASI</th>
                                        <th rowspan="1" class="text-center">FOLLOW UP</th>
                                        <th rowspan="1" class="text-center">REALISASI</th>
                                    <?php endfor ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($monitors)) : ?>
                                    <?php foreach ($monitors as $index => $monitor) : ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($monitor['unit_name']); ?></td>
                                            <td><?php echo htmlspecialchars($monitor['ba_name']); ?></td>
                                            <td><?php echo htmlspecialchars($monitor['ba_created_at']); ?></td>
                                            <?php

                                            $serikatNames = explode(",", $monitor['serikat_ids']);
                                            $serikatValues = explode(",", $monitor['nilai_values']);

                                            foreach ($serikatNames as $i => $name) {
                                                echo "<td>" . htmlspecialchars($serikatValues[$i]) . "</td>";
                                            }
                                            ?>

                                            <?php foreach ($bulans as $index => $bulan): ?>
                                                <td><?php echo htmlspecialchars($monitor['tema_' . $index + 1] ?? '-'); ?></td>
                                                <td>
                                                    <?php
                                                    $rekomendasi = $monitor['rekomendasi_' . $index + 1] ?? '-';
                                                    echo htmlspecialchars(implode(' ', array_slice(explode(' ', $rekomendasi), 0, 7))) .
                                                        (str_word_count($rekomendasi) > 7 ? '...' : '');
                                                    ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($monitor['tindak_lanjut_' . $index + 1] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($monitor['evaluasi_' . $index + 1] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($monitor['follow_up_' . $index + 1] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($monitor['realisasi_' . $index + 1] ?? '-'); ?></td>
                                            <?php endforeach; ?>
                                            <td><a href="index.php?page=monitor-jadwal-create&id=<?php echo $monitor["id"] ?>">Tambahkan Jadwal</a></td>
                                            <td class="text-center">
                                                <!-- <a href="index.php?page=monitor-edit&id=<?php echo $monitor['id']; ?>" 
                                                   class="btn btn-warning btn-sm">Edit</a> -->
                                                <form action="index.php?page=monitor-destroy&id=<?php echo $monitor['id']; ?>"
                                                    id="delete-<?php echo $monitor['id']; ?>" method="post" style="display:inline;">
                                                    <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                        data-id="<?php echo $monitor['id']; ?>">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Data tidak ditemukan.</td>
                                    </tr>
                                <?php endif; ?>
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
            const serikatId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data Monitoring ini akan dihapus secara permanen!',
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
$content = ob_get_clean();
include 'view/layouts/main.php';
