<?php include 'views/layouts/header.php'; ?>

<div class="container">
    
    <div style="background: white; padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 15px; position: sticky; top: 0; z-index: 100;">
        
        <a href="index.php?page=course_detail&id=<?= $course_id ?>" 
           class="btn" 
           style="background: #ecf0f1; color: #2c3e50; border: 1px solid #bdc3c7; display: flex; align-items: center; gap: 5px; text-decoration: none; font-size: 0.9rem;">
            ⬅️ Kembali ke Silabus
        </a>

        <div>
            <small style="color: gray; display: block;">Sedang mempelajari:</small>
            <h4 style="margin: 0;"><?= $materi['title'] ?></h4>
        </div>
    </div>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <h2><?= $materi['title'] ?></h2>
            <span class="badge bg-blue"><?= strtoupper($materi['type']) ?></span>
        </div>

        <div class="content-area" style="min-height: 300px;">
            
            <?php if($materi['type'] == 'text'): ?>
                <div style="font-size: 1.1rem; line-height: 1.6; color: #2c3e50;">
                    <?= nl2br($materi['text_content']) ?>
                </div>

            <?php elseif($materi['type'] == 'video'): ?>
                <?php 
                    $video_url = $materi['content_url'];
                    if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                        // Simple converter for YouTube
                        $video_url = str_replace("watch?v=", "embed/", $video_url);
                    }
                ?>
                <div style="text-align: center;">
                    <iframe width="100%" height="500" src="<?= $video_url ?>" frameborder="0" allowfullscreen style="border-radius: 8px;"></iframe>
                    <p style="margin-top: 10px;">
                        <a href="<?= $materi['content_url'] ?>" target="_blank">Buka video di tab baru ↗</a>
                    </p>
                </div>

            <?php elseif($materi['type'] == 'audio'): ?>
                <div style="text-align: center; padding: 50px;">
                    <span style="font-size: 5rem;">🎧</span>
                    <h3>Listening Section</h3>
                    <br>
                    <audio controls style="width: 100%;">
                        <source src="<?= $materi['content_url'] ?>" type="audio/mpeg">
                        Browser Anda tidak mendukung elemen audio.
                    </audio>
                </div>

            <?php endif; ?>

        </div>
        <hr style="margin: 30px 0;">

        <form action="" method="POST" style="margin-top: 30px;">
            
            <?php 
            // Cek status di ActivityModel
            $is_complete = cekMateriSelesai($koneksi, $_SESSION['user_id'], $materi['id']); 
            ?>

            <?php if($is_complete): ?>
                <button type="submit" name="mark_incomplete" class="btn" style="background: #e74c3c; color: white;">
                    ❌ Tandai Belum Selesai
                </button>
                <span style="color: green; margin-left: 10px;">✅ Sudah dipelajari</span>
            
            <?php else: ?>
                <button type="submit" name="mark_complete" class="btn btn-success">
                    ✅ Tandai Sudah Selesai
                </button>
            <?php endif; ?>

        </form>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>