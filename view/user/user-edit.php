<?php

ob_start(); // Mulai output buffering

?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Data User</h4>
                    <form action="index.php?page=user-update&id=<?= $user['id']; ?>" method="POST" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role_id">Role</label>
                                    <select class="form-control" id="role_id" name="role_id" required>
                                        <?php foreach ($roles as $role): ?>
                                            <option <?= $role['id'] == $user['role_id'] ? 'selected' : ''; ?> id="role-<?= $role['id']; ?>" value="<?= $role['id']; ?>">
                                                <?= $role['role_name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="d-flex align-content-center justify-content-between">
                                        <label for="password">Password</label>
                                        <div>
                                            <input type="checkbox" id="toggle-password" onclick="togglePassword()"> <label for="toggle-password">Show Password</label>
                                        </div>
                                    </div>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <small style="color: red;">*Kosongkan jika Anda tidak ingin mengganti password.</small>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="profile_picture">Profile Picture</label>
                                    <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                                    <?php if ($user['profile_picture']): ?>
                                        <div class="mt-2">
                                            <img src="<?= $user['profile_picture']; ?>" alt="Profile Picture" width="100">
                                            <span>Profil saat ini</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Simpan
                            </button>
                            <a href="index.php?page=user-list" class="btn btn-warning btn-sm">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const serikats = <?= json_encode($serikats); ?>;
    const units = <?= json_encode($units); ?>;

    document.getElementById('role_id').addEventListener('change', function () {
        const selectedRole = this.options[this.selectedIndex];
        const roleType = selectedRole.getAttribute('data-role-type');
        const timSelect = document.getElementById('tim');

        // Kosongkan opsi sebelumnya
        timSelect.innerHTML = '<option value="" selected disabled>Pilih Serikat / Unit</option>';

        // Isi opsi berdasarkan role type
        if (roleType === 'serikat') {
            serikats.forEach(serikat => {
                const option = document.createElement('option');
                option.value = serikat.name;
                option.textContent = serikat.name;
                timSelect.appendChild(option);
            });
        } else if (roleType === 'unit') {
            units.forEach(unit => {
                const option = document.createElement('option');
                option.value = unit.name;
                option.textContent = unit.name;
                timSelect.appendChild(option);
            });
        } else if(roleType === "admin") {
                const option = document.createElement('option');
                option.value = "superAdmin";
                option.textContent = "superadmin";
                timSelect.appendChild(option);
        } else if(roleType === "user") {
                const option = document.createElement('option');
                option.value = "user";
                option.textContent = "user";
                timSelect.appendChild(option);
        }
    });
</script>
<?php
$content = ob_get_clean(); // Simpan buffer ke dalam variabel $content
include 'view/layouts/main.php'; // Sertakan layout utama