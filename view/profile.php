<?php
ob_start();
?>
<div class="content-wrapper">
    <?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $messageClass = $message['type'] == 'success' ? 'alert-success' : 'alert-danger';
    ?>
        <div class="alert <?= $messageClass; ?>" role="alert">
            <?= $message['text']; ?>
        </div>
    <?php
        unset($_SESSION['message']);
    }
    ?>
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="profile text-center fw-bold">
                        <h3 class="profile-title"><?= htmlspecialchars($user['name']) ?></h3>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-center">
                        <?php if ($user['profile_picture']): ?>
                            <img src="<?= $user['profile_picture']; ?>" alt="Profile Picture" width="150" height="150" style="border-radius: 100%; margin-bottom: 1rem;">
                        <?php else: ?>
                            <span class="text-muted">No Picture</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Update Profile</p>
                    <form class="forms-sample" action="index.php?page=profile/update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= \Libraries\CSRF::generateToken(); ?>">
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Nama Lengkap" value="<?= htmlspecialchars($user['name']) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?= htmlspecialchars($user['email']) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?= htmlspecialchars($user['username']) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label">Password</label>
                            <input type="checkbox" id="toggle-password" onclick="togglePassword()"> <label for="toggle-password">Show Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="profile" class="col-sm-3 col-form-label">Profile</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="profile_picture" id="profile">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama