<?php include 'views/layouts/header.php'; ?>

<div class="container">
    <h2>👥 Daftar Siswa - <?= htmlspecialchars($course['title']) ?></h2><br>
    <a href="index.php?page=course_detail&id=<?= $course['id'] ?>" class="btn" style="background-color: #6c757d; color: white; text-decoration: none; padding: 10px 18px; border-radius: 5px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-weight: 500;">⬅ Kembali
        </a>
    <br><br>
    <hr>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Tanggal Bergabung</th>
                <th>Progress Materi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($s = mysqli_fetch_assoc($siswa_list)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($s['full_name']) ?></td>
                <td><?= date('d M Y', strtotime($s['enrolled_at'])) ?></td>
                <td>
                    <?php $prog = hitungProgressSiswa($koneksi, $s['id'], $course['id']); ?>
                    <div style="background:#eee; height:10px; width:100px; display:inline-block; border-radius:5px;">
                        <div style="background:#2ecc71; height:100%; width:<?= $prog ?>%"></div>
                    </div>
                    <?= $prog ?>%
                </td>
                <td>
                    <a href="index.php?page=progress_siswa&course_id=<?= $course['id'] ?>&student_id=<?= $s['id'] ?>" class="btn btn-primary btn-sm">Lihat Detail</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'views/layouts/footer.php'; ?>