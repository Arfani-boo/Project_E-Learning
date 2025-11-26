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
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required placeholder="contoh@email.com">
        </div>
            <div class="form-group">
                <label>Password</label>
                
                <div style="position: relative;">
                    <input type="password" name="password" id="passInput" class="form-control" required placeholder="******">
                    
                    <span id="togglePass" style="position: absolute; right: 10px; top: 10px; cursor: pointer;">
                        👁️
                    </span>
                </div>  
            </div>
        <button type="submit" class="btn btn-primary btn-block">Masuk Sekarang</button>
    </form>

    <p style="text-align:center; margin-top: 15px;">
        Siswa baru? <a href="index.php?page=register">Daftar di sini</a>
    </p>
</div>

<?php include 'views/layouts/footer.php'; ?>