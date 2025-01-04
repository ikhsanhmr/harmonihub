<?php ob_start(); ?>
<section class="header-section w-100">
    <div class="container">
        <div class="row">
            <!-- Highlight Section -->
            <div class="col-lg-5 mb-3">
                <?php if (!empty($highlights)): ?>
                    <div class="card">
                        <video controls>
                            <source src="<?php echo $highlights[0]['filePath']; ?>" type="video/mp4">
                            <source src="<?php echo $highlights[0]['filePath']; ?>" type="video/webm">
                            <source src="<?php echo $highlights[0]['filePath']; ?>" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <?php else: ?>
                    <p>No highlight available.</p>
                <?php endif; ?>
            </div>

            <!-- Flyers and Videos Section -->
            <div class="col-lg-7">
                <div class="row">
                    <!-- Flyers -->
                     <div class="col-md-12 d-flex flex-wrap">
                         <div class="col-md-12 d-flex flex-wrap ">
                         <?php foreach ($flyers as $flyer): ?>
                             <div class="col-md-6 mb-3">
                                 <div class="card">
                                     <img src="<?php echo $flyer['filePath']; ?>" class="card-img-top" alt="Flyer" data-bs-toggle="modal" data-bs-target="#flyerModal-<?php echo $flyer["id"]?>">
                                     <div class="modal fade" id="flyerModal-<?php echo $flyer["id"]?>" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="<?php echo $flyer['filePath']; ?>" class="img-fluid" alt="Flyer">
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                             </div>
                         <?php endforeach; ?>
                         </div>
                         <div style="margin-bottom: 1rem;" class="col-md-12 d-flex justify-content-center ">
                            <a class="<?php echo (count($flyers) < 2) ? "d-none":""?>" href="?harmonihub=flyer&flyer_page=1">view more flyers &raquo;</a>
                        </div>
                     </div>

                    <!-- Videos -->
                    <div class="col-md-12 d-flex flex-wrap">
                         <div class="col-md-12 d-flex ">
                         <?php foreach ($videos as $video): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <video class="card-img-top" controls style="width: 100%; height: auto;">
                                        <source src="<?php echo $video['filePath']; ?>" type="video/mp4">
                                        <source src="<?php echo $video['filePath']; ?>" type="video/webm">
                                        <source src="<?php echo $video['filePath']; ?>" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            </div>

                         <?php endforeach; ?>
                         </div>
                         <div class="col-md-12 d-flex justify-content-center  ">
                         <a class="<?php echo (count($videos) < 2) ? "d-none":""?>" href="?harmonihub=video&video_page=1">view more videos &raquo;</a>
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
