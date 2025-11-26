<?php include 'views/layouts/header.php'; ?>

<div class="container">
    <div class="card" style="border-left: 5px solid #f1c40f;">
        <h2>📋 Koreksi Jawaban Esai</h2>
        <p>Daftar jawaban siswa yang perlu dinilai manual.</p>
    </div>

    <?php if(mysqli_num_rows($listJawaban) == 0): ?>
        <div class="alert alert-success" style="text-align: center; margin-top: 20px;">
            <h3>🎉 Kerja Bagus!</h3>
            <p>Tidak ada jawaban esai yang perlu dikoreksi saat ini.</p>
        </div>
    <?php else: ?>

        <div style="display: grid; gap: 20px; margin-top: 20px;">
            <?php while($row = mysqli_fetch_assoc($listJawaban)): ?>
                
                <div class="card">
                    <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 15px; border-left: 3px solid #3498db;">
                        <small style="color: gray; font-weight: bold;">INFO SISWA & KUIS</small><br>
                        <b>👤 <?= $row['student_name'] ?></b> <br>
                        📚 <?= $row['quiz_title'] ?>
                    </div>

                    <p style="font-weight: bold; margin-bottom: 5px;">Pertanyaan:</p>
                    <div style="background: #eef2f3; padding: 10px; border-radius: 5px; font-style: italic; margin-bottom: 15px;">
                        "<?= nl2br($row['question_text']) ?>"
                    </div>

                    <p style="font-weight: bold; margin-bottom: 5px;">Jawaban Siswa:</p>
                    <div style="background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                        <?= nl2br($row['answer_text']) ?>
                    </div>

                    <form action="index.php?page=grade_essay" method="POST" style="background: #fdf2ce; padding: 15px; border-radius: 5px; border: 1px dashed #f1c40f;">
                        
                        <input type="hidden" name="answer_id" value="<?= $row['answer_id'] ?>">
                        <input type="hidden" name="attempt_id" value="<?= $row['attempt_id'] ?>">
                        
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <label style="font-weight: bold;">Beri Nilai:</label>
                            
                            <input type="number" name="score" class="form-control" 
                                   style="width: 100px;" 
                                   min="0" max="<?= $row['max_points'] ?>" 
                                   required placeholder="0 - <?= $row['max_points'] ?>">
                            
                            <span style="color: gray;">(Max: <?= $row['max_points'] ?> Poin)</span>
                            
                            <button type="submit" class="btn btn-primary">
                                ✅ Simpan Nilai
                            </button>
                        </div>
                    </form>

                </div>

            <?php endwhile; ?>
        </div>

    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>