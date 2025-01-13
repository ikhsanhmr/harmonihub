# SIRU : Sharing Industrial Relations UID
Sistem Sharing Industrial Relations UID (SIRU) memungkinkan pengelolaan hubungan industrial dalam organisasi. Fitur utamanya mencakup manajemen data unit, serikat, pengguna, berita acara, LKS Bipartit, laporan, monitoring, penilaian pegawai, dan database hubungan industrial. Sistem ini juga menyediakan fitur ekspor data ke PDF dan Excel untuk berbagai data. Dengan SIRU, transparansi dan efisiensi dalam manajemen hubungan industrial dapat terjaga.

## Role
- Admin
- Unit

## Fitur
<i>*🗝️: Fitur Khusus Admin</i>
- 🗝️ Master Data {CRUD}
    - Unit: Nama unit & manager
    - Serikat: Nama & Logo
    - User: Data User (nama, role, tim, etc.)
- 🗝️ Info SIRU {CRUD}: Pengirim, Tipe File [video or img], File, Date. 
- 🗝️ Serikat
    - Anggota
      - Export to PDF
      - Import Excel
      - {CRUD}: Nama, Unit, Serikat, Keanggotaan, No KTA, NIP, etc.
    - DSP {CRUD}: Nama Serikat, Dokumen PDF 
- Berita Acara [BA Pembentukan, BA Perubahan, Apporval Pembentukan, Approval Perubahan] {CRUD}: Unit, Nomor, Keterangan, Tgl, Dokumen, Status.
- LKS Bipartit
    - Jadwal {CRUD}: Agenda dan waktunya 
    - Tema {CRUD}: untuk Agenda
    - Tim: NIP Pegawai, Nama Pegawai, Peran, Unit
    - Laporan
      - {CRUD}: Unit, Tgl, Topik, Latar Belakang, Rekomendasi, Tindak Lanjut, etc.
      - Buat Laporan & Export to PDF  
    - Monitoring {CRUD}: unit, BA Pembentukan, tgl Pendaftaran BA, SP PLN, SPPI, Serpeg, dsb. 
- Penilaian PDP Lain 
    - {CRUD}: Nama Pegawai, NIP Pegawai, Unit, Peran, Tidak Tercantum KPI, Bukan Uraian Jabatan, Hasil Verifikasi, Semester, Nilai, etc.
    - Export Excel or PDF
- Database HI
  - [HI, AD] {CRUD}: nama_dokumen, link_gdrive, kategori, tanggal, etc.
  - Filter {kategori and/or tahun}
