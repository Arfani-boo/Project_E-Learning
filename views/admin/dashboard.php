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
                    <a href="index.php?page=manage_teachers" class="btn btn-primary">➕ Kelola Guru</a>
                </div>
                <div style="text-align:right;">
                    <h1 style="margin:0; color:#2ecc71; font-size:2.2rem;"><?= $totalGuru ?></h1>
                </div>
            </div>
        </div>

        <div class="info purple">
            <div class="info-l">
                <div class="info-user">
                    <img src="assets/image/school-flag-svgrepo-com.svg" class="icon-course" alt="siswa">
                </div>
                <div class="info-subject">
                    <div class="subject-name">Jumlah Sekolah</div>
                    <div class="subject-description">Total Sekolah terdaftar</div>
                    <a href="index.php?page=manage_schools" class="btn btn-success">🏫 Kelola Sekolah</a>
                </div>
                <div style="text-align:right;">
                    <h1 style="margin:0; color:#9b59b6; font-size:2.2rem;"><?= $totalSekolah ?></h1>
                </div>
            </div>
        </div>

        <div class="info">
            <div class="info-l">
                <div class="info-user">
                    <img src="assets/image/student-person-part-2-svgrepo-com.svg" class="icon-course" alt="siswa">
                </div>
                <div class="info-subject">
                    <div class="subject-name">Jumlah Siswa</div>
                    <div class="subject-description">Total siswa terdaftar</div>
                     <a href="index.php?page=manage_students" class="btn btn-success">➕ Kelola Siswa</a>
                </div>
                <div style="text-align:right;">
                    <h1 style="margin:0; color:#3498db; font-size:2.2rem;"><?= $totalSiswa ?></h1>
                </div>
            </div>
        </div>

        <div class="info red">
            <div class="info-l">
                <div class="info-user">
                    <img src="assets/image/admin.svg" class="icon-course" alt="siswa">
                </div>
                <div class="info-subject">
                    <div class="subject-name">Jumlah Admin</div>
                    <div class="subject-description">Total admin terdaftar</div>
                    <a href="index.php?page=manage_admin" class="btn btn-success">⚙️ Kelola Admin</a>
                </div>
                <div style="text-align:right;">
                    <h1 style="margin:0; color:#8c0132; font-size:2.2rem;"><?= $totalAdmin ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>