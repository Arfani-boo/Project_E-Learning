<?php
include "header.php"; ?>
<link rel="stylesheet" href="assets/css/student/quiz_take.css">

<div style="position: sticky; top: 0; z-index: 1000; background: white; padding: 15px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
            <small><b>Status Pengerjaan:</b></small>
            <small><span id="terjawab">0</span> / <span id="totalSoal"><?= count(
                $soalList,
            ) ?></span> Soal Terjawab</small>
        </div>
        <div style="background: #eee; height: 10px; border-radius: 5px; overflow: hidden;">
            <div id="progressBarQuiz" style="background: #e67e22; height: 100%; width: 0%; transition: width 0.3s;"></div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card" style="text-align: center; margin-top: 10px;">
        <h2>📝 SEDANG MENGERJAKAN UJIAN</h2>
        <p style="color: gray;">Jawablah pertanyaan di bawah ini dengan jujur.</p>
    </div>

    <form action="" method="POST" id="formUjian">

        <?php
        $no = 1;
        foreach ($soalList as $s): ?>
            <div class="card card-soal" style="margin-bottom: 20px; border-left: 4px solid #ddd;" id="card-<?= $s[
                "id"
            ] ?>">

                <h4 style="margin-bottom: 15px;">
                    Soal No. <?= $no++ ?>
                    <!--<span style="font-weight: normal; font-size: 0.8rem; color: #888;">
                        (Tipe: <?= $s["question_type"] == "multiple_choice"
                            ? "Pilihan Ganda"
                            : "Esai" ?>)
                    </span>-->
                </h4>

                <div style="font-size: 1.1rem; margin-bottom: 20px;">
                    <?= nl2br($s["question_text"]) ?>
                </div>

                <?php if ($s["question_type"] == "multiple_choice"): ?>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php foreach ($s["options"] as $opt): ?>
                            <label style="padding: 10px; border: 1px solid #eee; border-radius: 5px; cursor: pointer; display: flex; align-items: center;">
                                <input type="radio"
                                       name="jawaban[<?= $s["id"] ?>]"
                                       value="<?= $opt["id"] ?>"
                                       class="input-jawaban"
                                       data-soalid="<?= $s["id"] ?>"
                                       style="margin-right: 10px;"
                                       required>
                                <span><?= $opt["option_text"] ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <textarea name="jawaban[<?= $s["id"] ?>]"
                              class="form-control input-jawaban"
                              data-soalid="<?= $s["id"] ?>"
                              rows="5"
                              placeholder="Ketik jawaban Anda di sini..."
                              id="esai"></textarea>
                    <small id="esaiError" class="err"></small>
                <?php endif; ?>

            </div>
        <?php endforeach;
        ?>

        <div class="card" style="text-align: center; background: #fdfdfe;">
            <p>Pastikan semua bar oranye di atas sudah penuh!</p>
            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Yakin ingin mengumpulkan jawaban?')">
                📤 Kumpulkan Jawaban Saya
            </button>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const pilgan  = document.getElementById('pilgan').value.trim();
        const esai   = document.getElementById('esai').value.trim();
        let ok = true;
        if (!validatePilganMurid(pilgan)) {ok = false};
        if (!validateEsai(esai)) {ok = false};
        if (!ok){e.preventDefault()};
    });
});
document.addEventListener("DOMContentLoaded", function() {
    const semuaInput = document.querySelectorAll('.input-jawaban');
    const totalSoal = document.getElementById('totalSoal').innerText;
    const textTerjawab = document.getElementById('terjawab');
    const progressBar = document.getElementById('progressBarQuiz');

    // Fungsi Hitung
    function hitungProgress() {
        let soalTerjawabUnik = new Set(); // Pakai Set biar ID soal yg sama tidak dihitung 2x (khusus radio button)

        semuaInput.forEach(input => {
            // Logika: Kalau Radio Button dicek ATAU Textarea ada isinya
            if ((input.type === 'radio' && input.checked) || (input.type === 'textarea' && input.value.trim() !== '')) {
                // Simpan ID Soal ke Set
                soalTerjawabUnik.add(input.getAttribute('data-soalid'));

                // Efek Visual: Ubah warna border kiri kartu soal jadi Hijau
                document.getElementById('card-' + input.getAttribute('data-soalid')).style.borderLeft = "4px solid #2ecc71";
            } else if (input.type === 'textarea' && input.value.trim() === '') {
                 // Kalau textarea dihapus jadi kosong, balikin warna abu-abu
                 document.getElementById('card-' + input.getAttribute('data-soalid')).style.borderLeft = "4px solid #ddd";
            }
        });

        let jumlah = soalTerjawabUnik.size;
        textTerjawab.innerText = jumlah;

        // Hitung Persen
        let persen = (jumlah / totalSoal) * 100;
        progressBar.style.width = persen + "%";
    }

    // Pasang "Telinga" (Event Listener) ke semua input
    semuaInput.forEach(input => {
        input.addEventListener('change', hitungProgress); // Untuk Radio
        input.addEventListener('input', hitungProgress);  // Untuk Textarea (saat ngetik)
    });
});
</script>

<?php include "views/layouts/footer.php"; ?>
