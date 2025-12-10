<?php
$page_css = 'assets/css/teacher/student_progress.css';
include 'views/layouts/header.php';

$course = $course ?? [];
$student = $student ?? [];
$chapters = $chapters ?? [];
$nilai_list = $nilai_list ?? [];
?>

<div class="container">
    <div class="card" style="border-left: 5px solid #3498db;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>📊 Progress Detail Siswa</h2>
                <p>Siswa: <b><?= htmlspecialchars($student['full_name']) ?></b></p>
                <p>Kelas: <b><?= htmlspecialchars($course['title']) ?></b></p>
            </div>
            <div>
                <a href="index.php?page=student_list&course_id=<?= $course['id'] ?>" class="btn">⬅ Kembali</a>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 400px;">
            <div class="card">
                <h3>📚 Progress Materi per Bab</h3>
                <hr>

                <?php if (empty($chapters)): ?>
                    <p style="color: gray;">Belum ada bab atau materi.</p>
                <?php else: ?>
                    <?php foreach ($chapters as $bab): ?>
                        <div style="margin-bottom: 20px;">
                            <h4>📂 <?= htmlspecialchars($bab['title']) ?></h4>

                            <?php if (empty($bab['materials'])): ?>
                                <p style="color: gray; font-size: 0.9rem;">Tidak ada materi.</p>
                            <?php else: ?>
                                <ul style="list-style: none; padding: 0;">
                                    <?php foreach ($bab['materials'] as $mat): ?>
                                        <?php
                                        $selesai = cekMateriSelesai($koneksi, $student['id'], $mat['id']);
                                        $icon = $selesai ? '✅' : '⏳';
                                        $warna = $selesai ? '#2ecc71' : '#ccc';
                                        ?>
                                        <li style="padding: 8px; border-left: 4px solid <?= $warna ?>; background: #f9f9f9; margin-bottom: 5px;">
                                            <?= $icon ?> <?= htmlspecialchars($mat['title']) ?>
                                            <small style="float: right; color: gray;"><?= strtoupper($mat['type']) ?></small>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <div class="card">
                <h3>📝 Nilai Kuis per Bab</h3>
                <hr>

                <?php if (count($nilai_list) == 0): ?>
                    <p style="color: gray;">Belum ada kuis yang dikerjakan.</p>
                <?php else: ?>
                    <table class="table" style="font-size: 0.9rem;">
                        <thead>
                            <tr>
                                <th>Kuis</th>
                                <th>Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($nilai_list as $n): ?>
                                <?php
                                $status_koreksi = 'SELESAI';
                                $ada_essay = cekAdaSoalEssay($koneksi, $n['quiz_id']);
                                if ($ada_essay) {
                                    $status_koreksi = cekStatusKoreksi($koneksi, $n['id']);
                                }
                                $warna = $status_koreksi === 'PENDING' ? '#f39c12' : ($n['score'] >= 70 ? '#2ecc71' : '#e74c3c');
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($n['quiz_title']) ?></td>
                                    <td>
                                        <?php if ($status_koreksi === 'PENDING'): ?>
                                            <em>Menunggu</em>
                                        <?php else: ?>
                                            <b><?= $n['score'] ?></b>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: <?= $warna ?>; color: white;">
                                            <?= $status_koreksi === 'PENDING' ? '⏳' : ($n['score'] >= 70 ? '✅ Lulus' : '❌ Remidi') ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>