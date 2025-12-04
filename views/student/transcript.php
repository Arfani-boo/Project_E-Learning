<?php
include 'header.php';
?>

<style>
/* ===========================================
   CARD UTAMA
=========================================== */
.card {
    background: #ffffff;
    padding: 25px 28px;
    border-radius: 14px;
    border: 1px solid #e6e6e6;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    margin-top: 20px;
}

/* Header card */
.card h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 3px;
}

.card p {
    margin: 0;
    color: #7a7a7a;
}

/* Tombol Kembali */
.card .btn {
    background: #eef1f4;
    padding: 8px 16px;
    border-radius: 8px;
    color: #333;
    text-decoration: none;
    border: 1px solid #ddd;
    transition: .2s;
}

.card .btn:hover {
    background: #e2e5e9;
}

/* Garis pembatas */
.card hr {
    margin: 18px 0;
    border: 0;
    border-top: 1px solid #eee;
}

/* ===========================================
   TABEL
=========================================== */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 0.96rem;
    color: #2c3e50;
}

/* Header tabel */
.table thead tr {
    background: #f6f7f9;
    border-bottom: 2px solid #dcdcdc;
}

.table th {
    text-align: left;
    padding: 12px 14px;
    font-weight: 600;
    font-size: 0.95rem;
}

/* Body tabel */
.table td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee;
}

/* Hover row */
.table tbody tr:hover {
    background: #f9fafc;
}

/* Kolom nilai lebih menonjol */
.table td b {
    color: #2c3e50;
}

/* ===========================================
   BADGE STATUS
=========================================== */
.badge {
    font-size: 0.85rem;
    padding: 6px 10px;
    border-radius: 6px;
    display: inline-block;
    font-weight: 600;
}

/* Lulus */
.bg-green {
    background: #2ecc71;
    color: #fff !important;
}

/* Remidi */
.bg-red {
    background: #e74c3c;
    color: #fff !important;
}

/* Menunggu koreksi */
.bg-wait {
    background: #f1c40f;
    color: #333 !important;
}

/* ===========================================
   RESPONSIVE
=========================================== */
@media (max-width: 768px) {
    .card {
        padding: 20px;
    }

    .table thead {
        display: none;
    }

    .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
    }

    .table tr {
        margin-bottom: 15px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        padding: 10px 8px;
    }

    .table td {
        border: none;
        padding: 8px 10px;
        position: relative;
    }

    .table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #777;
        display: block;
        margin-bottom: 4px;
        font-size: 0.85rem;
    }
}

</style>
<div class="container">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>📜 Transkrip Nilai</h2>
                <p style="color: gray;">Kelas: <b><?= $course['title'] ?></b></p>
            </div>
            <a href="index.php?page=course_detail&id=<?= $course['id'] ?>" class="btn" style="background: #eee;">Kembali</a>
        </div>
        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Kuis / Latihan</th>
                    <th>Tanggal Pengerjaan</th>
                    <th>Nilai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($nilaiList) == 0): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: gray;">Belum ada nilai yang terekam.</td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $no = 1;
                    while($row = mysqli_fetch_assoc($nilaiList)): 
                        
                        // 1. DETEKSI NAMA KOLOM TANGGAL (Biar ga error undefined)
                        $tgl = '';
                        if(isset($row['created_at'])) $tgl = $row['created_at'];
                        elseif(isset($row['attempt_at'])) $tgl = $row['attempt_at'];
                        elseif(isset($row['date'])) $tgl = $row['date'];
                        elseif(isset($row['waktu'])) $tgl = $row['waktu'];
                        else $tgl = date('Y-m-d H:i:s'); // Fallback (Default)

                        // 2. Cek Status
                        $ada_essay = cekAdaSoalEssay($koneksi, $row['quiz_id']);
                        $status_koreksi = 'SELESAI';
                        if ($ada_essay) {
                            $status_koreksi = cekStatusKoreksi($koneksi, $row['id']);
                        }
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['quiz_title'] ?></td>
                            
                            <td><?= date('d M Y H:i', strtotime($tgl)) ?></td>
                            
                            <td>
                                <?php if($status_koreksi == 'PENDING'): ?>
                                    <span style="color: #f39c12; font-style: italic;">(Menunggu)</span>
                                <?php else: ?>
                                    <b style="font-size: 1.1rem;"><?= $row['score'] ?></b>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($status_koreksi == 'PENDING'): ?>
                                    <span class="badge" style="background: #f1c40f; color: #333;">⏳ Menunggu Koreksi</span>
                                <?php else: ?>
                                    <?php if($row['score'] >= 70): ?>
                                        <span class="badge bg-green">✅ LULUS</span>
                                    <?php else: ?>
                                        <span class="badge" style="background: #e74c3c; color: white;">❌ REMIDI</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>