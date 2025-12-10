<?php 
$page_css = "assets/css/student/dashboard.css";
include "views/layouts/header.php"; 
?>


<div class="section">
    <div class="course-grid">

        <?php
        $colors = ["", "blue", "cream", "purple"];
        $counter = 0;

        if (mysqli_num_rows($myClasses) == 0): ?>
            <div class="empty-state">
                <h3>No classes enrolled yet.</h3>
                <p>Click the "New Class" button in the header to start learning!</p>
            </div>
        <?php else: ?>

            <?php while ($kelas = mysqli_fetch_assoc($myClasses)):

                $persen = hitungPersentaseProgress(
                    $koneksi,
                    $_SESSION["user_id"],
                    $kelas["id"],
                );
                $themeClass = $colors[$counter % count($colors)];
                $counter++;
                ?>

            <div class="info <?= $themeClass ?>">
                <div class="card-menu-btn" onclick="showDeleteOverlay(this)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="12" cy="5" r="1"></circle>
                        <circle cx="12" cy="19" r="1"></circle>
                    </svg>
                </div>

                <div class="card-overlay">
                    <div class="overlay-text">Remove this course?</div>
                    <div class="overlay-actions">
                        <a href="index.php?page=dashboard&unenroll_id=<?= $kelas[
                            "id"
                        ] ?>" class="btn-action btn-delete">Remove</a>
                        <button class="btn-action btn-cancel" onclick="hideOverlay(this)">Cancel</button>
                    </div>
                </div>

                <a href="index.php?page=course_detail&id=<?= $kelas[
                    "id"
                ] ?>" class="info-l">
                    <div class="info-user">
                        <img src="assets/image/book-icon.svg">
                    </div>
                    <div class="info-subject">
                        <div class="subject-name"><?= htmlspecialchars(
                            $kelas["title"],
                        ) ?></div>
                        <div class="subject-description">
                            Joined on <?= date(
                                "d M Y",
                                strtotime($kelas["enrolled_at"]),
                            ) ?>
                        </div>
                        <div class="progress-container">
                            <div class="progress-label">
                                <span>Progress</span>
                                <span><?= $persen ?>%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: <?= $persen ?>%;"></div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <?php
            endwhile; ?>
        <?php endif;
        ?>

        <!-- Add New Course Card -->
        <a href="index.php?page=catalog" class="info add-course-card">
            <div class="add-course-content">
                <div class="add-course-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>
                <div class="add-course-text">
                    <div class="subject-name">Add New Course</div>
                    <div class="subject-description">Explore catalog and join a new class</div>
                </div>
            </div>
        </a>

    </div>
</div>

<script>
// Profile dropdown
function toggleProfileMenu() {
    var menu = document.getElementById("profileDropdown");
    menu.classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.closest('.profile-trigger')) {
        var dropdowns = document.getElementsByClassName("profile-dropdown");
        for (var i = 0; i < dropdowns.length; i++) {
            dropdowns[i].classList.remove('show');
        }
    }
}

// Delete overlay
function showDeleteOverlay(element) {
    event.stopPropagation();
    var card = element.closest('.info');
    card.querySelector('.card-overlay').classList.add('active');
}

function hideOverlay(element) {
    event.stopPropagation();
    element.closest('.card-overlay').classList.remove('active');
}
</script>


