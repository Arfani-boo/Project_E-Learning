<?php include 'views/layouts/header.php'; ?>

<div class="card" style="background: linear-gradient(to right, #6a11cb, #2575fc); color: white;">
    <h2>👋 Halo, <?= $_SESSION['full_name'] ?>!</h2>
    <p>Siap belajar Bahasa Inggris hari ini?</p>
    <br>
    <a href="index.php?page=catalog" class="btn" style="background: white; color: #2575fc; font-weight: bold;">🔍 Cari Kelas Baru</a>
</div>

<h3 style="margin: 20px 0;">Kelas Yang Sedang Saya Ikuti</h3>

<?php if(mysqli_num_rows($myClasses) == 0): ?>
    <div class="alert alert-danger">
        Kamu belum mengikuti kelas apapun. Yuk cari kelas di katalog!
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        <?php while($kelas = mysqli_fetch_assoc($myClasses)): ?>
            <div class="card">
                <h4 style="margin-bottom: 10px;"><?= $kelas['title'] ?></h4>
                <br><br>
                <p style="font-size: 0.9rem; color: gray;">
                    Bergabung sejak: <?= date('d M Y', strtotime($kelas['enrolled_at'])) ?>
                </p>
                
                <?php 
                    $persen = hitungPersentaseProgress($koneksi, $_SESSION['user_id'], $kelas['id']);
                    $warna_bar = ($persen < 50) ? '#f39c12' : '#2ecc71';
                ?>

                <div style="background: #eee; height: 10px; border-radius: 5px; margin: 15px 0;">
                    <div style="background: <?= $warna_bar ?>; height: 100%; width: <?= $persen ?>%; border-radius: 5px; transition: width 0.5s;"></div>
                </div>
                <small>Progress: <b><?= $persen ?>%</b> Selesai</small>
                
                <br><br>
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                <a href="index.php?page=course_detail&id=<?= $kelas['id'] ?>" class="btn btn-primary" style="flex: 1;">
                    Lanjut Belajar 🚀
                </a>
                
                <a href="index.php?page=dashboard&unenroll_id=<?= $kelas['id'] ?>" 
                   class="btn" 
                   style="background: #ffebee; color: #c0392b; border: 1px solid #ef9a9a;"
                   onclick="return confirm('Yakin ingin keluar dari kelas ini? Progress belajar Anda mungkin akan hilang.')">
                   ✖
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>