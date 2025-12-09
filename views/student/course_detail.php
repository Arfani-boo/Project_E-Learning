<?php
$page_css = "assets/css/student/course_detail.css";
include "views/layouts/header.php";
?>

<div class="student-container">

    <div class="card" style="background: linear-gradient(to right, #2c3e50, #4ca1af); color: white;">
        <h1><?= $course["title"] ?></h1>
        <p><?= $course["description"] ?></p>
        <div style="margin-top: 10px;">
             <a href="index.php?page=dashboard" class="btn" style="background: rgba(255,255,255,0.2); color: white;">⬅ Back to Dashboard</a>
        </div>
    </div>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">

        <div style="flex: 3; min-width: 300px;">
            <?php foreach ($chapters as $bab): ?>
                <div class="card" style="padding: 15px; margin-bottom: 15px;">
                    <h3>📂 <?= $bab["title"] ?></h3>

                    <ul style="list-style: none; padding: 0; margin-top: 10px;">

                        <?php foreach ($bab["materials"] as $mat): ?>
                            <li style="border-bottom: 1px solid #eee; padding: 10px 0; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <?php
                                    $icon = "📄";
                                    if ($mat["type"] == "video") {
                                        $icon = "📺";
                                    }
                                    if ($mat["type"] == "audio") {
                                        $icon = "🎧";
                                    }
                                    ?>
                                    <span style="font-size: 1.2rem; margin-right: 10px;"><?= $icon ?></span>
                                    <b><?= $mat["title"] ?></b>
                                </div>
                                <a href="index.php?page=learning&material_id=<?= $mat[
                                    "id"
                                ] ?>" class="btn btn-primary" style="padding: 5px 15px; font-size: 0.8rem;">
                                    Start Learning
                                </a>
                            </li>
                        <?php endforeach; ?>

                    </ul>

                    <?php
                    // Cek apakah ada kuis di bab ini
                    $q_kuis = mysqli_query(
                        $koneksi,
                        "SELECT * FROM quizzes WHERE chapter_id = " .
                            $bab["id"],
                    );

                    if (mysqli_num_rows($q_kuis) > 0): ?>
                        <div style="margin-top: 15px; border-top: 2px dashed #eee; padding-top: 15px;">
                            <small style="font-weight: bold; color: #555; display: block; margin-bottom: 5px;">EXERCISES & QUIZZES:</small>

                            <?php while ($k = mysqli_fetch_assoc($q_kuis)): ?>
                                <?php // Cek Status: Apakah siswa sudah pernah mengerjakan?
                                // Cek Status: Apakah siswa sudah pernah mengerjakan?
                                // Cek Status: Apakah siswa sudah pernah mengerjakan?
                                // Fungsi cekStatusKuis ada di QuizModel.php
                                $sudah_kerja = cekStatusKuis(
                                    $koneksi,
                                    $_SESSION["user_id"],
                                    $k["id"],
                                ); ?>

                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px; background: #f9f9f9; padding: 10px; border-radius: 8px; border: 1px solid #eee;">

                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span style="font-size: 1.1rem;">📝</span>
                                        <span style="font-weight: 500;"><?= $k[
                                            "title"
                                        ] ?></span>

                                        <?php if ($sudah_kerja): ?>
                                            <span class="badge bg-green">✅ Completed</span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($sudah_kerja): ?>
                                        <a href="index.php?page=take_quiz&quiz_id=<?= $k[
                                            "id"
                                        ] ?>"
                                           class="btn"
                                           onclick="return confirm('⚠️ WARNING:\n\nDo you want to retake this quiz?\nYour old score will be DELETED and replaced with the new one.\n\nContinue?')"
                                           style="background: #95a5a6; color: white; font-size: 0.8rem; padding: 5px 15px;">
                                           🔄 Retake Quiz
                                        </a>
                                    <?php else: ?>
                                        <a href="index.php?page=take_quiz&quiz_id=<?= $k[
                                            "id"
                                        ] ?>"
                                           class="btn"
                                           style="background: #9b59b6; color: white; font-size: 0.8rem; padding: 5px 15px;">
                                           ▶ Start Quiz
                                        </a>
                                    <?php endif; ?>

                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif;
                    ?>
                    </div>
            <?php endforeach; ?>
        </div>

        <div style="flex: 1; min-width: 250px;">
            <div class="card" style="position: sticky; top: 20px;">
                <h4>📊 Statistik Belajar</h4>

                <?php
                // Panggil fungsi hitung progress
                $persen = hitungPersentaseProgress(
                    $koneksi,
                    $_SESSION["user_id"],
                    $course["id"],
                );

                // Tentukan warna bar
                $warna_bar = $persen < 50 ? "#f39c12" : "#2ecc71";
                ?>

                <p style="font-size: 1.5rem; font-weight: bold; margin: 10px 0; color: #2c3e50;">
                    <?= $persen ?>% <span style="font-size: 0.9rem; font-weight: normal; color: gray;">Completed</span>
                </p>

                <div style="background: #eee; height: 10px; border-radius: 5px; margin-bottom: 15px; overflow: hidden;">
                    <div style="background: <?= $warna_bar ?>; height: 100%; width: <?= $persen ?>%;"></div>
                </div>

                <hr>
                <p style="font-size: 0.9rem; color: #555;">Complete all materials and quizzes to get your final grade.</p>
                <br>

                <a href="index.php?page=student_transcript&course_id=<?= $course[
                    "id"
                ] ?>"
                   class="btn btn-block"
                   style="border: 1px solid #ccc; background: #fff; color: #333; text-align: center;">cek nilai 

                </a>
            </div>
        </div>

    </div>
</div>

<?php include "views/layouts/footer.php"; ?>
