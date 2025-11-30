<?php include 'views/layouts/header.php'; ?>

<div class="card auth-box" style="max-width: 600px;">
    <h2>✏️ Edit Profil Saya</h2>
    <hr>
    
    <form action="" method="POST">
        
        <input type="hidden" name="from" value="<?= isset($kembali_ke) ? $kembali_ke : 'dashboard' ?>">

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" class="form-control" value="<?= $user['full_name'] ?>" readonly style="background: #eee;">
            <small>Hubungi admin jika ingin ganti nama.</small>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="text" name="email" class="form-control" value="<?= $user['email'] ?>" id="email">
            <small id="emailError" class="err"></small>
        </div>

        <?php if($_SESSION['role'] != 'admin'): ?>
            <div class="form-group">
                <label>Asal Sekolah / Instansi</label>
                <select name="school_id" class="form-control">
                    <option value="">-- Tidak Terikat / Umum --</option>
                    <?php foreach($daftar_sekolah as $sekolah): ?>
                        <option value="<?= $sekolah['id'] ?>" 
                            <?= ($user['school_id'] == $sekolah['id']) ? 'selected' : '' ?>>
                            <?= $sekolah['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label>Password Baru <small>(Biarkan kosong jika tidak ingin mengganti)</small></label>
            <div style="position: relative;">
                <input type="password" name="password" id="passInputProfile" class="form-control" placeholder="Ketik password baru...">
                <span id="togglePassProfile" style="position: absolute; right: 10px; top: 10px; cursor: pointer;">👁️</span>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            
            <a href="index.php?page=<?= isset($kembali_ke) ? $kembali_ke : 'dashboard' ?>" class="btn" style="background: #ccc;">Batal</a>
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