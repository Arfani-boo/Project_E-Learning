<?php include 'views/layouts/header.php'; ?>

<div class="container">
    
    <div class="card" style="border-left: 5px solid #9b59b6; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <small style="color: gray; font-weight: bold;">QUIZ BUILDER</small>
                <h2 style="margin: 0;"><?= $quiz['title'] ?></h2>
            </div>
            <a href="index.php?page=course_detail&id=<?= $quiz['course_id'] ?>" class="btn" style="background: #eee;">✅ Selesai & Kembali</a>
        </div>
    </div>

    <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">

        <div style="flex: 1.5; min-width: 300px;">
            <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px;">📋 Daftar Pertanyaan</h3>
            
            <?php if(empty($questions)): ?>
                <div class="alert alert-danger">
                    Belum ada soal. Silakan buat soal baru di formulir sebelah kanan 👉
                </div>
            <?php endif; ?>

            <?php 
            $no = 1; 
            $total_skor_saat_ini = 0; 
            
            foreach($questions as $q): 
                // [PERBAIKAN] Gunakan 'weight' agar sesuai database
                $total_skor_saat_ini += $q['weight'];
            ?>
                <div class="card" style="position: relative; padding: 20px; margin-bottom: 15px;">
                    
                    <a href="index.php?page=delete_question&question_id=<?= $q['id'] ?>&quiz_id=<?= $quiz['id'] ?>" 
                       onclick="return confirm('Yakin hapus soal ini?')"
                       style="position: absolute; top: 15px; right: 15px; color: #e74c3c; text-decoration: none; font-size: 0.9rem; font-weight: bold;">
                       ❌ Hapus
                    </a>

                    <p style="color: #555; font-size: 0.9rem; margin-bottom: 5px;">
                        <b>No <?= $no++ ?>.</b> 
                        <span class="badge" style="background: #eee; color: #333;">
                            <?= ($q['question_type'] == 'multiple_choice') ? 'Pilihan Ganda' : 'Esai / Writing' ?>
                        </span>
                        <span class="badge" style="background: #3498db; color: white;">
                            Bobot: <?= $q['weight'] ?> Poin
                        </span>
                    </p>

                    <p style="font-size: 1.1rem; font-weight: 500; margin-bottom: 10px;">
                        <?= nl2br($q['question_text']) ?>
                    </p>

                    <?php if(!empty($q['media_file'])): ?>
                        <div style="margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            <?php 
                                $ext = strtolower(pathinfo($q['media_file'], PATHINFO_EXTENSION));
                                if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): 
                            ?>
                                <img src="uploads/<?= $q['media_file'] ?>" style="max-width: 100%; max-height: 200px; border-radius: 5px;">
                            <?php elseif(in_array($ext, ['mp3', 'wav', 'ogg'])): ?>
                                🔊 <b>Audio Listening:</b><br>
                                <audio controls style="width: 100%; margin-top: 5px;">
                                    <source src="uploads/<?= $q['media_file'] ?>" type="audio/<?= $ext ?>">
                                    Browser Anda tidak mendukung audio player.
                                </audio>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($q['question_type'] == 'multiple_choice'): ?>
                        <ul style="list-style: none; padding-left: 0;">
                            <?php foreach($q['options'] as $opt): ?>
                                <li style="padding: 5px 10px; margin-bottom: 5px; border-radius: 4px; <?= $opt['is_correct'] ? 'background: #d4edda; border: 1px solid #c3e6cb;' : 'background: #fdfdfe; border: 1px solid #eee;' ?>">
                                    <?= $opt['is_correct'] ? '✅' : '⚪' ?> <?= $opt['option_text'] ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if($q['question_type'] == 'essay'): ?>
                        <div style="background: #fff3cd; padding: 10px; border: 1px dashed #ffeeba; color: #856404; font-size: 0.9rem;">
                            📝 Siswa akan menjawab dengan mengetik paragraf (dinilai manual oleh Guru).
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>

        <div style="flex: 1; min-width: 350px; position: sticky; top: 20px;">
            <div class="card" style="background: #fdfdfe; border: 1px solid #ddd; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0;">➕ Tambah Soal Baru</h3>
                <hr>

                <form action="index.php?page=save_question" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">

                    <div class="form-group">
                        <label>Tipe Pertanyaan</label>
                        <select name="question_type" id="tipeSoal" class="form-control" onchange="gantiTipe()">
                            <option value="multiple_choice">Pilihan Ganda (Reading/Listening)</option>
                            <option value="essay">Esai (Writing)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pertanyaan / Soal<a style="color:red; font-size:large;">*</a></label>
                        <textarea name="question_text" class="form-control" rows="3" id="soal" placeholder="Tulis pertanyaan di sini..."></textarea>
                        <small id="soalError" class="err"></small>
                    </div>

                    <div class="form-group">
                        <label>Upload Media (Opsional)</label>
                        <input type="file" name="media_file" class="form-control" accept="audio/*,image/*">
                        <small style="color: gray; font-size: 0.8rem;">Upload <b>MP3</b> untuk Listening atau <b>Gambar</b> untuk Reading.</small>
                    </div>

                    <div class="form-group">
                        <label>Bobot Nilai (Poin)<a style="color:red; font-size:large;">*</a></label>
                        <input type="text" name="weight" class="form-control" id="nilai" placeholder="Contoh: 10" min="1" max="100">
                        <small id="nilaiError" class="err"></small>
                        <?php $sisa_kuota = 100 - $total_skor_saat_ini; ?>
                        <div style="margin-top: 5px; font-size: 0.85rem;">
                            Total saat ini: <b><?= $total_skor_saat_ini ?>/100</b>
                            <br>
                            <span style="color: <?= $sisa_kuota > 0 ? 'green' : 'red' ?>;">
                                Sisa yang bisa diinput: <b><?= $sisa_kuota ?></b> poin
                            </span>
                        </div>
                    </div>

                    <div id="areaPilihanGanda">
                        <label>Opsi Jawaban & Kunci:<a style="color:red; font-size:large;">*</a></label>
                        <div style="background: #f4f6f7; padding: 15px; border-radius: 8px; border: 1px solid #e0e0e0;">
                            
                            <div class="form-group" style="margin-bottom: 8px;">
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <input type="radio" name="correct_option" value="A" checked>
                                    <span style="font-weight: bold;">A.</span>
                                    <input type="text" name="options[A]" class="form-control" placeholder="Jawaban A" id="pilganA">
                                </div>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 8px;">
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <input type="radio" name="correct_option" value="B">
                                    <span style="font-weight: bold;">B.</span>
                                    <input type="text" name="options[B]" class="form-control" placeholder="Jawaban B" id="pilganB">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 8px;">
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <input type="radio" name="correct_option" value="C">
                                    <span style="font-weight: bold;">C.</span>
                                    <input type="text" name="options[C]" class="form-control" placeholder="Jawaban C" id="pilganC">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <input type="radio" name="correct_option" value="D">
                                    <span style="font-weight: bold;">D.</span>
                                    <input type="text" name="options[D]" class="form-control" placeholder="Jawaban D" id="pilganD">
                                </div>
                            </div>
                            <small id="pilganError" class="err"></small>
                            <small style="display: block; margin-top: 10px; color: #7f8c8d;">
                                🔵 Klik lingkaran (radio button) pada jawaban yang benar.
                            </small>
                        </div>
                    </div>

                    <br>
                    <button type="submit" class="btn btn-primary btn-block" style="font-weight: bold;">
                        💾 Simpan Soal
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        let ok = true;
        const type = document.getElementById('tipeSoal').value;
        const judul   = document.getElementById('soal').value.trim();
        const nilai   = document.getElementById('nilai').value.trim();
        if (!validateSoal(judul)) {ok = false};
        if (!validateNilai(nilai)) {ok = false};
        if (type === "multiple_choice"){
            const pilganA  = document.getElementById('pilganA').value.trim();
            const pilganB  = document.getElementById('pilganB').value.trim();
            const pilganC  = document.getElementById('pilganC').value.trim();
            const pilganD  = document.getElementById('pilganD').value.trim();
            if (!validatePilgan(pilganA)) {ok = false};
            if (!validatePilgan(pilganB)) {ok = false};
            if (!validatePilgan(pilganC)) {ok = false};
            if (!validatePilgan(pilganD)) {ok = false};
            }
        if (!ok){e.preventDefault()};
    });
});
function gantiTipe() {
    var tipe = document.getElementById("tipeSoal").value;
    var areaPG = document.getElementById("areaPilihanGanda");
    var inputs = areaPG.querySelectorAll('input[type="text"]');

    if(tipe == 'essay') {
        areaPG.style.display = 'none';
        inputs.forEach(function(input) { input.required = false; });
    } else {
        areaPG.style.display = 'block';
        inputs.forEach(function(input) { input.required = true; });
    }
}
</script>

<?php include 'views/layouts/footer.php'; ?>