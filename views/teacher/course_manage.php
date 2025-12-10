<?php include 'views/layouts/header.php'; ?>

<div class="container">
    
    <div class="card" style="border-left: 5px solid #3498db;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <small style="color: gray;">MANAJEMEN KELAS</small>
                <h1><?= $course['title'] ?></h1>
                <span class="badge bg-blue"><?= strtoupper($course['level']) ?></span>
            </div>
            <div>
                <a href="index.php?page=dashboard" class="btn" style="background: #eee;">Kembali</a>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 20px; align-items: flex-start;">
        
        <div style="flex: 2;">
            
            <?php if(empty($chapters)): ?>
                <div class="alert alert-danger">Belum ada Bab/Modul. Silakan tambah Bab baru di sebelah kanan 👉</div>
            <?php endif; ?>

            <?php foreach($chapters as $bab): ?>
                
                <div class="card" style="margin-bottom: 20px; padding: 15px;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                        
                        <h3 style="margin: 0;">📂 <?= $bab['title'] ?></h3>

                        <div style="display: flex; gap: 10px; align-items: center;">
                            
                            <a href="index.php?page=course_detail&id=<?= $course['id'] ?>&hapus_chapter=<?= $bab['id'] ?>" 
                               class="btn"
                               onclick="return confirm('Yakin hapus modul ini? Semua materi di dalamnya akan hilang.')"
                               style="background: #e74c3c; color: white; font-size: 0.8rem; padding: 8px 15px; border-radius: 5px;">
                               Hapus Modul
                            </a>

                            <a href="index.php?page=manage_materials&chapter_id=<?= $bab['id'] ?>&course_id=<?= $course['id'] ?>" 
                               class="btn btn-success" 
                               style="font-size: 0.8rem; padding: 8px 15px; border-radius: 5px;">
                               + Materi
                            </a>
                        </div>

                    </div>

                    <div style="background: #f9f9f9; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px dashed #ddd;">
                        <small style="font-weight: bold; color: #555;">KUIS / UJIAN:</small>
                        
                        <?php
                            $q_kuis = mysqli_query($koneksi, "SELECT * FROM quizzes WHERE chapter_id = " . $bab['id']);
                            while($k = mysqli_fetch_assoc($q_kuis)):
                        ?>
                            <div style="display: flex; align-items: center; gap: 10px; margin-top: 8px; background: white; padding: 5px; border-radius: 4px; border: 1px solid #eee;">
                                <span style="flex: 1; font-size: 0.9rem;">📝 <?= $k['title'] ?></span>
                                
                                <a href="index.php?page=edit_quiz&id=<?= $k['id'] ?>" class="btn" style="background: #f1c40f; color: #333; font-size: 0.7rem; padding: 2px 8px;">Edit</a>
                                <a href="index.php?page=course_detail&id=<?= $course['id'] ?>&hapus_quiz=<?= $k['id'] ?>" 
                                   class="btn" style="background: #e74c3c; color: white; font-size: 0.7rem; padding: 2px 8px;"
                                   onclick="return confirm('Yakin hapus kuis ini?')">Hapus</a>
                            </div>
                        <?php endwhile; ?>

                        <form action="index.php?page=create_quiz" method="POST" style="margin-top: 10px;">
                            <input type="hidden" name="chapter_id" value="<?= $bab['id'] ?>">
                            <input type="hidden" name="title" value="Latihan Soal: <?= $bab['title'] ?>">
                            <button type="submit" class="btn" style="background: #9b59b6; color: white; font-size: 0.8rem; width: 100%;">+ Buat Kuis Baru</button>
                        </form>
                    </div>

                    <ul style="list-style: none; margin-top: 10px; padding-left: 0;">
                        <?php if(empty($bab['materials'])): ?>
                            <li style="color: gray; font-style: italic; font-size: 0.9rem;">Belum ada materi pelajaran.</li>
                        <?php else: ?>
                            <?php foreach($bab['materials'] as $mat): ?>
                                <li style="margin-bottom: 8px; padding: 8px; background: #f8f9fa; border-radius: 4px; display: flex; justify-content: space-between; border: 1px solid #eee;">
                                    <span>
                                        <?php if($mat['type']=='video') echo '📺'; elseif($mat['type']=='audio') echo '🎧'; else echo '📄'; ?>
                                        <?= $mat['title'] ?>
                                    </span>
                                    <small style="color: gray;"><?= strtoupper($mat['type']) ?></small>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <div class="card" style="background: #fdfdfe;">
                <h4>➕ Tambah Bab / Modul Baru</h4>
                <hr>
                <form action="" method="POST" id="newBab">
                    <input type="hidden" name="add_chapter" value="1">
                    
                    <div class="form-group">
                        <label>Judul Bab<a style="color:red; font-size:large;">*</a></label>
                        <input type="text" name="chapter_title" class="form-control" id="bab" placeholder="Misal: Chapter 1 - Introduction">
                        <small id="babError" class="err"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Urutan Bab<a style="color:red; font-size:large;">*</a></label>
                        <input type="text" name="sequence_order" class="form-control" value="1" id="urut">
                        <small id="urutBabError" class="err"></small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Simpan Bab</button>
                </form>
            </div>
            <a href="index.php?page=student_list&course_id=<?= $course['id'] ?>" class="btn btn-info btn-sm">👥 Lihat Siswa</a>
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('newBab');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const bab  = document.getElementById('bab').value.trim();
        const urut   = document.getElementById('urut').value.trim();
        let ok = true;
        if (!validateBabTitle(bab)) {ok = false};
        if (!validateUrutanB(urut)) {ok = false};
        if (!ok){e.preventDefault()};
    });
});
</script>
<?php include 'views/layouts/footer.php'; ?>