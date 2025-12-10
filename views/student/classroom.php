<?php
$page_css = "assets/css/student/classroom.css";
include "views/layouts/header.php";
?>


<div class="student-container">

    <div class="sticky-bar">
        <a href="index.php?page=course_detail&id=<?= $course_id ?>">
            <img src="assets/image/arrow-left.svg" width="45px">
        </a>
        <div>
            <small style="color: #6b7280; display: block;">Currently studying:</small>
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
                <div style="text-align: center; padding: 40px; background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;">
                    <span style="font-size: 3rem;">🎧</span>
                    <h3 style="margin: 10px 0;">Listening Section</h3>
                    <p style="color: #6b7280; font-size: 0.9rem;">Klik tombol di bawah untuk mendengarkan audio.</p>

                    <div style="display: flex; justify-content: center; gap: 15px; margin-top: 20px;">
                        <button id="playBtn" class="btn" style="background: #10b981; color: white; padding: 10px 25px;">▶ Play</button>
                        <button id="pauseBtn" class="btn" style="background: #f59e0b; color: white; padding: 10px 25px;">⏸ Pause</button>
                        <button id="stopBtn" class="btn" style="background: #ef4444; color: white; padding: 10px 25px;">⏹ Stop</button>
                    </div>

                    <div style="position: absolute; left: -9999px; top: -9999px;">
                        <?php 
                            $video_id = "";
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $materi["content_url"], $match)) {
                                $video_id = $match[1];
                            }
                        ?>
                        <div id="youtube-player"></div>
                    </div>
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
                    ❌ Mark Incomplete
                </button>
                <span style="color: #10b981; margin-left: 10px;">✅ Already studied</span>
            <?php else: ?>
                <button type="submit" name="mark_complete" class="btn btn-success">
                    ✅ Mark Complete
                </button>
            <?php endif; ?>
        </form>
    </div>
</div>

<script src="https://www.youtube.com/iframe_api"></script>
<script>
    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('youtube-player', {
            height: '0',
            width: '0',
            videoId: '<?= $video_id ?>',
            playerVars: {
                'playsinline': 1
            },
            events: {
                'onReady': onPlayerReady
            }
        });
    }

    function onPlayerReady(event) {
        const playBtn = document.getElementById('playBtn');
        const pauseBtn = document.getElementById('pauseBtn');
        const stopBtn = document.getElementById('stopBtn');

        playBtn.addEventListener('click', () => player.playVideo());
        pauseBtn.addEventListener('click', () => player.pauseVideo());
        stopBtn.addEventListener('click', () => {
            player.stopVideo();
            player.seekTo(0);
        });
    }
</script>
<?php include "views/layouts/footer.php"; ?>
