<?php
// controllers/StudentController.php
require_once 'models/CourseModel.php';
require_once 'models/QuizModel.php';
require_once 'models/ActivityModel.php';

// --- DASHBOARD SISWA ---
// [PERBAIKAN] Namanya sekarang cuma 'dashboard', cocok dengan index.php
function dashboard($koneksi) {
    $student_id = $_SESSION['user_id'];
    
    // --- LOGIC KELUAR KELAS ---
    if (isset($_GET['unenroll_id'])) {
        $course_id = intval($_GET['unenroll_id']);
        keluarDariKelas($koneksi, $student_id, $course_id);
        header("Location: index.php?page=dashboard&pesan=keluar_sukses");
        exit;
    }

    // 1. Ambil Data Statistik (PENTING BIAR GA ERROR)
    // Pastikan fungsi ambilStatistikSiswa sudah ada di ActivityModel.php
    $stats = ambilStatistikSiswa($koneksi, $student_id);

    // 2. Ambil kelas yang sedang diikuti
    $myClasses = ambilKelasSaya($koneksi, $student_id);
    
    require 'views/student/dashboard.php';
}

// --- KATALOG KELAS ---
function catalog($koneksi) {
    $student_id = $_SESSION['user_id'];

    $allCourses = ambilSemuaCoursePublik($koneksi);
    $myClasses = ambilKelasSaya($koneksi, $student_id);
    
    $sudah_diambil = [];
    while($kelas = mysqli_fetch_assoc($myClasses)) {
        $sudah_diambil[] = $kelas['id']; 
    }
    
    if (isset($_GET['join_id'])) {
        if (!in_array($_GET['join_id'], $sudah_diambil)) {
            gabungKelas($koneksi, $student_id, $_GET['join_id']);
            header("Location: index.php?page=dashboard");
        }
    }
    
    require 'views/student/catalog.php';
}

// --- RUANG BELAJAR ---
function learningRoom($koneksi) {
    $material_id = intval($_GET['material_id']);
    $materi = ambilDetailMateri($koneksi, $material_id);
    
    // Cari Course ID (Code fix sebelumnya)
    $courseInfo = ambilCourseInfoDariMaterial($koneksi, $material_id);
    $course_id = $courseInfo['id']; 
    
    // LOGIC 1: TANDAI SELESAI (SUDAH ADA)
    if (isset($_POST['mark_complete'])) {
        tandaiMateriSelesai($koneksi, $_SESSION['user_id'], $material_id);
        header("Location: index.php?page=learning&material_id=$material_id");
        exit;
    }

    // LOGIC 2: TANDAI BELUM SELESAI / RESET (BARU) 🛠️
    if (isset($_POST['mark_incomplete'])) {
        // Panggil fungsi hapus dari Model
        hapusTandaSelesai($koneksi, $_SESSION['user_id'], $material_id);
        
        // Refresh halaman
        header("Location: index.php?page=learning&material_id=$material_id");
        exit;
    }

    require 'views/student/classroom.php';
}

// --- MENGERJAKAN KUIS ---
function takeQuiz($koneksi) {
    $quiz_id = $_GET['quiz_id'];
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Siswa Submit Jawaban
        $jawabanSiswa = $_POST['jawaban']; 
        $user_id = $_SESSION['user_id'];

        // [LOGIC BARU] RESET NILAI LAMA DULU
        // Agar di transkrip tidak dobel, kita hapus riwayat sebelumnya
        resetRiwayatKuis($koneksi, $user_id, $quiz_id);
        
        // Baru simpan jawaban yang baru (Fresh Start)
        simpanJawabanSiswa($koneksi, $user_id, $quiz_id, $jawabanSiswa);
        
        // Tampilkan halaman hasil
        header("Location: index.php?page=quiz_result&quiz_id=$quiz_id");
    } else {
        // Tampilkan Soal
        $soalList = ambilSoalQuiz($koneksi, $quiz_id);
        require 'views/student/quiz_take.php';
    }
}

// --- DETAIL KELAS ---
function courseDetailStudent($koneksi) {
    require_once 'models/CourseModel.php';
    require_once 'models/ActivityModel.php'; 
    
    // 1. Amankan ID dari URL
    $course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // 2. Coba ambil data kursus dari Database
    $course = ambilCourseById($koneksi, $course_id);

    // 3. [FIX PENTING] Cek apakah kursusnya ketemu?
    if (!$course) {
        // Jika kursus tidak ada (NULL), tendang balik ke Dashboard
        echo "<script>
                alert('Maaf, Kursus ini tidak ditemukan atau sudah dihapus!');
                window.location='index.php?page=dashboard';
              </script>";
        exit;
    }

    // 4. Jika ada, baru lanjut ambil bab & materi
    $chapters = ambilFullCourseStructure($koneksi, $course_id);
    
    require 'views/student/course_detail.php';
}

// --- TRANSKRIP NILAI ---
function studentTranscript($koneksi) {
    require_once 'models/QuizModel.php';
    require_once 'models/CourseModel.php'; 
    
    $course_id = $_GET['course_id'];
    $student_id = $_SESSION['user_id'];
    
    $course = ambilCourseById($koneksi, $course_id);
    $nilaiList = ambilRekapNilaiSiswa($koneksi, $student_id, $course_id);
    
    require 'views/student/transcript.php';
}



?>