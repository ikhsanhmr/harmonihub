<?php
ob_start()
?>
        <section class="header-section w-100">
            <div class="col-12 container-fluid d-flex align-items-stretch flex-wrap">
                <div class="header-image flex-shrink-0 col-12 col-md-5  d-flex align-items-center justify-content-center">
                    <img src="assets/frontend/img/header.png" alt="Sample Image" class="img-fluid">
                </div>

                <!-- Header Text -->
                <div class="header-text col-12 col-md-6 col-md-7 ">
                    <h1 class="mx-4" style="color: rgb(246,79,36); font-family:sans-serif; letter-spacing: 2px; word-spacing: 1rem;font-weight:bold;">
                        Mari Bersama Ciptakan Lingkungan Kerja UID S2JB Yang Harmonis
                    </h1>


                </div>
            </div>
            <div  class="col-11 container-fluid d-flex flex-wrap">
                <div style="margin-top: 5rem;" class="col-12 col-md-5 d-flex justify-content-end">
                    <div style="box-shadow: 1rem 0px 20px rgba(0, 0, 0, 0.2), -1rem 0px 20px rgba(0, 0, 0, 0.2);" class="card col-10 col-sm-6 col-md-7">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <img src="assets/frontend/img/iconProfil.png" style="margin-right: 5px;" width="30" height="30" alt="Profile Icon">
                                        <p class="ms-2 mb-0" style="font-weight: bold;color: rgb(246,79,36);">Update Profile</p>
                                    </div>

                                    <p style="margin-top: 0.5rem;" class="card-text">Ayo PLNers segera lengkapi profil Anda!
                                    .</p>
                                </div>
                    </div>
                </div>
                <div style="margin-top: 0.8rem;" class="col-12 col-md-7">
                        <div style="box-shadow: 1rem 0px 20px rgba(0, 0, 0, 0.2), -1rem 0px 20px rgba(0, 0, 0, 0.2);" class="card col-10 col-sm-6 col-md-7">
                            <div class="card-body">
                                <img src="assets/frontend/img/infosirupng.png" width="auto" height="52" style="position: relative;left: 50%; transform:translateX(-50%);" alt="Profile Icon">

                                <p class="card-text">Bersama tingkatkan pemahaman tentang Hubungan Industrial.
                                </p>
                            </div>
                        </div>
                </div>
            </div>
        </section>

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/frontend/layouts/main.php'
?>
