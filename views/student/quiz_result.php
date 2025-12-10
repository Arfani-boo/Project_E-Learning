<?php
include "views/layouts/header.php";
require_once "models/QuizModel.php";

$quiz_id = isset($_GET["quiz_id"]) ? intval($_GET["quiz_id"]) : 0;
$student_id = $_SESSION["user_id"];

$course_id = ambilCourseIdDariQuiz($koneksi, $quiz_id);

$q_nilai = mysqli_query(
    $koneksi,
    "SELECT * FROM quiz_attempts WHERE quiz_id = $quiz_id AND user_id = $student_id ORDER BY id DESC LIMIT 1",
);
$hasil = mysqli_fetch_assoc($q_nilai);
$score = isset($hasil["score"]) ? $hasil["score"] : 0;

$ada_essay = cekAdaSoalEssay($koneksi, $quiz_id);

if ($ada_essay) {
    $judul_hasil = "Answer Submitted! 📤";
    $warna_hasil = "#3498db"; 
    $pesan_utama = "This quiz contains Essay questions.";
    $pesan_sub = "Your score will appear after being graded by Teacher.";
    $tampil_score = false; 
} else {
    $tampil_score = true;
    $warna_hasil = $score >= 70 ? "#2ecc71" : "#e74c3c";
    $judul_hasil = $score >= 70 ? "Excellent! 🎉" : "Don't Give Up! 💪";
    $pesan_utama = "Your score is ready.";
    $pesan_sub = "Score automatically saved.";
}
?>

<div class="container" style="max-width: 600px; margin-top: 50px; text-align: center;">

    <div class="card" style="padding: 40px; border-top: 5px solid <?= $warna_hasil ?>;">

        <h3><?= $judul_hasil ?></h3>
        <hr style="margin: 20px 0; border: 0; border-top: 1px dashed #eee;">

        <?php if ($tampil_score): ?>
            <h1 style="font-size: 5rem; color: <?= $warna_hasil ?>; margin: 0;">
                <?= $score ?>
            </h1>
        <?php else: ?>
            <div style="font-size: 4rem; margin: 10px 0;">⏳</div>
            <h3 style="color: #f39c12;">Waiting for Grading</h3>
        <?php endif; ?>

        <h4 style="color: #555; margin-top: 15px;"><?= $pesan_utama ?></h4>
        <p style="color: gray;"><?= $pesan_sub ?></p>

        <div style="margin-top: 30px; display: flex; flex-direction: column; gap: 10px;">

            <a href="index.php?page=course_detail&id=<?= $course_id ?>"
               class="btn btn-primary"
               style="padding: 12px; font-size: 1rem;">
               ⬅️ Back to Course Materials
            </a>

            <a href="index.php?page=dashboard"
               class="btn"
               style="background: #eee; color: #555; padding: 12px;">
               🏠 To Main Dashboard
            </a>

        </div>

    </div>
</div>

<?php include "views/layouts/footer.php"; ?>
