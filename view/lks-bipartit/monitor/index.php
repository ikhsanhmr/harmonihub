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
        // debugging start (bisa dihapus)
        var monitors = <?= json_encode($monitors, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        console.log("All monitors:", monitors);

        monitors.map((monitor, index) => {
            console.log(`Monitor #${index + 1}`);
            console.log("Unit:", monitor.unit_name);
            console.log("Serikat IDs (raw string):", monitor.serikat_ids);

            // Parse string ke array di JavaScript, bukan PHP
            let serikatIds = monitor.serikat_ids ? monitor.serikat_ids.split(",") : [];
            console.log("Serikat IDs (parsed array):", serikatIds);
        });
        // debugging end

        document.addEventListener('DOMContentLoaded', function () {
            const unitFilter = document.getElementById('unitFilter');
            const tahunFilter = document.getElementById('tahun');
            const rows = document.querySelectorAll('tbody tr[data-unit-id][data-tahun]');

            function applyFilters() {
                const selectedUnit = unitFilter?.value || 'all';
                const selectedTahun = tahunFilter?.value || '';

                rows.forEach(row => {
                    const rowUnitId = row.getAttribute('data-unit-id');
                    const rowTahun = row.getAttribute('data-tahun');

                    const matchUnit = selectedUnit === 'all' || rowUnitId === selectedUnit;
                    const matchTahun = selectedTahun === '' || rowTahun === selectedTahun;

                    row.style.display = (matchUnit && matchTahun) ? '' : 'none';
                });
            }

            unitFilter?.addEventListener('change', applyFilters);
            tahunFilter?.addEventListener('change', applyFilters);
        });

    </script>
    <style>
        .table-bordered td,
        .table-bordered th {
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
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <select class="form-control form-control-sm" id="tahun">
                                <option value="">Pilih Tahun</option>
                                <?php for ($i = 2015; $i <= date('Y'); $i++) { ?>
                                    <option value="<?= $i; ?>" <?= (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? 'selected' : ''; ?>>
                                        <?= $i; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-auto">
                            <select id="unitFilter" class="form-control form-control-sm">
                                <option value="all">Pilih Unit</option>
                                <?php foreach ($units as $unit): ?>
                                    <option value="<?= $unit['id']; ?>" <?= (isset($_GET['unit']) && $_GET['unit'] == $unit['id']) ? 'selected' : ''; ?>>
                                        <?= $unit['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end text-end">
                            <a href="index.php?page=monitor-create" class="btn btn-success btn-sm">Tambah Data</a>
                        </div>
                    </div>



                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th rowspan="2" class="text-center" width="50">No.</th>
                                    <th rowspan="2">UNIT</th>
                                    <th rowspan="2">BA PEMBENTUKAN</th>
                                    <th rowspan="2">PDF BA PERUBAHAN</th>
                                    <th colspan="3" rowspan="1">KOMPOSISI & JUMLAH KEANGGOTAAN SERIKAT DALAM LKS</th>
                                    <th colspan="7">MONITORING KEGIATAN LKS BIPARTIT</th>
                                    <th rowspan="2" class="text-center" width="100">AKSI</th>
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
                                <?php if (!empty($monitors)): ?>
                                    <?php foreach ($monitors as $monitorIndex => $monitor): ?>
                                        <?php foreach ($bulans as $bulanIndex => $bulan): ?>
                                            <tr data-unit-id="<?= $monitor['unit_id'] ?? ''; ?>"
                                                data-tahun="<?= date('Y', strtotime($monitor['ba_created_at'])) ?? ''; ?>">
                                                <?php if ($bulanIndex === 0): ?>
                                                    <td rowspan="12"><?= $monitorIndex + 1; ?></td>
                                                    <td class="unit-name" rowspan="12">
                                                        <?= $monitor['unit_name'] ?? ''; ?>
                                                    </td>
                                                    <td rowspan="12">
                                                        <div class="p-2 border rounded bg-light">
                                                            <h6 class="mb-1">
                                                                <?= $monitor['ba_name'] ?? '<em>Nama BA tidak tersedia</em>'; ?>
                                                            </h6>
                                                            <small class="text-muted">
                                                                <i class="bi bi-calendar-event me-1"></i>
                                                                Tanggal Pendaftaran BA:
                                                                <?= $monitor['ba_created_at'] ?? '<em>Belum ada</em>'; ?>
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td rowspan="12">
                                                        <div class="mb-2 fw-bold">
                                                            <?= !empty($monitor['ba_perubahan_laporan']) ? $monitor['ba_perubahan_laporan'][0]['name'] : ''; ?>
                                                        </div>

                                                        <?php foreach ($monitor['ba_perubahan_laporan'] as $baPerubahanLaporan): ?>
                                                            <?php if (empty($baPerubahanLaporan['pdf_name'])): ?>
                                                                <p class="mb-1 text-muted small">
                                                                    <?= $baPerubahanLaporan['topik_bahasan'] ?> <span
                                                                        class="badge bg-secondary">No File</span>
                                                                </p>
                                                            <?php else: ?>
                                                                <p class="mb-1">
                                                                    <a href="uploads/data-lks-bipartit/<?= $baPerubahanLaporan['pdf_name'] ?>"
                                                                        target="_blank" class="link-primary text-decoration-none">
                                                                        <?= $baPerubahanLaporan['topik_bahasan'] ?>
                                                                    </a>
                                                                </p>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </td>

                                                    <?php
                                                    $serikatNames = !empty($monitor['serikat_ids']) ? explode(",", $monitor['serikat_ids']) : [];
                                                    $serikatValues = !empty($monitor['nilai_values']) ? explode(",", $monitor['nilai_values']) : [];

                                                    foreach ($serikatNames as $i => $name) {
                                                        $value = isset($serikatValues[$i]) ? $serikatValues[$i] : '';
                                                        echo "<td rowspan='12'>" . $value . "</td>";
                                                    }
                                                    ?>
                                                <?php endif; ?>

                                                <td style="text-transform: uppercase;">
                                                    <?= $bulan["name"] ?? ''; ?>
                                                </td>
                                                <td><?= $monitor['tema_' . ($bulanIndex + 1)] ?? '-'; ?></td>
                                                <td>
                                                    <?php
                                                    $rekomendasi = $monitor['rekomendasi_' . ($bulanIndex + 1)] ?? '-';
                                                    if ($rekomendasi !== '-') {
                                                        $words = explode(' ', $rekomendasi);
                                                        $limitedWords = array_slice($words, 0, 7);
                                                        echo implode(' ', $limitedWords) .
                                                            (count($words) > 7 ? '...' : '');
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= $monitor['tindak_lanjut_' . ($bulanIndex + 1)] ?? '-' ?>
                                                </td>
                                                <td>
                                                    <?= $monitor['evaluasi_' . ($bulanIndex + 1)] ?? '-' ?>
                                                    <i class="mdi mdi-timer-sand"></i>
                                                </td>
                                                <td><?= $monitor['follow_up_' . ($bulanIndex + 1)] ?? '-' ?></td>
                                                <?php
                                                $realisasiKey = 'realisasi_' . ($bulanIndex + 1);
                                                $realisasi = $monitor[$realisasiKey] ?? null;
                                                $bgColor = match ($realisasi) {
                                                    '100%' => 'green',
                                                    '0%' => 'red',
                                                    default => 'yellow'
                                                };
                                                ?>
                                                <td class="text-center" style="background-color: <?= $bgColor; ?>">
                                                    <?= $realisasi ?? '-' ?>
                                                </td>

                                                <?php if ($bulanIndex === 0): ?>
                                                    <td rowspan="12">
                                                        <a href="index.php?page=monitor-jadwal-create&id=<?= $monitor['id'] ?? '' ?>">
                                                            Tambahkan Jadwal
                                                        </a>
                                                        <form action="index.php?page=monitor-destroy&id=<?= $monitor['id'] ?? '' ?>"
                                                            id="delete-<?= $monitor['id'] ?? '' ?>" method="post"
                                                            style="display:inline;">
                                                            <input type="hidden" name="csrf_token"
                                                                value="<?= \Libraries\CSRF::generateToken() ?>">
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                                data-id="<?= $monitor['id'] ?? '' ?>">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
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
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
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
