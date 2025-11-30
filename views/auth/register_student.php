<?php include 'views/layouts/header.php'; ?>

<div class="card auth-box">
    <h2 style="text-align:center; margin-bottom: 20px;">Daftar Siswa Baru</h2>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="index.php?page=register" method="POST">
        <div class="form-group">
            <label>Nama Lengkap<a style="color:red; font-size:large;">*</a></label>
            <input type="text" name="full_name" class="form-control" id="nama">
            <small id="fullNameError" class="err"></small>
        </div>
        <div class="form-group">
            <label>Email<a style="color:red; font-size:large;">*</a></label>
            <input type="text" name="email" class="form-control" id="email">
            <small id="emailError" class="err"></small>
        </div>
        <div class="form-group">
            <label>Password<a style="color:red; font-size:large;">*</a></label>
            <input type="password" name="password" class="form-control" id="password">
            <small id="passwordError" class="err"></small>
        </div>
        <div class="form-group">
            <label>Asal Sekolah</label>
            <select name="school_id" class="form-control" id="sekolah">
                <option value="">-- Pilih Sekolah (Jika Ada) --</option>
                <?php while($row = mysqli_fetch_assoc($sekolah)): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
            <small style="color:gray;">Kosongkan jika umum/independen</small>
        </div>
        <button type="submit" class="btn btn-success btn-block">Daftar Akun</button>
    </form>

    <p style="text-align:center; margin-top: 15px;">
        Sudah punya akun? <a href="index.php?page=login">Login</a>
    </p>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const email   = document.getElementById('email').value.trim();
        const password   = document.getElementById('password').value.trim();
        const nama   = document.getElementById('nama').value.trim();
        let ok = true;
        if (!validateEmail(email)) {ok = false};
        if (!validatePassword(password)) {ok = false};
        if (!validateFullName(nama)) {ok = false};
        if (!ok){e.preventDefault()};
    });
});
</script>
<?php include 'views/layouts/footer.php'; ?>