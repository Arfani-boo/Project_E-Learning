<?php
$page_css = "assets/css/student/quiz_take.css";
include 'views/layouts/header.php';
?>

<div style="position: sticky; top: 0; z-index: 1000; background: white; padding: 15px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px;">
    <div class="student-container">
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <small><b>Work Status:</b></small>
            <small><span id="terjawab">0</span> / <span id="totalSoal"><?= count($soalList) ?></span> Questions Answered</small>
        </div>
        <div style="background: #eee; height: 10px; border-radius: 5px; overflow: hidden;">
            <div id="progressBarQuiz" style="background: #e67e22; height: 100%; width: 0%; transition: width 0.3s;"></div>
        </div>
    </div>
</div>

<div class="student-container">
    <div class="card" style="text-align: center; margin-top: 10px;">
        <h2>📝 TAKING EXAM</h2>
        <p style="color: gray;">Answer the questions below honestly.</p>
    </div>

    <form action="" method="POST" id="formUjian">

        <?php
        $no = 1;
        foreach ($soalList as $s): ?>
            <div class="card card-soal" style="margin-bottom: 20px; border-left: 4px solid #ddd;" id="card-<?= $s['id'] ?>">

                <h4 style="margin-bottom: 15px;">
                    Question No. <?= $no++ ?>
                </h4>

                <div style="font-size: 1.1rem; margin-bottom: 15px;">
                    <?= nl2br($s["question_text"]) ?>
                </div>

                <?php if (!empty($s['media_file'])): ?>
                    <div style="margin-bottom: 20px; padding: 10px; background: #f9f9f9; border-radius: 8px; text-align: center;">
                        <?php 
                            $media = $s['media_file'];
                            // Cek jika YouTube Link
                            if (strpos($media, 'youtube.com') !== false || strpos($media, 'youtu.be') !== false):
                                $video_id = "";
                                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $media, $match)) {
                                    $video_id = $match[1];
                                }
                        ?>
                            <div style="max-width: 100%; overflow: hidden; border-radius: 5px;">
                                <iframe width="100%" height="300" src="https://www.youtube.com/embed/<?= $video_id ?>" frameborder="0" allowfullscreen></iframe>
                            </div>

                        <?php 
                            // Cek jika Gambar (JPG/PNG/GIF)
                            elseif (preg_match('/\.(jpg|jpeg|png|gif)$/i', $media)): 
                                $img_src = (strpos($media, 'http') === 0) ? $media : "uploads/" . $media;
                        ?>
                            <img src="<?= $img_src ?>" style="max-width: 100%; max-height: 400px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">

                        <?php 
                            // Cek jika Audio (MP3/WAV)
                            elseif (preg_match('/\.(mp3|wav|ogg)$/i', $media)): 
                                $audio_src = (strpos($media, 'http') === 0) ? $media : "uploads/" . $media;
                        ?>
                            <div style="padding: 20px; background: #fff; border: 1px solid #ddd; border-radius: 50px; display: inline-block; width: 80%;">
                                <span style="font-size: 1.5rem; display: block; margin-bottom: 5px;">🎧 Listen Carefully</span>
                                <audio controls style="width: 100%;">
                                    <source src="<?= $audio_src ?>">
                                </audio>
                            </div>

                        <?php else: ?>
                            <a href="<?= $media ?>" target="_blank" class="btn btn-sm btn-outline-primary">📄 Open Attachment</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($s["question_type"] == "multiple_choice"): ?>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php foreach ($s["options"] as $opt): ?>
                            <label style="padding: 10px; border: 1px solid #eee; border-radius: 5px; cursor: pointer; display: flex; align-items: center; transition: 0.2s;" onmouseover="this.style.background='#f0f8ff'" onmouseout="this.style.background='transparent'">
                                <input type="radio"
                                     name="jawaban[<?= $s['id'] ?>]"
                                     value="<?= $opt['id'] ?>"
                                     class="input-jawaban"
                                     data-soalid="<?= $s['id'] ?>"
                                     style="margin-right: 10px;"
                                     required>
                                <span><?= $opt['option_text'] ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <textarea name="jawaban[<?= $s['id'] ?>]"
                              class="form-control input-jawaban"
                              data-soalid="<?= $s['id'] ?>"
                              rows="5"
                              placeholder="Type your answer here..."
                              style="width: 100%; padding: 10px;"></textarea>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

        <div class="card" style="text-align: center; background: #fdfdfe; padding: 20px;">
            <p>Make sure all bars on the left are green!</p>
            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Are you sure you want to submit your answers? This action cannot be undone.')">
                📤 Submit My Answers
            </button>
        </div>

    </form>
</div>

<script>
// --- Script Progress Bar & Validasi Visual ---
document.addEventListener("DOMContentLoaded", function() {
    const semuaInput = document.querySelectorAll('.input-jawaban');
    const totalSoal = parseInt(document.getElementById('totalSoal').innerText);
    const textTerjawab = document.getElementById('terjawab');
    const progressBar = document.getElementById('progressBarQuiz');

    function hitungProgress() {
        let soalTerjawabUnik = new Set(); 

        semuaInput.forEach(input => {
            // Logika: Radio Button dicek ATAU Textarea ada isinya
            if ((input.type === 'radio' && input.checked) || (input.type === 'textarea' && input.value.trim() !== '')) {
                soalTerjawabUnik.add(input.getAttribute('data-soalid'));
                // Ubah border kiri jadi HIJAU
                document.getElementById('card-' + input.getAttribute('data-soalid')).style.borderLeft = "5px solid #2ecc71";
            } else if (input.type === 'textarea' && input.value.trim() === '') {
                 // Balikin jadi ABU-ABU kalau dihapus
                 // (Hanya jika belum ada radio yg terpilih, tapi ini kan textarea)
                 document.getElementById('card-' + input.getAttribute('data-soalid')).style.borderLeft = "4px solid #ddd";
            }
        });

        // Update Text & Bar
        let jumlah = soalTerjawabUnik.size;
        textTerjawab.innerText = jumlah;
        let persen = (jumlah / totalSoal) * 100;
        progressBar.style.width = persen + "%";
        
        // Ganti warna progress bar jika selesai
        if(jumlah === totalSoal){
             progressBar.style.background = "#2ecc71"; 
        } else {
             progressBar.style.background = "#e67e22";
        }
    }

    semuaInput.forEach(input => {
        input.addEventListener('change', hitungProgress);
        input.addEventListener('input', hitungProgress);
    });
});
</script>

<?php include "views/layouts/footer.php"; ?>