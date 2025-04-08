<?php

namespace Controllers\LksBipartit;

use DateTime;
use Helpers\Validation;
use Libraries\Database;
use Dompdf\Dompdf;
use Dompdf\Options;
use IntlDateFormatter;
use PDOException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
class Laporan
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        date_default_timezone_set(timezoneId: 'Asia/Jakarta');
    }

    public function index()
    {
        $start_date = $_GET['start_date'] ?? null;
        $end_date = $_GET['end_date'] ?? null;
        $baUbah = $_GET['ba_perubahan'] ?? null;
        $params = [];

        $sql = "SELECT l.id, 
                    ba_ubah.name AS ba_ubah_name, 
                    l.tanggal, 
                    l.topik_bahasan, 
                    l.latar_belakang, 
                    l.rekomendasi, 
                    l.tanggal_tindak_lanjut, 
                    l.uraian_tindak_lanjut
            FROM laporan_lks_bipartit l
            JOIN ba_perubahan ba_ubah ON l.ba_perubahan_id = ba_ubah.id";


        if ($baUbah !== null) {
            $sql .= " where ba_ubah.id = :ba_ubah";
            $params['ba_ubah'] = $baUbah;
        }

        if ($start_date && $end_date) {
            if (strpos(strtolower($sql), "where") !== false) {
                $sql .= " and l.tanggal BETWEEN :start_date AND :end_date";
            } else {
                $sql .= " where l.tanggal BETWEEN :start_date AND :end_date";
            }
            $params['start_date'] = $start_date;
            $params['end_date'] = $end_date;
        }

        $sql .= " ORDER BY l.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $laporans = $stmt->fetchAll(); // digunakan di views/

        $stmt = $this->db->prepare("select id , name from ba_perubahan");
        $stmt->execute();
        $baPerubahans = $stmt->fetchAll(); // digunakan di views/


        include 'view/lks-bipartit/laporan/index.php';
    }

    public function exportToPdf()
    {
        $start_date = $_GET['start_date'] ?? null;
        $end_date = $_GET['end_date'] ?? null;
        $unit = $_GET['ba_perubahan_id'] ?? null;
        $time_start = $_POST["time_start"];
        $time_end = $_POST["time_end"];
        $place = $_POST["place"];
        $agenda = $_POST["agenda"];
        $member = $_POST["member"];

        $dateTable = "";
        $fmt = new IntlDateFormatter(
            'id_ID', // Locale untuk bahasa Indonesia
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE
        );
        $params = [];

        if ($unit !== null) {
            $sql = "SELECT l.id, u.name as unit_name, l.tanggal, l.topik_bahasan, l.latar_belakang, l.rekomendasi, l.tanggal_tindak_lanjut, l.uraian_tindak_lanjut
            FROM laporan_lks_bipartit l
            JOIN units u ON l.ba_perubahan_id = u.id where l.ba_perubahan_id = :unit";
            $params = ["unit" => $unit];
        } else {
            $sql = "SELECT l.id, u.name as unit_name, l.tanggal, l.topik_bahasan, l.latar_belakang, l.rekomendasi, l.tanggal_tindak_lanjut, l.uraian_tindak_lanjut
            FROM laporan_lks_bipartit l
            JOIN units u ON l.ba_perubahan_id = u.id";
            $params = [];

        }


        if ($start_date && $end_date) {
            if ($start_date == $end_date) {
                $dateTable = $fmt->format(new DateTime($start_date));
            } else {
                $dateTable = $fmt->format(new DateTime($start_date)) . ' s/d ' . $fmt->format(new DateTime($end_date));
                $sql .= " WHERE l.tanggal BETWEEN :start_date AND :end_date";
                $params['start_date'] = $start_date;
                $params['end_date'] = $end_date;
            }
        }


        $sql .= " ORDER BY l.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $laporans = $stmt->fetchAll();

        // Query untuk data Ketua
        $sqlKetua = "SELECT tlb.nama_pegawai as nama_pegawai, tlb.nip_pegawai as nip_pegawai, tlb.peran as peran FROM tim_lks_bipartit tlb  join units u on tlb.unitId = u.id WHERE tlb.peran = 'Ketua' and tlb.unitId = ? LIMIT 1 ";
        $stmtKetua = $this->db->prepare($sqlKetua);
        $stmtKetua->execute([$unit]);
        $ketua = $stmtKetua->fetch(); // Mengambil satu data Ketua


        // Mulai Membuat PDF
        $html = '<h3 style="text-align: center;font-family: Arial, sans-serif;">
        LAPORAN PROGRESS KEGIATAN DAN TINDAK LANJUT LKS BIPARTIT<br>
        PT PLN (PERSERO) UNIT INDUK DISTRIBUSI SUMATERA SELATAN, JAMBI, DAN BENGKULU<br>
        
        BULAN NOVEMBER TAHUN 2024</h3>';


        if ($start_date && $end_date) {

            $html .= '<p style="font-family: Arial, sans-serif;margin:1rem"><strong>Hari dan Tanggal:</strong> ' . $dateTable . '</p>';
        }
        $html .= '<p style="font-family: Arial, sans-serif;margin:1rem"><strong>Waktu:</strong> ' . $time_start . ' - ' . $time_end . '</p>';
        $html .= '<p style="font-family: Arial, sans-serif;margin:1rem"><strong>Tempat:</strong> ' . $place . '</p>';
        $html .= '<p style="font-family: Arial, sans-serif;margin:1rem"><strong>Agenda:</strong> ' . $agenda . '</p>';
        $html .= '<p style="font-family: Arial, sans-serif;margin:1rem"><strong>Peserta:</strong> ' . $member . '</p>';
        $html .= '<table border="1" style="width:100%;">';
        $html .= ' <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Tanggal Pelaksanaan</th>
            <th rowspan="2">Topik Bahasan</th>
            <th rowspan="2">Latar Belakang Bahasan</th>
            <th rowspan="2">Rekomendasi</th>
            <th colspan="2">Tindak Lanjut</th> <!-- Gabungkan dua kolom di bawah Tindak Lanjut -->
        </tr>
        <tr>
            <th>Tanggal</th> 
            <th>Uraian</th> 
        </tr>';

        $no = 1;
        foreach ($laporans as $laporan) {
            $html .= '<tr style="font-family: Arial, sans-serif;">';
            $html .= '<td style="padding:0.8rem;">' . $no++ . '</td>';
            $html .= '<td style="padding:0.8rem;">' . $fmt->format(new DateTime($laporan["tanggal"])) . '</td>';
            $html .= '<td style="padding:0.8rem;">' . htmlspecialchars($laporan['topik_bahasan']) . '</td>';
            $html .= '<td style="padding:0.8rem;">' . htmlspecialchars($laporan['latar_belakang']) . '</td>';
            $html .= '<td style="padding:0.8rem;">' . htmlspecialchars($laporan['rekomendasi']) . '</td>';
            $html .= '<td style="padding:0.8rem;">' . $fmt->format(new DateTime($laporan["tanggal_tindak_lanjut"])) . '</td>';
            $html .= '<td style="padding:0.8rem;">' . htmlspecialchars($laporan['uraian_tindak_lanjut']) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        // Tambahkan bagian untuk Ketua
        if ($ketua) {
            $html .= '<h5 style="text-align: right;font-family: Arial, sans-serif;">KETUA TIM LKS BIPARTIT <br> PT PLN (PERSERO) UID S2JB</h5><br><br> ';
            $html .= '<p style="text-align: right;font-family: Arial, sans-serif;"><strong>' . htmlspecialchars($ketua['nama_pegawai']) . '</strong></p>';
        } else {
            $html .= '<p><strong>Data Ketua tidak ditemukan.</strong></p>';
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A3', 'landscape');

        $dompdf->render();

        $dompdf->stream("laporan_lks_bipartit.pdf", array("Attachment" => 0));
    }

    public function create()
    {
        $stmt = $this->db->prepare("SELECT id, name FROM ba_perubahan");
        $stmt->execute();
        $baPerubahans = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM anggota_serikats");
        $stmt->execute();
        $anggotas = $stmt->fetchAll();

        include 'view/lks-bipartit/laporan/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ba_perubahan_id = $_POST['ba_perubahan_id'];
            $tanggal = $_POST['tanggal'];
            $topik_bahasan = $_POST['topik_bahasan'];
            $latar_belakang = $_POST['latar_belakang'];
            $rekomendasi = $_POST['rekomendasi'];
            $tanggal_tindak_lanjut = $_POST['tanggal_tindak_lanjut'];
            $uraian_tindak_lanjut = $_POST['uraian_tindak_lanjut'];

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            $query = "INSERT INTO laporan_lks_bipartit (ba_perubahan_id, tanggal, topik_bahasan, latar_belakang, rekomendasi, tanggal_tindak_lanjut, uraian_tindak_lanjut, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$ba_perubahan_id, $tanggal, $topik_bahasan, $latar_belakang, $rekomendasi, $tanggal_tindak_lanjut, $uraian_tindak_lanjut, $createdAt, $updatedAt]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Laporan LKS Bipartit created successfully!'];

                header('Location: index.php?page=laporan-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to create Laporan LKS Bipartit!'];

                header('Location: index.php?page=laporan-create');
            }
        }
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM laporan_lks_bipartit WHERE id = ?");
        $stmt->execute([$id]);
        $laporan = $stmt->fetch();

        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        include 'view/lks-bipartit/laporan/edit.php';
    }


    public function importPdf()
    {
        // Periksa apakah ada file yang diunggah
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["pdf_file"])) {
            $pdf_id = $_POST["pdf_id"]; // ID dari tombol
            $file = $_FILES["pdf_file"];

            $file_name = basename($file["name"]);
            $file_tmp = $file["tmp_name"];
            $upload_dir = "uploads/data-lks-bipartit/"; // Folder tujuan penyimpanan

            // Pastikan folder uploads/ ada
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Simpan file ke folder uploads dengan nama unik
            $new_file_name = time() . "_" . $file_name;
            $target_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $target_path)) {  
                try {
                    // Simpan nama file ke database
                    $stmt = $this->db->prepare("UPDATE laporan_lks_bipartit SET pdf_name = ? WHERE id = ?");
                    $stmt->execute([$new_file_name, $pdf_id]);

                    // Redirect ke halaman laporan-list dengan parameter success
                    header('Location: index.php?page=laporan-list&success=1');
                    exit();

                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                } finally {
                    $stmt = null; // Tutup statement
                }
            } else {
                echo "Gagal mengunggah file ke server.";
            }
        } else {
            echo "Tidak ada file yang diunggah.";
        }
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ba_perubahan_id = $_POST['ba_perubahan_id'];
            $tanggal = $_POST['tanggal'];
            $topik_bahasan = $_POST['topik_bahasan'];
            $latar_belakang = $_POST['latar_belakang'];
            $rekomendasi = $_POST['rekomendasi'];
            $tanggal_tindak_lanjut = $_POST['tanggal_tindak_lanjut'];
            $uraian_tindak_lanjut = $_POST['uraian_tindak_lanjut'];

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE laporan_lks_bipartit SET ba_perubahan_id = ?, tanggal = ?, topik_bahasan = ?, latar_belakang = ?, rekomendasi = ?, tanggal_tindak_lanjut = ?, uraian_tindak_lanjut = ?, updated_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$ba_perubahan_id, $tanggal, $topik_bahasan, $latar_belakang, $rekomendasi, $tanggal_tindak_lanjut, $uraian_tindak_lanjut, $updatedAt, $id]);

            if ($success) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Laporan LKS Bipartit updated successfully!'];

                header('Location: index.php?page=laporan-list');
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to update Laporan LKS Bipartit!'];

                header('Location: index.php?page=laporan-create');
            }
        }
    }

    public function destroy($id)
    {
        $stmt = $this->db->prepare("DELETE FROM laporan_lks_bipartit WHERE id = ?");
        $success = $stmt->execute([$id]);

        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Laporan LKS Bipartit deleted successfully!'];

            header('Location: index.php?page=laporan-list');
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Failed to delete Laporan LKS Bipartit!'];

            header('Location: index.php?page=laporan-list');
        }
    }
}
