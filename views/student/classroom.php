<?php
include "header.php"; ?>
<link rel="stylesheet" href="http://localhost/Project_E-Learning/assets/css/student/classroom.css">


<div class="container">

    <div class="sticky-bar">
        <a href="index.php?page=course_detail&id=<?= $course_id ?>">
            <img src="http://localhost/Project_E-Learning/assets/image/arrow-left.svg" width="45px">
        </a>
        <div>
            <small style="color: #6b7280; display: block;">Sedang mempelajari:</small>
            <h4 style="margin: 0; color:#111827;"><?= $materi["title"] ?></h4>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2><?= $materi["title"] ?></h2>
            <span class="badge"><?= strtoupper($materi["type"]) ?></span>
        </div>

        <div class="content-area">
            <?php if ($materi["type"] == "text"): ?>
                <div>
                    <?= nl2br($materi["text_content"]) ?>
                </div>

            <?php elseif ($materi["type"] == "video"): ?>
                <?php
                $video_url = $materi["content_url"];
                if (
                    strpos($video_url, "youtube.com") !== false ||
                    strpos($video_url, "youtu.be") !== false
                ) {
                    $video_url = str_replace("watch?v=", "embed/", $video_url);
                }
                ?>
                <div style="text-align: center;">
                    <iframe width="100%" height="500" src="<?= $video_url ?>" frameborder="0" allowfullscreen></iframe>
                    <p style="margin-top: 10px;">
                        <a href="<?= $materi[
                            "content_url"
                        ] ?>" target="_blank">Buka video di tab baru ↗</a>
                    </p>
                </div>

            <?php elseif ($materi["type"] == "audio"): ?>
                <div style="text-align: center; padding: 40px;">
                    <span style="font-size: 4rem;">🎧</span>
                    <h3 style="margin-top: 1rem;">Listening Section</h3>
                    <audio controls style="width: 100%; margin-top: 1rem;">
                        <source src="<?= $materi[
                            "content_url"
                        ] ?>" type="audio/mpeg">
                        Browser Anda tidak mendukung elemen audio.
                    </audio>
                </div>
            <?php endif; ?>
        </div>

        <hr style="margin: 2rem 0;">

        <form action="" method="POST">
            <?php $is_complete = cekMateriSelesai(
                $koneksi,
                $_SESSION["user_id"],
                $materi["id"],
            ); ?>

            <?php if ($is_complete): ?>
                <button type="submit" name="mark_incomplete" class="btn btn-danger">
                    ❌ Tandai Belum Selesai
                </button>
                <span style="color: #10b981; margin-left: 10px;">✅ Sudah dipelajari</span>
            <?php else: ?>
                <button type="submit" name="mark_complete" class="btn btn-success">
                    ✅ Tandai Sudah Selesai
                </button>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php include "views/layouts/footer.php"; ?>
