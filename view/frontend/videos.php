<?php ob_start(); ?>
<section class="header-section w-100">
    <div class="container">
        <div class="row">
            <!-- Videos Section -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- Video -->
                     <div class="col-md-12 d-flex flex-wrap">
                     <nav class="col-12 d-flex justify-content-center">
                            <ul class="pagination <?= (count($videos) < 2) ? "d-none":""?>">
                                <li class="page-item">
                                    <a href="?harmonihub=video&video_page=<?= $pageVideo > 1 ?  $pageVideo - 1: $pageVideo = 1;?>" class="page-link">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $totalVideoPages; $i++) : ?>
                                    <li class="page-item <?= ($i == $pageVideo) ? 'active' : ''; ?>" aria-current="page">
                                        <a class="page-link" href="?harmonihub=video&video_page=<?= $i; ?>"><?= $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item">
                                    <a href="?harmonihub=video&video_page=<?php  echo $pageVideo < $totalVideoPages ?  $pageVideo + 1: $pageVideo; ?>" class="page-link">Next</a>
                                </li>
                            </ul>
                        </nav>
                         <div style="margin-top: 3rem;" class="col-md-12 d-flex flex-wrap ">
                         <?php foreach ($videos as $video): ?>
                             <div class="col-md-4 mb-3">
                                 <div class="card">
                                 <video class="card-img-top" controls>
                                    <source src="<?= $video['filePath']; ?>" type="video/mp4">
                                    <source src="<?= $video['filePath']; ?>" type="video/webm">
                                    <source src="<?= $video['filePath']; ?>" type="video/ogg">
                                    Your browser does not support the video tag.
                                 </video>
                                 </div>
                             </div>
                         <?php endforeach; ?>
                         </div>
                        
                     </div>

                   
                </div>
            </div>
         
        </div>
        
    </div>
</section>
<?php
$content = ob_get_clean(); 
include 'view/frontend/layouts/main.php';
?>
