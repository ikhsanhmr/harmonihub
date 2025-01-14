<?php ob_start(); ?>
<section class="header-section w-100">
    <div class="container">
        <div class="row">
            <!-- Flyers Section -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- Flyers -->
                     <div class="col-md-12 d-flex flex-wrap">
                     <nav class="col-12 d-flex justify-content-center">
                            <ul class="pagination <?= (count($flyers) < 2) ? "d-none":""?>">
                                <li class="page-item">
                                    <a href="?harmonihub=flyer&flyer_page=<?= $pageFlyer > 1 ?  $pageFlyer - 1: $pageFlyer = 1;?>" class="page-link">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $totalFlyerPages; $i++) : ?>
                                    <li class="page-item <?= ($i == $pageFlyer) ? 'active' : ''; ?>" aria-current="page">
                                        <a class="page-link" href="?harmonihub=flyer&flyer_page=<?= $i; ?>"><?= $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item">
                                    <a href="?harmonihub=flyer&flyer_page=<?php  echo $pageFlyer < $totalFlyerPages ?  $pageFlyer + 1: $pageFlyer; ?>" class="page-link">Next</a>
                                </li>
                            </ul>
                        </nav>
                         <div class="col-md-12 d-flex flex-wrap ">
                         <?php foreach ($flyers as $flyer): ?>
                             <div class="col-md-4 mb-3">
                                 <div class="card">
                                     <img src="<?= $flyer['filePath']; ?>" class="card-img-top" alt="Flyer" data-bs-toggle="modal" data-bs-target="#flyerModal-<?= $flyer["id"]?>">
                                     <div class="modal fade" id="flyerModal-<?= $flyer["id"]?>" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="<?= $flyer['filePath']; ?>" class="img-fluid" alt="Flyer">
                                            </div>
                                            </div>
                                        </div>
                                    </div>
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
