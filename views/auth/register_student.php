<?php include 'views/layouts/header.php'; ?>

<div class="card auth-box">
    <h2 style="text-align:center; margin-bottom: 20px;">Daftar Siswa Baru</h2>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="index.php?page=register" method="POST">
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Asal Sekolah</label>
            <select name="school_id" class="form-control">
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

<?php include 'views/layouts/footer.php'; ?>