<?php include 'views/layouts/header.php'; ?>

<?php

if (isset($_GET['edit_teacher'])) {
    $title = "Edit Data Guru";
    $form_name = 'update_teacher';
    $hidden_id = $user['id'];
    $back_page = $_GET['back'] ?? 'manage_teachers';

} elseif (isset($_GET['edit_school'])) {
    $title = "Edit Data Sekolah";
    $form_name = 'update_school';
    $hidden_id = $school['id'];
    $back_page = $_GET['back'] ?? 'manage_schools';
    
} else {

    $title = "Edit Profil Saya";
    $form_name = 'update_profile';
    $hidden_id = $_SESSION['user_id'];
    $back_page = $_GET['back'] ?? 'dashboard';
}
?>

<div class="card auth-box" style="max-width: 600px;">
    <h2><?= $title ?></h2>
    <hr>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" style="margin-bottom: 15px;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="index.php<?= '?' . http_build_query($_GET) ?>" method="POST">
        <input type="hidden" name="<?= $form_name ?>" value="1">
        <input type="hidden" name="id" value="<?= $hidden_id ?>">
        <input type="hidden" name="back" value="<?= $back_page ?>">
        <?php if ($form_name === 'update_teacher'): // Mode Guru ?>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>Asal Sekolah</label>
                <select name="school_id" class="form-control" required>
                    <option value="">-- Pilih Sekolah --</option>
                    <?php foreach ($daftar_sekolah as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ($s['id'] == $user['school_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Password Baru <small>(kosongkan jika tidak diganti)</small></label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan bila tidak berubah">
            </div>

        <?php elseif ($form_name === 'update_school'): ?>
            <div class="form-group">
                <label>Nama Sekolah</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($school['name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Alamat (Opsional)</label>
                <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($school['address'] ?? '') ?></textarea>
            </div>

        <?php else: // Mode edit profil sendiri ?>
            <input type="hidden" name="from" value="<?= $kembali_ke ?? 'dashboard' ?>">
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" value="<?= $user['full_name'] ?>" readonly style="background: #eee;">
                <small>Hubungi admin jika ingin mengubah nama.</small>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" id="email" required>
                <small id="emailError" class="err"></small>
            </div>
            <?php if ($_SESSION['role'] != 'admin'): ?>
                <div class="form-group">
                    <label>Asal Sekolah / Instansi</label>
                    <select name="school_id" class="form-control">
                        <option value="">-- Tidak Terikat / Umum --</option>
                        <?php foreach ($daftar_sekolah as $sekolah): ?>
                            <option value="<?= $sekolah['id'] ?>" <?= ($user['school_id'] == $sekolah['id']) ? 'selected' : '' ?>>
                                <?= $sekolah['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label>Password Baru <small>(Biarkan kosong jika tidak ingin mengubah)</small></label>
                <div style="position: relative;">
                    <input type="password" name="password" id="passInputProfile" class="form-control" placeholder="Ketik password baru...">
                    <span id="togglePassProfile" style="position: absolute; right: 10px; top: 10px; cursor: pointer;">👁️</span>
                </div>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="index.php?page=<?= $kembali_ke ?? 'dashboard' ?>" class="btn" style="background: #ccc;">Batal</a>
        </div>
    </form>
</div>

<script>
    const toggleBtn = document.getElementById('togglePassProfile');
    const passInput = document.getElementById('passInputProfile');
    
    if(toggleBtn){
        toggleBtn.addEventListener('click', function(){
            const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form');
        if (!form) return;
        form.addEventListener('submit', (e) => {
            const email = document.getElementById('email').value.trim();
            let ok = true;
            if (!validateEmail(email)) {ok = false};
            if (!ok){e.preventDefault()};
        });
    });
</script>

<?php include 'views/layouts/footer.php'; ?>
