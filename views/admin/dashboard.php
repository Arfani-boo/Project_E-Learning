<?php include 'views/layouts/header.php'; ?>

<div class="card">
    <h2>👨‍💼 Dashboard Administrator</h2>
    <p>Selamat datang, Admin! Berikut ringkasan sistem LMS saat ini.</p>
</div>

<div style="display: flex; gap: 20px; margin-bottom: 20px;">
    <div class="card" style="flex: 1; text-align: center; background: #e3f2fd;">
        <h3>Jumlah Guru</h3>
        <h1 style="font-size: 3rem; color: #1976d2;"><?= $totalGuru ?></h1>
    </div>
    <div class="card" style="flex: 1; text-align: center; background: #e8f5e9;">
        <h3>Jumlah Siswa</h3>
        <h1 style="font-size: 3rem; color: #388e3c;"><?= $totalSiswa ?></h1>
    </div>
</div>

<div class="card">
    <h3>Aksi Cepat</h3>
    <div style="margin-top: 10px;">
        <a href="index.php?page=manage_teachers" class="btn btn-primary">➕ Tambah/Kelola Guru</a>
        <a href="index.php?page=manage_schools" class="btn btn-success">🏫 Kelola Sekolah</a>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>