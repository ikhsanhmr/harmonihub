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

    <!-- Modal untuk menampilkan detail event -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Agenda</h5>
                    <button type="button" class="close" id="modal-close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Judul:</strong> <span id="modal-title"></span></p>
                    <p><strong>Unit:</strong> <span id="modal-unit"></span></p>
                    <p><strong>Tanggal Mulai:</strong> <span id="modal-date"></span></p>
                    <p><strong>Tanggal Selesai:</strong> <span id="modal-date-end"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="modal-close-btn">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    start: 'prev,next today',
                    center: 'title',
                    end: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: 'index.php?page=fectch/jadwal',
                eventClick: function(info) {
                    document.getElementById('modal-title').textContent = info.event.title;
                    document.getElementById('modal-unit').textContent = info.event.extendedProps.unit || 'Tidak ada unit';
                    document.getElementById('modal-date').textContent = info.event.start.toLocaleDateString('id-ID');
                    document.getElementById('modal-date-end').textContent = info.event.end.toLocaleDateString('id-ID');
                    $('#modalDetail').modal('show');
                }
            });

            calendar.render();

            // Fungsi untuk hanya menutup modal tanpa reload halaman
            function closeModal() {
                $('#modalDetail').modal('hide'); // Menutup modal
            }

            // Event listener untuk tombol "Tutup"
            document.getElementById('modal-close').addEventListener('click', closeModal);

            // Event listener untuk tombol "×"
            document.getElementById('modal-close-btn').addEventListener('click', closeModal);
        });
    </script>

    <?php
    $content = ob_get_clean();
    include 'view/layouts/main.php';
    ?>