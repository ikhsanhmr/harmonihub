<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HarmoniHub - Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="resources/vendors/feather/feather.css">
    <link rel="stylesheet" href="resources/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="resources/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="resources/css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="resources/images/logo_harmoni_hub.png" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-md-4 mx-auto">
                        <div class="auth-form-light text-center py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="resources/images/logo_harmoni_hub.png" alt="logo">
                            </div>
                            <h4>Halo! Selamat Datang</h4>
                            <h6 class="font-weight-light">Login untuk melanjutkan.</h6>

                            <form class="pt-3" action="index.php?page=do-login" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" name="username" id="username" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Password" required>
                                </div>
                                <div class="form-group text-right">
                                    <input type="checkbox" id="toggle-password" onclick="togglePassword()"> <label for="toggle-password">Show Password</label>
                                </div>  
                                <div class="mt-3">
                                    <button class="btn btn-block btn-primary btn-lg font-weight-medium" type="submit">SIGN IN</button>
                                </div>
                                <!-- <div class="text-center mt-4 font-weight-light">
                                    Don't have an account? <a href="register.html" class="text-primary">Create</a>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="resources/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="resources/js/off-canvas.js"></script>
    <script src="resources/js/hoverable-collapse.js"></script>
    <script src="resources/js/template.js"></script>
    <script src="resources/js/settings.js"></script>
    <script src="resources/js/todolist.js"></script>

    <script src="resources/js/script.js"></script>
    <!-- endinject -->
</body>

</html>