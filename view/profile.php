<?php
ob_start();
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <img src="https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg" alt="" width="150" height="150">
                    </div>
                    <div class="profile text-center fw-bold">
                        <p class="profile-title">ADMIN APRIYANA</p>
                        <p class="role">Admin</p>
                        <hr>
                        <p class="profile-title">Username</p>
                        <p class="title-name">adminapri</p>
                        <hr>
                        <p class="profile-title">Email</p>
                        <p class="title-name">adminapri@email.com</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Update Profile</p>
                    <form class="forms-sample">
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" placeholder="Nama Lengkap">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="username" placeholder="Username">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-sm-3 col-form-label">Re Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password-confirm" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="profile" class="col-sm-3 col-form-label">Profile</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" id="profile">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                        <button class="btn btn-light">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama