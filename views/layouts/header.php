<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>English LMS Pro</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <?php
    // Allow pages to inject additional stylesheets by setting $page_css (string) or
    // $page_css_arr (array) before including this header.
    if (!empty($page_css)) {
        echo '<link rel="stylesheet" href="' . htmlspecialchars($page_css) . '">';
    }
    if (!empty($page_css_arr) && is_array($page_css_arr)) {
        foreach ($page_css_arr as $css) {
            echo '<link rel="stylesheet" href="' . htmlspecialchars($css) . '">';
        }
    }
    ?>
</head>
<body>
    <nav class="navbar">
        
        <div class="container">
            <a href="index.php?page=dashboard" class="brand">🇬🇧 English LMS</a>
            <div class="nav-links">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span>Halo, <b><?= $_SESSION['full_name'] ?></b></span>
                    <?php $halaman_saat_ini = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; ?>
                    <a href="index.php?page=profile&from=<?= $halaman_saat_ini ?>" style="margin-left: 10px; margin-right: 10px; color: #ecf0f1;">Edit Profil</a>
                    <a href="index.php?page=logout" class="btn-logout">Logout</a>
                    <?php else: ?>
                    <a href="index.php?page=login">Login</a>
                    <a href="index.php?page=register">Daftar Siswa</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="main-content container">