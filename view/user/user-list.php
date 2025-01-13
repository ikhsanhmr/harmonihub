<?php

ob_start(); // Mulai output buffering

?>

<div class="content-wrapper">
    <?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $messageClass = $message['type'] == 'success' ? 'alert-success' : 'alert-danger';
    ?>
        <div class="alert <?php echo $messageClass; ?>" role="alert">
            <?php echo $message['text']; ?>
        </div>
    <?php
        unset($_SESSION['message']);
    }
    ?>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data User</h4>
                    <div style="float: right">
                        <a href="index.php?page=user-create" class="btn btn-success btn-sm">
                            Tambah Data
                        </a>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr class="text-center">
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Profile Picture</th>
                                    <th>Tanggal Dibuat</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($users as $user) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                                        <td>
                                            <?php if ($user['profile_picture']): ?>
                                                <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="100">
                                            <?php else: ?>
                                                <span>No Picture</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('d-m-Y H:i', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <a href="index.php?page=user-edit&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="index.php?page=user-delete&id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this data?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama