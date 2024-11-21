<?php
ob_start();
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Tema</p>
                    <p class="font-weight-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente at, culpa eligendi neque vitae ipsum nobis necessitatibus eum eveniet eos quas illum, quod dolorem ad aliquid. Atque sit minima quam nesciunt, necessitatibus praesentium sapiente explicabo ab omnis aperiam ex rerum.</p>
                    <h1>Tampilan Tema testing</h1>

                </div>
            </div>
        </div>
    </div>

    <?php
    $content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
    include 'view/layouts/main.php'; // Sertakan layout utama