<?php
$page_css = "assets/css/student/transcript.css";
include "views/layouts/header.php";
?>
<div class="container">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>📜 Grade Transcript</h2>
                <p style="color: gray;">Class: <b><?= $course[
                    "title"
                ] ?></b></p>
            </div>
            <a href="index.php?page=course_detail&id=<?= $course[
                "id"
            ] ?>" class="btn" style="background: #eee;">Back</a>
        </div>
        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Quiz / Exercise Title</th>
                    <th>Completion Date</th>
                    <th>Score</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($nilaiList) == 0): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: gray;">No grades recorded yet.</td>
                    </tr>
                <?php

                    // 1. DETEKSI NAMA KOLOM TANGGAL (Biar ga error undefined)
                    // Fallback (Default)

                    // 2. Cek Status
                    // 1. DETEKSI NAMA KOLOM TANGGAL (Biar ga error undefined)
                    // Fallback (Default)

                    // 2. Cek Status
                    // 1. DETEKSI NAMA KOLOM TANGGAL (Biar ga error undefined)
                    // Fallback (Default)

                    // 2. Cek Status
                    // 1. DETEKSI NAMA KOLOM TANGGAL (Biar ga error undefined)
                    // Fallback (Default)

                    // 2. Cek Status
                    else: ?>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($nilaiList)):

                        $tgl = "";
                        if (isset($row["created_at"])) {
                            $tgl = $row["created_at"];
                        } elseif (isset($row["attempt_at"])) {
                            $tgl = $row["attempt_at"];
                        } elseif (isset($row["date"])) {
                            $tgl = $row["date"];
                        } elseif (isset($row["waktu"])) {
                            $tgl = $row["waktu"];
                        } else {
                            $tgl = date("Y-m-d H:i:s");
                        }

                        $ada_essay = cekAdaSoalEssay($koneksi, $row["quiz_id"]);
                        $status_koreksi = "SELESAI";
                        if ($ada_essay) {
                            $status_koreksi = cekStatusKoreksi(
                                $koneksi,
                                $row["id"],
                            );
                        }
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row["quiz_title"] ?></td>

                            <td><?= date("d M Y H:i", strtotime($tgl)) ?></td>

                            <td>
                                <?php if ($status_koreksi == "PENDING"): ?>
                                    <span style="color: #f39c12; font-style: italic;">(Pending)</span>
                                <?php else: ?>
                                    <b style="font-size: 1.1rem;"><?= $row[
                                        "score"
                                    ] ?></b>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($status_koreksi == "PENDING"): ?>
                                    <span class="badge" style="background: #f1c40f; color: #333;">⏳ Waiting for Grading</span>
                                <?php else: ?>
                                    <?php if ($row["score"] >= 70): ?>
                                        <span class="badge bg-green">✅ PASSED</span>
                                    <?php else: ?>
                                        <span class="badge" style="background: #e74c3c; color: white;">❌ RETAKE</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    endwhile;
                    ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "views/layouts/footer.php"; ?>
