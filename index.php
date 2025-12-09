<?php
// index.php

// 1. Mulai sesi
session_start();

// 2. Hubungkan database
require_once 'koneksi.php';

// 3. Ambil parameter URL
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// 4. LOGIKA ROUTING
switch ($page) {
    
    // --- AUTHENTICATION ---
    case 'login':
        require_once 'controllers/AuthController.php';
        login($koneksi);
        break;

    case 'register':
        require_once 'controllers/AuthController.php';
        registerStudent($koneksi);
        break;

    case 'logout':
        require_once 'controllers/AuthController.php';
        logout();
        break;

    // --- DASHBOARD UTAMA ---


    case 'dashboard':
        if (!isset($_SESSION['role'])) {
            header("Location: index.php?page=login");
            exit;
        }
        $role = $_SESSION['role'];
        if ($role == 'admin') {
            require_once 'controllers/AdminController.php';
            dashboardAdmin($koneksi);
        } elseif ($role == 'teacher') {
            require_once 'controllers/TeacherController.php';
            dashboardTeacher($koneksi);
        } else {
            require_once 'controllers/StudentController.php';
            dashboard($koneksi);
        }
        break;

    // --- FITUR ADMIN ---
    case 'manage_teachers':
        require_once 'controllers/AdminController.php';
        manageTeachers($koneksi);
        break;

    case 'manage_schools':
        require_once 'controllers/AdminController.php';
        manageSchools($koneksi);
        break;

    case 'manage_students':
        require_once 'controllers/AdminController.php';
        manageStudents($koneksi);
        break;

    case 'manage_admin':
        require_once 'controllers/AdminController.php';
        manageAdmin($koneksi);
        break;

    // --- FITUR GURU ---
    case 'manage_course':
        require_once 'controllers/TeacherController.php';
        manageCourse($koneksi);
        break;
        
    case 'manage_materials':
        require_once 'controllers/TeacherController.php';
        manageMaterials($koneksi);
        break;

    case 'create_quiz':
        require_once 'controllers/QuizController.php';
        createQuiz($koneksi);
        break;

    case 'edit_quiz':
        require_once 'controllers/QuizController.php';
        editQuiz($koneksi);
        break;

    case 'save_question':
        require_once 'controllers/QuizController.php';
        saveQuestion($koneksi);
        break;

    case 'delete_question':
        require_once 'controllers/QuizController.php';
        deleteQuestion($koneksi);
        break;

    case 'grading_list':
    case 'grade_essay':
        require_once 'controllers/TeacherController.php';
        gradeEssay($koneksi);
        break;

    // --- FITUR SISWA ---
    case 'catalog':
        require_once 'controllers/StudentController.php';
        catalog($koneksi);
        break;

    case 'learning':
        require_once 'controllers/StudentController.php';
        learningRoom($koneksi);
        break;

    case 'take_quiz':
        require_once 'controllers/StudentController.php';
        takeQuiz($koneksi);
        break;
    
    case 'quiz_result': // Tambahan untuk halaman hasil kuis
        require 'views/student/quiz_result.php';
        break;

    // --- LOGIC DETAIL KELAS (PENTING: Gabungan Guru & Siswa) ---
    case 'course_detail':
        // Cek Role User untuk menentukan tampilan
        if ($_SESSION['role'] == 'teacher') {
            // Jika GURU: Masuk ke Mode Edit/Manage (Course Manage)
            require_once 'controllers/TeacherController.php';
            courseDetailTeacher($koneksi);
        } else {
            // Jika SISWA: Masuk ke Mode Belajar (Silabus)
            require_once 'controllers/StudentController.php';
            courseDetailStudent($koneksi);
        }
        break;

    // --- FITUR UMUM ---
    case 'profile':
        require_once 'controllers/ProfileController.php';
        editProfile($koneksi);
        break;

    case 'student_transcript':
        require_once 'controllers/StudentController.php';
        studentTranscript($koneksi);
        break;

    // --- DEFAULT (404) ---
    default:
        echo "<div style='text-align:center; margin-top:50px;'>";
        echo "<h3>404 - Halaman tidak ditemukan!</h3>";
        echo "<p>Maaf, halaman <code>$page</code> tidak ada dalam sistem.</p>";
        echo "<a href='index.php'>Kembali ke Halaman Utama</a>";
        echo "</div>";
        break;
}
?>