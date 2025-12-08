<?php
// Use student dashboard styles for consistent look
$page_css = 'assets/css/student/dashboard.css';
include 'views/layouts/header.php';
?>

<?php $page_title = 'Ruang Guru - Kelas Saya'; ?>

<div class="section">
    <div class="dashboard-header">
        <h2 style="margin:0;">🎓 Ruang Guru: Kelas Saya</h2>
        <div class="header-buttons">
            <a href="index.php?page=grading_list" class="btn" style="background: #f1c40f; color: #333; font-weight: bold;">📋 Koreksi Jawaban</a>
            <a href="index.php?page=manage_course" class="btn btn-primary">➕ Buat Kelas Baru</a>
        </div>
    </div>

    <div class="course-grid">
        <?php if (mysqli_num_rows($myCourses) == 0): ?>
            <div class="empty-state">
                <h3>Anda belum membuat kelas apapun.</h3>
                <p>Gunakan tombol "Buat Kelas Baru" untuk menambahkan kelas.</p>
            </div>
        <?php else: ?>
            <?php
            $colors = ["", "blue", "cream", "purple"]; $i = 0;
            while ($course = mysqli_fetch_assoc($myCourses)):
                $theme = $colors[$i % count($colors)]; $i++;
            ?>
                <div class="info <?= $theme ?>">
                    <a href="index.php?page=course_detail&id=<?= $course['id'] ?>" class="info-l">
                        <div class="info-user">
                            <img src="assets/image/book-icon.svg" class="icon-course" alt="course">
                        </div>
                        <div class="info-subject">
                            <div class="subject-name"><?= htmlspecialchars($course['title']) ?></div>
                            <div class="subject-description"><?= substr($course['description'],0,100) ?>...</div>
                            <div style="margin-top:10px; font-size:13px; color:#7f8c8d;">
                                👥 <b><?= $course['student_count'] ?></b> Siswa • 📚 <b><?= $course['total_materi'] ?></b> Materi
                            </div>
                        </div>
                    </a>

                    <div class="btn-action-group" style="padding:15px;">
                        <a href="index.php?page=manage_course&edit_id=<?= $course['id'] ?>" class="btn-action" style="background:#f1c40f; color:#333; font-weight:700">Edit</a>
                        <a href="index.php?page=manage_course&hapus_id=<?= $course['id'] ?>" class="btn-action" style="background:#e74c3c; color:#fff;" onclick="return confirm('⚠️ PERINGATAN: Menghapus kelas ini akan menghapus SELURUH DATA di dalamnya. Lanjutkan?')">Hapus Course</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>