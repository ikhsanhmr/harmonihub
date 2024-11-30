<?php ob_start(); ?>
<section class="header-section w-100">
    <div class="container">
        <div class="row">
            <!-- Highlight Section -->
            <div class="col-lg-5 mb-3">
                <?php if (!empty($highlights)): ?>
                    <div class="card">
                        <img src="<?php echo $highlights[0]['filePath']; ?>" class="card-img-top" alt="Highlight">
                    </div>
                <?php else: ?>
                    <p>No highlight available.</p>
                <?php endif; ?>
            </div>

            <!-- Flyers and Videos Section -->
            <div class="col-lg-7">
                <div class="row">
                    <!-- Flyers -->
                    <?php foreach ($flyers as $flyer): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <img src="<?php echo $flyer['filePath']; ?>" class="card-img-top" alt="Flyer">
                               
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Videos -->
                    <?php foreach ($videos as $video): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <img src="<?php echo $video['filePath']; ?>" class="card-img-top" alt="Video">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean(); 
include 'view/frontend/layouts/main.php';
?>
