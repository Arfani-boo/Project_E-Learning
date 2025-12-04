<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?= $page_title ?? "English LMS Dashboard" ?></title>
<meta content="width=device-width, initial-scale=1" name="viewport">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<style>
/* --- 1. RESET & BASIC --- */
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Roboto', sans-serif; }
body { background-color: #f4f7f6; min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; color: #34495e; }
a { text-decoration: none; color: inherit; cursor: pointer; }
button { font-family: inherit; }

/* --- 2. NAVBAR --- */
.navbar { background: #2c3e50; color: white; padding: 0.8rem 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; position: sticky; top: 0; z-index: 100; }
.nav-container { max-width: 1250px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
.nav-brand { font-size: 21px; font-weight: 700; color: white; display: flex; align-items: center; letter-spacing: 0.5px; }
.nav-right-group { display: flex; align-items: center; gap: 20px; }

/* ADD COURSE BUTTON (khusus dashboard) */
.btn-add-course {
    background-color: #3498db;
    color: #fff;
    padding: 8px 18px;
    border-radius: 4px;
    border: none;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: 0.2s;
}
.btn-add-course:hover { background-color: #2980b9; }

/* --- EXISTING BUTTON --- */
.btn-find-new {
    background-color: #1abc9c;
    color: #fff;
    padding: 8px 18px;
    border-radius: 4px;
    border: none;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: 0.2s;
}
.btn-find-new:hover { background-color: #16a085; }

/* --- PROFILE --- */
.profile-container { position: relative; }
.profile-trigger { display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 5px 10px; border-radius: 30px; transition: background-color 0.2s; }
.profile-trigger:hover { background-color: rgba(255, 255, 255, 0.1); }
.user-greeting { font-size: 14px; color: #ecf0f1; }
.profile-icon-wrap { width: 38px; height: 38px; background-color: #34495e; border: 2px solid #ecf0f1; border-radius: 50%; display: flex; justify-content: center; align-items: center; }
.header-user-icon { width: 20px; height: 20px; color: white; }

.profile-dropdown { display: none; position: absolute; top: 55px; right: 0; background-color: white; min-width: 180px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); border-radius: 6px; overflow: hidden; z-index: 1000; border: 1px solid #eee; animation: slideDown 0.2s ease; }
.profile-dropdown.show { display: block; }
.dropdown-item { display: block; padding: 12px 20px; color: #34495e; font-size: 14px; }
.dropdown-item:hover { background-color: #f8f9fa; color: #3498db; }
.dropdown-item.danger { color: #e74c3c; border-top: 1px solid #eee; }
.dropdown-item.danger:hover { background-color: #fff5f5; }

/* MEDIA */
@keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

@media screen and (max-width: 768px) {
    .user-greeting { display: none; }
    .nav-brand { font-size: 18px; }
}
</style>
</head>

<body>

<nav class="navbar">
    <div class="nav-container">

        <a href="index.php?page=dashboard" class="nav-brand">🇬🇧 English LMS</a>

        <div class="nav-right-group">

            <!-- TOMBOL ADD COURSE hanya jika halaman mengaktifkannya -->
            <?php if (!empty($show_add_course)): ?>
                <a href="index.php?page=catalog" class="btn-find-new">+ Add Course</a>
            <?php endif; ?>

            <div class="profile-container">
                <div class="profile-trigger" onclick="toggleProfileMenu()">
                    <span class="user-greeting">
                        <b><?= htmlspecialchars($_SESSION['full_name'] ?? 'Guest') ?></b>
                    </span>
                    <div class="profile-icon-wrap">
                        <svg class="header-user-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>

                <div id="profileDropdown" class="profile-dropdown">
                    <a href="index.php?page=profile" class="dropdown-item">Edit Profil</a>
                    <a href="index.php?page=logout" class="dropdown-item danger">Logout</a>
                </div>
            </div>
        </div>

    </div>
</nav>

<script>
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
</script>
