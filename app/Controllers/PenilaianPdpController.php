<?php

namespace Controllers;

use DateTime;
use Libraries\Database;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use IntlDateFormatter;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PenilaianPdpController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function index()
    {
        $stmt = $this->db->prepare("SELECT p.id, p.peran, p.kpi, p.uraian, p.hasil_verifikasi, p.semester, p.nilai, p.tanggal, u.name as user_name, u.nip as user_nip, t.name as unit_name 
                                    FROM penilaian_pdp p 
                                    JOIN anggota_serikats u ON p.anggota_serikat_id = u.id
                                    JOIN units t ON p.unit_id = t.id
                                    ORDER BY p.created_at DESC;");
        $stmt->execute();
        $pdps = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        include 'view/penilaian-pdp/penilaian-pdp-list.php';
    }

    public function create()
    {
        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM anggota_serikats");
        $stmt->execute();
        $anggotas = $stmt->fetchAll();

        include 'view/penilaian-pdp/penilaian-pdp-create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $unit_id = $_POST['unit_id'];
            $user_id = $_SESSION['user_id'];
            $anggota_serikat_id = $_POST['anggota_serikat_id'];
            $peran = $_POST['peran'];
            $kpi = $_POST['kpi'];
            $uraian = $_POST['uraian'];
            $hasil_verifikasi = $_POST['hasil_verifikasi'];
            $semester = $_POST['semseter'];
            $nilai = $_POST['nilai'];
            $tanggal = $_POST['tanggal'];

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            $query = "INSERT INTO penilaian_pdp (unit_id, user_id, anggota_serikat_id, peran, kpi, uraian, hasil_verifikasi, semester, nilai, tanggal, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unit_id, $user_id, $anggota_serikat_id, $peran, $kpi, $uraian, $hasil_verifikasi, $semester, $nilai, $tanggal, $createdAt, $updatedAt]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Penilaian PDP created successfully!'];

                header('Location: index.php?page=penilaian-pdp-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to create Penilaian PDP!'];

                header('Location: index.php?page=penilaian-pdp-create');
            }
        }
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM penilaian_pdp WHERE id = ?");
        $stmt->execute([$id]);
        $pdp = $stmt->fetch();

        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM anggota_serikats");
        $stmt->execute();
        $anggotas = $stmt->fetchAll();

        include 'view/penilaian-pdp/penilaian-pdp-edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $unit_id = $_POST['unit_id'];
            $user_id = $_SESSION['user_id'];
            $anggota_serikat_id = $_POST['anggota_serikat_id'];
            $peran = $_POST['peran'];
            $kpi = $_POST['kpi'];
            $uraian = $_POST['uraian'];
            $hasil_verifikasi = $_POST['hasil_verifikasi'];
            $semester = $_POST['semester'];
            $nilai = $_POST['nilai'];
            $tanggal = $_POST['tanggal'];

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE penilaian_pdp SET unit_id = ?, user_id = ?, anggota_serikat_id = ?, peran = ?, kpi = ?, uraian = ?, hasil_verifikasi = ?, semester = ?, nilai = ?, tanggal = ?, updated_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unit_id, $user_id, $anggota_serikat_id, $peran, $kpi, $uraian, $hasil_verifikasi, $semester, $nilai, $tanggal, $updatedAt, $id]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Penilaian PDP updated successfully!'];

                header('Location: index.php?page=penilaian-pdp-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update Penilaian PDP!'];

                header('Location: index.php?page=penilaian-pdp-create');
            }
        }
    }

    public function destroy($id)
    {
        $stmt = $this->db->prepare("DELETE FROM penilaian_pdp WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Penilaian PDP deleted successfully!'];

            header('Location: index.php?page=penilaian-pdp-list');
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to delete Penilaian PDP!'];

            header('Location: index.php?page=penilaian-pdp-list');
        }
    }

    public function exportToPdf()
    {
        $unit = isset($_POST['unit']) ? $_POST['unit'] : null;
        $semester = isset($_POST['semester']) ? $_POST['semester'] : null;
        $surat_keputusan = isset($_POST['surat_keputusan']) ? $_POST['surat_keputusan'] : null;
        $status_penugasan = isset($_POST['status_penugasan']) ? $_POST['status_penugasan'] : null;
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $judul_penugasan = isset($_POST['judul_penugasan']) ? $_POST['judul_penugasan'] : null;
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $strategis_perusahaan = isset($_POST['strategis_perusahaan']) ? $_POST['strategis_perusahaan'] : null;

        // Validasi data
        if (!$unit || !$semester || !$surat_keputusan || !$judul_penugasan || !$status_penugasan || !$strategis_perusahaan || !$start_date || !$end_date) {
            header("Location: index.php?page=penilaian-pdp-list&error=1");
            exit;
        }

         // Query untuk mendapatkan nama unit berdasarkan ID
        $stmtUnit = $this->db->prepare("SELECT name FROM units WHERE id = :unit_id");
        $stmtUnit->bindParam(':unit_id', $unit);
        $stmtUnit->execute();
        $unitData = $stmtUnit->fetch();

        // Jika tidak ditemukan, gunakan default nama unit
        $unit_name = $unitData['name'];

        // Query untuk mengambil data
        $stmt = $this->db->prepare("SELECT p.id, p.peran, p.kpi, p.uraian, p.hasil_verifikasi, p.semester, p.nilai, p.tanggal, u.name as user_name, u.nip as user_nip, t.name as unit_name 
                                FROM penilaian_pdp p 
                                JOIN anggota_serikats u ON p.anggota_serikat_id = u.id
                                JOIN units t ON p.unit_id = t.id
                                WHERE p.unit_id = :unit 
                                AND p.semester = :semester
                                AND p.tanggal BETWEEN :start_date AND :end_date
                                ORDER BY p.created_at DESC;");

        $stmt->bindParam(':unit', $unit);
        $stmt->bindParam(':semester', $semester);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->execute();
        $pdps = $stmt->fetchAll();

        // Cari data Ketua dari $pdps
        $ketua = null;
        foreach ($pdps as $pdp) {
            if (strtolower($pdp['peran']) === 'ketua') {
                $ketua = $pdp;
                break;
            }
        }
        // Tetapkan nama dan NIP default jika ketua tidak ditemukan
        if ($ketua === null && !empty($pdps)) {
            // Ambil data dari entri pertama jika ada
            $queryKetua = $this->db->prepare("SELECT p.id, p.peran, p.kpi, p.uraian, p.hasil_verifikasi, p.semester, p.nilai, p.tanggal, u.name as user_name, u.nip as user_nip
                                FROM penilaian_pdp p 
                                JOIN anggota_serikats u ON p.anggota_serikat_id = u.id");
            $queryKetua->execute();
            $qryKs = $queryKetua->fetchAll();
            foreach ($qryKs as $qryK) {
                if (strtolower($qryK['peran']) === 'ketua') {
                    $ketua = $qryK;
                    break;
                }
            }
        }

        // Tanggal Penilaian
        $tahun = date("Y");
        $dari_tgl = date("d-M-Y", strtotime($start_date));
        $sampai_tgl = date("d-M-Y", strtotime($end_date));

        // HTML template
        $html = '
            <h3 style="text-align: center; font-family: Arial, sans-serif;">BERITA ACARA PENILAIAN TIM STRATEGIS SEMESTER   '. $semester .' - '.$tahun.' <br>DIREKTORAT/ UNIT '. strtoupper($unit_name).'</h3>

            <table style="text-align: left; font-family: Arial, sans-serif; font-size: 10pt;">
                <tr>
                    <td>Surat Keputusan Direksi/ General Manager</td>
                    <td>:</td>
                    <td>'.$surat_keputusan.'</td>
                </tr>
                <tr>
                    <td>Judul Penugasan</td>
                    <td>:</td>
                    <td>'.$judul_penugasan.'</td>
                </tr>
                <tr>
                    <td>Status Penugasan</td>
                    <td>:</td>
                    <td>'.$status_penugasan.'</td>
                </tr>
                <tr>
                    <td>Bersifat Strategis untuk Pencapaian Kinerja/ Strategis Perusahaan</td>
                    <td>:</td>
                    <td>'.$strategis_perusahaan.'</td>
                </tr>
            </table>
            <table border="1" style="margin-top: 2rem; font-family: Arial, sans-serif; width: 100%; border-collapse: collapse; font-size: 10pt;">
                <thead>
                    <tr>
                        <th rowspan="2" style="padding: 8px;">No</th>
                        <th rowspan="2" style="padding: 8px;">NIP Pegawai</th>
                        <th rowspan="2" style="padding: 8px;">Nama Pegawai</th>
                        <th rowspan="2" style="padding: 8px;">Peran</th>
                        <th colspan="5">Evaluasi oleh Ketua Tim/Pejabat pemberi tugas</th>
                    </tr>
                    <tr>
                        <th style="padding: 8px;">Tidak tercantum KPI</th>
                        <th style="padding: 8px;">Bukan uraian jabatan</th>
                        <th style="padding: 8px;">Hasil verifikasi <em>(Ya / Tidak)</em></th>
                        <th style="padding: 8px;">Semester</th>
                        <th style="padding: 8px;">Nilai</th>
                    </tr>
                </thead>
                <tbody>';

                // Tambahkan data dinamis ke tabel
                $no = 1;
                foreach ($pdps as $pdp) {
                    $html .= '<tr>
                    <td style="padding: 8px; text-align: center;">' . $no++ . '</td>
                    <td style="padding: 8px;">' . htmlspecialchars($pdp['user_nip']) . '</td>
                    <td style="padding: 8px;">' . htmlspecialchars($pdp['user_name']) . '</td>
                    <td style="padding: 8px;">' . htmlspecialchars($pdp['peran']) . '</td>
                    <td style="padding: 8px;">' . ($pdp['kpi']) . '</td>
                    <td style="padding: 8px;">' . ($pdp['uraian']) . '</td>
                    <td style="padding: 8px;">' . htmlspecialchars($pdp['hasil_verifikasi']) . '</td>
                    <td style="padding: 8px;">' . htmlspecialchars($pdp['semester']) . '</td>
                    <td style="padding: 8px; text-align: center;">' . htmlspecialchars($pdp['nilai']) . '</td>
                </tr>';
                }

                $html .= '
                </tbody>
            </table>
            <div style="margin-top: 20px; font-family: Arial, sans-serif; position: relative; page-break-inside: avoid;">
                <div style="text-align: right; font-size: 12px; margin-top: 5px;">
                    Tanggal Penilaian: '. htmlspecialchars($dari_tgl) . ' - ' . htmlspecialchars($sampai_tgl) .
                    '<p style="margin-top: 5px;">Menyetujui,</p>
                    </div>
                <div style="text-align: right; font-size: 12px; margin-top: 10px;">
                    <p style="margin-top: 60px;">' . htmlspecialchars($ketua['user_name'] ?? ' ') . '</p>
                    <span>' . htmlspecialchars($ketua['user_nip'] ?? ' ') . '</span>
                </div>
            </div>';

        // Set up DomPDF options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        // Create DomPDF instance
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF (force download)
        $dompdf->stream('Penilaian_PDP.pdf', ['Attachment' => 0]);
        // Output PDF ke browser
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=Penilaian_PDP.pdf");
        echo $dompdf->output();
    }

    public function importFromExcel()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
            $fileTmpPath = $_FILES['excel_file']['tmp_name'];
            $fileName = $_FILES['excel_file']['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            // Validasi file upload
            $allowedExtensions = ['xlsx', 'xls', 'csv'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                header("Location: index.php?page=penilaian-pdp-list&error=2");
                exit;
            }

            try {
                // Load file Excel
                $spreadsheet = IOFactory::load($fileTmpPath);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                // Kolom yang harus ada di Excel
                $requiredColumns = [
                    'nama pegawai',
                    'nip pegawai',
                    'unit',
                    'peran',
                    'tidak tercantum pada kpi',
                    'bukan uraian jabatan',
                    'hasil verifikasi (ya/tidak)',
                    'semester',
                    'nilai',
                    'tanggal'
                ];

                // Ambil header untuk validasi
                $headers = array_map('strtolower', array_map('trim', $rows[0]));
                foreach ($requiredColumns as $column) {
                    if (!in_array($column, $headers)) {
                        header("Location: index.php?page=penilaian-pdp-list&error=3");
                        exit;
                    }
                }

                // Loop data, mulai dari baris kedua (karena baris pertama adalah header)
                for ($i = 1; $i < count($rows); $i++) {
                    $rowData = array_combine($headers, $rows[$i]);

                    // Ambil data dari baris
                    $nama_pegawai = $rowData['nama pegawai'];
                    $nip_pegawai = $rowData['nip pegawai'];
                    $unit_name = $rowData['unit'];
                    $peran = $rowData['peran'];
                    $tidak_tercantum = $rowData['tidak tercantum pada kpi'];
                    $bukan_uraian_jabatan = $rowData['bukan uraian jabatan'];
                    $hasil_verifikasi = $rowData['hasil verifikasi (ya/tidak)'];
                    $semester = $rowData['semester'];
                    $nilai = $rowData['nilai'];
                    $tanggal = date('Y-m-d', strtotime($rowData['tanggal']));

                    // Cari `unit_id` berdasarkan nama unit
                    $unitQuery = $this->db->prepare("SELECT id FROM units WHERE name = :name LIMIT 1");
                    $unitQuery->execute([':name' => $unit_name]);
                    $unit = $unitQuery->fetchColumn();

                    if (!$unit) {
                        header("Location: index.php?page=penilaian-pdp-list&error=Unit not found: " . $unit_name);
                        exit;
                    }

                    // Cari pegawai berdasarkan NIP
                    $pegawaiQuery = $this->db->prepare("SELECT id FROM anggota_serikats WHERE nip = :nip AND name = :name LIMIT 1");
                    $pegawaiQuery->execute([':nip' => $nip_pegawai, ':name' => $nama_pegawai]);
                    $pegawai_id = $pegawaiQuery->fetchColumn();

                    if (!$pegawai_id) {
                        header("Location: index.php?page=penilaian-pdp-list&error=4");
                        exit;
                    }

                    // Query untuk menyimpan ke tabel `penilaian_pdp`
                    $query = "INSERT INTO penilaian_pdp 
                            (anggota_serikat_id, unit_id, peran, kpi, uraian, hasil_verifikasi, semester, nilai, tanggal) 
                          VALUES 
                            (:anggota_serikat_id, :unit_id, :peran, :kpi, :uraian, :hasil_verifikasi, :semester, :nilai, :tanggal)";

                    $stmt = $this->db->prepare($query);
                    $stmt->execute([
                        ':anggota_serikat_id' => $pegawai_id,
                        ':unit_id' => $unit,
                        ':peran' => $peran,
                        ':kpi' => $tidak_tercantum,
                        ':uraian' => $bukan_uraian_jabatan,
                        ':hasil_verifikasi' => $hasil_verifikasi,
                        ':semester' => $semester,
                        ':nilai' => $nilai,
                        ':tanggal' => $tanggal,
                    ]);
                }

                header("Location: index.php?page=penilaian-pdp-list&success=1");
                exit;
            } catch (Exception $e) {
                header("Location: index.php?page=penilaian-pdp-list&error=3");
                exit;
            }
        } else {
            header("Location: index.php?page=penilaian-pdp-list&error=No file uploaded");
            exit;
        }
    }
}
