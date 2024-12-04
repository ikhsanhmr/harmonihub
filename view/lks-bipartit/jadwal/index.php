<?php
ob_start();

use Helpers\AlertHelper;

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Agenda baru berhasil ditambahkan.');
}

if (isset($_GET['success']) && $_GET['success'] == 2) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Agenda baru berhasil dihapus.');
}

if (isset($_GET['gagal']) && $_GET['gagal'] == 1) {
    echo AlertHelper::showAlert('error', 'Gagal!', 'Gagal menambahkan agenda.');
}

if (isset($_GET['success']) && $_GET['success'] == 3) {
    echo AlertHelper::showAlert('success', 'Berhasil!', 'Agenda berhasil diupdate.');
}
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Jadwal Terdaftar</div>
                    <div class="form-group">
                        <a href="index.php?page=jadwal/create" class="btn btn-success btn-sm">tambah</a>
                        <a href="index.php?page=jadwal/data" class="btn btn-warning btn-sm">edit</a>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <div id='calendar'></div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pilih elemen dengan ID "calendar"
            const calendarEl = document.getElementById('calendar');

            // Inisialisasi FullCalendar
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth', // Tampilan default: Bulanan
                locale: 'id',
                headerToolbar: {
                    start: 'prev,next today', // Tombol di sebelah kiri
                    center: 'title', // Judul di tengah
                    end: 'dayGridMonth,timeGridWeek,timeGridDay' // Tombol di sebelah kanan
                },
                events: 'index.php?page=fectch/jadwal'
            });

            // Render kalender
            calendar.render();
        });
    </script>


    <?php
    $content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
    include 'view/layouts/main.php'; // Sertakan layout utama