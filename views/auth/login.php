<?php include 'views/layouts/header.php'; ?>

<div class="card auth-box">
    <h2 style="text-align:center; margin-bottom: 20px;">Login Sistem</h2>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'sukses_daftar'): ?>
        <div class="alert alert-success">Pendaftaran berhasil! Silakan login.</div>
    <?php endif; ?>

    <form action="index.php?page=login" method="POST">
        <div class="form-group">
            <label>Email Address<a style="color:red; font-size:large;">*</a></label>
            <input type="text" id="email" name="email" class="form-control" placeholder="contoh@email.com">
            <small id="emailError" class="err"></small>
        </div>
        <div class="form-group">
            <label>Password<a style="color:red; font-size:large;">*</a></label>
            
            <div style="position: relative;">
                <input type="password" name="password" id="passInput" class="form-control" placeholder="******">
                
                <span id="togglePass" style="position: absolute; right: 10px; top: 10px; cursor: pointer;">
                    👁️
                </span>
            </div>
             <small id="passwordError" class="err"></small>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Masuk Sekarang</button>
    </form>

    <p style="text-align:center; margin-top: 15px;">
        Siswa baru? <a href="index.php?page=register">Daftar di sini</a>
    </p>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const email   = document.getElementById('email').value.trim();
        const password   = document.getElementById('passInput').value.trim();
        let ok = true;
        if (!validateEmail(email)) {ok = false};
        if (!validatePassword(password)) {ok = false};
        if (!ok){e.preventDefault()};
    });
});
</script>
<?php include 'views/layouts/footer.php'; ?>