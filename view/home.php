<?php
ob_start(); // Mulai output buffering

$username = $_SESSION['username'] ?? 'Guest';
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome <?= htmlspecialchars($username); ?>!</h3>
                    <h6 class="font-weight-normal mb-0">All systems are running smoothly!</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card tale-bg">
                <div class="card-people mt-auto">
                    <img src="resources/images/people.svg" alt="people">
                    <div class="weather-info">
                        <div class="d-flex">
                            <div>
                                <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>31<sup>C</sup></h2>
                            </div>
                            <div class="ml-2">
                                <!-- <h4 class="location font-weight-normal">Bangalore</h4> -->
                                <h6 class="font-weight-normal">Indonesia</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin transparent">
            <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-tale">
                        <div class="card-body">
                            <p class="mb-4">Pengguna</p>
                            <p class="fs-30 mb-2"><?= htmlspecialchars($data['total_pengguna']); ?></p>
                            <!-- <p>10.00% (30 days)</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-dark-blue">
                        <div class="card-body">
                            <p class="mb-4">Serikat</p>
                            <p class="fs-30 mb-2"><?= htmlspecialchars($data['total_serikat']); ?></p>
                            <!-- <p>22.00% (30 days)</p> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                    <div class="card card-light-blue">
                        <div class="card-body">
                            <p class="mb-4">Unit</p>
                            <p class="fs-30 mb-2"><?= htmlspecialchars($data['total_unit']); ?></p>
                            <!-- <p>2.00% (30 days)</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                    <div class="card card-light-danger">
                        <div class="card-body">
                            <p class="mb-4">Laporan LKS</p>
                            <p class="fs-30 mb-2"><?= htmlspecialchars($data['total_laporan']); ?></p>
                            <!-- <p>0.22% (30 days)</p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama
