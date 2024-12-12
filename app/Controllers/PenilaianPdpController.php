<?php

namespace Controllers;

use Libraries\Database;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        $stmt = $this->db->prepare("SELECT p.id, p.peran, p.kpi, p.uraian, p.hasil_verifikasi, p.nilai, p.tanggal, u.name as user_name, u.nip as user_nip, t.name as unit_name 
                                    FROM penilaian_pdp p 
                                    JOIN anggota_serikats u ON p.anggota_serikat_id = u.id
                                    JOIN units t ON p.unit_id = t.id
                                    ORDER BY p.created_at DESC;");
        $stmt->execute();
        $pdps = $stmt->fetchAll();

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
            $nilai = $_POST['nilai'];
            $tanggal = $_POST['tanggal'];

            $createdAt = date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            $query = "INSERT INTO penilaian_pdp (unit_id, user_id, anggota_serikat_id, peran, kpi, uraian, hasil_verifikasi, nilai, tanggal, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unit_id, $user_id, $anggota_serikat_id, $peran, $kpi, $uraian, $hasil_verifikasi, $nilai, $tanggal, $createdAt, $updatedAt]);

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
            $nilai = $_POST['nilai'];
            $tanggal = $_POST['tanggal'];

            $updatedAt = date('Y-m-d H:i:s');

            $query = "UPDATE penilaian_pdp SET unit_id = ?, user_id = ?, anggota_serikat_id = ?, peran = ?, kpi = ?, uraian = ?, hasil_verifikasi = ?, nilai = ?, tanggal = ?, updated_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([$unit_id, $user_id, $anggota_serikat_id, $peran, $kpi, $uraian, $hasil_verifikasi, $nilai, $tanggal, $updatedAt, $id]);

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
        // Query untuk mengambil data
        $stmt = $this->db->prepare("SELECT p.id, p.peran, p.kpi, p.uraian, p.hasil_verifikasi, p.nilai, p.tanggal, u.name as user_name, u.nip as user_nip, t.name as unit_name 
                                FROM penilaian_pdp p 
                                JOIN anggota_serikats u ON p.anggota_serikat_id = u.id
                                JOIN units t ON p.unit_id = t.id
                                ORDER BY p.created_at DESC;");
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

        // Atur zona waktu ke Indonesia
        date_default_timezone_set('Asia/Jakarta');

        // Atur locale ke bahasa Indonesia
        setlocale(LC_TIME, 'id_ID.UTF-8');
        
        // Tanggal saat ini
        $tanggalPenilaian = date('d F Y');

        // HTML template
        $html = '
    <h3 style="text-align: center; font-family: Arial, sans-serif;">BERITA ACARA PENILAIAN TIM STRATEGIS SEMESTER 1 - 2024 <br>DIREKTORAT/ UNIT INDUK
        DISTRIBUSI S2JB - UP3 JAMBI</h3>
    
    <table style="text-align: left; font-family: Arial, sans-serif; font-size: 10pt;">
        <tr>
            <td>Surat Keputusan Direksi/ General Manager</td>
            <td>:</td>
            <td>0048.K/GM/2024</td>
        </tr>
        <tr>
            <td>Judul Penugasan</td>
            <td>:</td>
            <td>TIM STRATEGIS LKS BIPARTIT UID S2JB</td>
        </tr>
        <tr>
            <td>Status Penugasan</td>
            <td>:</td>
            <td>Selesai / Belum Selesai</td>
        </tr>
        <tr>
            <td>Bersifat Strategis untuk Pencapaian Kinerja/ Strategis Perusahaan</td>
            <td>:</td>
            <td>Ya / Tidak</td>
        </tr>
    </table>
    <table border="1" style="margin-top: 2rem; font-family: Arial, sans-serif; width: 100%; border-collapse: collapse; font-size: 10pt;">
        <thead>
            <tr>
                <th rowspan="2" style="padding: 8px;">No</th>
                <th rowspan="2" style="padding: 8px;">NIP Pegawai</th>
                <th rowspan="2" style="padding: 8px;">Nama Pegawai</th>
                <th rowspan="2" style="padding: 8px;">Peran</th>
                <th colspan="4">Evaluasi oleh Ketua Tim/Pejabat pemberi tugas</th>
            </tr>
            <tr>
                <th style="padding: 8px;">Tidak tercantum KPI</th>
                <th style="padding: 8px;">Bukan uraian jabatan</th>
                <th style="padding: 8px;">Hasil verifikasi <em>(Ya / Tidak)</em></th>
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
            <td style="padding: 8px; text-align: center;">' . htmlspecialchars($pdp['nilai']) . '</td>
        </tr>';
        }

        $html .= '
        </tbody>
    </table>
    <div style="margin-top: 20px; font-family: Arial, sans-serif; position: relative; page-break-inside: avoid;">
        <div style="text-align: right; font-size: 12px; margin-top: 5px;">
            Tanggal Penilaian:'. htmlspecialchars($tanggalPenilaian) .
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
    }

}
