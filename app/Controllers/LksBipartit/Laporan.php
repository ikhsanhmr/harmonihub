<?php

namespace Controllers\LksBipartit;

use Libraries\Database;
use Dompdf\Dompdf;
use Dompdf\Options;

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

        $sql = "SELECT l.id, u.name as unit_name, l.tanggal, l.topik_bahasan, l.latar_belakang, l.rekomendasi, l.tanggal_tindak_lanjut, l.uraian_tindak_lanjut
            FROM laporan_lks_bipartit l
            JOIN units u ON l.unit_id = u.id";

        $params = [];
        if ($start_date && $end_date) {
            $sql .= " WHERE l.tanggal BETWEEN :start_date AND :end_date";
            $params['start_date'] = $start_date;
            $params['end_date'] = $end_date;
        }

        $sql .= " ORDER BY l.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $laporans = $stmt->fetchAll();

        include 'view/lks-bipartit/laporan/index.php';
    }

    public function exportToPdf()
{
    // Ambil filter tanggal dari URL
    $start_date = $_GET['start_date'] ?? null;
    $end_date = $_GET['end_date'] ?? null;

    // Query SQL dengan filter tanggal (jika ada)
    $sql = "SELECT l.id, u.name as unit_name, l.tanggal, l.topik_bahasan, l.latar_belakang, l.rekomendasi, l.tanggal_tindak_lanjut, l.uraian_tindak_lanjut
            FROM laporan_lks_bipartit l
            JOIN units u ON l.unit_id = u.id";

    $params = [];
    if ($start_date && $end_date) {
        $sql .= " WHERE l.tanggal BETWEEN :start_date AND :end_date";
        $params['start_date'] = $start_date;
        $params['end_date'] = $end_date;
    }

    $sql .= " ORDER BY l.created_at DESC";

    // Eksekusi query
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    $laporans = $stmt->fetchAll();

    // HTML untuk laporan PDF
    $html = '<h1>Laporan LKS Bipartit</h1>';
    if ($start_date && $end_date) {
        $html .= '<p><strong>Periode:</strong> ' . date('d-m-Y', strtotime($start_date)) . ' s/d ' . date('d-m-Y', strtotime($end_date)) . '</p>';
    }
    $html .= '<table border="1" cellpadding="10" cellspacing="0" style="width:100%;">';
    $html .= '<tr><th>No.</th><th>Unit</th><th>Tanggal</th><th>Topik Bahasan</th><th>Latar Belakang</th><th>Rekomendasi</th><th>Tanggal Tindak Lanjut</th><th>Uraian Tindak Lanjut</th></tr>';

    $no = 1;
    foreach ($laporans as $laporan) {
        $html .= '<tr>';
        $html .= '<td>' . $no++ . '</td>';
        $html .= '<td>' . htmlspecialchars($laporan['unit_name']) . '</td>';
        $html .= '<td>' . date('d-m-Y', strtotime($laporan['tanggal'])) . '</td>';
        $html .= '<td>' . htmlspecialchars($laporan['topik_bahasan']) . '</td>';
        $html .= '<td>' . htmlspecialchars($laporan['latar_belakang']) . '</td>';
        $html .= '<td>' . htmlspecialchars($laporan['rekomendasi']) . '</td>';
        $html .= '<td>' . date('d-m-Y', strtotime($laporan['tanggal_tindak_lanjut'])) . '</td>';
        $html .= '<td>' . htmlspecialchars($laporan['uraian_tindak_lanjut']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Inisialisasi DomPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $dompdf = new Dompdf($options);

    // Load HTML ke DomPDF
    $dompdf->loadHtml($html);

    // Atur ukuran dan orientasi halaman
    $dompdf->setPaper('A4', 'portrait');

    // Render PDF (first pass)
    $dompdf->render();

    // Output the generated PDF (pdf download)
    $dompdf->stream("laporan_lks_bipartit.pdf", array("Attachment" => 0)); // 0 untuk tampilkan di browser, 1 untuk download
}


    public function create()
    {
        $stmt = $this->db->prepare("SELECT id, name FROM units");
        $stmt->execute();
        $units = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT id, name FROM anggota_serikats");
        $stmt->execute();
        $anggotas = $stmt->fetchAll();

        include 'view/lks-bipartit/laporan/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $unit_id = $_POST['unit_id'];
            $tanggal = $_POST['tanggal'];
            $topik_bahasan = $_POST['topik_bahasan'];
            $latar_belakang = $_POST['latar_belakang'];
            $rekomendasi = $_POST['rekomendasi'];
            $tanggal_tindak_lanjut = $_POST['tanggal_tindak_lanjut'];
            $uraian_tindak_lanjut = $_POST['uraian_tindak_lanjut'];

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            $query = "INSERT INTO laporan_lks_bipartit (unit_id, tanggal, topik_bahasan, latar_belakang, rekomendasi, tanggal_tindak_lanjut, uraian_tindak_lanjut, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unit_id, $tanggal, $topik_bahasan, $latar_belakang, $rekomendasi, $tanggal_tindak_lanjut, $uraian_tindak_lanjut, $createdAt, $updatedAt]);

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

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $unit_id = $_POST['unit_id'];
            $tanggal = $_POST['tanggal'];
            $topik_bahasan = $_POST['topik_bahasan'];
            $latar_belakang = $_POST['latar_belakang'];
            $rekomendasi = $_POST['rekomendasi'];
            $tanggal_tindak_lanjut = $_POST['tanggal_tindak_lanjut'];
            $uraian_tindak_lanjut = $_POST['uraian_tindak_lanjut'];

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE laporan_lks_bipartit SET unit_id = ?, tanggal = ?, topik_bahasan = ?, latar_belakang = ?, rekomendasi = ?, tanggal_tindak_lanjut = ?, uraian_tindak_lanjut = ?, updated_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unit_id, $tanggal, $topik_bahasan, $latar_belakang, $rekomendasi, $tanggal_tindak_lanjut, $uraian_tindak_lanjut, $updatedAt, $id]);

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
