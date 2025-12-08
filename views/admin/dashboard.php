<?php
// Use student dashboard styles for consistent look
$page_css = 'assets/css/student/dashboard.css';
include 'views/layouts/header.php';
?>

<div class="section">
    <div class="dashboard-header">
        <h2 style="margin:0;">👨‍💼 Dashboard Administrator</h2>
    </div>

    <div class="course-grid">
        <div class="info blue">
            <div class="info-l">
                <div class="info-user">
                    <img src="assets/image/teacher-svgrepo-com.svg" class="icon-course" alt="guru">
                </div>
                <div class="info-subject">
                    <div class="subject-name">Jumlah Guru</div>
                    <div class="subject-description">Total guru terdaftar di sistem</div>
                </div>
                <div style="text-align:right;">
                    <h1 style="margin:0; color:#1976d2; font-size:2.2rem;"><?= $totalGuru ?></h1>
                </div>
            </div>
        </div>

        <div class="info cream">
            <div class="info-l">
                <div class="info-user">
                    <img src="assets/image/student-person-part-2-svgrepo-com.svg" class="icon-course" alt="siswa">
                </div>
                <div class="info-subject">
                    <div class="subject-name">Jumlah Siswa</div>
                    <div class="subject-description">Total siswa terdaftar</div>
                </div>
                <div style="text-align:right;">
                    <h1 style="margin:0; color:#388e3c; font-size:2.2rem;"><?= $totalSiswa ?></h1>
                </div>
            </div>
        </div>

        <div class="empty-state" style="grid-column: span 2; text-align:left;">
            <div class="card">
                <h3>Aksi Cepat</h3>
                <div style="margin-top: 10px; display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="index.php?page=manage_teachers" class="btn btn-primary">➕ Kelola Guru</a>
                    <a href="index.php?page=manage_schools" class="btn btn-success">🏫 Kelola Sekolah</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>