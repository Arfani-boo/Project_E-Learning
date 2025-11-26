<?php include 'views/layouts/header.php'; ?>

<style>
    /* 1. Agar tombol Edit & Hapus tingginya sama persis */
    .btn-action-group {
        display: flex;
        gap: 10px;
        background: #f8f9fa;
        padding: 15px;
        border-top: 1px solid #eee;
    }
    .btn-action {
        flex: 1; /* Lebar dibagi rata */
        display: flex; /* Flexbox di dalam tombol */
        align-items: center; /* Teks rata tengah vertikal */
        justify-content: center; /* Teks rata tengah horizontal */
        padding: 10px;
        font-size: 0.9rem;
        border-radius: 5px;
        height: 40px; /* TINGGI DIKUNCI BIAR SAMA */
        text-decoration: none;
    }

    /* 2. Agar Header Responsif di HP */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap; /* Biar bisa turun ke bawah */
        gap: 15px;
    }

    /* Tampilan khusus HP (Layar < 768px) */
    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column; /* Jadi Atas-Bawah */
            align-items: stretch; /* Lebar tombol full */
            text-align: center;
        }
        .header-buttons {
            display: flex;
            flex-direction: column; /* Tombol atas tumpuk */
            gap: 10px;
        }
        .header-buttons .btn {
            width: 100%; /* Tombol full width */
            text-align: center;
        }
    }
</style>

<div class="dashboard-header">
    <h2 style="margin: 0;">🎓 Ruang Guru: Kelas Saya</h2>
    
    <div class="header-buttons" style="display: flex; gap: 10px;">
        <a href="index.php?page=grading_list" class="btn" style="background: #f1c40f; color: #333; font-weight: bold;">
            📋 Koreksi Jawaban
        </a>
        <a href="index.php?page=manage_course" class="btn btn-primary">
            ➕ Buat Kelas Baru
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
    
    <?php if(mysqli_num_rows($myCourses) == 0): ?>
        <div class="alert alert-info" style="grid-column: 1/-1; text-align: center;">
            Anda belum membuat kelas apapun. Yuk buat kelas pertamamu!
        </div>
    <?php endif; ?>

    <?php while($course = mysqli_fetch_assoc($myCourses)): ?>
        
        <div class="card" style="position: relative; padding: 0; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; border-radius: 10px; border: 1px solid #ddd;">
            
            <a href="index.php?page=course_detail&id=<?= $course['id'] ?>" 
               style="text-decoration: none; color: inherit; display: block; padding: 20px; flex-grow: 1;">
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span class="badge <?= $course['level'] == 'beginner' ? 'bg-green' : ($course['level'] == 'intermediate' ? 'bg-orange' : 'bg-blue') ?>">
                        <?= strtoupper($course['level']) ?>
                    </span>
                    <small style="color: gray;">#ID: <?= $course['id'] ?></small>
                </div>

                <h3 style="margin: 0 0 10px 0; color: #2c3e50; font-size: 1.3rem;">
                    <?= $course['title'] ?>
                </h3>
                
                <p style="color: #666; font-size: 0.9rem; line-height: 1.5; margin-bottom: 15px;">
                    <?= substr($course['description'], 0, 90) ?>...
                </p>

                <div style="font-size: 0.85rem; color: #888; display: flex; gap: 15px; border-top: 1px dashed #eee; padding-top: 10px;">
                    <span>👥 <b><?= $course['student_count'] ?></b> Siswa</span>
                    <span>📚 <b><?= $course['total_materi'] ?></b> Materi</span>
                </div>
            </a>

            <div class="btn-action-group">
                
                <a href="index.php?page=manage_course&edit_id=<?= $course['id'] ?>" 
                   class="btn-action" 
                   style="background: #f1c40f; color: #333; font-weight: bold;">
                   Edit
                </a>

                <a href="index.php?page=manage_course&hapus_id=<?= $course['id'] ?>" 
                   class="btn-action" 
                   style="background: #e74c3c; color: white;"
                   onclick="return confirm('⚠️ PERINGATAN:\n\nMenghapus kelas ini akan menghapus SELURUH DATA di dalamnya (Materi, Kuis, Nilai Siswa).\n\nLanjutkan?')">
                   Hapus Course
                </a>

            </div>

        </div>

    <?php endwhile; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>