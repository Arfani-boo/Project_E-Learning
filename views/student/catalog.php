<?php
$page_css = "assets/css/student/catalog.css";
include "views/layouts/header.php";
?>

<div class="student-container">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2>📚 English Class Catalog</h2>
            <p style="color: gray;">Choose a class that matches your skill level.</p>
        </div>
        <a href="index.php?page=dashboard" class="btn" style="background: #ddd; color: #333;">
            ⬅ Back to Dashboard
        </a>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 30px;">

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">

        <?php if (mysqli_num_rows($allCourses) == 0): ?>
            <div class="alert alert-danger" style="grid-column: 1 / -1; text-align: center;">
                No classes available at the moment. Please contact Teacher.
            </div>
        <?php endif; ?>

        <?php while ($c = mysqli_fetch_assoc($allCourses)): ?>
            <div class="card">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span class="badge <?= $c["level"] == "beginner"
                        ? "bg-green"
                        : ($c["level"] == "intermediate"
                            ? "bg-orange"
                            : "bg-blue") ?>">
                        <?= strtoupper($c["level"]) ?>
                    </span>
                    <small style="color: gray;">ID: #<?= $c["id"] ?></small>
                </div>

                <h3 style="margin: 10px 0; min-height: 50px;"><?= $c[
                    "title"
                ] ?></h3>

                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="width: 30px; height: 30px; background: #eee; border-radius: 50%; text-align: center; line-height: 30px; margin-right: 10px;">
                        👨‍🏫
                    </div>
                    <p style="color: gray; font-size: 0.9rem; margin: 0;">
                        Teacher: <b><?= $c["teacher_name"] ?></b>
                    </p>
                </div>

                <p style="color: #555; font-size: 0.9rem; margin-bottom: 20px; line-height: 1.5;">
                    <?= substr($c["description"], 0, 80) ?>...
                </p>

                <?php if (in_array($c["id"], $sudah_diambil)): ?>

                    <button class="btn" style="background: #ecf0f1; color: gray; cursor: not-allowed; width: 100%;" disabled>
                        ✅ Already Enrolled
                    </button>
                <?php else: ?>
                    <a href="index.php?page=catalog&join_id=<?= $c["id"] ?>"
                    class="btn btn-success btn-block"
                    style="background-color: #1ae827; color: white; display: block; text-align: center;"
                    onclick="return confirm('Are you sure you want to join class &quot;<?= $c[
                        "title"
                    ] ?>&quot;?')">
                    Join Class 🚀
                    </a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include "views/layouts/footer.php"; ?>
