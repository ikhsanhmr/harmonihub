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
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ...existing head content... -->
     <script>
    document.addEventListener('DOMContentLoaded', function() {
        const unitFilter = document.getElementById('unitFilter');
        const rows = document.querySelectorAll('tbody tr');

        unitFilter.addEventListener('change', function() {
            const selectedUnit = this.value;
            rows.forEach(row => {
                const unitName = row.querySelector('.unit-name').textContent.trim();
                if (selectedUnit === 'all' || unitName === selectedUnit) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
<style>
.table-bordered td, .table-bordered th {
    border: 1px solid #dee2e6;
    vertical-align: middle;
    padding: 8px;
}
.table td {
    white-space: normal;
    word-wrap: break-word;
}
.text-center {
    text-align: center;
}
</style>
</head>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Monitoring LKS Bipartit</h4>
                    <div style="float: left;">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="page" value="monitor">

                            <div class="form-group">
                                <div class="select-group d-flex align-items-center">
                                    <select class="form-control form-control-sm" id="tahun" name="tahun">
                                        <!-- Pilihan tahun dari 2015 sampai tahun saat ini -->
                                        <option value="">Pilih tahun</option>
                                        <?php for ($i = 2015; $i <= date('Y'); $i++) { ?>
                                            <option value="<?= $i; ?>"
                                                <?= (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? 'selected' : ''; ?>>
                                                <?= $i; ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <!-- Gantikan button dengan a href -->
                                    <div class="select-group-append col-5">
                                        <a href="index.php?page=monitor&tahun=" id="filter-link" class="btn btn-sm btn-primary ms-2">
                                            Filter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="filter-container">
        <label for="unitFilter">Filter Unit:</label>
        <select id="unitFilter" class="form-select">
            <option value="all">All Units</option>
            <?php foreach ($units as $unit): ?>
                <option value="<?= htmlspecialchars($unit['name']); ?>"><?= htmlspecialchars($unit['name']); ?></option>
            <?php endforeach; ?>
        </select>
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
        <th colspan="7">MONITORING KEGIATAN LKS BIPARTIT</th>
        <th rowspan="2" colspan="2" class="text-center" width="100">AKSI</th>
    </tr>
    <tr class="text-center">
        <?php foreach ($serikats as $serikat): ?>
            <th rowspan="1" class="text-center"><?= $serikat["name"] ?></th>
        <?php endforeach; ?>
        <th class="text-center">BULAN</th>
        <th class="text-center">TEMA PEMBAHASAN</th>
        <th class="text-center">REKOMENDASI</th>
        <th class="text-center">TINDAK LANJUT</th>
        <th class="text-center">EVALUASI</th>
        <th class="text-center">FOLLOW UP</th>
        <th class="text-center">REALISASI</th>
    </tr>
</thead>
<tbody>
    <?php if (!empty($monitors)) : ?>
        <?php foreach ($monitors as $monitorIndex => $monitor) : ?>
            <?php foreach ($bulans as $bulanIndex => $bulan): ?>
                <tr>
                    <?php if ($bulanIndex === 0): ?>
                        <td rowspan="12"><?= $monitorIndex + 1; ?></td>
                        <td class="unit-name" rowspan="12"><?= htmlspecialchars($monitor['unit_name']); ?></td>
                        <td rowspan="12"><?= htmlspecialchars($monitor['ba_name']); ?></td>
                        <td rowspan="12"><?= htmlspecialchars($monitor['ba_created_at']); ?></td>
                        <?php
                        $serikatNames = explode(",", $monitor['serikat_ids']);
                        $serikatValues = explode(",", $monitor['nilai_values']);
                        foreach ($serikatNames as $i => $name) {
                            echo "<td rowspan='12'>" . htmlspecialchars($serikatValues[$i]) . "</td>";
                        }
                        ?>
                    <?php endif; ?>
                    
                    <td style="text-transform: uppercase;"><?= $bulan["name"]; ?></td>
                    <td><?= htmlspecialchars($monitor['tema_' . ($bulanIndex + 1)] ?? '-'); ?></td>
                    <td>
                        <?php
                        $rekomendasi = $monitor['rekomendasi_' . ($bulanIndex + 1)] ?? '-';
                        echo htmlspecialchars(implode(' ', array_slice(explode(' ', $rekomendasi), 0, 7))) . 
                             (str_word_count($rekomendasi) > 7 ? '...' : '');
                        ?>
                    </td>
                    <td><?= htmlspecialchars($monitor['tindak_lanjut_' . ($bulanIndex + 1)] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($monitor['evaluasi_' . ($bulanIndex + 1)] ?? '-'); ?> 
                        <i class="mdi mdi-timer-sand"></i>
                    </td>
                    <td><?= htmlspecialchars($monitor['follow_up_' . ($bulanIndex + 1)] ?? '-'); ?></td>
                    <?php
                    $realisasiKey = 'realisasi_' . ($bulanIndex + 1);
                    $realisasi = isset($monitor[$realisasiKey]) ? $monitor[$realisasiKey] : null;
                    if ($realisasi === '100%') { ?>
                        <td class="text-center" style="background-color: green;">
                    <?php } elseif ($realisasi === '0%') { ?>
                        <td class="text-center" style="background-color: red;">
                    <?php } else { ?>
                        <td class="text-center" style="background-color: yellow;">
                    <?php } ?>
                        <?= htmlspecialchars($realisasi ?? '-'); ?>
                    </td>
                    
                    <?php if ($bulanIndex === 0): ?>
                        <td rowspan="12">
                            <a href="index.php?page=monitor-jadwal-create&id=<?= $monitor["id"] ?>">
                                Tambahkan Jadwal
                            </a>
                        </td>
                        <td class="text-center" rowspan="12">
                            <form action="index.php?page=monitor-destroy&id=<?= $monitor['id']; ?>"
                                id="delete-<?= $monitor['id']; ?>" method="post" style="display:inline;">
                                <input type="hidden" name="csrf_token" 
                                       value="<?= \Libraries\CSRF::generateToken(); ?>">
                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                        data-id="<?= $monitor['id']; ?>">
                                    Delete
                                </button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="15" class="text-center">Data tidak ditemukan.</td>
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
    document.getElementById('tahun').addEventListener('change', function() {
        const tahun = this.value;
        const filterLink = document.getElementById('filter-link');
        let newUrl = 'index.php?page=monitor';

        if (tahun) {
            newUrl += `&tahun=${tahun}`;
        }

        filterLink.href = newUrl;
    });

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
